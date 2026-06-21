@extends('layouts.app')
@section('title', 'Ajukan Konseling')
@section('sidebar')
<a href="{{ route('siswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akademik</p>
<a href="{{ route('siswa.jadwal') }}" class="sidebar-link {{ request()->routeIs('siswa.jadwal') ? 'active' : '' }}">
    <i class="fa-solid fa-calendar-days"></i><span x-show="sidebarOpen">Jadwal Pelajaran</span>
</a>
<a href="{{ route('siswa.nilai') }}" class="sidebar-link {{ request()->routeIs('siswa.nilai') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-bar"></i><span x-show="sidebarOpen">Nilai & Rapor</span>
</a>
<a href="{{ route('siswa.absensi') }}" class="sidebar-link {{ request()->routeIs('siswa.absensi') ? 'active' : '' }}">
    <i class="fa-solid fa-clipboard-list"></i><span x-show="sidebarOpen">Absensi Saya</span>
</a>
<a href="{{ route('siswa.materi') }}" class="sidebar-link {{ request()->routeIs('siswa.materi') ? 'active' : '' }}">
    <i class="fa-solid fa-book-open"></i><span x-show="sidebarOpen">Materi Pelajaran</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Layanan</p>
<a href="{{ route('siswa.konseling.index') }}" class="sidebar-link {{ request()->routeIs('siswa.konseling*') ? 'active' : '' }}">
    <i class="fa-solid fa-comments"></i><span x-show="sidebarOpen">Konseling</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akun</p>
<a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="fa-solid fa-user-gear"></i><span x-show="sidebarOpen">Profil Saya</span>
</a>
@endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-primary to-secondary px-6 py-5">
            <h2 class="text-lg font-bold text-white">Ajukan Konseling</h2>
            <p class="text-blue-200 text-sm mt-0.5">Ceritakan apa yang ingin kamu diskusikan dengan Guru BK</p>
        </div>
        <form method="POST" action="{{ route('siswa.konseling.store') }}" class="p-6 space-y-4">
            @csrf
            @if($errors->any())<div class="bg-red-50 border border-red-200 rounded-xl p-4 text-sm text-red-700">@foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach</div>@endif
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Topik Konseling <span class="text-red-500">*</span></label>
                <input type="text" name="topik" value="{{ old('topik') }}" required placeholder="Contoh: Masalah belajar, tekanan ujian, dll" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Konseling <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['akademik' => '📚 Akademik', 'pribadi' => '💭 Pribadi', 'sosial' => '👥 Sosial', 'karir' => '🎯 Karir'] as $val => $label)
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:border-secondary transition has-[:checked]:border-secondary has-[:checked]:bg-blue-50">
                        <input type="radio" name="jenis" value="{{ $val }}" {{ old('jenis', 'pribadi') === $val ? 'checked' : '' }} class="text-secondary">
                        <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi Masalah <span class="text-red-500">*</span></label>
                <textarea name="deskripsi" rows="5" required placeholder="Ceritakan secara detail apa yang kamu rasakan atau alami..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none">{{ old('deskripsi') }}</textarea>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-700">
                <p class="font-semibold mb-1">🔒 Kerahasiaan Terjaga</p>
                <p>Semua informasi konseling bersifat rahasia dan hanya dapat diakses oleh kamu dan Guru BK.</p>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Kirim Permintaan</button>
                <a href="{{ route('siswa.konseling.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
