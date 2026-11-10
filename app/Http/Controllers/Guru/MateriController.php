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
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:10000',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajaran,id',
            'kelas_id' => 'nullable|integer|exists:kelas,id',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,jpeg,png|max:10240',
        ]);

        $guru = auth()->user()->guru;
        abort_unless($guru, 403);
        $permitted = \App\Models\Jadwal::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $data['mata_pelajaran_id'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])
            ->when(!empty($data['kelas_id']), fn ($q) => $q->where('kelas_id', $data['kelas_id']))
            ->exists();
        abort_unless($permitted, 403);

        $materiData = $request->only([
            'judul', 'deskripsi', 'mata_pelajaran_id', 'kelas_id', 'tahun_ajaran', 'semester',
        ]);
        $materiData['guru_id'] = $guru->id;

        if ($request->hasFile('file')) {
            $materiData['file_path'] = $request->file('file')->store('materi', 'public');
            $materiData['tipe_file'] = $request->file('file')->extension();
        }

        try {
            Materi::create($materiData);
        } catch (\Throwable $e) {
            if (!empty($materiData['file_path'])) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($materiData['file_path']);
            }
            throw $e;
        }

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diupload.');
    }

    public function destroy(Materi $materi)
    {
        abort_unless((int) $materi->guru_id === (int) auth()->user()->guru?->id, 403);
        $path = $materi->file_path;
        $materi->delete();
        if ($path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }
        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }

}
