<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nip')->unique()->nullable();
            $table->string('no_hp')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('alamat')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->string('jurusan_pendidikan')->nullable();
            $table->string('foto')->nullable();
            $table->string('jabatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('guru'); }
};
