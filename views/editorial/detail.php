<?php 
/**
 * Master Editorial Detail Template: Blog, Case Studies & Use Cases
 * High-performance, clean responsive storytelling architecture.
 */
?>
<?php if($type === 'blog'): ?>
    <!-- Blog Article Detail -->
    <header class="editorial-header">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?= url() ?>">Home</a>
                <span>/</span>
                <a href="<?= url('blog') ?>">Journal</a>
                <span>/</span>
                <span aria-current="page"><?= e($item['title']) ?></span>
            </nav>

            <div class="editorial-header__lead">
                <div class="editorial-kicker">
                    <span>EQUIPMENT PLANNING &amp; CLINICAL PERSPECTIVES</span>
                    <span class="editorial-kicker__dot"></span>
                    <span class="editorial-kicker__status"><?= icon('shield-check-line') ?> Verified Resource</span>
                </div>

                <h1 class="editorial-header__title"><?= e($item['title']) ?></h1>

                <div class="editorial-byline-strip">
                    <span class="byline-author">
                        <span class="byline-avatar"><?= mb_substr($item['author'] ?: 'S', 0, 1) ?></span>
                        <span class="byline-name">By <strong><?= e($item['author'] ?: 'Clinical Engineering Team') ?></strong></span>
                    </span>
                    <span class="byline-divider">·</span>
                    <span class="byline-time"><?= icon('calendar-line') ?> <time datetime="<?= date('Y-m-d', strtotime($item['published_at'] ?? 'now')) ?>"><?= date('d M Y', strtotime($item['published_at'] ?? 'now')) ?></time></span>
                    <span class="byline-divider">·</span>
                    <span class="byline-read"><?= icon('time-line') ?> <?= max(3, (int)ceil(str_word_count(strip_tags($item['content'] ?? '')) / 200)) ?> min read</span>
                    <span class="byline-divider">·</span>
                    <span class="byline-compliance"><?= icon('shield-check-line') ?> PESO Licensed Facility Agra</span>
                </div>
            </div>

            <?php if(!empty($item['thumbnail'])): ?>
            <div class="editorial-feature-cover">
                <img src="<?= photo($item['thumbnail']) ?>" alt="<?= e($item['image_alt'] ?: $item['title']) ?>" width="1240" height="520" fetchpriority="high">
                <div class="feature-cover-caption">
                    <?= icon('camera-lens-line') ?>
                    <span><?= e($item['image_alt'] ?: $item['title']) ?> — Shree Ji Gas Industries Depot Documentation</span>
                </div>
            </div>
            <?php endif ?>
        </div>
    </header>

    <div class="container article-layout">
        <div class="article-content prose" id="articleContent">
            <?php if(!empty($item['excerpt'])): ?>
                <p class="lead-text" style="font-size: 17px; line-height: 1.85; color: var(--color-primary); font-weight: 500; border-left: 3px solid var(--color-secondary); padding-left: 18px; margin-bottom: 30px;">
                    <?= e($item['excerpt']) ?>
                </p>
            <?php endif ?>

            <?= rich($item['content'] ?? '') ?>

            <div class="article-cta-spotlight">
                <h2>Planning gas infrastructure or clinical cylinder upgrades?</h2>
                <p>Direct supply line from our Agra licensed depot. Guaranteed 2-hour hospital emergency refills and PESO certified cylinder safety.</p>
                <div class="article-cta-spotlight__actions">
                    <a class="btn btn-primary" href="<?= url('contact') ?>">Discuss Your Requirements <?= icon('arrow-right-up-line') ?></a>
                    <a class="btn btn-whatsapp-action" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>" target="_blank" rel="noopener">WhatsApp Technical Desk <?= icon('whatsapp-line') ?></a>
                </div>
                <div class="article-cta-spotlight__meta">
                    <span><?= icon('truck-line') ?> 2-Hour Agra Clinical Turnaround</span>
                    <span><?= icon('shield-check-line') ?> Strict Hydrostatic Batch Inspections</span>
                    <span><?= icon('phone-line') ?> Hotline: <?= e(setting('phone')) ?></span>
                </div>
            </div>
        </div>

        <aside class="article-sidebar">
            <div class="sidebar-box sidebar-box--toc">
                <div class="sidebar-box__header">
                    <?= icon('list-check-2') ?>
                    <h3>In this article</h3>
                </div>
                <nav id="articleToc" class="article-toc-nav" aria-label="Table of contents"></nav>
            </div>

            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('phone-line') ?>
                    <h3>Central Dispatch Desk</h3>
                </div>
                <p class="sidebar-box__desc">Direct hotline to Agra depot dispatch supervisor for emergency oxygen &amp; medical gas delivery.</p>
                <a class="btn btn-primary sidebar-btn" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                    <?= icon('phone-line') ?> <?= e(setting('phone')) ?>
                </a>
                <div class="sidebar-box__trust">
                    <?= icon('checkbox-circle-fill') ?>
                    <span>PESO Licensed · &lt; 2-Hr Turnaround</span>
                </div>
            </div>

            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('share-forward-line') ?>
                    <h3>Share Article</h3>
                </div>
                <div class="sidebar-share-buttons">
                    <button type="button" class="sidebar-share-btn" id="copyLink"><?= icon('link') ?> Copy link</button>
                    <a class="sidebar-share-btn" href="https://wa.me/?text=<?= rawurlencode(SITE_URL.'/blog/'.$item['slug']) ?>" target="_blank" rel="noopener"><?= icon('whatsapp-line') ?> WhatsApp</a>
                </div>
            </div>

            <?php if(!empty($related)): ?>
            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('book-read-line') ?>
                    <h3>Keep exploring</h3>
                </div>
                <div class="sidebar-related-list">
                    <?php foreach($related as $r): ?>
                    <a class="sidebar-related-item" href="<?= url('blog/'.$r['slug']) ?>">
                        <strong><?= e($r['title']) ?></strong>
                        <div class="sidebar-related-meta">
                            <span>Read article <?= icon('arrow-right-line') ?></span>
                        </div>
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif ?>
        </aside>
    </div>

