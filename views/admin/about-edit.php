<?php
$aboutSections = [
    'hero' => ['title' => 'Overview & Intro', 'icon' => 'layout-top-line', 'target' => '#company-hero'],
    'story' => ['title' => 'Our Story', 'icon' => 'book-open-line', 'target' => '#company-story'],
    'range' => ['title' => 'Equipment Range', 'icon' => 'apps-2-line', 'target' => '#company-range'],
    'settings' => ['title' => 'Care Settings', 'icon' => 'hospital-line', 'target' => '#company-settings'],
    'purpose' => ['title' => 'Our Approach', 'icon' => 'compass-3-line', 'target' => '#company-purpose'],
    'journey' => ['title' => 'Consultation Journey', 'icon' => 'git-merge-line', 'target' => '#company-journey'],
    'local' => ['title' => 'Local Partner', 'icon' => 'hand-heart-line', 'target' => '#company-local'],
    'cta' => ['title' => 'Call to Action', 'icon' => 'megaphone-line', 'target' => '#company-cta'],
];
$isPublished = ($page['status'] ?? 'published') === 'published';
?>

<form class="visual-editor-page" id="sectionForm" method="post" action="<?= url('admin/pages/about') ?>">
    <?= csrfField() ?>
    
    <!-- Top Section Switcher Navigation (Only About Us Sections) -->
    <div class="section-switcher-container">
        <button type="button" class="tab-scroll-arrow tab-scroll-left" id="tabScrollLeft" aria-label="Scroll sections left">
            <?= icon('arrow-left-s-line') ?>
        </button>
        <div class="section-switcher-scroll" id="sectionSwitcherScroll">
            <?php $first = true; foreach($aboutSections as $key => $sec): ?>
                <button type="button" class="section-pill <?= $first ? 'is-active' : '' ?>" data-scroll-to="<?= e($sec['target']) ?>" data-key="<?= e($key) ?>">
                    <?= icon($sec['icon']) ?>
                    <span><?= e($sec['title']) ?></span>
                </button>
            <?php $first = false; endforeach ?>
        </div>
        <button type="button" class="tab-scroll-arrow tab-scroll-right" id="tabScrollRight" aria-label="Scroll sections right">
            <?= icon('arrow-right-s-line') ?>
        </button>
    </div>

    <?php if(!empty($error)): ?>
        <div class="admin-alert admin-alert--error"><?= e($error) ?></div>
    <?php endif ?>

    <!-- Visual Canvas Control Toolbar -->
    <div class="canvas-control-toolbar">
        <div class="canvas-control-toolbar__left">
            <span class="canvas-section-tag"><?= icon('building-4-line') ?> About Us Page</span>
            <label class="switch-label visual-switch-label">
                <input type="checkbox" name="is_active" id="topActiveToggle" value="1" <?= $isPublished ? 'checked' : '' ?>> 
                <span><?= $isPublished ? 'Live on website' : 'Draft / Hidden' ?></span>
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
            <span class="bg-match-badge is-light">
                <?= icon('sparkling-2-line') ?> About Page In-Place Canvas
            </span>
        </div>
    </div>

    <!-- Main Visual Page Editor Canvas (1600px Widescreen / In-Place Live Editing) -->
    <div class="visual-canvas-card">
        <div class="visual-canvas-hint-strip">
            <span><?= icon('edit-2-line') ?> <strong>Direct In-Place Editing:</strong> Click any section tab above to jump to that part of the page. Click and type directly on any headline, intro, or story copy below to edit. Drag and drop photos to replace.</span>
        </div>

        <div class="preview-viewport-outer">
            <div class="preview-viewport" data-viewport="1600">
                <iframe id="sectionPreview" src="<?= url('admin/preview').'?page=about' ?>" title="Visual Page Canvas for About Us" data-preview-url="<?= url('admin/preview').'?page=about' ?>"></iframe>
            </div>
        </div>
    </div>

    <!-- Hidden / Synced Page Fields (Auto-synced with in-place edits and forms) -->
    <input type="hidden" name="status" id="field-status" value="<?= $isPublished ? 'published' : 'draft' ?>">
    <input type="checkbox" id="hiddenIsActive" name="is_active" value="1" <?= $isPublished ? 'checked' : '' ?> style="display:none;">
    <div class="hidden-field-contracts" style="position:absolute;left:-9999px;opacity:0;pointer-events:none;" aria-hidden="true">
        <input type="text" id="field-title" name="title" value="<?= e($page['title']) ?>" data-preview-field="title">
        <textarea id="field-excerpt" name="excerpt" data-preview-field="excerpt"><?= e($page['excerpt']) ?></textarea>
        <textarea id="field-content" name="content" data-preview-field="content"><?= e($page['content']) ?></textarea>
        <input type="text" id="field-thumbnail" name="thumbnail" value="<?= e($page['thumbnail']) ?>" data-preview-field="thumbnail">
        <input type="text" id="field-image_alt" name="image_alt" value="<?= e($page['image_alt']) ?>" data-preview-field="image_alt">
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
            <a href="<?= url('about') ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                View on website <?= icon('arrow-right-up-line') ?>
            </a>
            <button class="btn btn-primary" type="submit" id="visualSaveBtn">
                Save changes <?= icon('check-line') ?>
            </button>
        </div>
    </div>
</form>
