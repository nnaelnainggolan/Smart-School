@extends('layouts.app')
@section('title', 'Pesan dengan Guru')
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
<div class="max-w-2xl space-y-5">
    <h2 class="text-xl font-bold text-gray-800">Pesan dengan Guru</h2>

    <!-- Form Kirim Pesan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4 text-sm">Kirim Pesan ke Guru</h3>
        <form method="POST" action="{{ route('orang_tua.pesan') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Guru</label>
                <select name="penerima_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guru as $g)
                        <option value="{{ $g->id }}">{{ $g->name }} ({{ str_replace('_',' ', $g->role) }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan</label>
                <textarea name="pesan" rows="3" required placeholder="Tuliskan pesan Anda..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none"></textarea>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition">Kirim Pesan</button>
        </form>
    </div>

    <!-- Riwayat Pesan -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold text-gray-800">Riwayat Percakapan</h3></div>
        <div class="divide-y divide-gray-50">
            @forelse($pesan as $p)
            <div class="px-5 py-4 {{ !$p->dibaca && $p->penerima_id === auth()->id() ? 'bg-blue-50' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg {{ $p->pengirim_id === auth()->id() ? 'bg-accent' : 'bg-primary' }} flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ substr($p->pengirim->name,0,1) }}</span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">{{ $p->pengirim->name }}</p>
                            <p class="text-xs text-gray-400">→ {{ $p->penerima->name }}</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 flex-shrink-0">{{ $p->created_at->diffForHumans() }}</p>
                </div>
                <p class="text-sm text-gray-700 mt-2 ml-10">{{ $p->pesan }}</p>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-400">
                <p class="text-sm">Belum ada percakapan</p>
            </div>
            @endforelse
        </div>
        @if($pesan->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">{{ $pesan->links() }}</div>
        @endif
    </div>
</div>
@endsection
