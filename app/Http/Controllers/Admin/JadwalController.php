<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Jadwal, Kelas, MataPelajaran, Guru};
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::with(['kelas','mataPelajaran','guru.user']);
        if ($request->kelas_id) $query->where('kelas_id', $request->kelas_id);
        $jadwal = $query->orderBy('hari')->orderBy('jam_mulai')->paginate(20);
        $kelas = Kelas::all();
        return view('admin.jadwal.index', compact('jadwal', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $mapel = MataPelajaran::all();
        $guru = Guru::with('user')->get();
        $tahunAjaran = ['2024/2025', '2025/2026'];
        return view('admin.jadwal.create', compact('kelas','mapel','guru','tahunAjaran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'tahun_ajaran' => 'required',
            'semester' => 'required',
        ]);
        Jadwal::create($request->all());
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
