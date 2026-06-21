@extends('layouts.app')
@section('title', 'Laporan Konseling')
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
<div class="space-y-6">
    <h2 class="text-xl font-bold text-gray-800">Laporan Konseling</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Konseling per Jenis</h3>
            @if($perJenis->count())
            <canvas id="jenisChart" height="200"></canvas>
            @else
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
            @endif
        </div>
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Tren per Bulan</h3>
            @if($perBulan->count())
            <canvas id="bulanChart" height="200"></canvas>
            @else
            <p class="text-sm text-gray-400 text-center py-6">Belum ada data</p>
            @endif
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100"><h3 class="font-bold text-gray-800">Daftar Konseling Selesai</h3></div>
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Siswa</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Topik</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status Psikologis</th>
                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($konseling as $k)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-3 font-medium text-gray-800">{{ $k->siswa->user->name }}</td>
                <td class="px-5 py-3 text-gray-600">{{ $k->topik }}</td>
                <td class="px-5 py-3"><span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded text-xs capitalize">{{ $k->jenis }}</span></td>
                <td class="px-5 py-3">
                    @if($k->status_psikologis)
                    <span class="px-2 py-0.5 rounded text-xs font-medium {{ $k->status_psikologis === 'baik' ? 'bg-green-100 text-green-700' : ($k->status_psikologis === 'kritis' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700') }}">
                        {{ str_replace('_', ' ', $k->status_psikologis) }}
                    </span>
                    @else<span class="text-gray-400 text-xs">-</span>@endif
                </td>
                <td class="px-5 py-3 text-gray-500 text-xs">{{ $k->created_at->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada konseling yang selesai</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
@if($perJenis->count())
<script>
new Chart(document.getElementById('jenisChart').getContext('2d'), {
    type: 'pie',
    data: {
        labels: {!! json_encode($perJenis->keys()) !!},
        datasets: [{ data: {!! json_encode($perJenis->values()) !!}, backgroundColor: ['#1E3A5F','#2E86AB','#F4A261','#10B981'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endif
@if($perBulan->count())
<script>
new Chart(document.getElementById('bulanChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($perBulan->keys()) !!},
        datasets: [{ label: 'Jumlah Konseling', data: {!! json_encode($perBulan->values()) !!}, backgroundColor: 'rgba(46,134,171,0.7)', borderRadius: 6, borderWidth: 0 }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } }, plugins: { legend: { display: false } } }
});
</script>
@endif
@endpush
@endsection
