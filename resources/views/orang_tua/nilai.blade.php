@extends('layouts.app')
@section('title', 'Nilai Anak')
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
            <h2 class="text-xl font-bold text-gray-800">Nilai — {{ $siswa->user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $siswa->kelas?->nama_kelas ?? '-' }} · NIS: {{ $siswa->nis }}</p>
        </div>
        <form class="flex gap-2">
            <select name="tahun_ajaran" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="2024/2025" {{ $tahunAjaran == '2024/2025' ? 'selected' : '' }}>2024/2025</option>
                <option value="2025/2026" {{ $tahunAjaran == '2025/2026' ? 'selected' : '' }}>2025/2026</option>
            </select>
            <select name="semester" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Semester 2</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Tampilkan</button>
        </form>
    </div>

    @if($nilai->count())
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Nilai {{ $tahunAjaran }} Semester {{ $semester }}</h3>
        <canvas id="nilaiChart" height="100"></canvas>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex justify-between">
            <h3 class="font-bold text-gray-800">Rapor Digital</h3>
            <span class="text-sm text-gray-500">Rata-rata: <strong>{{ number_format($nilai->avg('nilai_akhir'), 2) }}</strong></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Harian</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">UTS</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">UAS</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Akhir</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Predikat</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">KKM</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($nilai as $n)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $n->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $n->nilai_harian ?? '-' }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $n->nilai_uts ?? '-' }}</td>
                    <td class="px-5 py-3 text-center text-gray-600">{{ $n->nilai_uas ?? '-' }}</td>
                    <td class="px-5 py-3 text-center font-bold {{ $n->nilai_akhir >= $n->mataPelajaran->kkm ? 'text-green-600' : 'text-red-500' }}">
                        {{ $n->nilai_akhir ? number_format($n->nilai_akhir, 1) : '-' }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $n->predikat === 'A' ? 'bg-green-100 text-green-700' : ($n->predikat === 'B' ? 'bg-blue-100 text-blue-700' : ($n->predikat === 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">{{ $n->predikat ?? '-' }}</span>
                    </td>
                    <td class="px-5 py-3 text-center text-xs text-gray-500">{{ $n->mataPelajaran->kkm }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10"/></svg>
        <p class="text-gray-400">Belum ada nilai untuk semester ini</p>
    </div>
    @endif
</div>
@push('scripts')
@if($nilai->count())
<script>
new Chart(document.getElementById('nilaiChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($nilai->pluck('mataPelajaran.nama_mapel')) !!},
        datasets: [{
            label: 'Nilai Akhir',
            data: {!! json_encode($nilai->pluck('nilai_akhir')) !!},
            backgroundColor: {!! json_encode($nilai->map(fn($n) => $n->nilai_akhir >= $n->mataPelajaran->kkm ? 'rgba(16,185,129,0.7)' : 'rgba(239,68,68,0.7)')->toArray()) !!},
            borderRadius: 6, borderWidth: 0
        }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } }, plugins: { legend: { display: false } } }
});
</script>
@endif
@endpush
@endsection
