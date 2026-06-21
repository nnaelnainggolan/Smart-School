@extends('layouts.app')
@section('title', 'Chat Konseling')
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
<div class="max-w-2xl space-y-4">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start justify-between">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs font-medium capitalize">{{ $konseling->jenis }}</span>
                    <span class="px-2 py-0.5 rounded text-xs font-medium
                        {{ $konseling->status === 'pending' ? 'bg-yellow-100 text-yellow-700' :
                           ($konseling->status === 'disetujui' ? 'bg-green-100 text-green-700' :
                           ($konseling->status === 'berlangsung' ? 'bg-blue-100 text-blue-700' :
                           ($konseling->status === 'selesai' ? 'bg-gray-100 text-gray-600' : 'bg-red-100 text-red-600'))) }}">
                        {{ ucfirst($konseling->status) }}
                    </span>
                </div>
                <h2 class="text-lg font-bold text-gray-800">{{ $konseling->topik }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $konseling->deskripsi }}</p>
            </div>
            <a href="{{ route('siswa.konseling.index') }}" class="p-2 text-gray-400 hover:bg-gray-100 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></a>
        </div>
        @if($konseling->guruBK)
        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-2">
            <div class="w-7 h-7 bg-green-600 rounded-lg flex items-center justify-center"><span class="text-white text-xs font-bold">{{ substr($konseling->guruBK->user->name,0,1) }}</span></div>
            <div><p class="text-xs font-medium text-gray-700">{{ $konseling->guruBK->user->name }}</p><p class="text-xs text-gray-400">Guru BK / Konselor</p></div>
        </div>
        @else
        <p class="mt-3 text-xs text-yellow-600 bg-yellow-50 px-3 py-2 rounded-lg">Menunggu Guru BK menangani permintaan konseling ini...</p>
        @endif
        @if($konseling->catatan_bk)
        <div class="mt-3 bg-green-50 border border-green-100 rounded-xl p-3">
            <p class="text-xs font-semibold text-green-700 mb-1">Catatan Guru BK:</p>
            <p class="text-sm text-green-800">{{ $konseling->catatan_bk }}</p>
        </div>
        @endif
    </div>

    <!-- Chat Area -->
    @if(in_array($konseling->status, ['disetujui','berlangsung','selesai']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <p class="text-sm font-semibold text-gray-700">Sesi Chat Konseling</p>
        </div>
        <!-- Pesan -->
        <div class="p-4 space-y-3 max-h-80 overflow-y-auto" id="chatContainer">
            @forelse($konseling->chat as $c)
            <div class="flex {{ $c->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs">
                    @if($c->user_id !== auth()->id())
                    <p class="text-xs text-gray-400 mb-1 px-1">{{ $c->user->name }}</p>
                    @endif
                    <div class="px-4 py-2.5 rounded-2xl text-sm {{ $c->user_id === auth()->id() ? 'bg-primary text-white rounded-br-sm' : 'bg-gray-100 text-gray-800 rounded-bl-sm' }}">
                        {{ $c->pesan }}
                    </div>
                    <p class="text-xs text-gray-400 mt-1 px-1 {{ $c->user_id === auth()->id() ? 'text-right' : '' }}">{{ $c->created_at->format('H:i') }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-sm text-gray-400 py-6">Belum ada pesan. Mulai percakapan dengan Guru BK.</p>
            @endforelse
        </div>
        <!-- Input Pesan -->
        @if($konseling->status !== 'selesai')
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('siswa.konseling.chat', $konseling->id) }}" class="flex gap-2">
                @csrf
                <input type="text" name="pesan" required placeholder="Ketik pesan..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-secondary transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
        @else
        <div class="p-4 border-t border-gray-100 text-center text-sm text-gray-400 bg-gray-50">Sesi konseling telah selesai</div>
        @endif
    </div>
    @endif
</div>
@push('scripts')
<script>
const chatContainer = document.getElementById('chatContainer');
if (chatContainer) chatContainer.scrollTop = chatContainer.scrollHeight;
</script>
@endpush
@endsection
