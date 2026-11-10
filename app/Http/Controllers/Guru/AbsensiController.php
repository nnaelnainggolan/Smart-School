<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Jadwal;
use App\Models\LeaveRequest;
use App\Models\Notifikasi;
use App\Models\Siswa;
use App\Services\SchoolContext;
use App\Services\TeachingAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AbsensiController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;
        $riwayat = Absensi::with(['siswa.user', 'jadwal.mataPelajaran', 'jadwal.kelas'])
            ->where('guru_id', $guru->id)
            ->latest()->paginate(20);

        return view('guru.absensi.index', compact('riwayat'));
    }

    public function pilihKelas()
    {
        $guru = auth()->user()->guru;
        $jadwal = Jadwal::where(SchoolContext::period())->with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->orderBy('hari')->orderBy('jam_mulai')->get();

        return view('guru.absensi.pilih_kelas', compact('jadwal'));
    }

    public function form(Jadwal $jadwal)
    {
        TeachingAccess::jadwal($jadwal);
        request()->validate(['tanggal' => 'nullable|date_format:Y-m-d']);
        $siswa = TeachingAccess::roster($jadwal->kelas_id, $jadwal->tahun_ajaran)->with('user')->get();

        $tanggal = request('tanggal', today()->toDateString());
        $existing = Absensi::where('jadwal_id', $jadwal->id)
            ->whereDate('tanggal', $tanggal)->get()->keyBy('siswa_id');

        $approved = LeaveRequest::whereIn('siswa_id', $siswa->modelKeys())->where('status', 'approved')->whereDate('start_date', '<=', $tanggal)->whereDate('end_date', '>=', $tanggal)->latest()->get()->unique('siswa_id')->keyBy('siswa_id');

        return view('guru.absensi.form', compact('jadwal', 'siswa', 'tanggal', 'existing', 'approved'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date_format:Y-m-d',
            'absensi' => 'required|array|min:1',
            'absensi.*.status' => 'required|in:Hadir,Izin,Sakit,Alpha',
            'absensi.*.keterangan' => 'nullable|string|max:1000',
        ]);

        $guru = auth()->user()->guru;
        $jadwal = Jadwal::find($request->jadwal_id);

        TeachingAccess::jadwal($jadwal);
        TeachingAccess::siswa(array_keys($request->absensi), $jadwal->kelas_id, $jadwal->tahun_ajaran);
        DB::transaction(function () use ($request, $guru, $jadwal) {
            foreach ($request->absensi as $siswa_id => $data) {
                $record = Absensi::updateOrCreate(
                    ['siswa_id' => $siswa_id, 'jadwal_id' => $request->jadwal_id, 'tanggal' => $request->tanggal],
                    ['status' => $data['status'], 'keterangan' => $data['keterangan'] ?? null, 'guru_id' => $guru->id]
                );

                // Notifikasi orang tua jika tidak hadir
                if (($record->wasRecentlyCreated || $record->wasChanged('status')) && in_array($data['status'], ['Alpha', 'Sakit', 'Izin'])) {
                    $siswa = Siswa::with('orangTua.user')->find($siswa_id);
                    if ($siswa && $siswa->orangTua && $siswa->orangTua->user) {
                        Notifikasi::create([
                            'user_id' => $siswa->orangTua->user->id,
                            'judul' => 'Ketidakhadiran Siswa',
                            'pesan' => "{$siswa->user->name} tidak hadir ({$data['status']}) pada mata pelajaran {$jadwal->mataPelajaran->nama_mapel} tanggal ".date('d/m/Y', strtotime($request->tanggal)),
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
