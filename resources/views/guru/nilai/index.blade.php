@extends('layouts.app')
@section('title', 'Input Nilai')
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
    <h2 class="text-xl font-bold text-gray-800">Input Nilai Siswa</h2>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-700 mb-4">Pilih Kelas & Mata Pelajaran</h3>
        <form method="GET" action="{{ route('guru.nilai.form') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <select name="kelas_id" required class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">-- Pilih Kelas --</option>
                @foreach($jadwal->pluck('kelas')->unique('id') as $k)
                    <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <select name="mata_pelajaran_id" required class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">-- Pilih Mapel --</option>
                @foreach($jadwal->pluck('mataPelajaran')->unique('id') as $m)
                    <option value="{{ $m->id }}" {{ request('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_mapel }}</option>
                @endforeach
            </select>
            <select name="tahun_ajaran" required class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach(\App\Services\SchoolContext::years() as $ta)<option value="{{ $ta }}" {{ request('tahun_ajaran', \App\Services\SchoolContext::period()['tahun_ajaran']) == $ta ? 'selected' : '' }}>{{ $ta }}</option>@endforeach
            </select>
            <select name="semester" required class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="1" {{ request('semester', \App\Services\SchoolContext::period()['semester']) == '1' ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ request('semester', \App\Services\SchoolContext::period()['semester']) == '2' ? 'selected' : '' }}>Semester 2</option>
            </select>
            <div class="sm:col-span-2 lg:col-span-4">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition">Tampilkan Form Nilai</button>
            </div>
        </form>
    </div>
    @if(isset($jadwal) && $jadwal->count() === 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 text-sm text-yellow-700">Belum ada jadwal mengajar yang tersedia. Hubungi admin untuk penambahan jadwal.</div>
    @endif
</div>
@endsection
