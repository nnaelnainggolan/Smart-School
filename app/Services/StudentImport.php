<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Kelas;
use App\Models\OrangTua;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class StudentImport
{
    public const COLUMNS = ['nis', 'nama_siswa', 'email_siswa', 'jenis_kelamin', 'kelas', 'tahun_masuk', 'nama_ortu', 'email_ortu', 'no_hp_ortu'];

    public static function csvCell(string $value): string
    {
        return preg_match('/^[=+@\-\t\r\n]/', $value) ? "'".$value : $value;
    }

    public function validateRows(array $rows): array
    {
        if (! $rows || count($rows) > 1000) {
            throw ValidationException::withMessages(['file' => 'Isi 1–1000 siswa per file.']);
        }
        $seen = [];
        $parents = [];
        $emails = [];
        foreach ($rows as $i => $r) {
            $v = Validator::make($r, [
                'nis' => 'required|string|max:30|unique:siswa,nis', 'nama_siswa' => 'required|string|max:255',
                'email_siswa' => 'required|email|max:255|unique:users,email', 'jenis_kelamin' => 'required|in:L,P',
                'kelas' => 'required|string|exists:kelas,nama_kelas', 'tahun_masuk' => 'required|digits:4',
                'nama_ortu' => 'required|string|max:255', 'email_ortu' => 'required|email|max:255', 'no_hp_ortu' => 'required|string|max:20',
            ]);
            $errors = $v->errors()->all();
            if (isset($seen[$r['nis'] ?? ''])) {
                $errors[] = 'NIS berulang dalam file.';
            }
            $seen[$r['nis'] ?? ''] = true;
            $se = strtolower($r['email_siswa'] ?? '');
            $pe = strtolower($r['email_ortu'] ?? '');
            if (isset($emails[$se]) || $se === $pe || isset($parents[$se])) {
                $errors[] = 'Email siswa berulang atau digunakan orang tua.';
            }
            if (isset($emails[$pe])) {
                $errors[] = 'Email orang tua digunakan siswa.';
            }
            if (User::whereRaw('LOWER(email) = ?', [$se])->exists()) {
                $errors[] = 'Email siswa sudah digunakan.';
            }
            $emails[$se] = true;
            if (isset($parents[$pe]) && $parents[$pe] !== [$r['nama_ortu'] ?? '', $r['no_hp_ortu'] ?? '']) {
                $errors[] = 'Identitas orang tua dengan email sama tidak konsisten.';
            }
            $parents[$pe] = [$r['nama_ortu'] ?? '', $r['no_hp_ortu'] ?? ''];
            $existing = User::whereRaw('LOWER(email) = ?', [$pe])->first();
            if ($existing && ($existing->role !== 'orang_tua' || ! $existing->orangTua)) {
                $errors[] = 'Email orang tua sudah digunakan role lain.';
            }
            if ($existing?->orangTua && ($existing->orangTua->nama_ayah !== $r['nama_ortu'] || $existing->orangTua->no_hp !== $r['no_hp_ortu'])) {
                $errors[] = 'Nama/nomor orang tua tidak cocok dengan akun yang ada. Tinjau sebelum menghubungkan.';
            }
            if (Kelas::where('nama_kelas', $r['kelas'] ?? '')->count() > 1) {
                $errors[] = 'Nama kelas ambigu; rapikan nama kelas terlebih dahulu.';
            }
            if ($errors) {
                throw ValidationException::withMessages(['file' => 'Baris '.($i + 2).': '.implode(' ', $errors)]);
            }
        }
        foreach (collect($rows)->groupBy('kelas') as $name => $group) {
            $k = Kelas::where('nama_kelas', $name)->firstOrFail();
            if ($k->siswa()->where('status', 'aktif')->count() + $group->count() > $k->kapasitas) {
                throw ValidationException::withMessages(['file' => "Kapasitas kelas $name tidak mencukupi."]);
            }
        }

        return $rows;
    }

    public function createRows(array $rows): array
    {
        Kelas::whereIn('nama_kelas', array_column($rows, 'kelas'))->orderBy('id')->lockForUpdate()->get();
        $this->validateRows($rows);
        $credentials = [];
        foreach ($rows as $r) {
            $parentUser = User::whereRaw('LOWER(email) = ?', [strtolower($r['email_ortu'])])->first();
            if (! $parentUser) {
                $password = Str::random(24);
                $parentUser = User::create(['name' => $r['nama_ortu'].' (Ortu)', 'email' => strtolower($r['email_ortu']), 'password' => Hash::make($password), 'role' => 'orang_tua', 'is_active' => true]);
                $parentUser->forceFill(['must_change_password' => true])->save();
                OrangTua::create(['user_id' => $parentUser->id, 'nama_ayah' => $r['nama_ortu'], 'no_hp' => $r['no_hp_ortu']]);
                $credentials[] = ['email' => $parentUser->email, 'password' => $password, 'role' => 'orang_tua'];
            }
            $password = Str::random(24);
            $user = User::create(['name' => $r['nama_siswa'], 'email' => strtolower($r['email_siswa']), 'password' => Hash::make($password), 'role' => 'siswa', 'is_active' => true]);
            $user->forceFill(['must_change_password' => true])->save();
            $kelas = Kelas::where('nama_kelas', $r['kelas'])->firstOrFail();
            $s = Siswa::create(['user_id' => $user->id, 'orang_tua_id' => $parentUser->orangTua->id, 'kelas_id' => $kelas->id, 'nis' => $r['nis'], 'jenis_kelamin' => $r['jenis_kelamin'], 'tahun_masuk' => $r['tahun_masuk'], 'status' => 'aktif']);
            Enrollment::create(['siswa_id' => $s->id, 'kelas_id' => $kelas->id, 'tahun_ajaran' => SchoolContext::period()['tahun_ajaran']]);
            $credentials[] = ['email' => $user->email, 'password' => $password, 'role' => 'siswa'];
        }

        return $credentials;
    }
}
