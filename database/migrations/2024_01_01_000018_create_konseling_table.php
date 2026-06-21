<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('guru_bk_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->string('topik');
            $table->text('deskripsi')->nullable();
            $table->enum('jenis', ['akademik','pribadi','sosial','karir'])->default('pribadi');
            $table->enum('status', ['pending','disetujui','berlangsung','selesai','ditolak'])->default('pending');
            $table->dateTime('jadwal_konseling')->nullable();
            $table->text('catatan_bk')->nullable();
            $table->enum('status_psikologis', ['baik','perlu_perhatian','kritis'])->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('konseling'); }
};
