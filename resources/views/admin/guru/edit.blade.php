@extends('layouts.app')
@section('title', 'Edit Guru')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50">
            <h2 class="text-lg font-bold text-gray-800">Edit Data Guru</h2>
        </div>
        <form method="POST" action="{{ route('admin.guru.update', $guru->id) }}" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $guru->user->name) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">No HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $guru->no_hp) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $guru->jabatan) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ $guru->user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-secondary">
                        <span class="text-sm font-semibold text-gray-700">Akun Aktif</span>
                    </label>
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
