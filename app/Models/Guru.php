<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = ['user_id', 'nip', 'no_hp', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'pendidikan_terakhir', 'jurusan_pendidikan', 'foto', 'jabatan'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function nilai()
    {
        return $this->hasMany(Nilai::class);
    }

    public function materi()
    {
        return $this->hasMany(Materi::class);
    }

    public function konseling()
    {
        return $this->hasMany(Konseling::class, 'guru_bk_id');
    }

    public function waliKelas()
    {
        return $this->hasMany(Kelas::class, 'wali_guru_id');
    }
}
