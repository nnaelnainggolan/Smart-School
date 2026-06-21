<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, OrangTua, Siswa};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OrangTuaController extends Controller
{
    public function index(Request $request)
    {
        $query = OrangTua::with(['user', 'siswa.user']);

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('nama_ayah', 'like', "%{$request->search}%")
                  ->orWhere('nama_ibu', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$request->search}%")
                                                     ->orWhere('name', 'like', "%{$request->search}%"));
            });
        }

        $orangTua = $query->latest()->paginate(15);
        return view('admin.orangtua.index', compact('orangTua'));
    }

    public function create()
    {
        // Siswa yang belum punya orang tua terhubung
        $siswaTanpaOrtu = Siswa::whereNull('orang_tua_id')->with('user')->get();
        return view('admin.orangtua.create', compact('siswaTanpaOrtu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'no_hp' => 'required|string|max:20',
            'no_hp_ibu' => 'nullable|string|max:20',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama_ayah . ' (Ortu)',
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'orang_tua',
                'is_active' => true,
            ]);

            $orangTua = OrangTua::create([
                'user_id' => $user->id,
                'nama_ayah' => $request->nama_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp' => $request->no_hp,
                'no_hp_ibu' => $request->no_hp_ibu,
                'alamat' => $request->alamat,
            ]);

            // Hubungkan dengan siswa yang dipilih (jika ada)
            if ($request->siswa_ids) {
                Siswa::whereIn('id', $request->siswa_ids)->update(['orang_tua_id' => $orangTua->id]);
            }
        });

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil ditambahkan.');
    }

    public function edit(OrangTua $orangtua)
    {
        $orangtua->load(['user', 'siswa.user']);
        // Siswa yang belum punya ortu ATAU sudah terhubung dengan ortu ini
        $siswaTersedia = Siswa::where(function($q) use ($orangtua) {
                $q->whereNull('orang_tua_id')->orWhere('orang_tua_id', $orangtua->id);
            })->with('user')->get();
        return view('admin.orangtua.edit', compact('orangtua', 'siswaTersedia'));
    }

    public function update(Request $request, OrangTua $orangtua)
    {
        $request->validate([
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $orangtua->user_id,
            'no_hp' => 'required|string|max:20',
            'no_hp_ibu' => 'nullable|string|max:20',
            'pekerjaan_ayah' => 'nullable|string|max:100',
            'pekerjaan_ibu' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'siswa_ids' => 'nullable|array',
            'siswa_ids.*' => 'exists:siswa,id',
        ]);

        DB::transaction(function () use ($request, $orangtua) {
            $orangtua->user->update([
                'name' => $request->nama_ayah . ' (Ortu)',
                'email' => $request->email,
                'is_active' => $request->boolean('is_active', true),
            ]);

            $orangtua->update($request->only([
                'nama_ayah', 'nama_ibu', 'pekerjaan_ayah', 'pekerjaan_ibu',
                'no_hp', 'no_hp_ibu', 'alamat',
            ]));

            // Lepas semua siswa yang sebelumnya terhubung
            Siswa::where('orang_tua_id', $orangtua->id)->update(['orang_tua_id' => null]);

            // Hubungkan ulang dengan siswa yang dipilih
            if ($request->siswa_ids) {
                Siswa::whereIn('id', $request->siswa_ids)->update(['orang_tua_id' => $orangtua->id]);
            }
        });

        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil diperbarui.');
    }

    public function resetPassword(Request $request, OrangTua $orangtua)
    {
        $request->validate(['password' => 'required|min:6']);
        $orangtua->user->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password orang tua berhasil direset.');
    }

    public function destroy(OrangTua $orangtua)
    {
        // Lepas relasi siswa dulu sebelum hapus
        Siswa::where('orang_tua_id', $orangtua->id)->update(['orang_tua_id' => null]);
        $orangtua->user->delete(); // cascade akan hapus record orang_tua
        return redirect()->route('admin.orangtua.index')->with('success', 'Data orang tua berhasil dihapus.');
    }
}
