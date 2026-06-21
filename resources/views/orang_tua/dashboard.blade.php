@extends('layouts.app')
@section('title', 'Dashboard Orang Tua')
@section('subtitle', 'Pantau perkembangan anak Anda')
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
<div class="space-y-6">
    @if(!isset($siswa) || !$siswa)
    <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 flex items-center gap-4">
        <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-2xl"></i>
        <p class="text-yellow-700">Data siswa belum tersedia. Hubungi admin sekolah.</p>
    </div>
    @else

    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-primary to-secondary rounded-2xl p-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/5 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="relative flex items-start justify-between">
            <div>
                <p class="text-blue-200 text-sm">Monitoring perkembangan</p>
                <h2 class="text-2xl font-bold mt-0.5">{{ $siswa->user->name }}</h2>
                <p class="text-blue-200 text-sm mt-1">
                    <i class="fa-solid fa-building-columns mr-1"></i>{{ $siswa->kelas?->nama_kelas ?? '-' }}
                    <span class="mx-2">·</span>
                    <i class="fa-solid fa-id-card mr-1"></i>NIS: {{ $siswa->nis }}
                </p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-user-graduate text-white text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="stat-card text-center">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-check text-green-500"></i>
            </div>
            <p class="text-2xl font-bold text-green-600">{{ $rekapAbsensi['Hadir'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Hadir Bulan Ini</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 {{ ($rekapAbsensi['Alpha'] ?? 0) > 3 ? 'bg-red-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-xmark {{ ($rekapAbsensi['Alpha'] ?? 0) > 3 ? 'text-red-500' : 'text-gray-400' }}"></i>
            </div>
            <p class="text-2xl font-bold {{ ($rekapAbsensi['Alpha'] ?? 0) > 3 ? 'text-red-500' : 'text-gray-800' }}">{{ $rekapAbsensi['Alpha'] ?? 0 }}</p>
            <p class="text-xs text-gray-500 mt-1">Alpha Bulan Ini</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-comments text-purple-500"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $konselingAktif }}</p>
            <p class="text-xs text-gray-500 mt-1">Konseling Aktif</p>
        </div>
        <div class="stat-card text-center">
            <div class="w-10 h-10 {{ $pesanBaru > 0 ? 'bg-secondary/20' : 'bg-gray-100' }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid fa-envelope {{ $pesanBaru > 0 ? 'text-secondary' : 'text-gray-400' }}"></i>
            </div>
            <p class="text-2xl font-bold {{ $pesanBaru > 0 ? 'text-secondary' : 'text-gray-800' }}">{{ $pesanBaru }}</p>
            <p class="text-xs text-gray-500 mt-1">Pesan Belum Dibaca</p>
        </div>
    </div>

    @if($pesanBaru > 0 || ($rekapAbsensi['Alpha'] ?? 0) > 3)
    <div class="space-y-2">
        @if(($rekapAbsensi['Alpha'] ?? 0) > 3)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-sm text-red-700"><strong>Perhatian!</strong> {{ $siswa->user->name }} memiliki <strong>{{ $rekapAbsensi['Alpha'] }}</strong> ketidakhadiran tanpa keterangan bulan ini.</p>
        </div>
        @endif
        @if($pesanBaru > 0)
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
            <i class="fa-solid fa-envelope text-blue-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-sm text-blue-700">Ada <strong>{{ $pesanBaru }}</strong> pesan baru dari guru. <a href="{{ route('orang_tua.pesan') }}" class="underline font-semibold">Baca sekarang →</a></p>
        </div>
        @endif
    </div>
    @endif

    <!-- Grafik Nilai -->
    @if($nilaiTerbaru->count())
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800">Grafik Nilai Anak</h3>
            <a href="{{ route('orang_tua.nilai') }}" class="text-xs text-secondary font-semibold hover:underline">Detail Rapor →</a>
        </div>
        <canvas id="nilaiChart" height="100"></canvas>
    </div>
    @endif

    <!-- Quick Menu -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @foreach([
            ['route' => 'orang_tua.nilai', 'icon' => 'fa-chart-bar', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600', 'label' => 'Lihat Nilai'],
            ['route' => 'orang_tua.absensi', 'icon' => 'fa-clipboard-list', 'bg' => 'bg-green-100', 'text' => 'text-green-600', 'label' => 'Absensi'],
            ['route' => 'orang_tua.konseling', 'icon' => 'fa-heart-pulse', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600', 'label' => 'Konseling'],
            ['route' => 'orang_tua.pesan', 'icon' => 'fa-envelope', 'bg' => 'bg-orange-100', 'text' => 'text-orange-600', 'label' => 'Pesan Guru'],
        ] as $menu)
        <a href="{{ route($menu['route']) }}" class="stat-card flex flex-col items-center gap-2 text-center hover:border-secondary group cursor-pointer">
            <div class="w-12 h-12 {{ $menu['bg'] }} rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                <i class="fa-solid {{ $menu['icon'] }} {{ $menu['text'] }} text-lg"></i>
            </div>
            <span class="text-sm font-semibold text-gray-700">{{ $menu['label'] }}</span>
        </a>
        @endforeach
    </div>
    @endif
</div>

@push('scripts')
@if(isset($nilaiTerbaru) && $nilaiTerbaru->count())
<script>
new Chart(document.getElementById('nilaiChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($nilaiTerbaru->pluck('mataPelajaran.nama_mapel')) !!},
        datasets: [{
            label: 'Nilai Akhir',
            data: {!! json_encode($nilaiTerbaru->pluck('nilai_akhir')) !!},
            backgroundColor: {!! json_encode($nilaiTerbaru->map(fn($n) => $n->nilai_akhir >= $n->mataPelajaran->kkm ? 'rgba(16,185,129,0.75)' : 'rgba(239,68,68,0.75)')->toArray()) !!},
            borderRadius: 8, borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { font: { size: 11 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        },
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' Nilai: ' + ctx.raw } } }
    }
});
</script>
@endif
@endpush
@endsection
