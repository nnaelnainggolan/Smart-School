<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolImport;
use App\Services\SchoolContext;
use App\Services\StudentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ImportController extends Controller
{
    public function index()
    {
        return view('school.page', ['title' => 'Impor Siswa & Orang Tua', 'description' => 'CSV UTF-8 dari Excel, maksimal 1000 siswa. Akun lama tidak ditimpa.', 'body' => 'school.import']);
    }

    public function template()
    {
        return response()->streamDownload(function () {
            $f = fopen('php://output', 'w');
            fwrite($f, "\xEF\xBB\xBF");
            fputcsv($f, StudentImport::COLUMNS, ',', '"', '');
            fclose($f);
        }, 'template-siswa.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function preview(Request $request, StudentImport $service)
    {
        $request->validate(['file' => 'required|file|max:2048']);
        $contents = file_get_contents($request->file('file')->getRealPath());
        if (! $contents || ! mb_check_encoding($contents, 'UTF-8')) {
            throw ValidationException::withMessages(['file' => 'File kosong atau bukan CSV UTF-8.']);
        }
        $f = fopen($request->file('file')->getRealPath(), 'r');
        $first = fgets($f);
        rewind($f);
        $separator = substr_count($first, ';') > substr_count($first, ',') ? ';' : ',';
        $header = fgetcsv($f, 0, $separator, '"', '');
        if ($header) {
            $header[0] = ltrim($header[0], "\xEF\xBB\xBF");
        }
        if ($header !== StudentImport::COLUMNS) {
            fclose($f);
            throw ValidationException::withMessages(['file' => 'Kolom tidak sesuai template CSV.']);
        }
        $rows = [];
        while (($values = fgetcsv($f, 0, $separator, '"', '')) !== false) {
            if ($values === [null]) {
                continue;
            }
            if (count($values) !== count($header) || count($rows) >= 1000) {
                fclose($f);
                throw ValidationException::withMessages(['file' => 'Jumlah kolom tidak sesuai atau lebih dari 1000 baris.']);
            }
            $rows[] = array_combine($header, array_map('trim', $values));
        }
        fclose($f);
        $service->validateRows($rows);
        SchoolImport::where('created_at', '<', now()->subDay())->delete();
        $batch = SchoolImport::create(['user_id' => $request->user()->id, 'payload' => $rows]);

        return view('school.page', ['title' => 'Pratinjau Impor', 'body' => 'school.import', 'batch' => $batch, 'rows' => $rows]);
    }

    public function commit(Request $request, SchoolImport $batch, StudentImport $service)
    {
        $credentials = DB::transaction(function () use ($request, $batch, $service) {
            $batch = SchoolImport::lockForUpdate()->findOrFail($batch->id);
            abort_unless($batch->user_id === $request->user()->id && ! $batch->consumed_at && $batch->created_at->gt(now()->subMinutes(30)), 409, 'Pratinjau kedaluwarsa atau sudah diproses.');
            $credentials = $service->createRows($batch->payload);
            $count = count($batch->payload);
            $batch->update(['consumed_at' => now(), 'payload' => []]);
            SchoolContext::audit('import.created', 'batch:'.$batch->id, ['students' => $count]);

            return $credentials;
        });

        return response()->streamDownload(function () use ($credentials) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['email', 'password_sementara', 'role'], ',', '"', '');
            foreach ($credentials as $c) {
                fputcsv($f, array_map([StudentImport::class, 'csvCell'], array_values($c)), ',', '"', '');
            }fclose($f);
        }, 'akun-baru.csv', ['Content-Type' => 'text/csv', 'Cache-Control' => 'no-store']);
    }
}
