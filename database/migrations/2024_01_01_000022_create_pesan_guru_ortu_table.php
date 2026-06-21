<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesan_guru_ortu', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('penerima_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('siswa_id')->nullable()->constrained('siswa')->onDelete('set null');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pesan_guru_ortu'); }
};
