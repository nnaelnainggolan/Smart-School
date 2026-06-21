@extends('layouts.app')
@section('title', 'Dashboard Guru BK')
@section('subtitle', 'Layanan Bimbingan & Konseling')
@section('sidebar')
<a href="{{ route('guru_bk.dashboard') }}" class="sidebar-link {{ request()->routeIs('guru_bk.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Konseling</p>
<a href="{{ route('guru_bk.konseling.index') }}" class="sidebar-link {{ request()->routeIs('guru_bk.konseling*') ? 'active' : '' }}">
    <i class="fa-solid fa-comments"></i><span x-show="sidebarOpen">Kelola Konseling</span>
</a>
<a href="{{ route('guru_bk.laporan') }}" class="sidebar-link {{ request()->routeIs('guru_bk.laporan') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-pie"></i><span x-show="sidebarOpen">Laporan</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akun</p>
<a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="fa-solid fa-user-gear"></i><span x-show="sidebarOpen">Profil Saya</span>
</a>
@endsection

@section('content')
<div class="space-y-6">
    <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-10 translate-x-10"></div>
        <div class="relative">
            <p class="text-blue-200 text-sm">Layanan Bimbingan & Konseling</p>
            <h2 class="text-2xl font-bold mt-1">{{ auth()->user()->name }}</h2>
            <p class="text-blue-200 text-sm mt-0.5">Guru BK / Konselor Sekolah</p>
        </div>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card text-center">
            <div class="w-12 h-12 bg-yellow-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-clock text-yellow-500 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-yellow-500">{{ $stats['pending'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Menunggu</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-spinner text-blue-500 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-blue-500">{{ $stats['berlangsung'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Berlangsung</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-12 h-12 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-green-500">{{ $stats['selesai'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Selesai</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                <i class="fa-solid fa-users text-purple-500 text-xl"></i>
            </div>
            <p class="text-3xl font-bold text-gray-800">{{ $stats['total_siswa'] }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Siswa</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-bold text-gray-800">Permintaan Konseling Terbaru</h3>
            <a href="{{ route('guru_bk.konseling.index') }}" class="text-xs text-secondary font-semibold hover:underline">Lihat Semua →</a>
        </div>
        <div class="space-y-3">
            @forelse($konseling_terbaru as $k)
            <a href="{{ route('guru_bk.konseling.show', $k->id) }}"
               class="flex items-center gap-4 p-3.5 rounded-xl hover:bg-gray-50 transition border border-gray-50 hover:border-gray-200">
                <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center flex-shrink-0 text-white font-bold text-sm">
                    {{ substr($k->siswa->user->name,0,1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">{{ $k->siswa->user->name }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $k->topik }}</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                        {{ $k->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($k->status === 'disetujui' ? 'bg-green-100 text-green-700' :
                           ($k->status === 'berlangsung' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600')) }}">
                        {{ ucfirst($k->status) }}
                    </span>
                    <i class="fa-solid fa-chevron-right text-gray-300 text-xs"></i>
                </div>
            </a>
            @empty
            <div class="py-10 text-center">
                <i class="fa-regular fa-comments text-4xl text-gray-200 mb-3 block"></i>
                <p class="text-sm text-gray-400">Belum ada permintaan konseling</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
