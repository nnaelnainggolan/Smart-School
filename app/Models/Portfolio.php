<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    protected $guarded = [];
    protected $casts = ['verified_at' => 'datetime'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
}
