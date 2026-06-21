@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@section('subtitle', 'Jadwal pelajaran mingguan kelas Anda')
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
    <h2 class="text-xl font-bold text-gray-800">Jadwal Pelajaran Mingguan</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @forelse($urutanHari as $hari)
        @if(isset($jadwal[$hari]))
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-3 bg-primary text-white font-bold text-sm flex items-center gap-2">
                <i class="fa-solid fa-calendar-day"></i> {{ $hari }}
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($jadwal[$hari]->sortBy('jam_mulai') as $j)
                <div class="flex items-center gap-3 p-4">
                    <div class="bg-secondary/10 rounded-xl px-3 py-2 text-center min-w-[64px]">
                        <p class="text-xs font-bold text-secondary">{{ substr($j->jam_mulai,0,5) }}</p>
                        <p class="text-xs text-gray-400">{{ substr($j->jam_selesai,0,5) }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm">{{ $j->mataPelajaran->nama_mapel }}</p>
                        <p class="text-xs text-gray-400">{{ $j->guru->user->name }} @if($j->ruangan) · Ruang {{ $j->ruangan }}@endif</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @empty
        @endforelse
    </div>
    @if($jadwal->isEmpty())
    <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-200 mb-3 block"></i>
        <p class="text-gray-400">Jadwal pelajaran belum tersedia untuk kelas Anda</p>
    </div>
    @endif
</div>
@endsection
