@extends('layouts.app')
@section('title', 'Nilai & Rapor')
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
        <h2 class="text-xl font-bold text-gray-800">Nilai & Rapor Digital</h2>
        <form class="flex gap-2">
            <select name="tahun_ajaran" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                @foreach(\App\Services\SchoolContext::years() as $year)<option value="{{ $year }}" @selected($tahunAjaran === $year)>{{ $year }}</option>@endforeach
            </select>
            <select name="semester" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Semester 2</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-secondary text-white rounded-xl text-sm font-medium">Tampilkan</button>
        </form>
    </div>

    @if($nilai->count())
    <!-- Grafik Nilai -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Grafik Nilai</h3>
        <canvas id="nilaiChart" height="100"></canvas>
    </div>

    <!-- Tabel Rapor -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">Rapor Digital — {{ $tahunAjaran }} Semester {{ $semester }}</h3>
            <div class="text-sm text-gray-500">Rata-rata: <strong class="text-gray-800">{{ $rataRata ? number_format($rataRata,2) : '-' }}</strong></div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Harian</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">UTS</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">UAS</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Akhir</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Predikat</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Status</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @foreach($nilai as $n)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $n->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3.5 text-center text-gray-600">{{ $n->nilai_harian ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center text-gray-600">{{ $n->nilai_uts ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center text-gray-600">{{ $n->nilai_uas ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center font-bold {{ $n->nilai_akhir >= $n->mataPelajaran->kkm ? 'text-green-600' : 'text-red-500' }}">
                        {{ $n->nilai_akhir !== null ? number_format($n->nilai_akhir,1) : '-' }}
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-bold {{ $n->predikat === 'A' ? 'bg-green-100 text-green-700' : ($n->predikat === 'B' ? 'bg-blue-100 text-blue-700' : ($n->predikat === 'C' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $n->predikat ?? '-' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($n->nilai_akhir)
                            @if($n->nilai_akhir >= $n->mataPelajaran->kkm)
                                <span class="text-xs text-green-600 font-medium">✓ Tuntas</span>
                            @else
                                <span class="text-xs text-red-500 font-medium">✗ Remidial</span>
                            @endif
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-2xl p-12 shadow-sm border border-gray-100 text-center">
        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        <p class="text-gray-400">Belum ada nilai untuk semester ini</p>
    </div>
    @endif
</div>
@push('scripts')
@if($nilai->count())
<script>
const ctx = document.getElementById('nilaiChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($nilai->pluck('mataPelajaran.nama_mapel')) !!},
        datasets: [{
            label: 'Nilai Akhir',
            data: {!! json_encode($nilai->pluck('nilai_akhir')) !!},
            backgroundColor: 'rgba(46,134,171,0.7)',
            borderColor: '#2E86AB',
            borderWidth: 1,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } },
        plugins: { legend: { display: false } }
    }
});
</script>
@endif
@endpush
@endsection
