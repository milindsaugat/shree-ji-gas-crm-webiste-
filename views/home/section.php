<?php $key=$section['section_key']; $rawBg=trim((string)($s['background']??'')); $bg=preg_match('/^#[0-9a-fA-F]{3,6}$/',$rawBg)?$rawBg:''; $isDark=$bg!==''&&isDarkColor($bg); ?>
<section id="<?= e($key) ?>" class="home-section section-<?= e($key) ?> <?= $isDark?'section-dark':'section-light' ?>" <?= $bg?'style="background-color:'.e($bg).';"':'' ?>>
<?php if($key==='hero'): ?>
<div class="container hero"><div class="hero__copy"><div class="hero__eyebrow"><span></span><?= e($s['eyebrow']??'') ?></div><h1 data-preview="heading"><?= e($s['heading']??'') ?><br><span data-preview="accent"><?= e($s['accent']??'') ?></span></h1><p data-preview="body"><?= e($s['body']??'') ?></p><div class="hero__actions"><a class="btn btn-primary" data-preview="button_label" data-button-field="button_label" data-url-field="button_url" data-button-url="<?= e($s['button_url']??'products') ?>" href="<?= e(safeLink($s['button_url']??'products')) ?>"><?= e($s['button_label']??'Explore products') ?> <?= icon('arrow-right-up-line') ?></a><a class="btn btn-outline" data-preview="secondary_label" data-button-field="secondary_label" data-url-field="secondary_url" data-button-url="<?= e($s['secondary_url']??'contact') ?>" href="<?= e(safeLink($s['secondary_url']??'contact')) ?>"><?= e($s['secondary_label']??'Contact us') ?> <?= icon('arrow-right-line') ?></a></div><div class="hero__trust"><?php foreach($s['items']??[] as $i): ?><div><?= icon($i['icon']) ?><span><strong><?= e($i['title']) ?></strong><small><?= e($i['body']) ?></small></span></div><?php endforeach ?></div></div><div class="hero__visual"><div class="hero__frame"><span class="hero__image-label"><?= icon('add-line') ?> <?= e($s['badge']??'MEDICAL & GAS EQUIPMENT') ?></span><img data-preview="image" class="hero__image" src="<?= photo($s['image']??'') ?>" alt="<?= e($s['image_alt']??'Medical equipment') ?>" width="1536" height="1024" fetchpriority="high"><div class="hero__image-caption"><span>PRECISION. PURPOSE. PEACE OF MIND.</span><span>01 / 03</span></div></div><a href="<?= url('products/medical-oxygen-cylinder') ?>" class="hero__featured"><span class="hero__featured-icon"><?= icon('lungs-line') ?></span><span><small>EXPLORE THE ESSENTIALS</small><strong>Medical oxygen solutions</strong><span>Equipment that supports care</span></span><?= icon('arrow-right-up-line') ?></a><div class="hero__side-note">BUILT AROUND YOUR REQUIREMENTS</div></div></div><div class="container hero__bottom"><span>FOR THE PEOPLE WHO CARE FOR OTHERS.</span><a href="#categories">Discover our range <?= icon('arrow-down-line') ?></a></div>
<?php elseif($key==='stats'): ?>
<div class="container stats-grid"><?php foreach($s['items']??[] as $i): $statValue=str_contains(strtolower((string)($i['label']??'')),'equipment categories')?str_pad((string)count($categories??allCategories()),2,'0',STR_PAD_LEFT):($i['value']??''); ?><div class="stat"><?= icon($i['icon']??'add-line') ?><div><strong><?= e($statValue) ?></strong><span><?= e($i['label']??'') ?></span></div></div><?php endforeach ?></div>
<?php elseif($key==='categories'): ?>
<div class="container"><div class="section-heading"><div><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2></div><p data-preview="body"><?= e($s['body']??'') ?></p></div><div class="categories-grid"><?php foreach($categories??allCategories() as $cat): ?><a class="category-card" href="<?= url('categories/'.$cat['slug']) ?>"><span class="category-card__icon"><?= icon($cat['icon']) ?></span><span><h3><?= e($cat['name']) ?></h3><small><?= count(array_filter((new ContentModel('products'))->all(true),fn($p)=>$p['cat_id']==$cat['id'])) ?> products to explore</small></span><?= icon('arrow-right-up-line') ?></a><?php endforeach ?></div></div>
<?php elseif($key==='products'): ?>
<div class="container"><div class="section-heading"><div><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2><p data-preview="body"><?= e($s['body']??'') ?></p></div><a class="btn btn-outline" data-preview="button_label" data-button-field="button_label" data-url-field="button_url" data-button-url="<?= e($s['button_url']??'products') ?>" href="<?= e(safeLink($s['button_url']??'products')) ?>"><?= e($s['button_label']??'View all products') ?> <?= icon('arrow-right-up-line') ?></a></div><div class="swiper product-swiper"><div class="swiper-wrapper"><?php foreach($products??array_filter((new ContentModel('products'))->all(true),fn($p)=>$p['is_featured']) as $product): ?><div class="swiper-slide"><?php productCard($product); ?></div><?php endforeach ?></div><div class="swiper-pagination"></div></div><div class="product-footnote"><?= icon('information-line') ?> Product images are illustrative. Ask our team for current models, specifications and availability.</div></div>
<?php elseif($key==='why'): ?>
<div class="container why-grid"><div class="why-image"><img src="<?= photo($s['image']??'') ?>" alt="<?= e($s['image_alt']??'Hospital environment') ?>" loading="lazy" width="600" height="680"><div class="why-image__label"><?= icon('hand-heart-line') ?><span>Care is your calling.<br><strong>Supporting it is ours.</strong></span></div></div><div class="why-copy"><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2><p data-preview="body"><?= e($s['body']??'') ?></p><div class="feature-list"><?php foreach($s['items']??[] as $i): ?><div class="feature-row"><span><?= icon($i['icon']) ?></span><div><h3><?= e($i['title']) ?></h3><p><?= e($i['body']) ?></p></div></div><?php endforeach ?></div><a class="text-link" data-preview="button_label" data-button-field="button_label" data-url-field="button_url" data-button-url="<?= e($s['button_url']??'about') ?>" href="<?= e(safeLink($s['button_url']??'about')) ?>"><?= e($s['button_label']??'Get to know Shree Ji') ?> <?= icon('arrow-right-line') ?></a></div></div>
<?php elseif($key==='process'): ?>
<?php require ROOT.'/views/home/journey.php'; ?>
<?php elseif($key==='guidance'): ?>
<?php require ROOT.'/views/home/planning.php'; ?>
<?php elseif($key==='partnership'): ?>
<?php /* Section partnership removed per user request */ ?>
<?php elseif($key==='case_studies'): require ROOT.'/views/home/case-study-spotlight.php'; ?>
<?php elseif(in_array($key,['use_cases','blog'])): $items=$key==='use_cases'?($useCases??(new ContentModel('use_cases'))->all(true)):($posts??(new ContentModel('blog_posts'))->all(true)); $type=['use_cases'=>'use-cases','blog'=>'blog'][$key]; ?>
<div class="container"><div class="section-heading"><div><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2><p data-preview="body"><?= e($s['body']??'') ?></p></div><a class="text-link" data-preview="button_label" data-button-field="button_label" data-url-field="button_url" data-button-url="<?= e($s['button_url']??$type) ?>" href="<?= e(safeLink($s['button_url']??$type)) ?>"><?= e($s['button_label']??'Explore all') ?> <?= icon('arrow-right-up-line') ?></a></div><div class="editorial-grid"><?php foreach(array_slice($items,0,$key==='case_studies'?4:3) as $item) contentCard($item,$type); ?><?php if(!$items): ?><p>Stories will be shared here as they become available.</p><?php endif ?></div></div>
<?php elseif($key==='testimonials'): ?>
<div class="container testimonial-section"><div class="testimonial-heading"><div><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'CUSTOMER VOICES') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2></div><p data-preview="body"><?= e($s['body']??'Every conversation helps us understand how to support care teams better.') ?></p></div><?php $voices=array_values(array_filter((new ContentModel('testimonials'))->all(),fn($t)=>(bool)$t['is_active'])); ?><?php if($voices): ?><div class="testimonial-swiper-wrap"><div class="swiper testimonial-swiper"><div class="swiper-wrapper"><?php foreach($voices as $t): ?><figure class="swiper-slide testimonial" data-testimonial-id="<?= (int)$t['id'] ?>"><span class="quote-mark" aria-hidden="true">“</span><blockquote data-preview="quote" data-record-id="<?= (int)$t['id'] ?>"><?= e($t['quote']) ?></blockquote><figcaption><?php if(!empty($t['avatar'])): ?><span class="testimonial-avatar has-image" aria-hidden="true"><img src="<?= photo($t['avatar']) ?>" alt="<?= e($t['client_name']) ?>" loading="lazy" width="56" height="56"></span><?php else: ?><span class="testimonial-avatar" aria-hidden="true"><?= e(mb_strtoupper(mb_substr(trim((string)$t['client_name']),0,1))) ?></span><?php endif ?><span class="testimonial-person"><strong data-preview="client_name" data-record-id="<?= (int)$t['id'] ?>"><?= e($t['client_name']) ?></strong><small data-preview="organization" data-record-id="<?= (int)$t['id'] ?>"><?= e($t['organization']) ?></small></span><span class="testimonial-rating" aria-label="<?= (int)$t['rating'] ?> out of 5 stars"><?= str_repeat('★',max(1,min(5,(int)$t['rating']))) ?></span></figcaption></figure><?php endforeach ?></div><div class="testimonial-swiper-nav-row"><button type="button" class="testimonial-nav-btn testimonial-prev" aria-label="Previous testimonial"><?= icon('arrow-left-s-line') ?></button><div class="swiper-pagination"></div><button type="button" class="testimonial-nav-btn testimonial-next" aria-label="Next testimonial"><?= icon('arrow-right-s-line') ?></button></div></div></div><?php else: ?><article class="testimonial-empty"><div class="testimonial-empty-quote"><span aria-hidden="true">“</span><small>YOUR EXPERIENCE MATTERS</small></div><div><h3>We’re listening.</h3><p>We’re building lasting relationships through every enquiry. Verified customer stories will be shared here with permission.</p><a class="text-link" href="<?= url('contact') ?>">Start a conversation <?= icon('arrow-right-line') ?></a></div><div class="testimonial-empty-mark"><?= icon('chat-smile-3-line') ?></div></article><?php endif ?></div>

