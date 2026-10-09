<?php
/**
 * Master Visual Editorial Studio Editor (Blog Posts, Case Studies, Use Cases)
 * Beautiful, split-column visual layout matching the live page anatomy.
 */
$isPublished = ($record['status'] ?? 'published') === 'published';
$isFeatured = !empty($record['is_featured']);
$currentId = $id !== 'new' ? (int)($record['id'] ?? 0) : 'new';
$recordSlug = $record['slug'] ?? '';
$routePrefix = $config['route'] ?: $type;
$publicUrl = $recordSlug ? url($routePrefix . '/' . $recordSlug) : '';
?>

<div class="admin-page-heading">
  <div>
    <a class="back-link" href="<?= url('admin/content/' . $type) ?>">
      <?= icon('arrow-left-line') ?> <?= e($config['label']) ?>
    </a>
    <span class="eyebrow">EDITORIAL STUDIO · <?= strtoupper($config['label']) ?></span>
    <h1><?= e($title) ?></h1>
    <p>Compose and format articles with live WYSIWYG rich text, cover photography, and search engine previews.</p>
  </div>
  <?php if(!empty($publicUrl)): ?>
    <div style="display:flex; gap:10px; align-items:center;">
      <a class="btn btn-outline" href="<?= e($publicUrl) ?>" target="_blank" rel="noopener">
        View on website <?= icon('arrow-right-up-line') ?>
      </a>
    </div>
  <?php endif ?>
</div>

<?php if(!empty($error)): ?>
  <div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div>
<?php endif ?>

<!-- Top Section Switcher Navigation (All items of this type + Add new + Table view) -->
<?php if(!empty($allRecords) || $id === 'new'): ?>
<div class="section-switcher-container" style="margin-bottom: 24px;">
  <button type="button" class="tab-scroll-arrow tab-scroll-left" id="tabScrollLeft" aria-label="Scroll left">
    <?= icon('arrow-left-s-line') ?>
  </button>
  <div class="section-switcher-scroll" id="sectionSwitcherScroll">
    <a href="<?= url('admin/content/' . $type . '/new') ?>" class="section-pill section-pill--add <?= $id === 'new' ? 'is-active' : '' ?>">
      <?= icon('add-line') ?>
      <span>+ Add New <?= e($config['singular']) ?></span>
    </a>
    <?php foreach($allRecords as $row): 
      $isCurrent = (int)$row['id'] === (int)($record['id'] ?? 0);
      $rowIcon = !empty($row['is_featured']) ? 'star-fill' : ($type === 'blog_posts' ? 'article-line' : ($type === 'case_studies' ? 'file-list-3-line' : 'hospital-line'));
      $rowStatus = $row['status'] ?? 'published';
    ?>
      <a href="<?= url('admin/content/' . $type . '/' . $row['id']) ?>" class="section-pill <?= $isCurrent ? 'is-active' : '' ?>">
        <?= icon($rowIcon) ?>
        <span><?= e(mb_strimwidth($row['title'] ?? 'Untitled', 0, 32, '…')) ?></span>
        <?php if($rowStatus !== 'published'): ?>
          <span class="pill-dot is-hidden" title="Draft post"></span>
        <?php endif ?>
      </a>
    <?php endforeach ?>
    <a href="<?= url('admin/content/' . $type . '?view=list') ?>" class="section-pill section-pill--table" title="View table list">
      <?= icon('table-line') ?>
      <span>Table View</span>
    </a>
  </div>
  <button type="button" class="tab-scroll-arrow tab-scroll-right" id="tabScrollRight" aria-label="Scroll right">
    <?= icon('arrow-right-s-line') ?>
  </button>
</div>
<?php endif ?>

