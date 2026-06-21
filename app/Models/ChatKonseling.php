<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatKonseling extends Model
{
    protected $table = 'chat_konseling';
    protected $fillable = ['konseling_id','user_id','pesan','dibaca'];

    public function konseling() { return $this->belongsTo(Konseling::class); }
    public function user() { return $this->belongsTo(User::class); }
}
