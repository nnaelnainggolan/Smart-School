<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = ['nama_kelas','tingkat','jurusan','wali_kelas','kapasitas'];

    public function siswa() { return $this->hasMany(Siswa::class); }
    public function jadwal() { return $this->hasMany(Jadwal::class); }
}
