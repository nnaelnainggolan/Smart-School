<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Enrollment;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Konseling;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\User;
use App\Services\SchoolContext;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran', SchoolContext::period()['tahun_ajaran']);
        $semester = $request->get('semester', SchoolContext::period()['semester']);

        // Statistik umum
        $stats = [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::whereHas('user', fn ($q) => $q->where('role', 'guru'))->count(),
            'total_guru_bk' => Guru::whereHas('user', fn ($q) => $q->where('role', 'guru_bk'))->count(),
            'total_kelas' => Kelas::count(),
            'total_orang_tua' => User::where('role', 'orang_tua')->count(),
        ];

        // Rata-rata nilai per kelas
        $rataNilaiPerKelas = Kelas::with(['enrollments' => fn ($q) => $q->where('tahun_ajaran', $tahunAjaran), 'enrollments.siswa.nilai' => fn ($q) => $q->where('tahun_ajaran', $tahunAjaran)->where('semester', $semester)])
            ->get()->map(function ($kelas) {
                $students = $kelas->enrollments->pluck('siswa')->filter();
                $grades = $students->flatMap(fn ($s) => $s->nilai)->pluck('nilai_akhir')->filter(fn ($v) => $v !== null);

                return ['nama_kelas' => $kelas->nama_kelas, 'rata_rata' => $grades->count() ? round($grades->avg(), 2) : 0, 'jumlah_siswa' => $students->count()];
            });

        // Rekap absensi keseluruhan bulan ini
        $rekapAbsensiBulanan = Absensi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')->get()->pluck('total', 'status');

        // Konseling per status
        $konselingPerStatus = Konseling::selectRaw('status, count(*) as total')
            ->groupBy('status')->get()->pluck('total', 'status');

        // Konseling per jenis
        $konselingPerJenis = Konseling::selectRaw('jenis, count(*) as total')
            ->groupBy('jenis')->get()->pluck('total', 'jenis');

        // Siswa dengan absensi alpha terbanyak (perlu perhatian)
        $siswaAlphaTerbanyak = Siswa::with('user', 'kelas')
            ->withCount(['absensi' => function ($q) {
                $q->where('status', 'Alpha')
                    ->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
            }])
            ->orderByDesc('absensi_count')
            ->get()
            ->filter(function ($siswa) {
                return $siswa->absensi_count > 0;
            })
            ->take(5);

        return view('admin.laporan.index', compact(
            'stats', 'rataNilaiPerKelas', 'rekapAbsensiBulanan',
            'konselingPerStatus', 'konselingPerJenis', 'siswaAlphaTerbanyak',
            'tahunAjaran', 'semester'
        ));
    }

    public function cetakRapor(Request $request)
    {
        $request->validate(['kelas_id' => 'required|exists:kelas,id']);
        $kelas = Kelas::with(['siswa.user', 'siswa.nilai.mataPelajaran'])->find($request->kelas_id);
        $tahunAjaran = $request->get('tahun_ajaran', SchoolContext::period()['tahun_ajaran']);
        $semester = $request->get('semester', SchoolContext::period()['semester']);

        $members = Enrollment::where('kelas_id', $kelas->id)->where('tahun_ajaran', $tahunAjaran)->with(['siswa.user', 'siswa.orangTua', 'siswa.nilai.mataPelajaran'])->get();
        $kelas->setRelation('siswa', $members->pluck('siswa')->filter());

        return view('admin.laporan.cetak_rapor', compact('kelas', 'tahunAjaran', 'semester'));
    }
}
