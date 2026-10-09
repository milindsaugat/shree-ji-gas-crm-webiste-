<?php
$companyCategories = allCategories();
$companyProducts = (new ContentModel('products'))->all(true);
$companyUseCases = array_slice((new ContentModel('use_cases'))->all(true), 0, 3);
$productsByCategory = [];
foreach ($companyProducts as $product) $productsByCategory[$product['cat_id']][] = $product;
?>
<div class="company-page">
    <section id="company-hero" class="company-hero">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb"><a href="<?= url() ?>">Home</a><span>/</span><span>About us</span></nav>
            <div class="company-hero-grid">
                <div class="company-hero-copy">
                    <span class="eyebrow">MEET SHREE JI GAS INDUSTRIES</span>
                    <h1 data-preview="title"><?= e($page['title']) ?></h1><p data-preview="excerpt"><?= e($page['excerpt']) ?></p>
                    <div class="company-hero-actions"><a class="btn btn-primary" href="#company-story">Get to know us <?= icon('arrow-down-line') ?></a><a class="text-link" href="<?= url('products') ?>">Explore equipment <?= icon('arrow-right-up-line') ?></a></div>
                    <div class="company-location"><?= icon('map-pin-2-line') ?><span><strong>Rooted in Agra.</strong> Connected to your requirements.</span></div>
                </div>
                <div class="company-hero-visual">
                    <img src="<?= photo('assets/images/equipment.webp') ?>" alt="Illustrative selection of medical oxygen and surgical equipment" width="1536" height="1024" fetchpriority="high">
                    <div class="company-visual-caption"><span class="story-icon"><?= icon('hand-heart-line') ?></span><div><small>FOR THE PEOPLE WHO CARE</small><strong>Thoughtful choices.<br>Practical conversations.</strong></div></div>
                </div>
            </div>
            <div class="company-facts">
                <div><?= icon('apps-2-line') ?><span><strong><?= str_pad((string)count($companyCategories),2,'0',STR_PAD_LEFT) ?></strong><small>Equipment categories to explore</small></span></div>
                <div><?= icon('first-aid-kit-line') ?><span><strong><?= str_pad((string)count($companyProducts),2,'0',STR_PAD_LEFT) ?></strong><small>Products in our online catalogue</small></span></div>
                <div><?= icon('map-pin-2-line') ?><span><strong>Agra</strong><small>A local team to talk to</small></span></div>
            </div>
        </div>
    </section>

    <section id="company-story" class="company-section company-story">
        <div class="container company-story-grid">
            <figure class="company-story-photo"><img src="<?= photo($page['thumbnail']) ?>" alt="<?= e($page['image_alt']) ?>" width="800" height="950" loading="lazy"><figcaption><span><?= icon('hospital-line') ?></span><p>Every care setting is different.<br><strong>So is every equipment conversation.</strong></p></figcaption></figure>
            <div class="company-story-copy">
                <span class="eyebrow">THE PEOPLE BEHIND YOUR EQUIPMENT ENQUIRY</span>
                <div class="prose" data-preview="content"><?= rich($page['content']) ?></div>
                <ul class="company-story-points">
                    <li><?= icon('chat-1-line') ?><span><strong>Your requirements come first</strong>Tell us about the setting, application and details that matter to you.</span></li>
                    <li><?= icon('file-list-3-line') ?><span><strong>Make room for the right questions</strong>Explore product information, accessories and configuration requirements.</span></li>
                    <li><?= icon('customer-service-2-line') ?><span><strong>A person to continue the conversation with</strong>Connect with our team when you are ready to discuss your next step.</span></li>
                </ul>
            </div>
        </div>
    </section>

    <section id="company-range" class="company-section company-range">
        <div class="container">
            <div class="company-section-heading"><div><span class="eyebrow">OUR EQUIPMENT RANGE</span><h2>Different equipment.<br>One place to begin.</h2></div><div><p>From oxygen equipment to the essentials of a clinical workspace, explore dependable products built for care.</p><a class="text-link" href="<?= url('products') ?>">View the complete range <?= icon('arrow-right-line') ?></a></div></div>
            <div class="product-grid">
                <?php foreach($companyProducts as $product): ?>
                    <?php productCard($product); ?>
                <?php endforeach ?>
            </div>
        </div>
    </section>

    <?php if($companyUseCases): ?>
    <section id="company-settings" class="company-section company-settings">
        <div class="container company-settings-grid">
            <div class="company-settings-copy"><span class="eyebrow">BUILT AROUND YOUR CARE SETTING</span><h2>The setting changes.<br>The details still matter.</h2><p>A hospital, a clinic and a supervised home-care setting can have very different equipment requirements. Start with how the product will be used, then bring the practical details into focus.</p>
                <ul class="story-checks"><li><?= icon('check-line') ?><span>The application and people using the equipment</span></li><li><?= icon('check-line') ?><span>Available space, existing equipment and accessories</span></li><li><?= icon('check-line') ?><span>Product details to discuss with your clinical or technical team</span></li></ul>
                <a class="text-link" href="<?= url('use-cases') ?>">Explore care settings <?= icon('arrow-right-line') ?></a>
            </div>
            <div class="company-settings-list"><?php foreach($companyUseCases as $i=>$useCase): ?><a href="<?= url('use-cases/'.$useCase['slug']) ?>"><img src="<?= photo($useCase['thumbnail']) ?>" alt="<?= e($useCase['image_alt']?:$useCase['title']) ?>" loading="lazy" width="160" height="160"><div><small>CARE SETTING / <?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></small><h3><?= e($useCase['title']) ?></h3><p><?= e(mb_strimwidth(strip_tags($useCase['application_context']),0,140,'…')) ?></p></div><?= icon('arrow-right-up-line') ?></a><?php endforeach ?></div>
        </div>
    </section>
    <?php endif ?>

    <section id="company-purpose" class="company-section company-purpose">
        <div class="container">
            <div class="company-purpose-heading"><span class="eyebrow">WHAT GUIDES OUR APPROACH</span><h2>Useful information.<br>Human conversations.</h2><p>Equipment sourcing is a series of decisions. We believe each one deserves attention, clear questions and a practical next step.</p></div>
            <div class="company-purpose-grid">
                <article><div class="company-purpose-top"><span class="story-icon"><?= icon('compass-3-line') ?></span><small>OUR PURPOSE</small></div><h3>Make the starting point clearer.</h3><p>Help people turn a general equipment requirement into a more focused enquiry, with the information needed to explore suitable options.</p><ul class="story-checks"><li><?= icon('check-line') ?><span>Understand the need before discussing the product</span></li><li><?= icon('check-line') ?><span>Bring specifications and compatibility into the conversation</span></li><li><?= icon('check-line') ?><span>Keep the next step clear and practical</span></li></ul></article>
                <article><div class="company-purpose-top"><span class="story-icon"><?= icon('hand-heart-line') ?></span><small>OUR PERSPECTIVE</small></div><h3>Remember who the equipment is for.</h3><p>Behind an equipment list are people working to deliver care. Their setting, workflow and questions shape a more useful conversation.</p><ul class="story-checks"><li><?= icon('check-line') ?><span>Listen to the context behind the requirement</span></li><li><?= icon('check-line') ?><span>Make space for questions and follow-up</span></li><li><?= icon('check-line') ?><span>Connect product information with everyday needs</span></li></ul></article>
            </div>
        </div>
    </section>

    <?php $journeySection=Section::find('process'); if($journeySection): $s=$journeySection['fields']; ?>
    <section id="company-journey" class="company-section company-journey"><?php require ROOT.'/views/home/journey.php'; ?></section>
    <?php endif ?>
    <?php $localSection=Section::find('partnership'); if($localSection): $s=$localSection['fields']; ?>
    <section id="company-local" class="company-section company-local"><?php require ROOT.'/views/home/local-partner.php'; ?></section>
    <?php endif ?>
</div>
<?php $section=Section::find('cta'); if($section && $section['is_active']): $s=$section['fields']; ?>
<div id="company-cta"><?php require ROOT.'/views/home/section.php'; ?></div>
<?php endif ?>
