<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = ['siswa_id','jadwal_id','guru_id','tanggal','status','keterangan'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function jadwal() { return $this->belongsTo(Jadwal::class); }
    public function guru() { return $this->belongsTo(Guru::class); }
}
