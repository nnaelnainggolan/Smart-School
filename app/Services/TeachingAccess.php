<?php

namespace App\Services;

use App\Models\Jadwal;
use App\Models\Siswa;
use Illuminate\Validation\ValidationException;

class TeachingAccess
{
    public static function jadwal(Jadwal $jadwal): void
    {
        abort_unless(auth()->user()->guru && (int) $jadwal->guru_id === (int) auth()->user()->guru->id, 403);
    }

    public static function kelas(int $kelas, int $mapel, string $tahun, string $semester): void
    {
        abort_unless(Jadwal::where('guru_id', auth()->user()->guru?->id)
            ->where('kelas_id', $kelas)->where('mata_pelajaran_id', $mapel)
            ->where('tahun_ajaran', $tahun)->where('semester', $semester)->exists(), 403);
    }

    public static function roster(int $kelas, string $year)
    {
        return Siswa::where(function ($q) use ($kelas, $year) {
            $q->whereHas('enrollments', fn ($e) => $e->where('kelas_id', $kelas)->where('tahun_ajaran', $year));
            if ($year === SchoolContext::period()['tahun_ajaran']) {
                $q->orWhere(fn ($fallback) => $fallback->where('kelas_id', $kelas)->where('status', 'aktif')->whereDoesntHave('enrollments', fn ($e) => $e->where('tahun_ajaran', $year)));
            }
        });
    }

    public static function siswa(array $ids, int $kelas, string $year): void
    {
        $valid = self::roster($kelas, $year)->whereIn('id', $ids)->count();
        if ($valid !== count($ids)) {
            throw ValidationException::withMessages(['siswa' => 'Daftar siswa tidak sesuai dengan kelas dan tahun ajaran yang dipilih.']);
        }
    }
}
