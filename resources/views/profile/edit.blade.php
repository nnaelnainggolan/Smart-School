@extends('layouts.app')
@section('title', 'Profil Saya')
@section('subtitle', 'Kelola informasi akun Anda')
@section('sidebar')
    @if(auth()->user()->isAdmin())
        @include('admin.partials.sidebar')
    @elseif(auth()->user()->isGuru())
        <a href="{{ route('guru.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
    @elseif(auth()->user()->isGuruBK())
        <a href="{{ route('guru_bk.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
    @elseif(auth()->user()->isSiswa())
        <a href="{{ route('siswa.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
    @elseif(auth()->user()->isOrangTua())
        <a href="{{ route('orang_tua.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
    @endif
    <p class="sidebar-section" x-show="sidebarOpen">Akun</p>
    <a href="{{ route('profile.edit') }}" class="sidebar-link active"><i class="fa-solid fa-user-gear"></i><span x-show="sidebarOpen">Profil Saya</span></a>
@endsection
@section('content')
<div class="max-w-2xl space-y-5">
    <!-- Profile Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-8 text-center">
            <div class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center mx-auto mb-3 shadow-lg">
                <span class="text-primary font-bold text-3xl">{{ substr($user->name,0,1) }}</span>
            </div>
            <h2 class="text-white font-bold text-lg">{{ $user->name }}</h2>
            <p class="text-blue-200 text-sm capitalize">{{ str_replace('_',' ', $user->role) }}</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-4">
            @csrf @method('PUT')
            @if($errors->any() && session()->has('_old_input'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700">{{ $errors->first() }}</div>
            @endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            @if($user->isSiswa() && $user->siswa)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->siswa->no_hp) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat', $user->siswa->alamat) }}</textarea>
            </div>
            @elseif(($user->isGuru() || $user->isGuruBK()) && $user->guru)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->guru->no_hp) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat', $user->guru->alamat) }}</textarea>
            </div>
            @elseif($user->isOrangTua() && $user->orangTua)
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $user->orangTua->no_hp) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat', $user->orangTua->alamat) }}</textarea>
            </div>
            @endif
            <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">
                <i class="fa-solid fa-floppy-disk mr-1.5"></i>Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- Ganti Password -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-lock text-gray-400"></i> Ubah Password</h3>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            @if($errors->has('current_password'))
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700">{{ $errors->first('current_password') }}</div>
            @endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini</label>
                <input type="password" name="current_password" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                    <input type="password" name="new_password" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="new_password_confirmation" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-accent text-white rounded-xl font-semibold hover:bg-orange-400 transition text-sm">
                <i class="fa-solid fa-key mr-1.5"></i>Ubah Password
            </button>
        </form>
    </div>
</div>
@endsection
