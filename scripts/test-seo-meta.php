<?php
require __DIR__ . '/../config/config.php';
spl_autoload_register(function (string $class): void {
    foreach (['config','helpers','models','controllers'] as $dir) {
        $file = ROOT . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) { require $file; return; }
    }
});
require ROOT . '/helpers/functions.php';

echo "=== Seo::meta('/') ===\n";
print_r(Seo::meta('/'));

echo "=== Seo::meta('/about') ===\n";
print_r(Seo::meta('/about'));