<?php elseif($type === 'case-studies'): ?>
    <!-- Case Study Detail -->
    <?php $metrics = json_decode($item['results'] ?: '{}', true) ?: []; ?>
    <header class="editorial-header">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?= url() ?>">Home</a>
                <span>/</span>
                <a href="<?= url('case-studies') ?>">Case Studies</a>
                <span>/</span>
                <span aria-current="page"><?= e($item['title']) ?></span>
            </nav>

            <div class="case-kicker-bar">
                <span class="case-kicker-tag"><?= icon('file-list-3-line') ?> VERIFIED CASE STUDY</span>
                <span class="case-kicker-sep">/</span>
                <span class="case-kicker-meta"><?= e($item['industry'] ?: 'Hospital Pipeline Installation') ?></span>
                <span class="case-kicker-sep">/</span>
                <span class="case-kicker-verified"><?= icon('shield-check-line') ?> Certified Deployment</span>
            </div>

            <h1 class="case-header__title"><?= e($item['title']) ?></h1>

            <div class="case-header__partner-meta">
                <span class="partner-meta-label">INSTITUTIONAL PARTNER</span>
                <span class="partner-meta-name"><?= e($item['client_name']) ?></span>
                <span class="partner-meta-badge"><?= icon('checkbox-circle-fill') ?> Agra &amp; Surrounding District</span>
            </div>

            <?php if(!empty($item['thumbnail'])): ?>
            <div class="case-feature-visual" style="margin-top: 28px;">
                <img src="<?= photo($item['thumbnail']) ?>" alt="<?= e($item['image_alt'] ?: $item['title']) ?>" width="1240" height="560" fetchpriority="high">
                <div class="case-feature-caption">
                    <?= icon('camera-lens-line') ?>
                    <span><?= e($item['image_alt'] ?: $item['title']) ?> — Field installation &amp; gas manifold verification</span>
                </div>
            </div>
            <?php endif ?>

            <?php if(!empty($metrics)): ?>
            <div class="case-impact-strip">
                <div class="impact-strip-lead">
                    <span class="impact-lead-kicker">MEASURED OUTCOMES</span>
                    <strong>Verified Operating Results</strong>
                </div>
                <div class="impact-metrics-row">
                    <?php foreach(array_slice($metrics, 0, 3) as $label => $value): ?>
                    <div class="impact-metric-item">
                        <div class="impact-num"><?= e($value) ?></div>
                        <div class="impact-txt"><?= e($label) ?></div>
                    </div>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif ?>
        </div>
    </header>

    <div class="container case-body">
        <div class="case-content" id="articleContent">
            <section class="case-section">
                <div class="case-section__header">
                    <span class="case-section__step">PHASE 01 — SITUATION &amp; CHALLENGE</span>
                    <h2>The Challenge</h2>
                </div>
                <div class="prose">
                    <?= rich($item['challenge'] ?? '') ?>
                </div>
            </section>

            <section class="case-section">
                <div class="case-section__header">
                    <span class="case-section__step">PHASE 02 — ENGINEERING &amp; DEPLOYMENT</span>
                    <h2>Our Engineered Solution</h2>
                </div>
                <div class="prose">
                    <?= rich($item['solution'] ?? '') ?>
                </div>
            </section>

            <?php if(!empty($metrics)): ?>
            <section class="case-section">
                <div class="case-section__header">
                    <span class="case-section__step">PHASE 03 — IMPACT SCOREBOARD</span>
                    <h2>Quantified Performance Metrics</h2>
                </div>
                <div class="case-scoreboard">
                    <?php foreach($metrics as $label => $value): ?>
                    <div class="scoreboard-cell">
                        <div class="scoreboard-value"><?= e($value) ?></div>
                        <div class="scoreboard-label"><?= e($label) ?></div>
                        <div class="scoreboard-sub"><?= icon('checkbox-circle-line') ?> Verified Metric</div>
                    </div>
                    <?php endforeach ?>
                </div>
            </section>
            <?php endif ?>

            <div class="article-cta-spotlight">
                <h2>Require an Engineered Gas Solution for Your Facility?</h2>
                <p>Consult with our gas pipeline and manifold engineers in Agra for safety audits, PESO documentation, and hospital continuous supply systems.</p>
                <div class="article-cta-spotlight__actions">
                    <a class="btn btn-primary" href="<?= url('contact') ?>">Request Engineering Consultation <?= icon('arrow-right-up-line') ?></a>
                    <a class="btn btn-whatsapp-action" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>" target="_blank" rel="noopener">WhatsApp Direct Desk <?= icon('whatsapp-line') ?></a>
                </div>
                <div class="article-cta-spotlight__meta">
                    <span><?= icon('shield-check-line') ?> Certified Engineers</span>
                    <span><?= icon('truck-line') ?> 2-Hour Agra Supply Buffer</span>
                    <span><?= icon('phone-line') ?> Dispatch Desk: <?= e(setting('phone')) ?></span>
                </div>
            </div>
        </div>

        <aside class="case-sidebar">
            <div class="case-factsheet">
                <div class="case-factsheet__header">
                    <?= icon('file-info-line') ?>
                    <h3>Case Factsheet</h3>
                </div>
                <dl class="case-factsheet__list">
                    <div class="case-factsheet__row">
                        <dt>Client Partner</dt>
                        <dd><?= e($item['client_name']) ?></dd>
                    </div>
                    <div class="case-factsheet__row">
                        <dt>Industry / Sector</dt>
                        <dd><?= e($item['industry'] ?: 'Healthcare') ?></dd>
                    </div>
                    <div class="case-factsheet__row">
                        <dt>Region Covered</dt>
                        <dd>Agra &amp; Western UP</dd>
                    </div>
                    <div class="case-factsheet__row">
                        <dt>Compliance Standard</dt>
                        <dd>PESO / IS:7285 Certified</dd>
                    </div>
                </dl>
            </div>

            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('phone-line') ?>
                    <h3>Central Dispatch Desk</h3>
                </div>
                <p class="sidebar-box__desc">Direct hotline to Agra depot dispatch supervisor for emergency supply &amp; support.</p>
                <a class="btn btn-primary sidebar-btn" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                    <?= icon('phone-line') ?> <?= e(setting('phone')) ?>
                </a>
                <div class="sidebar-box__trust">
                    <?= icon('checkbox-circle-fill') ?>
                    <span>PESO Licensed · &lt; 2-Hr Turnaround</span>
                </div>
            </div>

            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('share-forward-line') ?>
                    <h3>Share Case Study</h3>
                </div>
                <div class="sidebar-share-buttons">
                    <button type="button" class="sidebar-share-btn" id="copyLink"><?= icon('link') ?> Copy link</button>
                    <a class="sidebar-share-btn" href="https://wa.me/?text=<?= rawurlencode(SITE_URL.'/case-studies/'.$item['slug']) ?>" target="_blank" rel="noopener"><?= icon('whatsapp-line') ?> WhatsApp</a>
                </div>
            </div>

            <?php if(!empty($related)): ?>
            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('book-read-line') ?>
                    <h3>More Case Studies</h3>
                </div>
                <div class="sidebar-related-list">
                    <?php foreach($related as $r): ?>
                    <a class="sidebar-related-item" href="<?= url('case-studies/'.$r['slug']) ?>">
                        <strong><?= e($r['title']) ?></strong>
                        <div class="sidebar-related-meta">
                            <span>Read study <?= icon('arrow-right-line') ?></span>
                        </div>
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif ?>
        </aside>
    </div>

