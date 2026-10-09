<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Notifikasi;
use App\Models\PesanGuruOrtu;
use App\Models\Siswa;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PesanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Daftar siswa yang diajar guru ini (berdasarkan jadwal)
        $kelasIds = Jadwal::where(SchoolContext::period())->where('guru_id', $guru->id)->pluck('kelas_id')->merge(Kelas::where('wali_guru_id', $guru->id)->pluck('id'))->unique();
        $siswaList = Siswa::with(['user', 'orangTua.user', 'kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->get();

        if ($request->isMethod('post')) {
            $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'pesan' => 'required|string|max:5000',
            ]);

            $siswa = Siswa::with('orangTua.user')->whereIn('kelas_id', $kelasIds)->findOrFail($request->siswa_id);
            if (! $siswa->orangTua || ! $siswa->orangTua->user) {
                return back()->with('error', 'Data orang tua siswa tidak ditemukan.');
            }

            PesanGuruOrtu::create([
                'pengirim_id' => $user->id,
                'penerima_id' => $siswa->orangTua->user->id,
                'siswa_id' => $siswa->id,
                'pesan' => $request->pesan,
            ]);

            Notifikasi::create([
                'user_id' => $siswa->orangTua->user->id,
                'judul' => 'Pesan Baru dari Guru',
                'pesan' => "{$user->name}: ".Str::limit($request->pesan, 60),
                'tipe' => 'info',
                'url' => route('orang_tua.pesan'),
            ]);

            return back()->with('success', 'Pesan berhasil dikirim ke orang tua siswa.');
        }

        PesanGuruOrtu::where('penerima_id', auth()->id())->where('dibaca', false)->update(['dibaca' => true]);
        $pesan = PesanGuruOrtu::with(['pengirim', 'penerima', 'siswa.user'])
            ->where(fn ($q) => $q->where('pengirim_id', $user->id)->orWhere('penerima_id', $user->id))
            ->latest()->paginate(20);

        return view('guru.pesan.index', compact('siswaList', 'pesan'));
    }
}
