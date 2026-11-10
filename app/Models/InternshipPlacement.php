<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternshipPlacement extends Model
{
    protected $guarded = [];
    protected $casts = ['starts_on' => 'date', 'ends_on' => 'date', 'final_score' => 'decimal:2'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function partner() { return $this->belongsTo(IndustryPartner::class, 'industry_partner_id'); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function journals() { return $this->hasMany(InternshipJournal::class); }
}
