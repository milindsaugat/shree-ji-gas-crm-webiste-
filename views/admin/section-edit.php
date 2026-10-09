<?php
$sectionIcons = [
    'hero' => 'layout-top-line',
    'stats' => 'bar-chart-box-line',
    'categories' => 'apps-2-line',
    'products' => 'shopping-bag-3-line',
    'why' => 'shield-star-line',
    'process' => 'git-merge-line',
    'guidance' => 'compass-3-line',
    'partnership' => 'hand-heart-line',
    'case_studies' => 'article-line',
    'use_cases' => 'hospital-line',
    'blog' => 'book-read-line',
    'testimonials' => 'chat-quote-line',
    'faq' => 'question-answer-line',
    'logos' => 'gallery-line',
    'contact' => 'map-pin-2-line',
    'cta' => 'megaphone-line',
    'footer' => 'layout-bottom-line',
];
$dynamicInfo = [
    'case_studies' => [
        'title' => 'Case Studies Spotlight',
        'desc' => 'This section dynamically spotlights your published case studies directly from the database.',
        'route' => 'admin/content/case_studies',
        'count' => $counts['case_studies'] ?? 0,
        'label' => 'Manage Case Studies',
        'icon' => 'article-line'
    ],
    'use_cases' => [
        'title' => 'Care Settings & Use Cases',
        'desc' => 'This section dynamically displays your clinical care environments (Hospitals, Clinics, Home Care).',
        'route' => 'admin/content/use_cases',
        'count' => $counts['use_cases'] ?? 0,
        'label' => 'Manage Use Cases',
        'icon' => 'hospital-line'
    ],
    'blog' => [
        'title' => 'Knowledge Centre & Journal Posts',
        'desc' => 'This section showcases the latest articles published in your equipment journal.',
        'route' => 'admin/content/blog_posts',
        'count' => $counts['blog_posts'] ?? 0,
        'label' => 'Manage Blog Posts',
        'icon' => 'book-read-line'
    ],
    'products' => [
        'title' => 'Featured Products Carousel',
        'desc' => 'This section dynamically loads your featured equipment items from the products catalogue.',
        'route' => 'admin/content/products',
        'count' => $counts['products'] ?? 0,
        'label' => 'Manage Products',
        'icon' => 'shopping-bag-3-line'
    ],
    'categories' => [
        'title' => 'Product Categories Grid',
        'desc' => 'This section displays your main product category cards and counts active products in each category.',
        'route' => 'admin/content/categories',
        'count' => $counts['categories'] ?? 0,
        'label' => 'Manage Categories',
        'icon' => 'apps-2-line'
    ],
    'testimonials' => [
        'title' => 'Client Testimonials Swiper',
        'desc' => 'This section renders verified customer reviews and ratings from your testimonials library.',
        'route' => 'admin/content/testimonials',
        'count' => $counts['testimonials'] ?? 0,
        'label' => 'Manage Testimonials',
        'singular' => 'Testimonial',
        'icon' => 'chat-quote-line'
    ],
    'logos' => [
        'title' => 'Client & Partner Logos Marquee',
        'desc' => 'This section scrolls logos of institutions and partners from your client logos library.',
        'route' => 'admin/content/client_logos',
        'count' => $counts['client_logos'] ?? 0,
        'label' => 'Manage Logos',
        'singular' => 'Logo',
        'icon' => 'gallery-line'
    ],
];
$currentBg = $section['fields']['background'] ?? '';
$palette = [
    '' => ['label'=>'Default', 'color'=>'#f7fafb', 'border'=>'#dce7ec'],
    '#FFFFFF' => ['label'=>'White', 'color'=>'#FFFFFF', 'border'=>'#dce7ec'],
    '#EAF7FA' => ['label'=>'Light Teal', 'color'=>'#EAF7FA', 'border'=>'#b6dfe7'],
    '#F5FCFD' => ['label'=>'Page Tint', 'color'=>'#F5FCFD', 'border'=>'#c9e7ef'],
    '#1B3D6F' => ['label'=>'Brand Navy', 'color'=>'#1B3D6F', 'border'=>'#10294c', 'dark'=>true],
    '#2E8FA8' => ['label'=>'Teal Accent', 'color'=>'#2E8FA8', 'border'=>'#1f6f84', 'dark'=>true]
];
?>

