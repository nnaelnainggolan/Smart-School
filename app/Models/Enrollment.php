<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    protected $table = 'enrollments';

    protected $fillable = ['siswa_id', 'kelas_id', 'tahun_ajaran'];

    protected function casts(): array
    {
        return [];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
