<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignmentSubmission extends Model
{
    protected $guarded = [];
    protected $casts = ['submitted_at' => 'datetime', 'reviewed_at' => 'datetime', 'score' => 'decimal:2'];

    public function assignment() { return $this->belongsTo(Assignment::class); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
