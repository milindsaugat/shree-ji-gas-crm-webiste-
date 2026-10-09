<?php
// Local XAMPP convenience only. Production credentials should be supplied by the host.
declare(strict_types=1);
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
$root=dirname(__DIR__);
if(is_file($root.'/config/local.php')){fwrite(STDERR,"A local configuration already exists. It has not been changed.\n");exit(1);}
$connection=new PDO('mysql:host=127.0.0.1;port=3306;charset=utf8mb4','root','',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
$name='sjg_local_'.bin2hex(random_bytes(3));$password=bin2hex(random_bytes(24));
$connection->exec('CREATE DATABASE IF NOT EXISTS `shreejigas` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
$user=$connection->quote($name);$hostNames=['localhost','127.0.0.1'];
foreach($hostNames as $host){
    $connection->exec('CREATE USER IF NOT EXISTS '.$user."@'".$host."' IDENTIFIED BY ".$connection->quote($password));
    $connection->exec('GRANT ALL PRIVILEGES ON `shreejigas`.* TO '.$user."@'".$host."'");
}
$connection->exec('FLUSH PRIVILEGES');
$settings=['DB_DRIVER'=>'mysql','DB_HOST'=>'127.0.0.1','DB_PORT'=>'3306','DB_NAME'=>'shreejigas','DB_USER'=>$name,'DB_PASS'=>$password,'APP_URL'=>'http://localhost/shreejigas','APP_BASE_PATH'=>'/shreejigas','MAIL_ENABLED'=>'0'];
$php="<?php\n// Local connection settings. Contains credentials; do not commit or share.\nreturn ".var_export($settings,true).";\n";
if(file_put_contents($root.'/config/local.php',$php,LOCK_EX)===false)throw new RuntimeException('Could not save local configuration.');
echo "Configured the site to use MySQL with a dedicated project database account.\n";
