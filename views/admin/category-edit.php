<div class="admin-page-heading">
  <div>
    <a class="back-link" href="<?= url('admin/content/categories') ?>"><?= icon('arrow-left-line') ?> Product categories</a>
    <span class="eyebrow">CATALOGUE TAXONOMY</span>
    <h1><?= e($title) ?></h1>
    <p>Configure category name, iconography, web route and catalogue menu ordering.</p>
  </div>
  <?php if(!empty($record['slug'])): ?>
    <div style="display:flex; gap:10px; align-items:center;">
      <a class="btn btn-outline" href="<?= url('categories/'.$record['slug']) ?>" target="_blank" rel="noopener">
        View on website <?= icon('arrow-right-up-line') ?>
      </a>
    </div>
  <?php endif ?>
</div>

<?php if($error): ?>
  <div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div>
<?php endif ?>

<form method="post" class="content-edit-form category-editor-form">
  <?= csrfField() ?>
  
  <div class="category-editor-grid">
    <!-- Main Left Column -->
    <div class="category-editor-main">
      <!-- General Details Card -->
      <div class="admin-panel category-card-panel">
        <div class="panel-section-title">
          <?= icon('information-line') ?>
          <div>
            <h3>Category Identity & Description</h3>
            <p>Set the public title, URL slug and descriptive overview for this equipment range.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-name">Category Name <span>*</span></label>
            <input id="input-name" name="name" type="text" value="<?= e($record['name'] ?? '') ?>" required data-title placeholder="e.g. Medical Oxygen">
          </div>

          <div class="admin-field admin-field--wide">
            <label for="input-slug">Web Route Slug <span>*</span></label>
            <div class="slug-input-group">
              <span class="slug-prefix">/categories/</span>
              <input id="input-slug" name="slug" type="text" value="<?= e($record['slug'] ?? '') ?>" required data-slug placeholder="medical-oxygen">
            </div>
            <small>Used in URLs, sitemap, and category filter links. Only lowercase letters, numbers, and dashes.</small>
          </div>

          <div class="admin-field admin-field--wide">
            <label for="input-description">Category Description</label>
            <textarea id="input-description" name="description" rows="3" placeholder="e.g. Oxygen cylinders, regulators & delivery equipment for hospitals and clinical settings..."><?= e($record['description'] ?? '') ?></textarea>
            <small>Displayed in the website header mega-menu, about page capability cards, and catalogue header.</small>
          </div>
        </div>
      </div>

      <!-- Iconography Card -->
      <div class="admin-panel category-card-panel">
        <div class="panel-section-title">
          <?= icon('apps-2-line') ?>
          <div>
            <h3>Visual Iconography & Healthcare Symbol</h3>
            <p>Choose an iconography badge representing this equipment category across the website.</p>
          </div>
        </div>

        <!-- Live Icon Preview Box -->
        <div class="icon-picker-hero">
          <div class="icon-preview-box">
            <div class="icon-avatar" id="iconAvatar">
              <i id="iconAvatarGlyph" class="ri-<?= e(preg_replace('/^ri-/', '', $record['icon'] ?: 'apps-2-line')) ?>"></i>
            </div>
            <div class="icon-preview-meta">
              <span class="icon-preview-label">Active Symbol</span>
              <strong id="iconPreviewName"><?= e($record['icon'] ?: 'apps-2-line') ?></strong>
              <small>Appears in mega-menu, category tabs, and homepage spotlight</small>
            </div>
          </div>

          <div class="icon-input-wrapper">
            <label for="input-icon">Remix Icon Name</label>
            <div class="icon-input-field">
              <i class="ri-search-eye-line"></i>
              <input id="input-icon" name="icon" type="text" value="<?= e($record['icon'] ?? 'apps-2-line') ?>" placeholder="e.g. lungs-line">
            </div>
            <small>Enter any valid Remix Icon class or pick one from the clinical presets below.</small>
          </div>
        </div>

        <!-- Clinical Preset Icons Grid -->
        <div class="icon-preset-section">
          <span class="preset-heading">Quick-Select Clinical & Industrial Presets</span>
          <div class="icon-presets-grid" id="iconPresetsGrid">
            <?php
            $presetIcons = [
              ['id' => 'lungs-line', 'label' => 'Medical Oxygen', 'sub' => 'Respiratory & Gases'],
              ['id' => 'scissors-2-line', 'label' => 'Surgical Tools', 'sub' => 'Precision & OR'],
              ['id' => 'dashboard-3-line', 'label' => 'Gas Regulators', 'sub' => 'Manifolds & Gauges'],
              ['id' => 'hospital-line', 'label' => 'Hospital Care', 'sub' => 'Ward & Clinic'],
              ['id' => 'first-aid-kit-line', 'label' => 'Emergency Kit', 'sub' => 'Trauma & Urgent'],
              ['id' => 'flask-line', 'label' => 'Lab & Pure Gas', 'sub' => 'Calibration Gases'],
              ['id' => 'heart-pulse-line', 'label' => 'Critical Care', 'sub' => 'ICU & Monitoring'],
              ['id' => 'stethoscope-line', 'label' => 'Diagnostics', 'sub' => 'Clinical Routine'],
              ['id' => 'shield-check-line', 'label' => 'Safety & PESO', 'sub' => 'Certified Compliance'],
              ['id' => 'fire-line', 'label' => 'Industrial Gas', 'sub' => 'Cutting & Welding'],
              ['id' => 'truck-line', 'label' => 'Depot Logistics', 'sub' => 'Bulk Delivery'],
              ['id' => 'temp-cold-line', 'label' => 'Cryogenics', 'sub' => 'Liquid Vessels & Dewars'],
            ];
            $currentIcon = preg_replace('/^ri-/', '', $record['icon'] ?? 'apps-2-line');
            foreach ($presetIcons as $preset):
              $isSelected = $currentIcon === $preset['id'];
            ?>
              <button type="button" class="icon-preset-btn <?= $isSelected ? 'is-selected' : '' ?>" data-icon="<?= e($preset['id']) ?>" title="Select <?= e($preset['label']) ?>">
                <div class="preset-icon-circle">
                  <i class="ri-<?= e($preset['id']) ?>"></i>
                </div>
                <div class="preset-text">
                  <strong><?= e($preset['label']) ?></strong>
                  <small><?= e($preset['sub']) ?></small>
                </div>
                <div class="preset-check"><?= icon('check-line') ?></div>
              </button>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Sidebar Column -->
    <div class="category-editor-sidebar">
      <!-- Placement & Order Card -->
      <div class="admin-panel category-card-panel">
        <div class="panel-section-title">
          <?= icon('sort-asc') ?>
          <div>
            <h3>Menu Sequence</h3>
            <p>Priority order on website.</p>
          </div>
        </div>

        <div class="edit-fields">
          <div class="admin-field admin-field--wide">
            <label for="input-sort_order">Display Priority (Sort Order)</label>
            <input id="input-sort_order" name="sort_order" type="number" min="0" max="999" value="<?= e($record['sort_order'] ?? 0) ?>">
            <small>Lower numbers display first (e.g. 0, 1, 2) in the header navigation menu and category tabs.</small>
          </div>
        </div>

        <?php if(!empty($record['slug'])): ?>
          <div class="category-public-route-box">
            <span class="route-badge-title">Live Public Route</span>
            <a href="<?= url('categories/'.$record['slug']) ?>" target="_blank" rel="noopener" class="route-badge-link">
              <span><?= SITE_URL ?>/categories/<strong><?= e($record['slug']) ?></strong></span>
              <?= icon('arrow-right-up-line') ?>
            </a>
          </div>
        <?php endif ?>
      </div>

      <!-- Assigned Products Card -->
      <div class="admin-panel category-card-panel">
        <div class="panel-section-title">
          <?= icon('archive-line') ?>
          <div>
            <h3>Assigned Equipment (<?= count($assignedProducts) ?>)</h3>
            <p>Products currently in this category.</p>
          </div>
        </div>

        <?php if(!empty($assignedProducts)): ?>
          <div class="category-assigned-products">
            <?php foreach($assignedProducts as $prod): ?>
              <div class="assigned-product-item">
                <div class="assigned-product-thumb">
                  <?php if(!empty($prod['thumbnail'])): ?>
                    <img src="<?= photo($prod['thumbnail']) ?>" alt="" width="38" height="38" loading="lazy">
                  <?php else: ?>
                    <div class="thumb-empty"><?= icon('archive-line') ?></div>
                  <?php endif ?>
                </div>
                <div class="assigned-product-info">
                  <a href="<?= url('admin/content/products/'.$prod['id']) ?>" class="assigned-product-title" target="_blank" title="Edit <?= e($prod['name']) ?>">
                    <?= e($prod['name']) ?>
                  </a>
                  <span class="assigned-product-meta">
                    <span class="status-badge <?= ($prod['status']??'published')==='published'?'':'status-badge--muted' ?>">
                      <?= e(ucfirst($prod['status']??'published')) ?>
                    </span>
                  </span>
                </div>
                <a href="<?= url('admin/content/products/'.$prod['id']) ?>" class="btn-product-edit" title="Edit in Visual Editor" target="_blank">
                  <?= icon('edit-line') ?>
                </a>
              </div>
            <?php endforeach ?>
          </div>
        <?php else: ?>
          <div class="category-no-products">
            <?= icon('inbox-line') ?>
            <p>No products assigned to this category yet.</p>
            <small>Assign products when editing items in the Products workspace.</small>
          </div>
        <?php endif ?>
      </div>

      <!-- SEO Settings Accordion -->
      <details class="admin-panel category-card-panel seo-details">
        <summary>
          <?= icon('search-eye-line') ?>
          <span>Search Engine (SEO) Meta</span>
        </summary>
        <div class="edit-fields" style="padding-top: 14px;">
          <div class="admin-field admin-field--wide">
            <label for="seo-meta_title">Custom Meta Title</label>
            <input id="seo-meta_title" name="seo[meta_title]" type="text" value="<?= e($seo['meta_title'] ?? '') ?>" placeholder="Medical Oxygen Cylinders & Equipment | Shree Ji Gas" data-counter="60">
            <small class="field-counter"></small>
          </div>

          <div class="admin-field admin-field--wide">
            <label for="seo-meta_desc">Meta Description</label>
            <textarea id="seo-meta_desc" name="seo[meta_desc]" rows="3" placeholder="Explore certified medical oxygen cylinders, regulators, and clinical equipment..." data-counter="155"><?= e($seo['meta_desc'] ?? '') ?></textarea>
            <small class="field-counter"></small>
          </div>

          <div class="admin-field admin-field--wide">
            <label for="seo-robots">Search Engine Indexing (Robots)</label>
            <select id="seo-robots" name="seo[robots]">
              <?php foreach(['index,follow'=>'Index & Follow (Recommended)','noindex,follow'=>'No Index, Follow links','noindex,nofollow'=>'Do Not Index or Follow'] as $v=>$lbl): ?>
                <option value="<?= $v ?>" <?= ($seo['robots']??'index,follow')===$v?'selected':'' ?>><?= $lbl ?></option>
              <?php endforeach ?>
            </select>
          </div>
        </div>
      </details>
    </div>
  </div>

  <!-- Sticky Bottom Action & Save Bar -->
  <div class="visual-sticky-action-bar category-save-bar">
    <div class="visual-sticky-left">
      <a class="btn-back-link" href="<?= url('admin/content/categories') ?>">
        <?= icon('arrow-left-line') ?> Back to product categories
      </a>
    </div>
    <div class="visual-sticky-right">
      <button class="btn btn-primary btn-save-category" type="submit">
        Save category <?= icon('check-line') ?>
      </button>
    </div>
  </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const iconInput = document.getElementById('input-icon');
  const glyph = document.getElementById('iconAvatarGlyph');
  const nameLabel = document.getElementById('iconPreviewName');
  const presetButtons = document.querySelectorAll('.icon-preset-btn');

  function updateIcon(name) {
    if (!name) return;
    const clean = name.replace(/^ri-/, '').trim();
    if (glyph) glyph.className = 'ri-' + clean;
    if (nameLabel) nameLabel.textContent = clean;
    if (iconInput && iconInput.value !== clean) iconInput.value = clean;

    presetButtons.forEach(btn => {
      if (btn.getAttribute('data-icon') === clean) {
        btn.classList.add('is-selected');
      } else {
        btn.classList.remove('is-selected');
      }
    });
  }

  if (iconInput) {
    iconInput.addEventListener('input', function() {
      updateIcon(this.value);
    });
  }

  presetButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const selected = this.getAttribute('data-icon');
      updateIcon(selected);
    });
  });

  // Auto-slugify if slug is empty or matches previous name
  const nameInput = document.getElementById('input-name');
  const slugInput = document.getElementById('input-slug');
  if (nameInput && slugInput && (!slugInput.value || slugInput.value.trim() === '')) {
    nameInput.addEventListener('input', function() {
      slugInput.value = this.value
        .toLowerCase()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/(^-|-$)+/g, '');
    });
  }
});
</script>
