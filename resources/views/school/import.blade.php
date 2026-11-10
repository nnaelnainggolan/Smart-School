<section class="dashboard-panel p-5 space-y-4">
<p><a class="text-secondary underline" href="{{ route('school.import.template') }}">Unduh template CSV</a>. Simpan NIS dan nomor telepon sebagai teks. Gunakan email, nama, dan nomor orang tua yang sama untuk saudara kandung.</p>
@if(isset($batch))
<p>{{ count($rows) }} siswa siap dibuat. Konfirmasi berlaku 30 menit. Setelah berhasil, berkas akun dan kata sandi sementara akan diunduh sekali; bagikan hanya kepada pemilik akun.</p>
<div class="overflow-x-auto"><table class="w-full text-sm"><thead><tr><th>NIS</th><th>Siswa</th><th>Kelas</th><th>Email Orang Tua</th></tr></thead><tbody>@foreach($rows as $row)<tr class="border-b"><td class="p-2">{{ $row['nis'] }}</td><td>{{ $row['nama_siswa'] }}</td><td>{{ $row['kelas'] }}</td><td>{{ $row['email_ortu'] }}</td></tr>@endforeach</tbody></table></div>
<form method="POST" action="{{ route('school.import.commit',$batch) }}">@csrf<button class="bg-primary text-white rounded-lg p-3">Konfirmasi impor & unduh akun</button></form>
@else
<form method="POST" action="{{ route('school.import.preview') }}" enctype="multipart/form-data" class="space-y-3">@csrf<label class="block">File CSV UTF-8<input class="block w-full mt-2" type="file" name="file" accept=".csv,text/csv" required></label><button class="bg-primary text-white p-3 rounded-lg">Periksa & pratinjau</button></form>
@endif
</section>
