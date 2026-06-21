<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $fillable = ['user_id','orang_tua_id','kelas_id','nis','nisn','jenis_kelamin','tanggal_lahir','tempat_lahir','alamat','no_hp','foto','tahun_masuk','status'];

    public function user() { return $this->belongsTo(User::class); }
    public function orangTua() { return $this->belongsTo(OrangTua::class, 'orang_tua_id'); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function absensi() { return $this->hasMany(Absensi::class); }
    public function nilai() { return $this->hasMany(Nilai::class); }
    public function konseling() { return $this->hasMany(Konseling::class); }
}
