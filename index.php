<?php
declare(strict_types=1);
require __DIR__ . '/config/config.php';
if (PHP_SAPI === 'cli-server') {
    $file = realpath(__DIR__ . rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    if ($file && str_starts_with($file, __DIR__ . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR) && is_file($file) && preg_match('/\.(css|js|svg|png|jpg|jpeg|webp|pdf|woff2?|ttf)$/i', $file)) return false;
}
ini_set('session.use_strict_mode', '1');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'path' => BASE_PATH ?: '/']);
session_start();
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-Frame-Options: SAMEORIGIN');
spl_autoload_register(function (string $class): void { foreach (['config','helpers','models','controllers','controllers/Admin'] as $dir) { $file = ROOT . '/' . $dir . '/' . $class . '.php'; if (is_file($file)) { require $file; return; } } });
require ROOT . '/helpers/functions.php';
$path = rawurldecode(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH));
if (BASE_PATH && str_starts_with($path, BASE_PATH)) $path = substr($path, strlen(BASE_PATH));
$path = trim($path, '/');
$GLOBALS['path'] = $path;
try {
    if (str_starts_with($path, 'admin')) { (new AdminController())->dispatch($path); }
    elseif ($path === '') { (new HomeController())->index(); }
    elseif ($path === 'products' || preg_match('#^(products|categories)/([^/]+)$#', $path)) { (new ProductController())->dispatch($path); }
    elseif (preg_match('#^(blog|case-studies|use-cases)(?:/([^/]+))?$#', $path, $m)) { (new EditorialController())->dispatch($m[1], $m[2] ?? null); }
    elseif (in_array($path, ['sitemap.xml','robots.txt'])) { (new Seo())->endpoint($path); }
    elseif ($path === 'enquiry' && $_SERVER['REQUEST_METHOD'] === 'POST') { (new PageController())->enquiry(); }
    elseif ($path === 'catalogue') { (new ProductController())->catalogue(); }
    elseif (in_array($path, ['about','contact','privacy','terms','compliance','shipping-policy']) || (new PageController())->handles($path)) { (new PageController())->show($path); }
    else { notFound(); }
} catch (Throwable $error) {
    error_log((string)$error);
    http_response_code(500);
    if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) jsonResponse(['ok' => false, 'message' => 'Unable to complete the request. Please try again.'], 500);
    header('Cache-Control: no-store, private');
    require ROOT.'/views/errors/unavailable.php';
}
