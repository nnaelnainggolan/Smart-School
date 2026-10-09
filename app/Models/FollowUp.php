<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $table = 'follow_ups';

    protected $fillable = ['siswa_id', 'user_id', 'note', 'due_date', 'status'];

    protected function casts(): array
    {
        return [];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
