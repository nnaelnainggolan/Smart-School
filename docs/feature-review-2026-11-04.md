# Pemeriksaan fitur SmartSchool — 4 November 2026

SmartSchool sudah mempunyai fondasi administrasi akademik dan pendampingan siswa yang cukup luas. Untuk menjadi sistem SMA/SMK yang lebih lengkap, prioritasnya adalah memperbaiki alur yang bermasalah, membuat kurikulum dan penilaian lebih fleksibel, lalu menambah pembelajaran digital dan layanan khas sekolah tujuan.

## Cakupan dan bukti pemeriksaan

- Ditinjau: route, controller, model, layanan akses, formulir utama, migrasi, konfigurasi sekolah, PWA, dan dokumentasi.
- Suite yang sudah ada: **34 tes lulus, 189 assertions**, melalui `php artisan test --compact`.
- Pemeriksaan tambahan menggunakan database **SQLite in-memory** dan data seeder: **71 endpoint GET**, terdiri dari **65 respons HTTP 200** dan **6 kegagalan method controller yang belum tersedia**. Route dipanggil dengan peran dan parameter data uji yang sesuai. Untuk layanan bersama, pemeriksaan ini memakai salah satu peran yang diizinkan; suite sebelumnya juga menguji halaman bersama lintas peran.
- Dua skenario tambahan mereproduksi masalah update NIP guru dan opsi unggah materi “Semua Kelas”. Pengujian reproduksi berhasil menemukan perilaku bermasalah; ini bukan berarti kedua fitur tersebut bebas bug.
- Empat endpoint berparameter tidak masuk pemindaian GET tambahan: unduhan materi, dokumen pendaftar, lampiran izin, dan rapor terbit. Akses materi dan publikasi rapor tercakup sebagian dalam suite yang sudah ada.
- Pemeriksaan ini tidak menjalankan seluruh kombinasi CRUD melalui browser, uji beban, pengujian perangkat fisik, pemulihan backup, ataupun validasi seluruh mesin database. Kelulusan tes bukan jaminan kesiapan produksi.
- Tidak ada perubahan kode aplikasi atau data sekolah dalam audit ini. Skrip audit sementara dihapus setelah hasil dicatat.

## Inventaris fitur yang sudah ada

| Area | Implementasi yang ditemukan | Batas yang perlu diperhatikan |
|---|---|---|
| Autentikasi | Login/logout, pembatasan percobaan login, lima peran, akun aktif/nonaktif, profil dan ganti password | Belum ada alur lupa password mandiri/2FA. Kewajiban mengganti password sementara sudah ada pada impor/konversi pendaftar, tetapi belum konsisten pada akun yang dibuat manual dan reset orang tua |
| Dashboard | Dashboard admin, guru, BK, siswa, orang tua; statistik dan grafik sesuai akses | Belum ada peran kepala sekolah, tata usaha, bendahara, kaprodi, alumni, atau pembimbing industri |
| Data induk | Siswa, guru/BK, orang tua, kelas, mapel; pencarian, kapasitas kelas dan relasi beberapa anak | Enam route detail belum diimplementasikan; validasi update guru perlu diperbaiki |
| Periode akademik | Tahun/semester aktif, penetapan wali kelas, riwayat kelas, kenaikan kelas, kelulusan | Tingkat hanya X–XII; kelulusan massal khusus XII; kenaikan kelas dilakukan massal sehingga keputusan individual perlu pengembangan |
| Impor | Template CSV, pratinjau, validasi ulang, transaksi, pencegahan proses ganda, kredensial sementara | Belum XLSX langsung; format impor masih terbatas pada kolom yang disediakan |
| Jadwal | Penugasan guru–kelas–mapel–periode, pemeriksaan bentrok guru/kelas/ruang | Belum edit jadwal, kelompok mapel pilihan lintas kelas, jadwal blok praktik, dan kalender pengecualian terpadu |
| Absensi | Absensi per jadwal, status hadir/izin/sakit/alpha, simpan ulang, rekap, notifikasi internal | Belum absensi gerbang, keterlambatan, pulang cepat, atau rekap harian yang terpisah dari hitungan per pelajaran |
| Nilai | Harian, UTS, UAS, nilai akhir/predikat, nilai nol yang valid, filter periode | Bobot 40%/30%/30% tetap; belum asesmen/rubrik fleksibel, pemetaan tujuan pembelajaran, atau riwayat remedial |
| Rapor | Pemeriksaan kelengkapan, snapshot publikasi, penguncian nilai, buka revisi dengan alasan, akses keluarga sendiri | Cetak melalui browser; belum pengesahan berjenjang, TTE, QR verifikasi, deskripsi capaian dan komponen kokurikuler/ekstrakurikuler yang lengkap |
| Materi belajar | Unggah, daftar, unduh privat, akses berdasarkan kelas/guru | Opsi “Semua Kelas” tidak selaras dengan validasi server; daftar pilihan belum dibatasi pada penugasan guru; belum tugas/pengumpulan/kuis |
| BK | Pengajuan konseling, penugasan BK, transisi status, percakapan, catatan internal dan ringkasan khusus orang tua | Percakapan memakai submit dan render ulang; belum pembaruan langsung otomatis atau pengingat sesi. Label status bukan alat diagnosis |
| Wali kelas | Indikator alpha/penurunan nilai, catatan tindak lanjut, tenggat, penutupan | Log audit tersedia, tetapi belum ada layar audit khusus; indikator kehadiran berdasarkan rekaman pelajaran |
| Orang tua | Pilih anak, nilai/absensi/konseling, pesan dengan guru terkait, pengajuan izin/sakit berlampiran privat | Notifikasi hanya di dalam aplikasi; belum pengiriman WhatsApp/email/push dan status pengiriman |
| Penerimaan siswa | Form pendaftar, dokumen privat, nomor referensi, pelacakan, verifikasi/terima/tolak, konversi sekali menjadi siswa | Belum manajemen periode/jalur/kuota/skoring/daftar ulang lengkap; terminologi dan konten publik masih PPDB |
| Informasi publik | Landing page, slot gambar, berita dinamis/detail berita, fasilitas, kontak, kalender | Identitas sekolah dan tanggal penerimaan masih bercampur/hardcoded; konten sebagian demonstrasi |
| PWA/operasional | Manifest, instalasi bila didukung, halaman offline, scheduler pembersihan impor, audit beberapa tindakan | PWA belum menyimpan pekerjaan offline atau push; belum ditemukan fitur backup/restore terjadwal; UI masih menggunakan beberapa CDN |

