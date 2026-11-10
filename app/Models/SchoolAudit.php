<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolAudit extends Model
{
    protected $table = 'school_audits';

    protected $fillable = ['user_id', 'action', 'subject', 'detail'];

    protected function casts(): array
    {
        return ['detail' => 'array'];
    }
}
