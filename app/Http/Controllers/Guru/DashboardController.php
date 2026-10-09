<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Nilai;
use App\Services\SchoolContext;

class DashboardController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $jadwalHariIni = Jadwal::where(SchoolContext::period())->with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', now()->locale('id')->dayName)
            ->orderBy('jam_mulai')->get();

        $hariMap = ['Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'];
        $hariIni = $hariMap[now()->format('l')];

        $jadwalHariIni = Jadwal::where(SchoolContext::period())->with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->where('hari', $hariIni)
            ->orderBy('jam_mulai')->get();

        $totalAbsensiInput = Absensi::where('guru_id', $guru->id)->whereDate('tanggal', today())->count();
        $totalMateri = Materi::where('guru_id', $guru->id)->count();
        $totalNilai = Nilai::where('guru_id', $guru->id)->count();

        return view('guru.dashboard', compact('guru', 'jadwalHariIni', 'totalAbsensiInput', 'totalMateri', 'totalNilai'));
    }
}
