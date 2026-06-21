<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $table = 'notifikasi';
    protected $fillable = ['user_id','judul','pesan','tipe','url','dibaca'];

    public function user() { return $this->belongsTo(User::class); }
}
