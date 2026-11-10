@extends('layouts.app')
@section('title', 'Materi Pelajaran')
@section('sidebar')
<a href="{{ route('guru.dashboard') }}" class="sidebar-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <i class="fa-solid fa-house"></i><span x-show="sidebarOpen">Dashboard</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Kehadiran</p>
<a href="{{ route('guru.absensi.pilih') }}" class="sidebar-link {{ request()->routeIs('guru.absensi.pilih') ? 'active' : '' }}">
    <i class="fa-solid fa-clipboard-check"></i><span x-show="sidebarOpen">Input Absensi</span>
</a>
<a href="{{ route('guru.absensi.index') }}" class="sidebar-link {{ request()->routeIs('guru.absensi.index') ? 'active' : '' }}">
    <i class="fa-solid fa-table-list"></i><span x-show="sidebarOpen">Riwayat Absensi</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Akademik</p>
<a href="{{ route('guru.nilai.index') }}" class="sidebar-link {{ request()->routeIs('guru.nilai*') ? 'active' : '' }}">
    <i class="fa-solid fa-chart-bar"></i><span x-show="sidebarOpen">Input Nilai</span>
</a>
<a href="{{ route('guru.materi.index') }}" class="sidebar-link {{ request()->routeIs('guru.materi*') ? 'active' : '' }}">
    <i class="fa-solid fa-book-open"></i><span x-show="sidebarOpen">Materi Pelajaran</span>
</a>
<p class="sidebar-section" x-show="sidebarOpen">Komunikasi</p>
<a href="{{ route('guru.pesan.index') }}" class="sidebar-link {{ request()->routeIs('guru.pesan*') ? 'active' : '' }}">
    <i class="fa-solid fa-envelope"></i><span x-show="sidebarOpen">Pesan Orang Tua</span>
</a>
@endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Materi Pelajaran</h2></div>
        <a href="{{ route('guru.materi.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Upload Materi</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($materi as $m)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <form method="POST" action="{{ route('guru.materi.destroy', $m->id) }}" onsubmit="return confirm('Hapus materi ini?')">@csrf @method('DELETE')<button class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
            </div>
            <h3 class="font-bold text-gray-800">{{ $m->judul }}</h3>
            <p class="text-sm text-gray-500 mt-0.5">{{ $m->mataPelajaran->nama_mapel }}</p>
            @if($m->kelas)<p class="text-xs text-gray-400">Kelas: {{ $m->kelas->nama_kelas }}</p>@endif
            <p class="text-xs text-gray-400 mt-1">{{ $m->tahun_ajaran }} · Semester {{ $m->semester }}</p>
            @if($m->file_path)
            <a href="{{ route('school.material', $m) }}" target="_blank" class="mt-3 inline-flex items-center gap-1 text-secondary text-xs font-medium hover:underline">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Unduh File ({{ strtoupper($m->tipe_file) }})
            </a>
            @endif
        </div>
        @empty
        <div class="col-span-3 py-12 text-center text-gray-400">Belum ada materi yang diupload</div>
        @endforelse
    </div>
    <div>{{ $materi->links() }}</div>
</div>
@endsection