<form class="visual-editor-page" id="sectionForm" method="post" action="<?= url('admin/sections/'.$section['section_key']) ?>">
    <?= csrfField() ?>
    
    <!-- Top Section Switcher Navigation with Smooth Scroll Controls -->
    <div class="section-switcher-container">
        <button type="button" class="tab-scroll-arrow tab-scroll-left" id="tabScrollLeft" aria-label="Scroll sections left">
            <?= icon('arrow-left-s-line') ?>
        </button>
        <div class="section-switcher-scroll" id="sectionSwitcherScroll">
            <?php foreach($allSections as $sRow): 
                if ($sRow['section_key'] === 'partnership' || $sRow['section_key'] === 'contact') continue;
                $isCurrent = $sRow['section_key'] === $section['section_key'];
                $sIcon = $sectionIcons[$sRow['section_key']] ?? 'layout-grid-line';
            ?>
                <a href="<?= url('admin/sections/'.$sRow['section_key']) ?>" class="section-pill <?= $isCurrent ? 'is-active' : '' ?>" data-key="<?= e($sRow['section_key']) ?>">
                    <?= icon($sIcon) ?>
                    <span><?= e($sRow['title']) ?></span>
                    <?php if(!$sRow['is_active']): ?><span class="pill-dot is-hidden" title="Hidden section"></span><?php endif ?>
                </a>
            <?php endforeach ?>
        </div>
        <button type="button" class="tab-scroll-arrow tab-scroll-right" id="tabScrollRight" aria-label="Scroll sections right">
            <?= icon('arrow-right-s-line') ?>
        </button>
    </div>

    <?php if($error): ?>
        <div class="admin-alert admin-alert--error"><?= e($error) ?></div>
    <?php endif ?>

    <!-- Dynamic Collection Notice Banner (if applicable) -->
    <?php if(isset($dynamicInfo[$section['section_key']])): $dyn = $dynamicInfo[$section['section_key']]; ?>
        <div class="dynamic-resource-banner">
            <div class="dynamic-resource-banner__icon">
                <?= icon($dyn['icon']) ?>
            </div>
            <div class="dynamic-resource-banner__body">
                <div class="dynamic-badge-strip">
                    <span class="dynamic-badge"><?= icon('sparkling-2-line') ?> Dynamic Collection</span>
                    <span class="dynamic-count"><strong><?= (int)$dyn['count'] ?></strong> records active in database</span>
                </div>
                <h3><?= e($dyn['title']) ?></h3>
                <p><?= e($dyn['desc']) ?> Section copy is directly editable below; individual records are managed in the library.</p>
            </div>
            <div class="dynamic-resource-banner__action">
                <?php if($section['section_key'] === 'testimonials'): ?>
                    <button type="button" class="btn btn-primary btn-sm" id="openQuickTestimonialBtn" onclick="document.getElementById('quickTestimonialModal')?.showModal()">
                        <?= icon('add-line') ?> Quick Add Testimonial
                    </button>
                <?php elseif(!empty($dyn['singular'])): ?>
                    <a href="<?= url($dyn['route'].'/new') ?>" class="btn btn-primary btn-sm" target="_blank">
                        <?= icon('add-line') ?> Add <?= e($dyn['singular']) ?>
                    </a>
                <?php endif ?>
                <a href="<?= url($dyn['route']) ?>" class="btn btn-outline btn-sm" target="_blank">
                    <?= e($dyn['label']) ?> <?= icon('arrow-right-up-line') ?>
                </a>
            </div>
        </div>
    <?php endif ?>

    <!-- Visual Canvas Control Toolbar -->
    <div class="canvas-control-toolbar">
        <div class="canvas-control-toolbar__left">
            <span class="canvas-section-tag"><?= icon($sectionIcons[$section['section_key']] ?? 'layout-grid-line') ?> <?= e($section['title']) ?></span>
            <label class="switch-label visual-switch-label">
                <input type="checkbox" name="is_active" value="1" <?= $section['is_active']?'checked':'' ?>> 
                <span><?= $section['is_active'] ? 'Live on website' : 'Hidden on website' ?></span>
            </label>
        </div>

        <div class="canvas-control-toolbar__center">
            <div class="preview-viewport-controls">
                <button type="button" class="viewport-btn is-active" data-viewport="1600" title="Desktop view (1600px)">
                    <?= icon('computer-line') ?> <span>Desktop (1600px)</span>
                </button>
                <button type="button" class="viewport-btn" data-viewport="768" title="Tablet view (768px)">
                    <?= icon('tablet-line') ?> <span>Tablet</span>
                </button>
                <button type="button" class="viewport-btn" data-viewport="390" title="Mobile view (390px)">
                    <?= icon('smartphone-line') ?> <span>Mobile</span>
                </button>
            </div>
        </div>

        <div class="canvas-control-toolbar__right">
            <span class="bg-picker-label">Theme Background:</span>
            <div class="bg-swatches-inline">
                <?php foreach($palette as $hex=>$meta): ?>
                    <button type="button" class="bg-swatch-circle <?= $currentBg === $hex ? 'is-selected' : '' ?>" data-color="<?= e($hex) ?>" style="background-color: <?= e($meta['color']) ?>; border-color: <?= e($meta['border']) ?>;" title="Background: <?= e($meta['label']) ?>">
                        <?php if($currentBg === $hex): ?><span class="swatch-dot"></span><?php endif ?>
                    </button>
                <?php endforeach ?>
                <label class="bg-custom-picker" title="Pick custom background color">
                    <input type="color" id="bgCustomColor" value="<?= e(preg_match('/^#[0-9a-fA-F]{6}$/',$currentBg)?$currentBg:'#1b3d6f') ?>">
                    <?= icon('palette-line') ?>
                </label>
            </div>
            <span class="bg-match-badge <?= $currentBg !== '' && isDarkColor($currentBg) ? 'is-dark' : 'is-light' ?>" id="bgMatchBadge">
                <?= $currentBg !== '' && isDarkColor($currentBg) ? '🌙 Dark Mode · Auto-matched light text' : '☀️ Light Mode · Auto-matched dark text' ?>
            </span>
            <input type="hidden" id="field-background" name="fields[background]" value="<?= e($currentBg) ?>">
        </div>
    </div>

    <!-- Main Visual Section Editor Canvas (Full-Width / In-Place Editing) -->
    <div class="visual-canvas-card">
        <div class="visual-canvas-hint-strip">
            <span><?= icon('edit-2-line') ?> <strong>Direct In-Place Editing:</strong> Click and type directly on any heading, subtitle, card, or button below to edit. Changes save instantly.</span>
        </div>

        <?php 
        $hasBtnLabel = array_key_exists('button_label', $section['fields']);
        $hasBtnUrl = array_key_exists('button_url', $section['fields']);
        $hasSecLabel = array_key_exists('secondary_label', $section['fields']);
        $hasSecUrl = array_key_exists('secondary_url', $section['fields']);
        if ($hasBtnLabel || $hasBtnUrl || $hasSecLabel || $hasSecUrl):
        ?>
        <div class="canvas-button-toolbar">
            <div class="canvas-button-toolbar__label">
                <?= icon('links-line') ?>
                <span>Section Buttons &amp; Links:</span>
            </div>
            <div class="canvas-button-toolbar__inputs">
                <?php if ($hasBtnLabel || $hasBtnUrl): ?>
                    <div class="btn-toolbar-group">
                        <span class="btn-toolbar-tag">Primary Button</span>
                        <?php if ($hasBtnLabel): ?>
                            <input type="text" class="btn-toolbar-input" id="toolbar-button_label" placeholder="Button Text / Label" value="<?= e($section['fields']['button_label'] ?? '') ?>" data-sync-field="button_label" title="Edit primary button text">
                        <?php endif ?>
                        <?php if ($hasBtnUrl): ?>
                            <input type="text" class="btn-toolbar-input btn-toolbar-input--url" id="toolbar-button_url" placeholder="Link / URL (e.g. products, contact)" value="<?= e($section['fields']['button_url'] ?? '') ?>" data-sync-field="button_url" title="Edit primary button URL">
                        <?php endif ?>
                    </div>
                <?php endif ?>
                <?php if ($hasSecLabel || $hasSecUrl): ?>
                    <div class="btn-toolbar-group">
                        <span class="btn-toolbar-tag">Secondary Button</span>
                        <?php if ($hasSecLabel): ?>
                            <input type="text" class="btn-toolbar-input" id="toolbar-secondary_label" placeholder="Secondary Button Text" value="<?= e($section['fields']['secondary_label'] ?? '') ?>" data-sync-field="secondary_label" title="Edit secondary button text">
                        <?php endif ?>
                        <?php if ($hasSecUrl): ?>
                            <input type="text" class="btn-toolbar-input btn-toolbar-input--url" id="toolbar-secondary_url" placeholder="Secondary URL (e.g. contact, about)" value="<?= e($section['fields']['secondary_url'] ?? '') ?>" data-sync-field="secondary_url" title="Edit secondary button URL">
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>

        <div class="preview-viewport-outer">
            <div class="preview-viewport" data-viewport="1600">
                <iframe id="sectionPreview" src="<?= url('admin/preview').'?section='.e($section['section_key']) ?>" title="Visual Section Canvas for <?= e($section['title']) ?>" data-preview-url="<?= url('admin/preview').'?section='.e($section['section_key']) ?>"></iframe>
            </div>
        </div>
    </div>

    <!-- Hidden / Synced Section Fields (Keeps seamless compatibility with automated tests & form save) -->
    <input type="hidden" name="sort_order" value="<?= (int)$section['sort_order'] ?>">
    <div class="hidden-field-contracts" style="position:absolute;left:-9999px;opacity:0;pointer-events:none;" aria-hidden="true">
        <?php foreach($section['fields'] as $key=>$value): if($key==='background') continue; $fieldName='fields['.$key.']'; ?>
            <?php if(is_array($value)): ?>
                <input type="text" id="field-<?= e($key) ?>" class="repeater-value" name="<?= e($fieldName) ?>" value="<?= e(json_encode($value)) ?>" data-preview-field="<?= e($key) ?>">
            <?php elseif(in_array($key,['body','heading','accent','note','description'])): ?>
                <textarea id="field-<?= e($key) ?>" name="<?= e($fieldName) ?>" data-preview-field="<?= e($key) ?>"><?= e($value) ?></textarea>
            <?php else: ?>
                <input type="text" id="field-<?= e($key) ?>" name="<?= e($fieldName) ?>" value="<?= e($value) ?>" data-preview-field="<?= e($key) ?>">
            <?php endif ?>
        <?php endforeach ?>
    </div>

    <!-- Sticky Bottom Action & Save Bar -->
    <div class="visual-sticky-action-bar">
        <div class="visual-sticky-left">
            <div class="save-status-indicator">
                <span class="save-state" id="saveStateIndicator" aria-live="polite">All changes saved</span>
                <span class="shortcut-tip"><kbd>Ctrl</kbd> + <kbd>S</kbd> to save</span>
            </div>
        </div>
        <div class="visual-sticky-right">
            <a href="<?= url() ?>#<?= e($section['section_key']) ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                View on website <?= icon('arrow-right-up-line') ?>
            </a>
            <button class="btn btn-primary" type="submit">
                Save changes <?= icon('check-line') ?>
            </button>
        </div>
    </div>
