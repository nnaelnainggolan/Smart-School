<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use App\Models\Materi;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolController extends Controller
{
    public function hub()
    {
        return view('school.page', ['title' => 'Layanan Sekolah', 'description' => 'Kelola kegiatan sekolah sesuai akses akun Anda.', 'body' => 'school.hub', 'agenda' => KalenderAkademik::whereDate('tanggal_mulai', '>=', today())->orderBy('tanggal_mulai')->limit(10)->get()]);
    }

    public function read(Request $r, Notifikasi $notification)
    {
        abort_unless($notification->user_id === $r->user()->id, 403);
        $notification->update(['dibaca' => true]);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function material(Request $r, Materi $materi)
    {
        $u = $r->user();
        abort_unless($u->role === 'admin' || ($u->role === 'guru' && $u->guru?->id === $materi->guru_id) || ($u->role === 'siswa' && ($materi->kelas_id === null || ($u->siswa?->kelas_id !== null && $u->siswa->kelas_id === $materi->kelas_id))), 403);
        abort_unless($materi->file_path, 404);
        $disk = Storage::disk('local')->exists($materi->file_path) ? 'local' : 'public';
        abort_unless(Storage::disk($disk)->exists($materi->file_path), 404);

        return Storage::disk($disk)->download($materi->file_path, null, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
