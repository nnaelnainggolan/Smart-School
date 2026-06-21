@extends('layouts.app')
@section('title', 'Data Orang Tua')
@section('subtitle', 'Kelola seluruh data orang tua / wali siswa')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Orang Tua / Wali</h2>
            <p class="text-sm text-gray-500">Kelola akun dan data orang tua siswa</p>
        </div>
        <a href="{{ route('admin.orangtua.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm">
            <i class="fa-solid fa-plus"></i> Tambah Orang Tua
        </a>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <form class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email orang tua..." class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
            <button type="submit" class="px-5 py-2.5 bg-secondary text-white rounded-xl text-sm font-medium">Cari</button>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Orang Tua</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Anak Terhubung</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orangTua as $ot)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-orange-500 flex items-center justify-center flex-shrink-0">
                                    <span class="text-white text-sm font-bold">{{ substr($ot->nama_ayah,0,1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $ot->nama_ayah }}</p>
                                    @if($ot->nama_ibu)<p class="text-xs text-gray-400">Ibu: {{ $ot->nama_ibu }}</p>@endif
                                    <p class="text-xs text-gray-400">{{ $ot->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-gray-600 text-xs"><i class="fa-solid fa-phone text-gray-400 mr-1"></i>{{ $ot->no_hp }}</p>
                            @if($ot->no_hp_ibu)<p class="text-gray-400 text-xs mt-0.5"><i class="fa-solid fa-phone text-gray-300 mr-1"></i>{{ $ot->no_hp_ibu }} (Ibu)</p>@endif
                        </td>
                        <td class="px-5 py-4">
                            @forelse($ot->siswa as $s)
                                <span class="inline-block px-2 py-0.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-medium mb-1">{{ $s->user->name }}</span><br>
                            @empty
                                <span class="text-xs text-gray-400 italic">Belum terhubung</span>
                            @endforelse
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $ot->user->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $ot->user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.orangtua.edit', $ot->id) }}" class="p-1.5 text-secondary hover:bg-blue-50 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.orangtua.destroy', $ot->id) }}" onsubmit="return confirm('Hapus orang tua ini? Akun login juga akan terhapus.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg transition" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                            <i class="fa-solid fa-people-roof text-3xl text-gray-200 mb-2 block"></i>
                            Belum ada data orang tua
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $orangTua->links() }}</div>
    </div>
</div>
@endsection
