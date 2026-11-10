<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyResult extends Model
{
    protected $guarded = [];
    protected $casts = ['score' => 'decimal:2'];

    public function exam() { return $this->belongsTo(CompetencyExam::class, 'competency_exam_id'); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
