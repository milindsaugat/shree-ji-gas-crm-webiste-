<div class="product-page-wrapper">
    <div class="container">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="<?= url() ?>"><?= icon('home-4-line') ?> Home</a>
            <span>/</span>
            <a href="<?= url('products') ?>">Products</a>
            <span>/</span>
            <a href="<?= url('categories/'.($category['slug']??'')) ?>"><?= e($category['name']??'Equipment') ?></a>
            <span>/</span>
            <span aria-current="page"><?= e($product['name']) ?></span>
        </nav>
    </div>

    <!-- Product Showcase & Ordering Matrix -->
    <section class="product-detail container">
        <!-- Left: Equipment Inspection Gallery -->
        <div class="product-gallery">
            <div class="product-gallery__main-wrap visual-image-container" data-field="thumbnail">
                <a href="<?= photo($product['thumbnail']) ?>" class="glightbox main-product-image" id="mainProductLink" data-gallery="product" aria-label="Enlarge technical photo of <?= e($product['name']) ?>">
                    <img id="mainProductImage" data-preview="thumbnail" src="<?= photo($product['thumbnail']) ?>" alt="<?= e($product['image_alt'] ?: $product['name']) ?>" width="800" height="600" fetchpriority="high">
                    <span class="product-gallery__zoom"><?= icon('zoom-in-line') ?> Inspect high-resolution</span>
                </a>
                <div class="visual-image-overlay">
                    <div class="visual-image-prompt">
                        <span><?= icon('image-edit-line') ?> Drag &amp; drop to replace photo</span>
                        <div class="visual-img-btn-row">
                            <button type="button" class="btn-img-action" onclick="parent.postMessage({type:'visual-open-media-picker'},'*')">Choose Library</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php 
            $galleryImages = array_merge([$product['thumbnail']], json_decode($product['gallery'] ?: '[]', true) ?: []);
            if(count($galleryImages) > 1): 
            ?>
            <div class="gallery-thumbs" aria-label="Product thumbnails">
                <?php foreach($galleryImages as $index => $image): 
                    $thumbPath = is_array($image) ? $image['path'] : $image;
                    $thumbAlt = is_array($image) ? ($image['alt'] ?? $product['name']) : $product['name'];
                ?>
                <a class="glightbox gallery-thumb <?= $index === 0 ? 'is-active' : '' ?>" data-gallery="product" href="<?= photo($thumbPath) ?>">
                    <img src="<?= photo($thumbPath) ?>" alt="<?= e($thumbAlt) ?>" width="90" height="75" loading="lazy">
                </a>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <!-- Clinical & Industrial Assurance Strip -->
            <div class="product-guarantee-box">
                <div class="product-guarantee-item">
                    <?= icon('shield-check-line') ?>
                    <div>
                        <strong>Hydro-Tested &amp; Verified</strong>
                        <small>Strict compliance with Gas Cylinder Rules &amp; PESO</small>
                    </div>
                </div>
                <div class="product-guarantee-item">
                    <?= icon('truck-line') ?>
                    <div>
                        <strong>Same-Day Dispatch in Agra</strong>
                        <small>Emergency hospital priority logistics network</small>
                    </div>
                </div>
                <div class="product-guarantee-item">
                    <?= icon('tools-line') ?>
                    <div>
                        <strong>Full Fitting Support</strong>
                        <small>Compatible valves, regulators &amp; manifold testing</small>
                    </div>
                </div>
            </div>
            <p class="form-note"><?= icon('information-line') ?> Images represent actual stock equipment. Final quotation confirms exact manifold fittings and pressure specifications.</p>
        </div>

        <!-- Right: Technical Specification & Procurement Console -->
        <div class="product-info">
            <div class="product-info__header-meta">
                <span class="product-category-pill">
                    <?= icon('checkbox-circle-line') ?>
                    <?= e($category['name'] ?? 'Medical & Industrial Equipment') ?>
                </span>
                <?php if(!empty($product['badge_label'])): ?>
                <span class="product-badge-accent" data-preview="badge_label">
                    <?= icon('star-fill') ?> <?= e($product['badge_label']) ?>
                </span>
                <?php endif ?>
                <span class="product-stock-pill">
                    <span class="stock-dot"></span> In Stock · Rapid Dispatch
                </span>
            </div>

            <h1 class="product-title" data-preview="name"><?= e($product['name']) ?></h1>
            <p class="lead-text" data-preview="short_desc"><?= e($product['short_desc']) ?></p>

            <?php if(!empty($product['tags'])): ?>
            <div class="product-tag-matrix" aria-label="Equipment features">
                <?php foreach(explode(',', $product['tags']) as $tag): if(trim($tag)==='') continue; ?>
                <span class="product-tag-chip"><?= icon('check-line') ?> <?= e(trim($tag)) ?></span>
                <?php endforeach ?>
            </div>
            <?php endif ?>

            <!-- Shopify-Style Certification & Lifecycle Meta Strip (Clean, Unboxed) -->
            <?php if(!empty($product['mfc_date']) || !empty($product['exp_date'])): ?>
            <div class="shopify-product-meta" aria-label="Statutory Certification and Dates">
                <div class="shopify-product-meta__tags">
                    <span class="shopify-meta-badge shopify-meta-badge--peso">
                        <?= icon('shield-check-line') ?> PESO / IS:7285 Certified
                    </span>
                    <span class="shopify-meta-badge shopify-meta-badge--qa">
                        <?= icon('check-double-line') ?> Factory QA Verified
                    </span>
                </div>
                <div class="shopify-product-meta__dates">
                    <?php if(!empty($product['mfc_date'])): ?>
                    <div class="shopify-date-item">
                        <span class="shopify-date-item__label">MFC Date:</span>
                        <strong class="shopify-date-item__value" data-preview="mfc_date"><?= e($product['mfc_date']) ?></strong>
                    </div>
                    <?php endif ?>
                    <?php if(!empty($product['mfc_date']) && !empty($product['exp_date'])): ?>
                    <span class="shopify-date-sep" aria-hidden="true">•</span>
                    <?php endif ?>
                    <?php if(!empty($product['exp_date'])): ?>
                    <div class="shopify-date-item">
                        <span class="shopify-date-item__label">Hydro-Test Valid:</span>
                        <strong class="shopify-date-item__value" data-preview="exp_date"><?= e($product['exp_date']) ?></strong>
                        <span class="shopify-date-item__note">(5-Yr PESO Cycle)</span>
                    </div>
                    <?php endif ?>
                </div>
            </div>
            <?php endif ?>

            <!-- Shopify-Style Technical Parameters List (Minimalist Clean Key-Value Structure) -->
            <?php 
            $specs = json_decode($product['specs'] ?: '{}', true) ?: []; 
            if(!empty($specs)):
            ?>
            <div class="shopify-specs-table" aria-label="Key Technical Specifications">
                <div class="shopify-specs-table__header">
                    <span class="shopify-specs-table__title">Specifications Overview</span>
                    <a href="#panel-specifications" onclick="document.getElementById('tab-specifications')?.click();" class="shopify-specs-table__link">Full Data Sheet <?= icon('arrow-down-s-line') ?></a>
                </div>
                <dl class="shopify-specs-list">
                    <?php 
                    $specKeys = array_keys($specs);
                    $previewSpecs = array_slice($specs, 0, 4, true);
                    foreach($previewSpecs as $label => $val): 
                    ?>
                    <div class="shopify-spec-row">
                        <dt class="shopify-spec-name"><?= e($label) ?></dt>
                        <dd class="shopify-spec-val" data-spec-key="<?= e($label) ?>"><?= e($val) ?></dd>
                    </div>
                    <?php endforeach ?>
                </dl>
            </div>
            <?php endif ?>

            <?php if(!empty($product['features'])): ?>
            <div class="product-key-points">
                <span class="product-key-points__title"><?= icon('award-line') ?> Key Operational Features:</span>
                <div class="prose product-features-content" data-preview="features">
                    <?= rich($product['features']) ?>
                </div>
            </div>
            <?php endif ?>

            <!-- Dual Primary Procurement Actions -->
            <div class="product-procurement-deck">
                <button type="button" class="btn btn-primary btn-quote-action" data-bs-toggle="modal" data-bs-target="#enquiryModal" data-product="<?= e($product['name']) ?>">
                    <span>Enquire now</span>
                    <?= icon('arrow-right-up-line') ?>
                </button>
                <a class="btn btn-whatsapp-action" target="_blank" rel="noopener" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>?text=<?= rawurlencode('Hello Shree Ji Gas Industries, I would like to inquire about specifications, pricing, and availability for: '.$product['name']) ?>">
                    <?= icon('whatsapp-line') ?>
                    <span>Instant WhatsApp Order</span>
                </a>
            </div>

            <?php if(!empty($product['brochure_pdf'])): ?>
            <div class="product-brochure-bar">
                <a class="product-brochure-btn" href="<?= photo($product['brochure_pdf']) ?>" download>
                    <?= icon('file-pdf-line') ?>
                    <span>
                        <strong>Download Technical Data Sheet (PDF)</strong>
                        <small>Full dimensional drawings, pressure ratings &amp; test certificates</small>
                    </span>
                    <?= icon('download-line') ?>
                </a>
            </div>
            <?php endif ?>

            <div class="product-dispatch-callout">
                <span class="dispatch-callout__icon"><?= icon('customer-service-2-line') ?></span>
                <div class="dispatch-callout__text">
                    <strong>Need Bulk Cylinder Manifolds or Hospital Pipeline Integration?</strong>
                    <p>Speak directly with our technical gas specialist at <a href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>"><strong><?= e(setting('phone')) ?></strong></a> for compatibility and manifold sizing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Detailed Technical Dossier (Tabbed Interface) -->
    <section class="section section-tint product-dossier-section">
        <div class="container" x-data="{tab:'description'}">
            <div class="dossier-tabs-nav" role="tablist" aria-label="Technical Documentation Tabs">
                <button type="button" id="tab-description" role="tab" aria-controls="panel-description" data-tab="description" :aria-selected="tab==='description'" :class="{'is-active':tab==='description'}" @click="tab='description'" class="is-active" aria-selected="true"><?= icon('article-line') ?> Description</button>
                <button type="button" id="tab-specifications" role="tab" aria-controls="panel-specifications" data-tab="specifications" :aria-selected="tab==='specifications'" :class="{'is-active':tab==='specifications'}" @click="tab='specifications'" aria-selected="false"><?= icon('table-line') ?> Specifications</button>
                <button type="button" id="tab-applications" role="tab" aria-controls="panel-applications" data-tab="applications" :aria-selected="tab==='applications'" :class="{'is-active':tab==='applications'}" @click="tab='applications'" aria-selected="false"><?= icon('hospital-line') ?> Applications</button>
                <button type="button" id="tab-safety" role="tab" aria-controls="panel-safety" data-tab="safety" :aria-selected="tab==='safety'" :class="{'is-active':tab==='safety'}" @click="tab='safety'" aria-selected="false"><?= icon('shield-cross-line') ?> Safety</button>
            </div>

            <!-- Tab 1: Description (Full-Width Engineering Dossier) -->
            <div class="tab-panel dossier-panel dossier-panel--full" id="panel-description" role="tabpanel" aria-labelledby="tab-description" x-show="tab==='description'">
                <div class="dossier-panel__header dossier-panel__header--flex">
                    <div>
                        <span class="dossier-eyebrow"><?= icon('shield-check-line') ?> Clinical Gas Engineering · Technical Briefing</span>
                        <h3>Equipment Overview &amp; Engineering Details</h3>
                        <p>Comprehensive operational guidance, compliance protocols &amp; installation requisites for <?= e($product['name']) ?></p>
                    </div>
                    <div class="dossier-header-seal">
                        <?= icon('verified-badge-line') ?>
                        <span>Certified Medical Device · QA Inspected</span>
                    </div>
                </div>

                <div class="dossier-desc-grid">
                    <!-- Main Engineering Text & Capabilities Matrix -->
                    <div class="dossier-desc-main">
                        <div class="dossier-prose" data-preview="description">
                            <?= rich($product['description'] ?: $product['short_desc']) ?>
                        </div>

                        <!-- 4-Card Operational Capability Matrix (In-Place Live Editable) -->
                        <?php
                        $defaultCapabilities = [
                            [
                                'title' => 'Purity & Pharmacopoeia Grade',
                                'desc' => 'Certified 99.5%+ pure medical grade gas tested via gas chromatography, strictly adhering to Indian Pharmacopoeia (IP) standards.',
                                'color' => 'cyan',
                                'icon' => 'drop-line'
                            ],
                            [
                                'title' => 'Hydraulic Stretch Integrity',
                                'desc' => 'Hydrostatically tested to 250 bar hydraulic test pressure for guaranteed structural resilience at standard 150 bar working fill.',
                                'color' => 'blue',
                                'icon' => 'speed-line'
                            ],
                            [
                                'title' => 'PESO Statutory Certification',
                                'desc' => 'Approved by PESO under Indian Gas Cylinder Rules 2016 with permanent collar date stamping and statutory 5-year re-test cycles.',
                                'color' => 'green',
                                'icon' => 'shield-check-line'
                            ],
                            [
                                'title' => 'Hospital Pipeline & Manifold Ready',
                                'desc' => 'Seamlessly integrates with medical gas pipeline systems (MGPS), central manifolds, ICU mechanical ventilators, and portable regulators.',
                                'color' => 'teal',
                                'icon' => 'stethoscope-line'
                            ]
                        ];
                        $parsedCaps = [];
                        if (!empty($product['features'])) {
                            if (preg_match_all('~<li>\s*(?:<strong>(.*?)</strong>:?\s*)?(.*?)\s*</li>~is', $product['features'], $mCaps, PREG_SET_ORDER)) {
                                foreach ($mCaps as $i => $m) {
                                    $cTitle = trim(strip_tags($m[1] ?? ''));
                                    $cDesc = trim(strip_tags($m[2] ?? ''));
                                    if (!$cTitle && $cDesc) {
                                        if (str_contains($cDesc, ':')) {
                                            [$cTitle, $cDesc] = explode(':', $cDesc, 2);
                                        }
                                    }
                                    if (!$cTitle || str_starts_with(strtolower($cTitle), 'feature')) {
                                        $cTitle = $defaultCapabilities[$i % 4]['title'];
                                    }
                                    if (!$cDesc) {
                                        $cDesc = $defaultCapabilities[$i % 4]['desc'];
                                    }
                                    $parsedCaps[] = [
                                        'title' => trim($cTitle),
                                        'desc' => trim($cDesc),
                                        'color' => $defaultCapabilities[$i % 4]['color'],
                                        'icon' => $defaultCapabilities[$i % 4]['icon']
                                    ];
                                }
                            }
                        }
                        // Guarantee all 4 capability cards are always present with rich content
                        while (count($parsedCaps) < 4) {
                            $idx = count($parsedCaps);
                            $parsedCaps[] = $defaultCapabilities[$idx % 4];
                        }
                        ?>
                        <div class="dossier-highlights-grid" data-features-matrix>
                            <?php foreach($parsedCaps as $idx => $cap): ?>
                            <div class="dossier-highlight-card" data-cap-idx="<?= $idx ?>">
                                <div class="highlight-icon-wrap highlight-icon--<?= e($cap['color']) ?>">
                                    <?= icon($cap['icon']) ?>
                                </div>
                                <div class="highlight-info">
                                    <h4 data-cap-field="title"><?= e($cap['title']) ?></h4>
                                    <p data-cap-field="desc"><?= e($cap['desc']) ?></p>
                                </div>
                            </div>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <!-- Sidebar: Verification Register & Instant Specialist Desk -->
                    <aside class="dossier-desc-sidebar">
                        <div class="dossier-sidebar-card dossier-sidebar-card--assurance">
                            <div class="sidebar-card__header">
                                <span class="pulse-indicator"></span>
                                <strong data-preview="badge_label"><?= e(!empty($product['badge_label']) ? $product['badge_label'] : 'Verified Supply Assurance') ?></strong>
                            </div>
                            <ul class="dossier-meta-list">
                                <li>
                                    <span class="meta-label"><?= icon('check-line') ?> Category</span>
                                    <strong class="meta-value" data-spec-key="Category"><?= e($specs['Category'] ?? ($category['name'] ?? 'Medical Gas')) ?></strong>
                                </li>
                                <li>
                                    <span class="meta-label"><?= icon('check-line') ?> Standard</span>
                                    <strong class="meta-value" data-spec-key="Standard"><?= e($specs['Standard'] ?? 'IS:7285 / IS:3224') ?></strong>
                                </li>
                                <li>
                                    <span class="meta-label"><?= icon('check-line') ?> Cylinder Shell</span>
                                    <strong class="meta-value" data-spec-key="Cylinder Shell"><?= e($specs['Cylinder Shell'] ?? 'Seamless Steel Alloy') ?></strong>
                                </li>
                                <li>
                                    <span class="meta-label"><?= icon('check-line') ?> Valve Standard</span>
                                    <strong class="meta-value" data-spec-key="Valve Standard"><?= e($specs['Valve Standard'] ?? 'High-Pressure Brass') ?></strong>
                                </li>
                                <li>
                                    <span class="meta-label"><?= icon('check-line') ?> Testing Cycle</span>
                                    <strong class="meta-value" data-spec-key="Testing Cycle"><?= e($specs['Testing Cycle'] ?? (!empty($product['exp_date']) ? $product['exp_date'] : '5-Yr PESO Hydro-Test')) ?></strong>
                                </li>
                            </ul>
                        </div>

                        <div class="dossier-sidebar-card dossier-sidebar-card--inquiry">
                            <div class="inquiry-card-icon">
                                <?= icon('customer-service-2-line') ?>
                            </div>
                            <h4 data-spec-key="Inquiry Title"><?= e($specs['Inquiry Title'] ?? 'Need Custom Pressure or Bulk Sizing?') ?></h4>
                            <p data-spec-key="Inquiry Text"><?= e($specs['Inquiry Text'] ?? 'Consult directly with our clinical gas specialists for manifold layouts, cylinder bank sizing, and hospital pipeline supply.') ?></p>
                            <div class="inquiry-card-actions">
                                <a href="https://wa.me/<?= e(preg_replace('/[^0-9]/','',setting('whatsapp') ?: setting('phone'))) ?>?text=<?= urlencode('Hi Shree Ji Gas, I am inquiring about technical specifications for '.$product['name']) ?>" class="btn-whatsapp-dossier" target="_blank" rel="noopener">
                                    <?= icon('whatsapp-line') ?> <span>WhatsApp Specialist</span>
                                </a>
                                <a href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>" class="btn-phone-dossier">
                                    <?= icon('phone-line') ?> <span><?= e(setting('phone')) ?></span>
                                </a>
                            </div>
                        </div>

                        <?php if(!empty($product['brochure_pdf'])): ?>
                        <a href="<?= photo($product['brochure_pdf']) ?>" download class="dossier-pdf-download">
                            <?= icon('file-pdf-line') ?>
                            <div>
                                <strong>Technical Data Sheet (PDF)</strong>
                                <small>Full engineering dimensional drawings &amp; specs</small>
                            </div>
                            <?= icon('download-line') ?>
                        </a>
                        <?php endif ?>
                    </aside>
                </div>
            </div>

            <!-- Tab 2: Full Specifications Table -->
            <div class="tab-panel dossier-panel dossier-panel--full" id="panel-specifications" role="tabpanel" aria-labelledby="tab-specifications" x-show="tab==='specifications'" x-cloak>
                <div class="dossier-panel__header dossier-panel__header--flex">
                    <div>
                        <span class="dossier-eyebrow" data-spec-key="Spec Eyebrow"><?= icon('table-line') ?> <?= e($specs['Spec Eyebrow'] ?? 'Certified Parameters') ?></span>
                        <h3 data-spec-key="Spec Heading"><?= e($specs['Spec Heading'] ?? 'Technical Specifications Data Sheet') ?></h3>
                        <p data-spec-key="Spec Subtitle"><?= e($specs['Spec Subtitle'] ?? 'Certified parameters tested under standard pressure and temperature conditions (NTP: 20°C, 101.325 kPa).') ?></p>
                    </div>
                    <div class="dossier-header-seal">
                        <?= icon('shield-check-line') ?>
                        <span data-spec-key="Spec Seal"><?= e($specs['Spec Seal'] ?? 'PESO & IS:7285 / IS:3098 Standard') ?></span>
                    </div>
                </div>
                <div class="spec-table-container">
                    <table class="spec-data-table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 38%;">Technical Parameter</th>
                                <th scope="col" style="width: 32%;">Certified Specification</th>
                                <th scope="col" style="width: 30%;">Quality Protocol / Standard</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row" class="spec-data-table__key">
                                    <span class="spec-icon-badge"><?= icon('calendar-check-line') ?></span>
                                    <span>Manufacturing Date (MFC)</span>
                                </th>
                                <td class="spec-data-table__val">
                                    <strong data-preview="mfc_date"><?= e(!empty($product['mfc_date']) ? $product['mfc_date'] : 'August 2024') ?></strong>
                                </td>
                                <td class="spec-data-table__std">
                                    <span class="compliance-tag compliance-tag--qa">Batch Stamped · QA Verified</span>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row" class="spec-data-table__key">
                                    <span class="spec-icon-badge"><?= icon('history-line') ?></span>
                                    <span>Hydro-Test Validity / Expiry</span>
                                </th>
                                <td class="spec-data-table__val">
                                    <strong data-preview="exp_date"><?= e(!empty($product['exp_date']) ? $product['exp_date'] : 'August 2029 (5-Yr Hydro-Test)') ?></strong>
                                </td>
                                <td class="spec-data-table__std">
                                    <span class="compliance-tag compliance-tag--peso">Statutory 5-Year PESO Cycle</span>
                                </td>
                            </tr>
                            <?php 
                            $internalPrefixes = ['app ', 'sector ', 'rule ', 'safety ', 'directive ', 'inquiry ', 'related ', 'spec ', 'tag_', 'tag '];
                            foreach($specs as $key => $value): 
                                $lowerKey = strtolower((string)$key);
                                $isInternal = false;
                                foreach($internalPrefixes as $pfx) {
                                    if(str_starts_with($lowerKey, $pfx)) { $isInternal = true; break; }
                                }
                                if ($isInternal) continue;

                                $tagClass = 'compliance-tag--std';
                                $tagLabel = 'PESO / IS Certified';
                                if(str_contains($lowerKey, 'purity')) {
                                    $tagClass = 'compliance-tag--purity';
                                    $tagLabel = 'Gas Chromatography (99.5%+)';
                                } elseif(str_contains($lowerKey, 'pressure') || str_contains($lowerKey, 'bar')) {
                                    $tagClass = 'compliance-tag--pressure';
                                    $tagLabel = 'Hydraulic Stretch Tested';
                                } elseif(str_contains($lowerKey, 'capacity') || str_contains($lowerKey, 'volume')) {
                                    $tagClass = 'compliance-tag--qa';
                                    $tagLabel = 'Water Capacity Verified';
                                } elseif(str_contains($lowerKey, 'valve') || str_contains($lowerKey, 'collar')) {
                                    $tagClass = 'compliance-tag--std';
                                    $tagLabel = 'IS:3224 Safety Valve';
                                }
                                $renderedTag = $specs['Tag_'.$key] ?? $tagLabel;

                                // Contextual icon mapping so every parameter has a matching badge (no lonely bullet points)
                                $paramIcon = 'checkbox-circle-line';
                                if (str_contains($lowerKey, 'purity') || str_contains($lowerKey, 'grade')) {
                                    $paramIcon = 'flask-line';
                                } elseif (str_contains($lowerKey, 'pressure') || str_contains($lowerKey, 'bar') || str_contains($lowerKey, 'psi')) {
                                    $paramIcon = 'speed-line';
                                } elseif (str_contains($lowerKey, 'capacity') || str_contains($lowerKey, 'volume') || str_contains($lowerKey, 'water')) {
                                    $paramIcon = 'drop-line';
                                } elseif (str_contains($lowerKey, 'valve') || str_contains($lowerKey, 'collar') || str_contains($lowerKey, 'spindle')) {
                                    $paramIcon = 'tools-line';
                                } elseif (str_contains($lowerKey, 'shell') || str_contains($lowerKey, 'material') || str_contains($lowerKey, 'cylinder') || str_contains($lowerKey, 'alloy')) {
                                    $paramIcon = 'shield-check-line';
                                } elseif (str_contains($lowerKey, 'standard') || str_contains($lowerKey, 'compliance') || str_contains($lowerKey, 'is:')) {
                                    $paramIcon = 'award-line';
                                } elseif (str_contains($lowerKey, 'testing') || str_contains($lowerKey, 'test') || str_contains($lowerKey, 'cycle')) {
                                    $paramIcon = 'shield-star-line';
                                } elseif (str_contains($lowerKey, 'category') || str_contains($lowerKey, 'type')) {
                                    $paramIcon = 'price-tag-3-line';
                                } elseif (str_contains($lowerKey, 'availability') || str_contains($lowerKey, 'dispatch') || str_contains($lowerKey, 'delivery')) {
                                    $paramIcon = 'truck-line';
                                } elseif (str_contains($lowerKey, 'weight') || str_contains($lowerKey, 'tare') || str_contains($lowerKey, 'mass')) {
                                    $paramIcon = 'scales-3-line';
                                } elseif (str_contains($lowerKey, 'temperature') || str_contains($lowerKey, 'temp')) {
                                    $paramIcon = 'temp-cold-line';
                                } elseif (str_contains($lowerKey, 'flow') || str_contains($lowerKey, 'rate')) {
                                    $paramIcon = 'pulse-line';
                                }
                            ?>
                            <tr class="spec-data-row" data-spec-row="<?= e($key) ?>">
                                <th scope="row" class="spec-data-table__key">
                                    <span class="spec-icon-badge"><?= icon($paramIcon) ?></span>
                                    <span class="spec-param-name" data-spec-param-name="<?= e($key) ?>" title="Click to edit parameter name"><?= e($key) ?></span>
                                </th>
                                <td class="spec-data-table__val">
                                    <strong data-spec-key="<?= e($key) ?>" title="Click to edit specification"><?= e($value) ?></strong>
                                </td>
                                <td class="spec-data-table__std">
                                    <span class="compliance-tag <?= $tagClass ?>" data-spec-key="Tag_<?= e($key) ?>" data-spec-tag-name="<?= e($key) ?>" title="Click to edit compliance standard"><?= e($renderedTag) ?></span>
                                    <button type="button" class="spec-row-delete-btn" data-del-spec="<?= e($key) ?>" title="Delete parameter" aria-label="Delete parameter">×</button>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>

                <div class="spec-table-add-action" style="margin-top: 16px;">
                    <button type="button" class="btn btn-outline btn-sm" id="canvasAddSpecRowBtn" style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer; padding: 8px 16px; border: 1px dashed #2e8fa8; background: #ffffff; border-radius: 6px; color: #1b3d6f; font-weight: 600;">
                        <?= icon('add-line') ?> <span>+ Add Technical Parameter Row</span>
                    </button>
                </div>
            </div>

            <!-- Tab 3: Applications (Full-Width Register) -->
            <div class="tab-panel dossier-panel dossier-panel--full" id="panel-applications" role="tabpanel" aria-labelledby="tab-applications" x-show="tab==='applications'" x-cloak>
                <div class="dossier-panel__header dossier-panel__header--flex">
                    <div>
                        <span class="dossier-eyebrow" data-spec-key="App Eyebrow"><?= icon('hospital-line') ?> <?= e($specs['App Eyebrow'] ?? 'Facility Deployment & Compatibility') ?></span>
                        <h3 data-spec-key="App Heading"><?= e($specs['App Heading'] ?? 'Deployment Scenarios & Clinical Compatibility') ?></h3>
                        <p data-spec-key="App Subtitle"><?= e($specs['App Subtitle'] ?? ('Certified operational workflows, facilities, and care environments utilizing '.($product['name'] ?? 'equipment'))) ?></p>
                    </div>
                    <div class="dossier-header-seal">
                        <?= icon('building-2-line') ?>
                        <span data-spec-key="App Seal"><?= e($specs['App Seal'] ?? 'Healthcare & Industrial Certified') ?></span>
                    </div>
                </div>

                <div class="deployment-scope-banner">
                    <div class="scope-icon"><?= icon('information-line') ?></div>
                    <div class="scope-text">
                        <strong data-spec-key="Directive Title"><?= e($specs['Directive Title'] ?? 'Clinical Deployment Directive:') ?></strong>
                        <p data-preview="applications"><?= e(trim(strip_tags($product['applications'] ?? 'Discuss your intended application with a qualified professional and our equipment team. We can help identify available products for hospitals, clinics and supervised home care.'))) ?></p>
                    </div>
                </div>

                <div class="deployment-grid">
                    <!-- Sector 01: Hospitals & ICU -->
                    <div class="deployment-card">
                        <div class="deployment-card__top">
                            <span class="deployment-pill deployment-pill--cyan" data-spec-key="Sector 01 Pill"><?= e($specs['Sector 01 Pill'] ?? 'SECTOR 01 · CRITICAL CARE') ?></span>
                            <div class="deployment-icon-box deployment-icon-box--cyan" title="Hospital & Critical Care">
                                <?= icon('hospital-line') ?>
                            </div>
                        </div>
                        <h4 class="deployment-card__title" data-spec-key="Sector 01 Title"><?= e($specs['Sector 01 Title'] ?? 'Hospitals & Intensive Care Units (ICU)') ?></h4>
                        <p class="deployment-card__desc" data-spec-key="Sector 01 Desc"><?= e($specs['Sector 01 Desc'] ?? 'Continuous medical gas supply, mechanical ventilation, high-flow nasal cannula therapy, and emergency resuscitation in critical care wards.') ?></p>
                        <div class="deployment-card__footer">
                            <span class="deployment-badge-pill deployment-badge-pill--green" data-spec-key="Sector 01 Badge">
                                <?= icon('check-double-line') ?> <?= e($specs['Sector 01 Badge'] ?? 'High-Volume Manifold Ready') ?>
                            </span>
                            <span class="deployment-meta-stat" data-spec-key="Sector 01 Stat"><?= e($specs['Sector 01 Stat'] ?? '24/7 Verified Flow') ?></span>
                        </div>
                    </div>

                    <!-- Sector 02: Ambulance & Trauma -->
                    <div class="deployment-card">
                        <div class="deployment-card__top">
                            <span class="deployment-pill deployment-pill--red" data-spec-key="Sector 02 Pill"><?= e($specs['Sector 02 Pill'] ?? 'SECTOR 02 · EMERGENCY TRANSIT') ?></span>
                            <div class="deployment-icon-box deployment-icon-box--red" title="Trauma & Mobile Fleets">
                                <?= icon('first-aid-kit-line') ?>
                            </div>
                        </div>
                        <h4 class="deployment-card__title" data-spec-key="Sector 02 Title"><?= e($specs['Sector 02 Title'] ?? 'Trauma, Casualty & Mobile Ambulance Fleets') ?></h4>
                        <p class="deployment-card__desc" data-spec-key="Sector 02 Desc"><?= e($specs['Sector 02 Desc'] ?? 'Rapid response portable cylinder integration with vibration-resistant pin-index click regulators for seamless patient transit.') ?></p>
                        <div class="deployment-card__footer">
                            <span class="deployment-badge-pill deployment-badge-pill--blue" data-spec-key="Sector 02 Badge">
                                <?= icon('truck-line') ?> <?= e($specs['Sector 02 Badge'] ?? 'Mobile Transport Compliant') ?>
                            </span>
                            <span class="deployment-meta-stat" data-spec-key="Sector 02 Stat"><?= e($specs['Sector 02 Stat'] ?? 'Rapid Mobilization') ?></span>
                        </div>
                    </div>

                    <!-- Sector 03: Diagnostics & Research Labs -->
                    <div class="deployment-card">
                        <div class="deployment-card__top">
                            <span class="deployment-pill deployment-pill--purple" data-spec-key="Sector 03 Pill"><?= e($specs['Sector 03 Pill'] ?? 'SECTOR 03 · DIAGNOSTICS & LABS') ?></span>
                            <div class="deployment-icon-box deployment-icon-box--purple" title="Pathology & Biomedical">
                                <?= icon('flask-line') ?>
                            </div>
                        </div>
                        <h4 class="deployment-card__title" data-spec-key="Sector 03 Title"><?= e($specs['Sector 03 Title'] ?? 'Pathology Labs, Biomedical & Research Centers') ?></h4>
                        <p class="deployment-card__desc" data-spec-key="Sector 03 Desc"><?= e($specs['Sector 03 Desc'] ?? 'Ultra-pure calibration gases, medical laboratory analyzers, incubation atmospheres, and controlled clinical test apparatus.') ?></p>
                        <div class="deployment-card__footer">
                            <span class="deployment-badge-pill deployment-badge-pill--purple" data-spec-key="Sector 03 Badge">
                                <?= icon('test-tube-line') ?> <?= e($specs['Sector 03 Badge'] ?? 'Analytical Purity Assured') ?>
                            </span>
                            <span class="deployment-meta-stat" data-spec-key="Sector 03 Stat"><?= e($specs['Sector 03 Stat'] ?? '99.5%+ Certified') ?></span>
                        </div>
                    </div>

                    <!-- Sector 04: Day Clinics & Home Healthcare -->
                    <div class="deployment-card">
                        <div class="deployment-card__top">
                            <span class="deployment-pill deployment-pill--emerald" data-spec-key="Sector 04 Pill"><?= e($specs['Sector 04 Pill'] ?? 'SECTOR 04 · HOME & OUTPATIENT') ?></span>
                            <div class="deployment-icon-box deployment-icon-box--emerald" title="Nursing & Home Care">
                                <?= icon('heart-pulse-line') ?>
                            </div>
                        </div>
                        <h4 class="deployment-card__title" data-spec-key="Sector 04 Title"><?= e($specs['Sector 04 Title'] ?? 'Nursing Homes, Day Clinics & Home Healthcare') ?></h4>
                        <p class="deployment-card__desc" data-spec-key="Sector 04 Desc"><?= e($specs['Sector 04 Desc'] ?? 'Dependable continuous flow therapy with low-noise bullnose regulation valves, user-friendly humidifiers, and integrated safety blow-off mechanisms.') ?></p>
                        <div class="deployment-card__footer">
                            <span class="deployment-badge-pill deployment-badge-pill--green" data-spec-key="Sector 04 Badge">
                                <?= icon('shield-check-line') ?> <?= e($specs['Sector 04 Badge'] ?? 'Patient Care Verified') ?>
                            </span>
                            <span class="deployment-meta-stat" data-spec-key="Sector 04 Stat"><?= e($specs['Sector 04 Stat'] ?? 'Controlled Flow Tech') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Safety & Handling -->
            <div class="tab-panel dossier-panel dossier-panel--full" id="panel-safety" role="tabpanel" aria-labelledby="tab-safety" x-show="tab==='safety'" x-cloak>
                <div class="dossier-panel__header dossier-panel__header--flex">
                    <div>
                        <span class="dossier-eyebrow" data-spec-key="Safety Eyebrow"><?= icon('shield-cross-line') ?> <?= e($specs['Safety Eyebrow'] ?? 'Mandatory Compliance') ?></span>
                        <h3 data-spec-key="Safety Heading"><?= e($specs['Safety Heading'] ?? 'High-Pressure Gas Cylinder Safety & Handling Protocol') ?></h3>
                        <p data-spec-key="Safety Subtitle"><?= e($specs['Safety Subtitle'] ?? 'Essential safety rules compliant with the Indian Gas Cylinder Rules 2016 and PESO standards.') ?></p>
                    </div>
                    <div class="dossier-header-seal dossier-header-seal--warning">
                        <?= icon('alert-line') ?>
                        <span data-spec-key="Safety Seal"><?= e($specs['Safety Seal'] ?? 'Statutory Safety Directive') ?></span>
                    </div>
                </div>
                <div class="safety-rules-grid">
                    <div class="safety-rule-card">
                        <div class="safety-rule-card__top">
                            <span class="safety-rule-pill safety-rule-pill--amber" data-spec-key="Rule 01 Pill"><?= e($specs['Rule 01 Pill'] ?? 'RULE 01 · ANCHORAGE') ?></span>
                            <span class="safety-rule-card__icon safety-rule-card__icon--amber"><?= icon('alert-line') ?></span>
                        </div>
                        <h4 class="safety-rule-card__title" data-spec-key="Rule 01 Title"><?= e($specs['Rule 01 Title'] ?? 'Upright Storage & Safety Valve Protection') ?></h4>
                        <p class="safety-rule-card__desc" data-spec-key="Rule 01 Desc"><?= e($specs['Rule 01 Desc'] ?? 'Always maintain cylinders in a strictly upright position secured via wall brackets or chained transport trolleys. Ensure threaded brass valve protection caps remain hand-tightened whenever not in active operation.') ?></p>
                        <div class="safety-rule-card__footer">
                            <?= icon('shield-check-line') ?>
                            <span data-spec-key="Rule 01 Clause"><?= e($specs['Rule 01 Clause'] ?? 'Gas Cylinder Rules 2016 · Statutory Clause 21') ?></span>
                        </div>
                    </div>
                    <div class="safety-rule-card">
                        <div class="safety-rule-card__top">
                            <span class="safety-rule-pill safety-rule-pill--red" data-spec-key="Rule 02 Pill"><?= e($specs['Rule 02 Pill'] ?? 'RULE 02 · THERMAL') ?></span>
                            <span class="safety-rule-card__icon safety-rule-card__icon--red"><?= icon('temp-hot-line') ?></span>
                        </div>
                        <h4 class="safety-rule-card__title" data-spec-key="Rule 02 Title"><?= e($specs['Rule 02 Title'] ?? 'Thermal Isolation & Cross-Ventilation') ?></h4>
                        <p class="safety-rule-card__desc" data-spec-key="Rule 02 Desc"><?= e($specs['Rule 02 Desc'] ?? 'Store in dry, well-ventilated bays isolated from boiler rooms, electrical switchboards, or open heating elements. Cylinder skin temperatures must never exceed 54°C (130°F).') ?></p>
                        <div class="safety-rule-card__footer">
                            <?= icon('shield-check-line') ?>
                            <span data-spec-key="Rule 02 Clause"><?= e($specs['Rule 02 Clause'] ?? 'PESO Safety Directive · Storage & Handling') ?></span>
                        </div>
                    </div>
                    <div class="safety-rule-card">
                        <div class="safety-rule-card__top">
                            <span class="safety-rule-pill safety-rule-pill--cyan" data-spec-key="Rule 03 Pill"><?= e($specs['Rule 03 Pill'] ?? 'RULE 03 · ZERO GREASE') ?></span>
                            <span class="safety-rule-card__icon safety-rule-card__icon--cyan"><?= icon('drop-line') ?></span>
                        </div>
                        <h4 class="safety-rule-card__title" data-spec-key="Rule 03 Title"><?= e($specs['Rule 03 Title'] ?? 'Strict Zero Hydrocarbon / Oil Contamination') ?></h4>
                        <p class="safety-rule-card__desc" data-spec-key="Rule 03 Desc"><?= e($specs['Rule 03 Desc'] ?? 'Never permit lubricants, greases, oils, or organic contaminants to contact oxygen cylinder valves, bullnose connectors, or pipeline regulators due to spontaneous violent oxidation hazards.') ?></p>
                        <div class="safety-rule-card__footer">
                            <?= icon('shield-check-line') ?>
                            <span data-spec-key="Rule 03 Clause"><?= e($specs['Rule 03 Clause'] ?? 'IS:7285 & PESO Technical Guidance') ?></span>
                        </div>
                    </div>
                    <div class="safety-rule-card">
                        <div class="safety-rule-card__top">
                            <span class="safety-rule-pill safety-rule-pill--emerald" data-spec-key="Rule 04 Pill"><?= e($specs['Rule 04 Pill'] ?? 'RULE 04 · RE-TESTING') ?></span>
                            <span class="safety-rule-card__icon safety-rule-card__icon--emerald"><?= icon('check-double-line') ?></span>
                        </div>
                        <h4 class="safety-rule-card__title" data-spec-key="Rule 04 Title"><?= e($specs['Rule 04 Title'] ?? 'Periodic Hydrostatic Re-testing Verification') ?></h4>
                        <p class="safety-rule-card__desc" data-spec-key="Rule 04 Desc"><?= e($specs['Rule 04 Desc'] ?? 'High-pressure medical cylinders must undergo water jacket hydrostatic stretch testing every 5 years at an authorized testing station. Verify collar test stamp validity before charging or clinical deployment.') ?></p>
                        <div class="safety-rule-card__footer">
                            <?= icon('shield-check-line') ?>
                            <span data-spec-key="Rule 04 Clause"><?= e($specs['Rule 04 Clause'] ?? 'Statutory 5-Year Re-test Certification') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Related Products Slider with Custom Navigation -->
    <?php if($related): ?>
    <section class="section product-related-section">
        <div class="container">
            <div class="section-heading section-heading--with-controls">
                <div>
                    <span class="eyebrow" data-spec-key="Related Eyebrow"><?= e($specs['Related Eyebrow'] ?? 'COMPATIBLE & ASSOCIATED EQUIPMENT') ?></span>
                    <h2 data-spec-key="Related Heading"><?= e($specs['Related Heading'] ?? ('Related Products from '.($category['name'] ?? 'our range'))) ?></h2>
                    <p data-spec-key="Related Subtitle"><?= e($specs['Related Subtitle'] ?? 'Complementary medical gas cylinders, regulators, manifolds, and clinical essentials.') ?></p>
                </div>
                <div class="related-slider-controls">
                    <button type="button" class="swiper-nav-btn swiper-button-prev-custom" aria-label="Previous product">
                        <?= icon('arrow-left-s-line') ?>
                    </button>
                    <button type="button" class="swiper-nav-btn swiper-button-next-custom" aria-label="Next product">
                        <?= icon('arrow-right-s-line') ?>
                    </button>
                    <a class="btn btn-outline" href="<?= url('categories/'.($category['slug']??'')) ?>">
                        View all <?= e($category['name'] ?? 'Equipment') ?> <?= icon('arrow-right-up-line') ?>
                    </a>
                </div>
            </div>

            <div class="swiper related-product-swiper">
                <div class="swiper-wrapper">
                    <?php foreach($related as $relProd): ?>
                    <div class="swiper-slide">
                        <?php productCard($relProd); ?>
                    </div>
                    <?php endforeach ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </section>
    <?php endif ?>

    <!-- Product Specific Technical FAQ -->
    <section class="section section-tint product-faq-section">
        <div class="container faq-layout">
            <div class="faq-intro">
                <span class="eyebrow" data-spec-key="FAQ Eyebrow"><?= e($specs['FAQ Eyebrow'] ?? 'TECHNICAL PROCUREMENT FAQ') ?></span>
                <h2 data-spec-key="FAQ Heading"><?= e($specs['FAQ Heading'] ?? ('Frequently Asked Questions About '.$product['name'])) ?></h2>
                <p data-spec-key="FAQ Subtitle"><?= e($specs['FAQ Subtitle'] ?? 'Answers to common questions from hospital biomedical engineers, procurement heads, and clinic administrators.') ?></p>
                
                <div class="faq-consultation-strip">
                    <div class="faq-consultation-strip__lead">
                        <span class="faq-consultation-strip__badge"><?= icon('customer-service-2-line') ?> DIRECT ENGINEERING CONSULTATION</span>
                        <strong>Need Manifold Sizing or Custom Pipeline Engineering?</strong>
                        <p>Our senior gas pipeline engineer can visit your Agra facility or calculate flow requirements directly.</p>
                    </div>
                    <div class="faq-consultation-strip__actions">
                        <a class="btn btn-primary" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                            <?= icon('phone-line') ?> Call <?= e(setting('phone')) ?>
                        </a>
                        <a class="btn btn-outline" target="_blank" rel="noopener" href="https://wa.me/<?= e(preg_replace('/\D/','',setting('whatsapp'))) ?>">
                            <?= icon('whatsapp-line') ?> WhatsApp Desk
                        </a>
                    </div>
                </div>
            </div>

            <div class="faq-list" data-faq-list>
                <?php 
                $defaultFaqs = [
                    [
                        'q' => 'What cylinder capacities and pressure ratings are available for this item?',
                        'a' => 'We supply a full spectrum of sizes from B-Type (10L water capacity) for mobile emergency care up to large D-Type (46.7L water capacity) and bulk manifold banks. All cylinders operate at a working pressure of 150 bar (tested up to 250 bar) strictly conforming to IS:7285.'
                    ],
                    [
                        'q' => 'How does Shree Ji Gas verify purity and hydrostatic compliance?',
                        'a' => 'Every medical oxygen batch undergoes gas chromatography purity testing ensuring ≥99.5% medical purity (IP/BP standard). Cylinders are PESO certified with stamped hydrostatic test dates, tare weight, and valve serial numbers.'
                    ],
                    [
                        'q' => 'Can you support turnkey installation, manifold connection, and pressure regulators?',
                        'a' => 'Yes. Beyond supplying cylinders, our technical team provides compatible dual-gauge regulators, flowmeters, copper manifold headers, automatic changeover panels, and on-site leak testing for healthcare institutions across Agra.'
                    ],
                    [
                        'q' => 'What is the delivery timeline for emergency hospital orders in Agra?',
                        'a' => 'For active hospitals, nursing homes, and critical care units in Agra city, our emergency dispatch vehicle guarantees delivery within 2 hours. Routine bulk replenishment is scheduled daily or on contract cycles.'
                    ],
                    [
                        'q' => 'How can we obtain an official quotation with GST invoicing?',
                        'a' => 'Simply click "Request Quotation" or connect with our procurement desk via WhatsApp. Provide your facility name, required quantity, and delivery address. You will receive a formalized commercial quotation with HSN codes and GST breakdown within 2 hours.'
                    ]
                ];
                $activeFaqs = $defaultFaqs;
                if (!empty($product['faqs'])) {
                    $decoded = json_decode($product['faqs'], true);
                    if (is_array($decoded) && !empty($decoded)) {
                        $activeFaqs = $decoded;
                    }
                }
                foreach($activeFaqs as $idx => $faq):
                ?>
                <details <?= $idx === 0 ? 'open' : '' ?> class="product-faq-item" data-faq-idx="<?= $idx ?>">
                    <summary>
                        <span class="faq-number"><?= str_pad((string)($idx+1), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="faq-question" data-faq-field="q"><?= e($faq['q'] ?? '') ?></span>
                        <span class="faq-toggle"><?= icon('arrow-down-s-line') ?></span>
                    </summary>
                    <div class="faq-answer-body">
                        <p data-faq-field="a"><?= e($faq['a'] ?? '') ?></p>
                    </div>
                </details>
                <?php endforeach ?>
            </div>
        </div>
    </section>

    <!-- Dedicated Product Search & Clinical Indexing Keywords Section (Structured Taxonomic Strip) -->
    <?php 
    $keywordsList = [];
    if (!empty($product['keywords'])) {
        $keywordsList = array_filter(array_map('trim', explode(',', $product['keywords'])));
    }
    if (!empty($keywordsList)): 
    ?>
    <section class="product-taxonomy-section" aria-label="Clinical Indexing and Equipment Taxonomy">
        <div class="container">
            <div class="product-taxonomy-strip">
                <div class="product-taxonomy-strip__meta">
                    <span class="taxonomy-kicker"><?= icon('price-tag-3-line') ?> CATALOGUE TAXONOMY &amp; PROCUREMENT INDEX</span>
                    <h3>Associated Clinical &amp; Industrial Keywords</h3>
                    <p>Standard search queries, biomedical indexing tags, and procurement terms for <?= e($product['name']) ?>.</p>
                </div>
                <div class="product-taxonomy-strip__tags" aria-label="Product search terms">
                    <?php foreach($keywordsList as $kw): ?>
                    <span class="taxonomy-tag">
                        <span class="taxonomy-tag__hash">#</span><?= e($kw) ?>
                    </span>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif ?>

    <!-- Master-Crafted Bottom Facility Supply CTA Banner (No cheesy circles!) -->
    <section class="product-cta-section">
        <div class="container">
            <div class="product-facility-banner">
                <div class="product-facility-banner__grid-bg" aria-hidden="true"></div>
                <div class="product-facility-banner__content">
                    <div class="facility-status-pill">
                        <span class="facility-status-dot"></span>
                        <span>TURNKEY MEDICAL &amp; INDUSTRIAL GAS SUPPLY</span>
                    </div>
                    <h2>Equipping a New Facility or Upgrading Your Cylinder Infrastructure?</h2>
                    <p>Talk directly to our gas engineers for manifold sizing, customized flow regulation, and guaranteed daily or weekly refill schedules.</p>
                </div>
                <div class="product-facility-banner__actions">
                    <button type="button" class="btn btn-cta-primary" data-bs-toggle="modal" data-bs-target="#enquiryModal" data-product="<?= e($product['name']) ?>">
                        <span>Request Custom Quotation</span>
                        <?= icon('arrow-right-up-line') ?>
                    </button>
                    <a class="btn btn-cta-secondary" href="tel:<?= e(preg_replace('/\s/','',setting('phone'))) ?>">
                        <?= icon('phone-line') ?>
                        <span>Speak with Technical Lead</span>
                    </a>
                </div>
            </div>
        </div>
    <!-- Scripts for Interactive Thumbnails and Dossier Tabs -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Gallery thumbnail switcher
        const mainImg = document.getElementById('mainProductImage');
        const mainLink = document.getElementById('mainProductLink');
        const thumbs = document.querySelectorAll('.gallery-thumb');
        thumbs.forEach(thumb => {
            thumb.addEventListener('click', function(e) {
                e.preventDefault();
                thumbs.forEach(t => t.classList.remove('is-active'));
                thumb.classList.add('is-active');
                const targetSrc = thumb.getAttribute('href');
                if (mainImg && targetSrc) {
                    mainImg.style.opacity = '0.35';
                    mainImg.src = targetSrc;
                    if (mainLink) mainLink.href = targetSrc;
                    setTimeout(() => { mainImg.style.opacity = '1'; }, 120);
                }
            });
        });

        // 2. Dossier tabs vanilla fallback & live canvas switcher
        const tabButtons = document.querySelectorAll('.dossier-tabs-nav button');
        const tabPanels = document.querySelectorAll('.dossier-panel');
        tabButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const targetTab = btn.getAttribute('data-tab') || btn.id.replace('tab-', '');
                tabButtons.forEach(b => {
                    const isActive = (b === btn);
                    b.classList.toggle('is-active', isActive);
                    b.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });
                tabPanels.forEach(panel => {
                    const isTarget = (panel.id === 'panel-' + targetTab);
                    if (isTarget) {
                        panel.removeAttribute('hidden');
                        panel.removeAttribute('x-cloak');
                        panel.style.display = 'block';
                        panel.classList.add('is-active');
                    } else {
                        panel.style.display = 'none';
                        panel.classList.remove('is-active');
                    }
                });
                try {
                    const alpineContainer = btn.closest('[x-data]');
                    if (alpineContainer && window.Alpine) {
                        Alpine.$data(alpineContainer).tab = targetTab;
                    }
                } catch(err) {}
                if (typeof window.reportHeight === 'function') window.reportHeight();
                window.dispatchEvent(new Event('resize'));
            });
        });

        // 3. Stop summary toggling details when clicking inside editable FAQ question
        document.querySelectorAll('.product-faq-item summary [data-faq-field="q"], .product-faq-item summary .faq-question').forEach(el => {
            el.addEventListener('click', e => {
                e.stopPropagation();
            });
        });
    });
    </script>
</div>
