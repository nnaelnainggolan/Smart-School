<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScheme extends Model
{
    protected $guarded = [];
    protected $casts = ['weights' => 'array'];

    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }

    public static function weightsFor(int $subject, string $year, string $semester): array
    {
        return static::where(['mata_pelajaran_id' => $subject, 'tahun_ajaran' => $year, 'semester' => $semester])->first()?->weights ?? ['harian' => 40, 'uts' => 30, 'uas' => 30];
    }

    public static function calculate(array $data, array $weights): ?float
    {
        $total = 0;
        foreach ($weights as $key => $weight) {
            if ($weight > 0 && !isset($data['nilai_'.$key])) return null;
            $total += ($data['nilai_'.$key] ?? 0) * $weight / 100;
        }
        return round($total, 2);
    }
}
