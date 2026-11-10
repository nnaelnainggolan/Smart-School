
<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Koneksi langsung ke SQLite lokal
$source = new PDO(
    'sqlite:' . __DIR__ . '/database/smartschool_uji.sqlite'
);
$source->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Koneksi PostgreSQL Neon melalui Laravel
$target = Illuminate\Support\Facades\DB::connection('pgsql');

$skip = [
    'migrations', 'cache', 'cache_locks', 'sessions',
    'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens'
];

$tables = $source->query("
    SELECT name
    FROM sqlite_master
    WHERE type = 'table'
      AND name NOT LIKE 'sqlite_%'
    ORDER BY name
")->fetchAll(PDO::FETCH_COLUMN);

$hasMismatch = false;

foreach ($tables as $table) {
    if (in_array($table, $skip, true)) {
        continue;
    }

    $sourceColumns = $source
        ->query('PRAGMA table_info("' . $table . '")')
        ->fetchAll(PDO::FETCH_COLUMN, 1);

    $targetColumns = $target->select("
        SELECT column_name
        FROM information_schema.columns
        WHERE table_schema = 'public'
          AND table_name = ?
        ORDER BY ordinal_position
    ", [$table]);

    $targetColumns = array_map(
        fn ($column) => $column->column_name,
        $targetColumns
    );

    $missing = array_diff($sourceColumns, $targetColumns);
    $extra = array_diff($targetColumns, $sourceColumns);

    if ($missing || $extra) {
        $hasMismatch = true;
        echo "\nMISMATCH: {$table}\n";

        if ($missing) {
            echo "Kolom belum ada di Neon: "
                . implode(', ', $missing) . "\n";
        }

        if ($extra) {
            echo "Kolom tambahan di Neon: "
                . implode(', ', $extra) . "\n";
        }
    }
}

if (!$hasMismatch) {
    echo "\nOK: Semua nama kolom tabel aplikasi cocok antara SQLite dan Neon.\n";
}
