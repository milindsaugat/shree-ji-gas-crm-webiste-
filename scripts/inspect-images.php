<?php
foreach (glob(__DIR__ . '/../assets/images/*.*') as $file) {
    $info = @getimagesize($file);
    $size = filesize($file);
    echo basename($file) . " | " . ($info ? "{$info[0]}x{$info[1]} ({$info['mime']})" : 'non-image') . " | " . round($size / 1024, 1) . " KB\n";
}
