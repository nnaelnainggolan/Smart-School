<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    protected $guarded = [];
    protected $casts = [];

    public function vacancy() { return $this->belongsTo(JobVacancy::class, 'job_vacancy_id'); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
