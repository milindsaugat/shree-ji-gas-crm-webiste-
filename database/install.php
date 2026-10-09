<?php
function schemaStatements(string $driver): array
{
    $id = $driver === 'mysql' ? 'INT PRIMARY KEY AUTO_INCREMENT' : 'INTEGER PRIMARY KEY AUTOINCREMENT';
    $tables = [
        'settings' => "id $id, `key` VARCHAR(100) NOT NULL UNIQUE, `value` TEXT NOT NULL, `group` VARCHAR(100) DEFAULT 'site'",
        'sections' => "id $id, section_key VARCHAR(80) NOT NULL UNIQUE, title VARCHAR(255) NOT NULL, data TEXT NOT NULL, is_active INTEGER DEFAULT 1, sort_order INTEGER DEFAULT 0",
        'categories' => "id $id, parent_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, icon VARCHAR(100) DEFAULT 'first-aid-kit-line', description TEXT, sort_order INTEGER DEFAULT 0",
        'products' => "id $id, cat_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, short_desc TEXT, description TEXT, specs TEXT, tags TEXT, thumbnail TEXT, image_alt VARCHAR(255), gallery TEXT, brochure_pdf TEXT, applications TEXT, features TEXT, faqs TEXT, is_featured INTEGER DEFAULT 0, badge_label VARCHAR(100), badge_type VARCHAR(40), status VARCHAR(30) DEFAULT 'draft', sort_order INTEGER DEFAULT 0, created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'blog_categories' => "id $id, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, sort_order INTEGER DEFAULT 0",
        'blog_posts' => "id $id, cat_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, excerpt TEXT, content TEXT, thumbnail TEXT, image_alt VARCHAR(255), author VARCHAR(255), tags TEXT, is_featured INTEGER DEFAULT 0, status VARCHAR(30) DEFAULT 'draft', published_at DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'case_studies' => "id $id, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, client_name VARCHAR(255), industry VARCHAR(255), challenge TEXT, solution TEXT, results TEXT, products_used TEXT, thumbnail TEXT, image_alt VARCHAR(255), status VARCHAR(30) DEFAULT 'draft'",
        'use_cases' => "id $id, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, application_context TEXT, benefits TEXT, related_products TEXT, thumbnail TEXT, image_alt VARCHAR(255), status VARCHAR(30) DEFAULT 'draft'",
        'testimonials' => "id $id, quote TEXT NOT NULL, client_name VARCHAR(255), organization VARCHAR(255), rating INTEGER DEFAULT 5, avatar TEXT, is_active INTEGER DEFAULT 0, sort_order INTEGER DEFAULT 0",
        'client_logos' => "id $id, name VARCHAR(255) NOT NULL, logo_path TEXT, image_alt VARCHAR(255), website_url TEXT, is_active INTEGER DEFAULT 0, sort_order INTEGER DEFAULT 0",
        'pages' => "id $id, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL UNIQUE, excerpt TEXT, content TEXT, thumbnail TEXT, image_alt VARCHAR(255), status VARCHAR(30) DEFAULT 'published'",
        'seo_meta' => "id $id, page_key VARCHAR(255) NOT NULL UNIQUE, meta_title VARCHAR(255), meta_desc TEXT, focus_keyword VARCHAR(255), og_title VARCHAR(255), og_desc TEXT, og_image TEXT, canonical_url TEXT, robots VARCHAR(40) DEFAULT 'index,follow', created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'media' => "id $id, filename VARCHAR(255), original_name VARCHAR(255), path TEXT, image_alt VARCHAR(255), size INTEGER, type VARCHAR(100), uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'admin_users' => "id $id, name VARCHAR(255) NOT NULL, username VARCHAR(100) UNIQUE, email VARCHAR(255) NOT NULL UNIQUE, password_hash VARCHAR(255) NOT NULL, last_login DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'password_resets' => "id $id, admin_id INTEGER NOT NULL, token_hash VARCHAR(128) NOT NULL UNIQUE, otp_hash VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, attempts INTEGER DEFAULT 0, used_at DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'enquiries' => "id $id, name VARCHAR(150) NOT NULL, phone VARCHAR(30) NOT NULL, email VARCHAR(255), product VARCHAR(255), message TEXT NOT NULL, status VARCHAR(30) DEFAULT 'new', email_sent INTEGER DEFAULT 0, created_at DATETIME DEFAULT CURRENT_TIMESTAMP",
        'rate_limits' => "id $id, action_key VARCHAR(255) NOT NULL UNIQUE, attempts INTEGER DEFAULT 0, window_start INTEGER NOT NULL",
    ];
    $jsonColumns=['sections'=>['data'],'products'=>['specs','gallery'],'case_studies'=>['results','products_used'],'use_cases'=>['related_products']];
    $sql = []; foreach ($tables as $name => $columns) {
        if($driver==='mysql')foreach($jsonColumns[$name]??[] as $column)$columns=preg_replace('/\b'.preg_quote($column,'/').' TEXT\b/',$column.' JSON',$columns);
        $sql[] = 'CREATE TABLE IF NOT EXISTS ' . $name . ' (' . $columns . ')' . ($driver === 'mysql' ? ' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci' : '');
    }
    return $sql;
}
function installDatabase(PDO $pdo, string $driver): void
{
    // The settings table is the installation marker; never reseed an existing database.
    if ($driver === 'sqlite' && $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'")->fetchColumn()) { migrateDatabase($pdo, $driver); return; }
    foreach (schemaStatements($driver) as $sql) $pdo->exec($sql);
    if ($pdo->query('SELECT COUNT(*) FROM settings')->fetchColumn()) { migrateDatabase($pdo, $driver); return; }
    require __DIR__ . '/seed.php';
    $pdo->beginTransaction();
    try { seedDatabase($pdo); $pdo->commit(); } catch (Throwable $e) { $pdo->rollBack(); throw $e; }
    migrateDatabase($pdo, $driver);
}

function migrateDatabase(PDO $pdo, string $driver): void
{
    $hasUsername = false;
    if ($driver === 'mysql') { $stmt=$pdo->query("SHOW COLUMNS FROM admin_users LIKE 'username'"); $hasUsername=(bool)$stmt->fetch(); }
    else { foreach ($pdo->query('PRAGMA table_info(admin_users)')->fetchAll() as $column) if (($column['name']??'')==='username') $hasUsername=true; }
    if (!$hasUsername) { $pdo->exec($driver==='mysql' ? 'ALTER TABLE admin_users ADD COLUMN username VARCHAR(100) NULL UNIQUE AFTER name' : 'ALTER TABLE admin_users ADD COLUMN username TEXT'); }
    $hasFaqs = false;
    if ($driver === 'mysql') { $stmt=$pdo->query("SHOW COLUMNS FROM products LIKE 'faqs'"); $hasFaqs=(bool)$stmt->fetch(); }
    else { foreach ($pdo->query('PRAGMA table_info(products)')->fetchAll() as $col) if (($col['name']??'')==='faqs') $hasFaqs=true; }
    if (!$hasFaqs) { $pdo->exec($driver==='mysql' ? 'ALTER TABLE products ADD COLUMN faqs TEXT NULL AFTER features' : 'ALTER TABLE products ADD COLUMN faqs TEXT'); }
    $hasAvatar = false;
    if ($driver === 'mysql') { $stmt=$pdo->query("SHOW COLUMNS FROM testimonials LIKE 'avatar'"); $hasAvatar=(bool)$stmt->fetch(); }
    else { foreach ($pdo->query('PRAGMA table_info(testimonials)')->fetchAll() as $col) if (($col['name']??'')==='avatar') $hasAvatar=true; }
    if (!$hasAvatar) { $pdo->exec($driver==='mysql' ? 'ALTER TABLE testimonials ADD COLUMN avatar TEXT NULL AFTER rating' : 'ALTER TABLE testimonials ADD COLUMN avatar TEXT'); }
    $pdo->exec('CREATE TABLE IF NOT EXISTS password_resets (id '.($driver==='mysql'?'INT PRIMARY KEY AUTO_INCREMENT':'INTEGER PRIMARY KEY AUTOINCREMENT').', admin_id INTEGER NOT NULL, token_hash VARCHAR(128) NOT NULL UNIQUE, otp_hash VARCHAR(255) NOT NULL, expires_at DATETIME NOT NULL, attempts INTEGER DEFAULT 0, used_at DATETIME, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)'.($driver==='mysql'?' ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci':''));
    $rows=$pdo->query("SELECT id,name,email FROM admin_users WHERE username IS NULL OR username = ''")->fetchAll();
    foreach($rows as $row){ $base=strtolower((string)preg_replace('/[^a-z0-9]+/i','-',strtok((string)$row['name'],' '))); if(!$base)$base=strtolower((string)preg_replace('/[^a-z0-9]+/i','-',strtok((string)$row['email'],'@'))); if(!$base)$base='admin'; $candidate=trim($base,'-')?:'admin'; $n=1; while(true){$q=$pdo->prepare('SELECT COUNT(*) FROM admin_users WHERE username = ? AND id <> ?');$q->execute([$candidate,$row['id']]);if(!(int)$q->fetchColumn())break;$candidate=$base.'-'.(++$n);} $pdo->prepare('UPDATE admin_users SET username=? WHERE id=?')->execute([$candidate,$row['id']]); }
    $faq=$pdo->prepare('SELECT COUNT(*) FROM sections WHERE section_key=?'); $faq->execute(['faq']);
    if (!(int)$faq->fetchColumn()) { $max=(int)$pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM sections')->fetchColumn()+1; $data=json_encode(['eyebrow'=>'CLEAR ANSWERS','heading'=>'Questions are part of good care.','body'=>'A few practical answers to help you move forward with confidence.','items'=>[['question'=>'Can you help us choose the right equipment?','answer'=>'Yes. Share your application, flow requirements and delivery location and our team will guide you to a suitable range.'],['question'=>'Do you support hospitals and clinics?','answer'=>'We support clinical spaces with dependable equipment, clear documentation and responsive after-sales guidance.'],['question'=>'How do I request a quote?','answer'=>'Use the enquiry form or WhatsApp link and tell us what you need. We will get back with the next practical step.']]],JSON_UNESCAPED_UNICODE); $stmt=$pdo->prepare('INSERT INTO sections (section_key,title,data,is_active,sort_order) VALUES (?,?,?,?,?)');$stmt->execute(['faq','Frequently asked questions',$data,1,$max]); }
    $homeAdditions=[
        ['process','Our approach',['eyebrow'=>'A CLEARER PATH FORWARD','heading'=>'From your question to a considered choice.','body'=>'A useful equipment conversation starts with understanding the setting, the people using it and the details that matter.','items'=>[['number'=>'01','title'=>'Tell us about your need','body'=>'Share the care setting, application, quantity and any product details you already have.'],['number'=>'02','title'=>'Explore suitable options','body'=>'We help you review available products, configurations and compatibility questions.'],['number'=>'03','title'=>'Decide with clarity','body'=>'Get clear information to help you choose the next step for your requirements.']]]],
        ['guidance','Practical guidance',['eyebrow'=>'THOUGHTFUL SUPPORT','heading'=>'Small details make equipment planning easier.','body'=>'Our team can help you ask the right questions before you move ahead.','items'=>[['icon'=>'hospital-line','title'=>'Care setting','body'=>'Describe where and how the equipment will be used.'],['icon'=>'tools-line','title'=>'Compatibility','body'=>'Check fittings, accessories and configuration requirements with the team.'],['icon'=>'file-list-3-line','title'=>'Product information','body'=>'Request available specifications and documentation for the options you are considering.'],['icon'=>'customer-service-2-line','title'=>'Local conversation','body'=>'Speak directly with our Agra team about your equipment enquiry.']]]],
        ['partnership','A local partner',['eyebrow'=>'ROOTED IN AGRA','heading'=>'Equipment matters. A helpful conversation does too.','body'=>'Shree Ji Gas Industries connects care teams with medical oxygen equipment, surgical instruments, gas accessories and hospital essentials. Tell us what you are planning and we will help you explore the available options.','button_label'=>'Talk to our team','button_url'=>'contact','secondary_label'=>'Explore products','secondary_url'=>'products']]
    ];
    $added=false;$exists=$pdo->prepare('SELECT COUNT(*) FROM sections WHERE section_key=?');$insertSection=$pdo->prepare('INSERT INTO sections (section_key,title,data,is_active,sort_order) VALUES (?,?,?,?,?)');$position=(int)$pdo->query('SELECT COALESCE(MAX(sort_order),0) FROM sections')->fetchColumn()+1;
    foreach($homeAdditions as [$key,$title,$data]){$exists->execute([$key]);if(!(int)$exists->fetchColumn()){$insertSection->execute([$key,$title,json_encode($data,JSON_UNESCAPED_UNICODE),1,$position++]);$added=true;}}
    $positions=$pdo->query("SELECT section_key,sort_order FROM sections WHERE section_key IN ('faq','cta','contact')")->fetchAll();$order=[];foreach($positions as $row)$order[$row['section_key']]=(int)$row['sort_order'];
    if($added||(isset($order['faq'],$order['cta'])&&$order['faq']>$order['cta'])||(isset($order['contact'],$order['cta'])&&$order['cta']<$order['contact'])){$canonical=['hero'=>0,'stats'=>1,'categories'=>2,'products'=>3,'why'=>4,'use_cases'=>5,'case_studies'=>6,'blog'=>7,'process'=>8,'guidance'=>9,'partnership'=>10,'testimonials'=>11,'faq'=>12,'logos'=>13,'contact'=>14,'cta'=>15,'footer'=>16];$update=$pdo->prepare('UPDATE sections SET sort_order=? WHERE section_key=?');foreach($canonical as $key=>$position)$update->execute([$position,$key]);}
}