</form>

<?php if($section['section_key'] === 'testimonials'): ?>
    <dialog class="admin-modal" id="quickTestimonialModal">
        <form method="post" action="<?= url('admin/content/testimonials/new') ?>" class="admin-modal__card" id="quickTestimonialForm">
            <?= csrfField() ?>
            <div class="admin-modal__header">
                <h3><?= icon('chat-quote-line') ?> Add Client Testimonial</h3>
                <button type="button" class="admin-modal__close" id="closeTestimonialModalBtn" aria-label="Close dialog">&times;</button>
            </div>
            <div class="admin-modal__body">
                <div class="form-row">
                    <label>Client / Doctor Name <span>*</span>
                        <input type="text" name="client_name" required placeholder="e.g. Dr. Rajesh Sharma">
                    </label>
                    <label>Hospital / Clinic Organization
                        <input type="text" name="organization" placeholder="e.g. Apex Hospital, Agra">
                    </label>
                </div>
                <div class="form-row">
                    <label>Avatar Photo (Circle Image)
                        <div class="asset-field">
                            <div class="asset-field-controls">
                                <input type="text" name="avatar" id="modal_avatar" placeholder="assets/images/... or choose from library">
                                <button type="button" class="btn-admin-action asset-picker-button"><?= icon('folder-image-line') ?> <span>Choose</span></button>
                                <label class="btn-admin-action btn-admin-upload"><?= icon('upload-cloud-2-line') ?> <span>Upload</span><input type="file" class="asset-upload" accept="image/jpeg,image/png,image/webp"></label>
                            </div>
                        </div>
                    </label>
                    <label>Star Rating (1 - 5)
                        <select name="rating">
                            <option value="5" selected>★★★★★ 5 Stars</option>
                            <option value="4">★★★★☆ 4 Stars</option>
                            <option value="3">★★★☆☆ 3 Stars</option>
                        </select>
                    </label>
                </div>
                <div class="form-row">
                    <label class="checkbox-label" style="margin-top:6px; margin-bottom:12px;">
                        <input type="checkbox" name="is_active" value="1" checked>
                        <span>Active and visible in live slider</span>
                    </label>
                </div>
                <label>Testimonial Quote / Verified Experience <span>*</span>
                    <textarea name="quote" rows="4" required placeholder="Share the verified client experience with Shree Ji Gas equipment…"></textarea>
                </label>
            </div>
            <div class="admin-modal__footer">
                <button type="button" class="btn btn-outline btn-sm" id="cancelTestimonialModalBtn">Cancel</button>
                <button type="submit" class="btn btn-primary btn-sm" id="submitQuickTestimonialBtn">
                    <?= icon('check-line') ?> Save Testimonial
                </button>
            </div>
        </form>
    </dialog>
<?php endif ?>
