@extends('layouts.app')
@section('title', 'Detail Konseling')
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
<div class="max-w-2xl space-y-4">
    <!-- Info Siswa & Topik -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-100">
            <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-lg">{{ substr($konseling->siswa->user->name,0,1) }}</span>
            </div>
            <div>
                <p class="font-bold text-gray-800">{{ $konseling->siswa->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $konseling->siswa->kelas?->nama_kelas ?? '-' }} · NIS: {{ $konseling->siswa->nis }}</p>
            </div>
        </div>
        <h3 class="font-bold text-gray-800">{{ $konseling->topik }}</h3>
        <p class="text-sm text-gray-600 mt-1">{{ $konseling->deskripsi }}</p>
        <div class="flex gap-2 mt-2">
            <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs capitalize">{{ $konseling->jenis }}</span>
            <span class="px-2 py-0.5 rounded text-xs font-medium {{ $konseling->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : ($konseling->status === 'selesai' ? 'bg-gray-100 text-gray-600' : 'bg-green-100 text-green-700') }}">{{ ucfirst($konseling->status) }}</span>
        </div>
    </div>

    <!-- Update Status -->
    @if($konseling->status !== 'selesai')
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5" x-data="{ open: false }">
        <button @click="open = !open" class="flex items-center justify-between w-full">
            <span class="font-semibold text-gray-800 text-sm">Update Status & Catatan</span>
            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-cloak class="mt-4">
            <form method="POST" action="{{ route('guru_bk.konseling.update', $konseling->id) }}" class="space-y-3">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-3">
                    <div><label class="block text-xs font-semibold text-gray-600 mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                            @foreach(['pending','disetujui','berlangsung','selesai','ditolak'] as $s)
                            <option value="{{ $s }}" {{ $konseling->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="block text-xs font-semibold text-gray-600 mb-1">Status Psikologis</label>
                        <select name="status_psikologis" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                            <option value="">-- Pilih --</option>
                            <option value="baik" {{ $konseling->status_psikologis === 'baik' ? 'selected' : '' }}>Baik</option>
                            <option value="perlu_perhatian" {{ $konseling->status_psikologis === 'perlu_perhatian' ? 'selected' : '' }}>Perlu Perhatian</option>
                            <option value="kritis" {{ $konseling->status_psikologis === 'kritis' ? 'selected' : '' }}>Kritis</option>
                        </select>
                    </div>
                </div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Jadwal Konseling</label>
                    <input type="datetime-local" name="jadwal_konseling" value="{{ $konseling->jadwal_konseling ? \Carbon\Carbon::parse($konseling->jadwal_konseling)->format('Y-m-d\TH:i') : '' }}" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div><label class="block text-xs font-semibold text-gray-600 mb-1">Catatan BK</label>
                    <textarea name="catatan_bk" rows="3" placeholder="Catatan untuk siswa..." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ $konseling->catatan_bk }}</textarea>
                </div>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition">Simpan Perubahan</button>
            </form>
        </div>
    </div>
    @endif

    <!-- Chat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
            <p class="text-sm font-semibold text-gray-700">Chat Konseling</p>
        </div>
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
            <p class="text-center text-sm text-gray-400 py-6">Belum ada pesan</p>
            @endforelse
        </div>
        @if(in_array($konseling->status, ['disetujui','berlangsung']))
        <div class="p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('guru_bk.konseling.chat', $konseling->id) }}" class="flex gap-2">
                @csrf
                <input type="text" name="pesan" required placeholder="Ketik balasan..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-xl hover:bg-secondary transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>
        @endif
    </div>
</div>
@push('scripts')
<script>const c = document.getElementById('chatContainer'); if(c) c.scrollTop = c.scrollHeight;</script>
@endpush
@endsection
