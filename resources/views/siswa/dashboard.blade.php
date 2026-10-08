@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('subtitle', 'Pantau perkembangan belajarmu')
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
<div class="dashboard-page space-y-6">
    <!-- Hero Banner -->
    <div class="dashboard-welcome bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 right-20 w-32 h-32 bg-white/5 rounded-full translate-y-12"></div>
        <div class="relative flex items-center justify-between">
            <div>
                <p class="text-blue-200 text-sm">Selamat datang,</p>
                <h2 class="text-2xl font-bold mt-0.5">{{ $siswa->user->name }}</h2>
                <p class="text-blue-200 text-sm mt-1">
                    <i class="fa-solid fa-building-columns mr-1"></i>{{ $siswa->kelas?->nama_kelas ?? 'Belum ada kelas' }}
                    <span class="mx-2">·</span>
                    <i class="fa-solid fa-id-card mr-1"></i>NIS: {{ $siswa->nis }}
                </p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-graduate text-white text-2xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card text-center">
            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-star text-blue-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $nilaiTerbaru->avg('nilai_akhir') ? number_format($nilaiTerbaru->avg('nilai_akhir'),1) : '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">Rata-rata Nilai</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 {{ $totalAbsensiAlpha > 3 ? 'bg-red-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-triangle-exclamation {{ $totalAbsensiAlpha > 3 ? 'text-red-500' : 'text-green-500' }}"></i>
            </div>
            <p class="text-2xl font-bold {{ $totalAbsensiAlpha > 3 ? 'text-red-500' : 'text-gray-800' }}">{{ $totalAbsensiAlpha }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Alpha</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-calendar-day text-purple-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $jadwalHariIni->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Jadwal Hari Ini</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-book text-orange-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $materiTerbaru->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Materi Tersedia</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Jadwal Hari Ini -->
        <div class="dashboard-panel">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800">Jadwal Hari Ini</h3>
                <span class="text-xs text-gray-400">{{ now()->locale('id')->isoFormat('dddd') }}</span>
            </div>
            @forelse($jadwalHariIni as $j)
            <div class="flex items-center gap-3 py-2.5 border-b border-gray-50 last:border-0">
                <div class="bg-secondary/10 rounded-lg px-2.5 py-2 text-center min-w-[52px]">
                    <p class="text-xs font-bold text-secondary leading-tight">{{ substr($j->jam_mulai,0,5) }}</p>
                    <p class="text-xs text-gray-400 leading-tight">{{ substr($j->jam_selesai,0,5) }}</p>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm text-gray-800">{{ $j->mataPelajaran->nama_mapel }}</p>
                    <p class="text-xs text-gray-400">{{ $j->guru->user->name }}</p>
                </div>
            </div>
            @empty
            <div class="py-8 text-center">
                <i class="fa-regular fa-calendar-xmark text-3xl text-gray-200 mb-2 block"></i>
                <p class="text-sm text-gray-400">Tidak ada jadwal hari ini</p>
            </div>
            @endforelse
        </div>

        <!-- Nilai Terbaru -->
        <div class="dashboard-panel">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Nilai Terbaru</h3>
                <a href="{{ route('siswa.nilai') }}" class="text-xs text-secondary font-semibold hover:underline">Lihat Rapor →</a>
            </div>
            @forelse($nilaiTerbaru as $n)
            <div class="flex items-center justify-between py-2.5 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fa-solid fa-book-open text-primary text-xs"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800 leading-tight">{{ $n->mataPelajaran->nama_mapel }}</p>
                        <p class="text-xs text-gray-400">Semester {{ $n->semester }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-bold text-base {{ $n->nilai_akhir >= 75 ? 'text-green-600' : 'text-red-500' }}">{{ $n->nilai_akhir ? number_format($n->nilai_akhir,1) : '-' }}</p>
                    <span class="text-xs px-1.5 py-0.5 rounded font-bold {{ $n->predikat === 'A' ? 'bg-green-100 text-green-700' : ($n->predikat === 'B' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') }}">{{ $n->predikat ?? '-' }}</span>
                </div>
            </div>
            @empty
            <div class="py-8 text-center">
                <i class="fa-solid fa-chart-bar text-3xl text-gray-200 mb-2 block"></i>
                <p class="text-sm text-gray-400">Belum ada nilai</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Materi Terbaru -->
    @if($materiTerbaru->count())
    <div class="dashboard-panel">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800">Materi Terbaru</h3>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($materiTerbaru as $m)
            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl hover:bg-blue-50 transition">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-file-lines text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-800 truncate">{{ $m->judul }}</p>
                    <p class="text-xs text-gray-400">{{ $m->mataPelajaran->nama_mapel }}</p>
                </div>
                @if($m->file_path)
                <a href="{{ Storage::url($m->file_path) }}" target="_blank" rel="noopener noreferrer" aria-label="Unduh materi {{ $m->judul }}"
                   class="w-8 h-8 bg-secondary rounded-lg flex items-center justify-center flex-shrink-0 hover:bg-primary transition">
                    <i class="fa-solid fa-download text-white text-xs"></i>
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
