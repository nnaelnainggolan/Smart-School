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
        $data = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajaran,id',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
        ]);

        $guru = auth()->user()->guru;
        abort_unless($guru, 403);
        abort_unless(Jadwal::where('guru_id', $guru->id)
            ->where('kelas_id', $data['kelas_id'])
            ->where('mata_pelajaran_id', $data['mata_pelajaran_id'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])->exists(), 403);

        $siswa = Siswa::with('user')
            ->where('kelas_id', $data['kelas_id'])->where('status', 'aktif')->get();
        $mapel = MataPelajaran::findOrFail($data['mata_pelajaran_id']);
        $kelas = Kelas::findOrFail($data['kelas_id']);

        $nilai = Nilai::where('guru_id', $guru->id)
            ->where('mata_pelajaran_id', $data['mata_pelajaran_id'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])
            ->whereIn('siswa_id', $siswa->pluck('id'))
            ->get()->keyBy('siswa_id');

        return view('guru.nilai.form', compact('siswa', 'mapel', 'kelas', 'nilai', 'request'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajaran,id',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
            'nilai' => 'required|array|min:1',
            'nilai.*.nilai_harian' => 'nullable|numeric|between:0,100',
            'nilai.*.nilai_uts' => 'nullable|numeric|between:0,100',
            'nilai.*.nilai_uas' => 'nullable|numeric|between:0,100',
            'nilai.*.catatan' => 'nullable|string|max:1000',
        ]);

        $guru = auth()->user()->guru;
        abort_unless($guru, 403);
        abort_unless(Jadwal::where('guru_id', $guru->id)
            ->where('kelas_id', $data['kelas_id'])
            ->where('mata_pelajaran_id', $data['mata_pelajaran_id'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])->exists(), 403);

        $ids = array_keys($data['nilai']);
        $validCount = Siswa::where('kelas_id', $data['kelas_id'])
            ->where('status', 'aktif')->whereIn('id', $ids)->count();
        if ($validCount !== count($ids)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'nilai' => 'Daftar siswa tidak sesuai dengan kelas yang Anda ajar.',
            ]);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $guru) {
            foreach ($data['nilai'] as $siswaId => $item) {
                $h = $item['nilai_harian'] ?? null;
                $u = $item['nilai_uts'] ?? null;
                $a = $item['nilai_uas'] ?? null;
                $akhir = ($h !== null && $u !== null && $a !== null)
                    ? round(($h * 0.4) + ($u * 0.3) + ($a * 0.3), 2)
                    : null;

                Nilai::updateOrCreate(
                    [
                        'siswa_id' => $siswaId,
                        'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                        'tahun_ajaran' => $data['tahun_ajaran'],
                        'semester' => $data['semester'],
                    ],
                    [
                        'guru_id' => $guru->id,
                        'nilai_harian' => $h,
                        'nilai_uts' => $u,
                        'nilai_uas' => $a,
                        'nilai_akhir' => $akhir,
                        'predikat' => $akhir !== null ? Nilai::hitungPredikat($akhir) : null,
                        'catatan' => $item['catatan'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('guru.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }

}
