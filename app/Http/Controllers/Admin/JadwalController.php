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
        $data = $request->validate([
            'kelas_id' => 'required|integer|exists:kelas,id',
            'mata_pelajaran_id' => 'required|integer|exists:mata_pelajaran,id',
            'guru_id' => 'required|integer|exists:guru,id',
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'tahun_ajaran' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => 'required|in:1,2',
            'ruangan' => 'nullable|string|max:100',
        ]);

        $bentrok = Jadwal::where('hari', $data['hari'])
            ->where('tahun_ajaran', $data['tahun_ajaran'])
            ->where('semester', $data['semester'])
            ->where('jam_mulai', '<', $data['jam_selesai'])
            ->where('jam_selesai', '>', $data['jam_mulai'])
            ->where(function ($q) use ($data) {
                $q->where('kelas_id', $data['kelas_id'])->orWhere('guru_id', $data['guru_id']);
                if (!empty($data['ruangan'])) $q->orWhere('ruangan', $data['ruangan']);
            })->exists();

        if ($bentrok) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'jam_mulai' => 'Jadwal bentrok dengan kelas, guru, atau ruangan lain.',
            ]);
        }

        Jadwal::create($data);
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Jadwal $jadwal)
    {
        $jadwal->delete();
        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
