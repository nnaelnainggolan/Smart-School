@extends('layouts.app')
@section('title', 'Absensi Saya')
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
<div class="space-y-5">
    <div class="flex flex-wrap gap-3 items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">Rekap Absensi</h2>
        <form class="flex gap-2">
            <select name="bulan" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach(range(1,12) as $b)<option value="{{ $b }}" {{ $bulan == $b ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($b)->locale('id')->monthName }}</option>@endforeach
            </select>
            <select name="tahun" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach([date('Y'), date('Y')-1] as $t)<option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>@endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Tampilkan</button>
        </form>
    </div>

    <!-- Rekap Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach(['Hadir' => 'green', 'Izin' => 'yellow', 'Sakit' => 'blue', 'Alpha' => 'red'] as $st => $color)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 text-center">
            <p class="text-2xl font-bold text-{{ $color }}-600">{{ $rekap[$st] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $st }}</p>
        </div>
        @endforeach
    </div>

    <!-- Grafik -->
    @if($absensi->count())
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Kehadiran Bulan Ini</h3>
        <canvas id="absensiChart" height="80"></canvas>
    </div>
    @endif

    <!-- List Absensi -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($absensi as $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5 text-gray-600 text-xs">{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $a->jadwal->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3.5">
                        <span class="px-2.5 py-1 rounded-full text-xs font-medium
                            {{ $a->status === 'Hadir' ? 'bg-green-100 text-green-700' :
                               ($a->status === 'Alpha' ? 'bg-red-100 text-red-700' :
                               ($a->status === 'Sakit' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700')) }}">
                            {{ $a->status }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $a->keterangan ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-5 py-10 text-center text-gray-400">Tidak ada data absensi bulan ini</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@push('scripts')
@if($absensi->count())
<script>
const labels = {!! json_encode($absensi->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d/m'))->unique()->values()) !!};
const ctx = document.getElementById('absensiChart').getContext('2d');
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{ data: [{{ $rekap['Hadir'] ?? 0 }}, {{ $rekap['Izin'] ?? 0 }}, {{ $rekap['Sakit'] ?? 0 }}, {{ $rekap['Alpha'] ?? 0 }}], backgroundColor: ['#10B981','#F59E0B','#2E86AB','#EF4444'], borderWidth: 0 }]
    },
    options: { responsive: true, cutout: '65%', plugins: { legend: { position: 'right' } } }
});
</script>
@endif
@endpush
@endsection
