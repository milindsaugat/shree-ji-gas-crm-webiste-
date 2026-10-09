<?php
final class AdminController
{
    private array $resources;
    public function __construct(){ $this->resources=require ROOT.'/config/resources.php'; }
    private function render(string $template,array $data=[]):void{ view('admin/'.$template,$data+['resources'=>$this->resources],'admin'); }
    public function dispatch(string $path):void {
        header('Cache-Control: no-store, private');header('X-Robots-Tag: noindex, nofollow');
        if($path==='admin/login'||$path==='admin/setup'){ $this->login($path==='admin/setup');return; }
        if($path==='admin/forgot'||$path==='admin/reset'){ $this->resetPassword($path);return; }
        Auth::guard();
        if($_SERVER['REQUEST_METHOD']==='POST')checkCsrf();
        if($path==='admin/logout'&&$_SERVER['REQUEST_METHOD']==='POST'){unset($_SESSION['admin']);session_regenerate_id(true);redirect('admin/login');}
        if($path==='admin'){ $this->dashboard();return; }
        if($path==='admin/sections'||str_starts_with($path,'admin/sections/')){$this->sections(substr($path,15));return;}
        if($path==='admin/pages/about'){$this->aboutPage();return;}
        if($path==='admin/preview'){$this->preview();return;}
        if($path==='admin/settings'||str_starts_with($path,'admin/settings/')){$this->settings($path);return;}
        if($path==='admin/password'){$this->password();return;}
        if($path==='admin/email-change'||str_starts_with($path,'admin/email-change/')){$this->emailChange($path);return;}
        if($path==='admin/media'){$this->media();return;}
        if($path==='admin/enquiries'){$this->enquiries();return;}
        if($path==='admin/products'||$path==='admin/products/catalogue'||$path==='admin/catalogue'){$this->cataloguePage();return;}
        if(preg_match('#^admin/content/([a-z_]+)(?:/(new|catalogue|\d+))?$#',$path,$m)&&isset($this->resources[$m[1]])){$this->content($m[1],$m[2]??null);return;}
        http_response_code(404);$this->render('not-found',['title'=>'Page not found']);
    }
    private function login(bool $setup):void {
        if(Auth::user())redirect('admin');
        $hasAdmin=Auth::hasAdmin();$canSetup=Auth::localSetup()||getenv('APP_SETUP_TOKEN');$error='';
        if($_SERVER['REQUEST_METHOD']==='POST'){
            checkCsrf();
            if(!RateLimit::allow('login',10,900))$error='Too many attempts. Try again in 15 minutes.';
            elseif($setup&&!$hasAdmin&&$canSetup){
                $email=trim((string)($_POST['email']??''));$username=trim((string)($_POST['username']??''));$name=trim((string)($_POST['name']??''));$password=(string)($_POST['password']??'');
                if(!Auth::localSetup()&&!hash_equals((string)getenv('APP_SETUP_TOKEN'),(string)($_POST['setup_token']??'')))$error='The installation token is invalid.';
                elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($email)>254||!preg_match('/^[a-zA-Z0-9._-]{3,100}$/',$username)||strlen($name)<2||strlen($name)>150||strlen($password)<12||strlen($password)>72)$error='Enter your name, a username, a valid email, and a password between 12 and 72 characters.';
                else {try { Database::get()->prepare('INSERT INTO admin_users (name,username,email,password_hash) VALUES (?,?,?,?)')->execute([$name,$username,$email,password_hash($password,PASSWORD_BCRYPT)]);Auth::login($email,$password);flash('Your admin account is ready. Welcome to your workspace.');redirect('admin'); } catch(Throwable $e) { $error='That username or email is already in use.'; }}
            }elseif(Auth::login(trim((string)($_POST['identifier']??$_POST['email']??'')),(string)($_POST['password']??'')))redirect('admin');
            else $error='Username/email or password is incorrect.';
        }
        $this->render('login',['title'=>$setup?'Create admin account':'Welcome back','error'=>$error,'setup'=>$setup&&!$hasAdmin&&$canSetup,'hasAdmin'=>$hasAdmin,'canSetup'=>$canSetup]);
    }
    private function resetPassword(string $path):void {
        if(Auth::user()) { redirect('admin'); }
        $error=''; $notice=''; $token=trim((string)($_GET['token']??$_POST['token']??''));
        if($path==='admin/forgot') {
            if($_SERVER['REQUEST_METHOD']==='POST') { checkCsrf(); $identifier=trim((string)($_POST['identifier']??''));
                if(!RateLimit::allow('password-reset',5,900)) $error='Too many requests. Try again in 15 minutes.';
                elseif($identifier==='') $error='Enter your username or email address.';
                else { $admin=Auth::findByIdentifier($identifier); if($admin) { $rawToken=bin2hex(random_bytes(32)); $otp=(string)random_int(100000,999999); Database::get()->prepare('DELETE FROM password_resets WHERE admin_id = ?')->execute([$admin['id']]); Database::get()->prepare('INSERT INTO password_resets (admin_id,token_hash,otp_hash,expires_at) VALUES (?,?,?,?)')->execute([$admin['id'],hash('sha256',$rawToken),password_hash($otp,PASSWORD_DEFAULT),date('Y-m-d H:i:s',time()+OTP_EXPIRY_MINUTES*60)]); $link=url('admin/reset').'?token='.rawurlencode($rawToken); try { $tpl=EmailTemplate::otp($admin['name']??'Administrator',$otp,'You requested a password reset for your Shree Ji Gas admin account. Your one-time verification code is below.',$link); SmtpMailer::send($admin['email'],$tpl['subject'],$tpl['plain'],$tpl['html']); $notice='If an account matches those details, a verification code has been sent to its email address.'; } catch(Throwable $e) { $error=$e->getMessage(); } } else $notice='If an account matches those details, a verification code has been sent to its email address.'; }
            }
            $this->render('forgot',['title'=>'Forgot password','error'=>$error,'notice'=>$notice]); return;
        }
        $row=null; if($token!=='') { $stmt=Database::get()->prepare('SELECT * FROM password_resets WHERE token_hash=? AND used_at IS NULL AND expires_at > ?');$stmt->execute([hash('sha256',$token),date('Y-m-d H:i:s')]);$row=$stmt->fetch()?:null; }
        if(!$row) $error='This reset link is invalid or has expired. Request a new code.';
        elseif($_SERVER['REQUEST_METHOD']==='POST') { checkCsrf(); $otp=trim((string)($_POST['otp']??''));$password=(string)($_POST['password']??'');$confirm=(string)($_POST['confirm_password']??'');
            if((int)$row['attempts']>=5) $error='Too many incorrect codes. Request a new code.';
            else { Database::get()->prepare('UPDATE password_resets SET attempts=attempts+1 WHERE id=?')->execute([$row['id']]); if(!preg_match('/^\d{6}$/',$otp)||!password_verify($otp,$row['otp_hash']))$error='The verification code is incorrect.'; elseif(strlen($password)<12||strlen($password)>72||$password!==$confirm)$error='Use 12–72 characters and make sure both passwords match.'; else { Database::get()->prepare('UPDATE admin_users SET password_hash=? WHERE id=?')->execute([password_hash($password,PASSWORD_BCRYPT),$row['admin_id']]);Database::get()->prepare('UPDATE password_resets SET used_at=? WHERE id=?')->execute([date('Y-m-d H:i:s'),$row['id']]);flash('Password reset complete. You can sign in now.');redirect('admin/login'); } }
        }
        $this->render('reset',['title'=>'Reset password','error'=>$error,'token'=>$token,'valid'=>(bool)$row]);
    }
    private function dashboard():void {
        $tables=['products','blog_posts','enquiries','media','categories','testimonials'];
        $counts=[];
        foreach($tables as $table){
            try { $counts[$table]=(int)Database::get()->query('SELECT COUNT(*) FROM '.$table)->fetchColumn(); }
            catch(Throwable $e) { $counts[$table]=0; }
        }
        $unreadCount=0;
        try { $unreadCount=(int)Database::get()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn(); }
        catch(Throwable $e) {}
        $this->render('dashboard',[
            'title'=>'Your workspace',
            'counts'=>$counts,
            'unreadCount'=>$unreadCount,
            'enquiries'=>array_slice((new ContentModel('enquiries'))->all(),0,6)
        ]);
    }
    private function sections(string $key):void {
        $key=trim($key,'/');
        if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['order'])){
            $keys=json_decode($_POST['order'],true);if(!is_array($keys))jsonResponse(['ok'=>false],422);
            $stmt=Database::get()->prepare('UPDATE sections SET sort_order = ? WHERE section_key = ?');foreach($keys as $index=>$name)$stmt->execute([$index,$name]);jsonResponse(['ok'=>true,'message'=>'Section order saved.']);
        }
        $allSections = array_values(array_filter(Section::all(), fn($s) => !in_array($s['section_key'], ['contact', 'partnership'], true)));
        $counts = [
            'case_studies' => (int)Database::get()->query('SELECT COUNT(*) FROM case_studies WHERE status = "published"')->fetchColumn(),
            'use_cases' => (int)Database::get()->query('SELECT COUNT(*) FROM use_cases WHERE status = "published"')->fetchColumn(),
            'blog_posts' => (int)Database::get()->query('SELECT COUNT(*) FROM blog_posts WHERE status = "published"')->fetchColumn(),
            'products' => (int)Database::get()->query('SELECT COUNT(*) FROM products WHERE is_featured = 1 AND status = "published"')->fetchColumn(),
            'categories' => (int)Database::get()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
            'testimonials' => (int)Database::get()->query('SELECT COUNT(*) FROM testimonials WHERE is_active = 1')->fetchColumn(),
            'client_logos' => (int)Database::get()->query('SELECT COUNT(*) FROM client_logos WHERE is_active = 1')->fetchColumn(),
        ];
        if(!$key || in_array($key, ['contact', 'partnership'], true)){
            $firstKey = !empty($allSections[0]['section_key']) ? $allSections[0]['section_key'] : 'hero';
            redirect('admin/sections/'.$firstKey);
            return;
        }
        $section=Section::find($key);if(!$section){http_response_code(404);$this->render('not-found',['title'=>'Section not found']);return;}
        $error='';
        if($_SERVER['REQUEST_METHOD']==='POST'){
            try{
                $fields=$this->sectionFields($_POST['fields']??[],$section['fields']);
                Section::save($key,$section['title'],$fields,isset($_POST['is_active']),(int)($_POST['sort_order']??$section['sort_order']));
                if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>true,'message'=>'Changes saved. Your website is up to date.']);
                flash('Section saved.');redirect('admin/sections/'.$key);
            }catch(Throwable $e){$error=$e->getMessage();if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>false,'message'=>$error],422);}
        }
        $this->render('section-edit',['title'=>$section['title'],'section'=>$section,'allSections'=>$allSections,'counts'=>$counts,'error'=>$error]);
    }
    private function aboutPage(): void {
        $model = new ContentModel('pages');
        $page = $model->bySlug('about');
        if (!$page) {
            http_response_code(404);
            $this->render('not-found', ['title' => 'About page not found']);
            return;
        }
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $title = trim((string)($_POST['title'] ?? $page['title']));
                $excerpt = trim((string)($_POST['excerpt'] ?? $page['excerpt']));
                $content = (string)($_POST['content'] ?? $page['content']);
                $thumbnail = trim((string)($_POST['thumbnail'] ?? $page['thumbnail']));
                $imageAlt = trim((string)($_POST['image_alt'] ?? $page['image_alt']));
                $status = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : (isset($_POST['is_active']) ? ($_POST['is_active'] ? 'published' : 'draft') : $page['status']);

                if (mb_strlen($title) < 2) throw new RuntimeException('Page title is required.');
                if ($thumbnail) $this->validateAsset($thumbnail);

                $model->save([
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => $content,
                    'thumbnail' => $thumbnail,
                    'image_alt' => $imageAlt,
                    'status' => $status
                ], (int)$page['id']);

                if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                    jsonResponse(['ok' => true, 'message' => 'About us page saved. Your website is up to date.']);
                }
                flash('About us page saved.');
                redirect('admin/pages/about');
            } catch (Throwable $e) {
                $error = $e->getMessage();
                if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                    jsonResponse(['ok' => false, 'message' => $error], 422);
                }
            }
        }
        $allSections = Section::all();
        $this->render('about-edit', [
            'title' => 'About us',
            'page' => $page,
            'allSections' => $allSections,
            'error' => $error
        ]);
    }
    private function sectionFields(array $input,array $existing):array{
        $data=$existing;
        foreach($existing as $key=>$value){
            if(is_array($value)){
                $items=json_decode((string)($input[$key]??json_encode($value)),true,32,JSON_THROW_ON_ERROR);
                if(!is_array($items)||count($items)>40)throw new RuntimeException('Use at most 40 items per list.');
                foreach($items as $item)if(!is_array($item)||array_filter($item,fn($v)=>!is_scalar($v)&&$v!==null))throw new RuntimeException('List items must contain text values.');
                $data[$key]=$items;
            }else{$data[$key]=mb_substr(trim((string)($input[$key]??$value)),0,10000);if(str_contains($key,'url'))$this->validateUrl($data[$key],true);if($key==='image')$this->validateAsset($data[$key]);}
        }
        $bg=$input['background']??'';if(in_array($bg,['','#FFFFFF','#EAF7FA','#F5FCFD','#1B3D6F','#2E8FA8'],true))$data['background']=$bg;
        return $data;
    }
    private function preview():void {
        if (($_GET['page'] ?? '') === 'about') {
            $pageModel = new ContentModel('pages');
            $page = $pageModel->bySlug('about');
            if (!$page) { http_response_code(404); exit; }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['title'])) $page['title'] = trim((string)$_POST['title']);
                if (isset($_POST['excerpt'])) $page['excerpt'] = trim((string)$_POST['excerpt']);
                if (isset($_POST['content'])) $page['content'] = (string)$_POST['content'];
                if (isset($_POST['thumbnail'])) $page['thumbnail'] = trim((string)$_POST['thumbnail']);
                if (isset($_POST['image_alt'])) $page['image_alt'] = trim((string)$_POST['image_alt']);
            }
            header('Content-Type: text/html; charset=utf-8');
            echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
            echo '<link rel="stylesheet" href="'.asset('vendor/fonts/fonts.css').'">';
            echo '<link rel="stylesheet" href="'.asset('vendor/remixicon.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/main.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/refinements.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/storytelling.css').'">';
            echo '<style>
                html, body { background: #ffffff; margin: 0; padding: 0; }
                .container { max-width: 1600px; width: 100%; margin: 0 auto; }
                /* In-place Editable Text */
                .visual-inline-editable, [contenteditable="true"] {
                    outline: 1px dashed transparent;
                    outline-offset: 4px;
                    cursor: text;
                    transition: all .15s ease;
                    border-radius: 2px;
                }
                .visual-inline-editable:hover, [contenteditable="true"]:hover {
                    outline-color: #2e8fa8 !important;
                    background: rgba(46,143,168,0.06);
                }
                .visual-inline-editable:focus, [contenteditable="true"]:focus, .visual-inline-editable.is-editing {
                    outline: 2px solid #2e8fa8 !important;
                    background: rgba(46,143,168,0.09);
                }
                /* Visual Image Container & Overlay */
                .visual-image-container { position: relative; }
                .visual-image-overlay {
                    position: absolute; inset: 0; background: rgba(27,61,111,0.85);
                    display: none; place-items: center; border-radius: 6px; color: white; z-index: 30; backdrop-filter: blur(2px);
                }
                .visual-image-container:hover .visual-image-overlay, .visual-image-overlay.is-dragover { display: grid; }
                .visual-image-prompt { text-align: center; padding: 15px; }
                .visual-image-prompt span { display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; }
                .visual-img-btn-row { display: flex; gap: 8px; justify-content: center; }
                .btn-img-action { padding: 5px 10px; border-radius: 4px; border: 1px solid white; background: white; color: #1b3d6f; font-size: 10px; font-weight: 600; cursor: pointer; }
                .btn-img-delete { background: #893e36; border-color: #893e36; color: white; }
            </style></head><body>';
            require ROOT.'/views/pages/about.php';
            echo '<script src="'.asset('admin/js/preview.js').'"></script></body></html>';
            return;
        }
        if (isset($_GET['catalogue'])) {
            $catTitle = setting('catalogue_title') ?: 'Medical & Gas Equipment';
            $catEyebrow = setting('catalogue_eyebrow') ?: 'EQUIPMENT FOR BETTER CARE';
            $catDesc = setting('catalogue_desc') ?: 'Explore the essentials. Find your fit. Let’s discuss the details.';
            $catGuideTitle = setting('catalogue_guidance_title') ?: 'A little guidance?';
            $catGuideDesc = setting('catalogue_guidance_desc') ?: 'Our team can help you explore the right options.';
            $catGuideBtn = setting('catalogue_guidance_btn') ?: 'Talk to us';

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['catalogue_title'])) $catTitle = trim((string)$_POST['catalogue_title']);
                if (isset($_POST['catalogue_eyebrow'])) $catEyebrow = trim((string)$_POST['catalogue_eyebrow']);
                if (isset($_POST['catalogue_desc'])) $catDesc = trim((string)$_POST['catalogue_desc']);
                if (isset($_POST['catalogue_guidance_title'])) $catGuideTitle = trim((string)$_POST['catalogue_guidance_title']);
                if (isset($_POST['catalogue_guidance_desc'])) $catGuideDesc = trim((string)$_POST['catalogue_guidance_desc']);
                if (isset($_POST['catalogue_guidance_btn'])) $catGuideBtn = trim((string)$_POST['catalogue_guidance_btn']);
            }

            $products = (new ContentModel('products'))->all(true);
            $categories = (new ContentModel('categories'))->all();
            $category = null;
            $title = $catTitle;

            header('Content-Type: text/html; charset=utf-8');
            echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
            echo '<link rel="stylesheet" href="'.asset('vendor/fonts/fonts.css').'">';
            echo '<link rel="stylesheet" href="'.asset('vendor/remixicon.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/main.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/refinements.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/storytelling.css').'">';
            echo '<style>
                html, body { background: #ffffff; margin: 0; padding: 0; }
                .container { max-width: 1600px; width: 100%; margin: 0 auto; }
                .visual-inline-editable, [contenteditable="true"] {
                    outline: 1px dashed transparent;
                    outline-offset: 4px;
                    cursor: text;
                    transition: all .15s ease;
                    border-radius: 2px;
                }
                .visual-inline-editable:hover, [contenteditable="true"]:hover {
                    outline-color: #2e8fa8 !important;
                    background: rgba(46,143,168,0.06);
                }
                .visual-inline-editable:focus, [contenteditable="true"]:focus, .visual-inline-editable.is-editing {
                    outline: 2px solid #2e8fa8 !important;
                    background: rgba(46,143,168,0.09);
                }
            </style></head><body class="catalogue-preview-body">';
            require ROOT.'/views/product/index.php';
            echo '<script src="'.asset('admin/js/preview.js').'"></script></body></html>';
            return;
        }
        if (isset($_GET['product'])) {
            $prodId = (int)$_GET['product'];
            $prodModel = new ContentModel('products');
            $product = $prodId > 0 ? $prodModel->find($prodId) : null;
            if (!$product) {
                $product = $prodModel->bySlug((string)$_GET['product']);
            }
            if (!$product) {
                $allP = $prodModel->all();
                $product = $allP[0] ?? null;
            }
            if (!$product) { http_response_code(404); exit; }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['name'])) $product['name'] = trim((string)$_POST['name']);
                if (isset($_POST['short_desc'])) $product['short_desc'] = trim((string)$_POST['short_desc']);
                if (isset($_POST['description'])) $product['description'] = (string)$_POST['description'];
                if (isset($_POST['features'])) $product['features'] = (string)$_POST['features'];
                if (isset($_POST['applications'])) $product['applications'] = (string)$_POST['applications'];
                if (isset($_POST['specs'])) $product['specs'] = is_array($_POST['specs']) ? json_encode($_POST['specs']) : (string)$_POST['specs'];
                if (isset($_POST['tags'])) $product['tags'] = (string)$_POST['tags'];
                if (isset($_POST['mfc_date'])) $product['mfc_date'] = trim((string)$_POST['mfc_date']);
                if (isset($_POST['exp_date'])) $product['exp_date'] = trim((string)$_POST['exp_date']);
                if (isset($_POST['thumbnail'])) $product['thumbnail'] = trim((string)$_POST['thumbnail']);
                if (isset($_POST['image_alt'])) $product['image_alt'] = trim((string)$_POST['image_alt']);
                if (isset($_POST['badge_label'])) $product['badge_label'] = trim((string)$_POST['badge_label']);
                if (isset($_POST['brochure_pdf'])) $product['brochure_pdf'] = trim((string)$_POST['brochure_pdf']);
                if (isset($_POST['cat_id'])) $product['cat_id'] = (int)$_POST['cat_id'];
                if (isset($_POST['faqs'])) $product['faqs'] = (string)$_POST['faqs'];
            }

            $category = (new ContentModel('categories'))->find((int)$product['cat_id']);
            $related = array_values(array_filter($prodModel->all(true), fn($p) => (int)$p['id'] !== (int)$product['id'] && (int)$p['cat_id'] === (int)$product['cat_id']));

            header('Content-Type: text/html; charset=utf-8');
            echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
            echo '<link rel="stylesheet" href="'.asset('vendor/fonts/fonts.css').'">';
            echo '<link rel="stylesheet" href="'.asset('vendor/remixicon.css').'">';
            echo '<link rel="stylesheet" href="'.asset('vendor/swiper-bundle.min.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/main.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/refinements.css').'">';
            echo '<link rel="stylesheet" href="'.asset('css/storytelling.css').'">';
            echo '<style>
                html, body { background: #ffffff; margin: 0; padding: 0; }
                .container { max-width: 1440px; width: 100%; margin: 0 auto; }
                /* In-place Editable Text */
                .visual-inline-editable, [contenteditable="true"] {
                    outline: 1px dashed transparent;
                    outline-offset: 4px;
                    cursor: text;
                    transition: all .15s ease;
                    border-radius: 2px;
                }
                .visual-inline-editable:hover, [contenteditable="true"]:hover {
                    outline-color: #2e8fa8 !important;
                    background: rgba(46,143,168,0.06);
                }
                .visual-inline-editable:focus, [contenteditable="true"]:focus, .visual-inline-editable.is-editing {
                    outline: 2px solid #2e8fa8 !important;
                    background: rgba(46,143,168,0.09);
                }
                /* Visual Image Container & Overlay */
                .visual-image-container { position: relative; }
                .visual-image-overlay {
                    position: absolute; inset: 0; background: rgba(27,61,111,0.85);
                    display: none; place-items: center; border-radius: 6px; color: white; z-index: 30; backdrop-filter: blur(2px);
                }
                .visual-image-container:hover .visual-image-overlay, .visual-image-overlay.is-dragover { display: grid; }
                .visual-image-prompt { text-align: center; padding: 15px; }
                .visual-image-prompt span { display: block; font-size: 12px; font-weight: 600; margin-bottom: 8px; }
                .visual-img-btn-row { display: flex; gap: 8px; justify-content: center; }
                .btn-img-action { padding: 5px 10px; border-radius: 4px; border: 1px solid white; background: white; color: #1b3d6f; font-size: 10px; font-weight: 600; cursor: pointer; }
            </style></head><body class="product-preview-body">';
            require ROOT.'/views/product/detail.php';
            echo '<script src="'.asset('vendor/swiper-bundle.min.js').'"></script>';
            echo '<script src="'.asset('admin/js/preview.js').'"></script></body></html>';
            return;
        }
        $section=Section::find((string)($_GET['section']??''));if(!$section){http_response_code(404);exit;}
        if($_SERVER['REQUEST_METHOD']==='POST')$section['fields']=$this->sectionFields($_POST['fields']??[],$section['fields']);
        $s=$section['fields'];header('Content-Type: text/html; charset=utf-8');
        
        $recent = static function(string $type, int $limit): array {
            $rows = (new ContentModel($type))->all(true);
            usort($rows, static fn($a,$b) => (int)$b['id'] <=> (int)$a['id']);
            return array_slice($rows, 0, $limit);
        };
        $categories = allCategories();
        $products = $recent('products', 6);
        $posts = $recent('blog_posts', 3);
        $useCases = $recent('use_cases', 3);
        $caseStudies = $recent('case_studies', 4);
        $testimonials = array_values(array_filter((new ContentModel('testimonials'))->all(), fn($t) => (bool)$t['is_active']));
        $logos = array_values(array_filter((new ContentModel('client_logos'))->all(), fn($l) => (bool)$l['is_active']));

        $rawBg = trim((string)($s['background'] ?? ''));
        $sectionKey = $section['section_key'];
        $hasCustomBg = (bool)preg_match('/^#[0-9a-fA-F]{3,6}$/', $rawBg);
        $defaultBg = in_array($sectionKey, ['stats', 'cta'], true) ? '#1B3D6F' : '#ffffff';
        $resolvedBg = $hasCustomBg ? $rawBg : $defaultBg;

        echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">';
        echo '<link rel="stylesheet" href="'.asset('vendor/fonts/fonts.css').'">';
        echo '<link rel="stylesheet" href="'.asset('vendor/remixicon.css').'">';
        echo '<link rel="stylesheet" href="'.asset('vendor/swiper-bundle.min.css').'">';
        echo '<link rel="stylesheet" href="'.asset('css/main.css').'">';
        echo '<link rel="stylesheet" href="'.asset('css/refinements.css').'">';
        echo '<link rel="stylesheet" href="'.asset('css/storytelling.css').'">';
        echo '<style>
            html, body {
                background: '.$resolvedBg.' !important;
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                min-height: 100% !important;
                box-sizing: border-box !important;
            }
            *, *::before, *::after { box-sizing: border-box !important; }
            
            /* Admin Canvas Section Edge Coverage & Balanced Padding */
            .home-section {
                width: 100% !important;
                min-height: auto !important;
                box-sizing: border-box !important;
                padding: 32px 48px !important;
                background: '.$resolvedBg.' !important;
                margin: 0 !important;
            }
            .section-stats {
                padding: 24px 48px !important;
            }
            .section-hero {
                padding: 36px 48px !important;
            }
            
            /* Container Edge Protection (Guarantees cards never touch or get clipped at edges) */
            .container {
                width: 100% !important;
                max-width: 1440px !important;
                margin: 0 auto !important;
                padding-left: 24px !important;
                padding-right: 24px !important;
                box-sizing: border-box !important;
            }
            
            /* Grid and cards padding & clean spacing */
            .stats-grid {
                width: 100% !important;
                max-width: 1440px !important;
                box-sizing: border-box !important;
                padding: 0 !important;
                margin: 0 auto !important;
                gap: 20px !important;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)) !important;
                overflow: visible !important;
            }
            .stat {
                box-sizing: border-box !important;
                min-width: 0 !important;
                margin: 0 !important;
                padding: 20px 24px !important;
                border-radius: 8px !important;
                background: #ffffff !important;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08) !important;
                border: 1px solid rgba(255, 255, 255, 0.6) !important;
                transition: transform .2s ease, box-shadow .2s ease !important;
            }
            .stat:hover {
                transform: translateY(-2px) !important;
                box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
            }
            .stat strong {
                color: #1b3d6f !important;
                font-size: 26px !important;
                font-family: var(--font-heading) !important;
            }
            .stat span {
                color: #40536a !important;
                font-size: 11px !important;
                font-weight: 500 !important;
                margin-top: 3px !important;
            }
            .stat i {
                color: #2e8fa8 !important;
                font-size: 30px !important;
            }
            
            /* In-place Editable Text */
            .visual-inline-editable, [contenteditable="true"] {
                outline: 1px dashed transparent;
                outline-offset: 4px;
                cursor: text;
                transition: all .15s ease;
                border-radius: 2px;
            }
            .visual-inline-editable:hover, [contenteditable="true"]:hover {
                outline-color: #2e8fa8 !important;
                background: rgba(46,143,168,0.06);
            }
            .visual-inline-editable:focus, [contenteditable="true"]:focus, .visual-inline-editable.is-editing {
                outline: 2px solid #2e8fa8 !important;
                background: rgba(46,143,168,0.09);
            }
            
            /* Card Hover Actions */
            .visual-card-item {
                position: relative;
                transition: box-shadow .2s ease;
            }
            .visual-card-actions {
                position: absolute;
                top: -10px;
                right: 8px;
                display: none;
                background: #1b3d6f;
                border-radius: 14px;
                padding: 3px 8px;
                gap: 6px;
                align-items: center;
                box-shadow: 0 4px 12px rgba(0,0,0,0.25);
                z-index: 50;
            }
            .visual-card-item:hover .visual-card-actions {
                display: flex;
            }
            .visual-card-actions button {
                background: none;
                border: 0;
                color: white;
                font-size: 13px;
                cursor: pointer;
                padding: 2px 5px;
                line-height: 1;
            }
            .visual-card-actions button:hover {
                color: #eaf7fa;
            }
            .visual-card-actions .btn-card-delete {
                color: #f7a8a0;
                font-size: 16px;
            }
            .visual-card-actions .btn-card-delete:hover {
                color: #ff4d4d;
            }
            
            /* Add Item Button */
            .visual-add-card-wrap {
                width: 100% !important;
                margin-top: 28px !important;
                display: flex !important;
                justify-content: center !important;
            }
            .visual-add-card-btn {
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                gap: 8px !important;
                max-width: 440px !important;
                width: 100% !important;
                margin: 0 auto !important;
                padding: 13px 24px !important;
                background: rgba(255, 255, 255, 0.15) !important;
                border: 2px dashed rgba(255, 255, 255, 0.6) !important;
                color: #ffffff !important;
                font-weight: 600 !important;
                font-size: 13px !important;
                border-radius: 8px !important;
                cursor: pointer !important;
                text-align: center !important;
                transition: all .2s ease !important;
                backdrop-filter: blur(4px) !important;
            }
            .section-light .visual-add-card-btn {
                background: rgba(46, 143, 168, 0.08) !important;
                border: 2px dashed #2e8fa8 !important;
                color: #1b3d6f !important;
            }
            .visual-add-card-btn:hover {
                background: #2e8fa8 !important;
                border-color: #2e8fa8 !important;
                color: #ffffff !important;
                transform: translateY(-1px) !important;
            }
            
            /* Visual Image Container & Overlay */
            .visual-image-container {
                position: relative;
            }
            .visual-image-overlay {
                position: absolute;
                inset: 0;
                background: rgba(27,61,111,0.85);
                display: none;
                place-items: center;
                border-radius: 6px;
                color: white;
                z-index: 30;
                backdrop-filter: blur(2px);
            }
            .visual-image-container:hover .visual-image-overlay,
            .visual-image-overlay.is-dragover {
                display: grid;
            }
            .visual-image-prompt {
                text-align: center;
                padding: 15px;
            }
            .visual-image-prompt span {
                display: block;
                font-size: 12px;
                font-weight: 600;
                margin-bottom: 8px;
            }
            .visual-img-btn-row {
                display: flex;
                gap: 8px;
                justify-content: center;
            }
            .btn-img-action {
                padding: 5px 10px;
                border-radius: 4px;
                border: 1px solid white;
                background: white;
                color: #1b3d6f;
                font-size: 10px;
                font-weight: 600;
                cursor: pointer;
            }
            .btn-img-delete {
                background: #893e36;
                border-color: #893e36;
                color: white;
            }
        </style></head><body>';
        if($section['section_key']==='footer'){$footerPreview=$section;require ROOT.'/views/partials/footer.php';}else require ROOT.'/views/home/section.php';
        echo '<script src="'.asset('vendor/swiper-bundle.min.js').'"></script>';
        echo '<script src="'.asset('admin/js/preview.js').'"></script></body></html>';
    }
    private function content(string $type,?string $id):void{
        if($type==='products'&&$id==='catalogue'){$this->cataloguePage();return;}
        $config=$this->resources[$type];$model=new ContentModel($type);$record=$id&&$id!=='new'?$model->find((int)$id):[];$error='';
        if($id&&$id!=='new'&&!$record){http_response_code(404);$this->render('not-found',['title'=>'Content not found']);return;}
        if($id&&$id!=='new'&&$type==='pages'){
            if(($record['slug']??'')==='about'){redirect('admin/pages/about');return;}
            if(($record['slug']??'')==='contact'){flash('Contact details and forms are configured in Site Settings and the contact view.');redirect('admin/content/pages');return;}
        }
        if($_SERVER['REQUEST_METHOD']==='POST'){
            if(($_POST['action']??'')==='delete'&&$id&&$id!=='new'){
                if($type==='pages' && in_array($record['slug'] ?? '', ['privacy','terms','compliance','shipping-policy'])){flash('Standard legal and policy pages can be edited or unpublished, but cannot be deleted.','error');redirect('admin/content/'.$type);}
                if($type==='seo_meta'){flash('Core website SEO pages are permanent system routes and cannot be deleted.','error');redirect('admin/content/seo_meta');}
                if(in_array($type,['categories','blog_categories'])){ $target=$type==='categories'?'products':'blog_posts';$stmt=Database::get()->prepare('SELECT COUNT(*) FROM '.$target.' WHERE cat_id = ?');$stmt->execute([(int)$id]);if($stmt->fetchColumn()){flash('Move the items in this category before deleting it.','error');redirect('admin/content/'.$type);}}
                $model->delete((int)$id);flash($config['singular'].' deleted.');redirect('admin/content/'.$type);
            }
            try{
                $data=$this->validateRecord($_POST,$config['fields']);
                if($type==='pages'&&$record&&in_array($record['slug'],['privacy','terms','compliance','shipping-policy'])&&$data['slug']!==$record['slug'])throw new RuntimeException('Standard page URLs are fixed for system routing. Edit the title and content instead.');
                if($type==='seo_meta'&&$record&&isset($record['page_key'])&&$data['page_key']!==$record['page_key'])throw new RuntimeException('Core page URLs are fixed. Customize the meta titles, descriptions and keywords instead.');
                if($type==='blog_posts'&&empty($data['published_at']))$data['published_at']=date('Y-m-d H:i:s');
                Database::get()->beginTransaction();
                $saved=$model->save($data,$id&&$id!=='new'?(int)$id:null);
                $seoTargetKey=$type==='pages'?'/'.$data['slug']:($config['route']?'/'.$config['route'].'/'.$data['slug']:null);
                if(isset($_POST['seo'])&&$seoTargetKey)$this->saveInlineSeo($seoTargetKey,$_POST['seo']);
                Database::get()->commit();
                if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>true,'id'=>$saved,'message'=>$config['singular'].' saved.']);
                flash($config['singular'].' saved.');redirect('admin/content/'.$type.'/'.$saved);
            }catch(Throwable $e){if(Database::get()->inTransaction())Database::get()->rollBack();$error=$e instanceof PDOException?'This URL or page key is already in use. Please choose a unique value.':$e->getMessage();if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>false,'message'=>$error],422);$record=array_merge($record?:[],array_intersect_key($_POST,$config['fields']));}
        }
        if(!$id){
            if($type==='products' && (!isset($_GET['view']) || $_GET['view']!=='list')){
                $first = $model->all();
                $targetId = !empty($first[0]['id']) ? (int)$first[0]['id'] : 'new';
                redirect('admin/content/products/'.$targetId);
                return;
            }
            if($type==='categories'){
                $rows = Database::get()->query('SELECT c.*, COUNT(p.id) AS product_count FROM categories c LEFT JOIN products p ON p.cat_id = c.id GROUP BY c.id ORDER BY c.sort_order ASC, c.id ASC')->fetchAll(PDO::FETCH_ASSOC);
                $this->render('content-list',['title'=>$config['label'],'type'=>$type,'config'=>$config,'rows'=>$rows]);
                return;
            }
            if($type==='pages'){
                $rows = array_values(array_filter($model->all(), fn($r) => in_array($r['slug'] ?? '', ['privacy','terms','compliance','shipping-policy'], true)));
                $this->render('content-list',['title'=>'Legal & Statutory Pages','type'=>$type,'config'=>$config,'rows'=>$rows]);
                return;
            }
            if($type==='seo_meta'){
                $corePages = Seo::corePages();
                $allSeo = $model->all();
                $byKey = [];
                foreach($allSeo as $row){
                    if(!empty($row['page_key'])){
                        $byKey[$row['page_key']] = $row;
                    }
                }
                $rows = [];
                foreach(array_keys($corePages) as $key){
                    if(isset($byKey[$key])){
                        $rows[] = $byKey[$key];
                    }
                }
                $this->render('content-list',['title'=>'Website Pages SEO Settings','type'=>$type,'config'=>$config,'rows'=>$rows]);
                return;
            }
            $this->render('content-list',['title'=>$config['label'],'type'=>$type,'config'=>$config,'rows'=>$model->all()]);
            return;
        }
        $seoLookupKey = $type==='pages' ? (!empty($record['slug']) ? '/'.$record['slug'] : '') : ($config['route'] && !empty($record['slug']) ? '/'.$config['route'].'/'.$record['slug'] : '');
        $seo=[]; if($seoLookupKey){$stmt=Database::get()->prepare('SELECT * FROM seo_meta WHERE page_key = ?');$stmt->execute([$seoLookupKey]);$seo=$stmt->fetch()?:[];}
        if($type==='products'&&$id){
            $allProducts=$model->all();
            $categories=(new ContentModel('categories'))->all();
            $this->render('product-edit',['title'=>($id==='new'?'Add ':'Edit ').strtolower($config['singular']),'type'=>$type,'config'=>$config,'record'=>$record?:[],'id'=>$id,'allProducts'=>$allProducts,'categories'=>$categories,'error'=>$error,'seo'=>$seo]);
            return;
        }
        if($type==='categories'&&$id){
            $assignedProducts = [];
            if($id !== 'new'){
                $stmt = Database::get()->prepare('SELECT id, name, slug, thumbnail, status FROM products WHERE cat_id = ? ORDER BY sort_order ASC, name ASC');
                $stmt->execute([(int)$id]);
                $assignedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
            $this->render('category-edit',[
                'title'=>($id==='new'?'Add ':'Edit ').strtolower($config['singular']),
                'type'=>$type,
                'config'=>$config,
                'record'=>$record?:[],
                'id'=>$id,
                'assignedProducts'=>$assignedProducts,
                'error'=>$error,
                'seo'=>$seo
            ]);
            return;
        }
        if(in_array($type,['blog_posts','case_studies','use_cases'])&&$id){
            $allRecords=$model->all();
            $blogCategories=$type==='blog_posts'?(new ContentModel('blog_categories'))->all():[];
            $allProducts=in_array($type,['case_studies','use_cases'])?(new ContentModel('products'))->all():[];
            $this->render('editorial-edit',[
                'title'=>($id==='new'?'Add ':'Edit ').strtolower($config['singular']),
                'type'=>$type,
                'config'=>$config,
                'record'=>$record?:[],
                'id'=>$id,
                'allRecords'=>$allRecords,
                'blogCategories'=>$blogCategories,
                'allProducts'=>$allProducts,
                'error'=>$error,
                'seo'=>$seo
            ]);
            return;
        }
        $this->render('content-edit',['title'=>($id==='new'?'Add ':'Edit ').strtolower($config['singular']),'type'=>$type,'config'=>$config,'record'=>$record?:[],'error'=>$error,'seo'=>$seo]);
    }
    private function cataloguePage(): void {
        $keys = ['catalogue_title', 'catalogue_eyebrow', 'catalogue_desc', 'catalogue_guidance_title', 'catalogue_guidance_desc', 'catalogue_guidance_btn'];
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                foreach ($keys as $k) {
                    if (isset($_POST[$k])) {
                        Setting::save($k, trim((string)$_POST[$k]));
                    }
                }
                if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                    jsonResponse(['ok' => true, 'message' => 'Catalogue page saved.']);
                }
                flash('Catalogue page saved.');
                redirect('admin/content/products/catalogue');
            } catch (Throwable $e) {
                $error = $e->getMessage();
                if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                    jsonResponse(['ok' => false, 'message' => $error], 422);
                }
            }
        }
        $allProducts = (new ContentModel('products'))->all();
        $this->render('catalogue-edit', [
            'title' => 'Product Catalogue Visual Editor',
            'allProducts' => $allProducts,
            'error' => $error
        ]);
    }
    private function validateRecord(array $input,array $fields):array{
        $data=[];
        foreach($fields as $key=>$rule){
            $required=str_ends_with($rule,'!');$kind=rtrim($rule,'!');$value=$input[$key]??'';
            if(in_array($kind,['products'])){$value=json_encode(array_values(array_map('intval',is_array($value)?$value:[])));}
            elseif($kind==='checkbox')$value=isset($input[$key])?1:0;
            elseif(in_array($kind,['number','rating','category','blog_category'])){$value=(int)$value;if($kind==='rating')$value=max(1,min(5,$value));if(in_array($kind,['category','blog_category'])&&!$value)$value=null;}
            elseif($kind==='gallery'){
                $decoded=json_decode((string)($value?:'[]'),true,32,JSON_THROW_ON_ERROR);
                if(!is_array($decoded)||!array_is_list($decoded)||count($decoded)>30)throw new RuntimeException('A gallery can contain up to 30 images.');
                $gallery=[];
                foreach($decoded as $entry){
                    if(is_string($entry))$entry=['path'=>$entry,'alt'=>trim((string)($input['image_alt']??''))];
                    if(!is_array($entry)||!is_string($entry['path']??null)||!is_string($entry['alt']??null))throw new RuntimeException('Each gallery image needs a path and description.');
                    $path=trim($entry['path']);$alt=trim($entry['alt']);$this->validateAsset($path);
                    if(!$path||preg_match('/\.pdf(?:[?#]|$)/i',$path))throw new RuntimeException('Choose image files for the gallery.');
                    if(mb_strlen($alt)<3||mb_strlen($alt)>255)throw new RuntimeException('Each gallery image description must contain 3–255 characters.');
                    $gallery[]=['path'=>$path,'alt'=>$alt];
                }
                $value=json_encode($gallery,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
            }
            elseif($kind==='keyvalue'){$decoded=json_decode((string)($value?:'{}'),true,32,JSON_THROW_ON_ERROR);if(!is_array($decoded))throw new RuntimeException('Enter a valid list for '.$key);foreach($decoded as $entry)if(!is_scalar($entry))throw new RuntimeException('Use text values for '.$key);$value=json_encode($decoded);}
            else{$value=trim((string)$value);if(mb_strlen($value)>100000)throw new RuntimeException('Content is too long.');}
            if($required&&$value==='')throw new RuntimeException(ucfirst(str_replace('_',' ',$key)).' is required.');
            if($kind==='slug'){$value=Slug::make($value);if(!$value||strlen($value)>200)throw new RuntimeException('Provide a valid URL slug under 200 characters.');}
            if($kind==='rich')$value=rich($value);
            if($kind==='status'&&!in_array($value,['draft','published']))$value='draft';
            if($kind==='robots'&&!in_array($value,['index,follow','noindex,follow','noindex,nofollow']))$value='index,follow';
            if($kind==='url')$this->validateUrl($value);
            if(in_array($kind,['image','pdf']))$this->validateAsset($value);
            if($key!=='keywords'&&in_array($kind,['text','icon','tags','meta_title'])&&mb_strlen($value)>255)throw new RuntimeException(ucfirst($key).' must be less than 255 characters.');
            if($kind==='datetime'){$time=$value?strtotime($value):false;$value=$time?date('Y-m-d H:i:s',$time):null;}
            $data[$key]=$value;
        }
        if(!empty($data['thumbnail'])&&empty($data['image_alt']))throw new RuntimeException('An image description is required.');
        return $data;
    }
    private function validateUrl(string $value,bool $relative=false):void{if($value===''||($relative&&preg_match('~^[a-zA-Z0-9/_?&=.%#-]+$~',$value)&&!str_starts_with($value,'//')))return;if(!preg_match('#^(https://|mailto:|tel:|/(?!/)|\#)#i',$value))throw new RuntimeException('Use a secure https:// URL or a local page path.');}
    private function validateAsset(string $value):void{if($value!==''&&!preg_match('#^assets/(images|uploads)/[a-zA-Z0-9._/-]+\.(webp|png|jpg|jpeg|svg|pdf)$#i',$value)&&!preg_match('#^https://[^\s]+$#',$value))throw new RuntimeException('Choose an uploaded file, a local asset path or a secure HTTPS image.');if(str_contains($value,'..'))throw new RuntimeException('Invalid asset path.');}
    private function saveInlineSeo(string $key,array $input):void{$input['page_key']=$key;$data=$this->validateRecord($input,$this->resources['seo_meta']['fields']);$stmt=Database::get()->prepare('SELECT id FROM seo_meta WHERE page_key = ?');$stmt->execute([$key]);$id=$stmt->fetchColumn();(new ContentModel('seo_meta'))->save($data,$id?(int)$id:null);}
    private function settings(string $path = 'admin/settings'): void {
        if ($path === 'admin/settings/test-smtp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            checkCsrf();
            try {
                $testTo = trim((string)($_POST['test_to'] ?? Auth::user()['email']));
                if (!filter_var($testTo, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Enter a valid recipient email address.');
                if (isset($_POST['smtp_host']) && $_POST['smtp_host'] !== '') Setting::save('smtp_host', trim((string)$_POST['smtp_host']));
                if (isset($_POST['smtp_port']) && $_POST['smtp_port'] !== '') Setting::save('smtp_port', trim((string)$_POST['smtp_port']));
                if (isset($_POST['smtp_user']) && $_POST['smtp_user'] !== '') Setting::save('smtp_user', trim((string)$_POST['smtp_user']));
                if (isset($_POST['smtp_pass']) && $_POST['smtp_pass'] !== '') Setting::save('smtp_pass', (string)$_POST['smtp_pass']);
                if (isset($_POST['smtp_from_email']) && $_POST['smtp_from_email'] !== '') Setting::save('smtp_from_email', trim((string)$_POST['smtp_from_email']));
                if (isset($_POST['smtp_from_name']) && $_POST['smtp_from_name'] !== '') Setting::save('smtp_from_name', trim((string)$_POST['smtp_from_name']));
                if (isset($_POST['smtp_enabled'])) Setting::save('smtp_enabled', $_POST['smtp_enabled'] ? '1' : '0');
                if (isset($_POST['smtp_encryption']) && $_POST['smtp_encryption'] !== '') Setting::save('smtp_encryption', trim((string)$_POST['smtp_encryption']));

                $tpl = EmailTemplate::test($testTo, date('d M Y, h:i:s A') . ' IST');
                SmtpMailer::send($testTo, $tpl['subject'], $tpl['plain'], $tpl['html']);
                jsonResponse(['ok' => true, 'message' => 'SMTP test email sent successfully to ' . $testTo . '!']);
            } catch (Throwable $e) {
                jsonResponse(['ok' => false, 'message' => $e->getMessage()], 422);
            }
        }

        $fields = [
            'phone', 'email', 'alert_email', 'address', 'whatsapp', 'tagline', 'description',
            'operating_hours', 'gst_number',
            'logo', 'catalogue_pdf',
            'smtp_enabled', 'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_user', 'smtp_pass', 'smtp_from_email', 'smtp_from_name',
            'meta_title', 'meta_description', 'focus_keywords',
            'facebook', 'instagram', 'linkedin'
        ];
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') try {
            foreach ($fields as $key) {
                $value = trim((string)($_POST[$key] ?? ''));
                if (in_array($key, ['logo', 'catalogue_pdf'])) $this->validateAsset($value);
                if (in_array($key, ['facebook', 'instagram', 'linkedin'])) $this->validateUrl($value);
                if (in_array($key, ['email', 'smtp_from_email', 'alert_email']) && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) throw new RuntimeException('Enter a valid email address.');
                if (in_array($key, ['phone', 'address', 'whatsapp']) && !$value) throw new RuntimeException('Phone, address and WhatsApp are required.');
                if (strlen($value) > 10000) throw new RuntimeException('Setting value is too long.');
                Setting::save($key, $value);
            }
            Setting::save('preview_notice', isset($_POST['preview_notice']) ? '1' : '0');
            if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                jsonResponse(['ok' => true, 'message' => 'Site settings saved successfully.']);
            }
            flash('Site settings saved.');
            redirect('admin/settings');
        } catch (Throwable $e) {
            $error = $e->getMessage();
            if (str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')) {
                jsonResponse(['ok' => false, 'message' => $error], 422);
            }
        }
        $this->render('settings', ['title' => 'Site settings & security', 'fields' => $fields, 'error' => $error]);
    }

    private function password(): void {
        $error = '';
        $isAjax = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            checkCsrf();
            $stmt = Database::get()->prepare('SELECT password_hash FROM admin_users WHERE id = ?');
            $stmt->execute([Auth::user()['id']]);
            $hash = $stmt->fetchColumn();
            $current = (string)($_POST['current_password'] ?? '');
            $new = (string)($_POST['password'] ?? $_POST['new_password'] ?? '');
            $confirm = (string)($_POST['confirm_password'] ?? '');

            if (!password_verify($current, $hash)) {
                $error = 'Your current password is incorrect.';
            } elseif (strlen($new) < 12 || strlen($new) > 72 || $new !== $confirm) {
                $error = 'Use 12–72 characters and make sure both passwords match.';
            } else {
                Database::get()->prepare('UPDATE admin_users SET password_hash = ? WHERE id = ?')->execute([password_hash($new, PASSWORD_BCRYPT), Auth::user()['id']]);
                session_regenerate_id(true);
                if ($isAjax) {
                    jsonResponse(['ok' => true, 'message' => 'Password updated successfully!']);
                }
                flash('Password updated.');
                redirect('admin/settings');
            }
            if ($isAjax && $error) {
                jsonResponse(['ok' => false, 'message' => $error], 422);
            }
        }
        $this->render('password', ['title' => 'Change password', 'error' => $error]);
    }

    private function emailChange(string $path): void {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            jsonResponse(['ok' => false, 'message' => 'Invalid request method.'], 405);
        }
        checkCsrf();
        $action = trim((string)($_POST['action'] ?? ''));
        if ($action === 'request' || str_ends_with($path, '/request')) {
            $newEmail = trim((string)($_POST['new_email'] ?? ''));
            $currentPassword = (string)($_POST['current_password'] ?? '');
            $user = Auth::user();
            if (!$user) jsonResponse(['ok' => false, 'message' => 'Unauthorized.'], 401);
            if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                jsonResponse(['ok' => false, 'message' => 'Please enter a valid email address.'], 422);
            }
            if (strcasecmp($newEmail, (string)$user['email']) === 0) {
                jsonResponse(['ok' => false, 'message' => 'The new email must be different from your current email.'], 422);
            }
            $stmt = Database::get()->prepare('SELECT id, password_hash FROM admin_users WHERE id = ?');
            $stmt->execute([$user['id']]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($currentPassword, $row['password_hash'])) {
                jsonResponse(['ok' => false, 'message' => 'Your current password is incorrect.'], 422);
            }
            $exists = Database::get()->prepare('SELECT id FROM admin_users WHERE email = ? AND id <> ?');
            $exists->execute([$newEmail, $user['id']]);
            if ($exists->fetchColumn()) {
                jsonResponse(['ok' => false, 'message' => 'That email address is already in use by another administrator.'], 422);
            }

            $otp = (string)random_int(100000, 999999);
            $_SESSION['email_change'] = [
                'new_email' => $newEmail,
                'otp_hash' => password_hash($otp, PASSWORD_DEFAULT),
                'expires_at' => time() + 600
            ];

            $simulated = false;
            try {
                if (SmtpMailer::isConfigured()) {
                    $tpl = EmailTemplate::otp(
                        $user['name'] ?? 'Administrator',
                        $otp,
                        "Your one-time verification code to update your admin email to <strong>{$newEmail}</strong> is below. Valid for 10 minutes."
                    );
                    SmtpMailer::send($newEmail, $tpl['subject'], $tpl['plain'], $tpl['html']);
                } else {
                    $simulated = true;
                }
            } catch (Throwable $e) {
                $simulated = true;
            }

            $msg = 'Verification code sent to ' . $newEmail . ($simulated ? " (Local Verification Code: {$otp})" : '');
            jsonResponse(['ok' => true, 'message' => $msg, 'simulated' => $simulated, 'otp' => $simulated ? $otp : null]);
        }

        if ($action === 'verify' || str_ends_with($path, '/verify')) {
            $otp = trim((string)($_POST['otp'] ?? ''));
            $user = Auth::user();
            if (!$user) jsonResponse(['ok' => false, 'message' => 'Unauthorized.'], 401);
            $state = $_SESSION['email_change'] ?? null;
            if (!$state || empty($state['new_email']) || empty($state['otp_hash'])) {
                jsonResponse(['ok' => false, 'message' => 'No pending email change request found. Request a new code.'], 422);
            }
            if (time() > ($state['expires_at'] ?? 0)) {
                unset($_SESSION['email_change']);
                jsonResponse(['ok' => false, 'message' => 'Verification code has expired. Request a new code.'], 422);
            }
            if (!preg_match('/^\d{6}$/', $otp) || !password_verify($otp, $state['otp_hash'])) {
                jsonResponse(['ok' => false, 'message' => 'The 6-digit verification code is incorrect.'], 422);
            }

            $newEmail = $state['new_email'];
            Database::get()->prepare('UPDATE admin_users SET email = ? WHERE id = ?')->execute([$newEmail, $user['id']]);
            $_SESSION['admin']['email'] = $newEmail;
            unset($_SESSION['email_change']);
            flash('Admin email successfully changed to ' . $newEmail . '.');
            jsonResponse(['ok' => true, 'message' => 'Email updated successfully to ' . $newEmail . '!', 'new_email' => $newEmail]);
        }

        jsonResponse(['ok' => false, 'message' => 'Invalid email change action.'], 400);
    }
    private function media():void{
        $error='';$model=new ContentModel('media');
        if($_SERVER['REQUEST_METHOD']==='POST')try{
            if(($_POST['action']??'')==='delete'){
                $row=$model->find((int)($_POST['id']??0));if(!$row)throw new RuntimeException('File not found.');
                $referenced=false;
                foreach(['products'=>['thumbnail','gallery','brochure_pdf'],'blog_posts'=>['thumbnail','content'],'case_studies'=>['thumbnail'],'use_cases'=>['thumbnail'],'pages'=>['thumbnail','content'],'client_logos'=>['logo_path'],'seo_meta'=>['og_image'],'settings'=>['value'],'sections'=>['data']] as $table=>$columns){$sql='SELECT COUNT(*) FROM '.$table.' WHERE '.implode(' OR ',array_map(fn($c)=>'`'.$c.'` LIKE ?',$columns));$stmt=Database::get()->prepare($sql);$stmt->execute(array_fill(0,count($columns),'%'.$row['path'].'%'));if($stmt->fetchColumn())$referenced=true;}
                if($referenced)throw new RuntimeException('This file is used by website content. Remove those references before deleting it.');
                $file=realpath(ROOT.'/'.$row['path']);$root=realpath(ROOT.'/assets/uploads');if($file&&$root&&str_starts_with($file,$root.DIRECTORY_SEPARATOR)&&is_file($file))unlink($file);$model->delete((int)$row['id']);flash('File deleted.');redirect('admin/media');
            }
            $media=Upload::save($_FILES['file']??[],(string)($_POST['image_alt']??''),(string)($_POST['filename']??''));
            if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>true,'media'=>$media]);
            flash('File uploaded.');redirect('admin/media');
        }catch(Throwable $e){$error=$e->getMessage();if(str_contains($_SERVER['HTTP_ACCEPT']??'','application/json'))jsonResponse(['ok'=>false,'message'=>$error],422);}
        $this->render('media',['title'=>'Media library','rows'=>$model->all(),'error'=>$error]);
    }
    private function enquiries():void{
        $model=new ContentModel('enquiries');
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $action=(string)($_POST['action']??'');
            if($action==='export'){
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename="shreeji-enquiries-'.date('Y-m-d').'.csv"');
                $file=fopen('php://output','w');
                fwrite($file,"\xEF\xBB\xBF");
                fputcsv($file,['ID','Name','Phone','Email','Product','Message','Status','Date']);
                $exportStmt = Database::get()->query("SELECT * FROM enquiries ORDER BY id DESC");
                while($row = $exportStmt->fetch(PDO::FETCH_ASSOC)){
                    $values=[];
                    foreach(['id','name','phone','email','product','message','status','created_at'] as $key){
                        $v=(string)$row[$key];
                        $values[]=preg_match('/^[\s]*[=+@\-]/',$v)?"'".$v:$v;
                    }
                    fputcsv($file,$values);
                }
                fclose($file);
                exit;
            }
            if($action==='delete'){
                $id=(int)($_POST['id']??0);
                if($id>0){
                    $model->delete($id);
                    flash('Enquiry #'.$id.' deleted successfully.');
                }
                redirect('admin/enquiries');
            }
            if($action==='bulk_delete'){
                $rawIds=$_POST['ids']??[];
                if(is_string($rawIds))$rawIds=explode(',',$rawIds);
                $ids=array_filter(array_map('intval',(array)$rawIds));
                if(!empty($ids)){
                    $placeholders=implode(',',array_fill(0,count($ids),'?'));
                    Database::get()->prepare("DELETE FROM enquiries WHERE id IN ($placeholders)")->execute(array_values($ids));
                    flash(count($ids).' enquiries deleted successfully.');
                }else{
                    flash('No enquiries selected for deletion.','error');
                }
                redirect('admin/enquiries');
            }
            if($action==='bulk_status'){
                $rawIds=$_POST['ids']??[];
                if(is_string($rawIds))$rawIds=explode(',',$rawIds);
                $ids=array_filter(array_map('intval',(array)$rawIds));
                $status=(string)($_POST['status']??'');
                if(!empty($ids)&&in_array($status,['new','read','replied'],true)){
                    $placeholders=implode(',',array_fill(0,count($ids),'?'));
                    Database::get()->prepare("UPDATE enquiries SET status = ? WHERE id IN ($placeholders)")->execute([$status,...array_values($ids)]);
                    flash(count($ids).' enquiries marked as '.ucfirst($status).'.');
                }else{
                    flash('Please select enquiries and a valid status.','error');
                }
                redirect('admin/enquiries');
            }
            $status=(string)($_POST['status']??'');
            $id=(int)($_POST['id']??0);
            if(in_array($status,['new','read','replied'],true)&&$id>0){
                $model->save(['status'=>$status],$id);
                if(str_contains($_SERVER['HTTP_ACCEPT']??'', 'application/json')){
                    jsonResponse(['ok'=>true, 'id'=>$id, 'status'=>$status, 'label'=>ucfirst($status), 'message'=>'Enquiry #'.$id.' status updated to '.ucfirst($status).'.']);
                }
                flash('Enquiry #'.$id.' status updated to '.ucfirst($status).'.');
            }
            if(str_contains($_SERVER['HTTP_ACCEPT']??'', 'application/json')){
                jsonResponse(['ok'=>false, 'message'=>'Invalid enquiry update request.'], 422);
            }
            redirect('admin/enquiries');
        }

        // Summary counts for KPIs
        $totalCount=(int)Database::get()->query("SELECT COUNT(*) FROM enquiries")->fetchColumn();
        $newCount=(int)Database::get()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'new'")->fetchColumn();
        $readCount=(int)Database::get()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'read'")->fetchColumn();
        $repliedCount=(int)Database::get()->query("SELECT COUNT(*) FROM enquiries WHERE status = 'replied'")->fetchColumn();

        // Search and filter handling
        $statusFilter=trim((string)($_GET['status']??''));
        $q=trim((string)($_GET['q']??''));
        $where=[];
        $params=[];

        if(in_array($statusFilter,['new','read','replied'],true)){
            $where[]="status = ?";
            $params[]=$statusFilter;
        }
        if($q!==''){
            $where[]="(name LIKE ? OR phone LIKE ? OR email LIKE ? OR product LIKE ? OR message LIKE ?)";
            $like='%'.$q.'%';
            $params=array_merge($params,[$like,$like,$like,$like,$like]);
        }

        $whereSql=!empty($where)?' WHERE '.implode(' AND ',$where):'';

        // Count matching records
        $countStmt=Database::get()->prepare("SELECT COUNT(*) FROM enquiries".$whereSql);
        $countStmt->execute($params);
        $filteredCount=(int)$countStmt->fetchColumn();

        // 20 enquiries per page
        $perPage=20;
        $totalPages=max(1,(int)ceil($filteredCount/$perPage));
        $page=max(1,min($totalPages,(int)($_GET['page']??1)));
        $offset=($page-1)*$perPage;

        // Always newest on top: ORDER BY id DESC
        $selectSql="SELECT * FROM enquiries".$whereSql." ORDER BY id DESC LIMIT ".(int)$perPage." OFFSET ".(int)$offset;
        $stmt=Database::get()->prepare($selectSql);
        $stmt->execute($params);
        $rows=$stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->render('enquiries',[
            'title'=>'Enquiry inbox',
            'rows'=>$rows,
            'totalCount'=>$totalCount,
            'newCount'=>$newCount,
            'readCount'=>$readCount,
            'repliedCount'=>$repliedCount,
            'filteredCount'=>$filteredCount,
            'page'=>$page,
            'perPage'=>$perPage,
            'totalPages'=>$totalPages,
            'statusFilter'=>$statusFilter,
            'searchQuery'=>$q
        ]);
    }
}
