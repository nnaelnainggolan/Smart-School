@extends('layouts.app')
@section('title', 'Konseling')
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
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Konseling Digital</h2><p class="text-sm text-gray-500">Ajukan dan pantau sesi konseling Anda</p></div>
        <a href="{{ route('siswa.konseling.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Ajukan Konseling</a>
    </div>
    <div class="space-y-3">
        @forelse($konseling as $k)
        <a href="{{ route('siswa.konseling.show', $k->id) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:border-secondary hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs font-medium capitalize">{{ $k->jenis }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            {{ $k->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               ($k->status === 'disetujui' ? 'bg-green-100 text-green-700' :
                               ($k->status === 'berlangsung' ? 'bg-blue-100 text-blue-700' :
                               ($k->status === 'selesai' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600'))) }}">
                            {{ ucfirst($k->status) }}
                        </span>
                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $k->topik }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5 line-clamp-1">{{ $k->deskripsi }}</p>
                    @if($k->guruBK)<p class="text-xs text-gray-400 mt-1">Konselor: {{ $k->guruBK->user->name }}</p>@endif
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs text-gray-400">{{ $k->created_at->diffForHumans() }}</p>
                    <svg class="w-4 h-4 text-gray-300 ml-auto mt-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </div>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            <p class="text-gray-400 mb-3">Belum ada sesi konseling</p>
            <a href="{{ route('siswa.konseling.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-secondary transition">Ajukan Konseling</a>
        </div>
        @endforelse
    </div>
    <div>{{ $konseling->links() }}</div>
</div>
@endsection
