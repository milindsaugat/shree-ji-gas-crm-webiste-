<?php
declare(strict_types=1);

final class Database
{
    private static ?PDO $connection = null;
    public static function get(): PDO
    {
        if (self::$connection) return self::$connection;
        if (DB_DRIVER === 'mysql') {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            try {
                self::$connection = new PDO($dsn, DB_USER, DB_PASS, self::options());
            } catch (PDOException $e) {
                // If custom/Hostinger credentials fail on local development with access denied (1045) or unknown database, attempt local XAMPP root fallback
                if (($e->getCode() === 1045 || str_contains($e->getMessage(), 'Access denied') || str_contains($e->getMessage(), 'Unknown database')) && (DB_USER !== 'root' || DB_PASS !== '')) {
                    try {
                        $localDsn = 'mysql:host=127.0.0.1;port=3306;dbname=shreejigas;charset=utf8mb4';
                        self::$connection = new PDO($localDsn, 'root', '', self::options());
                    } catch (Throwable) {
                        throw $e;
                    }
                } else {
                    throw $e;
                }
            }
        } else {
            if (!is_dir(ROOT . '/storage')) mkdir(ROOT . '/storage', 0750, true);
            self::$connection = new PDO('sqlite:' . ROOT . '/storage/preview.sqlite', null, null, self::options());
            self::$connection->exec('PRAGMA foreign_keys = ON');
            self::$connection->exec('PRAGMA busy_timeout = 5000');
            require_once ROOT . '/database/install.php';
            installDatabase(self::$connection, 'sqlite');
        }
        return self::$connection;
    }
    private static function options(): array
    {
        return [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false];
    }
}
