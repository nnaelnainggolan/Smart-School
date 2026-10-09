<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolImport extends Model
{
    protected $table = 'school_imports';

    protected $fillable = ['user_id', 'payload', 'consumed_at'];

    protected function casts(): array
    {
        return ['payload' => 'encrypted:array', 'consumed_at' => 'datetime'];
    }
}
