<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{PesanGuruOrtu, Siswa, Notifikasi, Jadwal};
use Illuminate\Http\Request;

class PesanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        // Daftar siswa yang diajar guru ini (berdasarkan jadwal)
        $kelasIds = Jadwal::where('guru_id', $guru->id)->pluck('kelas_id')->unique();
        $siswaList = Siswa::with(['user','orangTua.user','kelas'])
            ->whereIn('kelas_id', $kelasIds)
            ->where('status', 'aktif')
            ->get();

        if ($request->isMethod('post')) {
            $request->validate([
                'siswa_id' => 'required|exists:siswa,id',
                'pesan' => 'required|string',
            ]);

            $siswa = Siswa::with('orangTua.user')->find($request->siswa_id);
            if (!$siswa->orangTua || !$siswa->orangTua->user) {
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
                'pesan' => "{$user->name}: " . \Illuminate\Support\Str::limit($request->pesan, 60),
                'tipe' => 'info',
                'url' => route('orang_tua.pesan'),
            ]);

            return back()->with('success', 'Pesan berhasil dikirim ke orang tua siswa.');
        }

        $pesan = PesanGuruOrtu::with(['pengirim','penerima','siswa.user'])
            ->where(fn($q) => $q->where('pengirim_id', $user->id)->orWhere('penerima_id', $user->id))
            ->latest()->paginate(20);

        return view('guru.pesan.index', compact('siswaList', 'pesan'));
    }
}
