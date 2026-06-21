@extends('layouts.app')
@section('title', 'Buat Berita')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Buat Berita / Pengumuman</h2></div>
        <form method="POST" action="{{ route('admin.berita.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul <span class="text-red-500">*</span></label><input type="text" name="judul" value="{{ old('judul') }}" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="kategori" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        @foreach(['berita','pengumuman','prestasi','kegiatan'] as $kat)<option value="{{ $kat }}" {{ old('kategori') == $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>@endforeach
                    </select>
                </div>
                <div class="flex items-end pb-1"><label class="flex items-center gap-2 cursor-pointer"><input type="checkbox" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }} class="rounded border-gray-300 text-secondary"><span class="text-sm font-semibold text-gray-700">Publikasikan Sekarang</span></label></div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Gambar</label><input type="file" name="gambar" accept="image/*" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Konten <span class="text-red-500">*</span></label><textarea name="konten" rows="8" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">{{ old('konten') }}</textarea></div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.berita.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
