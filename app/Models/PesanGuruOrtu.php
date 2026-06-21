<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PesanGuruOrtu extends Model
{
    protected $table = 'pesan_guru_ortu';
    protected $fillable = ['pengirim_id','penerima_id','siswa_id','pesan','dibaca'];

    public function pengirim() { return $this->belongsTo(User::class, 'pengirim_id'); }
    public function penerima() { return $this->belongsTo(User::class, 'penerima_id'); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
