<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use App\Services\HistoryGuard;
use App\Services\SchoolContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with(['user', 'kelas', 'orangTua.user']);
        if ($request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        if ($request->search) {
            $query->where(fn ($q) => $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$request->search}%"))->orWhere('nis', 'like', "%{$request->search}%"));
        }
        $siswa = $query->latest()->paginate(15);
        $kelas = Kelas::all();

        return view('admin.siswa.index', compact('siswa', 'kelas'));
    }

    public function create()
    {
        $kelas = Kelas::all();
        $parents = OrangTua::with('user')->get();

        return view('admin.siswa.create', compact('kelas', 'parents'));
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
            'orang_tua_id' => 'nullable|exists:orang_tua,id',
            'nisn' => 'nullable|string|max:30|unique:siswa,nisn',
            'tanggal_lahir' => 'nullable|date',
            'nama_ayah' => 'required_without:orang_tua_id|nullable|string',
            'no_hp_ortu' => 'required_without:orang_tua_id|nullable|string|max:20',
            'email_ortu' => 'required_without:orang_tua_id|nullable|email|unique:users,email|different:email_siswa',
            'password_ortu' => 'required_without:orang_tua_id|nullable|min:6',
        ]);

        DB::transaction(function () use ($request) {
            $kelas = Kelas::lockForUpdate()->findOrFail($request->kelas_id);
            if ($kelas->siswa()->where('status', 'aktif')->count() >= $kelas->kapasitas) {
                throw ValidationException::withMessages(['kelas_id' => 'Kelas sudah penuh.']);
            }
            if ($request->filled('orang_tua_id')) {
                $orangTua = OrangTua::findOrFail($request->orang_tua_id);
            } else {
                // Buat user orang tua
                $userOrtu = User::create([
                    'name' => $request->nama_ayah.' (Ortu)',
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

            }
            // Buat user siswa
            $userSiswa = User::create([
                'name' => $request->name,
                'email' => $request->email_siswa,
                'password' => Hash::make($request->password_siswa),
                'role' => 'siswa',
                'is_active' => true,
            ]);

            $siswa = Siswa::create([
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
            Enrollment::create(['siswa_id' => $siswa->id, 'kelas_id' => $kelas->id, 'tahun_ajaran' => SchoolContext::period()['tahun_ajaran']]);
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
        $d = $request->validate(['name' => 'required|string|max:255', 'kelas_id' => 'required|exists:kelas,id', 'jenis_kelamin' => 'required|in:L,P', 'tanggal_lahir' => 'nullable|date', 'tempat_lahir' => 'nullable|string|max:255', 'alamat' => 'nullable|string|max:2000', 'status' => 'required|in:aktif,lulus,keluar']);
        DB::transaction(function () use ($d, $siswa) {
            $kelas = Kelas::lockForUpdate()->findOrFail($d['kelas_id']);
            if ($d['status'] === 'aktif' && ($siswa->kelas_id !== $kelas->id || $siswa->status !== 'aktif') && $kelas->siswa()->where('status', 'aktif')->count() >= $kelas->kapasitas) {
                throw ValidationException::withMessages(['kelas_id' => 'Kelas tujuan sudah penuh.']);
            }
            $old = $siswa->only(['kelas_id', 'status']);
            $siswa->user->update(['name' => $d['name']]);
            $siswa->update(collect($d)->except('name')->all());
            if ($old['kelas_id'] !== $kelas->id) {
                $year = SchoolContext::period()['tahun_ajaran'];
                if ($siswa->enrollments()->where('tahun_ajaran', '>', $year)->exists()) {
                    throw ValidationException::withMessages(['kelas_id' => 'Aktifkan tahun ajaran terbaru sebelum mengubah kelas siswa yang sudah naik kelas.']);
                }
                Enrollment::updateOrCreate(['siswa_id' => $siswa->id, 'tahun_ajaran' => $year], ['kelas_id' => $kelas->id]);
            }
            SchoolContext::audit('student.updated', 'siswa:'.$siswa->id, ['before' => $old, 'after' => $siswa->only(['kelas_id', 'status'])]);
        });

        return redirect()->route('admin.siswa.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        HistoryGuard::check($siswa, ['absensi', 'nilai', 'konseling', 'enrollments']);
        $siswa->user->delete();

        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }
}
