<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chat_konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konseling_id')->constrained('konseling')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('pesan');
            $table->boolean('dibaca')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chat_konseling'); }
};