## Masalah yang terverifikasi

### 1. Update guru bisa gagal dengan perubahan yang sudah tersimpan — prioritas tinggi

Di `app/Http/Controllers/Admin/GuruController.php:71`, hanya nama yang divalidasi. Data akun disimpan sebelum data guru, tanpa transaksi yang membungkus keduanya.

Reproduksi pada data uji: ubah nama guru dan isi NIP milik guru lain. Respons menjadi HTTP 500 karena constraint NIP unik, tetapi nama akun sudah berubah. Perbaikan yang disarankan: validasi seluruh input, keunikan NIP dengan pengecualian record sendiri, serta transaksi untuk update akun dan profil guru. Periksa juga transaksi pada pembuatan akun guru.

### 2. Enam route detail admin menuju method yang tidak ada — prioritas sedang

`routes/web.php:35` dan resource terkait mendaftarkan `show`, sedangkan controller tidak mempunyai method tersebut:

- `admin.siswa.show`
- `admin.guru.show`
- `admin.orangtua.show`
- `admin.kelas.show`
- `admin.mapel.show`
- `admin.berita.show`

Semua direproduksi sebagai error “Call to undefined method …::show()”. Ini tidak berarti halaman daftar/edit ikut gagal. Implementasikan halaman detail bila diperlukan, atau keluarkan `show` dari resource.

### 3. Opsi “Semua Kelas” pada unggah materi tidak dapat disimpan — prioritas sedang

Form di `resources/views/guru/materi/create.blade.php:41` menawarkan nilai kelas kosong, tetapi `app/Http/Controllers/Guru/MateriController.php:38` mewajibkan kelas. Pilihan tersebut menghasilkan error validasi, sudah direproduksi.

Batasi pilihan pada kelas/mapel/periode yang diajar guru. Jika materi lintas kelas memang diperlukan, buat pilihan beberapa kelas dengan pemeriksaan hak akses untuk masing-masing kelas.

### 4. Konten publik belum konsisten — prioritas sedang

Landing page mencampur “SMA Smart School”, “SMK Smart School”, dan “SMK HASANUDDIN”. Bagian penerimaan masih menyatakan tahun 2025/2026 dan jadwal tahun 2025; gambar PPDB memuat tahun berbeda. Login masih menampilkan petunjuk password demo. Temuan ini tidak membuktikan bahwa semua akun aktif masih memakai password demo.

Sediakan pengaturan profil sekolah, jenis SMA/SMK, identitas, logo, kontak, tahun penerimaan, dan status buka/tutup pendaftaran. Kelola konten demo sebagai konfigurasi lingkungan/demo, bukan teks permanen.

## Batas desain yang paling relevan untuk SMA/SMK

