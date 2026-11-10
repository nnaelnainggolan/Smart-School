<?php

namespace App\Services;

use App\Models\Kelas;
use App\Models\Siswa;

class HomeroomAccess
{
    public static function classes()
    {
        $q = Kelas::query();
        if (auth()->user()->role !== 'admin') {
            $q->where('wali_guru_id', auth()->user()->guru?->id ?? 0);
        }

        return $q;
    }

    public static function student(Siswa $s): void
    {
        abort_unless(auth()->user()->role === 'admin' || ($s->kelas_id && self::classes()->whereKey($s->kelas_id)->exists()), 403);
    }
}
