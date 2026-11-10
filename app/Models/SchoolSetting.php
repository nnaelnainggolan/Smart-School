<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $guarded = [];
    protected $casts = ['data' => 'array'];

    public static function current(): array
    {
        $year = now()->month >= 7 ? now()->year : now()->year - 1;
        return array_replace([
            'name' => 'SMK Hasanuddin', 'type' => 'SMK', 'tagline' => 'Sekolah Unggulan Terpadu',
            'study_years' => 3, 'address' => '', 'phone' => '', 'email' => '',
            'admissions_year' => $year.'/'.($year + 1), 'admissions_open' => true,
            'admissions_start' => null, 'admissions_end' => null, 'show_demo' => false,
        ], static::find(1)?->data ?? []);
    }

    public static function admissionsOpen(): bool
    {
        $s = static::current();
        return $s['admissions_open'] && (!$s['admissions_start'] || today()->gte($s['admissions_start'])) && (!$s['admissions_end'] || today()->lte($s['admissions_end']));
    }
}
