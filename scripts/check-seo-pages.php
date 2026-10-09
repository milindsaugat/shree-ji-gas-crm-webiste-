<?php
require __DIR__ . '/../config/config.php';
spl_autoload_register(function (string $class): void {
    foreach (['config','helpers','models','controllers','controllers/Admin'] as $dir) {
        $file = ROOT . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) { require $file; return; }
    }
});
require ROOT . '/helpers/functions.php';

$pdo = Database::get();
echo "=== PAGES ===\n";
$pages = $pdo->query('SELECT id, slug, title, status FROM pages ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
print_r($pages);

echo "=== SEO META ===\n";
$seo = $pdo->query('SELECT id, page_key, meta_title, focus_keyword FROM seo_meta ORDER BY id ASC')->fetchAll(PDO::FETCH_ASSOC);
print_r($seo);
