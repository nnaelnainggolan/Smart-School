@extends('layouts.app')
@section('title', 'Jadwal Pelajaran')
@section('sidebar') @include('admin.partials.sidebar') @endsection
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div><h2 class="text-xl font-bold text-gray-800">Jadwal Pelajaran</h2><p class="text-sm text-gray-500">Kelola jadwal pelajaran per kelas</p></div>
        <a href="{{ route('admin.jadwal.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-medium hover:bg-secondary transition text-sm"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Jadwal</a>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
        <form class="flex gap-3">
            <select name="kelas_id" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-secondary">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)<option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>@endforeach
            </select>
            <button type="submit" class="px-5 py-2.5 bg-secondary text-white rounded-xl text-sm font-medium">Filter</button>
        </form>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="bg-gray-50 border-b border-gray-100">
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Kelas</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Mata Pelajaran</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Guru</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Hari</th>
                    <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase">Waktu</th>
                    <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-50">
                @forelse($jadwal as $j)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5"><span class="px-2.5 py-1 bg-primary bg-opacity-10 text-primary rounded-lg text-xs font-semibold">{{ $j->kelas->nama_kelas }}</span></td>
                    <td class="px-5 py-3.5 font-medium text-gray-800">{{ $j->mataPelajaran->nama_mapel }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $j->guru->user->name }}</td>
                    <td class="px-5 py-3.5"><span class="px-2 py-0.5 bg-secondary bg-opacity-10 text-secondary rounded text-xs font-medium">{{ $j->hari }}</span></td>
                    <td class="px-5 py-3.5 text-gray-600 text-xs">{{ substr($j->jam_mulai,0,5) }} – {{ substr($j->jam_selesai,0,5) }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}" onsubmit="return confirm('Hapus jadwal ini?')">@csrf @method('DELETE')<button class="p-1.5 text-red-400 hover:bg-red-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada jadwal pelajaran</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 py-4 border-t border-gray-100">{{ $jadwal->links() }}</div>
    </div>
</div>
@endsection
