@extends('layouts.app')
@section('title', 'Upload Materi')
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
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Upload Materi Pelajaran</h2></div>
        <form method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Materi <span class="text-red-500">*</span></label><input type="text" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="mata_pelajaran_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapel as $m)<option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas</label>
                <select name="kelas_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="tahun_ajaran" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        @foreach(\App\Services\SchoolContext::years() as $year)<option @selected($year === \App\Services\SchoolContext::period()['tahun_ajaran'])>{{ $year }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="1">Semester 1</option><option value="2">Semester 2</option>
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label><textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('deskripsi') }}</textarea></div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">File Materi <span class="text-xs text-gray-400 font-normal">(PDF, DOC, PPT, max 10MB)</span></label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Upload Materi</button>
                <a href="{{ route('guru.materi.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
