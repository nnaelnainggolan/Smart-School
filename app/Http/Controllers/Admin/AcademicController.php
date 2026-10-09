<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AcademicController extends Controller
{
    public function index()
    {
        return view('school.page', ['title' => 'Periode & Kelas', 'body' => 'school.academic', 'periods' => AcademicPeriod::orderByDesc('tahun_ajaran')->get(), 'classes' => Kelas::all(), 'teachers' => Guru::whereHas('user', fn ($q) => $q->where('role', 'guru'))->with('user')->get()]);
    }

    public function period(Request $r)
    {
        $d = $r->validate(['tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'], 'semester' => 'required|in:1,2']);
        [$start,$end] = explode('/', $d['tahun_ajaran']);
        if ((int) $end !== (int) $start + 1) {
            throw ValidationException::withMessages(['tahun_ajaran' => 'Tahun akhir harus satu tahun setelah tahun awal.']);
        }
        DB::transaction(function () use ($d) {
            // Serialize activation even before the first academic period exists.
            User::where('role', 'admin')->orderBy('id')->lockForUpdate()->firstOrFail();
            AcademicPeriod::query()->lockForUpdate()->get();
            AcademicPeriod::query()->update(['active' => false]);
            AcademicPeriod::updateOrCreate($d, ['active' => true]);
            SchoolContext::audit('period.activated', $d['tahun_ajaran'].':'.$d['semester']);
        });

        return back()->with('success', 'Periode aktif diperbarui. Periksa jadwal dan daftar kelas untuk periode ini.');
    }

    public function teacher(Request $r)
    {
        $d = $r->validate(['kelas_id' => 'required|exists:kelas,id', 'guru_id' => 'required|exists:guru,id']);
        $g = Guru::with('user')->findOrFail($d['guru_id']);
        abort_unless($g->user->role === 'guru', 422);
        Kelas::findOrFail($d['kelas_id'])->update(['wali_guru_id' => $g->id, 'wali_kelas' => $g->user->name]);
        SchoolContext::audit('homeroom.assigned', 'kelas:'.$d['kelas_id'], ['guru_id' => $g->id]);

        return back()->with('success', 'Wali kelas ditetapkan.');
    }

    public function promote(Request $r)
    {
        $d = $r->validate(['source' => 'required|exists:kelas,id', 'target' => 'required|exists:kelas,id|different:source', 'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'], 'confirm' => 'accepted']);
        [$first,$last] = explode('/', $d['tahun_ajaran']);
        if ((int) $last !== (int) $first + 1) {
            throw ValidationException::withMessages(['tahun_ajaran' => 'Format tahun ajaran tidak valid.']);
        }
        DB::transaction(function () use ($d) {
            Kelas::whereIn('id', [$d['source'], $d['target']])->orderBy('id')->lockForUpdate()->get();
            $students = Siswa::where('kelas_id', $d['source'])->where('status', 'aktif')->lockForUpdate()->get();
            if ($students->isEmpty()) {
                throw ValidationException::withMessages(['source' => 'Kelas asal tidak memiliki siswa aktif.']);
            }
            $target = Kelas::findOrFail($d['target']);
            if ($target->siswa()->where('status', 'aktif')->count() + $students->count() > $target->kapasitas) {
                throw ValidationException::withMessages(['target' => 'Kapasitas tujuan tidak mencukupi.']);
            }
            $latest = Enrollment::whereIn('siswa_id', $students->modelKeys())->max('tahun_ajaran');
            if ($latest && $d['tahun_ajaran'] <= $latest) {
                throw ValidationException::withMessages(['tahun_ajaran' => 'Tahun tujuan harus setelah riwayat tahun terakhir siswa.']);
            }
            foreach ($students as $s) {
                Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $target->id, 'tahun_ajaran' => $d['tahun_ajaran']]);
                $s->update(['kelas_id' => $target->id]);
            }
            SchoolContext::audit('class.promoted', 'kelas:'.$d['source'], ['target' => $target->id, 'year' => $d['tahun_ajaran'], 'students' => $students->modelKeys()]);
        });

        return back()->with('success', 'Kenaikan kelas selesai; riwayat kelas sebelumnya tetap tersimpan.');
    }

    public function graduate(Request $r)
    {
        $d = $r->validate(['kelas_id' => 'required|exists:kelas,id', 'confirm' => 'accepted']);
        DB::transaction(function () use ($d) {
            $kelas = Kelas::lockForUpdate()->findOrFail($d['kelas_id']);
            if ($kelas->tingkat !== 'XII') {
                throw ValidationException::withMessages(['kelas_id' => 'Kelulusan massal hanya untuk kelas XII.']);
            }
            $ids = $kelas->siswa()->where('status', 'aktif')->pluck('id');
            Siswa::whereIn('id', $ids)->update(['status' => 'lulus']);
            SchoolContext::audit('class.graduated', 'kelas:'.$kelas->id, ['students' => $ids->all()]);
        });

        return back()->with('success', 'Siswa ditandai lulus. Akun dan riwayat tetap tersedia.');
    }
}
