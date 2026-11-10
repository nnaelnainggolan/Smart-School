<p data-connection-warning hidden role="status">Koneksi terputus. Tunggu jaringan kembali sebelum menyimpan.</p><button data-install-school hidden class="bg-primary text-white p-3 rounded-lg">Pasang Smart School di perangkat</button>
<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-4">
@php
$links=[];$role=auth()->user()->role;
if($role==='admin')$links=[['school.import','Impor siswa & orang tua'],['school.academic','Periode & kenaikan kelas'],['school.admissions','Pendaftaran PPDB']];
if(in_array($role,['admin','guru','guru_bk']))$links[]=['school.homeroom','Wali kelas & tindak lanjut'];
if(in_array($role,['admin','guru','orang_tua']))$links[]=['school.leave','Pengajuan izin'];
if(in_array($role,['admin','guru','orang_tua','siswa']))$links[]=['school.reports','Rapor terbit'];
@endphp
@foreach($links as [$route,$label])<a class="dashboard-panel p-5 font-semibold hover:underline" href="{{ route($route) }}">{{ $label }} →</a>@endforeach
</div>
<section class="dashboard-panel p-5"><h3 class="font-bold mb-3">Agenda sekolah berikutnya</h3>@forelse($agenda as $a)<div class="py-3 border-b"><strong>{{ $a->judul }}</strong><p>{{ $a->tanggal_mulai->format('d M Y') }} · {{ $a->deskripsi }}</p></div>@empty<p>Belum ada agenda.</p>@endforelse</section>
