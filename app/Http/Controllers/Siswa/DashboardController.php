<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Berita;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Nilai;
use App\Services\SchoolContext;

class DashboardController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        if (! $siswa) {
            return redirect()->route('login')->with('error', 'Data siswa tidak ditemukan.');
        }

        $hariMap = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hariIni = $hariMap[now()->format('l')];

        $jadwalHariIni = Jadwal::where(SchoolContext::period())->with(['mataPelajaran', 'guru.user'])
            ->where('kelas_id', $siswa->kelas_id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')->get();

        $nilaiTerbaru = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)->latest()->take(5)->get();

        $totalAbsensiAlpha = Absensi::where('siswa_id', $siswa->id)
            ->where('status', 'Alpha')->count();

        $materiTerbaru = Materi::with(['mataPelajaran', 'guru.user'])
            ->whereHas('kelas', fn ($q) => $q->where('id', $siswa->kelas_id))
            ->latest()->take(5)->get();

        $berita = Berita::where('is_published', true)->latest('published_at')->take(3)->get();

        return view('siswa.dashboard', compact('siswa', 'jadwalHariIni', 'nilaiTerbaru', 'totalAbsensiAlpha', 'materiTerbaru', 'berita'));
    }
}
