<?php

use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\HomeroomController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SchoolController;
use App\Models\Berita;
use Illuminate\Support\Facades\Route;

Route::get('/ppdb', [AdmissionController::class, 'form'])->name('ppdb.form');
Route::post('/ppdb', [AdmissionController::class, 'store'])->middleware('throttle:5,10')->name('ppdb.store');
Route::get('/ppdb/status', [AdmissionController::class, 'statusForm'])->name('ppdb.status');
Route::post('/ppdb/status', [AdmissionController::class, 'status'])->middleware('throttle:10,1')->name('ppdb.check');
Route::get('/berita/{berita:slug}', function (Berita $berita) {
    abort_unless($berita->is_published, 404);

    return view('school.news', compact('berita'));
})->name('berita.show');
Route::middleware(['auth', 'role:admin,guru,guru_bk,siswa,orang_tua'])->prefix('school')->name('school.')->group(function () {
    Route::get('/', [SchoolController::class, 'hub'])->name('hub');
    Route::put('/notifikasi/{notification}', [SchoolController::class, 'read'])->name('notifications.read');
    Route::get('/materi/{materi}', [SchoolController::class, 'material'])->name('material');
    Route::middleware('role:admin')->group(function () {
        Route::get('/import', [ImportController::class, 'index'])->name('import');
        Route::get('/import/template', [ImportController::class, 'template'])->name('import.template');
        Route::post('/import/preview', [ImportController::class, 'preview'])->name('import.preview');
        Route::post('/import/{batch}/commit', [ImportController::class, 'commit'])->name('import.commit');
        Route::get('/academic', [AcademicController::class, 'index'])->name('academic');
        Route::post('/academic/period', [AcademicController::class, 'period'])->name('academic.period');
        Route::post('/academic/teacher', [AcademicController::class, 'teacher'])->name('academic.teacher');
        Route::post('/academic/graduate', [AcademicController::class, 'graduate'])->name('academic.graduate');
        Route::post('/academic/promote', [AcademicController::class, 'promote'])->name('academic.promote');
        Route::get('/admissions', [AdmissionController::class, 'index'])->name('admissions');
        Route::put('/admissions/{admission}', [AdmissionController::class, 'review'])->name('admissions.review');
        Route::get('/admissions/{admission}/document', [AdmissionController::class, 'document'])->name('admissions.document');
        Route::post('/admissions/{admission}/convert', [AdmissionController::class, 'convert'])->name('admissions.convert');
    });
    Route::middleware('role:admin,guru,orang_tua')->group(function () {
        Route::get('/leave', [LeaveController::class, 'index'])->name('leave');
        Route::post('/leave', [LeaveController::class, 'store'])->name('leave.store');
        Route::put('/leave/{leave}', [LeaveController::class, 'review'])->name('leave.review');
        Route::get('/leave/{leave}/attachment', [LeaveController::class, 'attachment'])->name('leave.attachment');
    });
    Route::middleware('role:admin,guru,guru_bk')->group(function () {
        Route::get('/homeroom', [HomeroomController::class, 'index'])->name('homeroom');
        Route::post('/followup', [HomeroomController::class, 'store'])->name('followup.store');
        Route::put('/followup/{follow}', [HomeroomController::class, 'close'])->name('followup.close');
    });
    Route::middleware('role:admin,guru,siswa,orang_tua')->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::post('/reports', [ReportController::class, 'publish'])->name('reports.publish');
        Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
        Route::put('/reports/{report}/reopen', [ReportController::class, 'reopen'])->name('reports.reopen');
    });
});