<?php else: ?>
    <!-- Use Cases Detail -->
    <header class="editorial-header">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?= url() ?>">Home</a>
                <span>/</span>
                <a href="<?= url('use-cases') ?>">Care Settings</a>
                <span>/</span>
                <span aria-current="page"><?= e($item['title']) ?></span>
            </nav>

            <div class="editorial-header__lead">
                <div class="editorial-kicker">
                    <span>CARE SETTINGS &amp; CLINICAL ENVIRONMENTS</span>
                    <span class="editorial-kicker__dot"></span>
                    <span class="editorial-kicker__status"><?= icon('hospital-line') ?> Clinical Setting</span>
                </div>

                <h1 class="editorial-header__title"><?= e($item['title']) ?></h1>

                <div class="editorial-byline-strip">
                    <span class="byline-author">
                        <span class="byline-name">Setting Profile: <strong>Agra Healthcare Infrastructure</strong></span>
                    </span>
                    <span class="byline-divider">·</span>
                    <span class="byline-time"><?= icon('truck-line') ?> 2-Hour Emergency Protocol</span>
                    <span class="byline-divider">·</span>
                    <span class="byline-compliance"><?= icon('shield-check-line') ?> PESO Certified Bulk &amp; Cylinder Depots</span>
                </div>
            </div>

            <?php if(!empty($item['thumbnail'])): ?>
            <div class="editorial-feature-cover">
                <img src="<?= photo($item['thumbnail']) ?>" alt="<?= e($item['image_alt'] ?: $item['title']) ?>" width="1240" height="520" fetchpriority="high">
                <div class="feature-cover-caption">
                    <?= icon('camera-lens-line') ?>
                    <span><?= e($item['image_alt'] ?: $item['title']) ?> — Specialized Gas Infrastructure Profile</span>
                </div>
            </div>
            <?php endif ?>
        </div>
    </header>

    <div class="container article-layout">
        <div class="article-content" id="articleContent">
            <div class="usecase-structured-narrative">
                <section class="usecase-narrative-section">
                    <div class="usecase-section-kicker">
                        <span class="usecase-badge">SETTING CONTEXT</span>
                        <span class="usecase-badge-meta">Operational Demands &amp; Clinical Setting</span>
                    </div>
                    <h2>Care in this setting</h2>
                    <div class="prose usecase-body-text">
                        <?= rich($item['application_context'] ?? '') ?>
                    </div>
                </section>

                <section class="usecase-narrative-section">
                    <div class="usecase-section-kicker">
                        <span class="usecase-badge">ENGINEERED PROVISION</span>
                        <span class="usecase-badge-meta">How Shree Ji Gas Industries Delivers</span>
                    </div>
                    <h2>Our equipment &amp; supply support</h2>
                    <div class="prose usecase-body-text">
                        <?= rich($item['benefits'] ?? '') ?>
                    </div>
                </section>
            </div>

            <div class="article-cta-spotlight">
                <h2>Equipping this healthcare environment?</h2>
                <p>Discuss manifold configurations, backup banks, and round-the-clock emergency delivery arrangements directly with our Agra dispatch engineers.</p>
                <div class="article-cta-spotlight__actions">
                    <a class="btn btn-primary" href="<?= url('contact') ?>">Request Infrastructure Consultation <?= icon('arrow-right-up-line') ?></a>
                    <a class="btn btn-whatsapp-action" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>" target="_blank" rel="noopener">WhatsApp Clinical Desk <?= icon('whatsapp-line') ?></a>
                </div>
                <div class="article-cta-spotlight__meta">
                    <span><?= icon('truck-line') ?> Rapid Agra Response</span>
                    <span><?= icon('shield-check-line') ?> Certified Equipment &amp; High-Pressure Manifolds</span>
                    <span><?= icon('phone-line') ?> Dispatch Desk: <?= e(setting('phone')) ?></span>
                </div>
            </div>
        </div>

        <aside class="article-sidebar">
            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('phone-line') ?>
                    <h3>Central Dispatch Desk</h3>
                </div>
                <p class="sidebar-box__desc">Direct hotline to Agra depot dispatch supervisor for emergency refills &amp; installation planning.</p>
                <a class="btn btn-primary sidebar-btn" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                    <?= icon('phone-line') ?> <?= e(setting('phone')) ?>
                </a>
                <div class="sidebar-box__trust">
                    <?= icon('checkbox-circle-fill') ?>
                    <span>PESO Licensed · &lt; 2-Hr Turnaround</span>
                </div>
            </div>

            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('share-forward-line') ?>
                    <h3>Share Profile</h3>
                </div>
                <div class="sidebar-share-buttons">
                    <button type="button" class="sidebar-share-btn" id="copyLink"><?= icon('link') ?> Copy link</button>
                    <a class="sidebar-share-btn" href="https://wa.me/?text=<?= rawurlencode(SITE_URL.'/use-cases/'.$item['slug']) ?>" target="_blank" rel="noopener"><?= icon('whatsapp-line') ?> WhatsApp</a>
                </div>
            </div>

            <?php if(!empty($related)): ?>
            <div class="sidebar-box">
                <div class="sidebar-box__header">
                    <?= icon('hospital-line') ?>
                    <h3>Other Settings</h3>
                </div>
                <div class="sidebar-related-list">
                    <?php foreach($related as $r): ?>
                    <a class="sidebar-related-item" href="<?= url('use-cases/'.$r['slug']) ?>">
                        <strong><?= e($r['title']) ?></strong>
                        <div class="sidebar-related-meta">
                            <span>Explore setting <?= icon('arrow-right-line') ?></span>
                        </div>
                    </a>
                    <?php endforeach ?>
                </div>
            </div>
            <?php endif ?>
        </aside>
    </div>
<?php endif ?>

<?php if(!empty($products)): ?>
<section class="section section-tint product-related-section">
    <div class="container">
        <div class="section-heading">
            <div>
                <span class="eyebrow">EXPLORE EQUIPMENT</span>
                <h2>Products for this setting</h2>
                <p>Certified clinical grade gas equipment and delivery cylinders tailored for this clinical setup.</p>
            </div>
        </div>
        <div class="product-grid related-product-grid">
            <?php foreach($products as $product) productCard($product); ?>
        </div>
    </div>
</section>
<?php endif ?>
