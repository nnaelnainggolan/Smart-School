<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $guarded = [];
    protected $casts = ['closes_on' => 'date', 'active' => 'boolean'];

    public function partner() { return $this->belongsTo(IndustryPartner::class, 'industry_partner_id'); }
    public function applications() { return $this->hasMany(JobApplication::class); }
}
