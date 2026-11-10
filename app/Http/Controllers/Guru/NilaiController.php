<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\ReportPublication;
use App\Services\TeachingAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class NilaiController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $jadwal = Jadwal::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)->get();

        return view('guru.nilai.index', compact('jadwal'));
    }

    public function form(Request $request)
    {
        $guru = auth()->user()->guru;
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
        ]);

        TeachingAccess::kelas((int) $request->kelas_id, (int) $request->mata_pelajaran_id, $request->tahun_ajaran, $request->semester);
        $siswa = TeachingAccess::roster((int) $request->kelas_id, $request->tahun_ajaran)->with('user')->get();
        $mapel = MataPelajaran::find($request->mata_pelajaran_id);
        $kelas = Kelas::find($request->kelas_id);

        $nilai = Nilai::whereIn('siswa_id', $siswa->modelKeys())
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->where('tahun_ajaran', $request->tahun_ajaran)
            ->where('semester', $request->semester)
            ->get()->keyBy('siswa_id');

        return view('guru.nilai.form', compact('siswa', 'mapel', 'kelas', 'nilai', 'request'));
    }

    public function store(Request $request)
    {
        $guru = auth()->user()->guru;
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
            'nilai' => 'required|array|min:1',
            'nilai.*.nilai_harian' => 'nullable|numeric|between:0,100',
            'nilai.*.nilai_uts' => 'nullable|numeric|between:0,100',
            'nilai.*.nilai_uas' => 'nullable|numeric|between:0,100',
            'nilai.*.catatan' => 'nullable|string|max:1000',
        ]);
        TeachingAccess::kelas((int) $request->kelas_id, (int) $request->mata_pelajaran_id, $request->tahun_ajaran, $request->semester);
        TeachingAccess::siswa(array_keys($request->nilai), (int) $request->kelas_id, $request->tahun_ajaran);
        DB::transaction(function () use ($request, $guru) {
            foreach ($request->nilai as $siswa_id => $data) {
                $publication = ReportPublication::firstOrCreate(['siswa_id' => $siswa_id, 'tahun_ajaran' => $request->tahun_ajaran, 'semester' => $request->semester], ['snapshot' => []]);
                $publication = ReportPublication::lockForUpdate()->findOrFail($publication->id);
                if ($publication->published_at) {
                    throw ValidationException::withMessages(['nilai' => 'Rapor sudah terbit. Hubungi admin untuk membuka revisi.']);
                }
                $nilaiAkhir = null;
                if (isset($data['nilai_harian'], $data['nilai_uts'], $data['nilai_uas'])) {
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
                        'predikat' => $nilaiAkhir !== null ? Nilai::hitungPredikat($nilaiAkhir) : null,
                        'catatan' => $data['catatan'] ?? null,
                    ]
                );
            }
        });

        return redirect()->route('guru.nilai.index')->with('success', 'Nilai berhasil disimpan.');
    }
}
