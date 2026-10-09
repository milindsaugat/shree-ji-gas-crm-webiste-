<?php
$isPublished = ($record['status'] ?? 'published') === 'published';
$isFeatured = !empty($record['is_featured']);
$currentId = $id !== 'new' ? (int)($record['id'] ?? 0) : 'new';
$specs = json_decode($record['specs'] ?? '{}', true) ?: [];
$featuresHtml = (string)($record['features'] ?? '');
preg_match_all('~<li>(.*?)</li>~is', $featuresHtml, $mBullets);
$featureBullets = !empty($mBullets[1]) ? array_map('trim', array_map('strip_tags', $mBullets[1])) : [];
if(empty($featureBullets) && !empty($featuresHtml)) {
    $featureBullets = array_filter(array_map('trim', explode("\n", strip_tags($featuresHtml))));
}
if(empty($featureBullets)) {
    $featureBullets = ['Certified clinical grade medical equipment', 'Precision engineered for continuous healthcare reliability', 'Compatible with standard hospital infrastructure'];
}
if(empty($specs)) {
    $specs = ['Standard' => 'IS:7285 / PESO Certified', 'Quality Protocol' => 'QA Batch Inspected', 'Availability' => 'Immediate dispatch in Agra'];
}
$previewId = $currentId !== 'new' && $currentId > 0 ? $currentId : ($allProducts[0]['id'] ?? 1);
?>

