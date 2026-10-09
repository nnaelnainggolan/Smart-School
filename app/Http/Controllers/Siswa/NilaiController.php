<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Nilai;
use App\Services\SchoolContext;
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $tahunAjaran = $request->get('tahun_ajaran', SchoolContext::period()['tahun_ajaran']);
        $semester = $request->get('semester', SchoolContext::period()['semester']);

        $nilai = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)->get();

        $rataRata = $nilai->avg('nilai_akhir');

        return view('siswa.nilai.index', compact('nilai', 'rataRata', 'tahunAjaran', 'semester'));
    }
}
