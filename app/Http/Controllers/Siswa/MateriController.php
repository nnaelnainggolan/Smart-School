<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $query = Materi::with(['mataPelajaran','guru.user'])
            ->where(function($q) use ($siswa) {
                $q->where('kelas_id', $siswa->kelas_id)
                  ->orWhereNull('kelas_id');
            });

        if ($request->mapel_id) {
            $query->where('mata_pelajaran_id', $request->mapel_id);
        }

        $materi = $query->latest()->paginate(12);
        $mapelList = \App\Models\MataPelajaran::all();

        return view('siswa.materi.index', compact('materi', 'mapelList'));
    }
}
