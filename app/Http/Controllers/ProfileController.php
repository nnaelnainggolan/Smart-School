<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        // Update data tambahan sesuai role
        if ($user->isSiswa() && $user->siswa) {
            $user->siswa->update($request->only(['no_hp', 'alamat']));
        } elseif (($user->isGuru() || $user->isGuruBK()) && $user->guru) {
            $user->guru->update($request->only(['no_hp', 'alamat']));
        } elseif ($user->isOrangTua() && $user->orangTua) {
            $user->orangTua->update($request->only(['no_hp', 'alamat']));
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diubah.');
    }
}
