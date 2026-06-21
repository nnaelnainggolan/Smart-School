@extends('layouts.app')
@section('title', 'Absensi Anak')
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
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Absensi — {{ $siswa->user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $siswa->kelas?->nama_kelas ?? '-' }}</p>
        </div>
        <form class="flex gap-2">
            <select name="bulan" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach(range(1,12) as $b)
                    <option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}</option>
                @endforeach
            </select>
            <select name="tahun" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach([date('Y'), date('Y')-1] as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Tampilkan</button>
        </form>
    </div>

    <!-- Rekap -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach(['Hadir' => ['bg-green-100','text-green-600'], 'Izin' => ['bg-yellow-100','text-yellow-600'], 'Sakit' => ['bg-blue-100','text-blue-600'], 'Alpha' => ['bg-red-100','text-red-600']] as $st => [$bg, $tc])
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <div class="w-10 h-10 {{ $bg }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <span class="font-bold {{ $tc }}">{{ $rekap[$st] ?? 0 }}</span>
            </div>
            <p class="text-xs text-gray-500">{{ $st }}</p>
        </div>
        @endforeach
    </div>

    @if(($rekap['Alpha'] ?? 0) > 3)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        <div>
            <p class="text-sm font-semibold text-red-700">Perhatian!</p>
            <p class="text-sm text-red-600">{{ $siswa->user->name }} memiliki <strong>{{ $rekap['Alpha'] }}</strong> ketidakhadiran tanpa keterangan (Alpha) bulan ini. Harap segera menghubungi pihak sekolah.</p>
        </div>
    </div>
    @endif

    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($absensi as $a)
                <tr class="hover:bg-gray-50 {{ $a->status === 'Alpha' ? 'bg-red-50' : '' }}">
                    <td class="px-5 py-3 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $a->jadwal->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $a->status === 'Hadir' ? 'bg-green-100 text-green-700' :
                               ($a->status === 'Alpha' ? 'bg-red-100 text-red-700' :
                               ($a->status === 'Sakit' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ $a->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-400 text-xs">{{ $a->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Tidak ada data absensi bulan ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
