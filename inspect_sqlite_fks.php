<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/smartschool_uji.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$tables = $db->query("
    SELECT name FROM sqlite_master
    WHERE type = 'table' AND name NOT LIKE 'sqlite_%'
    ORDER BY name
")->fetchAll(PDO::FETCH_COLUMN);

foreach ($tables as $table) {
    $safeTable = str_replace('"', '""', $table);
    $foreignKeys = $db->query('PRAGMA foreign_key_list("' . $safeTable . '")')
                      ->fetchAll(PDO::FETCH_ASSOC);

    foreach ($foreignKeys as $fk) {
        echo $table . '.' . $fk['from']
            . ' -> ' . $fk['table'] . '.' . $fk['to']
            . PHP_EOL;
    }
}
