<?php

namespace App\Http\Controllers\GuruBK;

use App\Http\Controllers\Controller;
use App\Models\ChatKonseling;
use App\Models\Konseling;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KonselingController extends Controller
{
    public function index(Request $request)
    {
        $guru = auth()->user()->guru;
        $query = Konseling::with(['siswa.user'])->where(fn ($q) => $q->whereNull('guru_bk_id')->orWhere('guru_bk_id', $guru->id));
        if ($request->status) {
            $query->where('status', $request->status);
        }
        $konseling = $query->latest()->paginate(15);

        return view('guru_bk.konseling.index', compact('konseling'));
    }

    public function show(Konseling $konseling)
    {
        abort_if($konseling->guru_bk_id && $konseling->guru_bk_id !== auth()->user()->guru?->id, 403);
        $konseling->load(['siswa.user', 'guruBK.user', 'chat.user']);

        return view('guru_bk.konseling.show', compact('konseling'));
    }

    public function update(Request $request, Konseling $konseling)
    {
        DB::transaction(function () use ($request, $konseling) {
            $konseling = Konseling::lockForUpdate()->findOrFail($konseling->id);
            $guru = auth()->user()->guru;
            abort_if($konseling->guru_bk_id && $konseling->guru_bk_id !== $guru->id, 403);
            $data = $request->validate(['status' => 'required|in:pending,disetujui,berlangsung,selesai,ditolak', 'catatan_bk' => 'nullable|string|max:5000', 'ringkasan_ortu' => 'nullable|string|max:2000', 'jadwal_konseling' => 'nullable|date', 'status_psikologis' => 'nullable|in:baik,perlu_perhatian,kritis']);
            $allowed = ['pending' => ['pending', 'disetujui', 'ditolak'], 'disetujui' => ['disetujui', 'berlangsung', 'ditolak'], 'berlangsung' => ['berlangsung', 'selesai'], 'selesai' => ['selesai'], 'ditolak' => ['ditolak']];
            abort_unless(in_array($data['status'], $allowed[$konseling->status]), 422, 'Perpindahan status tidak valid.');
            $oldStatus = $konseling->status;
            $data['guru_bk_id'] = $guru->id;
            $konseling->update($data);

            if ($request->status === 'disetujui' && $oldStatus !== 'disetujui') {
                Notifikasi::create([
                    'user_id' => $konseling->siswa->user->id,
                    'judul' => 'Konseling Disetujui',
                    'pesan' => "Permintaan konseling '{$konseling->topik}' Anda telah disetujui.",
                    'tipe' => 'success',
                    'url' => route('siswa.konseling.show', $konseling->id),
                ]);
            }

        });

        return back()->with('success', 'Status konseling berhasil diperbarui.');
    }

    public function sendChat(Request $request, Konseling $konseling)
    {
        abort_unless($konseling->guru_bk_id === auth()->user()->guru?->id && in_array($konseling->status, ['disetujui', 'berlangsung']), 403);
        $request->validate(['pesan' => 'required|string|max:5000']);
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
        $perBulan = $konseling->groupBy(fn ($k) => $k->created_at->format('M Y'))->map->count();

        return view('guru_bk.laporan', compact('konseling', 'perJenis', 'perBulan'));
    }
}
