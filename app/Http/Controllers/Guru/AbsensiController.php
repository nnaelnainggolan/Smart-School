<?php
namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\{Absensi, Jadwal, Siswa, Kelas, Guru, Notifikasi};
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $riwayat = Absensi::with(['siswa.user','jadwal.mataPelajaran','jadwal.kelas'])
            ->where('guru_id', $guru->id)
            ->latest()->paginate(20);
        return view('guru.absensi.index', compact('riwayat'));
    }

    public function pilihKelas()
    {
        $guru = auth()->user()->guru;
        $jadwal = Jadwal::with(['kelas','mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->orderBy('hari')->orderBy('jam_mulai')->get();
        return view('guru.absensi.pilih_kelas', compact('jadwal'));
    }

    public function form(Jadwal $jadwal)
    {
        $guru = auth()->user()->guru;
        abort_unless($guru && (int) $jadwal->guru_id === (int) $guru->id, 403);

        $data = request()->validate(['tanggal' => 'nullable|date']);
        $tanggal = $data['tanggal'] ?? today()->toDateString();

        $siswa = Siswa::with('user')
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('status', 'aktif')->get();

        $existing = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)->get()->keyBy('siswa_id');

        return view('guru.absensi.form', compact('jadwal', 'siswa', 'tanggal', 'existing'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'jadwal_id' => 'required|integer|exists:jadwal,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array|min:1',
            'absensi.*.status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'absensi.*.keterangan' => 'nullable|string|max:500',
        ]);

        $guru = auth()->user()->guru;
        abort_unless($guru, 403);
        $jadwal = Jadwal::with('mataPelajaran')
            ->where('guru_id', $guru->id)->findOrFail($data['jadwal_id']);

        $ids = array_keys($data['absensi']);
        $siswa = Siswa::with(['user', 'orangTua.user'])
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('status', 'aktif')
            ->whereIn('id', $ids)->get()->keyBy('id');
        if ($siswa->count() !== count($ids)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'absensi' => 'Daftar siswa tidak sesuai dengan kelas pada jadwal.',
            ]);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($data, $guru, $jadwal, $siswa) {
            foreach ($data['absensi'] as $siswaId => $input) {
                $absensi = Absensi::updateOrCreate(
                    ['siswa_id' => $siswaId, 'jadwal_id' => $jadwal->id, 'tanggal' => $data['tanggal']],
                    [
                        'status' => $input['status'],
                        'keterangan' => $input['keterangan'] ?? null,
                        'guru_id' => $guru->id,
                    ]
                );

                // Do not resend an absence notification when nothing changed.
                if (in_array($input['status'], ['Alpha', 'Sakit', 'Izin'], true)
                    && ($absensi->wasRecentlyCreated || $absensi->wasChanged('status'))) {
                    $anak = $siswa->get($siswaId);
                    $parent = $anak?->orangTua?->user;
                    if ($parent) {
                        Notifikasi::create([
                            'user_id' => $parent->id,
                            'judul' => 'Ketidakhadiran Siswa',
                            'pesan' => "{$anak->user->name} tidak hadir ({$input['status']}) pada mata pelajaran {$jadwal->mataPelajaran->nama_mapel} tanggal " . date('d/m/Y', strtotime($data['tanggal'])),
                            'tipe' => 'warning',
                            'url' => route('orang_tua.absensi'),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil disimpan.');
    }

}
