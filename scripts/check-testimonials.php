<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

$db = Database::get();
$rows = $db->query("SELECT * FROM testimonials")->fetchAll();
echo "Found " . count($rows) . " testimonials:\n";
foreach ($rows as $r) {
    print_r($r);
}
