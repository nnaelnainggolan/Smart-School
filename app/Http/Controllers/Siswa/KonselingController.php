<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\ChatKonseling;
use App\Models\Guru;
use App\Models\Konseling;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class KonselingController extends Controller
{
    public function index()
    {
        $siswa = auth()->user()->siswa;
        $konseling = Konseling::with('guruBK.user')
            ->where('siswa_id', $siswa->id)->latest()->paginate(10);

        return view('siswa.konseling.index', compact('konseling'));
    }

    public function create()
    {
        return view('siswa.konseling.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'topik' => 'required|string|max:255',
            'deskripsi' => 'required',
            'jenis' => 'required|in:akademik,pribadi,sosial,karir',
        ]);

        $siswa = auth()->user()->siswa;
        $konseling = Konseling::create([
            'siswa_id' => $siswa->id,
            'topik' => $request->topik,
            'deskripsi' => $request->deskripsi,
            'jenis' => $request->jenis,
            'status' => 'pending',
        ]);

        // Notifikasi ke semua guru BK
        $guruBK = Guru::whereHas('user', fn ($q) => $q->where('role', 'guru_bk'))->with('user')->get();
        foreach ($guruBK as $bk) {
            Notifikasi::create([
                'user_id' => $bk->user->id,
                'judul' => 'Permintaan Konseling Baru',
                'pesan' => "{$siswa->user->name} mengajukan konseling: {$request->topik}",
                'tipe' => 'info',
                'url' => route('guru_bk.konseling.show', $konseling->id),
            ]);
        }

        return redirect()->route('siswa.konseling.show', $konseling->id)->with('success', 'Permintaan konseling berhasil dikirim.');
    }

    public function show(Konseling $konseling)
    {
        $siswa = auth()->user()->siswa;
        abort_if($konseling->siswa_id !== $siswa->id, 403);
        $konseling->load(['guruBK.user', 'chat.user']);

        return view('siswa.konseling.show', compact('konseling'));
    }

    public function sendChat(Request $request, Konseling $konseling)
    {
        abort_unless(in_array($konseling->status, ['pending', 'disetujui', 'berlangsung']), 422);
        $request->validate(['pesan' => 'required|string|max:5000']);
        $siswa = auth()->user()->siswa;
        abort_if($konseling->siswa_id !== $siswa->id, 403);

        ChatKonseling::create([
            'konseling_id' => $konseling->id,
            'user_id' => auth()->id(),
            'pesan' => $request->pesan,
        ]);

        return back();
    }
}
