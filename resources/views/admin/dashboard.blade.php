@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Pusat pengelolaan Smart School')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
<div class="dashboard-page">
    <section class="dashboard-welcome dashboard-greeting" aria-label="Selamat datang">
        <div class="dashboard-greeting-copy">
            <p class="dashboard-eyebrow">Ruang kerja administrator</p>
            <h2 class="font-bold">Selamat datang, {{ auth()->user()->name }}.</h2>
            <p class="text-sm text-blue-200 mt-2">Kelola kegiatan sekolah dan pantau informasi penting hari ini.</p>
        </div>
        <div class="dashboard-welcome-icon" aria-hidden="true"><i class="fa-solid fa-school"></i></div>
    </section>

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4" aria-label="Ringkasan sekolah">
        @foreach([
            ['label' => 'Siswa aktif', 'value' => $stats['total_siswa'], 'icon' => 'fa-user-graduate', 'route' => 'admin.siswa.index'],
            ['label' => 'Tenaga pendidik', 'value' => $stats['total_guru'], 'icon' => 'fa-chalkboard-user', 'route' => 'admin.guru.index'],
            ['label' => 'Kelas tersedia', 'value' => $stats['total_kelas'], 'icon' => 'fa-building-columns', 'route' => 'admin.kelas.index'],
            ['label' => 'Konseling pending', 'value' => $stats['konseling_pending'], 'icon' => 'fa-comments', 'route' => null],
        ] as $stat)
        <div class="stat-card">
            <div class="flex items-center justify-between gap-3">
                <div><p class="dashboard-stat-label">{{ $stat['label'] }}</p><p class="dashboard-stat-number">{{ number_format($stat['value'], 0, ',', '.') }}</p></div>
                <div class="dashboard-stat-icon {{ $stat['route'] ? '' : 'gold' }}" aria-hidden="true"><i class="fa-solid {{ $stat['icon'] }}"></i></div>
            </div>
            <p class="dashboard-stat-foot">
                @if($stat['route'])
                <a href="{{ route($stat['route']) }}">Lihat data <span aria-hidden="true">↗</span></a>
                @else
                Menunggu penanganan guru BK
                @endif
            </p>
        </div>
        @endforeach
    </section>

    <div class="admin-overview">
        <section class="dashboard-panel" aria-labelledby="attendance-title">
            <div class="dashboard-panel-header">
                <div><h3 id="attendance-title" class="font-bold">Kehadiran hari ini</h3><p>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p></div>
                <span class="dashboard-stat-icon" aria-hidden="true"><i class="fa-solid fa-clipboard-check"></i></span>
            </div>
            @if($absensi_hari_ini->isEmpty())
            <div class="dashboard-empty"><i class="fa-regular fa-clipboard" aria-hidden="true"></i>Belum ada absensi hari ini.</div>
            @else
            <div class="dashboard-chart-layout">
                <div class="dashboard-chart-donut"><canvas id="absensiChart" role="img" aria-label="Rekap absensi hari ini; rincian jumlah tersedia di samping grafik."></canvas></div>
                <dl class="space-y-3 flex-1 min-w-0">
                    @foreach(['Hadir' => '#527348', 'Izin' => '#C49B53', 'Sakit' => '#648B8B', 'Alpha' => '#C66B5D'] as $status => $color)
                    <div class="flex items-center justify-between gap-3">
                        <dt class="flex items-center gap-2 text-sm text-gray-600"><span class="w-2.5 h-2.5 rounded-full" style="background:{{ $color }}" aria-hidden="true"></span>{{ $status }}</dt>
                        <dd class="font-semibold text-sm text-gray-800">{{ $absensi_hari_ini->get($status, 0) }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
            @endif
        </section>

        <section class="dashboard-panel" aria-labelledby="news-title">
            <div class="dashboard-panel-header">
                <div><h3 id="news-title" class="font-bold">Kabar sekolah</h3><p>Berita dan pengumuman terbaru</p></div>
                <a href="{{ route('admin.berita.index') }}">Lihat semua <span aria-hidden="true">↗</span></a>
            </div>
            @forelse($berita_terbaru as $b)
            <a href="{{ route('admin.berita.edit', $b) }}" class="dashboard-news-row">
                <div class="news-symbol" aria-hidden="true"><i class="fa-solid {{ $b->kategori === 'pengumuman' ? 'fa-bullhorn' : ($b->kategori === 'prestasi' ? 'fa-trophy' : 'fa-newspaper') }}"></i></div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-gray-800 line-clamp-1">{{ $b->judul }}</p>
                    <p class="text-xs text-gray-400 mt-1"><span class="capitalize">{{ $b->kategori }}</span> · {{ $b->published_at?->format('d M Y') }}</p>
                </div>
                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-gray-400" aria-hidden="true"></i>
            </a>
            @empty
            <div class="dashboard-empty"><i class="fa-regular fa-newspaper" aria-hidden="true"></i>Belum ada berita dipublikasikan.</div>
            @endforelse
        </section>
    </div>

    <section class="dashboard-panel" aria-labelledby="quick-actions-title">
        <div class="dashboard-panel-header"><div><h3 id="quick-actions-title" class="font-bold">Mulai dari sini</h3><p>Akses cepat untuk pekerjaan rutin Anda</p></div></div>
        <div class="dashboard-quick-links">
            @foreach([
                ['route' => 'admin.siswa.create', 'icon' => 'fa-user-plus', 'label' => 'Tambah siswa'],
                ['route' => 'admin.guru.create', 'icon' => 'fa-chalkboard-user', 'label' => 'Tambah guru'],
                ['route' => 'admin.jadwal.create', 'icon' => 'fa-calendar-plus', 'label' => 'Buat jadwal'],
                ['route' => 'admin.berita.create', 'icon' => 'fa-pen-to-square', 'label' => 'Tulis berita'],
            ] as $action)
            <a href="{{ route($action['route']) }}"><i class="fa-solid {{ $action['icon'] }}" aria-hidden="true"></i>{{ $action['label'] }}</a>
            @endforeach
        </div>
    </section>
</div>
@endsection

@push('scripts')
@if(!$absensi_hari_ini->isEmpty())
<script>
new Chart(document.getElementById('absensiChart'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{
            data: {{ Illuminate\Support\Js::from(collect(['Hadir', 'Izin', 'Sakit', 'Alpha'])->map(fn($status) => (int) $absensi_hari_ini->get($status, 0))) }},
            backgroundColor: ['#527348', '#C49B53', '#648B8B', '#C66B5D'],
            borderWidth: 3, borderColor: '#ffffff', hoverOffset: 3
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } }, cutout: '73%'
    }
});
</script>
@endif
@endpush
