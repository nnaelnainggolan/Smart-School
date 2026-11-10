<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class HistoryGuard
{
    public static function check(Model $model, array $relations): void
    {
        foreach ($relations as $relation) {
            if ($model->$relation()->exists()) {
                throw ValidationException::withMessages(['hapus' => 'Data masih terhubung dengan riwayat sekolah. Nonaktifkan akun atau arsipkan status siswa; jangan hapus data ini.']);
            }
        }
    }
}
