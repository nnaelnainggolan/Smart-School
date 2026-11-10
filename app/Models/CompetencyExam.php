<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetencyExam extends Model
{
    protected $guarded = [];
    protected $casts = ['scheduled_at' => 'datetime'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function results() { return $this->hasMany(CompetencyResult::class); }
}
