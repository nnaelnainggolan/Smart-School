@extends('layouts.app')
@section('title', 'Pilih Kelas Absensi')
@section('sidebar')
<a href="{{ route('guru.dashboard') }}" class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Kehadiran</p>
<a href="{{ route('guru.absensi.pilih') }}" class="sidebar-link {{ request()->routeIs('guru.absensi.pilih') ? 'active' : '' }}">
    <i class="fa-solid fa-clipboard-check"></i><span x-show="sidebarOpen">Input Absensi</span>
</a>
<a href="{{ route('guru.absensi.index') }}" class="sidebar-link {{ request()->routeIs('guru.absensi.index') ? 'active' : '' }}">
    <i class="fa-solid fa-table-list"></i><span x-show="sidebarOpen">Riwayat Absensi</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akademik</p>
<a href="{{ route('guru.nilai.index') }}" class="sidebar-link {{ request()->routeIs('guru.nilai*') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-bar"></i><span x-show="sidebarOpen">Input Nilai</span>
</a>
<a href="{{ route('guru.materi.index') }}" class="sidebar-link {{ request()->routeIs('guru.materi*') ? 'active' : '' }}">
    <i class="fa-solid fa-book-open"></i><span x-show="sidebarOpen">Materi Pelajaran</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Komunikasi</p>
<a href="{{ route('guru.pesan.index') }}" class="sidebar-link {{ request()->routeIs('guru.pesan*') ? 'active' : '' }}">
    <i class="fa-solid fa-envelope"></i><span x-show="sidebarOpen">Pesan Orang Tua</span>
</a>
@endsection
@section('content')
<div class="space-y-5">
    <div><h2 class="text-xl font-bold text-gray-800">Pilih Kelas untuk Absensi</h2><p class="text-sm text-gray-500">Pilih jadwal yang ingin diabsen</p></div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($jadwal as $j)
        <a href="{{ route('guru.absensi.form', $j->id) }}" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:border-secondary hover:shadow-md transition group">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-xs">{{ substr($j->hari,0,3) }}</span>
                </div>
                <span class="text-xs text-gray-400">{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</span>
            </div>
            <h3 class="font-bold text-gray-800 group-hover:text-secondary transition">{{ $j->mataPelajaran->nama_mapel }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">Kelas {{ $j->kelas->nama_kelas }}</p>
            <div class="mt-3 flex items-center gap-1 text-secondary text-xs font-medium">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                Isi Absensi →
            </div>
        </a>
        @empty
        <div class="col-span-3 py-12 text-center text-gray-400">Tidak ada jadwal mengajar yang tersedia</div>
        @endforelse
    </div>
</div>
@endsection
