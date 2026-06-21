# 🎓 Smart School — Panduan Instalasi Lengkap

## Prasyarat
- PHP >= 8.2 dengan ekstensi `pdo_sqlite` aktif
- Composer
- Node.js & NPM
- Laragon (Windows) / XAMPP / PHP CLI

---

## Langkah Instalasi

```bash
cd SmartSchool
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Akses:
- **Landing Page (Publik)**: http://localhost:8000/
- **Login Sistem**: http://localhost:8000/login

---

## Akun Login Demo (Password: `password`)

| Role        | Email                     |
|-------------|---------------------------|
| Admin       | admin@smartschool.id      |
| Guru        | guru@smartschool.id       |
| Guru BK     | bk@smartschool.id         |
| Siswa       | siswa@smartschool.id      |
| Orang Tua   | ortu@smartschool.id       |

---

## 🌐 Landing Page (Publik — Tanpa Login)
- Hero section dengan info PPDB
- Profil & tentang sekolah
- Program studi / jurusan
- Fasilitas sekolah
- Form pendaftaran minat PPDB online
- Berita & pengumuman terbaru (dinamis dari database)
- Testimoni
- Lokasi sekolah (Google Maps) + kontak + jam operasional
- Link sosial media

## 📋 Fitur Lengkap per Role

### 👨‍💼 Admin
- Dashboard statistik real-time
- CRUD Siswa (otomatis membuat akun Orang Tua dalam 1 form)
- CRUD Guru & Guru BK
- CRUD Kelas, Mata Pelajaran, Jadwal Pelajaran
- CRUD Berita & Pengumuman (dengan upload gambar)
- **Kalender Akademik** (agenda akademik/ujian/libur/acara)
- **Laporan Sistem**: statistik nilai per kelas, rekap absensi, status konseling, siswa perlu perhatian
- **Cetak Rapor PDF** per kelas (siap print)
- Edit profil & ganti password

### 👨‍🏫 Guru
- Dashboard jadwal mengajar hari ini
- Input absensi (pilih kelas/jadwal, tombol "Semua Hadir")
- Riwayat absensi yang pernah diinput
- Input nilai harian/UTS/UAS (nilai akhir & predikat otomatis)
- Upload & kelola materi pelajaran
- **Pesan ke Orang Tua** (komunikasi dua arah)
- Edit profil & ganti password

### 🎓 Siswa
- Dashboard ringkasan akademik
- **Jadwal Pelajaran mingguan** (per hari)
- Nilai & Rapor digital + grafik
- Rekap absensi bulanan + grafik
- **Akses Materi Pelajaran** (filter per mapel, unduh file)
- Ajukan & chat konseling dengan Guru BK
- Edit profil & ganti password

### 🧑‍💻 Guru BK
- Dashboard statistik konseling
- Kelola semua permintaan konseling (approve/tolak/proses)
- Chat real-time dengan siswa
- Catatan & status psikologis siswa
- Laporan konseling (grafik per jenis & per bulan)
- Edit profil & ganti password

### 👨‍👩‍👦 Orang Tua
- Dashboard monitoring anak + alert otomatis (alpha > 3)
- Grafik nilai & rapor anak
- Rekap absensi anak
- Laporan konseling anak
- **Pesan dua arah dengan Guru** (kirim & terima)
- Edit profil & ganti password

---

## Teknologi
- Laravel 13, PHP 8.2+, SQLite
- Tailwind CSS, Alpine.js, Chart.js, Font Awesome 6
- Auth: Laravel Session + Custom RoleMiddleware (RBAC)

## Troubleshooting
```bash
# Error "could not find driver"
# Aktifkan ekstensi pdo_sqlite di php.ini, restart server

# Error key/cache
php artisan config:clear
php artisan key:generate
php artisan migrate:fresh --seed
```
