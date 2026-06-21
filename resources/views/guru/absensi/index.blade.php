@extends('layouts.app')
@section('title', 'Riwayat Absensi')
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
    <h2 class="text-xl font-bold text-gray-800">Riwayat Absensi</h2>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($riwayat as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $r->siswa->user->name }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $r->jadwal->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3.5 text-gray-500">{{ $r->jadwal->kelas->nama_kelas }}</td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($r->tanggal)->format('d M Y') }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $r->status === 'Hadir' ? 'bg-green-100 text-green-700' :
                               ($r->status === 'Alpha' ? 'bg-red-100 text-red-700' :
                               ($r->status === 'Sakit' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ $r->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat absensi</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $riwayat->links() }}</div>
    </div>
</div>
@endsection
