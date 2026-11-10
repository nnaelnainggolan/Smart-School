<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicPeriod extends Model
{
    protected $table = 'academic_periods';

    protected $fillable = ['tahun_ajaran', 'semester', 'active'];

    protected function casts(): array
    {
        return ['active' => 'boolean'];
    }
}
