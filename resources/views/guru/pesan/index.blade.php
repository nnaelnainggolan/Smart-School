@extends('layouts.app')
@section('title', 'Pesan ke Orang Tua')
@section('subtitle', 'Komunikasi dengan orang tua siswa')
@section('sidebar')
<a href="{{ route('guru.dashboard') }}" class="sidebar-link"><i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span></a>
<p class="sidebar-section" x-show="sidebarOpen">Kehadiran</p>
<a href="{{ route('guru.absensi.pilih') }}" class="sidebar-link"><i class="fa-solid fa-clipboard-check"></i><span x-show="sidebarOpen">Input Absensi</span></a>
<a href="{{ route('guru.absensi.index') }}" class="sidebar-link"><i class="fa-solid fa-table-list"></i><span x-show="sidebarOpen">Riwayat Absensi</span></a>
<p class="sidebar-section" x-show="sidebarOpen">Akademik</p>
<a href="{{ route('guru.nilai.index') }}" class="sidebar-link"><i class="fa-solid fa-chart-bar"></i><span x-show="sidebarOpen">Input Nilai</span></a>
<a href="{{ route('guru.materi.index') }}" class="sidebar-link"><i class="fa-solid fa-book-open"></i><span x-show="sidebarOpen">Materi Pelajaran</span></a>
<p class="sidebar-section" x-show="sidebarOpen">Komunikasi</p>
<a href="{{ route('guru.pesan.index') }}" class="sidebar-link active"><i class="fa-solid fa-envelope"></i><span x-show="sidebarOpen">Pesan Orang Tua</span></a>
@endsection
@section('content')
<div class="max-w-2xl space-y-5">
    <h2 class="text-xl font-bold text-gray-800">Komunikasi dengan Orang Tua</h2>

    <!-- Form Kirim -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4 text-sm">Kirim Pesan ke Orang Tua Siswa</h3>
        <form method="POST" action="{{ route('guru.pesan.index') }}" class="space-y-3">
            @csrf
            @if($errors->any())<div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700">{{ $errors->first() }}</div>@endif
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pilih Siswa</label>
                <select name="siswa_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaList as $s)
                        <option value="{{ $s->id }}">{{ $s->user->name }} — {{ $s->kelas?->nama_kelas }} (Ortu: {{ $s->orangTua?->nama_ayah ?? 'Belum ada' }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pesan</label>
                <textarea name="pesan" rows="3" required placeholder="Tuliskan pesan untuk orang tua..." class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary resize-none"></textarea>
            </div>
            <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition">
                <i class="fa-solid fa-paper-plane mr-1.5"></i>Kirim Pesan
            </button>
        </form>
    </div>

    <!-- Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold text-gray-800">Riwayat Percakapan</h3></div>
        <div class="divide-y divide-gray-50">
            @forelse($pesan as $p)
            <div class="px-5 py-4 {{ !$p->dibaca && $p->penerima_id === auth()->id() ? 'bg-blue-50' : '' }}">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg {{ $p->pengirim_id === auth()->id() ? 'bg-accent' : 'bg-primary' }} flex items-center justify-center text-white text-xs font-bold">
                            {{ substr($p->pengirim->name,0,1) }}
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-700">{{ $p->pengirim->name }}</p>
                            <p class="text-xs text-gray-400">→ {{ $p->penerima->name }} @if($p->siswa) ({{ $p->siswa->user->name }}) @endif</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 flex-shrink-0">{{ $p->created_at->diffForHumans() }}</p>
                </div>
                <p class="text-sm text-gray-700 mt-2 ml-10">{{ $p->pesan }}</p>
            </div>
            @empty
            <div class="px-5 py-10 text-center text-gray-400">
                <i class="fa-regular fa-comment-dots text-3xl text-gray-200 mb-2 block"></i>
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
