<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require dirname(__DIR__).'/config/config.php';require ROOT.'/database/install.php';
if(DB_DRIVER!=='mysql'){fwrite(STDERR,"Configure the MySQL connection before importing.\n");exit(1);}
if(!is_file(ROOT.'/storage/preview.sqlite')){fwrite(STDERR,"No preview database found.\n");exit(1);}
$source=new PDO('sqlite:'.ROOT.'/storage/preview.sqlite',null,null,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$target=new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false]);
foreach(schemaStatements('mysql') as $sql)$target->exec($sql);
$tables=['settings','sections','categories','products','blog_categories','blog_posts','case_studies','use_cases','testimonials','client_logos','pages','seo_meta','media','admin_users','enquiries'];
foreach($tables as $table)if((int)$target->query('SELECT COUNT(*) FROM '.$table)->fetchColumn()!==0){fwrite(STDERR,'Import refused: '.$table." contains existing records. Use an empty target database.\n");exit(1);}
$target->beginTransaction();
try{foreach($tables as $table){$count=0;foreach($source->query('SELECT * FROM '.$table) as $row){$sql='INSERT INTO `'.$table.'` (`'.implode('`,`',array_keys($row)).'`) VALUES ('.implode(',',array_fill(0,count($row),'?')).')';$target->prepare($sql)->execute(array_values($row));$count++;}echo $table.': '.$count." records copied\n";}$target->commit();echo "MySQL import complete. The original preview database has been preserved.\n";}catch(Throwable $e){$target->rollBack();fwrite(STDERR,$e->getMessage()."\n");exit(1);}
