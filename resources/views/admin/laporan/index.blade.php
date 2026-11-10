@extends('layouts.app')
@section('title', 'Laporan Sistem')
@section('subtitle', 'Ringkasan & analisis data sekolah')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-6">
    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <form class="flex flex-wrap gap-3 items-center">
            <span class="text-sm font-semibold text-gray-600">Periode:</span>
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

    <!-- Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        @foreach([
            ['icon'=>'fa-user-graduate','color'=>'bg-blue-100 text-blue-600','label'=>'Total Siswa','value'=>$stats['total_siswa']],
            ['icon'=>'fa-chalkboard-user','color'=>'bg-green-100 text-green-600','label'=>'Total Guru','value'=>$stats['total_guru']],
            ['icon'=>'fa-user-doctor','color'=>'bg-purple-100 text-purple-600','label'=>'Guru BK','value'=>$stats['total_guru_bk']],
            ['icon'=>'fa-building-columns','color'=>'bg-orange-100 text-orange-600','label'=>'Total Kelas','value'=>$stats['total_kelas']],
            ['icon'=>'fa-people-roof','color'=>'bg-pink-100 text-pink-600','label'=>'Orang Tua','value'=>$stats['total_orang_tua']],
        ] as $s)
        <div class="stat-card text-center">
            <div class="w-11 h-11 {{ $s['color'] }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i class="fa-solid {{ $s['icon'] }}"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800">{{ $s['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Rata-rata Nilai per Kelas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Rata-rata Nilai per Kelas</h3>
            @if($rataNilaiPerKelas->where('rata_rata', '>', 0)->count())
            <canvas id="nilaiKelasChart" height="200"></canvas>
            @else
            <p class="text-sm text-gray-400 text-center py-10">Belum ada data nilai</p>
            @endif
        </div>

        <!-- Absensi Bulanan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Rekap Absensi Bulan Ini</h3>
            @if($rekapAbsensiBulanan->count())
            <canvas id="absensiBulanChart" height="200"></canvas>
            @else
            <p class="text-sm text-gray-400 text-center py-10">Belum ada data absensi</p>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <!-- Konseling per Status -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">Status Konseling</h3>
            <div class="space-y-2.5">
                @foreach(['pending'=>'Menunggu','disetujui'=>'Disetujui','berlangsung'=>'Berlangsung','selesai'=>'Selesai','ditolak'=>'Ditolak'] as $key => $label)
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">{{ $label }}</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-bold">{{ $konselingPerStatus->get($key, 0) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Siswa Perlu Perhatian -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4">⚠️ Siswa Perlu Perhatian (Alpha Terbanyak)</h3>
            <div class="space-y-2">
                @forelse($siswaAlphaTerbanyak as $s)
                <div class="flex items-center justify-between p-2.5 bg-red-50 rounded-xl">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 bg-red-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">{{ substr($s->user->name,0,1) }}</div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">{{ $s->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $s->kelas?->nama_kelas }}</p>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $s->absensi_count }}x Alpha</span>
                </div>
                @empty
                <p class="text-sm text-gray-400 text-center py-6">Tidak ada siswa dengan alpha bulan ini 🎉</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Cetak Rapor -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4">Cetak Rapor per Kelas</h3>
        <form method="GET" action="{{ route('admin.laporan.cetak_rapor') }}" target="_blank" class="flex flex-wrap gap-3">
            <select name="kelas_id" required class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">-- Pilih Kelas --</option>
                @foreach(\App\Models\Kelas::all() as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                @endforeach
            </select>
            <input type="hidden" name="tahun_ajaran" value="{{ $tahunAjaran }}">
            <input type="hidden" name="semester" value="{{ $semester }}">
            <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl text-sm font-semibold hover:bg-secondary transition">
                <i class="fa-solid fa-print mr-1.5"></i>Cetak Rapor
            </button>
        </form>
    </div>
</div>

@push('scripts')
@if($rataNilaiPerKelas->where('rata_rata', '>', 0)->count())
<script>
new Chart(document.getElementById('nilaiKelasChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($rataNilaiPerKelas->pluck('nama_kelas')) !!},
        datasets: [{ label: 'Rata-rata Nilai', data: {!! json_encode($rataNilaiPerKelas->pluck('rata_rata')) !!}, backgroundColor: 'rgba(46,134,171,0.75)', borderRadius: 8, borderWidth: 0 }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } }, plugins: { legend: { display: false } } }
});
</script>
@endif
@if($rekapAbsensiBulanan->count())
<script>
new Chart(document.getElementById('absensiBulanChart').getContext('2d'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir','Izin','Sakit','Alpha'],
        datasets: [{ data: [{{ $rekapAbsensiBulanan->get('Hadir',0) }},{{ $rekapAbsensiBulanan->get('Izin',0) }},{{ $rekapAbsensiBulanan->get('Sakit',0) }},{{ $rekapAbsensiBulanan->get('Alpha',0) }}], backgroundColor: ['#10B981','#F59E0B','#2E86AB','#EF4444'], borderWidth: 0 }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '65%' }
});
</script>
@endif
@endpush
@endsection
