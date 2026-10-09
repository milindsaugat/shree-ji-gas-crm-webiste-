<div class="container planning">
    <div class="planning-heading"><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2><p data-preview="body"><?= e($s['body']??'') ?></p></div>
    <div class="planning-layout">
        <figure class="planning-image"><img src="<?= photo($s['image']??'assets/images/surgery.jpg') ?>" alt="<?= e($s['image_alt']??'Clinical care environment') ?>" loading="lazy" width="700" height="850"><figcaption><span class="story-icon"><?= icon('hand-heart-line') ?></span><span><?= e($s['image_caption']??'The right details make a better starting point.') ?></span></figcaption></figure>
        <div class="planning-cards">
            <?php foreach($s['items']??[] as $i=>$item): ?>
            <article><div class="planning-card-top"><span class="story-icon"><?= icon($item['icon']??'check-line') ?></span><small><?= str_pad((string)($i+1),2,'0',STR_PAD_LEFT) ?></small></div><h3><?= e($item['title']??'') ?></h3><p><?= e($item['body']??'') ?></p>
                <?php if(!empty($item['point_1'])||!empty($item['point_2'])): ?><ul class="story-checks"><?php foreach(['point_1','point_2'] as $point): if(empty($item[$point]))continue; ?><li><?= icon('check-line') ?><span><?= e($item[$point]) ?></span></li><?php endforeach ?></ul><?php endif ?>
            </article>
            <?php endforeach ?>
        </div>
    </div>
    <?php if(!empty($s['note'])): ?><p class="planning-note"><?= icon('information-line') ?> <?= e($s['note']) ?></p><?php endif ?>
</div>
