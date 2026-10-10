<?php
final class PageController
{
    public function handles(string $slug): bool {
        return in_array($slug, ['about', 'contact'], true) || (bool)(new ContentModel('pages'))->bySlug($slug);
    }
    public function show(string $slug): void {
        $page = (new ContentModel('pages'))->bySlug($slug);
        if (!$page) {
            if ($slug === 'contact') {
                $page = [
                    'id' => 0,
                    'slug' => 'contact',
                    'title' => 'Let’s find the right equipment for you.',
                    'excerpt' => 'Direct Procurement & Dispatch Desks. Connect with our biomedical gas engineers in Agra.',
                    'content' => '',
                    'status' => 'published'
                ];
            } else {
                notFound();
            }
        }
        $template = is_file(ROOT . '/views/pages/' . $slug . '.php') ? 'pages/' . $slug : 'pages/legal';
        view($template, ['title' => $page['title'], 'page' => $page, 'pageKey' => '/' . $slug]);
    }
    public function enquiry(): never {
        checkCsrf();
        $ajax=str_contains($_SERVER['HTTP_ACCEPT']??'', 'application/json');
        $fail=function(string $message, int $status=422) use($ajax): never { if($ajax) jsonResponse(['ok'=>false,'message'=>$message],$status); flash($message,'error'); redirect('contact'); };
        if (!empty($_POST['enquiry_pot_check'])) $fail('Unable to submit this enquiry.');
        $data=[]; foreach(['name','phone','email','product','message'] as $key) $data[$key]=trim((string)($_POST[$key]??''));
        if (mb_strlen($data['name'])<2||mb_strlen($data['name'])>150) $fail('Please enter a name between 2 and 150 characters.');
        if (!preg_match('/^[+\d\s()-]{8,25}$/',$data['phone'])||strlen(preg_replace('/\D/','',$data['phone']))<8) $fail('Please enter a valid phone number.');
        if ($data['email'] && (!filter_var($data['email'],FILTER_VALIDATE_EMAIL)||strlen($data['email'])>254)) $fail('Please enter a valid email address.');
        if (mb_strlen($data['message'])<10||mb_strlen($data['message'])>5000) $fail('Please enter a message between 10 and 5,000 characters.');
        if (mb_strlen($data['product'])>255) $fail('Please select a valid product.');
        if (empty($_POST['consent'])) $fail('Please accept the data processing consent to submit your enquiry.');
        if (!RateLimit::allow('enquiry',5,600)) $fail('Too many enquiries. Please try again in ten minutes or call our team.',429);
        $data['status']='new'; $data['email_sent']=0;
        $model=new ContentModel('enquiries'); $id=$model->save($data);
        try {
            $destEmail = setting('email') ?: 'shreejigasteam@gmail.com';
            $tpl = EmailTemplate::enquiry($data, $id);
            if (SmtpMailer::isConfigured()) {
                SmtpMailer::send($destEmail, $tpl['subject'], $tpl['plain'], $tpl['html']);
                $model->save(['email_sent' => 1], $id);
            } elseif (MAIL_ENABLED) {
                $headers = [
                    'MIME-Version' => '1.0',
                    'Content-Type' => 'text/html; charset=UTF-8',
                    'From' => mb_encode_mimeheader(setting('tagline') ?: SITE_NAME) . ' <' . $destEmail . '>'
                ];
                if ($data['email']) $headers['Reply-To'] = $data['email'];
                $sent = @mail($destEmail, $tpl['subject'], $tpl['html'], $headers);
                if ($sent) $model->save(['email_sent' => 1], $id);
            }
        } catch (Throwable $e) {
            $logDir = ROOT . '/storage/logs';
            if (!is_dir($logDir)) @mkdir($logDir, 0777, true);
            @file_put_contents($logDir . '/mail.log', '[' . date('Y-m-d H:i:s') . '] Enquiry #' . $id . ' mail dispatch notice: ' . $e->getMessage() . "\n", FILE_APPEND);
        }
        $message='Thank you, '.$data['name'].'. Your enquiry has been received. Our team will be in touch.';
        $whatsapp=!empty($_POST['whatsapp'])?'https://wa.me/'.preg_replace('/\D/','',setting('whatsapp')).'?text='.rawurlencode('Hello, I am '.$data['name'].'. I’m interested in '.($data['product']?:'your equipment').'. '.$data['message']):null;
        if($ajax) jsonResponse(['ok'=>true,'message'=>$message,'name'=>$data['name'],'whatsapp'=>$whatsapp]);
        flash($message); if($whatsapp) $_SESSION['enquiry_whatsapp']=$whatsapp; redirect('contact');
    }
}
