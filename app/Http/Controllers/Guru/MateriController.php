<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Materi, MataPelajaran, Kelas};
use Illuminate\Http\Request;

class MateriController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $materi = Materi::with(['mataPelajaran','kelas'])
            ->where('guru_id', $guru->id)->latest()->paginate(15);
        return view('guru.materi.index', compact('materi'));
    }

    public function create()
    {
        $mapel = MataPelajaran::all();
        $kelas = Kelas::all();
        return view('guru.materi.create', compact('mapel','kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => 'required',
            'semester' => 'required',
            'file' => 'nullable|file|max:10240',
        ]);

        $guru = auth()->user()->guru;
        $data = $request->except('file');
        $data['guru_id'] = $guru->id;

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('materi', 'public');
            $data['tipe_file'] = $request->file('file')->getClientOriginalExtension();
        }

        Materi::create($data);
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupload.');
    }

    public function destroy(Materi $materi)
    {
        $materi->delete();
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
