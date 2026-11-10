<?php

namespace App\Http\Controllers\OrangTua;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Konseling;
use App\Models\Nilai;
use App\Models\Notifikasi;
use App\Models\PesanGuruOrtu;
use App\Services\CommunicationAccess;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MonitorController extends Controller
{
    private function getSiswa()
    {
        return SchoolContext::child(request());
    }

    public function nilai(Request $request)
    {
        $siswa = $this->getSiswa();
        if (! $siswa) {
            return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
        }
        $tahunAjaran = $request->get('tahun_ajaran', SchoolContext::period()['tahun_ajaran']);
        $semester = $request->get('semester', SchoolContext::period()['semester']);
        $nilai = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester)->get();

        return view('orang_tua.nilai', compact('siswa', 'nilai', 'tahunAjaran', 'semester'));
    }

    public function absensi(Request $request)
    {
        $siswa = $this->getSiswa();
        if (! $siswa) {
            return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
        }
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $absensi = Absensi::with(['jadwal.mataPelajaran'])
            ->where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun)
            ->orderBy('tanggal')->get();
        $rekap = $absensi->groupBy('status')->map->count();

        return view('orang_tua.absensi', compact('siswa', 'absensi', 'rekap', 'bulan', 'tahun'));
    }

    public function konseling()
    {
        $siswa = $this->getSiswa();
        if (! $siswa) {
            return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
        }
        $konseling = Konseling::with('guruBK.user')
            ->where('siswa_id', $siswa->id)->latest()->get();

        return view('orang_tua.konseling', compact('siswa', 'konseling'));
    }

    public function pesan(Request $request)
    {
        $user = auth()->user();
        $siswa = $this->getSiswa();
        if (! $siswa) {
            return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
        }
        if ($request->isMethod('post')) {
            $request->validate(['pesan' => 'required|string|max:5000', 'penerima_id' => ['required', Rule::exists('users', 'id')->where(fn ($q) => $q->where('role', 'guru')->where('is_active', true))]]);
            $siswa = $this->getSiswa();
            if (! $siswa) {
                return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
            }
            CommunicationAccess::teachers($siswa)->findOrFail($request->penerima_id);
            PesanGuruOrtu::create([
                'pengirim_id' => $user->id,
                'penerima_id' => $request->penerima_id,
                'siswa_id' => $siswa->id,
                'pesan' => $request->pesan,
            ]);
            Notifikasi::create(['user_id' => $request->penerima_id, 'judul' => 'Pesan Orang Tua', 'pesan' => 'Ada pesan baru dari orang tua siswa.', 'tipe' => 'info', 'url' => route('guru.pesan.index')]);

            return back()->with('success', 'Pesan berhasil dikirim.');
        }

        PesanGuruOrtu::where('penerima_id', auth()->id())->where('dibaca', false)->update(['dibaca' => true]);
        $pesan = PesanGuruOrtu::with(['pengirim', 'penerima'])
            ->where(fn ($q) => $q->where('pengirim_id', $user->id)->orWhere('penerima_id', $user->id))
            ->latest()->paginate(20);

        $guru = CommunicationAccess::teachers($siswa)->get();
        $siswa = $this->getSiswa();
        if (! $siswa) {
            return redirect()->route('orang_tua.dashboard')->with('error', 'Belum ada siswa terhubung.');
        }

        return view('orang_tua.pesan', compact('pesan', 'guru', 'siswa'));
    }
}
