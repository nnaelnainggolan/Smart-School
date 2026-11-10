<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Services\HistoryGuard;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapel = MataPelajaran::latest()->paginate(15);

        return view('admin.mapel.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mapel.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kelompok' => 'nullable|string|max:100', 'deskripsi' => 'nullable|string|max:5000',
            'nama_mapel' => 'required|string|max:100',
            'kode_mapel' => 'required|unique:mata_pelajaran,kode_mapel',
            'kkm' => 'required|integer|min:0|max:100',
        ]);
        MataPelajaran::create($data);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function edit(MataPelajaran $mapel)
    {
        return view('admin.mapel.edit', compact('mapel'));
    }

    public function update(Request $request, MataPelajaran $mapel)
    {
        $data = $request->validate([
            'nama_mapel' => 'required|string|max:100',
            'kode_mapel' => ['required', 'string', 'max:30', Rule::unique('mata_pelajaran', 'kode_mapel')->ignore($mapel->id)],
            'kkm' => 'required|integer|min:0|max:100', 'kelompok' => 'nullable|string|max:100', 'deskripsi' => 'nullable|string|max:5000',
        ]);
        $mapel->update($data);

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mapel)
    {
        HistoryGuard::check($mapel, ['jadwal', 'nilai', 'materi']);
        $mapel->delete();

        return redirect()->route('admin.mapel.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
