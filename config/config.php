<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
define('SITE_NAME', 'Shree Ji Gas Industries');
$local = is_file(__DIR__ . '/local.php') ? require __DIR__ . '/local.php' : [];
$option = static fn(string $name, mixed $default = ''): mixed => getenv($name) !== false ? getenv($name) : ($local[$name] ?? $default);
define('DB_DRIVER', $option('DB_DRIVER', 'sqlite'));
define('DB_HOST', $option('DB_HOST', '127.0.0.1'));
define('DB_PORT', $option('DB_PORT', '3306'));
define('DB_NAME', $option('DB_NAME', 'shreejigas'));
define('DB_USER', $option('DB_USER', 'root'));
define('DB_PASS', $option('DB_PASS', ''));
define('MAIL_ENABLED', (string)$option('MAIL_ENABLED', '0') === '1');
define('SMTP_ENABLED', (string)$option('SMTP_ENABLED', '0') === '1');
define('SMTP_HOST', (string)$option('SMTP_HOST', 'smtp.gmail.com'));
define('SMTP_PORT', (int)$option('SMTP_PORT', 587));
define('SMTP_USER', (string)$option('SMTP_USER', ''));
define('SMTP_PASS', (string)$option('SMTP_PASS', ''));
define('SMTP_FROM_EMAIL', (string)$option('SMTP_FROM_EMAIL', SMTP_USER));
define('SMTP_FROM_NAME', (string)$option('SMTP_FROM_NAME', SITE_NAME));
define('OTP_EXPIRY_MINUTES', max(5, min(30, (int)$option('OTP_EXPIRY_MINUTES', 10))));
$script = PHP_SAPI === 'cli' ? '' : str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
define('BASE_PATH', rtrim($option('APP_BASE_PATH', $script === '/' || $script === '.' ? '' : $script), '/'));
define('SITE_URL', rtrim($option('APP_URL', 'http://localhost' . BASE_PATH), '/'));
date_default_timezone_set('Asia/Kolkata');
