@extends('layouts.app')
@section('title', 'Kelola Konseling')
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
<div class="space-y-5">
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">Kelola Konseling Siswa</h2>
        <form class="flex gap-2">
            <select name="status" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">Semua Status</option>
                @foreach(['pending','disetujui','berlangsung','selesai','ditolak'] as $s)<option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>@endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Filter</button>
        </form>
    </div>
    <div class="space-y-3">
        @forelse($konseling as $k)
        <a href="{{ route('guru_bk.konseling.show', $k->id) }}" class="block bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:border-secondary hover:shadow-md transition">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm">{{ substr($k->siswa->user->name,0,1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <p class="font-semibold text-gray-800 text-sm">{{ $k->siswa->user->name }}</p>
                            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs capitalize">{{ $k->jenis }}</span>
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $k->topik }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $k->deskripsi }}</p>
                    </div>
                </div>
                <div class="flex-shrink-0 text-right">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium
                        {{ $k->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($k->status === 'disetujui' ? 'bg-green-100 text-green-700' :
                           ($k->status === 'berlangsung' ? 'bg-blue-100 text-blue-700' :
                           ($k->status === 'selesai' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600'))) }}">
                        {{ ucfirst($k->status) }}
                    </span>
                    <p class="text-xs text-gray-400 mt-1">{{ $k->created_at->diffForHumans() }}</p>
                </div>
            </div>
        </a>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <p class="text-gray-400">Tidak ada data konseling</p>
        </div>
        @endforelse
    </div>
    <div>{{ $konseling->links() }}</div>
</div>
@endsection