<?php elseif($key==='faq'): ?>
<div class="container faq-layout"><div class="faq-intro"><span class="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2><?= e($s['heading']??'') ?></h2><p><?= e($s['body']??'') ?></p><a class="faq-contact-card" href="<?= url('contact') ?>"><span><?= icon('question-answer-line') ?></span><span><strong>Need a little more help?</strong><small>Talk with our team about your requirements.</small></span><?= icon('arrow-right-up-line') ?></a></div><div class="faq-list"><?php foreach($s['items']??[] as $i=>$item): ?><details <?= $i===0?'open':'' ?>><summary><span class="faq-number"><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></span><span class="faq-question"><?= e($item['question']??'') ?></span><span class="faq-toggle"><?= icon('arrow-down-s-line') ?></span></summary><p><?= e($item['answer']??'') ?></p></details><?php endforeach ?></div></div>
<?php elseif($key==='logos'): ?>
<?php $logos=array_values(array_filter((new ContentModel('client_logos'))->all(),fn($logo)=>(bool)$logo['is_active'])); ?>
<div class="container"><h2><?= e($s['heading']??'') ?></h2><?php if($logos): ?><div class="logo-marquee <?= count($logos)>1?'logo-marquee--animated':'' ?>"><div class="logo-marquee__track"><?php for($copy=0;$copy<(count($logos)>1?2:1);$copy++): ?><div class="logo-marquee__group" <?= $copy?'aria-hidden="true"':'' ?>><?php foreach($logos as $logo): ?><a href="<?= e(safeLink($logo['website_url']?:'#')) ?>" <?= $copy?'tabindex="-1"':'' ?>><img src="<?= photo($logo['logo_path']) ?>" alt="<?= $copy?'':e($logo['image_alt']?:$logo['name']) ?>" loading="lazy" width="160" height="60"></a><?php endforeach ?></div><?php endfor ?></div></div><?php if(count($logos)>1): ?><button type="button" class="logo-marquee-toggle" aria-pressed="false">Pause logo scroll</button><?php endif ?><?php else: ?><p>Client logos will appear here when added to your library.</p><?php endif ?></div>
<?php elseif($key==='cta'): ?>
<div class="cta-spotlight-section">
    <div class="container">
        <div class="cta-spotlight-card">
            <div class="cta-spotlight-card__grid">
                <!-- Left: Content & Direct Communication Desk -->
                <div class="cta-spotlight-card__content">
                    <div class="cta-status-pill">
                        <span class="cta-status-dot"></span>
                        <span>24/7 PRIORITY DISPATCH · AGRA &amp; NCR</span>
                    </div>

                    <h2 class="cta-spotlight-card__title" data-preview="heading">
                        <?= e($s['heading'] ?: 'The right gas solution starts with a conversation.') ?>
                    </h2>

                    <p class="cta-spotlight-card__desc" data-preview="body">
                        <?= e($s['body'] ?: 'Connect with our engineering desk for immediate hospital cylinder delivery, custom pipeline manifold sizing, or certified industrial supply contracts across Agra and Western UP.') ?>
                    </p>

                    <div class="cta-spotlight-card__actions">
                        <a class="btn btn-primary" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                            <?= icon('phone-line') ?>
                            <span>Call <?= e(setting('phone')) ?></span>
                        </a>
                        <a class="btn btn-whatsapp-action" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>?text=<?= rawurlencode('Hello Shree Ji Gas team, I would like to enquire about gas supply and equipment for our facility.') ?>" target="_blank" rel="noopener">
                            <?= icon('whatsapp-line') ?>
                            <span>WhatsApp Desk</span>
                        </a>
                        <a class="btn btn-secondary" href="<?= e(safeLink($s['button_url'] ?? 'contact')) ?>">
                            <span><?= e($s['button_label'] ?? 'Request quote') ?></span>
                            <?= icon('arrow-right-up-line') ?>
                        </a>
                    </div>

                    <div class="cta-spotlight-card__meta-strip">
                        <span class="cta-meta-item"><?= icon('shield-check-line') ?> <strong>PESO Certified</strong></span>
                        <span class="cta-meta-sep">•</span>
                        <span class="cta-meta-item"><?= icon('truck-line') ?> <strong>&lt; 2-Hr Dispatch</strong></span>
                        <span class="cta-meta-sep">•</span>
                        <span class="cta-meta-item"><?= icon('flask-line') ?> <strong>99.9% Purity</strong></span>
                        <span class="cta-meta-sep">•</span>
                        <span class="cta-meta-item"><?= icon('mail-line') ?> <a href="mailto:<?= e(setting('email')) ?>"><?= e(setting('email')) ?></a></span>
                    </div>
                </div>

                <!-- Right: High-Resolution Industrial Facility Showcase -->
                <div class="cta-spotlight-card__visual">
                    <div class="cta-facility-showcase">
                        <img src="<?= asset('images/facility.jpg') ?>" alt="Shree Ji Gas Industries Central Agra Gas Depot and Logistics Infrastructure" loading="lazy" width="600" height="400">
                        <div class="cta-facility-showcase__overlay">
                            <span class="cta-depot-tag">
                                <span class="cta-status-dot"></span>
                                Central Agra Depot · PESO Licensed
                            </span>
                            <span class="cta-depot-sub">24/7 Bulk &amp; Emergency Fleet Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php elseif($key==='contact'): ?>
<?php /* Section contact strip removed per user request */ ?>
<?php endif ?>
</section>
