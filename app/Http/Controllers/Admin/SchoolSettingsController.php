<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{GradeScheme, MataPelajaran, Nilai, SchoolAudit, SchoolSetting};
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SchoolSettingsController extends Controller
{
    public function index()
    {
        return view('school.page', ['title' => 'Pengaturan Sekolah & Penilaian', 'body' => 'school.settings', 'settings' => SchoolSetting::current(), 'subjects' => MataPelajaran::orderBy('nama_mapel')->get(), 'schemes' => GradeScheme::with('mataPelajaran')->latest()->get(), 'period' => SchoolContext::period()]);
    }

    public function update(Request $r)
    {
        $d = $r->validate(['name' => 'required|string|max:150', 'type' => 'required|in:SMA,SMK', 'tagline' => 'required|string|max:200', 'study_years' => 'required|integer|in:3,4', 'address' => 'nullable|string|max:2000', 'phone' => 'nullable|string|max:30', 'email' => 'nullable|email|max:255', 'admissions_year' => ['required', 'regex:/^\d{4}\/\d{4}$/'], 'admissions_start' => 'nullable|date_format:Y-m-d', 'admissions_end' => 'nullable|date_format:Y-m-d|after_or_equal:admissions_start', 'admissions_open' => 'sometimes|boolean', 'show_demo' => 'sometimes|boolean']);
        [$first, $last] = explode('/', $d['admissions_year']);
        if ((int)$last !== (int)$first + 1 || ($d['type'] === 'SMA' && (int)$d['study_years'] !== 3)) throw ValidationException::withMessages(['settings' => 'Periksa tahun ajaran dan lama studi. SMA menggunakan tiga tahun.']);
        $d['admissions_open'] = $r->boolean('admissions_open');
        $d['show_demo'] = $r->boolean('show_demo');
        DB::transaction(function () use ($d) {
            SchoolSetting::updateOrCreate(['id' => 1], ['data' => $d]);
            SchoolContext::audit('school.settings.updated', 'school:1');
        });
        return back()->with('success', 'Pengaturan sekolah diperbarui.');
    }

    public function scheme(Request $r)
    {
        $d = $r->validate(['mata_pelajaran_id' => 'required|exists:mata_pelajaran,id', 'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'], 'semester' => 'required|in:1,2', 'harian' => 'required|integer|between:0,100', 'uts' => 'required|integer|between:0,100', 'uas' => 'required|integer|between:0,100']);
        $weights = collect($d)->only(['harian', 'uts', 'uas'])->map(fn ($v) => (int)$v)->all();
        [$first, $last] = explode('/', $d['tahun_ajaran']);
        if (array_sum($weights) !== 100 || (int)$last !== (int)$first + 1) throw ValidationException::withMessages(['weights' => 'Total bobot harus 100% dan tahun ajaran berurutan.']);
        $key = collect($d)->only(['mata_pelajaran_id', 'tahun_ajaran', 'semester'])->all();
        DB::transaction(function () use ($key, $weights) {
            MataPelajaran::whereKey($key['mata_pelajaran_id'])->lockForUpdate()->firstOrFail();
            if (Nilai::where($key)->exists()) throw ValidationException::withMessages(['weights' => 'Nilai periode ini sudah diinput. Atur bobot sebelum pengisian nilai atau gunakan periode berikutnya.']);
            GradeScheme::updateOrCreate($key, ['weights' => $weights]);
            SchoolContext::audit('grade.scheme.updated', 'mapel:'.$key['mata_pelajaran_id'], $key + ['weights' => $weights]);
        });
        return back()->with('success', 'Bobot penilaian disimpan.');
    }

    public function audit()
    {
        return view('school.page', ['title' => 'Riwayat Aktivitas', 'body' => 'school.audit', 'audits' => SchoolAudit::with('user')->latest()->paginate(30)]);
    }
}
