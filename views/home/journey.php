<div class="container journey">
    <div class="journey-heading">
        <div><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'HOW WE WORK') ?></span><h2 data-preview="heading"><?= e($s['heading']??'A clearer path to your next step.') ?></h2></div>
        <p data-preview="body"><?= e($s['body']??'') ?></p>
    </div>
    <ol class="journey-steps">
        <?php foreach($s['items']??[] as $i=>$item): ?>
        <li>
            <div class="journey-step-top"><span class="story-icon"><?= icon($item['icon']??'arrow-right-line') ?></span><span class="journey-number"><?= e($item['number']??str_pad((string)($i+1),2,'0',STR_PAD_LEFT)) ?></span></div>
            <h3><?= e($item['title']??'') ?></h3><p><?= e($item['body']??'') ?></p>
            <?php if(!empty($item['point_1'])||!empty($item['point_2'])): ?><ul class="story-checks"><?php foreach(['point_1','point_2'] as $point): if(empty($item[$point]))continue; ?><li><?= icon('check-line') ?><span><?= e($item[$point]) ?></span></li><?php endforeach ?></ul><?php endif ?>
        </li>
        <?php endforeach ?>
    </ol>
    <?php if(!empty($s['note'])): ?><div class="journey-note"><?= icon('file-text-line') ?><p><?= e($s['note']) ?></p><a class="text-link" href="<?= url('contact') ?>">Send an enquiry <?= icon('arrow-right-up-line') ?></a></div><?php endif ?>
</div>
