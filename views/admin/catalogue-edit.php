<?php
$catTitle = setting('catalogue_title') ?: 'Medical & Gas Equipment';
$catEyebrow = setting('catalogue_eyebrow') ?: 'EQUIPMENT FOR BETTER CARE';
$catDesc = setting('catalogue_desc') ?: 'Explore the essentials. Find your fit. Let’s discuss the details.';
$catGuideTitle = setting('catalogue_guidance_title') ?: 'A little guidance?';
$catGuideDesc = setting('catalogue_guidance_desc') ?: 'Our team can help you explore the right options.';
$catGuideBtn = setting('catalogue_guidance_btn') ?: 'Talk to us';
?>

<form class="visual-editor-page product-visual-editor" id="catalogueForm" method="post" action="<?= url('admin/content/products/catalogue') ?>">
    <?= csrfField() ?>

    <!-- Top Navigation Switcher (Catalogue Page + All Products + Add Product) -->
    <div class="section-switcher-container">
        <button type="button" class="tab-scroll-arrow tab-scroll-left" id="tabScrollLeft" aria-label="Scroll products left">
            <?= icon('arrow-left-s-line') ?>
        </button>
        <div class="section-switcher-scroll" id="sectionSwitcherScroll">
            <a href="<?= url('admin/content/products/catalogue') ?>" class="section-pill is-active">
                <?= icon('store-2-line') ?>
                <span>Catalogue Page (/products)</span>
            </a>
            <?php foreach($allProducts as $pRow): 
                $pIcon = !empty($pRow['is_featured']) ? 'star-fill' : 'archive-line';
            ?>
                <a href="<?= url('admin/content/products/'.$pRow['id']) ?>" class="section-pill" data-id="<?= (int)$pRow['id'] ?>">
                    <?= icon($pIcon) ?>
                    <span><?= e($pRow['name']) ?></span>
                </a>
            <?php endforeach ?>
            <a href="<?= url('admin/content/products/new') ?>" class="section-pill section-pill--add">
                <?= icon('add-line') ?>
                <span>+ Add Product</span>
            </a>
            <a href="<?= url('admin/content/products?view=list') ?>" class="section-pill section-pill--table" title="View raw table records">
                <?= icon('table-line') ?>
                <span>Table View</span>
            </a>
        </div>
        <button type="button" class="tab-scroll-arrow tab-scroll-right" id="tabScrollRight" aria-label="Scroll products right">
            <?= icon('arrow-right-s-line') ?>
        </button>
    </div>

    <?php if(!empty($error)): ?>
        <div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div>
    <?php endif ?>

    <!-- Visual Canvas Control Toolbar -->
    <div class="canvas-control-toolbar">
        <div class="canvas-control-toolbar__left">
            <span class="canvas-section-tag"><?= icon('store-2-line') ?> Product Catalogue Page (/products)</span>
            <span class="pill-dot is-active" style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#28a745; margin-left:6px;" title="Live on website"></span>
            <span style="font-size:12px; font-weight:600; color:#1b3d6f;">Live Catalogue Page</span>
        </div>

        <div class="canvas-control-toolbar__center">
            <div class="viewport-switcher" role="group" aria-label="Viewport size">
                <button type="button" class="viewport-btn is-active" data-viewport="desktop" title="Desktop view (1600px)">
                    <?= icon('computer-line') ?>
                </button>
                <button type="button" class="viewport-btn" data-viewport="tablet" title="Tablet view (768px)">
                    <?= icon('tablet-line') ?>
                </button>
                <button type="button" class="viewport-btn" data-viewport="mobile" title="Mobile view (390px)">
                    <?= icon('smartphone-line') ?>
                </button>
            </div>
        </div>

        <div class="canvas-control-toolbar__right">
            <a href="<?= url('products') ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">
                View on website <?= icon('arrow-right-up-line') ?>
            </a>
        </div>
    </div>

    <!-- Live Interactive Preview Canvas Frame -->
    <div class="preview-canvas-container" id="previewCanvasContainer">
        <div class="preview-canvas-wrapper" id="previewCanvasWrapper" style="width: 100%; transition: width 0.3s ease; margin: 0 auto;">
            <iframe 
                id="cataloguePreview" 
                class="visual-preview-iframe"
                src="<?= url('admin/preview?catalogue=1') ?>" 
                data-preview-url="<?= url('admin/preview?catalogue=1') ?>"
                title="Live Catalogue Page Preview"
                style="width: 100%; height: 750px; border: 1px solid #d0d7de; border-radius: 8px; background: #ffffff; display: block;"
            ></iframe>
        </div>
    </div>

    <!-- Structured Content Controls Below Canvas -->
    <div class="product-structured-panel admin-panel" style="margin-top: 24px;">
        <div class="panel-header" style="padding: 16px 20px; border-bottom: 1px solid #e1e4e8;">
            <h2 style="font-size: 16px; margin: 0; color: #1b3d6f; display: flex; align-items: center; gap: 8px;">
                <?= icon('edit-box-line') ?> Catalogue Page Texts &amp; Guidance Settings
            </h2>
            <p style="font-size: 13px; color: #586069; margin: 4px 0 0;">
                Click directly on any text inside the live preview above to edit in-place, or update the structured values below.
            </p>
        </div>

        <div class="edit-fields" style="padding: 20px;">
            <div class="form-row">
                <div>
                    <label for="field-catalogue_eyebrow">Catalogue Top Eyebrow</label>
                    <input type="text" id="field-catalogue_eyebrow" name="catalogue_eyebrow" value="<?= e($catEyebrow) ?>" placeholder="e.g. EQUIPMENT FOR BETTER CARE">
                </div>
                <div>
                    <label for="field-catalogue_title">Catalogue Main Heading (Title)</label>
                    <input type="text" id="field-catalogue_title" name="catalogue_title" value="<?= e($catTitle) ?>" placeholder="e.g. Medical & Gas Equipment">
                </div>
            </div>

            <div>
                <label for="field-catalogue_desc">Catalogue Subtitle / Introductory Description</label>
                <textarea id="field-catalogue_desc" name="catalogue_desc" rows="3" placeholder="Explore certified clinical equipment, gas manifolds, and hospital care solutions..."><?= e($catDesc) ?></textarea>
            </div>

            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px dashed #d0d7de;">
                <h3 style="font-size: 14px; color: #1b3d6f; margin: 0 0 12px;"><?= icon('customer-service-2-line') ?> Sidebar Guidance Callout Box</h3>
                <div class="form-row">
                    <div>
                        <label for="field-catalogue_guidance_title">Guidance Title</label>
                        <input type="text" id="field-catalogue_guidance_title" name="catalogue_guidance_title" value="<?= e($catGuideTitle) ?>" placeholder="e.g. A little guidance?">
                    </div>
                    <div>
                        <label for="field-catalogue_guidance_btn">Guidance Action Button Text</label>
                        <input type="text" id="field-catalogue_guidance_btn" name="catalogue_guidance_btn" value="<?= e($catGuideBtn) ?>" placeholder="e.g. Talk to us">
                    </div>
                </div>
                <div>
                    <label for="field-catalogue_guidance_desc">Guidance Message</label>
                    <input type="text" id="field-catalogue_guidance_desc" name="catalogue_guidance_desc" value="<?= e($catGuideDesc) ?>" placeholder="e.g. Our team can help you explore the right options.">
                </div>
            </div>
        </div>
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
            <a class="text-link" href="<?= url('admin/content/products') ?>">
                <?= icon('arrow-left-line') ?> Back to products
            </a>

            <button class="btn btn-primary" type="submit" id="saveCatalogueBtn">
                Save catalogue page <?= icon('check-line') ?>
            </button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('catalogueForm');
    const iframe = document.getElementById('cataloguePreview');
    const saveIndicator = document.getElementById('saveStateIndicator');
    const canvasWrapper = document.getElementById('previewCanvasWrapper');

    // 1. Viewport Switcher
    document.querySelectorAll('.viewport-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.viewport-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const vp = btn.dataset.viewport;
            if (vp === 'mobile') {
                canvasWrapper.style.maxWidth = '390px';
            } else if (vp === 'tablet') {
                canvasWrapper.style.maxWidth = '768px';
            } else {
                canvasWrapper.style.maxWidth = '100%';
            }
        });
    });

    // 2. Listen to in-place edits and navigation from the preview iframe
    window.addEventListener('message', event => {
        if (event.origin !== location.origin) return;
        const data = event.data;
        if (!data || !data.type) return;

        if (data.type === 'visual-navigate-product' && data.id) {
            window.location.href = '<?= BASE_PATH ?>/admin/content/products/' + data.id;
            return;
        }

        if (data.type === 'preview-height' && data.height) {
            iframe.style.height = Math.max(data.height + 40, 800) + 'px';
            return;
        }

        if (data.type === 'visual-field-edit') {
            const fieldMap = {
                'catalogue_eyebrow': 'field-catalogue_eyebrow',
                'catalogue_title': 'field-catalogue_title',
                'catalogue_desc': 'field-catalogue_desc',
                'catalogue_guidance_title': 'field-catalogue_guidance_title',
                'catalogue_guidance_desc': 'field-catalogue_guidance_desc',
                'catalogue_guidance_btn': 'field-catalogue_guidance_btn'
            };
            const targetId = fieldMap[data.field];
            if (targetId) {
                const el = document.getElementById(targetId);
                if (el) {
                    el.value = data.value;
                    if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                }
            }
        }
    });

    // 3. Debounced live iframe sync
    let syncTimer;
    const triggerSync = () => {
        if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
        clearTimeout(syncTimer);
        syncTimer = setTimeout(async () => {
            if (!iframe || !iframe.dataset.previewUrl) return;
            try {
                const res = await fetch(iframe.dataset.previewUrl, {
                    method: 'POST',
                    body: new FormData(form)
                });
                if (res.ok) {
                    iframe.srcdoc = await res.text();
                }
            } catch (err) {}
        }, 150);
    };

    form?.querySelectorAll('input, textarea').forEach(inp => {
        inp.addEventListener('input', triggerSync);
        inp.addEventListener('change', triggerSync);
    });

    // 4. Ctrl + S shortcut
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
            e.preventDefault();
            form?.requestSubmit();
        }
    });
});
</script>
