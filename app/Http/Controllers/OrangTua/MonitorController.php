<?php
namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\{Nilai, Absensi, Konseling, PesanGuruOrtu, User};
use Illuminate\Http\Request;

class MonitorController extends Controller
{
    private function getSiswa()
    {
        return auth()->user()->orangTua->siswa()->with(['user','kelas'])->first();
    }

    public function nilai(Request $request)
    {
        $siswa = $this->getSiswa();
        $tahunAjaran = $request->get('tahun_ajaran', '2024/2025');
        $semester = $request->get('semester', '1');
        $nilai = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)->get();
        return view('orang_tua.nilai', compact('siswa','nilai','tahunAjaran','semester'));
    }

    public function absensi(Request $request)
    {
        $siswa = $this->getSiswa();
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $absensi = Absensi::with(['jadwal.mataPelajaran'])
            ->where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')->get();
        $rekap = $absensi->groupBy('status')->map->count();
        return view('orang_tua.absensi', compact('siswa','absensi','rekap','bulan','tahun'));
    }

    public function konseling()
    {
        $siswa = $this->getSiswa();
        $konseling = Konseling::with('guruBK.user')
            ->where('siswa_id', $siswa->id)->latest()->get();
        return view('orang_tua.konseling', compact('siswa','konseling'));
    }

    public function pesan(Request $request)
    {
        $user = auth()->user();
        if ($request->isMethod('post')) {
            $request->validate(['pesan' => 'required', 'penerima_id' => 'required|exists:users,id']);
            $siswa = $this->getSiswa();
            PesanGuruOrtu::create([
                'pengirim_id' => $user->id,
                'penerima_id' => $request->penerima_id,
                'siswa_id' => $siswa->id,
                'pesan' => $request->pesan,
            ]);
            return back()->with('success', 'Pesan berhasil dikirim.');
        }

        $pesan = PesanGuruOrtu::with(['pengirim','penerima'])
            ->where(fn($q) => $q->where('pengirim_id',$user->id)->orWhere('penerima_id',$user->id))
            ->latest()->paginate(20);

        $guru = User::whereIn('role',['guru','guru_bk'])->get();
        $siswa = $this->getSiswa();
        return view('orang_tua.pesan', compact('pesan','guru','siswa'));
    }
}
