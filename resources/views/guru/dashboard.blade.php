@extends('layouts.app')
@section('title', 'Dashboard Guru')
@section('subtitle', 'Selamat datang kembali!')
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
<div class="dashboard-page space-y-6">

    <!-- Welcome Banner -->
    <div class="dashboard-welcome bg-gradient-to-r from-primary via-primary to-secondary rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-10 translate-x-10"></div>
        <div class="absolute bottom-0 right-10 w-24 h-24 bg-white/5 rounded-full translate-y-8"></div>
        <div class="relative">
            <p class="text-blue-200 text-sm">Hari ini, {{ now()->locale('id')->isoFormat('dddd D MMMM Y') }}</p>
            <h2 class="text-2xl font-bold mt-1">{{ $guru->user->name }}</h2>
            <p class="text-blue-200 text-sm mt-0.5">{{ $guru->jabatan ?? 'Guru' }}</p>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clipboard-check text-blue-600 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalAbsensiInput }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Absensi Hari Ini</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chart-bar text-green-600 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalNilai }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Total Nilai Diinput</p>
            </div>
        </div>
        <div class="stat-card flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-book-open text-purple-600 text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $totalMateri }}</p>
                <p class="text-xs text-gray-500 mt-0.5">Materi Diupload</p>
            </div>
        </div>
    </div>

    <!-- Jadwal & Aksi Cepat -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Jadwal Hari Ini -->
        <div class="dashboard-panel">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Jadwal Mengajar Hari Ini</h3>
                <span class="text-xs bg-primary/10 text-primary px-2.5 py-1 rounded-full font-semibold">{{ $jadwalHariIni->count() }} kelas</span>
            </div>
            @forelse($jadwalHariIni as $j)
            <div class="flex items-center gap-4 py-3 border-b border-gray-50 last:border-0">
                <div class="bg-secondary/10 rounded-xl px-3 py-2 text-center min-w-[56px]">
                    <p class="text-xs font-bold text-secondary">{{ substr($j->jam_mulai,0,5) }}</p>
                    <p class="text-xs text-gray-400">{{ substr($j->jam_selesai,0,5) }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $j->mataPelajaran->nama_mapel }}</p>
                    <p class="text-xs text-gray-400">Kelas {{ $j->kelas->nama_kelas }}</p>
                </div>
                <a href="{{ route('guru.absensi.form', $j->id) }}"
                   class="flex-shrink-0 px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-secondary transition flex items-center gap-1.5">
                    <i class="fa-solid fa-clipboard-check text-xs"></i> Absen
                </a>
            </div>
            @empty
            <div class="py-10 text-center text-gray-300">
                <i class="fa-regular fa-calendar-xmark text-4xl mb-3 block"></i>
                <p class="text-sm text-gray-400">Tidak ada jadwal mengajar hari ini</p>
            </div>
            @endforelse
        </div>

        <!-- Aksi Cepat -->
        <div class="dashboard-panel">
            <h3 class="font-bold text-gray-800 mb-4">Aksi Cepat</h3>
            <div class="grid grid-cols-2 gap-3">
                <a href="{{ route('guru.absensi.pilih') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-blue-50 transition group text-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                        <i class="fa-solid fa-clipboard-check text-blue-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Isi Absensi</span>
                </a>
                <a href="{{ route('guru.nilai.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-green-50 transition group text-center">
                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                        <i class="fa-solid fa-pen-to-square text-green-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Input Nilai</span>
                </a>
                <a href="{{ route('guru.materi.create') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-purple-50 transition group text-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition">
                        <i class="fa-solid fa-upload text-purple-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Upload Materi</span>
                </a>
                <a href="{{ route('guru.absensi.index') }}" class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-orange-50 transition group text-center">
                    <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-200 transition">
                        <i class="fa-solid fa-table-list text-orange-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-gray-600">Riwayat Absensi</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
