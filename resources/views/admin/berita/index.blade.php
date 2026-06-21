@extends('layouts.app')
@section('title', 'Berita & Pengumuman')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Berita & Pengumuman</h2></div>
        <a href="{{ route('admin.berita.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Buat Berita</a>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead><tr class="bg-gray-50 border-b border-gray-100">
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Judul</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-50">
            @forelse($berita as $b)
            <tr class="hover:bg-gray-50">
                <td class="px-5 py-4"><p class="font-medium text-gray-800 line-clamp-1">{{ $b->judul }}</p><p class="text-xs text-gray-400 mt-0.5">Oleh: {{ $b->user->name }}</p></td>
                <td class="px-5 py-4"><span class="px-2 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium capitalize">{{ $b->kategori }}</span></td>
                <td class="px-5 py-4"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $b->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $b->is_published ? 'Dipublikasikan' : 'Draft' }}</span></td>
                <td class="px-5 py-4 text-gray-500 text-xs">{{ $b->created_at->format('d M Y') }}</td>
                <td class="px-5 py-4"><div class="flex justify-center gap-2">
                    <a href="{{ route('admin.berita.edit', $b->id) }}" class="p-1.5 text-secondary hover:bg-blue-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></a>
                    <form method="POST" action="{{ route('admin.berita.destroy', $b->id) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')<button class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                </div></td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-5 py-10 text-center text-gray-400">Belum ada berita</td></tr>
            @endforelse
            </tbody>
        </table>
        <div class="px-5 py-4 border-t border-gray-100">{{ $berita->links() }}</div>
    </div>
</div>
@endsection
