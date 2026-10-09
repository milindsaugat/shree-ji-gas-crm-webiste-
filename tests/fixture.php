<?php
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require dirname(__DIR__).'/config/config.php';require ROOT.'/config/database.php';
$allowDbTests = getenv('ALLOW_LOCAL_DB_TESTS') === '1' || ($_SERVER['ALLOW_LOCAL_DB_TESTS'] ?? '') === '1' || ($_ENV['ALLOW_LOCAL_DB_TESTS'] ?? '') === '1';
if(DB_DRIVER!=='sqlite'&&(!$allowDbTests||!in_array(DB_HOST,['127.0.0.1','localhost'],true)||DB_NAME!=='shreejigas')){fwrite(STDERR,"MySQL fixtures require ALLOW_LOCAL_DB_TESTS=1 and the local shreejigas database.\n");exit(1);}
$pdo=Database::get();$action=$argv[1]??'';
if($action==='create-admin'){
    $pdo->prepare('DELETE FROM admin_users WHERE email = ? OR username = ? OR email = ?')->execute(['browser-admin@example.test', 'browser-test', 'new-verified-admin@example.test']);
    $pdo->prepare('INSERT INTO admin_users (name,username,email,password_hash) VALUES (?,?,?,?)')->execute(['Browser Test','browser-test','browser-admin@example.test',password_hash($argv[2],PASSWORD_BCRYPT)]);
    echo "Temporary test administrator created.\n";
}elseif($action==='cleanup'){
    $pdo->prepare('DELETE FROM admin_users WHERE email = ? OR username = ? OR email = ?')->execute(['browser-admin@example.test', 'browser-test', 'new-verified-admin@example.test']);
    $pdo->prepare('DELETE FROM enquiries WHERE email = ? AND name = ?')->execute(['browser-smoke@example.test','Browser smoke test']);
    if (DB_DRIVER === 'mysql') {
        $maxId = (int)$pdo->query('SELECT COALESCE(MAX(id), 0) FROM enquiries')->fetchColumn();
        $nextId = $maxId + 1;
        $pdo->exec("ALTER TABLE enquiries AUTO_INCREMENT = {$nextId}");
    }
    $stmt=$pdo->prepare('SELECT id,path FROM media WHERE image_alt = ? AND original_name = ?');$stmt->execute(['Browser upload test equipment','browser-test-equipment.webp']);
    foreach($stmt->fetchAll() as $row){$file=realpath(ROOT.'/'.$row['path']);$uploads=realpath(ROOT.'/assets/uploads');if($file&&$uploads&&str_starts_with($file,$uploads.DIRECTORY_SEPARATOR)&&str_starts_with(basename($file),'browser-test-equipment-'))unlink($file);$pdo->prepare('DELETE FROM media WHERE id = ?')->execute([$row['id']]);}
    echo "Test records removed.\n";
}
