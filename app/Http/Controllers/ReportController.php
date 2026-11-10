<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Nilai;
use App\Models\ReportPublication;
use App\Models\Siswa;
use App\Services\HomeroomAccess;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    private function query()
    {
        $q = ReportPublication::with('siswa.user');
        $u = auth()->user();
        if ($u->role === 'siswa') {
            $q->where('siswa_id', $u->siswa?->id ?? 0)->whereNotNull('published_at');
        } elseif ($u->role === 'orang_tua') {
            $q->whereIn('siswa_id', $u->orangTua?->siswa()->pluck('id') ?? [])->whereNotNull('published_at');
        } elseif ($u->role === 'guru') {
            $q->whereIn('siswa_id', Siswa::whereIn('kelas_id', HomeroomAccess::classes()->pluck('id'))->pluck('id'));
        } else {
            abort_unless($u->role === 'admin', 403);
        }

        return $q;
    }

    public function index()
    {
        $students = in_array(auth()->user()->role, ['admin', 'guru']) ? Siswa::with('user')->whereIn('kelas_id', HomeroomAccess::classes()->pluck('id'))->where('status', 'aktif')->get() : collect();

        return view('school.page', ['title' => 'Pengesahan & Rapor Terbit', 'body' => 'school.reports', 'reports' => $this->query()->latest()->paginate(20), 'students' => $students, 'period' => SchoolContext::period()]);
    }

    public function publish(Request $r)
    {
        abort_unless(in_array(auth()->user()->role, ['admin', 'guru']), 403);
        $d = $r->validate(['siswa_id' => 'required|exists:siswa,id', 'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'], 'semester' => 'required|in:1,2', 'confirm' => 'accepted']);
        $s = Siswa::with(['user', 'kelas'])->findOrFail($d['siswa_id']);
        HomeroomAccess::student($s);
        DB::transaction(function () use ($d, $s) {
            $key = collect($d)->only(['siswa_id', 'tahun_ajaran', 'semester'])->all();
            $pub = ReportPublication::firstOrCreate($key, ['snapshot' => []]);
            $pub = ReportPublication::lockForUpdate()->findOrFail($pub->id);
            abort_if($pub->published_at, 409, 'Rapor sudah diterbitkan. Buka revisi terlebih dahulu.');
            $enrollment = Enrollment::where('siswa_id', $s->id)->where('tahun_ajaran', $d['tahun_ajaran'])->first();
            if (! $enrollment) {
                throw ValidationException::withMessages(['rapor' => 'Riwayat kelas pada tahun ini belum tersedia.']);
            }
            $ids = Jadwal::where('kelas_id', $enrollment->kelas_id)->where('tahun_ajaran', $d['tahun_ajaran'])->where('semester', $d['semester'])->pluck('mata_pelajaran_id')->unique();
            $grades = Nilai::with('mataPelajaran')->where('siswa_id', $s->id)->where('tahun_ajaran', $d['tahun_ajaran'])->where('semester', $d['semester'])->whereIn('mata_pelajaran_id', $ids)->get();
            if ($ids->isEmpty() || $grades->count() !== $ids->count() || $grades->contains(fn ($g) => $g->nilai_akhir === null)) {
                throw ValidationException::withMessages(['rapor' => 'Nilai seluruh mata pelajaran pada jadwal harus lengkap sebelum diterbitkan.']);
            }
            $snapshot = ['name' => $s->user->name, 'nis' => $s->nis, 'kelas' => Kelas::findOrFail($enrollment->kelas_id)->nama_kelas, 'grades' => $grades->map(fn ($g) => ['mapel' => $g->mataPelajaran->nama_mapel, 'harian' => $g->nilai_harian, 'uts' => $g->nilai_uts, 'uas' => $g->nilai_uas, 'akhir' => $g->nilai_akhir, 'predikat' => $g->predikat, 'kkm' => $g->mataPelajaran->kkm])->all()];
            $pub->update(['snapshot' => $snapshot, 'published_at' => now(), 'published_by' => auth()->id(), 'revision' => $pub->revision + 1]);
            SchoolContext::audit('report.published', 'report:'.$pub->id, ['revision' => $pub->revision, 'snapshot' => $snapshot]);
        });

        return back()->with('success', 'Rapor diterbitkan. Nilai pada periode ini terkunci.');
    }

    public function reopen(Request $r, ReportPublication $report)
    {
        abort_unless(auth()->user()->role === 'admin', 403);
        $d = $r->validate(['reason' => 'required|string|max:1000']);
        DB::transaction(function () use ($report, $d) {
            $p = ReportPublication::lockForUpdate()->findOrFail($report->id);
            SchoolContext::audit('report.reopened', 'report:'.$p->id, ['reason' => $d['reason'], 'revision' => $p->revision]);
            $p->update(['published_at' => null]);
        });

        return back()->with('success', 'Revisi dibuka. Terbitkan ulang setelah diperiksa.');
    }

    public function show(ReportPublication $report)
    {
        $this->query()->findOrFail($report->id);
        abort_unless($report->published_at, 404);

        return response()->view('school.report-print', ['report' => $report])->header('Cache-Control', 'private, no-store');
    }
}
