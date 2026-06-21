<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    protected $table = 'kalender_akademik';
    protected $fillable = ['judul', 'deskripsi', 'tanggal_mulai', 'tanggal_selesai', 'kategori'];

    protected function casts(): array {
        return ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];
    }
}
