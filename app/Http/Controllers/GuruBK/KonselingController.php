<?php
namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\{Konseling, ChatKonseling, Notifikasi};
use Illuminate\Http\Request;

class KonselingController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $query = Konseling::with(['siswa.user']);
        if ($request->status) $query->where('status', $request->status);
        $konseling = $query->latest()->paginate(15);
        return view('guru_bk.konseling.index', compact('konseling'));
    }

    public function show(Konseling $konseling)
    {
        $konseling->load(['siswa.user','guruBK.user','chat.user']);
        return view('guru_bk.konseling.show', compact('konseling'));
    }

    public function update(Request $request, Konseling $konseling)
    {
        $guru = auth()->user()->guru;
        $data = $request->only(['status','catatan_bk','jadwal_konseling','status_psikologis']);
        $data['guru_bk_id'] = $guru->id;
        $konseling->update($data);

        if ($request->status === 'disetujui') {
            Notifikasi::create([
                'user_id' => $konseling->siswa->user->id,
                'judul' => 'Konseling Disetujui',
                'pesan' => "Permintaan konseling '{$konseling->topik}' Anda telah disetujui.",
                'tipe' => 'success',
                'url' => route('siswa.konseling.show', $konseling->id),
            ]);
        }

        return back()->with('success', 'Status konseling berhasil diperbarui.');
    }

    public function sendChat(Request $request, Konseling $konseling)
    {
        $request->validate(['pesan' => 'required|string']);
        ChatKonseling::create([
            'konseling_id' => $konseling->id,
            'user_id' => auth()->id(),
            'pesan' => $request->pesan,
        ]);

        Notifikasi::create([
            'user_id' => $konseling->siswa->user->id,
            'judul' => 'Pesan Baru dari Guru BK',
            'pesan' => 'Ada pesan baru pada sesi konseling Anda.',
            'tipe' => 'info',
            'url' => route('siswa.konseling.show', $konseling->id),
        ]);

        return back();
    }

    public function laporan()
    {
        $guru = auth()->user()->guru;
        $konseling = Konseling::with(['siswa.user'])
            ->where('guru_bk_id', $guru->id)
            ->where('status', 'selesai')->get();

        $perJenis = $konseling->groupBy('jenis')->map->count();
        $perBulan = $konseling->groupBy(fn($k) => $k->created_at->format('M Y'))->map->count();

        return view('guru_bk.laporan', compact('konseling','perJenis','perBulan'));
    }
}
