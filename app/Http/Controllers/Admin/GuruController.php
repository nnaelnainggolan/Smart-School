<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\User;
use App\Services\HistoryGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'nip' => 'nullable|string|max:255|unique:guru,nip',
            'jenis_kelamin' => 'required|in:L,P',
            'role' => 'required|in:guru,guru_bk',
            'no_hp' => 'nullable|string|max:30',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'alamat' => 'nullable|string|max:2000',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
        ]);
        $user->forceFill(['must_change_password' => true])->save();

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
        });

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        $guru->load('user');

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['nullable', 'string', 'max:255', Rule::unique('guru', 'nip')->ignore($guru->id)],
            'no_hp' => 'nullable|string|max:30',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'tanggal_lahir' => 'nullable|date|before_or_equal:today',
            'alamat' => 'nullable|string|max:2000',
            'pendidikan_terakhir' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);
        DB::transaction(function () use ($request, $guru, $data) {
            $guru->user->update(['name' => $data['name'], 'is_active' => $request->boolean('is_active')]);
            $guru->update(collect($data)->except(['name', 'is_active'])->all());
        });

        return redirect()->route('admin.guru.index')->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        HistoryGuard::check($guru, ['jadwal', 'absensi', 'nilai', 'materi', 'konseling', 'waliKelas']);
        $guru->user->delete();

        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus.');
    }
}
