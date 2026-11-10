@extends('layouts.app')
@section('title', 'Materi Pelajaran')
@section('subtitle', 'Akses materi belajar dari guru')
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
<div class="space-y-5">
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">Materi Pelajaran</h2>
        <form class="flex gap-2">
            <select name="mapel_id" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">Semua Mata Pelajaran</option>
                @foreach($mapelList as $m)
                    <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Filter</button>
        </form>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($materi as $m)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-file-lines text-purple-600"></i>
                </div>
                @if($m->file_path)
                <span class="text-xs px-2 py-1 bg-gray-100 text-gray-500 rounded-lg font-mono uppercase">{{ $m->tipe_file }}</span>
                @endif
            </div>
            <h3 class="font-bold text-gray-800 leading-snug">{{ $m->judul }}</h3>
            <p class="text-sm text-secondary font-medium mt-1">{{ $m->mataPelajaran->nama_mapel }}</p>
            @if($m->deskripsi)
            <p class="text-xs text-gray-500 mt-2 line-clamp-2">{{ $m->deskripsi }}</p>
            @endif
            <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-50">
                <div class="flex items-center gap-1.5">
                    <div class="w-6 h-6 bg-secondary rounded-md flex items-center justify-center text-white text-xs font-bold">{{ substr($m->guru->user->name,0,1) }}</div>
                    <span class="text-xs text-gray-500">{{ $m->guru->user->name }}</span>
                </div>
                @if($m->file_path)
                <a href="{{ route('school.material', $m) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-secondary transition">
                    <i class="fa-solid fa-download"></i> Unduh
                </a>
                @endif
            </div>
        </div>
        @empty
        <div class="col-span-3 bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <i class="fa-regular fa-folder-open text-4xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400">Belum ada materi yang tersedia</p>
        </div>
        @endforelse
    </div>
    <div>{{ $materi->links() }}</div>
</div>
@endsection
