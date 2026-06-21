@extends('layouts.app')
@section('title', 'Kalender Akademik')
@section('subtitle', 'Kelola agenda dan kalender sekolah')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-800">Kalender Akademik</h2>
        <a href="{{ route('admin.kalender.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Agenda
        </a>
    </div>
    <div class="space-y-3">
        @forelse($kalender as $k)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center flex-shrink-0
                {{ $k->kategori === 'libur' ? 'bg-red-100 text-red-600' :
                   ($k->kategori === 'ujian' ? 'bg-orange-100 text-orange-600' :
                   ($k->kategori === 'acara' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600')) }}">
                <span class="text-xs font-bold">{{ $k->tanggal_mulai->format('d') }}</span>
                <span class="text-xs">{{ $k->tanggal_mulai->locale('id')->isoFormat('MMM') }}</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h3 class="font-bold text-gray-800">{{ $k->judul }}</h3>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium capitalize
                        {{ $k->kategori === 'libur' ? 'bg-red-100 text-red-700' :
                           ($k->kategori === 'ujian' ? 'bg-orange-100 text-orange-700' :
                           ($k->kategori === 'acara' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700')) }}">
                        {{ $k->kategori }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 mt-0.5">
                    {{ $k->tanggal_mulai->format('d M Y') }}
                    @if($k->tanggal_selesai) — {{ $k->tanggal_selesai->format('d M Y') }} @endif
                </p>
                @if($k->deskripsi)<p class="text-xs text-gray-400 mt-1">{{ $k->deskripsi }}</p>@endif
            </div>
            <form method="POST" action="{{ route('admin.kalender.destroy', $k->id) }}" onsubmit="return confirm('Hapus agenda ini?')">
                @csrf @method('DELETE')
                <button class="p-2 text-red-400 hover:bg-red-50 rounded-lg"><i class="fa-solid fa-trash"></i></button>
            </form>
        </div>
        @empty
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100">
            <i class="fa-regular fa-calendar text-4xl text-gray-200 mb-3 block"></i>
            <p class="text-gray-400">Belum ada agenda kalender akademik</p>
        </div>
        @endforelse
    </div>
    <div>{{ $kalender->links() }}</div>
</div>
@endsection
