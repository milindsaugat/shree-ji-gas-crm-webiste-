<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('SITE_NAME', 'Shree Ji Gas Industries');
// 1. Detect environment (Localhost vs Hostinger Production)
$httpHost = strtolower($_SERVER['HTTP_HOST'] ?? '');
$isHostinger = (
    str_contains($httpHost, 'shreejigasindustriesagra.in') ||
    str_contains(__DIR__, 'u745447873') ||
    (!empty($httpHost) && !str_starts_with($httpHost, 'localhost') && !str_starts_with($httpHost, '127.0.0.1') && !str_starts_with($httpHost, '[::1]')) ||
    (DIRECTORY_SEPARATOR === '/' && !str_starts_with($httpHost, 'localhost'))
);
$isLocalhost = !$isHostinger;

// 2. Default credentials based on detected environment
if ($isHostinger) {
    $defaultDbDriver = 'mysql';
    $defaultDbHost   = 'localhost';
    $defaultDbPort   = '3306';
    $defaultDbName   = 'u745447873_shreejigas';
    $defaultDbUser   = 'u745447873_shreejigas';
    $defaultDbPass   = 'Shreejigas@123456';
    $defaultBasePath = '';
    $scheme          = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'https://';
    $defaultAppUrl   = $scheme . ($httpHost ?: 'shreejigasindustriesagra.in');
} else {
    $defaultDbDriver = 'mysql';
    $defaultDbHost   = '127.0.0.1';
    $defaultDbPort   = '3306';
    $defaultDbName   = 'shreejigas';
    $defaultDbUser   = 'root';
    $defaultDbPass   = '';
    $defaultBasePath = '/shreejigas';
    $defaultAppUrl   = 'http://localhost/shreejigas';
}

// 3. Optional local.php overrides (if present)
$local = is_file(__DIR__ . '/local.php') ? require __DIR__ . '/local.php' : [];
if (!is_array($local)) $local = [];

// If on Hostinger, never allow accidental local XAMPP credentials from uploaded local.php to override production
if ($isHostinger) {
    if (($local['DB_USER'] ?? '') === 'root' || ($local['DB_NAME'] ?? '') === 'shreejigas') {
        unset($local['DB_DRIVER'], $local['DB_HOST'], $local['DB_PORT'], $local['DB_NAME'], $local['DB_USER'], $local['DB_PASS'], $local['APP_BASE_PATH'], $local['APP_URL']);
    }
}

$option = static fn(string $name, mixed $default = ''): mixed => getenv($name) !== false ? getenv($name) : ($local[$name] ?? $default);

define('DB_DRIVER', (string)$option('DB_DRIVER', $defaultDbDriver));
define('DB_HOST', (string)$option('DB_HOST', $defaultDbHost));
define('DB_PORT', (string)$option('DB_PORT', $defaultDbPort));
define('DB_NAME', (string)$option('DB_NAME', $defaultDbName));
define('DB_USER', (string)$option('DB_USER', $defaultDbUser));
define('DB_PASS', (string)$option('DB_PASS', $defaultDbPass));

define('MAIL_ENABLED', (string)$option('MAIL_ENABLED', $isHostinger ? '1' : '0') === '1');
define('SMTP_ENABLED', (string)$option('SMTP_ENABLED', '0') === '1');
define('SMTP_HOST', (string)$option('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int)$option('SMTP_PORT', 587));
define('SMTP_USER', (string)$option('SMTP_USER', ''));
define('SMTP_PASS', (string)$option('SMTP_PASS', ''));
define('SMTP_FROM_EMAIL', (string)$option('SMTP_FROM_EMAIL', SMTP_USER));
define('SMTP_FROM_NAME', (string)$option('SMTP_FROM_NAME', SITE_NAME));
define('OTP_EXPIRY_MINUTES', max(5, min(30, (int)$option('OTP_EXPIRY_MINUTES', 10))));

$script = PHP_SAPI === 'cli' ? '' : str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$autoScriptPath = ($script === '/' || $script === '.' || $script === '') ? '' : $script;
define('BASE_PATH', rtrim((string)$option('APP_BASE_PATH', $isHostinger ? $autoScriptPath : ($defaultBasePath ?: $autoScriptPath)), '/'));
define('SITE_URL', rtrim((string)$option('APP_URL', $defaultAppUrl), '/'));
date_default_timezone_set('Asia/Kolkata');
require_once __DIR__ . '/database.php';
