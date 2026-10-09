<div class="container local-partner">
    <div class="local-partner-copy"><span class="eyebrow" data-preview="eyebrow"><?= e($s['eyebrow']??'') ?></span><h2 data-preview="heading"><?= e($s['heading']??'') ?></h2><p data-preview="body"><?= e($s['body']??'') ?></p>
        <ul class="local-partner-points"><?php foreach($s['items']??[] as $item): ?><li><span class="story-icon"><?= icon($item['icon']??'check-line') ?></span><div><h3><?= e($item['title']??'') ?></h3><p><?= e($item['body']??'') ?></p></div></li><?php endforeach ?></ul>
        <a class="text-link" href="<?= e(safeLink($s['secondary_url']??'products')) ?>"><?= e($s['secondary_label']??'Explore products') ?> <?= icon('arrow-right-line') ?></a>
    </div>
    <aside class="contact-desk" aria-label="Contact our team">
        <div class="contact-desk-top"><span><?= icon('customer-service-2-line') ?></span><small>LET’S TALK ABOUT<br>YOUR REQUIREMENTS</small></div>
        <h3>A conversation.<br>A clearer next step.</h3><p>Have a product in mind or a question to ask? Here is how to reach us.</p>
        <a class="contact-desk-row" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>"><?= icon('phone-line') ?><span><small>CALL OUR TEAM</small><strong><?= e(setting('phone')) ?></strong></span><?= icon('arrow-right-up-line') ?></a>
        <a class="contact-desk-row" href="mailto:<?= e(setting('email')) ?>"><?= icon('mail-line') ?><span><small>SEND YOUR ENQUIRY</small><strong><?= e(setting('email')) ?></strong></span><?= icon('arrow-right-up-line') ?></a>
        <a class="contact-desk-row" href="https://www.google.com/maps/search/?api=1&amp;query=<?= rawurlencode(setting('address')) ?>" target="_blank" rel="noopener"><?= icon('map-pin-2-line') ?><span><small>FIND US IN AGRA</small><strong><?= e(setting('address')) ?></strong></span><?= icon('arrow-right-up-line') ?></a>
        <a class="btn btn-light" href="<?= e(safeLink($s['button_url']??'contact')) ?>"><?= e($s['button_label']??'Talk to our team') ?> <?= icon('arrow-right-up-line') ?></a>
    </aside>
</div>
