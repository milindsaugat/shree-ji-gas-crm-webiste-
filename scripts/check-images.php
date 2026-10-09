<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::get();
foreach (['blog_posts', 'case_studies', 'use_cases'] as $table) {
    echo "=== Table: $table ===\n";
    $rows = $db->query("SELECT id, title, slug, status, thumbnail, image_alt FROM $table")->fetchAll();
    foreach ($rows as $r) {
        echo "#{$r['id']} | '{$r['slug']}' | status: '{$r['status']}' | thumb: '{$r['thumbnail']}'\n";
    }
    echo "\n";
}
