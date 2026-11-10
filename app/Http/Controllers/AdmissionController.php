<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\SchoolContext;
use App\Services\StudentImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdmissionController extends Controller
{
    public function form()
    {
        return view('school.admission-public');
    }

    public function store(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:255', 'parent_name' => 'required|string|max:255', 'email' => 'required|email|max:255', 'phone' => 'required|string|max:20', 'previous_school' => 'required|string|max:255', 'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', 'consent' => 'accepted']);
        unset($d['document'],$d['consent']);
        $d['email'] = strtolower($d['email']);
        $d['reference'] = (string) Str::uuid();
        if ($r->hasFile('document')) {
            $d['document'] = $r->file('document')->store('ppdb', 'local');
        }
        try {
            $a = Admission::create($d);
        } catch (\Throwable $e) {
            if (isset($d['document'])) {
                Storage::disk('local')->delete($d['document']);
            }throw $e;
        }

        return redirect()->route('ppdb.form')->with('reference', $a->reference);
    }

    public function statusForm()
    {
        return view('school.admission-public', ['tracking' => true]);
    }

    public function status(Request $r)
    {
        $d = $r->validate(['reference' => 'required|uuid', 'email' => 'required|email']);
        $a = Admission::where('reference', $d['reference'])->where('email', strtolower($d['email']))->first();

        return response()->view('school.admission-public', ['tracking' => true, 'result' => $a?->status ?? 'Data tidak ditemukan. Periksa nomor dan email.'])->header('Cache-Control', 'no-store');
    }

    public function index()
    {
        return view('school.page', ['title' => 'Pendaftaran PPDB', 'body' => 'school.admissions', 'admissions' => Admission::latest()->paginate(15), 'classes' => Kelas::all()]);
    }

    public function review(Request $r, Admission $admission)
    {
        $d = $r->validate(['status' => 'required|in:verified,accepted,rejected', 'note' => 'required|string|max:2000']);
        DB::transaction(function () use ($d, $admission) {
            $a = Admission::lockForUpdate()->findOrFail($admission->id);
            abort_if($a->siswa_id, 409, 'Pendaftar sudah menjadi siswa.');
            $a->update($d);
            SchoolContext::audit('admission.reviewed', 'admission:'.$a->id, ['status' => $d['status']]);
        });

        return back()->with('success', 'Status pendaftaran diperbarui.');
    }

    public function document(Admission $admission)
    {
        abort_unless($admission->document, 404);

        return Storage::disk('local')->download($admission->document, null, ['Cache-Control' => 'private, no-store']);
    }

    public function convert(Request $r, Admission $admission, StudentImport $service)
    {
        $d = $r->validate(['nis' => 'required|string|max:30', 'email_siswa' => 'required|email', 'jenis_kelamin' => 'required|in:L,P', 'kelas' => 'required|string', 'tahun_masuk' => 'required|digits:4']);
        $credentials = DB::transaction(function () use ($d, $admission, $service) {
            $a = Admission::lockForUpdate()->findOrFail($admission->id);
            abort_unless($a->status === 'accepted' && ! $a->siswa_id, 409);
            $creds = $service->createRows([$d + ['nama_siswa' => $a->name, 'nama_ortu' => $a->parent_name, 'email_ortu' => $a->email, 'no_hp_ortu' => $a->phone]]);
            $s = Siswa::where('nis', $d['nis'])->firstOrFail();
            $a->update(['siswa_id' => $s->id]);
            SchoolContext::audit('admission.converted', 'admission:'.$a->id, ['siswa_id' => $s->id]);

            return $creds;
        });

        return response()->streamDownload(function () use ($credentials) {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['email', 'password_sementara', 'role'], ',', '"', '');
            foreach ($credentials as $c) {
                fputcsv($f, array_map([StudentImport::class, 'csvCell'], array_values($c)), ',', '"', '');
            }fclose($f);
        }, 'akun-ppdb.csv', ['Content-Type' => 'text/csv', 'Cache-Control' => 'no-store']);
    }
}
