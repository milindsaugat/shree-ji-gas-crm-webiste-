<?php
// Exports content and uploaded-file references; copy assets/uploads separately.
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require dirname(__DIR__).'/config/config.php';require ROOT.'/database/install.php';
$source=new PDO('sqlite:'.ROOT.'/storage/preview.sqlite',null,null,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$tables=['settings','sections','categories','products','blog_categories','blog_posts','case_studies','use_cases','testimonials','client_logos','pages','seo_meta','media'];
$file=ROOT.'/storage/content-export.sql';$out=fopen($file,'wb');fwrite($out,"-- Content export; import into an EMPTY MySQL database after schema.sql.\n-- Does not include administrator passwords, enquiries or rate-limit records.\nSET NAMES utf8mb4;\nSTART TRANSACTION;\n");
foreach($tables as $table){foreach($source->query('SELECT * FROM '.$table) as $row){$values=array_map(fn($v)=>$v===null?'NULL':"CONVERT(0x".bin2hex((string)$v)." USING utf8mb4)",array_values($row));$values=array_map(fn($v)=>$v==='CONVERT(0x USING utf8mb4)'?"''":$v,$values);fwrite($out,'INSERT INTO `'.$table.'` (`'.implode('`,`',array_keys($row)).'`) VALUES ('.implode(',',$values).");\n");}}
fwrite($out,"COMMIT;\n");fclose($out);echo "Content exported to storage/content-export.sql. Import into a fresh schema only.\n";
