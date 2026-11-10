<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumniProfile extends Model
{
    protected $guarded = [];
    protected $casts = ['started_on' => 'date', 'field_match' => 'boolean', 'consent_contact' => 'boolean', 'verified_at' => 'datetime'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
}
