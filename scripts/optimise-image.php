<?php
// Development utility: PHP -d extension=gd scripts/optimise-image.php input.png output.webp
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
if ($argc !== 3 || !extension_loaded('gd')) { fwrite(STDERR,"Usage: php -d extension=gd scripts/optimise-image.php input.png output.webp\n"); exit(1); }
$source=imagecreatefromstring(file_get_contents($argv[1]));
if(!$source)exit(1);
$width=min(1536,imagesx($source));$height=(int)round(imagesy($source)*$width/imagesx($source));
$image=imagecreatetruecolor($width,$height);imagealphablending($image,false);imagesavealpha($image,true);imagefill($image,0,0,imagecolorallocatealpha($image,255,255,255,127));imagecopyresampled($image,$source,0,0,0,0,$width,$height,imagesx($source),imagesy($source));
imagewebp($image,$argv[2],85);imagedestroy($source);imagedestroy($image);echo basename($argv[2])." saved\n";
