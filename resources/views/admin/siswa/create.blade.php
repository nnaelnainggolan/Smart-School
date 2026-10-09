@extends('layouts.app')
@section('title', 'Tambah Siswa')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-4xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Form Pendaftaran Siswa Baru</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pilih akun orang tua yang sudah ada, atau buat akun baru bersama siswa.</p>
        </div>
        <form method="POST" action="{{ route('admin.siswa.store') }}" class="p-6 space-y-8">
            @csrf
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <ul class="text-sm text-red-700 space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <!-- Data Siswa -->
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary text-white rounded-lg flex items-center justify-center text-xs font-bold">1</span>
                    Data Siswa
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap Siswa <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIS <span class="text-red-500">*</span></label>
                        <input type="text" name="nis" value="{{ old('nis') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">NISN</label>
                        <input type="text" name="nisn" value="{{ old('nisn') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                        <select name="kelas_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Masuk <span class="text-red-500">*</span></label>
                        <input type="text" name="tahun_masuk" value="{{ old('tahun_masuk', date('Y')) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Siswa</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Akun Siswa -->
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-secondary text-white rounded-lg flex items-center justify-center text-xs font-bold">2</span>
                    Akun Login Siswa
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Siswa <span class="text-red-500">*</span></label>
                        <input type="email" name="email_siswa" value="{{ old('email_siswa') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Siswa <span class="text-red-500">*</span></label>
                        <input type="password" name="password_siswa" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                </div>
            </div>

            <div x-data="{ existing: '{{ old('orang_tua_id') }}' }">
            <label class="block">Hubungkan akun Orang Tua<select name="orang_tua_id" x-model="existing" class="w-full border rounded p-3"><option value="">Buat akun baru</option>@foreach($parents as $parent)<option value="{{ $parent->id }}">{{ $parent->nama_ayah }} — {{ $parent->user->email }}</option>@endforeach</select></label>
            <fieldset :disabled="!!existing" x-show="!existing" class="mt-4">
            <!-- Data Orang Tua -->
            <div class="bg-orange-50 rounded-xl p-5 border border-orange-100">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-accent text-white rounded-lg flex items-center justify-center text-xs font-bold">3</span>
                    Data & Akun Orang Tua
                    <span class="text-xs font-normal text-orange-600 bg-orange-100 px-2 py-0.5 rounded-full">Dibuat otomatis</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ayah <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ibu</label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP Orang Tua <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp_ortu" value="{{ old('no_hp_ortu') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP Ibu</label>
                        <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email Orang Tua <span class="text-red-500">*</span></label>
                        <input type="email" name="email_ortu" value="{{ old('email_ortu') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Orang Tua <span class="text-red-500">*</span></label>
                        <input type="password" name="password_ortu" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Orang Tua</label>
                        <textarea name="alamat_ortu" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat_ortu') }}</textarea>
                    </div>
                </div>
            </div>

            </fieldset></div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">
                    Simpan Data Siswa & Orang Tua
                </button>
                <a href="{{ route('admin.siswa.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
