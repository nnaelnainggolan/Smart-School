<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Nilai, Siswa, MataPelajaran, Jadwal, Kelas};
use Illuminate\Http\Request;

class NilaiController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $jadwal = Jadwal::with(['kelas','mataPelajaran'])
            ->where('guru_id', $guru->id)->get();
        return view('guru.nilai.index', compact('jadwal'));
    }

    public function form(Request $request)
    {
        $guru = auth()->user()->guru;
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => 'required',
            'semester' => 'required',
        ]);

        $siswa = Siswa::with('user')->where('kelas_id', $request->kelas_id)->where('status','aktif')->get();
        $mapel = MataPelajaran::find($request->mata_pelajaran_id);
        $kelas = Kelas::find($request->kelas_id);

        $nilai = Nilai::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->where('semester', $request->semester)
            ->get()->keyBy('siswa_id');

        return view('guru.nilai.form', compact('siswa','mapel','kelas','nilai','request'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        foreach ($request->nilai as $siswa_id => $data) {
            $nilaiAkhir = null;
            if (!empty($data['nilai_harian']) && !empty($data['nilai_uts']) && !empty($data['nilai_uas'])) {
                $nilaiAkhir = ($data['nilai_harian'] * 0.4) + ($data['nilai_uts'] * 0.3) + ($data['nilai_uas'] * 0.3);
            }
            Nilai::updateOrCreate(
                [
                    'siswa_id' => $siswa_id,
                    'mata_pelajaran_id' => $request->mata_pelajaran_id,
                    'tahun_ajaran' => $request->tahun_ajaran,
                    'semester' => $request->semester,
                ],
                [
                    'guru_id' => $guru->id,
                    'nilai_harian' => $data['nilai_harian'] ?? null,
                    'nilai_uts' => $data['nilai_uts'] ?? null,
                    'nilai_uas' => $data['nilai_uas'] ?? null,
                    'nilai_akhir' => $nilaiAkhir,
                    'predikat' => $nilaiAkhir ? \App\Models\Nilai::hitungPredikat($nilaiAkhir) : null,
                    'catatan' => $data['catatan'] ?? null,
                ]
            );
        }
        return redirect()->route('guru.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }
}
