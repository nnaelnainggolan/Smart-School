<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Services\HistoryGuard;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['kelas', 'mataPelajaran', 'guru.user']);
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20);
        $kelas = Kelas::all();

        return view('admin.jadwal.index', compact('jadwal', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = Guru::with('user')->get();
        $tahunAjaran = AcademicPeriod::orderByDesc('tahun_ajaran')->pluck('tahun_ajaran')->unique()->values()->all();
        if (! $tahunAjaran) {
            $tahunAjaran = [SchoolContext::period()['tahun_ajaran']];
        }

        return view('admin.jadwal.create', compact('kelas', 'mapel', 'guru', 'tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_ajaran' => 'required',
            'semester' => 'required|in:1,2',
        ]);
        $conflict = Jadwal::where('hari', $request->hari)->where('tahun_ajaran', $request->tahun_ajaran)->where('semester', $request->semester)
            ->where('jam_mulai', '<', $request->jam_selesai)->where('jam_selesai', '>', $request->jam_mulai)
            ->where(function ($q) use ($request) {
                $q->where('guru_id', $request->guru_id)->orWhere('kelas_id', $request->kelas_id);
                if ($request->filled('ruangan')) {
                    $q->orWhere('ruangan', $request->ruangan);
                }
            })->exists();
        if ($conflict) {
            throw ValidationException::withMessages(['jadwal' => 'Jadwal bertabrakan dengan guru, kelas, atau ruangan.']);
        }
        Jadwal::create($request->only(['kelas_id', 'mata_pelajaran_id', 'guru_id', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan', 'tahun_ajaran', 'semester']));

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Jadwal $jadwal)
    {
        HistoryGuard::check($jadwal, ['absensi']);
        $jadwal->delete();

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
