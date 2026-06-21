<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    protected $table = 'materi';
    protected $fillable = ['guru_id','mata_pelajaran_id','kelas_id','judul','deskripsi','file_path','tipe_file','tahun_ajaran','semester'];

    public function guru() { return $this->belongsTo(Guru::class); }
    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
}
