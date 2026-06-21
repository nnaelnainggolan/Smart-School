<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('kalender_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('kategori', ['akademik','libur','ujian','acara'])->default('akademik');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kalender_akademik'); }
};
