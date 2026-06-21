<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Siswa, Guru, Kelas, Nilai, Absensi, Konseling, User};
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAjaran = $request->get('tahun_ajaran', '2024/2025');
        $semester = $request->get('semester', '1');

        // Statistik umum
        $stats = [
            'total_siswa' => Siswa::where('status', 'aktif')->count(),
            'total_guru' => Guru::whereHas('user', fn($q) => $q->where('role', 'guru'))->count(),
            'total_guru_bk' => Guru::whereHas('user', fn($q) => $q->where('role', 'guru_bk'))->count(),
            'total_kelas' => Kelas::count(),
            'total_orang_tua' => User::where('role', 'orang_tua')->count(),
        ];

        // Rata-rata nilai per kelas
        $rataNilaiPerKelas = Kelas::with(['siswa.nilai' => function($q) use ($tahunAjaran, $semester) {
                $q->where('tahun_ajaran', $tahunAjaran)->where('semester', $semester);
            }])
            ->get()
            ->map(function($kelas) {
                $semuaNilai = $kelas->siswa->flatMap(fn($s) => $s->nilai)->pluck('nilai_akhir')->filter();
                return [
                    'nama_kelas' => $kelas->nama_kelas,
                    'rata_rata' => $semuaNilai->count() ? round($semuaNilai->avg(), 2) : 0,
                    'jumlah_siswa' => $kelas->siswa->count(),
                ];
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
          ->whereMonth('tanggal', now()->month);
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
        $tahunAjaran = $request->get('tahun_ajaran', '2024/2025');
        $semester = $request->get('semester', '1');

        return view('admin.laporan.cetak_rapor', compact('kelas', 'tahunAjaran', 'semester'));
    }
}
