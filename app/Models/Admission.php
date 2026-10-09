<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $table = 'admissions';

    protected $fillable = ['reference', 'name', 'parent_name', 'email', 'phone', 'previous_school', 'document', 'status', 'note', 'siswa_id'];

    protected function casts(): array
    {
        return [];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
