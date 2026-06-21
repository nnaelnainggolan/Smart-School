<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';
    protected $fillable = ['kelas_id','mata_pelajaran_id','guru_id','hari','jam_mulai','jam_selesai','ruangan','tahun_ajaran','semester'];

    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
    public function absensi() { return $this->hasMany(Absensi::class); }
}
