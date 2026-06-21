@extends('layouts.app')
@section('title', 'Edit Orang Tua')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-3xl space-y-5">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Edit Data Orang Tua</h2>
            <p class="text-sm text-gray-500 mt-0.5">{{ $orangtua->nama_ayah }} — {{ $orangtua->user->email }}</p>
        </div>
        <form method="POST" action="{{ route('admin.orangtua.update', $orangtua->id) }}" class="p-6 space-y-6">
            @csrf @method('PUT')
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <ul class="text-sm text-red-700 space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <div>
                <h3 class="text-base font-bold text-gray-800 mb-4">Data Orang Tua</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ayah <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $orangtua->nama_ayah) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Ibu</label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $orangtua->nama_ibu) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ayah</label>
                        <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $orangtua->pekerjaan_ayah) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pekerjaan Ibu</label>
                        <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $orangtua->pekerjaan_ibu) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP (Ayah) <span class="text-red-500">*</span></label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $orangtua->no_hp) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP (Ibu)</label>
                        <input type="text" name="no_hp_ibu" value="{{ old('no_hp_ibu', $orangtua->no_hp_ibu) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat</label>
                        <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('alamat', $orangtua->alamat) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                <h3 class="text-base font-bold text-gray-800 mb-4">Akun Login</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $orangtua->user->email) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    </div>
                    <div class="flex items-end pb-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ $orangtua->user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-secondary">
                            <span class="text-sm font-semibold text-gray-700">Akun Aktif</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-xl p-5 border border-orange-100">
                <h3 class="text-base font-bold text-gray-800 mb-4">Siswa Terhubung</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto">
                    @foreach($siswaTersedia as $s)
                    <label class="flex items-center gap-2.5 p-3 bg-white border border-gray-200 rounded-xl cursor-pointer hover:border-secondary transition has-[:checked]:border-secondary has-[:checked]:bg-blue-50">
                        <input type="checkbox" name="siswa_ids[]" value="{{ $s->id }}"
                            {{ in_array($s->id, old('siswa_ids', $orangtua->siswa->pluck('id')->toArray())) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-secondary">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $s->user->name }}</p>
                            <p class="text-xs text-gray-400">NIS: {{ $s->nis }} · {{ $s->kelas?->nama_kelas ?? '-' }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan Perubahan</button>
                <a href="{{ route('admin.orangtua.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>

    <!-- Reset Password -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-key text-gray-400"></i> Reset Password</h3>
        <form method="POST" action="{{ route('admin.orangtua.reset_password', $orangtua->id) }}" class="flex gap-3">
            @csrf @method('PUT')
            <input type="password" name="password" required minlength="6" placeholder="Password baru..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            <button type="submit" class="px-5 py-2.5 bg-accent text-white rounded-xl font-semibold hover:bg-orange-400 transition text-sm">Reset Password</button>
        </form>
    </div>
</div>
@endsection
