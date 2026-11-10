<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipJournal extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date', 'approved_at' => 'datetime'];

    public function placement() { return $this->belongsTo(InternshipPlacement::class, 'internship_placement_id'); }
}
