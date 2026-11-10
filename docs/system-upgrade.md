# Pembaruan sistem sekolah — versi pertama

Implementasi mengikuti lima tahap: integritas data dan akses, administrasi akademik/impor, alur orang tua dan mobile, wali kelas/rapor, lalu PPDB/PWA.

## Sebelum memasang

Cadangkan database serta seluruh `storage/app`, simpan `.env` dan APP_KEY yang ada. Uji pada salinan database terlebih dahulu. PHP minimal 8.4 sesuai composer.json. Jangan menjalankan `migrate:fresh`, `db:wipe`, atau seeder pada data sekolah. Jangan mengganti APP_KEY: payload impor dienkripsi dengan kunci tersebut.

Setelah mengambil branch atau menggabungkan PR:

```powershell
composer install
php artisan optimize:clear
php artisan migrate
php artisan test
php artisan school:privatize-materials
```

Perintah terakhir hanya menampilkan materi lama yang masih publik. Sesudah backup dan peninjauan, jalankan:

```powershell
php artisan school:privatize-materials --apply
```

Perintah menyalin ke disk privat, membandingkan SHA-256, baru menghapus salinan publik. Jika isi tujuan berbeda, proses berhenti; periksa kedua file sebelum melanjutkan. File materi lama tetap dapat diakses langsung melalui URL publik sampai pemindahan dilakukan. Unggahan materi baru langsung privat.

Migrasi pertama menolak duplikasi kunci absensi/nilai dengan pesan kesalahan, tanpa menghapus data. Jika berhenti, periksa dan gabungkan duplikasi secara manual dengan backup terlebih dahulu lalu jalankan migrate kembali. Migrasi kedua membuat tabel baru dan mengisi riwayat kelas dari kelas siswa saat ini untuk tahun jadwal terbaru saja. Riwayat tahun lebih lama tidak dapat direkonstruksi otomatis; validasi sebelum menerbitkan rapor lama.

## Pengaturan pertama

1. Admin → **Layanan Sekolah → Periode & Kelas**: pilih tahun/semester aktif, tetapkan guru wali kelas. Nama wali kelas berupa teks di data lama belum otomatis menjadi hak akses guru.
2. Pastikan jadwal guru, kelas, mata pelajaran, tahun dan semester benar. Hak input absensi/nilai mengikuti penugasan tersebut.
3. Periksa kapasitas kelas. Untuk pergantian tahun: luluskan kelas XII, naikkan XI ke XII, lalu X ke XI agar kelas tujuan tidak penuh. Pilih tahun tujuan lebih baru. Setelah seluruh pemindahan selesai, aktifkan periode baru, susun jadwal, kemudian masukkan siswa baru. Kerjakan dalam masa administrasi terkoordinasi agar impor tidak masuk periode yang salah.
4. Lengkapi riwayat kelas jika data lama tidak sesuai. Perubahan nama/profil tidak mengubah riwayat kelas; perubahan kelas setelah promosi menunggu aktivasi tahun terbaru.

## Impor siswa dan orang tua

Unduh template dari **Impor Siswa & Orang Tua**, isi dan ekspor sebagai **CSV UTF-8** (bukan XLSX). Maksimal 1.000 baris dan 2 MB. Pemisah koma atau titik koma dideteksi. Simpan NIS sebagai teks agar nol awal tidak hilang.

Urutan kolom wajib:

`nis,nama_siswa,email_siswa,jenis_kelamin,kelas,tahun_masuk,nama_ortu,email_ortu,no_hp_ortu`

- Jenis kelamin `L` / `P`; kelas harus persis cocok dan tidak ambigu; tahun masuk empat digit.
- Email siswa dan NIS unik. Untuk saudara kandung, ulangi email, nama dan nomor orang tua yang sama. Akun orang tua yang ada digunakan kembali setelah identitas cocok; tidak ditimpa.
- Pratinjau tidak membuat akun. Konfirmasi memeriksa kembali data dan kapasitas dalam transaksi. Pratinjau berlaku 30 menit dan sekali pakai.
- Setelah berhasil, unduh CSV kredensial sekali tampil dan simpan secara aman. Hanya akun baru mendapat password sementara acak; login mewajibkan penggantian password. Akun orang tua yang sudah ada tetap memakai passwordnya.
- Sel CSV yang berpotensi menjadi formula diberi awalan apostrof. Jika email tampak berawalan apostrof saat dibuka sebagai teks, apostrof tersebut adalah pelindung CSV, bukan bagian alamat login.
- Jalankan Laravel scheduler (`php artisan schedule:run` setiap menit pada server) agar payload kedaluwarsa dibersihkan harian. Pembersihan juga berjalan saat pratinjau baru dibuat; tersedia `php artisan school:prune-imports`.