Model akademik saat ini berpusat pada kelas dan mapel yang dijadwalkan bagi kelas tersebut. Belum ada kelompok belajar berdasarkan pilihan individual siswa. Nilai memakai tiga komponen tetap. Untuk pengembangan SMA, pemilihan mapel dan kelompok belajar perlu menjadi data tersendiri; panduan PDSS 2026 juga mengakomodasi siswa dengan set mapel pilihan berbeda. [Panduan PDSS 2026](https://files.snpmb.id/web2026/Panduan%20Pengisian%20PDSS%202026%20Manual.pdf).

Untuk SMK, jurusan masih berupa teks pada kelas, belum master bidang/program/konsentrasi keahlian. Aplikasi membatasi kelas X–XII dan kelulusan XII. Jika digunakan sekolah dengan program empat tahun, dukungan XIII perlu ditambahkan; struktur kurikulum resmi juga memuat kelas XIII SMK/MAK Terapan. [Struktur kurikulum resmi](https://siken.kemendikdasmen.go.id/struktur-kurikulum?tab=ekstrakurikuler).

Nilai dan rapor yang ada sebaiknya dikembangkan, bukan dibuat ulang. Tambahkan jenis asesmen, rubrik, bobot per mapel/periode, deskripsi capaian, tindak lanjut remedial, dan publikasi yang tetap mengunci versi nilai. Format final harus disepakati dengan sekolah dan kebutuhan pelaporannya.

## Rekomendasi pengembangan

Rekomendasi berikut adalah penilaian kebutuhan produk berdasarkan kode dan sumber yang diperiksa, bukan daftar kewajiban hukum bagi seluruh sekolah.

| Prioritas | Fitur | Sasaran dan versi awal yang disarankan |
|---|---|---|
| P1 | Pengaturan sekolah dan kurikulum | SMA/SMK: profil sekolah, periode, jenis/program pendidikan, kelompok belajar/mapel pilihan, konfigurasi asesmen dan rapor |
| P1 | Tugas dan pengumpulan digital | SMA/SMK: tugas individu/kelompok, tenggat, unggah jawaban, rubrik, umpan balik, revisi, pengingat. Kembangkan dari modul materi |
| P1 untuk SMK | PKL end-to-end | Mitra industri, kuota/penempatan, persetujuan, pembimbing sekolah/industri, jurnal harian, kehadiran, monitoring, penilaian dan laporan |
| P1 untuk SMK | UKK dan portofolio kompetensi | Paket/skema kompetensi, penguji, jadwal/tempat uji, rubrik, bukti karya, hasil dan arsip sertifikat. Penerbitan sertifikat mengikuti pihak berwenang |
| P1 untuk SMA | Bimbingan studi lanjut | Pilihan mapel, minat/prodi, portofolio prestasi, agenda TKA/SNPMB, kelengkapan rapor/NISN, rekap siap diperiksa operator |
| P2 | SPMB lengkap | Periode/jalur/kuota dan aturan sesuai sekolah/daerah, berkas wajib, antrean verifikasi, seleksi, pengumuman dan daftar ulang |
| P2 | BKK dan penelusuran alumni | Terutama SMK: lowongan terverifikasi, lamaran, status bekerja/kuliah/wirausaha, waktu tunggu kerja, kesesuaian bidang dan umpan balik industri |
| P2 | Presensi harian dan notifikasi keluarga | Rekap hari terpisah dari pelajaran, terlambat/pulang cepat, verifikasi guru, izin terpadu; WhatsApp/email/push sesuai persetujuan dan preferensi penerima |
| P2 | Kepala sekolah dan staf operasional | Dashboard pimpinan; akses khusus TU, bendahara, kaprodi, pembimbing industri. Pembatasan per tugas dan siswa yang ditangani |
| P2 | Audit dan pemulihan data | Layar riwayat perubahan, ekspor, backup terjadwal dan uji restore, reset akun, perubahan password sementara yang konsisten |
| P2 sesuai kebutuhan | Keuangan sekolah | Tagihan yang memang berlaku, beasiswa/keringanan, bukti bayar, rekonsiliasi, kuitansi, laporan bendahara; pembayaran daring dapat menyusul |
| P2 sesuai kebutuhan | Laboratorium/bengkel/perpustakaan | Inventaris, peminjaman, booking ruang/alat, bahan habis pakai, kerusakan, perawatan dan daftar pemeriksaan K3 |
| P2 | Ekstrakurikuler, kokurikuler dan prestasi | Pendaftaran, pembina, kehadiran, portofolio kegiatan dan rekap untuk rapor; melengkapi data akademik yang sudah ada |
| P2 | Pengaduan dan tindak lanjut siswa | Kanal terbatas bagi petugas yang ditunjuk, status penanganan dan eskalasi; bedakan dari catatan konseling rutin dan batasi akses informasi |
| P3 | Teaching factory dan proyek usaha | Khusus kebutuhan SMK: proyek/pesanan, tim siswa, bahan, biaya, kualitas produk, pelanggan dan penilaian kompetensi |
| P3 | Bantuan AI untuk guru | Draf soal/rubrik atau ringkasan materi yang harus ditinjau guru; mulai dari materi pembelajaran, tidak dari catatan konseling atau keputusan otomatis atas siswa |

**Alasan prioritas khusus SMK:** PKL merupakan pembelajaran di dunia kerja yang dijelaskan dalam panduan resmi. UKK mempunyai alur asesmen kompetensi, penguji, pelaporan dan sertifikasi tersendiri. Karena keduanya belum ada, manfaat modul tersebut lebih langsung untuk SMK dibanding menambah fitur dekoratif dashboard. [Panduan PKL](https://smk.kemendikdasmen.go.id/berita/panduan-praktek-kerja-lapangan-edisi-revisi-tahun-2024), [Pedoman UKK 2026](https://smk.kemendikdasmen.go.id/storage/ukk/1767839725_Pedoman%20UKK%20Fix.pdf).

**Alasan penelusuran alumni:** publikasi Kemendikdasmen tahun 2026 menyebut indikator bekerja, wirausaha, melanjutkan pendidikan, waktu tunggu kerja dan kesesuaian pekerjaan dengan kompetensi. Modul lokal sebaiknya membantu pengumpulan/verifikasi data tersebut dan ekspor yang dapat diperiksa operator. [Penelusuran Tamatan SMK 2026](https://vokasi.kemendikdasmen.go.id/read/b/bangun-budaya-mutu-berkelanjutan-kemendikdasmen-sempurnakan-penelusuran-tamatan-smk-2026).

**Alasan studi lanjut:** dukungan pemilihan mapel dan konseling karier dapat membantu siswa menyiapkan rapor serta pilihan studi. Aturan dan jadwal TKA/SNPMB harus dapat diperbarui per tahun; aplikasi tidak boleh mengasumsikan aturan seleksi selalu tetap. [FAQ resmi SNPMB](https://www.snpmb.id/faq), [FAQ TKA](https://pusatinformasi.ult.kemendikdasmen.go.id/hc/id/articles/52001769236249-FAQ-Tes-Kemampuan-Akademik-TKA).

**Alasan peningkatan penerimaan:** tahun ajaran 2026/2027 sudah mempunyai pedoman pelaksanaan penerimaan murid SMK dalam kerangka SPMB. Aturan SMA dan SMK maupun petunjuk daerah tidak sebaiknya disamakan atau ditulis permanen dalam kode. [Surat edaran penerimaan murid SMK 2026/2027](https://smk.kemendikdasmen.go.id/storage/publikasi/1773633637_SE_01Tahun%202026%20Penerimaan%20Murid%20Baru%20SMK%20Tahun%202026_2027.pdf).

**Koding dan kecerdasan artifisial:** dukung sebagai konten/mapel pilihan sesuai program sekolah, termasuk proyek dan literasi penggunaan teknologi. Tidak perlu langsung membangun chatbot sekolah untuk mendukung pembelajaran ini. [Informasi resmi Koding dan KA](https://kurikulum.kemendikdasmen.go.id/koding-ka).

## Urutan implementasi yang disarankan

1. **Perbaikan dasar:** validasi/transaksi guru, route detail, formulir materi, identitas sekolah dan tanggal penerimaan. Tambahkan tes regresi untuk temuan audit.
2. **Fondasi bersama:** pengaturan sekolah/kurikulum, penilaian fleksibel, peran staf dan audit, lalu tugas/pengumpulan yang terhubung dengan materi.
3. **Jika fokus SMK:** mulai PKL, lanjut UKK/portofolio, kemudian BKK/alumni. Jika fokus SMA: mulai mapel pilihan dan bimbingan studi lanjut.
4. **Layanan operasional:** notifikasi keluarga, SPMB lanjutan, sarana, keuangan sesuai kebutuhan nyata sekolah.
5. **Integrasi dan otomasi:** ekspor yang tervalidasi lebih dahulu; sinkronisasi Dapodik/e-Rapor/PDSS atau layanan eksternal hanya setelah mekanisme resmi, izin akses, dan format datanya dipastikan. Integrasi tersebut belum ditemukan dalam aplikasi saat ini.

Untuk SMK Hasanuddin, pilihan awal yang paling kuat setelah perbaikan dasar adalah **PKL, UKK/portofolio, serta BKK/alumni**, dengan modul kurikulum dan asesmen sebagai fondasi bersama. Apabila proyek memang akan melayani SMA sekaligus SMK, gunakan pengaturan jenis sekolah dan modul yang bisa diaktifkan sesuai kebutuhan, agar menu pengguna tetap ringkas.
