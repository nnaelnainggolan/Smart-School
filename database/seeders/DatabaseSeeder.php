<?php
namespace Database\Seeders;

use App\Models\{User, Siswa, Guru, OrangTua, Kelas, MataPelajaran, Jadwal, Absensi, Nilai, Berita, Notifikasi, KalenderAkademik, Materi, Konseling, ChatKonseling, PesanGuruOrtu};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============ KELAS ============
        $kelas = [
            ['nama_kelas' => 'X IPA 1', 'tingkat' => 'X', 'jurusan' => 'IPA', 'wali_kelas' => 'Dra. Siti Rahayu', 'kapasitas' => 32],
            ['nama_kelas' => 'X IPS 1', 'tingkat' => 'X', 'jurusan' => 'IPS', 'wali_kelas' => 'Bpk. Ahmad Fauzi', 'kapasitas' => 30],
            ['nama_kelas' => 'XI IPA 1', 'tingkat' => 'XI', 'jurusan' => 'IPA', 'wali_kelas' => 'Ibu Dewi Lestari', 'kapasitas' => 32],
            ['nama_kelas' => 'XI IPS 1', 'tingkat' => 'XI', 'jurusan' => 'IPS', 'wali_kelas' => 'Bpk. Hendra Wijaya', 'kapasitas' => 30],
            ['nama_kelas' => 'XII IPA 1', 'tingkat' => 'XII', 'jurusan' => 'IPA', 'wali_kelas' => 'Ibu Ratna Sari', 'kapasitas' => 30],
        ];
        foreach ($kelas as $k) Kelas::create($k);

        // ============ MATA PELAJARAN ============
        $mapelData = [
            ['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK', 'kelompok' => 'A', 'kkm' => 75],
            ['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BIN', 'kelompok' => 'A', 'kkm' => 75],
            ['nama_mapel' => 'Bahasa Inggris', 'kode_mapel' => 'BING', 'kelompok' => 'A', 'kkm' => 75],
            ['nama_mapel' => 'Fisika', 'kode_mapel' => 'FIS', 'kelompok' => 'B', 'kkm' => 70],
            ['nama_mapel' => 'Kimia', 'kode_mapel' => 'KIM', 'kelompok' => 'B', 'kkm' => 70],
            ['nama_mapel' => 'Biologi', 'kode_mapel' => 'BIO', 'kelompok' => 'B', 'kkm' => 70],
            ['nama_mapel' => 'Pendidikan Agama', 'kode_mapel' => 'PAI', 'kelompok' => 'A', 'kkm' => 75],
            ['nama_mapel' => 'Pendidikan Kewarganegaraan', 'kode_mapel' => 'PKN', 'kelompok' => 'A', 'kkm' => 75],
        ];
        foreach ($mapelData as $m) MataPelajaran::create($m);

        // ============ ADMIN ============
        User::create([
            'name' => 'Administrator Sekolah',
            'email' => 'admin@smartschool.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // ============ GURU ============
        $guruData = [
            ['name' => 'Bpk. Ahmad Fauzi, S.Pd', 'email' => 'guru@smartschool.id', 'nip' => '198501012010011001', 'jk' => 'L', 'jabatan' => 'Guru Matematika'],
            ['name' => 'Ibu Dewi Lestari, S.Pd', 'email' => 'dewi@smartschool.id', 'nip' => '198703152012012002', 'jk' => 'P', 'jabatan' => 'Guru Bahasa Indonesia'],
            ['name' => 'Bpk. Hendra Wijaya, M.Pd', 'email' => 'hendra@smartschool.id', 'nip' => '198605202011011003', 'jk' => 'L', 'jabatan' => 'Guru Fisika'],
        ];
        foreach ($guruData as $g) {
            $user = User::create(['name' => $g['name'], 'email' => $g['email'], 'password' => Hash::make('password'), 'role' => 'guru', 'is_active' => true]);
            Guru::create(['user_id' => $user->id, 'nip' => $g['nip'], 'jenis_kelamin' => $g['jk'], 'jabatan' => $g['jabatan'], 'no_hp' => '0812' . rand(10000000, 99999999)]);
        }

        // ============ GURU BK ============
        $userBK = User::create(['name' => 'Ibu Ratna Sari, S.Psi', 'email' => 'bk@smartschool.id', 'password' => Hash::make('password'), 'role' => 'guru_bk', 'is_active' => true]);
        Guru::create(['user_id' => $userBK->id, 'nip' => '198902112013012004', 'jenis_kelamin' => 'P', 'jabatan' => 'Guru BK / Konselor', 'no_hp' => '081234567890']);

        // ============ SISWA & ORANG TUA (Demo) ============
        $kelasX = Kelas::first();

        // Siswa demo utama
        $userOrtu = User::create(['name' => 'Bpk. Budi Santoso (Ortu)', 'email' => 'ortu@smartschool.id', 'password' => Hash::make('password'), 'role' => 'orang_tua', 'is_active' => true]);
        $orangTua = OrangTua::create(['user_id' => $userOrtu->id, 'nama_ayah' => 'Budi Santoso', 'nama_ibu' => 'Sri Wahyuni', 'no_hp' => '08123456789', 'alamat' => 'Jl. Merdeka No. 10, Medan']);

        $userSiswa = User::create(['name' => 'Andi Santoso', 'email' => 'siswa@smartschool.id', 'password' => Hash::make('password'), 'role' => 'siswa', 'is_active' => true]);
        $siswa = Siswa::create(['user_id' => $userSiswa->id, 'orang_tua_id' => $orangTua->id, 'kelas_id' => $kelasX->id, 'nis' => '2024001', 'nisn' => '0012345678', 'jenis_kelamin' => 'L', 'tanggal_lahir' => '2008-05-15', 'tempat_lahir' => 'Medan', 'tahun_masuk' => '2024', 'status' => 'aktif']);

        // Siswa tambahan untuk data demo
        $siswaTambahan = [
            ['name' => 'Siti Aisyah', 'nis' => '2024002', 'jk' => 'P'],
            ['name' => 'Rizky Pratama', 'nis' => '2024003', 'jk' => 'L'],
            ['name' => 'Dina Puspita', 'nis' => '2024004', 'jk' => 'P'],
            ['name' => 'Fajar Nugroho', 'nis' => '2024005', 'jk' => 'L'],
        ];
        foreach ($siswaTambahan as $idx => $st) {
            $uOrtu = User::create(['name' => 'Ortu ' . $st['name'], 'email' => 'ortu' . ($idx+2) . '@smartschool.id', 'password' => Hash::make('password'), 'role' => 'orang_tua', 'is_active' => true]);
            $ot = OrangTua::create(['user_id' => $uOrtu->id, 'nama_ayah' => 'Ayah ' . $st['name'], 'no_hp' => '0812' . rand(10000000, 99999999)]);
            $uSiswa = User::create(['name' => $st['name'], 'email' => strtolower(str_replace(' ', '', $st['name'])) . '@smartschool.id', 'password' => Hash::make('password'), 'role' => 'siswa', 'is_active' => true]);
            Siswa::create(['user_id' => $uSiswa->id, 'orang_tua_id' => $ot->id, 'kelas_id' => $kelasX->id, 'nis' => $st['nis'], 'jenis_kelamin' => $st['jk'], 'tahun_masuk' => '2024', 'status' => 'aktif']);
        }

        // ============ JADWAL ============
        $guruObj = Guru::first();
        $mapel1 = MataPelajaran::where('kode_mapel', 'MTK')->first();
        $mapel2 = MataPelajaran::where('kode_mapel', 'BIN')->first();
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jadwalData = [
            ['kelas_id' => $kelasX->id, 'mata_pelajaran_id' => $mapel1->id, 'guru_id' => $guruObj->id, 'hari' => 'Senin', 'jam_mulai' => '07:30', 'jam_selesai' => '09:00'],
            ['kelas_id' => $kelasX->id, 'mata_pelajaran_id' => $mapel2->id, 'guru_id' => $guruObj->id, 'hari' => 'Selasa', 'jam_mulai' => '07:30', 'jam_selesai' => '09:00'],
            ['kelas_id' => $kelasX->id, 'mata_pelajaran_id' => $mapel1->id, 'guru_id' => $guruObj->id, 'hari' => 'Rabu', 'jam_mulai' => '10:00', 'jam_selesai' => '11:30'],
        ];
        foreach ($jadwalData as $j) {
            Jadwal::create(array_merge($j, ['tahun_ajaran' => '2024/2025', 'semester' => '1']));
        }

        // ============ NILAI DEMO ============
        $semuaSiswa = Siswa::where('kelas_id', $kelasX->id)->get();
        $semuaMapel = MataPelajaran::take(5)->get();
        foreach ($semuaSiswa as $s) {
            foreach ($semuaMapel as $m) {
                $h = rand(70, 95);
                $u = rand(65, 95);
                $a = rand(68, 95);
                $akhir = ($h * 0.4) + ($u * 0.3) + ($a * 0.3);
                Nilai::create([
                    'siswa_id' => $s->id,
                    'mata_pelajaran_id' => $m->id,
                    'guru_id' => $guruObj->id,
                    'tahun_ajaran' => '2024/2025',
                    'semester' => '1',
                    'nilai_harian' => $h,
                    'nilai_uts' => $u,
                    'nilai_uas' => $a,
                    'nilai_akhir' => round($akhir, 2),
                    'predikat' => \App\Models\Nilai::hitungPredikat($akhir),
                ]);
            }
        }

        // ============ ABSENSI DEMO ============
        $jadwal = Jadwal::first();
        if ($jadwal) {
            $tanggalList = [now()->subDays(2), now()->subDays(5), now()->subDays(9)];
            foreach ($semuaSiswa as $s) {
                foreach ($tanggalList as $tgl) {
                    $statusList = ['Hadir', 'Hadir', 'Hadir', 'Izin', 'Sakit'];
                    Absensi::create([
                        'siswa_id' => $s->id,
                        'jadwal_id' => $jadwal->id,
                        'guru_id' => $guruObj->id,
                        'tanggal' => $tgl->toDateString(),
                        'status' => $statusList[array_rand($statusList)],
                    ]);
                }
            }
        }

        // ============ BERITA ============
        $admin = User::where('role', 'admin')->first();
        $beritaData = [
            ['judul' => 'Penerimaan Peserta Didik Baru Tahun 2025/2026 Telah Dibuka', 'kategori' => 'pengumuman', 'konten' => 'SMA Smart School dengan bangga mengumumkan bahwa Penerimaan Peserta Didik Baru (PPDB) untuk tahun ajaran 2025/2026 telah resmi dibuka. Pendaftaran dapat dilakukan secara online melalui website sekolah mulai tanggal 1 Januari 2025.'],
            ['judul' => 'Siswa Kita Raih Juara Olimpiade Matematika Nasional', 'kategori' => 'prestasi', 'konten' => 'Kami dengan bangga mengumumkan bahwa salah satu siswa terbaik kita berhasil meraih juara pertama dalam Olimpiade Matematika Nasional yang diselenggarakan di Jakarta. Prestasi ini membanggakan seluruh warga sekolah.'],
            ['judul' => 'Jadwal Ujian Semester 1 Tahun Ajaran 2024/2025', 'kategori' => 'pengumuman', 'konten' => 'Ujian Akhir Semester 1 akan dilaksanakan mulai tanggal 15 Januari 2025. Seluruh siswa diwajibkan hadir tepat waktu dan membawa kartu ujian yang telah divalidasi oleh wali kelas masing-masing.'],
        ];
        foreach ($beritaData as $b) {
            Berita::create(array_merge($b, ['user_id' => $admin->id, 'slug' => Str::slug($b['judul']) . '-' . time() . rand(1,999), 'is_published' => true, 'published_at' => now()->subDays(rand(1,10))]));
        }

        // ============ NOTIFIKASI DEMO ============
        $siswaUser = User::where('email', 'siswa@smartschool.id')->first();
        Notifikasi::create(['user_id' => $siswaUser->id, 'judul' => 'Selamat Datang di Smart School', 'pesan' => 'Akun Anda telah berhasil dibuat. Selamat menggunakan sistem.', 'tipe' => 'success']);

        // ============ KALENDER AKADEMIK ============
        $kalenderData = [
            ['judul' => 'Awal Tahun Ajaran 2024/2025', 'kategori' => 'akademik', 'tanggal_mulai' => '2024-07-15', 'deskripsi' => 'Hari pertama masuk sekolah tahun ajaran baru'],
            ['judul' => 'Ujian Tengah Semester 1', 'kategori' => 'ujian', 'tanggal_mulai' => '2024-09-23', 'tanggal_selesai' => '2024-09-27', 'deskripsi' => 'Pelaksanaan UTS untuk seluruh kelas'],
            ['judul' => 'Libur Maulid Nabi', 'kategori' => 'libur', 'tanggal_mulai' => '2024-09-16', 'deskripsi' => 'Libur nasional memperingati Maulid Nabi Muhammad SAW'],
            ['judul' => 'Gelar Karya Siswa 2024', 'kategori' => 'acara', 'tanggal_mulai' => '2024-12-10', 'deskripsi' => 'Pameran hasil karya dan kreativitas siswa'],
            ['judul' => 'Ujian Akhir Semester 1', 'kategori' => 'ujian', 'tanggal_mulai' => '2025-01-15', 'tanggal_selesai' => '2025-01-20', 'deskripsi' => 'Pelaksanaan UAS Semester 1'],
            ['judul' => 'Libur Semester 1', 'kategori' => 'libur', 'tanggal_mulai' => '2025-01-22', 'tanggal_selesai' => '2025-02-02', 'deskripsi' => 'Libur akhir semester ganjil'],
        ];
        foreach ($kalenderData as $k) KalenderAkademik::create($k);

        // ============ MATERI PELAJARAN DEMO ============
        $mapelMtk = MataPelajaran::where('kode_mapel', 'MTK')->first();
        $mapelBin = MataPelajaran::where('kode_mapel', 'BIN')->first();
        Materi::create(['guru_id' => $guruObj->id, 'mata_pelajaran_id' => $mapelMtk->id, 'kelas_id' => $kelasX->id, 'judul' => 'Aljabar Linear - Bab 1', 'deskripsi' => 'Materi pengantar aljabar linear untuk kelas X', 'tahun_ajaran' => '2024/2025', 'semester' => '1']);
        Materi::create(['guru_id' => $guruObj->id, 'mata_pelajaran_id' => $mapelMtk->id, 'kelas_id' => $kelasX->id, 'judul' => 'Latihan Soal Trigonometri', 'deskripsi' => 'Kumpulan latihan soal trigonometri dasar', 'tahun_ajaran' => '2024/2025', 'semester' => '1']);
        Materi::create(['guru_id' => $guruObj->id, 'mata_pelajaran_id' => $mapelBin->id, 'kelas_id' => null, 'judul' => 'Teknik Menulis Esai yang Baik', 'deskripsi' => 'Panduan menulis esai akademik untuk semua kelas', 'tahun_ajaran' => '2024/2025', 'semester' => '1']);

        // ============ KONSELING DEMO ============
        $konseling1 = Konseling::create([
            'siswa_id' => $siswa->id,
            'guru_bk_id' => null,
            'topik' => 'Kesulitan Membagi Waktu Belajar',
            'deskripsi' => 'Saya merasa kesulitan membagi waktu antara belajar dan kegiatan ekstrakurikuler. Mohon bimbingannya.',
            'jenis' => 'akademik',
            'status' => 'pending',
        ]);

        $guruBKObj = Guru::whereHas('user', fn($q) => $q->where('role', 'guru_bk'))->first();
        $konseling2 = Konseling::create([
            'siswa_id' => $siswa->id,
            'guru_bk_id' => $guruBKObj->id,
            'topik' => 'Adaptasi di Lingkungan Sekolah Baru',
            'deskripsi' => 'Saya baru pindah ke sekolah ini dan masih merasa belum nyaman dengan teman-teman baru.',
            'jenis' => 'sosial',
            'status' => 'selesai',
            'catatan_bk' => 'Siswa menunjukkan perkembangan baik dalam beradaptasi. Disarankan untuk lebih aktif dalam kegiatan kelompok.',
            'status_psikologis' => 'baik',
        ]);
        ChatKonseling::create(['konseling_id' => $konseling2->id, 'user_id' => $siswaUser->id, 'pesan' => 'Selamat siang Bu, saya ingin konsultasi tentang kesulitan beradaptasi di sekolah baru.']);
        ChatKonseling::create(['konseling_id' => $konseling2->id, 'user_id' => $userBK->id, 'pesan' => 'Selamat siang. Baik, ceritakan lebih detail apa yang membuat kamu merasa belum nyaman?']);

        // ============ PESAN GURU-ORTU DEMO ============
        PesanGuruOrtu::create([
            'pengirim_id' => $userOrtu->id,
            'penerima_id' => $guruObj->user->id,
            'siswa_id' => $siswa->id,
            'pesan' => 'Selamat siang Pak, saya ingin menanyakan perkembangan belajar anak saya di kelas.',
        ]);
        PesanGuruOrtu::create([
            'pengirim_id' => $guruObj->user->id,
            'penerima_id' => $userOrtu->id,
            'siswa_id' => $siswa->id,
            'pesan' => 'Selamat siang Bu. Alhamdulillah perkembangan anak Ibu cukup baik, terutama di mata pelajaran Matematika.',
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('📧 Login credentials (password: password):');
        $this->command->table(
            ['Role', 'Email'],
            [
                ['Admin', 'admin@smartschool.id'],
                ['Guru', 'guru@smartschool.id'],
                ['Guru BK', 'bk@smartschool.id'],
                ['Siswa', 'siswa@smartschool.id'],
                ['Orang Tua', 'ortu@smartschool.id'],
            ]
        );
    }
}
