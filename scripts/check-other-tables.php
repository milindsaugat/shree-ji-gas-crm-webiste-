<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::get();
foreach (['products', 'pages', 'media'] as $table) {
    echo "=== Table: $table ===\n";
    $rows = $db->query("SELECT * FROM $table LIMIT 10")->fetchAll();
    foreach ($rows as $r) {
        $name = $r['name'] ?? $r['title'] ?? $r['filename'] ?? '';
        $thumb = $r['thumbnail'] ?? $r['path'] ?? '';
        echo "#{$r['id']} | '{$name}' | path: '{$thumb}'\n";
    }
    echo "\n";
}
