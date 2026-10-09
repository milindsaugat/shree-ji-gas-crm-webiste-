<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';
spl_autoload_register(function (string $class): void {
    foreach (['config', 'helpers', 'models', 'controllers', 'controllers/Admin'] as $dir) {
        $file = ROOT . '/' . $dir . '/' . $class . '.php';
        if (is_file($file)) {
            require $file;
            return;
        }
    }
});
require ROOT . '/helpers/functions.php';

echo "Database driver: " . DB_DRIVER . PHP_EOL;
echo "Old logo setting: " . setting('logo') . PHP_EOL;

// Update MySQL or currently active database
Setting::save('logo', 'assets/images/logo.png');
echo "Active DB new logo setting: " . Setting::get('logo') . PHP_EOL;

// Also update SQLite preview database if it exists
$sqlitePath = ROOT . '/storage/preview.sqlite';
if (is_file($sqlitePath)) {
    $sq = new PDO('sqlite:' . $sqlitePath);
    $sq->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $sq->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
    $stmt->execute(['assets/images/logo.png', 'logo']);
    echo "SQLite preview database updated: " . $stmt->rowCount() . " rows affected." . PHP_EOL;
}

