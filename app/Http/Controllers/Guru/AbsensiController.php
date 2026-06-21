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
        $siswa = Siswa::with('user')
            ->where('kelas_id', $jadwal->kelas_id)
            ->where('status', 'aktif')->get();

        $tanggal = request('tanggal', today()->toDateString());
        $existing = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)->get()->keyBy('siswa_id');

        return view('guru.absensi.form', compact('jadwal','siswa','tanggal','existing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
            'absensi' => 'required|array',
        ]);

        $guru = auth()->user()->guru;
        $jadwal = Jadwal::find($request->jadwal_id);

        foreach ($request->absensi as $siswa_id => $data) {
            Absensi::updateOrCreate(
                ['siswa_id' => $siswa_id, 'jadwal_id' => $request->jadwal_id, 'tanggal' => $request->tanggal],
                ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null, 'guru_id' => $guru->id]
            );

            // Notifikasi orang tua jika tidak hadir
            if (in_array($data['status'], ['Alpha','Sakit','Izin'])) {
                $siswa = Siswa::with('orangTua.user')->find($siswa_id);
                if ($siswa && $siswa->orangTua && $siswa->orangTua->user) {
                    Notifikasi::create([
                        'user_id' => $siswa->orangTua->user->id,
                        'judul' => 'Ketidakhadiran Siswa',
                        'pesan' => "{$siswa->user->name} tidak hadir ({$data['status']}) pada mata pelajaran {$jadwal->mataPelajaran->nama_mapel} tanggal " . date('d/m/Y', strtotime($request->tanggal)),
                        'tipe' => 'warning',
                        'url' => route('orang_tua.absensi'),
                    ]);
                }
            }
        }

        return redirect()->route('guru.absensi.index')->with('success', 'Absensi berhasil disimpan.');
    }
}
