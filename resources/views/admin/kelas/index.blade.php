@extends('layouts.app')
@section('title', 'Data Kelas')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Data Kelas</h2><p class="text-sm text-gray-500">Kelola data kelas dan rombongan belajar</p></div>
        <a href="{{ route('admin.kelas.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Kelas
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($kelas as $k)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ $k->tingkat }}</span>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('admin.kelas.edit', $k->id) }}" class="p-1.5 text-secondary hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                    <form method="POST" action="{{ route('admin.kelas.destroy', $k->id) }}" onsubmit="return confirm('Hapus kelas ini?')">@csrf @method('DELETE')<button class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                </div>
            </div>
            <h3 class="font-bold text-gray-800 text-lg">{{ $k->nama_kelas }}</h3>
            @if($k->jurusan)<p class="text-sm text-gray-500">{{ $k->jurusan }}</p>@endif
            <div class="mt-3 flex items-center justify-between text-sm">
                <span class="text-gray-500">Siswa: <strong class="text-gray-800">{{ $k->siswa_count }}</strong></span>
                <span class="text-gray-500">Kapasitas: <strong class="text-gray-800">{{ $k->kapasitas }}</strong></span>
            </div>
            @if($k->wali_kelas)<p class="text-xs text-gray-400 mt-2">Wali Kelas: {{ $k->wali_kelas }}</p>@endif
        </div>
        @empty
        <div class="col-span-3 py-12 text-center text-gray-400">Belum ada data kelas</div>
        @endforelse
    </div>
    <div>{{ $kelas->links() }}</div>
</div>
@endsection
