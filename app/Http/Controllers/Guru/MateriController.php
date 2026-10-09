<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Services\TeachingAccess;
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $materi = Materi::with(['mataPelajaran', 'kelas'])
            ->where('guru_id', $guru->id)->latest()->paginate(15);

        return view('guru.materi.index', compact('materi'));
    }

    public function create()
    {
        $mapel = MataPelajaran::all();
        $kelas = Kelas::all();

        return view('guru.materi.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => 'required',
            'semester' => 'required',
            'kelas_id' => 'required|exists:kelas,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,txt,jpg,jpeg,png|max:10240',
        ]);

        $guru = auth()->user()->guru;
        TeachingAccess::kelas((int) $request->kelas_id, (int) $request->mata_pelajaran_id, $request->tahun_ajaran, $request->semester);
        $data = $request->only(['judul', 'deskripsi', 'kelas_id', 'mata_pelajaran_id', 'tahun_ajaran', 'semester']);
        $data['guru_id'] = $guru->id;

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materi', 'local');
            $data['tipe_file'] = $request->file('file')->getClientOriginalExtension();
        }

        Materi::create($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupload.');
    }

    public function destroy(Materi $materi)
    {
        abort_unless((int) $materi->guru_id === (int) auth()->user()->guru?->id, 403);
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
