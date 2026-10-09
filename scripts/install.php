<?php
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require dirname(__DIR__).'/config/config.php';
require ROOT.'/database/install.php';
require ROOT.'/helpers/Slug.php';
if(in_array('--schema',$argv,true)){
    file_put_contents(ROOT.'/schema.sql',"-- Shree Ji Gas Industries · MySQL 8.0\n-- Import into an empty database, then run php scripts/install.php to populate starter content.\n\n".implode(";\n\n",schemaStatements('mysql')).";\n");
    echo "schema.sql generated.\n";exit;
}
try{
    if(DB_DRIVER!=='mysql'){fwrite(STDERR,"Set DB_DRIVER=mysql and your database connection settings before running the MySQL installer. SQLite preview installs automatically.\n");exit(1);}
    $pdo=new PDO('mysql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME.';charset=utf8mb4',DB_USER,DB_PASS,[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
    installDatabase($pdo,'mysql');
    echo "Database ready. Open /admin/setup to create an administrator. Remote setup requires APP_SETUP_TOKEN.\n";
}catch(Throwable $e){fwrite(STDERR,"Installation failed: ".$e->getMessage()."\n");exit(1);}
