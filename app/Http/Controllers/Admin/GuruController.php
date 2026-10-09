<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Services\HistoryGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = Guru::with('user');
        if ($request->search) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
                ->orWhere('nip', 'like', "%{$request->search}%");
        }
        $guru = $query->latest()->paginate(15);

        return view('admin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'nip' => 'nullable|unique:guru,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'role' => 'required|in:guru,guru_bk',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);

        Guru::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'pendidikan_terakhir' => $request->pendidikan_terakhir,
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $guru->user->update(['name' => $request->name, 'is_active' => $request->boolean('is_active')]);
        $guru->update($request->only(['nip', 'no_hp', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'pendidikan_terakhir', 'jabatan']));

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        HistoryGuard::check($guru, ['jadwal', 'absensi', 'nilai', 'materi', 'konseling', 'waliKelas']);
        $guru->user->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