## Absensi, komunikasi dan pendampingan

Guru hanya mengisi jadwal miliknya dan siswa pada daftar tahun/kelas tersebut. Pemilihan tanggal memuat ulang data hari itu dengan peringatan perubahan belum disimpan. Penyimpanan berulang memperbarui rekaman yang sama. Nilai nol sah; nilai di luar 0–100 ditolak. Data yang memiliki riwayat tidak dapat dihapus sembarangan.

Orang tua dapat memilih anak pada halaman pemantauan. Pengajuan izin/sakit mendukung lampiran privat; admin atau wali kelas memeriksa sekali dengan catatan. Persetujuan menjadi saran status pada formulir absensi, tidak menimpa absensi yang sudah tercatat. Pesan guru–orang tua dibatasi hubungan pengajaran/wali kelas. Notifikasi tersedia di dalam aplikasi, bukan WhatsApp/email/push.

Wali kelas melihat siswa yang menjadi tanggung jawabnya, rekap Alpha 30 hari, perubahan rata-rata semester, serta tindak lanjut. Ambang di `config/school.php`. Perbandingan nilai perlu ditafsirkan bersama komposisi mata pelajaran. Indikator bukan diagnosis. Catatan internal BK tetap privat; ringkasan untuk orang tua diisi terpisah oleh BK.

## Rapor

Menu rapor resmi menerbitkan snapshot hanya ketika seluruh mata pelajaran terjadwal mempunyai nilai lengkap. Siswa/orang tua hanya melihat publikasi miliknya. Publikasi mengunci perubahan nilai periode tersebut; admin dapat membuka kembali dengan alasan, lalu menerbitkan revisi. Riwayat tindakan/snapshot disimpan dalam `school_audits`; belum ada antarmuka audit khusus. Cetak melalui browser → Simpan PDF. Halaman cetak laporan lama berlabel pratinjau, bukan publikasi resmi.

## PPDB dan PWA

PPDB menyimpan pendaftaran, bukti privat, nomor referensi dan status. Pelacakan memerlukan referensi serta email. Admin memverifikasi dan menerima/menolak; konversi pendaftar diterima menjadi siswa meminta kelas, NIS dan email siswa, lalu menghasilkan akun sekali saja.

PWA mempunyai manifest, ikon, tombol instalasi jika browser mendukung, dan halaman offline. Gunakan HTTPS untuk pemasangan Android; `http://192.168.x.x` dapat dibuka untuk pengujian biasa tetapi tidak mengaktifkan service worker. Hanya halaman offline publik disimpan service worker: data siswa, rapor dan formulir tetap membutuhkan koneksi. Tidak ada absensi offline atau push notification.

## Verifikasi dan batas pengujian

- 30 tes fitur, 162 assertions: hak akses, nilai nol, absensi berulang, impor/transaksi, hubungan anak, izin, publikasi/revisi, promosi, BK, PPDB, materi privat, dan dashboard.
- 48 pemeriksaan browser: 12 halaman pada lebar 320, 390, 768 dan 1366 px; tidak ada overflow horizontal dokumen atau error JavaScript. Pemeriksaan memakai HTML yang dirender Laravel dengan data uji dan aset CDN diganti salinan lokal.
- Pengujian lokal menggunakan SQLite in-memory dan PHP 8.5; workflow GitHub disiapkan untuk PHP 8.4. Database MySQL sekolah dan perangkat Android fisik belum diuji.
- Uji dengan salinan database MySQL sebelum produksi, terutama migrasi/indeks unik dan operasi serentak. Jadwalkan backup, HTTPS, `APP_DEBUG=false`, dan scheduler pada lingkungan produksi.
- Versi ini belum mencakup impor XLSX langsung, pemulihan password via email, integrasi WhatsApp, backup otomatis, pembayaran, tanda tangan digital rapor atau aplikasi Android native.
