<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $keys = ['absensi' => ['siswa_id', 'jadwal_id', 'tanggal'], 'nilai' => ['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran', 'semester']];
        // Do not silently delete historical duplicates. Admin must resolve them first.
        foreach ($keys as $table => $columns) {
            if (DB::table($table)->select($columns)->groupBy($columns)->havingRaw('COUNT(*) > 1')->first()) {
                throw new RuntimeException("Ada duplikasi pada $table. Cadangkan dan tinjau data sebelum migrasi; tidak ada data yang dihapus otomatis.");
            }
        }
        foreach ($keys as $table => $columns) {
            Schema::table($table, fn (Blueprint $t) => $t->unique($columns, $table.'_academic_unique'));
        }
    }

    public function down(): void
    {
        foreach (['absensi', 'nilai'] as $table) {
            Schema::table($table, fn (Blueprint $t) => $t->dropUnique($table.'_academic_unique'));
        }
    }
};
