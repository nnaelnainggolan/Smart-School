<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportPublication extends Model
{
    protected $table = 'report_publications';

    protected $fillable = ['siswa_id', 'tahun_ajaran', 'semester', 'snapshot', 'published_by', 'published_at', 'revision'];

    protected function casts(): array
    {
        return ['snapshot' => 'array', 'published_at' => 'datetime'];
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
