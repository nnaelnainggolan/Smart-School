@extends('layouts.app')
@section('title', 'Tambah Jadwal')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="max-w-lg">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50"><h2 class="text-lg font-bold text-gray-800">Tambah Jadwal Pelajaran</h2></div>
        <form method="POST" action="{{ route('admin.jadwal.store') }}" class="p-6 space-y-4">
            @csrf
            @if($errors->any())<div class="bg-red-50 border border-red-200 rounded-xl p-4"><ul class="text-sm text-red-700">@foreach($errors->all() as $e)<li>• {{ $e }}</li>@endforeach</ul></div>@endif
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                <select name="kelas_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Kelas --</option>
                    @foreach($kelas as $k)<option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Mata Pelajaran <span class="text-red-500">*</span></label>
                <select name="mata_pelajaran_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Mapel --</option>
                    @foreach($mapel as $m)<option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>@endforeach
                </select>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Guru <span class="text-red-500">*</span></label>
                <select name="guru_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    <option value="">-- Pilih Guru --</option>
                    @foreach($guru as $g)<option value="{{ $g->id }}">{{ $g->user->name }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Tahun Ajaran <span class="text-red-500">*</span></label>
                    <select name="tahun_ajaran" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        @foreach($tahunAjaran as $ta)<option value="{{ $ta }}">{{ $ta }}</option>@endforeach
                    </select>
                </div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Semester <span class="text-red-500">*</span></label>
                    <select name="semester" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                        <option value="1">Semester 1 (Ganjil)</option>
                        <option value="2">Semester 2 (Genap)</option>
                    </select>
                </div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Hari <span class="text-red-500">*</span></label>
                <select name="hari" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $h)<option value="{{ $h }}">{{ $h }}</option>@endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Mulai <span class="text-red-500">*</span></label><input type="time" name="jam_mulai" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Selesai <span class="text-red-500">*</span></label><input type="time" name="jam_selesai" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            </div>
            <div><label class="block text-sm font-semibold text-gray-700 mb-1.5">Ruangan</label><input type="text" name="ruangan" value="{{ old('ruangan') }}" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary"></div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-semibold hover:bg-secondary transition text-sm">Simpan</button>
                <a href="{{ route('admin.jadwal.index') }}" class="px-6 py-2.5 border border-gray-200 text-gray-600 rounded-xl font-medium hover:bg-gray-50 transition text-sm">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
