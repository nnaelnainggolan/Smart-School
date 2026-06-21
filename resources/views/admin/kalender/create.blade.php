@extends('layouts.app')
@section('title', 'Tambah Agenda')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Tambah Agenda Kalender</h2></div>
        <form method="POST" action="{{ route('admin.kalender.store') }}" class="p-6 space-y-4">
            @csrf
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Judul Agenda <span class="text-red-500">*</span></label>
                <input type="text" name="judul" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                <select name="kategori" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="akademik">Akademik</option>
                    <option value="ujian">Ujian</option>
                    <option value="libur">Libur</option>
                    <option value="acara">Acara/Kegiatan</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Mulai <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_mulai" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.kalender.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
