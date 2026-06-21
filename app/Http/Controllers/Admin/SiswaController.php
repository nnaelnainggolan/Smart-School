<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Siswa, OrangTua, Kelas};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas', 'orangTua.user']);
        if ($request->kelas_id) $query->where('kelas_id', $request->kelas_id);
        if ($request->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                  ->orWhere('nis', 'like', "%{$request->search}%");
        }
        $siswa = $query->latest()->paginate(15);
        $kelas = Kelas::all();
        return view('admin.siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.siswa.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email_siswa' => 'required|email|unique:users,email',
            'password_siswa' => 'required|min:6',
            'nis' => 'required|unique:siswa,nis',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
            'tahun_masuk' => 'required',
            'nama_ayah' => 'required|string',
            'no_hp_ortu' => 'required',
            'email_ortu' => 'required|email|unique:users,email',
            'password_ortu' => 'required|min:6',
        ]);

        DB::transaction(function () use ($request) {
            // Buat user orang tua
            $userOrtu = User::create([
                'name' => $request->nama_ayah . ' (Ortu)',
                'email' => $request->email_ortu,
                'password' => Hash::make($request->password_ortu),
                'role' => 'orang_tua',
                'is_active' => true,
            ]);

            $orangTua = OrangTua::create([
                'user_id' => $userOrtu->id,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'no_hp' => $request->no_hp_ortu,
                'no_hp_ibu' => $request->no_hp_ibu,
                'alamat' => $request->alamat_ortu,
            ]);

            // Buat user siswa
            $userSiswa = User::create([
                'name' => $request->name,
                'email' => $request->email_siswa,
                'password' => Hash::make($request->password_siswa),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            Siswa::create([
                'user_id' => $userSiswa->id,
                'orang_tua_id' => $orangTua->id,
                'kelas_id' => $request->kelas_id,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'tempat_lahir' => $request->tempat_lahir,
                'alamat' => $request->alamat,
                'tahun_masuk' => $request->tahun_masuk,
                'status' => 'aktif',
            ]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil ditambahkan beserta akun orang tua.');
    }

    public function edit(Siswa $siswa)
    {
        $siswa->load(['user', 'kelas', 'orangTua.user']);
        $kelas = Kelas::all();
        return view('admin.siswa.edit', compact('siswa', 'kelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        $siswa->user->update(['name' => $request->name]);
        $siswa->update($request->only(['kelas_id','jenis_kelamin','tanggal_lahir','tempat_lahir','alamat','status']));

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->user->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
