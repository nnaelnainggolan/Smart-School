<?php

namespace App\Services;

use App\Models\{Enrollment, Jadwal, Notifikasi, Siswa};
use Illuminate\Support\Facades\Storage;

class CampusAccess
{
    public static function familyIds(): array
    {
        $u = auth()->user();
        return match ($u->role) {
            'siswa' => $u->siswa ? [$u->siswa->id] : [],
            'orang_tua' => $u->orangTua?->siswa()->pluck('id')->all() ?? [],
            default => [],
        };
    }

    public static function familySchedules()
    {
        $ids = self::familyIds();
        $enrollments = Enrollment::whereIn('siswa_id', $ids)->get();
        $period = SchoolContext::period();
        $fallback = Siswa::whereIn('id', $ids)->where('status', 'aktif')->whereDoesntHave('enrollments', fn ($q) => $q->where('tahun_ajaran', $period['tahun_ajaran']))->get();
        return Jadwal::where(function ($q) use ($enrollments, $fallback, $period) {
            $q->whereRaw('1 = 0');
            foreach ($enrollments as $e) $q->orWhere(fn ($p) => $p->where('kelas_id', $e->kelas_id)->where('tahun_ajaran', $e->tahun_ajaran));
            foreach ($fallback as $s) $q->orWhere(fn ($p) => $p->where('kelas_id', $s->kelas_id)->where('tahun_ajaran', $period['tahun_ajaran']));
        })->select('id');
    }

    public static function studentsForStaff()
    {
        $u = auth()->user();
        $q = Siswa::with(['user', 'kelas']);
        if (in_array($u->role, ['admin', 'guru_bk'])) return $q;
        abort_unless($u->role === 'guru', 403);
        return $q->where(function ($q) use ($u) {
            $q->whereIn('kelas_id', Jadwal::where('guru_id', $u->guru?->id ?? 0)->where(SchoolContext::period())->select('kelas_id'))
                ->orWhereHas('kelas', fn ($k) => $k->where('wali_guru_id', $u->guru?->id ?? 0));
        });
    }

    public static function notifyStudent(int $id, string $title, string $message, string $url): void
    {
        $s = Siswa::findOrFail($id);
        Notifikasi::create(['user_id' => $s->user_id, 'judul' => $title, 'pesan' => $message, 'tipe' => 'info', 'url' => $url]);
    }

    public static function download(?string $path)
    {
        abort_unless($path && Storage::disk('local')->exists($path), 404);
        return Storage::disk('local')->download($path, null, ['Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
