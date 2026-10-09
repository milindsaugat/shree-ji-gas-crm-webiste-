<div class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= url() ?>">Home</a>
            <span>/</span>
            <span>Contact</span>
        </nav>
        <span class="eyebrow">A CONVERSATION IS A GOOD START</span>
        <h1><?= e($page['title']) ?></h1>
        <p><?= e($page['excerpt']) ?></p>
    </div>
</div>

<section class="section section-contact-main">
    <div class="container contact-layout">
        <!-- Left: Direct Procurement & Dispatch Desks -->
        <aside class="contact-details">
            <div class="contact-status-pill">
                <span class="pulse-dot"></span>
                <span>CENTRAL AGRA DEPOT · 24/7 PRIORITY DISPATCH</span>
            </div>

            <h2 class="contact-title">Direct Procurement &amp; Engineering Desks</h2>
            <p class="contact-lead">Connect directly with our biomedical gas engineers, facility coordinators, and emergency cylinder dispatch officer in Agra.</p>

            <div class="contact-channels-grid">
                <!-- Channel 1: Emergency & Clinical Hotline -->
                <a class="contact-channel-card contact-channel-card--phone" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                    <div class="channel-icon-box">
                        <?= icon('phone-line') ?>
                    </div>
                    <div class="channel-info">
                        <span class="channel-tag">24/7 CLINICAL &amp; BULK DISPATCH</span>
                        <strong class="channel-value"><?= e(setting('phone')) ?></strong>
                        <span class="channel-sla"><?= icon('time-line') ?> Direct hotline · &lt; 2-Hour Hospital SLA</span>
                    </div>
                    <div class="channel-action-arrow">
                        <?= icon('arrow-right-up-line') ?>
                    </div>
                </a>

                <!-- Channel 2: WhatsApp Procurement Desk -->
                <a class="contact-channel-card contact-channel-card--whatsapp" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>?text=<?= rawurlencode('Hello Shree Ji Gas team, I would like to inquire about gas supply and equipment for our facility.') ?>" target="_blank" rel="noopener">
                    <div class="channel-icon-box channel-icon-box--whatsapp">
                        <?= icon('whatsapp-line') ?>
                    </div>
                    <div class="channel-info">
                        <span class="channel-tag">WHATSAPP PROCUREMENT DESK</span>
                        <strong class="channel-value">Instant Chat &amp; Spec Sheets</strong>
                        <span class="channel-sla"><?= icon('chat-check-line') ?> Send cylinder count, tank specs or RFPs</span>
                    </div>
                    <div class="channel-action-arrow">
                        <?= icon('arrow-right-up-line') ?>
                    </div>
                </a>

                <!-- Channel 3: Institutional Tenders & Email -->
                <a class="contact-channel-card" href="mailto:<?= e(setting('email')) ?>">
                    <div class="channel-icon-box">
                        <?= icon('mail-line') ?>
                    </div>
                    <div class="channel-info">
                        <span class="channel-tag">INSTITUTIONAL TENDERS &amp; RFPS</span>
                        <strong class="channel-value"><?= e(setting('email')) ?></strong>
                        <span class="channel-sla"><?= icon('file-text-line') ?> Formal quotes, GST billing &amp; contracts</span>
                    </div>
                    <div class="channel-action-arrow">
                        <?= icon('arrow-right-up-line') ?>
                    </div>
                </a>

                <!-- Channel 4: Central Depot & Refill Station -->
                <a class="contact-channel-card" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode(setting('address')) ?>">
                    <div class="channel-icon-box">
                        <?= icon('map-pin-2-line') ?>
                    </div>
                    <div class="channel-info">
                        <span class="channel-tag">CENTRAL AGRA DEPOT &amp; WAREHOUSE</span>
                        <strong class="channel-value"><?= e(setting('address')) ?></strong>
                        <span class="channel-sla"><?= icon('building-2-line') ?> PESO Licensed filling facility &amp; pickup</span>
                    </div>
                    <div class="channel-action-arrow">
                        <?= icon('arrow-right-up-line') ?>
                    </div>
                </a>
            </div>

            <!-- Compliance Trust Bar -->
            <div class="contact-trust-bar">
                <div class="trust-badge-item">
                    <?= icon('shield-check-line') ?>
                    <span>PESO Certified Depot</span>
                </div>
                <div class="trust-badge-sep">·</div>
                <div class="trust-badge-item">
                    <?= icon('truck-line') ?>
                    <span>&lt; 2-Hr Agra Dispatch</span>
                </div>
                <div class="trust-badge-sep">·</div>
                <div class="trust-badge-item">
                    <?= icon('flask-line') ?>
                    <span>99.9% Gas Purity</span>
                </div>
            </div>
        </aside>

        <!-- Right: Official Enquiry Form Panel -->
        <div class="contact-form-panel">
            <div class="contact-form-panel__header">
                <span class="form-badge"><?= icon('edit-2-line') ?> OFFICIAL ENQUIRY DESK</span>
                <h2>Request a Quotation or Consultation</h2>
                <p>Provide your facility and cylinder sizing requirements. Our Agra engineering team will provide a formalized commercial proposal.</p>
            </div>
            <?php $formId='contact'; require ROOT.'/views/partials/enquiry-form.php'; ?>
        </div>
    </div>
</section>

<!-- Direct Embedded Google Maps Section -->
<section class="map-section">
    <div class="container">
        <div class="map-embed-wrapper">
            <div class="map-embed-header">
                <div class="map-embed-header__info">
                    <span class="eyebrow"><?= icon('map-pin-2-line') ?> CENTRAL AGRA DEPOT &amp; REFILL STATION</span>
                    <h2>Find us in Pratappura, Agra</h2>
                    <p><?= e(setting('address')) ?> · 24/7 Priority Emergency Gas Supply &amp; Equipment Delivery Hub</p>
                </div>
                <div class="map-embed-header__actions">
                    <a class="btn btn-outline btn-sm" target="_blank" rel="noopener" href="https://www.google.com/maps/search/?api=1&query=<?= rawurlencode(setting('address')) ?>">
                        <?= icon('navigation-line') ?> Open in Google Maps <?= icon('arrow-right-up-line') ?>
                    </a>
                </div>
            </div>
            <div class="map-embed-container">
                <iframe 
                    src="https://maps.google.com/maps?q=<?= rawurlencode(setting('address')) ?>&t=&z=15&ie=UTF8&iwloc=&output=embed" 
                    width="100%" 
                    height="450" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade" 
                    title="Shree Ji Gas Industries location in Pratappura, Agra">
                </iframe>
            </div>
        </div>
    </div>
</section>
