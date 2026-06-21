<?php
namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\{Nilai, Absensi, Konseling, PesanGuruOrtu, Notifikasi};

class DashboardController extends Controller
{
    public function index()
    {
        $orangTua = auth()->user()->orangTua;
        if (!$orangTua) return redirect()->route('login')->with('error','Data orang tua tidak ditemukan.');

        $siswa = $orangTua->siswa()->with(['user','kelas'])->first();
        if (!$siswa) return view('orang_tua.dashboard', ['siswa' => null]);

        $nilaiTerbaru = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)->latest()->take(6)->get();

        $rekapAbsensi = Absensi::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', now()->month)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')->get()->pluck('total','status');

        $konselingAktif = Konseling::where('siswa_id', $siswa->id)
            ->whereIn('status',['pending','disetujui','berlangsung'])->count();

        $pesanBaru = PesanGuruOrtu::where('penerima_id', auth()->id())->where('dibaca', false)->count();

        return view('orang_tua.dashboard', compact('siswa','nilaiTerbaru','rekapAbsensi','konselingAktif','pesanBaru'));
    }
}
