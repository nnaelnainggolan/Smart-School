<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = ['nama_mapel','kode_mapel','kelompok','kkm','deskripsi'];

    public function jadwal() { return $this->hasMany(Jadwal::class); }
    public function nilai() { return $this->hasMany(Nilai::class); }
    public function materi() { return $this->hasMany(Materi::class); }
}
