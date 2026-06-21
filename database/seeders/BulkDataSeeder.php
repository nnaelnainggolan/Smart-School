<?php
namespace Database\Seeders;

use App\Models\{User, Siswa, Guru, OrangTua, Kelas, MataPelajaran};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class BulkDataSeeder extends Seeder
{
    // Daftar nama untuk digenerate (kombinasi acak agar terkesan natural)
    private array $namaDepanL = ['Ahmad','Muhammad','Rizki','Fajar','Dimas','Bayu','Andika','Reza','Fadli','Yusuf','Rafi','Arya','Dwiki','Galih','Hendra','Ilham','Joko','Krisna','Lukman','Nanda','Oka','Putra','Qori','Rendi','Sandi','Taufik','Umar','Vino','Wahyu','Zaki'];
    private array $namaDepanP = ['Siti','Putri','Aisyah','Dewi','Nisa','Wulan','Indah','Maya','Salsabila','Fitri','Ayu','Citra','Dinda','Elsa','Farah','Gita','Hana','Intan','Kirana','Laila','Melati','Nadia','Olivia','Permata','Qonita','Ratna','Sari','Tia','Vania','Zahra'];
    private array $namaBelakang = ['Pratama','Saputra','Wijaya','Kurniawan','Setiawan','Hidayat','Nugraha','Santoso','Putra','Permana','Wibowo','Hakim','Firmansyah','Gunawan','Hartono','Iskandar','Kusuma','Lesmana','Maulana','Nurhadi','Pranata','Rahman','Suryadi','Tanjung','Utama','Wahyudi','Yulianto','Anggara','Budiman','Cahyono'];

    public function run(): void
    {
        $this->command->info('🚀 Mulai generate data massal: 10 kelas, 300 siswa, guru, dan orang tua...');

        // ============ 1. BUAT 10 KELAS BARU ============
        $kelasList = [];
        $tingkatList = ['X','X','X','XI','XI','XI','XII','XII','XII','XII'];
        $jurusanList = ['IPA','IPS','IPA','IPA','IPS','Bahasa','IPA','IPS','IPA','IPS'];

        for ($i = 1; $i <= 10; $i++) {
            $kelas = Kelas::create([
                'nama_kelas' => $tingkatList[$i-1] . ' ' . $jurusanList[$i-1] . ' ' . (($i % 3) + 1),
                'tingkat' => $tingkatList[$i-1],
                'jurusan' => $jurusanList[$i-1],
                'wali_kelas' => null, // diisi setelah guru dibuat
                'kapasitas' => 30,
            ]);
            $kelasList[] = $kelas;
        }
        $this->command->info('✅ 10 kelas baru berhasil dibuat.');

        // ============ 2. BUAT GURU SESUAI JUMLAH MATA PELAJARAN ============
        $mapelList = MataPelajaran::all();
        $guruBaruList = [];
        $namaGuruDepan = ['Bambang','Suharto','Rina','Indra','Yulia','Agus','Rosa','Hendro'];
        $gelarList = ['S.Pd','S.Pd, M.Pd','S.Si, M.Pd','S.Pd','M.Pd','S.Pd','S.Pd','S.Pd, Gr'];

        foreach ($mapelList as $idx => $mapel) {
            $namaDepan = $namaGuruDepan[$idx % count($namaGuruDepan)];
            $namaBelakang = $this->namaBelakang[array_rand($this->namaBelakang)];
            $jk = $idx % 2 === 0 ? 'L' : 'P';
            $gelar = $gelarList[$idx % count($gelarList)];
            $namaLengkap = ($jk === 'P' ? 'Ibu ' : 'Bpk. ') . "{$namaDepan} {$namaBelakang}, {$gelar}";

            $emailSlug = strtolower(str_replace([' ','.',','], ['','',''], "{$namaDepan}{$namaBelakang}")) . $idx;

            $user = User::create([
                'name' => $namaLengkap,
                'email' => "{$emailSlug}@smartschool.id",
                'password' => Hash::make('password'),
                'role' => 'guru',
                'is_active' => true,
            ]);

            $guru = Guru::create([
                'user_id' => $user->id,
                'nip' => '20' . rand(10,24) . str_pad($idx+1, 6, '0', STR_PAD_LEFT) . str_pad($idx, 2, '0', STR_PAD_LEFT),
                'no_hp' => '08' . rand(11,29) . rand(10000000,99999999),
                'jenis_kelamin' => $jk,
                'jabatan' => 'Guru ' . $mapel->nama_mapel,
            ]);

            $guruBaruList[] = ['guru' => $guru, 'mapel' => $mapel];
        }
        $this->command->info('✅ ' . count($guruBaruList) . ' guru baru berhasil dibuat (1 guru per mata pelajaran).');

        // ============ 3. TUGASKAN WALI KELAS (BERGILIR DARI GURU YANG ADA) ============
        foreach ($kelasList as $idx => $kelas) {
            $guruWali = $guruBaruList[$idx % count($guruBaruList)]['guru'];
            $kelas->update(['wali_kelas' => $guruWali->user->name]);
        }
        $this->command->info('✅ Wali kelas berhasil ditugaskan ke 10 kelas (bergilir).');

        // ============ 4. BUAT 300 SISWA + 300 ORANG TUA ============
        $tahunMasukMap = ['X' => '2025', 'XI' => '2024', 'XII' => '2023'];
        $counterGlobal = DB::table('siswa')->count() + 1; // lanjutkan nomor NIS dari yang sudah ada

        foreach ($kelasList as $kelas) {
            for ($s = 1; $s <= 30; $s++) {
                $jk = rand(0,1) === 0 ? 'L' : 'P';
                $namaDepan = $jk === 'L'
                    ? $this->namaDepanL[array_rand($this->namaDepanL)]
                    : $this->namaDepanP[array_rand($this->namaDepanP)];
                $namaBelakang = $this->namaBelakang[array_rand($this->namaBelakang)];
                $namaSiswa = "{$namaDepan} {$namaBelakang}";

                $nis = '20' . substr($tahunMasukMap[$kelas->tingkat], -2) . str_pad($counterGlobal, 4, '0', STR_PAD_LEFT);
                $nisn = '00' . str_pad($counterGlobal, 8, '0', STR_PAD_LEFT);

                // --- Buat Orang Tua dulu ---
                $namaAyah = $this->namaDepanL[array_rand($this->namaDepanL)] . ' ' . $namaBelakang;
                $namaIbu = $this->namaDepanP[array_rand($this->namaDepanP)] . ' ' . $namaBelakang;
                $emailOrtuSlug = 'ortu' . $counterGlobal . strtolower(substr(str_replace(' ','',$namaBelakang),0,4));

                $userOrtu = User::create([
                    'name' => $namaAyah . ' (Ortu)',
                    'email' => "{$emailOrtuSlug}@smartschool.id",
                    'password' => Hash::make('password'),
                    'role' => 'orang_tua',
                    'is_active' => true,
                ]);

                $pekerjaanAyahList = ['Wiraswasta','PNS','Karyawan Swasta','Pedagang','Petani','Guru'];
                $pekerjaanIbuList = ['Ibu Rumah Tangga','PNS','Karyawan Swasta','Wiraswasta'];
                $kotaList = ['Medan','Binjai','Deli Serdang','Langkat','Tebing Tinggi'];

                $orangTua = OrangTua::create([
                    'user_id' => $userOrtu->id,
                    'nama_ayah' => $namaAyah,
                    'nama_ibu' => $namaIbu,
                    'pekerjaan_ayah' => $pekerjaanAyahList[array_rand($pekerjaanAyahList)],
                    'pekerjaan_ibu' => $pekerjaanIbuList[array_rand($pekerjaanIbuList)],
                    'no_hp' => '08' . rand(11,29) . rand(10000000,99999999),
                    'no_hp_ibu' => '08' . rand(11,29) . rand(10000000,99999999),
                    'alamat' => 'Jl. ' . $this->namaBelakang[array_rand($this->namaBelakang)] . ' No. ' . rand(1,150) . ', Medan',
                ]);

                // --- Buat Siswa, terhubung ke Orang Tua & Kelas ---
                $emailSiswaSlug = strtolower(str_replace(' ','',$namaSiswa)) . $counterGlobal;

                $userSiswa = User::create([
                    'name' => $namaSiswa,
                    'email' => "{$emailSiswaSlug}@smartschool.id",
                    'password' => Hash::make('password'),
                    'role' => 'siswa',
                    'is_active' => true,
                ]);

                Siswa::create([
                    'user_id' => $userSiswa->id,
                    'orang_tua_id' => $orangTua->id,
                    'kelas_id' => $kelas->id,
                    'nis' => $nis,
                    'nisn' => $nisn,
                    'jenis_kelamin' => $jk,
                    'tanggal_lahir' => now()->subYears(15 + rand(0,2))->subDays(rand(0,365))->toDateString(),
                    'tempat_lahir' => $kotaList[array_rand($kotaList)],
                    'alamat' => 'Jl. ' . $this->namaBelakang[array_rand($this->namaBelakang)] . ' No. ' . rand(1,150) . ', Medan',
                    'tahun_masuk' => $tahunMasukMap[$kelas->tingkat],
                    'status' => 'aktif',
                ]);

                $counterGlobal++;
            }
            $this->command->info("  → Kelas {$kelas->nama_kelas}: 30 siswa + 30 orang tua selesai dibuat.");
        }

        $this->command->info('');
        $this->command->info('🎉 SELESAI! Ringkasan data yang ditambahkan:');
        $this->command->table(
            ['Kategori', 'Jumlah Ditambahkan'],
            [
                ['Kelas Baru', count($kelasList)],
                ['Guru Baru', count($guruBaruList)],
                ['Siswa Baru', 300],
                ['Orang Tua Baru', 300],
            ]
        );
        $this->command->info('🔑 Semua akun baru menggunakan password: password');
    }
}
