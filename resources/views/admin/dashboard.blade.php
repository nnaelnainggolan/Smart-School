@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan data sistem Smart School')
@section('sidebar') @include('admin.partials.sidebar') @endsection

@section('content')
<div class="space-y-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Siswa Aktif</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_siswa'] }}</p>
                    <a href="{{ route('admin.siswa.index') }}" class="text-xs text-secondary hover:underline mt-2 inline-block">Lihat data →</a>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-user-graduate text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Guru</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_guru'] }}</p>
                    <a href="{{ route('admin.guru.index') }}" class="text-xs text-secondary hover:underline mt-2 inline-block">Lihat data →</a>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-chalkboard-user text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total Kelas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['total_kelas'] }}</p>
                    <a href="{{ route('admin.kelas.index') }}" class="text-xs text-secondary hover:underline mt-2 inline-block">Lihat data →</a>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-building-columns text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Konseling Pending</p>
                    <p class="text-3xl font-bold {{ $stats['konseling_pending'] > 0 ? 'text-orange-500' : 'text-gray-800' }}">{{ $stats['konseling_pending'] }}</p>
                    <span class="text-xs text-gray-400 mt-2 inline-block">Menunggu ditangani</span>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                    <i class="fa-solid fa-comments text-orange-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Berita Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        <!-- Absensi Chart -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-800">Rekap Absensi Hari Ini</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-check text-blue-500 text-sm"></i>
                </div>
            </div>
            @if($absensi_hari_ini->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-gray-300">
                    <i class="fa-regular fa-clipboard text-4xl mb-3"></i>
                    <p class="text-sm text-gray-400">Belum ada absensi hari ini</p>
                </div>
            @else
                <div class="flex items-center gap-6">
                    <canvas id="absensiChart" width="160" height="160" style="max-width:160px;max-height:160px"></canvas>
                    <div class="space-y-2 flex-1">
                        @foreach(['Hadir' => ['#10B981','bg-green-100 text-green-700'], 'Izin' => ['#F59E0B','bg-yellow-100 text-yellow-700'], 'Sakit' => ['#2E86AB','bg-blue-100 text-blue-700'], 'Alpha' => ['#EF4444','bg-red-100 text-red-700']] as $st => [$color, $cls])
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full" style="background:{{ $color }}"></div>
                                <span class="text-sm text-gray-600">{{ $st }}</span>
                            </div>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $cls }}">{{ $absensi_hari_ini->get($st, 0) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Berita Terbaru -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-800">Berita & Pengumuman</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Publikasi terbaru</p>
                </div>
                <a href="{{ route('admin.berita.index') }}" class="text-xs text-secondary font-semibold hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($berita_terbaru as $b)
                <div class="flex gap-3 p-3 rounded-xl hover:bg-gray-50 transition border border-gray-50">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $b->kategori === 'pengumuman' ? 'bg-orange-100' : ($b->kategori === 'prestasi' ? 'bg-green-100' : 'bg-blue-100') }}">
                        <i class="text-xs {{ $b->kategori === 'pengumuman' ? 'fa-solid fa-bullhorn text-orange-500' : ($b->kategori === 'prestasi' ? 'fa-solid fa-trophy text-green-500' : 'fa-solid fa-newspaper text-blue-500') }}"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-800 leading-snug line-clamp-1">{{ $b->judul }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $b->published_at?->format('d M Y') }} · <span class="capitalize">{{ $b->kategori }}</span></p>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center text-gray-400">
                    <i class="fa-regular fa-newspaper text-3xl mb-2 block"></i>
                    <p class="text-sm">Belum ada berita dipublikasikan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Akses Cepat</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            <a href="{{ route('admin.siswa.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-blue-50 transition group text-center">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-200 transition">
                    <i class="fa-solid fa-user-plus text-blue-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-600 group-hover:text-secondary">Tambah Siswa</span>
            </a>
            <a href="{{ route('admin.guru.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-green-50 transition group text-center">
                <div class="w-11 h-11 bg-green-100 rounded-xl flex items-center justify-center group-hover:bg-green-200 transition">
                    <i class="fa-solid fa-person-chalkboard text-green-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-600 group-hover:text-secondary">Tambah Guru</span>
            </a>
            <a href="{{ route('admin.jadwal.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-purple-50 transition group text-center">
                <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-200 transition">
                    <i class="fa-solid fa-calendar-plus text-purple-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-600 group-hover:text-secondary">Buat Jadwal</span>
            </a>
            <a href="{{ route('admin.berita.create') }}"
               class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-dashed border-gray-200 hover:border-secondary hover:bg-orange-50 transition group text-center">
                <div class="w-11 h-11 bg-orange-100 rounded-xl flex items-center justify-center group-hover:bg-orange-200 transition">
                    <i class="fa-solid fa-pen-to-square text-orange-600"></i>
                </div>
                <span class="text-xs font-semibold text-gray-600 group-hover:text-secondary">Buat Berita</span>
            </a>
        </div>
    </div>

</div>

@push('scripts')
@if(!$absensi_hari_ini->isEmpty())
<script>
new Chart(document.getElementById('absensiChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
        datasets: [{
            data: [
                {{ $absensi_hari_ini->get('Hadir', 0) }},
                {{ $absensi_hari_ini->get('Izin', 0) }},
                {{ $absensi_hari_ini->get('Sakit', 0) }},
                {{ $absensi_hari_ini->get('Alpha', 0) }}
            ],
            backgroundColor: ['#10B981','#F59E0B','#2E86AB','#EF4444'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: false,
        plugins: { legend: { display: false } },
        cutout: '68%'
    }
});
</script>
@endif
@endpush
@endsection
