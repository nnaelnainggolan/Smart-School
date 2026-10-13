# Dashboard SMK Hasanuddin

Pembaruan ini menerapkan tema forest–sage–ivory pada kelima dashboard, identitas SMK Hasanuddin pada layout bersama, kartu statistik konsisten, grafik responsif, serta rincian data yang dapat dibuka tanpa JavaScript. Tidak mengubah landing page atau menambahkan chatbot.

## Grafik per role

- Admin: tren kehadiran 7 hari dan distribusi siswa aktif per kelas; rekap hari ini tetap berupa angka.
- Guru: tren kehadiran dari rekaman yang diinput guru tersebut dan kelengkapan rekaman nilai periode aktif. Kelengkapan bukan persentase seluruh siswa: siswa tanpa rekaman nilai belum masuk perhitungan.
- Guru BK: pengajuan 6 bulan dan status sesi yang ditugaskan kepada guru tersebut. Antrian menunggu hanya menghitung yang belum ditugaskan atau ditugaskan kepadanya.
- Siswa: tren kehadiran sendiri dan nilai akhir periode aktif beserta KKM.
- Orang tua: tren kehadiran dan nilai periode aktif anak yang dipilih, mengikuti pemilihan anak yang sudah ada.

Kehadiran adalah persentase rekaman Hadir terhadap semua rekaman absensi pada tanggal itu, bukan jumlah hari atau siswa unik. Hari tanpa rekaman ditampilkan sebagai celah, bukan nol. Nilai kosong tidak diperlakukan sebagai nol; nilai nol tetap sah. Data nilai pada dashboard adalah nilai berjalan, bukan snapshot rapor resmi.

## Tata letak

Dua grafik per dashboard, ukuran kartu konsisten dan nama panjang dapat membungkus. Pada mobile, grafik satu kolom; data kelas/mapel yang banyak memakai area gulir terbatas. Ringkasan angka, judul, dan tabel rincian mendampingi grafik sehingga warna bukan satu-satunya penanda. SVG dan CSS lokal tidak memerlukan Chart.js untuk grafik baru.

## Pemasangan

Branch ini berbasis `codex/school-system-upgrade` (PR #2). Pasang migrasi pembaruan sistem terlebih dahulu jika belum pernah dilakukan, mengikuti `docs/system-upgrade.md`. Pembaruan dashboard ini tidak menambah migrasi atau dependensi.

Setelah mengambil branch: jalankan `php artisan view:clear`, `php artisan test`, lalu muat ulang browser dengan Ctrl+F5. Atur periode aktif melalui Layanan Sekolah supaya grafik nilai mengikuti tahun/semester yang benar.

## Verifikasi

34 tes / 189 assertions lulus pada PHP 8.5 dan SQLite in-memory. Termasuk periode nilai, nol vs null, absensi tanpa rekaman, cakupan guru/BK, dan pemilihan anak. Pemeriksaan browser pada lima role × lebar 320/390/768/1366 px: tanpa overflow horizontal atau error JavaScript; tabel rincian dan menu mobile berfungsi. Fixture menggunakan respons Blade asli dan data seeder; aset CDN disediakan lokal saat pemeriksaan. Perangkat Android fisik dan database pengguna belum diuji.
