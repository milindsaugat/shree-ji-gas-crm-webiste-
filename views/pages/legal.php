<section class="legal-hero-section">
    <div class="container">
        <nav class="breadcrumb legal-breadcrumb" aria-label="Breadcrumb">
            <a href="<?= url() ?>">Home</a>
            <span>/</span>
            <span class="breadcrumb-current"><?= e($page['title']) ?></span>
        </nav>
        <div class="legal-hero-badge">
            <span class="legal-badge-dot"></span>
            <span>STATUTORY TRANSPARENCY &amp; CARE COMPLIANCE · AGRA &amp; NCR</span>
        </div>
        <h1 class="legal-title"><?= e($page['title']) ?></h1>
        <?php if(!empty($page['excerpt'])): ?>
            <p class="legal-lead"><?= e($page['excerpt']) ?></p>
        <?php endif ?>
        <div class="legal-meta-bar">
            <span><?= icon('shield-check-line') ?> Verified Statutory Compliance</span>
            <span class="meta-divider">·</span>
            <span><?= icon('calendar-check-line') ?> Operating Standard: 2026–2027</span>
            <span class="meta-divider">·</span>
            <button type="button" class="btn-print-legal" onclick="window.print()">
                <?= icon('printer-line') ?> Print document
            </button>
        </div>
    </div>
</section>

<section class="legal-content-section">
    <div class="container">
        <div class="legal-layout-grid">
            <!-- Sidebar: Navigation between Legal Documents & Help Desk -->
            <aside class="legal-sidebar">
                <div class="legal-nav-card">
                    <h3 class="legal-nav-title"><?= icon('book-read-line') ?> Legal &amp; Policies</h3>
                    <nav class="legal-nav-menu" aria-label="Legal documents">
                        <a href="<?= url('privacy') ?>" class="legal-nav-link <?= ($page['slug']==='privacy')?'is-active':'' ?>">
                            <?= icon('shield-keyhole-line') ?>
                            <span>Privacy Policy (DPDP)</span>
                        </a>
                        <a href="<?= url('terms') ?>" class="legal-nav-link <?= ($page['slug']==='terms')?'is-active':'' ?>">
                            <?= icon('file-text-line') ?>
                            <span>Terms &amp; Conditions</span>
                        </a>
                        <a href="<?= url('compliance') ?>" class="legal-nav-link <?= ($page['slug']==='compliance')?'is-active':'' ?>">
                            <?= icon('award-line') ?>
                            <span>Safety &amp; Compliance</span>
                        </a>
                        <a href="<?= url('shipping-policy') ?>" class="legal-nav-link <?= ($page['slug']==='shipping-policy')?'is-active':'' ?>">
                            <?= icon('truck-line') ?>
                            <span>Emergency Delivery Policy</span>
                        </a>
                    </nav>
                </div>

                <div class="legal-contact-card">
                    <div class="legal-contact-icon">
                        <?= icon('customer-service-2-line') ?>
                    </div>
                    <h4>Regulatory Desk Support</h4>
                    <p>Have questions regarding hospital supply contracts or cylinder licensing?</p>
                    <a href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>" class="btn-legal-call">
                        <?= icon('phone-line') ?> <?= e(setting('phone')) ?>
                    </a>
                    <a href="mailto:<?= e(setting('email')) ?>" class="legal-email-link">
                        <?= e(setting('email')) ?>
                    </a>
                </div>
            </aside>

            <!-- Main Content Card -->
            <main class="legal-main-card">
                <article class="prose legal-prose">
                    <?= rich($page['content']) ?>
                </article>

                <div class="legal-document-footer">
                    <div class="legal-seal-badge">
                        <?= icon('verified-badge-line') ?>
                        <div>
                            <strong>Shree Ji Gas Industries</strong>
                            <small>33/103, Pratappura, Agra, Uttar Pradesh — 282001</small>
                        </div>
                    </div>
                    <div class="legal-footer-action">
                        <a href="<?= url('contact') ?>" class="btn btn-primary btn-sm">
                            Enquire with our team <?= icon('arrow-right-line') ?>
                        </a>
                    </div>
                </div>
            </main>
        </div>
    </div>
</section>
