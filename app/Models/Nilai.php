<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    protected $table = 'nilai';

    protected $fillable = ['siswa_id', 'mata_pelajaran_id', 'guru_id', 'tahun_ajaran', 'semester', 'nilai_harian', 'nilai_uts', 'nilai_uas', 'nilai_akhir', 'predikat', 'catatan'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function hitungNilaiAkhir()
    {
        if ($this->nilai_harian !== null && $this->nilai_uts !== null && $this->nilai_uas !== null) {
            return ($this->nilai_harian * 0.4) + ($this->nilai_uts * 0.3) + ($this->nilai_uas * 0.3);
        }

        return null;
    }

    public static function hitungPredikat($nilai)
    {
        if ($nilai >= 90) {
            return 'A';
        }
        if ($nilai >= 80) {
            return 'B';
        }
        if ($nilai >= 70) {
            return 'C';
        }
        if ($nilai >= 60) {
            return 'D';
        }

        return 'E';
    }
}
