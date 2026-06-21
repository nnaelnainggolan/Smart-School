@extends('layouts.app')
@section('title', 'Tambah Guru')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Tambah Guru / Guru BK</h2>
        </div>
        <form method="POST" action="{{ route('admin.guru.store') }}" class="p-6 space-y-4">
            @csrf
            @if($errors->any())<div class="bg-red-50 border border-red-200 rounded-xl p-4"><ul class="text-sm text-red-700 space-y-1">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul></div>@endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required minlength="6" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru Mata Pelajaran</option>
                        <option value="guru_bk" {{ old('role') == 'guru_bk' ? 'selected' : '' }}>Guru BK (Konselor)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.guru.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
