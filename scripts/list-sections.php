<?php
require __DIR__ . '/../config/config.php';
spl_autoload_register(function (string $c) {
    foreach (['config','helpers','models','controllers','controllers/Admin'] as $d) {
        $f = ROOT . '/' . $d . '/' . $c . '.php';
        if (is_file($f)) { require $f; return; }
    }
});
foreach (Section::all() as $s) {
    echo $s['section_key'] . ' => ' . $s['title'] . PHP_EOL;
}
