<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Services\HistoryGuard;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::withCount('siswa')->latest()->paginate(15);

        return view('admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        $guru = Guru::with('user')->get();

        return view('admin.kelas.create', compact('guru'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:100',
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan' => 'nullable|string|max:100',
            'wali_kelas' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:1',
        ]);
        Kelas::create($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        $guru = Guru::with('user')->get();

        return view('admin.kelas.edit', compact('kelas', 'guru'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $data = $request->validate([
            'nama_kelas' => 'required|string|max:100', 'tingkat' => 'required|in:X,XI,XII',
            'jurusan' => 'nullable|string|max:100', 'wali_kelas' => 'nullable|string|max:255',
            'kapasitas' => 'required|integer|min:'.max(1, $kelas->siswa()->where('status', 'aktif')->count()),
        ]);
        $kelas->update($data);

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        HistoryGuard::check($kelas, ['siswa', 'jadwal', 'enrollments']);
        $kelas->delete();

        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
