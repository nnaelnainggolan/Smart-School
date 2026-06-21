@extends('layouts.app')
@section('title', 'Tambah Orang Tua')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Tambah Data Orang Tua / Wali</h2>
            <p class="text-sm text-gray-500 mt-0.5">Buat akun baru untuk orang tua, lalu hubungkan dengan siswa (opsional)</p>
        </div>
        <form method="POST" action="{{ route('admin.orangtua.store') }}" class="p-6 space-y-6">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <ul class="text-sm text-red-700 space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <!-- Data Orang Tua -->
            <div>
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-primary text-white rounded-lg flex items-center justify-center text-xs font-bold">1</span>
                    Data Orang Tua
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
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP (Ayah) <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP (Ibu)</label>
                        <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Akun Login -->
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-secondary text-white rounded-lg flex items-center justify-center text-xs font-bold">2</span>
                    Akun Login Orang Tua
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                </div>
            </div>

            <!-- Hubungkan dengan Siswa -->
            <div class="bg-orange-50 rounded-xl p-5 border border-orange-100">
                <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-7 h-7 bg-accent text-white rounded-lg flex items-center justify-center text-xs font-bold">3</span>
                    Hubungkan dengan Siswa
                    <span class="text-xs font-normal text-orange-600 bg-orange-100 px-2 py-0.5 rounded-full">Opsional</span>
                </h3>
                @if($siswaTanpaOrtu->count())
                <p class="text-xs text-gray-500 mb-3">Pilih satu atau lebih siswa yang belum memiliki orang tua terhubung:</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto">
                    @foreach($siswaTanpaOrtu as $s)
                    <label class="flex items-center gap-2.5 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-secondary transition has-[:checked]:border-secondary has-[:checked]:bg-blue-50">
                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}" {{ in_array($s->id, old('siswa_ids', [])) ? 'checked' : '' }} class="rounded border-gray-300 text-secondary">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $s->user->name }}</p>
                            <p class="text-xs text-gray-400">NIS: {{ $s->nis }} · {{ $s->kelas?->nama_kelas ?? '-' }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <p class="text-sm text-gray-400 italic">Semua siswa sudah memiliki orang tua terhubung.</p>
                @endif
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan Data Orang Tua</button>
                <a href="{{ route('admin.orangtua.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
