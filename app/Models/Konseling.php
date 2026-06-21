<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Konseling extends Model
{
    protected $table = 'konseling';
    protected $fillable = ['siswa_id','guru_bk_id','topik','deskripsi','jenis','status','jadwal_konseling','catatan_bk','status_psikologis'];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function guruBK() { return $this->belongsTo(Guru::class, 'guru_bk_id'); }
    public function chat() { return $this->hasMany(ChatKonseling::class); }
}