<form class="visual-editor-page product-visual-editor" id="productForm" method="post" action="<?= url('admin/content/products/'.$id) ?>">
    <?= csrfField() ?>
    
    <!-- Top Product Switcher Navigation (All Products + Add Product) -->
    <div class="section-switcher-container">
        <button type="button" class="tab-scroll-arrow tab-scroll-left" id="tabScrollLeft" aria-label="Scroll products left">
            <?= icon('arrow-left-s-line') ?>
        </button>
        <div class="section-switcher-scroll" id="sectionSwitcherScroll">
            <a href="<?= url('admin/content/products/catalogue') ?>" class="section-pill section-pill--catalogue">
                <?= icon('store-2-line') ?>
                <span>Catalogue Page (/products)</span>
            </a>
            <?php foreach($allProducts as $pRow): 
                $isCurrent = (int)$pRow['id'] === (int)($record['id'] ?? 0);
                $pIcon = !empty($pRow['is_featured']) ? 'star-fill' : 'archive-line';
                $pStatus = $pRow['status'] ?? 'published';
            ?>
                <a href="<?= url('admin/content/products/'.$pRow['id']) ?>" class="section-pill <?= $isCurrent ? 'is-active' : '' ?>" data-id="<?= (int)$pRow['id'] ?>">
                    <?= icon($pIcon) ?>
                    <span><?= e($pRow['name']) ?></span>
                    <?php if($pStatus !== 'published'): ?>
                        <span class="pill-dot is-hidden" title="Draft product"></span>
                    <?php endif ?>
                </a>
            <?php endforeach ?>
            <a href="<?= url('admin/content/products/new') ?>" class="section-pill section-pill--add <?= $id === 'new' ? 'is-active' : '' ?>">
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
            <span class="canvas-section-tag"><?= icon('archive-line') ?> <?= e($record['name'] ?? 'New Product') ?></span>
            <label class="switch-label visual-switch-label">
                <input type="checkbox" id="topStatusToggle" <?= $isPublished ? 'checked' : '' ?>> 
                <span><?= $isPublished ? 'Live on website' : 'Draft / Hidden' ?></span>
            </label>
            <label class="switch-label visual-switch-label">
                <input type="checkbox" name="is_featured" id="field-is_featured" value="1" <?= $isFeatured ? 'checked' : '' ?>> 
                <span><?= icon('star-line') ?> Featured on homepage</span>
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
            <?php if(!empty($record['slug'])): ?>
                <a href="<?= url('products/'.$record['slug']) ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener">
                    View on website <?= icon('arrow-right-up-line') ?>
                </a>
            <?php endif ?>
        </div>
    </div>

    <!-- Main Visual Product Editor Canvas (In-Place Live Editing) -->
    <div class="visual-canvas-card">
        <div class="visual-canvas-hint-strip">
            <span><?= icon('edit-2-line') ?> <strong>Direct In-Place Canvas:</strong> Click and type directly on the product title, lead text, specifications, or description inside the canvas below. Drag and drop a photo directly onto the product to replace.</span>
        </div>

        <div class="preview-viewport-outer">
            <div class="preview-viewport" data-viewport="1600">
                <iframe id="sectionPreview" src="<?= url('admin/preview').'?product='.$previewId ?>" title="Visual Canvas for <?= e($record['name'] ?? 'Product') ?>" data-preview-url="<?= url('admin/preview').'?product='.$previewId ?>"></iframe>
            </div>
        </div>
    </div>

    <!-- Hidden state contracts for sync -->
    <input type="hidden" name="status" id="field-status" value="<?= $isPublished ? 'published' : 'draft' ?>">
    <input type="hidden" name="specs" id="field-specs" value="<?= e(json_encode($specs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)) ?>">
    <input type="hidden" name="features" id="field-features" value="<?= e($featuresHtml) ?>">
    <input type="hidden" name="description" id="field-description" value="<?= e($record['description'] ?? '') ?>">
    <input type="hidden" name="applications" id="field-applications" value="<?= e($record['applications'] ?? '') ?>">
    <input type="hidden" name="faqs" id="field-faqs" value="<?= e($record['faqs'] ?? '') ?>">

    <!-- Product Attributes & Media Settings (Clean Unified Panel Below Canvas) -->
    <!-- Product Attributes & Media Settings (Clean Unified Panel Below Canvas) -->
    <div class="admin-panel product-structured-panel">
        <div class="product-settings-header">
            <div class="product-settings-header__main">
                <div class="product-settings-header__icon">
                    <?= icon('settings-4-fill') ?>
                </div>
                <div>
                    <div class="product-settings-header__title-row">
                        <h3 class="product-settings-header__title">Product Settings &amp; Media Assets</h3>
                        <span class="product-status-pill <?= $isPublished ? 'is-live' : 'is-draft' ?>">
                            <span class="status-pulse-dot"></span>
                            <?= $isPublished ? 'Live on catalogue' : 'Draft / Unpublished' ?>
                        </span>
                    </div>
                    <p class="product-settings-header__desc">Configure core identity, URL routing, equipment media library, statutory verification dates, and technical specifications.</p>
                </div>
            </div>
            <div class="product-settings-header__meta">
                <span class="badge-tag badge-tag--info" style="background: #e0f2fe; color: #0369a1; padding: 5px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">
                    <?= icon('archive-line') ?> ID #<?= e($record['id'] ?? 'new') ?>
                </span>
                <?php if(!empty($record['slug'])): ?>
                    <a href="<?= url('products/'.$record['slug']) ?>" class="btn btn-outline btn-sm" target="_blank" rel="noopener" style="font-size: 12px; padding: 5px 12px;">
                        <?= icon('arrow-right-up-line') ?> Live Page
                    </a>
                <?php endif ?>
            </div>
        </div>

        <!-- 1. Core Attributes & Categorization Card -->
        <div class="settings-group-card">
            <div class="settings-group-card__header">
                <div class="settings-group-card__title-wrap">
                    <span class="settings-group-card__icon-badge"><?= icon('equalizer-fill') ?></span>
                    <div>
                        <div class="settings-group-card__title">Core Attributes &amp; Categorization</div>
                        <div class="settings-group-card__desc">Identity, URL routing, taxonomy, and catalogue placement</div>
                    </div>
                </div>
                <span class="settings-card-badge">Attributes</span>
            </div>

            <div class="edit-fields">
                <div class="form-row">
                    <div class="form-group-item">
                        <label for="field-name">
                            <span class="field-label-left"><?= icon('archive-line') ?> Product Name <span class="req-star">*</span></span>
                            <span class="field-label-badge">Clinical Title</span>
                        </label>
                        <input type="text" id="field-name" name="name" value="<?= e($record['name'] ?? '') ?>" required data-title placeholder="e.g. Medical Oxygen Cylinder">
                        <small class="admin-help"><?= icon('information-line') ?> Primary equipment title shown in store, quotation requests, and bills.</small>
                    </div>
                    <div class="form-group-item">
                        <label for="field-slug">
                            <span class="field-label-left"><?= icon('links-line') ?> URL Slug <span class="req-star">*</span></span>
                            <span class="field-label-badge">Routing Key</span>
                        </label>
                        <input type="text" id="field-slug" name="slug" value="<?= e($record['slug'] ?? '') ?>" required data-slug placeholder="e.g. medical-oxygen-cylinder">
                        <small class="admin-help slug-url-preview"><?= icon('global-line') ?> /products/<strong id="slugPreviewText"><?= e($record['slug'] ?? 'medical-oxygen-cylinder') ?></strong></small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-item">
                        <label for="field-cat_id">
                            <span class="field-label-left"><?= icon('folder-3-line') ?> Product Category</span>
                            <span class="field-label-badge">Taxonomy</span>
                        </label>
                        <select id="field-cat_id" name="cat_id">
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= (int)$cat['id'] ?>" <?= ((int)($record['cat_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                                    <?= e($cat['name']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                        <small class="admin-help"><?= icon('filter-3-line') ?> Determines department classification on catalogue filter menus.</small>
                    </div>
                    <div class="form-group-item">
                        <label for="field-sort_order">
                            <span class="field-label-left"><?= icon('sort-asc') ?> Display Sort Order</span>
                            <span class="field-label-badge">Catalogue Index</span>
                        </label>
                        <input type="number" id="field-sort_order" name="sort_order" value="<?= (int)($record['sort_order'] ?? 0) ?>" min="0">
                        <small class="admin-help"><?= icon('arrow-down-line') ?> Lower indices (0, 1, 2) appear first in catalogue listings.</small>
                    </div>
                </div>

                <!-- Homepage Featured Spotlight Card -->
                <div class="featured-spotlight-card <?= $isFeatured ? 'is-active' : '' ?>" id="featuredToggleCard">
                    <div class="featured-spotlight-card__info">
                        <span class="featured-spotlight-icon"><?= icon('star-fill') ?></span>
                        <div>
                            <div class="featured-spotlight-title">Featured Product on Homepage Showcase</div>
                            <div class="featured-spotlight-sub">Prioritize this equipment in the homepage highlight grid, quick enquiry drawer, and curated medical solutions banner.</div>
                        </div>
                    </div>
                    <label class="modern-switch" for="field-is_featured" title="Toggle featured state">
                        <input type="checkbox" name="is_featured" id="field-is_featured" value="1" <?= $isFeatured ? 'checked' : '' ?>>
                        <span class="modern-switch__slider"></span>
                    </label>
                </div>

                <div class="form-row">
                    <div class="form-group-item">
                        <label for="field-mfc_date">
                            <span class="field-label-left"><?= icon('calendar-check-line') ?> Manufacturing Date (MFC)</span>
                            <span class="field-label-badge">Statutory Strip</span>
                        </label>
                        <input type="text" id="field-mfc_date" name="mfc_date" value="<?= e($record['mfc_date'] ?? '') ?>" placeholder="e.g. August 2024">
                        <small class="admin-help"><?= icon('shield-check-line') ?> Stamped on technical data sheet and compliance certificate strip.</small>
                    </div>
                    <div class="form-group-item">
                        <label for="field-exp_date">
                            <span class="field-label-left"><?= icon('history-line') ?> Hydro-Test Validity / Expiry Date</span>
                            <span class="field-label-badge">5-Yr PESO Cycle</span>
                        </label>
                        <input type="text" id="field-exp_date" name="exp_date" value="<?= e($record['exp_date'] ?? '') ?>" placeholder="e.g. August 2029 (5-Yr Hydro-Test)">
                        <small class="admin-help"><?= icon('time-line') ?> Certifies statutory 5-year PESO / IS:7285 periodic re-test cycle.</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group-item">
                        <label for="field-badge_label">
                            <span class="field-label-left"><?= icon('price-tag-3-line') ?> Product Highlight Badge</span>
                            <span class="field-label-badge">Card Pill</span>
                        </label>
                        <input type="text" id="field-badge_label" name="badge_label" value="<?= e($record['badge_label'] ?? '') ?>" placeholder="e.g. Popular choice or Verified Supply Assurance">
                        <small class="admin-help"><?= icon('award-line') ?> Floating pill shown on equipment card thumbnail and header badge.</small>
                    </div>
                    <div class="form-group-item">
                        <label for="field-tags">
                            <span class="field-label-left"><?= icon('hashtag') ?> Product Category Tags</span>
                            <span class="field-label-badge">Faceted Search</span>
                        </label>
                        <input type="text" id="field-tags" name="tags" value="<?= e($record['tags'] ?? '') ?>" placeholder="e.g. Medical Oxygen, Hospital Supplies">
                        <small class="admin-help"><?= icon('price-tag-line') ?> Comma-separated tags used for catalogue filter matching.</small>
                    </div>
                </div>

                <div class="form-group-item">
                    <label for="field-keywords">
                        <span class="field-label-left"><?= icon('search-eye-line') ?> Search &amp; Clinical Directory Keywords</span>
                        <span class="field-label-badge">Index Pills</span>
                    </label>
                    <input type="text" id="field-keywords" name="keywords" value="<?= e($record['keywords'] ?? '') ?>" placeholder="e.g. medical oxygen, hospital pipeline, ICU cylinder, 47L gas">
                    <small class="admin-help"><?= icon('lightbulb-line') ?> Keywords displayed as clickable search pills at the bottom of the product page.</small>
                </div>

                <div class="form-group-item">
                    <label for="field-short_desc">
                        <span class="field-label-left"><?= icon('file-text-line') ?> Lead Summary / Short Description <span class="req-star">*</span></span>
                        <span class="field-label-badge">Hero Lead</span>
                    </label>
                    <textarea id="field-short_desc" name="short_desc" rows="3" required placeholder="A clear summary displayed prominently below product title..."><?= e($record['short_desc'] ?? '') ?></textarea>
                    <small class="admin-help"><?= icon('edit-line') ?> A concise medical lead sentence displayed right beneath the product hero heading.</small>
                </div>
            </div>
        </div>

        <!-- 2. Media Assets & Documentation Hub Card -->
        <div class="settings-group-card">
            <div class="settings-group-card__header">
                <div class="settings-group-card__title-wrap">
                    <span class="settings-group-card__icon-badge"><?= icon('image-2-fill') ?></span>
                    <div>
                        <div class="settings-group-card__title">Media Assets &amp; Technical Documentation</div>
                        <div class="settings-group-card__desc">Primary photography, high-resolution aspect ratios, accessibility alt tags, and PDF brochures</div>
                    </div>
                </div>
                <span class="settings-card-badge">Media Deck</span>
            </div>

            <div class="product-media-deck">
                <!-- Main Equipment Thumbnail -->
                <div class="media-asset-card">
                    <label for="field-thumbnail">
                        <span class="field-label-left"><?= icon('image-line') ?> Main Equipment Thumbnail <span class="req-star">*</span></span>
                        <span class="media-badge-tag media-badge-tag--ratio">4:3 Aspect Ratio</span>
                    </label>
                    <div class="media-preview-container" id="thumbPreviewWrap">
                        <?php if(!empty($record['thumbnail'])): ?>
                            <img class="asset-preview" id="mainAssetPreview" src="<?= photo($record['thumbnail']) ?>" alt="Current thumbnail">
                        <?php else: ?>
                            <div class="media-preview-placeholder">
                                <?= icon('image-add-line') ?>
                                <span>No equipment photo selected</span>
                            </div>
                        <?php endif ?>
                    </div>
                    <div class="asset-actions-bar">
                        <input class="asset-path-input" id="field-thumbnail" name="thumbnail" value="<?= e($record['thumbnail'] ?? '') ?>" required placeholder="Choose a file or paste path">
                        <div class="asset-actions-row">
                            <button type="button" class="btn-library-action asset-picker-button" data-target="thumbnail">
                                <?= icon('folder-image-line') ?> Choose from library
                            </button>
                            <label class="custom-file-upload-btn">
                                <?= icon('upload-cloud-2-line') ?> Upload Photo
                                <input type="file" class="asset-upload" accept="image/jpeg,image/png,image/webp" data-crop="4:3" data-target="thumbnail" style="display:none;">
                            </label>
                        </div>
                        <small class="upload-state" aria-live="polite"></small>
                    </div>
                </div>

                <!-- Right: Image Alt & Technical Brochure PDF -->
                <div class="media-asset-card">
                    <div class="form-group-item">
                        <label for="field-image_alt">
                            <span class="field-label-left"><?= icon('eye-line') ?> Image Description / Alt Text <span class="req-star">*</span></span>
                            <span class="media-badge-tag media-badge-tag--seo">A11y &amp; SEO</span>
                        </label>
                        <input type="text" id="field-image_alt" name="image_alt" value="<?= e($record['image_alt'] ?? ($record['name'] ?? '')) ?>" required placeholder="Descriptive text for accessibility and search indexing">
                        <small class="admin-help"><?= icon('information-line') ?> Required for WCAG 2.1 AA screen-reader and search image indexing.</small>
                    </div>

                    <div class="form-group-item" style="margin-top: 14px;">
                        <label for="field-brochure_pdf">
                            <span class="field-label-left"><?= icon('file-pdf-line') ?> Technical Data Sheet / Brochure PDF</span>
                            <span class="media-badge-tag media-badge-tag--pdf">PDF Document</span>
                        </label>
                        <div class="document-preview-box">
                            <div class="document-preview-badge">
                                <?= icon('file-pdf-fill') ?>
                            </div>
                            <div class="document-preview-info">
                                <div class="document-preview-name" id="docPreviewName">
                                    <?= !empty($record['brochure_pdf']) ? basename($record['brochure_pdf']) : 'No brochure PDF attached' ?>
                                </div>
                                <div class="document-preview-status">
                                    <?= !empty($record['brochure_pdf']) ? 'PDF Ready for hospital procurement download' : 'Optional engineering data sheet' ?>
                                </div>
                            </div>
                        </div>
                        <div class="asset-actions-bar" style="margin-top: 10px;">
                            <input class="asset-path-input" id="field-brochure_pdf" name="brochure_pdf" value="<?= e($record['brochure_pdf'] ?? '') ?>" placeholder="Choose a PDF file or paste path">
                            <div class="asset-actions-row">
                                <button type="button" class="btn-library-action asset-picker-button" data-target="brochure_pdf">
                                    <?= icon('file-pdf-line') ?> Choose from library
                                </button>
                                <label class="custom-file-upload-btn">
                                    <?= icon('upload-cloud-2-line') ?> Upload PDF
                                    <input type="file" class="asset-upload" accept="application/pdf" data-target="brochure_pdf" style="display:none;">
                                </label>
                            </div>
                            <small class="upload-state" aria-live="polite"></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width: Gallery Showcase -->
            <div class="gallery-showcase-box">
                <label for="field-gallery" style="display: flex; align-items: center; justify-content: space-between; font-weight: 600; color: #1b3d6f;">
                    <span class="field-label-left"><?= icon('gallery-line') ?> Product Gallery Images</span>
                    <span class="media-badge-tag media-badge-tag--gallery">Multi-Angle Views</span>
                </label>
                <div class="gallery-field">
                    <input type="hidden" id="field-gallery" name="gallery" class="gallery-value" value="<?= e($record['gallery'] ?? '[]') ?>">
                    <div class="gallery-items gallery-items-grid"></div>
                    <div class="asset-actions-row" style="margin-top: 12px;">
                        <button type="button" class="btn-library-action gallery-picker-button">
                            <?= icon('image-add-line') ?> Add from library
                        </button>
                        <label class="custom-file-upload-btn">
                            <?= icon('upload-cloud-2-line') ?> Upload Gallery Images
                            <input type="file" class="gallery-upload" accept="image/jpeg,image/png,image/webp" multiple style="display:none;">
                        </label>
                    </div>
                    <small class="upload-state" aria-live="polite"></small>
                </div>
            </div>
        </div>

        <!-- 3. Technical Specifications Matrix Card -->
        <details class="specs-matrix-card" id="specsMatrixDetails">
            <summary>
                <div class="specs-matrix-summary-left">
                    <span class="specs-summary-icon"><?= icon('table-line') ?></span>
                    <div>
                        <span class="specs-summary-title">Technical Specifications Matrix (Direct &amp; Fallback Sync)</span>
                        <span class="specs-summary-sub">Key-value parameters synchronized with the live canvas specifications tab</span>
                    </div>
                </div>
                <div class="specs-matrix-summary-right">
                    <span class="specs-count-badge" id="specsCountBadge"><?= count($specs) ?> Parameters</span>
                    <span class="summary-toggle-arrow"><?= icon('arrow-down-s-line') ?></span>
                </div>
            </summary>
            <div class="specs-matrix-content">
                <p style="font-size: 13px; color: #64748b; margin-bottom: 14px;">You can edit specifications directly inside the live canvas above, or manage parameter rows below:</p>
                <div id="specsMatrixRows" style="display: flex; flex-direction: column; gap: 8px;"></div>
                <button type="button" class="btn btn-outline btn-sm" id="addSpecRowBtn" style="margin-top: 14px; display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border: 1px dashed #2e8fa8; background: #ffffff; border-radius: 6px; color: #1b3d6f; font-weight: 600;">
                    <?= icon('add-line') ?> Add Parameter Row
                </button>
            </div>
        </details>
    </div>

    <!-- Standalone SEO & Social Sharing Settings (Matches global content editing & smoke tests) -->
    <?php if(!empty($config['route'])): ?>
        <details class="admin-panel seo-details" style="margin-top: 18px;">
            <summary><?= icon('search-eye-line') ?> Search engine &amp; social sharing settings</summary>
            <div class="edit-fields" style="margin-top: 14px;">
                <?php 
                $seoConfig = $resources['seo_meta']['fields'] ?? []; 
                foreach($seoConfig as $key => $rule): 
                    if($key === 'page_key') continue;
                    $name = 'seo['.$key.']';
                    $value = $seo[$key] ?? '';
                    $required = false;
                    $kind = rtrim($rule, '!');
                    require ROOT.'/views/admin/field.php';
                endforeach; 
                ?>
            </div>
        </details>
    <?php endif ?>

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

            <?php if($id !== 'new'): ?>
                <button type="button" class="btn btn-outline btn-sm btn-danger-action" id="deleteProductBtn" title="Delete product">
                    <?= icon('delete-bin-line') ?> Delete
                </button>
            <?php endif ?>

            <button class="btn btn-primary" type="submit" id="saveProductBtn">
                Save product <?= icon('check-line') ?>
            </button>
        </div>
    </div>
