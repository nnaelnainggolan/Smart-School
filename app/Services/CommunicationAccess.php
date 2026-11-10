<?php

namespace App\Services;

use App\Models\Siswa;
use App\Models\User;

class CommunicationAccess
{
    public static function teachers(Siswa $s)
    {
        return User::where('is_active', true)->where('role', 'guru')->whereHas('guru', function ($g) use ($s) {
            $g->whereHas('jadwal', fn ($j) => $j->where('kelas_id', $s->kelas_id)->where(SchoolContext::period()))
                ->orWhereHas('waliKelas', fn ($k) => $k->whereKey($s->kelas_id))
                ->orWhereHas('konseling', fn ($k) => $k->where('siswa_id', $s->id));
        });
    }
}