<form method="post" class="content-edit-form editorial-editor-form" id="editorialForm">
  <?= csrfField() ?>

  <div class="editorial-editor-grid">
    <!-- Left Main Column (Article Structure) -->
    <div class="editorial-editor-main">
      
      <!-- 1. Headline, Slug & Executive Summary -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('text') ?>
          <div>
            <h3>Headline &amp; Executive Summary</h3>
            <p>Title and lead synopsis matching the live website presentation.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-title">Headline / Title <span>*</span></label>
            <input id="input-title" name="title" type="text" class="input-editorial-title" value="<?= e($record['title'] ?? '') ?>" required data-title placeholder="Enter a descriptive, compelling article headline...">
          </div>

          <div class="admin-field admin-field--wide">
            <label for="input-slug">Permanent URL Slug <span>*</span></label>
            <div class="slug-input-group">
              <span class="slug-prefix">/<?= e($routePrefix) ?>/</span>
              <input id="input-slug" name="slug" type="text" value="<?= e($record['slug'] ?? '') ?>" required data-slug placeholder="url-friendly-slug">
            </div>
            <small>Lowercase letters, numbers and hyphens. Auto-generated from headline.</small>
          </div>

          <?php if($type === 'blog_posts'): ?>
          <div class="admin-field admin-field--wide">
            <label for="input-excerpt">Executive Excerpt (Lead Summary) <span>*</span></label>
            <textarea id="input-excerpt" name="excerpt" rows="3" required data-counter="220" placeholder="A concise 2-sentence summary that appears in bold lead styling on the article and in Google search snippet..."><?= e($record['excerpt'] ?? '') ?></textarea>
            <small class="field-counter"></small>
          </div>

          <div class="admin-field">
            <label for="input-author">Author / Byline <span>*</span></label>
            <input id="input-author" name="author" type="text" value="<?= e($record['author'] ?? 'Clinical Engineering Team') ?>" required placeholder="e.g. Clinical Engineering Team">
            <small>Displayed with avatar badge in the article byline strip.</small>
          </div>
          <?php elseif($type === 'case_studies'): ?>
          <div class="admin-field">
            <label for="input-client_name">Institutional Partner / Client Name <span>*</span></label>
            <input id="input-client_name" name="client_name" type="text" value="<?= e($record['client_name'] ?? '') ?>" required placeholder="e.g. SN Medical College &amp; Hospital, Agra">
          </div>

          <div class="admin-field">
            <label for="input-industry">Healthcare Sector / Specialization</label>
            <input id="input-industry" name="industry" type="text" value="<?= e($record['industry'] ?? '') ?>" placeholder="e.g. Intensive Care &amp; Central Pipeline Manifold">
          </div>
          <?php endif ?>
        </div>
      </div>

      <!-- 2. Hero Cover Photography -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('image-2-line') ?>
          <div>
            <h3>Featured Cover Photography</h3>
            <p>High-resolution cover image presented edge-to-edge across the top of the story.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide editorial-cover-field-group">
            <label for="input-thumbnail">Cover Image <span>*</span></label>
            
            <div class="editorial-asset-toolbar">
              <div class="asset-input-wrapper">
                <span class="asset-input-icon"><?= icon('image-line') ?></span>
                <input id="input-thumbnail" name="thumbnail" value="<?= e($record['thumbnail'] ?? '') ?>" required placeholder="Select from library or enter path (e.g. assets/images/manifold.jpg)" class="asset-path-input">
                <button type="button" class="btn-clear-cover" id="btnClearCover" title="Remove cover image" style="<?= empty($record['thumbnail']) ? 'display:none;' : '' ?>">
                  <?= icon('close-circle-fill') ?>
                </button>
              </div>
              <div class="asset-toolbar-actions">
                <button type="button" class="btn-admin-action asset-picker-button" id="btnOpenLibrary">
                  <?= icon('folder-image-line') ?> <span>Choose from library</span>
                </button>
                <label class="btn-admin-action btn-admin-upload">
                  <?= icon('upload-cloud-2-line') ?> <span>Upload photo</span>
                  <input type="file" class="asset-upload" accept="image/jpeg,image/png,image/webp">
                </label>
              </div>
            </div>
            <small class="upload-state" aria-live="polite"></small>
            
            <!-- Live Cover Preview Box -->
            <div class="editorial-preview-cover-box" id="coverPreviewBox" style="<?= empty($record['thumbnail']) ? 'display:none;' : '' ?>">
              <img id="coverPreviewImg" src="<?= photo($record['thumbnail'] ?? '') ?>" alt="Cover preview" loading="lazy">
              <div class="cover-preview-overlay">
                <span class="cover-crop-badge">
                  <?= icon('aspect-ratio-line') ?> 16:9 Editorial Aspect Ratio · Live Preview
                </span>
                <div class="cover-overlay-actions">
                  <button type="button" class="cover-action-pill asset-picker-trigger" id="btnChangeCoverPhoto">
                    <?= icon('refresh-line') ?> Change Photo
                  </button>
                </div>
              </div>
            </div>

            <!-- Empty State Placeholder when no image is selected -->
            <div class="editorial-empty-cover-placeholder" id="coverEmptyPlaceholder" style="<?= !empty($record['thumbnail']) ? 'display:none;' : '' ?>">
              <div class="empty-cover-icon">
                <?= icon('image-add-line') ?>
              </div>
              <div class="empty-cover-info">
                <strong>No cover photography assigned</strong>
                <p>Select a verified photograph from the media library or upload a high-resolution 16:9 cover image.</p>
              </div>
              <button type="button" class="btn-admin-action asset-picker-button">
                <?= icon('folder-image-line') ?> Browse Media Library
              </button>
            </div>
          </div>

          <div class="admin-field admin-field--wide">
            <label for="input-image_alt">Image Description (Alt Text) <span>*</span></label>
            <input id="input-image_alt" name="image_alt" type="text" value="<?= e($record['image_alt'] ?? '') ?>" required placeholder="Describe the installation or clinical setting depicted in this photo">
            <small>Required for accessibility and Google Image search ranking.</small>
          </div>
        </div>
      </div>

      <!-- 3. WYSIWYG Content Sections -->
      <?php if($type === 'blog_posts'): ?>
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('quill-pen-line') ?>
          <div>
            <h3>Article Body &amp; Clinical Narrative</h3>
            <p>Full rich-text editor with headings, formatted quotes, bullet lists, links, tables, and images.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-content">Content (WYSIWYG Rich Editor) <span>*</span></label>
            <div class="rich-editor-container" data-field-id="input-content">
              <div class="rich-editor" id="quill-input-content" data-for="input-content"></div>
              <textarea id="input-content" name="content" class="rich-source" rows="12"><?= e($record['content'] ?? '') ?></textarea>
            </div>
            <small>Headings (H2, H3) automatically generate the in-article Table of Contents on the live website.</small>
          </div>
        </div>
      </div>

      <?php elseif($type === 'case_studies'): ?>
      <!-- Phase 1 Challenge -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('alarm-warning-line') ?>
          <div>
            <h3>Phase 01 — The Clinical Challenge</h3>
            <p>Initial operational bottlenecks, infrastructure limitations, or gas delivery needs.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-challenge">The Challenge <span>*</span></label>
            <div class="rich-editor-container" data-field-id="input-challenge">
              <div class="rich-editor" id="quill-input-challenge" data-for="input-challenge"></div>
              <textarea id="input-challenge" name="challenge" class="rich-source" rows="8"><?= e($record['challenge'] ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Phase 2 Solution -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('shield-check-line') ?>
          <div>
            <h3>Phase 02 — Our Engineered Solution</h3>
            <p>Equipment deployed, pipeline sizing, manifold fittings, safety telemetry, and dispatch protocol.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-solution">Our Solution <span>*</span></label>
            <div class="rich-editor-container" data-field-id="input-solution">
              <div class="rich-editor" id="quill-input-solution" data-for="input-solution"></div>
              <textarea id="input-solution" name="solution" class="rich-source" rows="8"><?= e($record['solution'] ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Phase 3 Quantified Results -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('bar-chart-2-line') ?>
          <div>
            <h3>Phase 03 — Quantified Performance Metrics</h3>
            <p>Key impact metrics displayed in the scoreboard (e.g. Metric: "99.98%", Label: "Pipeline Uptime").</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label>Measured Results Key-Values</label>
            <div class="keyvalue-editor">
              <input type="hidden" name="results" class="keyvalue-value" value="<?= e($record['results'] ?: '{"Continuous Supply Uptime":"99.98%","Emergency Refill Speed":"< 2 Hours","Cylinder Hydro-Test Compliance":"100% PESO"}') ?>">
              <div class="keyvalue-items"></div>
              <button type="button" class="btn btn-outline add-keyvalue"><?= icon('add-line') ?> Add Metric</button>
            </div>
          </div>

          <div class="admin-field admin-field--wide">
            <label>Equipment &amp; Products Deployed</label>
            <?php 
              $selectedProducts = json_decode($record['products_used'] ?? '[]', true) ?: [];
            ?>
            <div class="product-multiselect">
              <?php foreach($allProducts as $p): ?>
                <label class="checkbox-label">
                  <input type="checkbox" name="products_used[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $selectedProducts) ? 'checked' : '' ?>>
                  <?= e($p['name']) ?>
                </label>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>

      <?php else: ?>
      <!-- Use Cases -->
      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('hospital-line') ?>
          <div>
            <h3>Setting Context &amp; Operational Demands</h3>
            <p>Operational background, patient care demands, and cylinder consumption profile.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-application_context">Care in this setting (Context) <span>*</span></label>
            <div class="rich-editor-container" data-field-id="input-application_context">
              <div class="rich-editor" id="quill-input-application_context" data-for="input-application_context"></div>
              <textarea id="input-application_context" name="application_context" class="rich-source" rows="8"><?= e($record['application_context'] ?? '') ?></textarea>
            </div>
          </div>
        </div>
      </div>

      <div class="admin-panel editorial-card-panel">
        <div class="panel-section-title">
          <?= icon('check-double-line') ?>
          <div>
            <h3>Engineered Solutions &amp; Equipment Benefits</h3>
            <p>How Shree Ji Gas Industries configures manifolds, cylinder banks, and safety support.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-benefits">How we can help (Benefits &amp; Support) <span>*</span></label>
            <div class="rich-editor-container" data-field-id="input-benefits">
              <div class="rich-editor" id="quill-input-benefits" data-for="input-benefits"></div>
              <textarea id="input-benefits" name="benefits" class="rich-source" rows="8"><?= e($record['benefits'] ?? '') ?></textarea>
            </div>
          </div>

          <div class="admin-field admin-field--wide">
            <label>Related Gas Equipment for this Setting</label>
            <?php 
              $selectedRelated = json_decode($record['related_products'] ?? '[]', true) ?: [];
            ?>
            <div class="product-multiselect">
              <?php foreach($allProducts as $p): ?>
                <label class="checkbox-label">
                  <input type="checkbox" name="related_products[]" value="<?= $p['id'] ?>" <?= in_array($p['id'], $selectedRelated) ? 'checked' : '' ?>>
                  <?= e($p['name']) ?>
                </label>
              <?php endforeach ?>
            </div>
          </div>
        </div>
      </div>
      <?php endif ?>

    </div>

    <!-- Right Sidebar Column (Meta, Publishing, SEO) -->
    <div class="editorial-editor-sidebar">
      
      <!-- 1. Publication & Visibility -->
      <div class="admin-panel sidebar-panel">
        <div class="sidebar-panel-header">
          <?= icon('send-plane-line') ?>
          <h4>Publishing &amp; Status</h4>
        </div>
        
        <div class="sidebar-fields">
          <div class="admin-field">
            <label for="input-status">Publication Status</label>
            <select id="input-status" name="status">
              <option value="published" <?= $isPublished ? 'selected' : '' ?>>Published — Live on website</option>
              <option value="draft" <?= !$isPublished ? 'selected' : '' ?>>Draft — Admin preview only</option>
            </select>
          </div>

          <div class="admin-field">
            <label for="input-published_at">Publication Date</label>
            <?php 
              $pubDate = !empty($record['published_at']) ? date('Y-m-d\TH:i', strtotime($record['published_at'])) : date('Y-m-d\TH:i');
            ?>
            <input id="input-published_at" name="published_at" type="datetime-local" value="<?= e($pubDate) ?>">
            <small>Used for chronologic sorting and byline display.</small>
          </div>

          <div class="admin-field">
            <label class="checkbox-label">
              <input type="checkbox" name="is_featured" value="1" <?= $isFeatured ? 'checked' : '' ?>>
              <span><?= icon('star-line') ?> Feature on Homepage</span>
            </label>
          </div>
        </div>
      </div>

      <!-- 2. Taxonomy & Tags -->
      <div class="admin-panel sidebar-panel">
        <div class="sidebar-panel-header">
          <?= icon('price-tag-3-line') ?>
          <h4>Taxonomy &amp; Organization</h4>
        </div>

        <div class="sidebar-fields">
          <?php if($type === 'blog_posts'): ?>
          <div class="admin-field">
            <label for="input-cat_id">Journal Category</label>
            <select id="input-cat_id" name="cat_id">
              <option value="">Choose a category</option>
              <?php foreach($blogCategories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ((int)($record['cat_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                  <?= e($cat['name']) ?>
                </option>
              <?php endforeach ?>
            </select>
          </div>
          <?php endif ?>

          <?php if(isset($config['fields']['tags'])): ?>
          <div class="admin-field">
            <label for="input-tags">Search &amp; Filter Tags</label>
            <input id="input-tags" name="tags" type="text" data-tags value="<?= e($record['tags'] ?? '') ?>" placeholder="Equipment, Planning, Hospital">
            <div class="tag-preview"></div>
            <small>Comma-separated list of topics for filtering.</small>
          </div>
          <?php endif ?>
        </div>
      </div>

      <!-- 3. Google SERP Live Snippet Preview (SEO) -->
      <div class="admin-panel sidebar-panel">
        <div class="sidebar-panel-header">
          <?= icon('search-eye-line') ?>
          <h4>Search Engine Optimization (SEO)</h4>
        </div>

        <div class="sidebar-fields">
          <!-- Live Google Search Result Box -->
          <div class="google-serp-preview">
            <div class="serp-preview-top">
              <img src="<?= asset('images/logo.svg') ?>" alt="" width="16" height="16">
              <div>
                <span class="serp-preview-domain"><?= parse_url(SITE_URL, PHP_URL_HOST) ?: 'shreejigas.com' ?></span>
                <span class="serp-preview-url" id="serpUrlPreview">/<?= e($routePrefix) ?>/<?= e($recordSlug ?: 'your-slug') ?></span>
              </div>
            </div>
            <div class="serp-preview-title" id="serpTitlePreview">
              <?= e($seo['meta_title'] ?? ($record['title'] ?? 'Title · Shree Ji Gas')) ?>
            </div>
            <div class="serp-preview-desc" id="serpDescPreview">
              <?= e($seo['meta_desc'] ?? ($record['excerpt'] ?? 'Shree Ji Gas Industries provides medical oxygen and industrial gas cylinders in Agra.')) ?>
            </div>
          </div>

          <div class="admin-field">
            <label for="input-seo-meta_title">SEO Title Tag</label>
            <input id="input-seo-meta_title" name="seo[meta_title]" type="text" data-counter="60" value="<?= e($seo['meta_title'] ?? '') ?>" placeholder="<?= e(mb_strimwidth($record['title'] ?? '', 0, 50)) ?> · Shree Ji Gas">
            <small class="field-counter"></small>
          </div>

          <div class="admin-field">
            <label for="input-seo-meta_desc">SEO Meta Description</label>
            <textarea id="input-seo-meta_desc" name="seo[meta_desc]" rows="3" data-counter="155" placeholder="Brief summary for Google search result listing..."><?= e($seo['meta_desc'] ?? '') ?></textarea>
            <small class="field-counter"></small>
          </div>

          <div class="admin-field">
            <label for="input-seo-focus_keyword">Focus Keyword</label>
            <input id="input-seo-focus_keyword" name="seo[focus_keyword]" type="text" value="<?= e($seo['focus_keyword'] ?? '') ?>" placeholder="e.g. medical oxygen cylinder agra">
          </div>

          <div class="admin-field">
            <label for="input-seo-og_image">Social Share (OG Image)</label>
            <input id="input-seo-og_image" name="seo[og_image]" type="text" value="<?= e($seo['og_image'] ?? '') ?>" placeholder="Defaults to featured cover image">
          </div>
        </div>
      </div>

      <!-- 4. Quality & Compliance Checklist -->
      <div class="admin-panel sidebar-panel" style="background: #f8fafc;">
        <div class="sidebar-panel-header">
          <?= icon('checkbox-circle-line') ?>
          <h4>Editorial Quality Checklist</h4>
        </div>
        <ul class="editorial-checklist">
          <li><?= icon('check-line') ?> <strong>PESO Certified Data:</strong> Verify cylinder pressure and valve specifications.</li>
          <li><?= icon('check-line') ?> <strong>Heading Hierarchy:</strong> Use H2 for main topics, H3 for technical details.</li>
          <li><?= icon('check-line') ?> <strong>Clean Photography:</strong> Ensure photos show genuine clinical or industrial equipment.</li>
          <li><?= icon('check-line') ?> <strong>Direct Hotline:</strong> Agra emergency refills guaranteed within 2 hours.</li>
        </ul>
      </div>

    </div>
  </div>

  <!-- Sticky Save Bar -->
  <div class="visual-sticky-action-bar category-save-bar">
    <div class="visual-sticky-left">
      <a class="btn-back-link" href="<?= url('admin/content/' . $type) ?>">
        <?= icon('arrow-left-line') ?> Back to <?= e(strtolower($config['label'])) ?>
      </a>
    </div>
    <div class="visual-sticky-right">
      <span class="save-shortcut-hint">Press <strong>Ctrl+S</strong> to save anytime</span>
      <button class="btn btn-primary" type="submit" id="editorialSubmitBtn">
        Save <?= e(strtolower($config['singular'])) ?> <?= icon('check-line') ?>
      </button>
    </div>
  </div>
</form>
