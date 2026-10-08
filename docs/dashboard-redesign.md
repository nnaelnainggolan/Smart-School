# Tampilan dashboard Smart School

Layout bersama menggunakan warna forest, sage, krem, dan aksen emas. Dashboard Admin menampilkan ringkasan sekolah, kehadiran, berita, serta akses cepat. Dashboard Guru, Guru BK, Siswa, dan Orang Tua memakai pola warna dan kartu yang sama.

## File untuk penyesuaian

- `resources/views/layouts/app.blade.php`: sidebar, header, notifikasi, menu akun.
- `public/css/dashboard.css`: warna, ukuran, jarak, kartu, dan aturan responsif. Variabel warna berada di awal file.
- `public/js/dashboard.js`: buka/tutup sidebar dan navigasi keyboard.
- `resources/views/admin/dashboard.blade.php`: susunan dashboard Admin.
- `resources/views/{guru,guru_bk,siswa,orang_tua}/dashboard.blade.php`: konten dashboard masing-masing role.

Sidebar tetap terlihat pada desktop dan menjadi panel yang dapat dibuka pada layar kecil. Konten panjang tetap dapat digulir agar data tidak terpotong.

Setelah mengambil perubahan, jalankan `php artisan view:clear`, lalu muat ulang browser. Perubahan ini tidak memerlukan migrasi database atau pembaruan dependensi.

## Pemeriksaan

- Kelima dashboard dirender melalui Laravel menggunakan database SQLite sementara dan data seeder.
- Browser Chromium: lebar 320, 390, 768, 1024, dan 1366 piksel; tidak ada overflow horizontal atau error JavaScript.
- Sidebar desktop/mobile, overlay, Escape, fokus keyboard, perubahan ukuran layar, notifikasi, dan menu akun diperiksa.
- Kondisi absensi/berita kosong, orang tua tanpa siswa, serta akses Siswa ke dashboard Admin (403) diperiksa melalui HTTP kernel Laravel.
- `tests/Feature/DashboardViewsTest.php` menyediakan tes regresi: jalankan `php artisan test --filter=DashboardViewsTest` di lingkungan PHP lokal. Runner PHPUnit tidak dapat berjalan pada runtime PHP WebAssembly pengujian ini; pemeriksaan HTTP di atas dijalankan langsung melalui Laravel.
