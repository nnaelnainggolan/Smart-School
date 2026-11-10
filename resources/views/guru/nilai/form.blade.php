@extends('layouts.app')
@section('title', 'Form Input Nilai')
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
    <div class="bg-primary text-white rounded-2xl p-5">
        <h2 class="text-lg font-bold">{{ $mapel->nama_mapel }}</h2>
        <p class="text-blue-200 text-sm">Kelas {{ $kelas->nama_kelas }} · {{ $request->tahun_ajaran }} Semester {{ $request->semester }} · KKM: {{ $mapel->kkm }}</p>
    </div>
    <form method="POST" action="{{ route('guru.nilai.store') }}">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $mapel->id }}">
        <input type="hidden" name="tahun_ajaran" value="{{ $request->tahun_ajaran }}">
        <input type="hidden" name="semester" value="{{ $request->semester }}">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm grade-table">
                    <thead><tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase w-8">#</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Nama Siswa</th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Harian<br><span class="font-normal normal-case text-gray-400">40%</span></th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Nilai UTS<br><span class="font-normal normal-case text-gray-400">30%</span></th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Nilai UAS<br><span class="font-normal normal-case text-gray-400">30%</span></th>
                        <th class="px-4 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Nilai Akhir</th>
                        <th class="px-4 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Catatan</th>
                    </tr></thead>
                    <tbody class="divide-y divide-gray-50">
                    @forelse($siswa as $i => $s)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400 text-xs">{{ $i+1 }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-secondary flex items-center justify-center flex-shrink-0"><span class="text-white text-xs font-bold">{{ substr($s->user->name,0,1) }}</span></div>
                                <p class="font-medium text-gray-800">{{ $s->user->name }}</p>
                            </div>
                        </td>
                        @php $n = $nilai[$s->id] ?? null @endphp
                        <td class="px-4 py-3 text-center"><input type="number" aria-label="Harian untuk {{ $s->user->name }}" name="nilai[{{ $s->id }}][nilai_harian]" value="{{ old('nilai.'.$s->id.'.nilai_harian', $n?->nilai_harian) }}" min="0" max="100" step="0.01" class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-1 focus:ring-secondary nilai-input" data-siswa="{{ $s->id }}" data-type="harian"></td>
                        <td class="px-4 py-3 text-center"><input type="number" aria-label="UTS untuk {{ $s->user->name }}" name="nilai[{{ $s->id }}][nilai_uts]" value="{{ old('nilai.'.$s->id.'.nilai_uts', $n?->nilai_uts) }}" min="0" max="100" step="0.01" class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-1 focus:ring-secondary nilai-input" data-siswa="{{ $s->id }}" data-type="uts"></td>
                        <td class="px-4 py-3 text-center"><input type="number" aria-label="UAS untuk {{ $s->user->name }}" name="nilai[{{ $s->id }}][nilai_uas]" value="{{ old('nilai.'.$s->id.'.nilai_uas', $n?->nilai_uas) }}" min="0" max="100" step="0.01" class="w-20 px-2 py-1.5 border border-gray-200 rounded-lg text-sm text-center focus:outline-none focus:ring-1 focus:ring-secondary nilai-input" data-siswa="{{ $s->id }}" data-type="uas"></td>
                        <td class="px-4 py-3 text-center">
                            <span id="akhir-{{ $s->id }}" class="font-bold text-gray-800">{{ $n?->nilai_akhir !== null ? number_format($n->nilai_akhir,1) : '-' }}</span>
                        </td>
                        <td class="px-4 py-3"><input type="text" name="nilai[{{ $s->id }}][catatan]" value="{{ old('nilai.'.$s->id.'.catatan', $n?->catatan) }}" placeholder="Opsional..." class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-1 focus:ring-secondary"></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">Tidak ada siswa di kelas ini</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-5 py-4 border-t border-gray-100 flex gap-3">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan Semua Nilai</button>
                <a href="{{ route('guru.nilai.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Kembali</a>
            </div>
        </div>
    </form>
</div>
@push('scripts')
<script>
document.querySelectorAll('.nilai-input').forEach(input => {
    input.addEventListener('input', function() {
        const siswaId = this.dataset.siswa;
        const h = parseFloat(document.querySelector(`[data-siswa="${siswaId}"][data-type="harian"]`)?.value) || 0;
        const u = parseFloat(document.querySelector(`[data-siswa="${siswaId}"][data-type="uts"]`)?.value) || 0;
        const a = parseFloat(document.querySelector(`[data-siswa="${siswaId}"][data-type="uas"]`)?.value) || 0;
        if (h && u && a) {
            const akhir = (h * 0.4) + (u * 0.3) + (a * 0.3);
            document.getElementById(`akhir-${siswaId}`).textContent = akhir.toFixed(1);
        }
    });
});
</script>
@endpush
@endsection
