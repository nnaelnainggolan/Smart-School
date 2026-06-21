<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);

        $absensi = Absensi::with(['jadwal.mataPelajaran'])
            ->where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')->get();

        $rekap = $absensi->groupBy('status')->map->count();
        return view('siswa.absensi.index', compact('absensi','rekap','bulan','tahun'));
    }
}
