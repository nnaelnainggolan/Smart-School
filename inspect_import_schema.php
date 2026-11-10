<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$tables = Illuminate\Support\Facades\DB::connection('sqlite')
    ->select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name");

foreach ($tables as $item) {
    $table = $item->name;

    if ($table === 'migrations' || in_array($table, [
        'cache', 'cache_locks', 'sessions', 'jobs',
        'job_batches', 'failed_jobs', 'password_reset_tokens'
    ])) {
        continue;
    }

    echo "\n=== {$table} ===\n";

    $columns = Illuminate\Support\Facades\DB::connection('sqlite')
        ->select('PRAGMA table_info("' . $table . '")');

    foreach ($columns as $column) {
        echo "{$column->name} | {$column->type} | "
            . ($column->notnull ? 'NOT NULL' : 'NULL')
            . " | " . ($column->pk ? 'PRIMARY KEY' : '')
            . PHP_EOL;
    }
}
