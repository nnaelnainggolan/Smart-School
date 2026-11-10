<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    DB::beginTransaction();

    Schema::create('migration_probe_users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->enum('role', ['admin', 'guru', 'guru_bk', 'siswa', 'orang_tua'])->default('siswa');
        $table->boolean('is_active')->default(true);
        $table->rememberToken();
        $table->timestamps();
    });

    echo "USERS SCHEMA TEST: OK\n";
} catch (Throwable $e) {
    echo "ERROR CLASS: " . get_class($e) . "\n";
    echo "ERROR MESSAGE: " . $e->getMessage() . "\n";
} finally {
    if (DB::transactionLevel() > 0) {
        DB::rollBack();
    }
}