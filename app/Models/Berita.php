<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = 'berita';
    protected $fillable = ['user_id','judul','slug','konten','gambar','kategori','is_published','published_at'];

    protected function casts(): array {
        return ['is_published' => 'boolean', 'published_at' => 'datetime'];
    }

    public function user() { return $this->belongsTo(User::class); }
}
