<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndustryPartner extends Model
{
    protected $guarded = [];
    protected $casts = ['active' => 'boolean'];

    public function placements() { return $this->hasMany(InternshipPlacement::class); }
}
