<?php
declare(strict_types=1);
function e(mixed $value): string { return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $path = ''): string { return BASE_PATH . '/' . ltrim($path, '/'); }
function asset(string $path): string { $relative='assets/'.ltrim($path,'/'); $file=ROOT.'/'.$relative; $version=is_file($file)?(string)filemtime($file):'1'; return url($relative).'?v='.$version; }
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function notFound(): never { http_response_code(404); header('Refresh: 8; url='.url()); header('Cache-Control: no-store'); require ROOT.'/views/errors/not-found.php'; exit; }
function icon(string $name, string $class = ''): string { return '<i class="ri-' . e(preg_replace('/[^a-z0-9-]/', '', $name)) . ' ' . e($class) . '" aria-hidden="true"></i>'; }
function csrf(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrfField(): string { return '<input type="hidden" name="csrf" value="' . e(csrf()) . '">'; }
function checkCsrf(): void {
    if (!hash_equals(csrf(), (string)($_POST['csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ''))) {
        http_response_code(403); exit('Your session expired. Refresh the page and try again.');
    }
}
function flash(string $message, string $type = 'success'): void { $_SESSION['flash'] = compact('message', 'type'); }
function jsonResponse(array $data, int $status = 200): never { http_response_code($status); header('Content-Type: application/json'); echo json_encode($data); exit; }
function setting(string $key, string $default = ''): string { return Setting::get($key, $default); }
function photo(string $path): string { return preg_match('#^https://#', $path) ? $path : url($path ?: 'assets/images/equipment.webp'); }
function safeLink(string $link): string { return preg_match('#^(https://|mailto:|tel:|/|\#)#i', $link) && !str_starts_with($link, '//') ? $link : url($link); }
function rich(string $html): string {
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();
    $allowed = ['div','p','br','strong','b','em','i','u','s','h2','h3','h4','h5','h6','ul','ol','li','blockquote','pre','code','a','table','thead','tbody','tfoot','tr','th','td','span','figure','figcaption','hr','img'];
    $clean = function (DOMNode $node) use (&$clean, $allowed): void {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child instanceof DOMElement) {
                $tag = strtolower($child->tagName);
                if (!in_array($tag, $allowed, true)) { $node->removeChild($child); continue; }
                foreach (iterator_to_array($child->attributes) as $attribute) {
                    $attrName = strtolower($attribute->name);
                    $val = $attribute->value;
                    if ($tag === 'a') {
                        if ($attrName === 'href' && preg_match('#^(https?://|/(?!/)|mailto:|tel:|\#)#i', $val)) continue;
                        if (in_array($attrName, ['target', 'title', 'class', 'rel'])) continue;
                    } elseif ($tag === 'img') {
                        if ($attrName === 'src' && preg_match('#^(https?://|/(?!/)|assets/|data:image/)#i', $val)) continue;
                        if (in_array($attrName, ['alt', 'width', 'height', 'loading', 'class', 'style'])) continue;
                    } elseif ($attrName === 'class' && preg_match('/^[a-zA-Z0-9_\-\s]+$/', $val)) {
                        continue;
                    } elseif ($attrName === 'style' && !preg_match('/(expression|javascript|url)/i', $val)) {
                        continue;
                    }
                    $child->removeAttribute($attribute->name);
                }
                if ($tag === 'a') $child->setAttribute('rel', 'noopener noreferrer');
                $clean($child);
            }
        }
    };
    $root = $doc->getElementsByTagName('div')->item(0);
    if (!$root) return '';
    $clean($root);
    $out = ''; foreach ($root->childNodes as $node) $out .= $doc->saveHTML($node);
    return $out;
}
function view(string $template, array $data = [], string $layout = 'main'): void {
    extract($data, EXTR_SKIP);
    ob_start(); require ROOT . '/views/' . $template . '.php'; $content = ob_get_clean();
    require ROOT . '/views/layouts/' . $layout . '.php';
}
function allCategories(): array { return (new ContentModel('categories'))->all(); }
function productCard(array $product): void { require ROOT . '/views/partials/product-card.php'; }
function contentCard(array $item, string $type): void { require ROOT . '/views/partials/content-card.php'; }
function isDarkColor(string $hex): bool {
    $hex = trim($hex);
    if ($hex === '') return false;
    $hex = ltrim($hex, '#');
    if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
    if (strlen($hex) !== 6 || !ctype_xdigit($hex)) return false;
    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));
    return ((0.299 * $r + 0.587 * $g + 0.114 * $b) / 255) < 0.55;
}