</form>

<?php if($id !== 'new'): ?>
<form id="deleteProductForm" method="post" action="<?= url('admin/content/products/'.$id) ?>" style="display:none;">
    <?= csrfField() ?>
    <input type="hidden" name="action" value="delete">
</form>
<?php endif ?>

<!-- Dialog for Media Library Selection -->
<dialog id="mediaPicker" class="media-dialog">
    <div class="media-dialog-header">
        <h2>Choose from Media Library</h2>
        <button type="button" class="btn-close" data-close-dialog aria-label="Close dialog"><?= icon('close-line') ?></button>
    </div>
    <div class="media-dialog-grid">
        <?php foreach((new ContentModel('media'))->all() as $m): ?>
            <button type="button" class="media-choice" data-path="<?= e($m['path']) ?>" data-alt="<?= e($m['image_alt'] ?? '') ?>">
                <img src="<?= photo($m['path']) ?>" alt="<?= e($m['image_alt'] ?? '') ?>" loading="lazy" width="160" height="120">
                <span><?= e(basename($m['path'])) ?></span>
            </button>
        <?php endforeach ?>
    </div>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('productForm');
    const iframe = document.getElementById('sectionPreview');
    const saveIndicator = document.getElementById('saveStateIndicator');

    // 1. Viewport Switcher Controls
    const canvasWrapper = document.getElementById('previewCanvasWrapper');
    document.querySelectorAll('.viewport-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.viewport-btn').forEach(b => b.classList.remove('is-active'));
            btn.classList.add('is-active');
            const vp = btn.dataset.viewport;
            if (vp === 'mobile') {
                if (canvasWrapper) canvasWrapper.style.maxWidth = '390px';
            } else if (vp === 'tablet') {
                if (canvasWrapper) canvasWrapper.style.maxWidth = '768px';
            } else {
                if (canvasWrapper) canvasWrapper.style.maxWidth = '100%';
            }
        });
    });

    // 2. Status Switcher
    const topStatus = document.getElementById('topStatusToggle');
    const hiddenStatus = document.getElementById('field-status');
    topStatus?.addEventListener('change', () => {
        const isPub = topStatus.checked;
        hiddenStatus.value = isPub ? 'published' : 'draft';
        const span = topStatus.parentElement.querySelector('span');
        if (span) span.textContent = isPub ? 'Live on website' : 'Draft / Hidden';
        if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
    });

    // 3. Bidirectional In-Place Live Message Listener (From Preview Canvas)
    window.addEventListener('message', event => {
        if (event.origin !== location.origin) return;
        const data = event.data;
        if (!data || !data.type) return;

        // In-place field text edits
        if (data.type === 'visual-field-edit') {
            const field = data.field;
            const val = data.value;
            const inp = document.getElementById('field-' + field) || form?.querySelector('[name="' + field + '"]');
            if (inp) {
                inp.value = val;
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
            }
            if (field === 'name') {
                const titleTag = document.querySelector('.canvas-section-tag');
                if (titleTag) titleTag.innerHTML = '<i class="ri-archive-line"></i> ' + (val || 'Product');
            }
        }

        // In-place spec row edit from canvas (key-value)
        if (data.type === 'visual-spec-edit') {
            const sKey = (data.key || '').trim();
            const sVal = (data.value || '').trim();
            const specsHidden = document.getElementById('field-specs');
            if (specsHidden && sKey) {
                let currentSpecs = {};
                try {
                    currentSpecs = JSON.parse(specsHidden.value || '{}');
                } catch(e) {}
                currentSpecs[sKey] = sVal;
                specsHidden.value = JSON.stringify(currentSpecs);
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                renderSpecsMatrix();
            }
        }

        // In-place spec row rename from canvas
        if (data.type === 'visual-spec-rename') {
            const oldKey = (data.oldKey || '').trim();
            const newKey = (data.newKey || '').trim();
            const specsHidden = document.getElementById('field-specs');
            if (specsHidden && oldKey && newKey) {
                let currentSpecs = {};
                try { currentSpecs = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
                const val = currentSpecs[oldKey] !== undefined ? currentSpecs[oldKey] : (data.value || '');
                delete currentSpecs[oldKey];
                currentSpecs[newKey] = val;
                if (currentSpecs['Tag_' + oldKey] !== undefined) {
                    currentSpecs['Tag_' + newKey] = currentSpecs['Tag_' + oldKey];
                    delete currentSpecs['Tag_' + oldKey];
                }
                specsHidden.value = JSON.stringify(currentSpecs);
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                renderSpecsMatrix();
            }
        }

        // In-place spec row delete from canvas
        if (data.type === 'visual-spec-delete') {
            const dKey = (data.key || '').trim();
            const specsHidden = document.getElementById('field-specs');
            if (specsHidden && dKey) {
                let currentSpecs = {};
                try { currentSpecs = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
                delete currentSpecs[dKey];
                delete currentSpecs['Tag_' + dKey];
                specsHidden.value = JSON.stringify(currentSpecs);
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                renderSpecsMatrix();
            }
        }

        // In-place spec row add from canvas
        if (data.type === 'visual-spec-add') {
            const aKey = (data.key || '').trim();
            const aVal = (data.value || '').trim();
            const specsHidden = document.getElementById('field-specs');
            if (specsHidden && aKey) {
                let currentSpecs = {};
                try { currentSpecs = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
                currentSpecs[aKey] = aVal;
                specsHidden.value = JSON.stringify(currentSpecs);
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                renderSpecsMatrix();
            }
        }

        // Open media library picker
        if (data.type === 'visual-open-media-picker') {
            const dialog = document.getElementById('mediaPicker');
            if (dialog && typeof dialog.showModal === 'function') dialog.showModal();
        }
    });

    // 4. Specs Matrix UI
    const specsContainer = document.getElementById('specsMatrixRows');
    const specsHidden = document.getElementById('field-specs');
    const addSpecBtn = document.getElementById('addSpecRowBtn');
    const internalSpecPrefixes = ['app ', 'sector ', 'rule ', 'safety ', 'directive ', 'inquiry ', 'related ', 'spec ', 'tag_', 'tag ', 'faq '];

    const renderSpecsMatrix = () => {
        if (!specsContainer || !specsHidden) return;
        let specsObj = {};
        try { specsObj = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
        specsContainer.innerHTML = '';
        let rowCount = 0;
        Object.entries(specsObj).forEach(([k, v]) => {
            const lowerK = k.toLowerCase();
            if (internalSpecPrefixes.some(pfx => lowerK.startsWith(pfx))) return;
            rowCount++;

            const row = document.createElement('div');
            row.className = 'specs-matrix-row-item';
            row.innerHTML = `
                <input type="text" class="spec-key-inp" value="${k.replace(/"/g, '&quot;')}" placeholder="Parameter (e.g. Working Pressure)" style="flex: 1;">
                <input type="text" class="spec-val-inp" value="${String(v).replace(/"/g, '&quot;')}" placeholder="Specification (e.g. 150 bar)" style="flex: 1.2;">
                <button type="button" class="btn-del-spec-row" title="Remove parameter">×</button>
            `;
            const keyInp = row.querySelector('.spec-key-inp');
            const valInp = row.querySelector('.spec-val-inp');
            const delBtn = row.querySelector('.btn-del-spec-row');

            const updateFromInputs = () => {
                let existing = {};
                try { existing = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
                const newObj = {};
                Object.entries(existing).forEach(([ek, ev]) => {
                    const lowerEk = ek.toLowerCase();
                    if (internalSpecPrefixes.some(pfx => lowerEk.startsWith(pfx))) {
                        newObj[ek] = ev;
                    }
                });
                specsContainer.querySelectorAll('.specs-matrix-row-item').forEach(r => {
                    const rK = r.querySelector('.spec-key-inp')?.value.trim();
                    const rV = r.querySelector('.spec-val-inp')?.value.trim();
                    if (rK) newObj[rK] = rV;
                });
                specsHidden.value = JSON.stringify(newObj);
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                triggerIframeSync();
            };

            keyInp.addEventListener('change', updateFromInputs);
            valInp.addEventListener('input', updateFromInputs);
            delBtn.addEventListener('click', () => {
                row.remove();
                updateFromInputs();
                const badge = document.getElementById('specsCountBadge');
                if (badge) {
                    const c = specsContainer.querySelectorAll('.specs-matrix-row-item').length;
                    badge.textContent = `${c} Parameter${c === 1 ? '' : 's'}`;
                }
            });
            specsContainer.appendChild(row);
        });

        const badge = document.getElementById('specsCountBadge');
        if (badge) badge.textContent = `${rowCount} Parameter${rowCount === 1 ? '' : 's'}`;
    };

    // Featured spotlight switch sync
    const featCheckbox = document.getElementById('field-is_featured');
    const featCard = document.getElementById('featuredToggleCard');
    featCheckbox?.addEventListener('change', () => {
        if (featCard) featCard.classList.toggle('is-active', featCheckbox.checked);
        if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
        triggerIframeSync();
    });

    // Slug preview live update
    const slugInp = document.getElementById('field-slug');
    const slugPreviewText = document.getElementById('slugPreviewText');
    slugInp?.addEventListener('input', () => {
        if (slugPreviewText) slugPreviewText.textContent = slugInp.value || 'slug';
    });

    renderSpecsMatrix();

    addSpecBtn?.addEventListener('click', () => {
        let specsObj = {};
        try { specsObj = JSON.parse(specsHidden.value || '{}'); } catch(e) {}
        specsObj['Custom Parameter ' + (Object.keys(specsObj).length + 1)] = 'Specified value';
        specsHidden.value = JSON.stringify(specsObj);
        renderSpecsMatrix();
        triggerIframeSync();
    });

    // 6. Media Library Dialog & File Upload Handlers
    const mediaPicker = document.getElementById('mediaPicker');
    let currentAssetTarget = 'thumbnail';

    document.querySelectorAll('.asset-picker-button').forEach(btn => {
        btn.addEventListener('click', () => {
            currentAssetTarget = btn.dataset.target || 'thumbnail';
            if (mediaPicker && typeof mediaPicker.showModal === 'function') mediaPicker.showModal();
        });
    });

    document.querySelector('.gallery-picker-button')?.addEventListener('click', () => {
        currentAssetTarget = 'gallery';
        if (mediaPicker && typeof mediaPicker.showModal === 'function') mediaPicker.showModal();
    });

    mediaPicker?.querySelectorAll('.media-choice').forEach(btn => {
        btn.addEventListener('click', () => {
            const path = btn.dataset.path;
            const alt = btn.dataset.alt;
            if (currentAssetTarget === 'brochure_pdf') {
                const pdfInp = document.getElementById('field-brochure_pdf');
                if (pdfInp) pdfInp.value = path;
                const docName = document.getElementById('docPreviewName');
                if (docName) docName.textContent = path.split('/').pop();
            } else if (currentAssetTarget === 'gallery') {
                addGalleryImage(path, alt);
            } else {
                const thumbInput = document.getElementById('field-thumbnail');
                if (thumbInput) {
                    thumbInput.value = path;
                    const prevWrap = document.getElementById('thumbPreviewWrap');
                    if (prevWrap) {
                        const fullSrc = path.startsWith('assets/') ? '<?= BASE_PATH ?>/' + path : path;
                        prevWrap.innerHTML = `<img class="asset-preview" id="mainAssetPreview" src="${fullSrc}" alt="Current thumbnail">`;
                    }
                    const altInput = document.getElementById('field-image_alt');
                    if (altInput && !altInput.value) altInput.value = alt || '';
                }
            }
            if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
            triggerIframeSync();
            mediaPicker.close();
        });
    });

    mediaPicker?.querySelectorAll('[data-close-dialog]').forEach(b => {
        b.addEventListener('click', () => mediaPicker.close());
    });

    // Instant AJAX File Uploads
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    document.querySelectorAll('.asset-upload').forEach(fileInput => {
        fileInput.addEventListener('change', async e => {
            const file = e.target.files[0];
            if (!file) return;

            const target = fileInput.dataset.target || 'thumbnail';
            const stateEl = fileInput.closest('.asset-actions-bar')?.querySelector('.upload-state');
            if (stateEl) stateEl.textContent = 'Uploading ' + file.name + '...';

            if (target === 'thumbnail' && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = ev => {
                    const prevWrap = document.getElementById('thumbPreviewWrap');
                    if (prevWrap) prevWrap.innerHTML = `<img class="asset-preview" id="mainAssetPreview" src="${ev.target.result}" alt="Preview">`;
                };
                reader.readAsDataURL(file);
            }

            const formData = new FormData();
            formData.append('file', file);
            formData.append('image_alt', document.getElementById('field-image_alt')?.value || file.name.replace(/\.[^.]+$/, ''));
            formData.append('filename', file.name);

            try {
                const res = await fetch('<?= url("admin/media") ?>', {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if (data && data.ok && data.media) {
                    if (target === 'brochure_pdf') {
                        const pdfInp = document.getElementById('field-brochure_pdf');
                        if (pdfInp) pdfInp.value = data.media.path;
                        const docName = document.getElementById('docPreviewName');
                        if (docName) docName.textContent = file.name;
                    } else {
                        const thumbInp = document.getElementById('field-thumbnail');
                        if (thumbInp) thumbInp.value = data.media.path;
                    }
                    if (stateEl) stateEl.textContent = '✓ Uploaded successfully';
                    if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                    triggerIframeSync();
                } else {
                    if (stateEl) stateEl.textContent = 'Upload error: ' + (data.message || 'Server rejected file');
                }
            } catch(err) {
                if (stateEl) stateEl.textContent = 'Upload failed: ' + err.message;
            }
        });
    });

    // Gallery Showcase Management
    const galleryHidden = document.getElementById('field-gallery');
    const galleryItemsWrap = document.querySelector('.gallery-items');

    const renderGallery = () => {
        if (!galleryHidden || !galleryItemsWrap) return;
        let items = [];
        try { items = JSON.parse(galleryHidden.value || '[]'); } catch(e) {}
        galleryItemsWrap.innerHTML = '';
        if (items.length === 0) {
            galleryItemsWrap.innerHTML = '<span style="font-size: 12.5px; color: #94a3b8; font-style: italic;">No extra gallery photos added. Click buttons below to add multiple views.</span>';
            return;
        }
        items.forEach((item, idx) => {
            const p = typeof item === 'string' ? item : item.path;
            const fullSrc = p.startsWith('assets/') ? '<?= BASE_PATH ?>/' + p : p;
            const thumb = document.createElement('div');
            thumb.className = 'gallery-thumb-item';
            thumb.innerHTML = `
                <img src="${fullSrc}" alt="Gallery item ${idx+1}">
                <button type="button" class="del-gallery-item" data-idx="${idx}" style="position: absolute; top: 2px; right: 2px; background: rgba(220,38,38,0.85); color: #fff; border: none; border-radius: 50%; width: 18px; height: 18px; font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; line-height: 1;" title="Remove">×</button>
            `;
            thumb.querySelector('.del-gallery-item').addEventListener('click', () => {
                items.splice(idx, 1);
                galleryHidden.value = JSON.stringify(items);
                renderGallery();
                if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
                triggerIframeSync();
            });
            galleryItemsWrap.appendChild(thumb);
        });
    };

    const addGalleryImage = (path, alt) => {
        if (!galleryHidden) return;
        let items = [];
        try { items = JSON.parse(galleryHidden.value || '[]'); } catch(e) {}
        items.push({ path: path, alt: alt || '' });
        galleryHidden.value = JSON.stringify(items);
        renderGallery();
    };

    renderGallery();

    document.querySelector('.gallery-upload')?.addEventListener('change', async e => {
        const files = Array.from(e.target.files);
        if (!files.length) return;
        const stateEl = e.target.closest('.gallery-field')?.querySelector('.upload-state');
        if (stateEl) stateEl.textContent = `Uploading ${files.length} images...`;
        for (const file of files) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('image_alt', file.name.replace(/\.[^.]+$/, ''));
            formData.append('filename', file.name);
            try {
                const res = await fetch('<?= url("admin/media") ?>', {
                    method: 'POST',
                    headers: { 'X-CSRF-Token': csrfToken, 'Accept': 'application/json' },
                    body: formData
                });
                const data = await res.json();
                if (data && data.ok && data.media) {
                    addGalleryImage(data.media.path, file.name);
                }
            } catch(err) {}
        }
        if (stateEl) stateEl.textContent = '✓ Gallery images uploaded';
        if (saveIndicator) saveIndicator.textContent = 'Unsaved changes';
        triggerIframeSync();
    });

    // 7. Live Iframe Debounced Sync
    let syncTimer;
    const triggerIframeSync = () => {
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

    // Listen to form input for live iframe update
    form?.querySelectorAll('input:not([type=file]), textarea, select').forEach(inp => {
        inp.addEventListener('input', triggerIframeSync);
        inp.addEventListener('change', triggerIframeSync);
    });

    // 8. Delete confirmation
    document.getElementById('deleteProductBtn')?.addEventListener('click', () => {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            document.getElementById('deleteProductForm')?.submit();
        }
    });

    // 9. Ctrl + S keyboard shortcut
    document.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
            e.preventDefault();
            form?.requestSubmit();
        }
    });
});
</script>
