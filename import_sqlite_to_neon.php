
<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$source = new PDO(
    'sqlite:' . __DIR__ . '/database/smartschool_uji.sqlite'
);
$source->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$target = DB::connection('pgsql');

$tables = [
    'users',
    'mata_pelajaran',
    'orang_tua',
    'guru',
    'kelas',
    'siswa',
    'jadwal',
    'absensi',
    'nilai',
    'konseling',
    'chat_konseling',
    'materi',
    'berita',
    'pesan_guru_ortu',
    'notifikasi',
    'kalender_akademik',
    'academic_periods',
    'enrollments',
    'school_imports',
    'leave_requests',
    'report_publications',
    'follow_ups',
    'school_audits',
    'admissions',
];

echo "=== PEMERIKSAAN AWAL ===\n";

$sourceCounts = [];

foreach ($tables as $table) {
    $sourceCount = (int) $source
        ->query('SELECT COUNT(*) FROM "' . $table . '"')
        ->fetchColumn();

    $targetCount = (int) $target->table($table)->count();

    $sourceCounts[$table] = $sourceCount;

    echo "{$table}: SQLite={$sourceCount}, Neon={$targetCount}\n";

    if ($targetCount !== 0) {
        throw new RuntimeException(
            "Tabel {$table} di Neon tidak kosong. Impor dibatalkan."
        );
    }
}

echo "\n=== MEMULAI IMPOR ===\n";

$target->transaction(function () use (
    $source,
    $target,
    $tables,
    $sourceCounts
) {
    foreach ($tables as $table) {
        $stmt = $source->query('SELECT * FROM "' . $table . '"');

        $batch = [];
        $inserted = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $batch[] = $row;

            if (count($batch) >= 100) {
                $target->table($table)->insert($batch);
                $inserted += count($batch);
                $batch = [];
            }
        }

        if ($batch !== []) {
            $target->table($table)->insert($batch);
            $inserted += count($batch);
        }

        if ($inserted !== $sourceCounts[$table]) {
            throw new RuntimeException(
                "Jumlah data tidak cocok pada tabel {$table}."
            );
        }

        // Sesuaikan sequence ID PostgreSQL.
        $sequence = $target->selectOne(
            "SELECT pg_get_serial_sequence(?, 'id') AS sequence",
            ["public.{$table}"]
        )->sequence;

        if ($sequence !== null) {
            $maxId = $target->table($table)->max('id');

            $target->selectOne(
                'SELECT setval(?::regclass, ?, ?)',
                [
                    $sequence,
                    $maxId ?? 1,
                    $maxId !== null,
                ]
            );
        }

        echo "Imported {$table}: {$inserted} baris\n";
    }

    echo "\n=== VERIFIKASI DALAM TRANSAKSI ===\n";

    foreach ($tables as $table) {
        $actual = (int) $target->table($table)->count();

        if ($actual !== $sourceCounts[$table]) {
            throw new RuntimeException(
                "Verifikasi gagal pada {$table}: "
                . "sumber={$sourceCounts[$table]}, tujuan={$actual}"
            );
        }

        echo "OK {$table}: {$actual} baris\n";
    }
});

echo "\nIMPORT BERHASIL. TRANSAKSI TELAH DI-COMMIT.\n";
