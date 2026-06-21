<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tua';
    protected $fillable = ['user_id','nama_ayah','nama_ibu','pekerjaan_ayah','pekerjaan_ibu','no_hp','no_hp_ibu','alamat'];

    public function user() { return $this->belongsTo(User::class); }
    public function siswa() { return $this->hasMany(Siswa::class); }
}
