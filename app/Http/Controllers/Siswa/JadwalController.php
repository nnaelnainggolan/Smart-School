<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $jadwal = Jadwal::with(['mataPelajaran','guru.user'])
            ->where('kelas_id', $siswa->kelas_id)
            ->orderBy('hari')->orderBy('jam_mulai')
            ->get()
            ->groupBy('hari');

        $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
        return view('siswa.jadwal.index', compact('jadwal', 'urutanHari'));
    }
}
