@extends('layouts.app')
@section('title', 'Laporan Konseling Anak')
@section('sidebar')
<a href="{{ route('orang_tua.dashboard') }}" class="sidebar-link {{ request()->routeIs('orang_tua.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Monitoring</p>
<a href="{{ route('orang_tua.nilai') }}" class="sidebar-link {{ request()->routeIs('orang_tua.nilai') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-bar"></i><span x-show="sidebarOpen">Nilai Anak</span>
</a>
<a href="{{ route('orang_tua.absensi') }}" class="sidebar-link {{ request()->routeIs('orang_tua.absensi') ? 'active' : '' }}">
    <i class="fa-solid fa-clipboard-list"></i><span x-show="sidebarOpen">Absensi Anak</span>
</a>
<a href="{{ route('orang_tua.konseling') }}" class="sidebar-link {{ request()->routeIs('orang_tua.konseling') ? 'active' : '' }}">
    <i class="fa-solid fa-heart-pulse"></i><span x-show="sidebarOpen">Laporan Konseling</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Komunikasi</p>
<a href="{{ route('orang_tua.pesan') }}" class="sidebar-link {{ request()->routeIs('orang_tua.pesan') ? 'active' : '' }}">
    <i class="fa-solid fa-envelope"></i><span x-show="sidebarOpen">Pesan Guru</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akun</p>
<a href="{{ route('profile.edit') }}" class="sidebar-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
    <i class="fa-solid fa-user-gear"></i><span x-show="sidebarOpen">Profil Saya</span>
</a>
@endsection
@section('content')
<div class="space-y-5">
    <div>
        <h2 class="text-xl font-bold text-gray-800">Laporan Konseling — {{ $siswa->user->name }}</h2>
        <p class="text-sm text-gray-500">Riwayat dan status konseling anak Anda di sekolah</p>
    </div>
    <div class="space-y-3">
        @forelse($konseling as $k)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs capitalize">{{ $k->jenis }}</span>
                        <span class="px-2 py-0.5 rounded text-xs font-medium
                            {{ $k->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                               ($k->status === 'disetujui' ? 'bg-green-100 text-green-700' :
                               ($k->status === 'berlangsung' ? 'bg-blue-100 text-blue-700' :
                               ($k->status === 'selesai' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600'))) }}">
                            {{ ucfirst($k->status) }}
                        </span>

                    </div>
                    <h3 class="font-semibold text-gray-800">{{ $k->topik }}</h3>
                    @if($k->guruBK)
                    <p class="text-xs text-gray-500 mt-1">Konselor: {{ $k->guruBK->user->name }}</p>
                    @endif
                    @if($k->ringkasan_ortu)
                    <div class="mt-2 bg-green-50 border border-green-100 rounded-lg p-2.5">
                        <p class="text-xs font-semibold text-green-700">Catatan Guru BK:</p>
                        <p class="text-xs text-green-800 mt-0.5">{{ $k->ringkasan_ortu }}</p>
                    </div>
                    @endif
                    @if($k->jadwal_konseling)
                    <p class="text-xs text-gray-400 mt-1">📅 Jadwal: {{ \Carbon\Carbon::parse($k->jadwal_konseling)->format('d M Y, H:i') }}</p>
                    @endif
                </div>
                <p class="text-xs text-gray-400 flex-shrink-0">{{ $k->created_at->format('d M Y') }}</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            <p class="text-gray-400">Belum ada riwayat konseling</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
