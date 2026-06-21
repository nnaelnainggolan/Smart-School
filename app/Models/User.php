<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role', 'is_active'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function siswa() { return $this->hasOne(Siswa::class); }
    public function guru() { return $this->hasOne(Guru::class); }
    public function orangTua() { return $this->hasOne(OrangTua::class); }

    public function isAdmin() { return $this->role === 'admin'; }
    public function isGuru() { return $this->role === 'guru'; }
    public function isGuruBK() { return $this->role === 'guru_bk'; }
    public function isSiswa() { return $this->role === 'siswa'; }
    public function isOrangTua() { return $this->role === 'orang_tua'; }

    public function notifikasi() { return $this->hasMany(Notifikasi::class); }
    public function notifikasiTidakDibaca() { return $this->hasMany(Notifikasi::class)->where('dibaca', false); }
}
