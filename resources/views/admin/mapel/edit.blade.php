@extends('layouts.app')
@section('title', 'Edit Mata Pelajaran')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Edit Mata Pelajaran</h2></div>
        <form method="POST" action="{{ route('admin.mapel.update', $mapel->id) }}" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Mata Pelajaran</label><input type="text" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Mapel</label><input type="text" name="kode_mapel" value="{{ old('kode_mapel', $mapel->kode_mapel) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">KKM</label><input type="number" name="kkm" value="{{ old('kkm', $mapel->kkm) }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelompok</label><input type="text" name="kelompok" value="{{ old('kelompok', $mapel->kelompok) }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label><textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('deskripsi', $mapel->deskripsi) }}</textarea></div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.mapel.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
