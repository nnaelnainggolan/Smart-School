@extends('layouts.app')
@section('title', 'Mata Pelajaran')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Mata Pelajaran</h2><p class="text-sm text-gray-500">Kelola daftar mata pelajaran</p></div>
        <a href="{{ route('admin.mapel.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Mapel</a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kode</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kelompok</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">KKM</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($mapel as $m)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-4"><p class="font-medium text-gray-800">{{ $m->nama_mapel }}</p><p class="text-xs text-gray-400 mt-0.5">{{ $m->deskripsi }}</p></td>
                    <td class="px-5 py-4 font-mono text-xs text-gray-500">{{ $m->kode_mapel }}</td>
                    <td class="px-5 py-4 text-gray-600">{{ $m->kelompok ?? '-' }}</td>
                    <td class="px-5 py-4"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">{{ $m->kkm }}</span></td>
                    <td class="px-5 py-4"><div class="flex justify-center gap-2">
                        <a href="{{ route('admin.mapel.edit', $m->id) }}" class="p-1.5 text-secondary hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                        <form method="POST" action="{{ route('admin.mapel.destroy', $m->id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                    </div></td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada mata pelajaran</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $mapel->links() }}</div>
    </div>
</div>
@endsection
