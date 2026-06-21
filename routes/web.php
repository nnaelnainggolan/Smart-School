<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// ============ HEALTH CHECK (untuk Railway) ============
Route::get('/up', fn() => response('OK', 200));
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;
use App\Http\Controllers\GuruBK;
use App\Http\Controllers\OrangTua;

// ============ LANDING PAGE ============
Route::get('/', [LandingController::class, 'index'])->name('landing');

// ============ AUTH ============
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ============ PROFILE (semua role) ============
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// ============ ADMIN ============
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('siswa', Admin\SiswaController::class);
    Route::resource('guru', Admin\GuruController::class);
    Route::resource('orangtua', Admin\OrangTuaController::class);
    Route::put('/orangtua/{orangtua}/reset-password', [Admin\OrangTuaController::class, 'resetPassword'])->name('orangtua.reset_password');
    Route::resource('kelas', Admin\KelasController::class)->parameters(['kelas' => 'kelas']);
    Route::resource('jadwal', Admin\JadwalController::class)->except(['show','edit','update']);
    Route::resource('mapel', Admin\MataPelajaranController::class);
    Route::resource('berita', Admin\BeritaController::class)->parameters(['berita' => 'berita']);
    Route::resource('kalender', Admin\KalenderController::class)->except(['show','edit','update']);

    Route::get('/laporan', [Admin\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/cetak-rapor', [Admin\LaporanController::class, 'cetakRapor'])->name('laporan.cetak_rapor');
});

// ============ GURU ============
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('/dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/absensi', [Guru\AbsensiController::class, 'index'])->name('absensi.index');
    Route::get('/absensi/pilih-kelas', [Guru\AbsensiController::class, 'pilihKelas'])->name('absensi.pilih');
    Route::get('/absensi/form/{jadwal}', [Guru\AbsensiController::class, 'form'])->name('absensi.form');
    Route::post('/absensi', [Guru\AbsensiController::class, 'store'])->name('absensi.store');

    Route::get('/nilai', [Guru\NilaiController::class, 'index'])->name('nilai.index');
    Route::get('/nilai/form', [Guru\NilaiController::class, 'form'])->name('nilai.form');
    Route::post('/nilai', [Guru\NilaiController::class, 'store'])->name('nilai.store');

    Route::resource('materi', Guru\MateriController::class)->except(['show','edit','update']);

    Route::match(['get','post'], '/pesan', [Guru\PesanController::class, 'index'])->name('pesan.index');
});

// ============ SISWA ============
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('/dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/nilai', [Siswa\NilaiController::class, 'index'])->name('nilai');
    Route::get('/absensi', [Siswa\AbsensiController::class, 'index'])->name('absensi');
    Route::get('/jadwal', [Siswa\JadwalController::class, 'index'])->name('jadwal');
    Route::get('/materi', [Siswa\MateriController::class, 'index'])->name('materi');

    Route::get('/konseling', [Siswa\KonselingController::class, 'index'])->name('konseling.index');
    Route::get('/konseling/buat', [Siswa\KonselingController::class, 'create'])->name('konseling.create');
    Route::post('/konseling', [Siswa\KonselingController::class, 'store'])->name('konseling.store');
    Route::get('/konseling/{konseling}', [Siswa\KonselingController::class, 'show'])->name('konseling.show');
    Route::post('/konseling/{konseling}/chat', [Siswa\KonselingController::class, 'sendChat'])->name('konseling.chat');
});

// ============ GURU BK ============
Route::prefix('guru-bk')->name('guru_bk.')->middleware(['auth', 'role:guru_bk'])->group(function () {
    Route::get('/dashboard', [GuruBK\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/konseling', [GuruBK\KonselingController::class, 'index'])->name('konseling.index');
    Route::get('/konseling/{konseling}', [GuruBK\KonselingController::class, 'show'])->name('konseling.show');
    Route::put('/konseling/{konseling}', [GuruBK\KonselingController::class, 'update'])->name('konseling.update');
    Route::post('/konseling/{konseling}/chat', [GuruBK\KonselingController::class, 'sendChat'])->name('konseling.chat');
    Route::get('/laporan', [GuruBK\KonselingController::class, 'laporan'])->name('laporan');
});

// ============ ORANG TUA ============
Route::prefix('orang-tua')->name('orang_tua.')->middleware(['auth', 'role:orang_tua'])->group(function () {
    Route::get('/dashboard', [OrangTua\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/nilai', [OrangTua\MonitorController::class, 'nilai'])->name('nilai');
    Route::get('/absensi', [OrangTua\MonitorController::class, 'absensi'])->name('absensi');
    Route::get('/konseling', [OrangTua\MonitorController::class, 'konseling'])->name('konseling');
    Route::match(['get','post'], '/pesan', [OrangTua\MonitorController::class, 'pesan'])->name('pesan');
});
