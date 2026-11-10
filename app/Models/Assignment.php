<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $guarded = [];
    protected $casts = ['due_at' => 'datetime', 'published' => 'boolean'];

    public function jadwal() { return $this->belongsTo(Jadwal::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function submissions() { return $this->hasMany(AssignmentSubmission::class); }
}
