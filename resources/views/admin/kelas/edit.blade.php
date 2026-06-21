@extends('layouts.app')
@section('title', 'Tambah Kelas')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Edit Kelas</h2></div>
        <form method="POST" action="{{ route('admin.kelas.update', $kelas->id) }}" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Kelas <span class="text-red-500">*</span></label>
                <input type="text" name="nama_kelas" value="{{ old('nama_kelas') }}" required placeholder="Contoh: X IPA 1" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Tingkat <span class="text-red-500">*</span></label>
                    <select name="tingkat" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="X">X (Sepuluh)</option>
                        <option value="XI">XI (Sebelas)</option>
                        <option value="XII">XII (Dua Belas)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kapasitas <span class="text-red-500">*</span></label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas', 30) }}" required min="1" max="50" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Jurusan</label>
                <input type="text" name="jurusan" value="{{ old('jurusan') }}" placeholder="IPA / IPS / Teknik / dll" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Wali Kelas</label>
                <input type="text" name="wali_kelas" value="{{ old('wali_kelas') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.kelas.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
