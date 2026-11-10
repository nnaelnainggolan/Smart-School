@extends('layouts.app')
@section('title', 'Form Absensi')
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
    <!-- Info Jadwal -->
    <div class="bg-primary rounded-2xl p-5 text-white">
        <div class="flex flex-wrap gap-4 items-center justify-between">
            <div>
                <h2 class="text-lg font-bold">{{ $jadwal->mataPelajaran->nama_mapel }}</h2>
                <p class="text-blue-200 text-sm">Kelas {{ $jadwal->kelas->nama_kelas }} · {{ $jadwal->hari }} {{ substr($jadwal->jam_mulai,0,5) }}–{{ substr($jadwal->jam_selesai,0,5) }}</p>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-sm text-blue-200">Tanggal:</label>
                <input type="date" id="tanggalPicker" value="{{ $tanggal }}" class="px-3 py-1.5 bg-white bg-opacity-10 border border-white border-opacity-20 rounded-lg text-white text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('guru.absensi.store') }}" id="absensiForm">
        @csrf
        <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
        <input type="hidden" name="tanggal" id="tanggalInput" value="{{ $tanggal }}">

        <!-- Tombol aksi cepat -->
        <div class="flex flex-wrap gap-2 mb-4">
            <button type="button" onclick="setAllStatus('Hadir')" class="px-4 py-2 bg-green-100 text-green-700 rounded-xl text-sm font-medium hover:bg-green-200 transition">✓ Semua Hadir</button>
            <button type="button" onclick="setAllStatus('Alpha')" class="px-4 py-2 bg-red-100 text-red-700 rounded-xl text-sm font-medium hover:bg-red-200 transition">✗ Semua Alpha</button>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm attendance-table">
                    <thead><tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase w-8">#</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Hadir</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Izin</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Sakit</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Alpha</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Keterangan</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                    @forelse($siswa as $i => $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3.5 text-gray-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-secondary flex items-center justify-center flex-shrink-0"><span class="text-white text-xs font-bold">{{ substr($s->user->name,0,1) }}</span></div>
                                <p class="font-medium text-gray-800">{{ $s->user->name }} @if(isset($approved[$s->id]))<small class="block text-secondary">Izin disetujui: {{ $approved[$s->id]->type }}</small>@endif</p>
                            </div>
                        </td>
                        @foreach(['Hadir','Izin','Sakit','Alpha'] as $st)
                        <td class="px-5 py-3.5 text-center">
                            <label class="inline-flex items-center gap-2"><span class="sm:hidden">{{ $st }}</span><input aria-label="{{ $st }} untuk {{ $s->user->name }}" type="radio" name="absensi[{{ $s->id }}][status]" value="{{ $st }}" class="w-4 h-4 text-secondary"
                                @checked(old('absensi.'.$s->id.'.status', $existing[$s->id]->status ?? $approved[$s->id]->type ?? 'Hadir') === $st)></label>
                        </td>
                        @endforeach
                        <td class="px-5 py-3.5">
                            <input type="text" name="absensi[{{ $s->id }}][keterangan]" value="{{ old('absensi.'.$s->id.'.keterangan', $existing[$s->id]->keterangan ?? '') }}" placeholder="Opsional..." class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-secondary">
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">Tidak ada siswa di kelas ini</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan Absensi</button>
                <a href="{{ route('guru.absensi.pilih') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.getElementById('tanggalPicker').addEventListener('change', function() {
    if (!this.value) return;
    if (window.absensiDirty && !confirm('Perubahan belum disimpan. Ganti tanggal?')) { this.value = document.getElementById('tanggalInput').value; return; }
    const url = new URL(window.location.href);
    url.searchParams.set('tanggal', this.value);
    window.location.assign(url);
});
document.getElementById('absensiForm').addEventListener('change', () => window.absensiDirty = true);
function setAllStatus(status) {
    window.absensiDirty = true;
    document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(r => r.checked = true);
}
</script>
@endpush
@endsection
