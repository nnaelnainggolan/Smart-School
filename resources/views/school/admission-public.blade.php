<!doctype html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>PPDB Smart School</title><link rel="stylesheet" href="{{ asset('css/public-school.css') }}"><link rel="manifest" href="/manifest.webmanifest"><meta name="theme-color" content="#173f35"></head><body><main><nav><a href="{{ route('landing') }}">← Beranda</a> · <a href="{{ route('ppdb.form') }}">Daftar</a> · <a href="{{ route('ppdb.status') }}">Cek status</a></nav><h1>{{ isset($tracking)?'Status Pendaftaran':'Pendaftaran Peserta Didik' }}</h1>
@if($errors->any())<div role="alert">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
@if(session('reference'))<section role="status"><h2>Pendaftaran tersimpan</h2><p>Simpan nomor pendaftaran berikut untuk mengecek status bersama email Anda:</p><strong style="overflow-wrap:anywhere">{{ session('reference') }}</strong></section>@endif
@if(isset($tracking))
<form method="POST" action="{{ route('ppdb.check') }}">@csrf<label>Nomor pendaftaran<input required name="reference" value="{{ old('reference') }}"></label><label>Email orang tua<input type="email" name="email" required value="{{ old('email') }}"></label><button>Cek status</button></form>@isset($result)<p role="status">Status: {{ $result }}</p>@endisset
@else
<p>Isi identitas calon siswa dan kontak orang tua. Berkas hanya dapat diakses petugas PPDB.</p>
<form method="POST" action="{{ route('ppdb.store') }}" enctype="multipart/form-data">@csrf
@foreach(['name'=>'Nama calon siswa','parent_name'=>'Nama orang tua / wali','email'=>'Email orang tua','phone'=>'Nomor telepon','previous_school'=>'Sekolah asal'] as $key=>$label)<label>{{ $label }}<input name="{{ $key }}" type="{{ $key==='email'?'email':($key==='phone'?'tel':'text') }}" required maxlength="{{ $key==='phone'?20:255 }}" value="{{ old($key) }}"></label>@endforeach
<label>Berkas pendukung (opsional, PDF/JPG/PNG maksimal 5 MB)<input type="file" name="document" accept=".pdf,.jpg,.jpeg,.png"></label>
<label><input style="width:auto" type="checkbox" required name="consent" value="1"> Saya menyetujui data ini digunakan sekolah untuk proses pendaftaran dan menghubungi saya.</label><button>Kirim pendaftaran</button></form>
@endif
</main><script src="/js/pwa.js" defer></script></body></html>
