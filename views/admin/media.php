<div class="admin-page-heading media-page-heading">
  <div>
    <span class="eyebrow">DIGITAL ASSET MANAGEMENT</span>
    <h1>Media &amp; Document Library</h1>
    <p>Upload, organize and reference medical equipment photos, brochures and certificates across your website.</p>
  </div>
  <div class="media-heading-actions">
    <button type="button" class="btn btn-primary btn-sm" id="toggleUploadPanelBtn">
      <?= icon('upload-cloud-2-line') ?> <span>Upload New Asset</span>
    </button>
  </div>
</div>

<?php if($error): ?>
  <div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div>
<?php endif ?>

<?php if(!extension_loaded('gd')): ?>
  <div class="admin-notice">
    <?= icon('information-line') ?>
    <p>PHP GD is disabled. Enable <code>extension=gd</code> in php.ini to auto-optimise images to WebP. PDF uploads remain fully supported.</p>
  </div>
<?php endif ?>

<!-- Upload Panel (Collapsible or Prominent) -->
<div class="admin-panel media-upload-panel" id="mediaUploadPanel">
  <div class="media-upload-panel__header">
    <h3><?= icon('upload-cloud-line') ?> Upload Digital Asset</h3>
    <small>Supports JPG, PNG, WebP and PDF up to 10 MB · Images auto-converted to web-ready format</small>
  </div>
  <form method="post" enctype="multipart/form-data" class="media-upload-form" id="mediaUploadForm">
    <?= csrfField() ?>
    <div class="media-upload-grid">
      <div class="drop-zone media-drop-zone" id="mediaDropZone">
        <label for="mediaFile" class="drop-zone-label">
          <div class="drop-zone-icon"><?= icon('image-add-line') ?></div>
          <strong>Choose a file or drag &amp; drop here</strong>
          <span>JPG, PNG, WebP or PDF · up to 10 MB</span>
          <div class="drop-zone-selected" id="dropZoneFilename" hidden></div>
        </label>
        <input type="file" id="mediaFile" name="file" required accept="image/jpeg,image/png,image/webp,application/pdf">
      </div>
      <div class="media-meta-inputs">
        <div class="form-row">
          <label>Image Description / Document Title <span>*</span>
            <input id="mediaAlt" name="image_alt" required minlength="3" placeholder="e.g. 47L High-Pressure Medical Oxygen Cylinder manifold setup">
            <small class="field-hint">Essential for accessibility (screen readers) and SEO image alt tags.</small>
          </label>
        </div>
        <div class="form-row">
          <label>Custom Filename <small>(optional)</small>
            <input id="mediaName" name="filename" placeholder="e.g. medical-oxygen-cylinder-47l">
          </label>
        </div>
        <div class="media-upload-submit-row">
          <button type="submit" class="btn btn-primary" id="btnUploadSubmit">
            Upload Asset <?= icon('check-line') ?>
          </button>
          <span class="upload-progress-text" id="uploadStatusText"></span>
        </div>
      </div>
    </div>
  </form>
</div>

<!-- Toolbar: Live Search & Type Filtering -->
<div class="admin-panel media-toolbar-panel">
  <div class="media-toolbar-layout">
    <div class="media-search-wrap">
      <?= icon('search-line') ?>
      <input type="search" id="mediaSearchInput" placeholder="Quick search assets by filename, alt text or keyword…" aria-label="Search media files">
    </div>
    <div class="media-filter-pills" role="radiogroup" aria-label="Filter by asset kind">
      <button type="button" class="media-pill is-active" data-filter="all">All Assets (<?= count($rows) ?>)</button>
      <button type="button" class="media-pill" data-filter="image">Images</button>
      <button type="button" class="media-pill" data-filter="pdf">PDF Documents</button>
    </div>
    <div class="media-stats-badge">
      <span id="visibleAssetCount"><?= count($rows) ?></span> of <?= count($rows) ?> files
    </div>
  </div>
</div>

<!-- Modern Media Grid -->
<div class="media-grid-container" id="mediaGridContainer">
  <?php foreach($rows as $row): 
      $isPdf = $row['type'] === 'application/pdf';
      $ext = strtoupper(pathinfo($row['path'], PATHINFO_EXTENSION) ?: ($isPdf ? 'PDF' : 'IMG'));
      $sizeKb = round($row['size'] / 1024);
      $date = substr($row['uploaded_at'], 0, 10);
  ?>
    <article class="media-card" data-type="<?= $isPdf ? 'pdf' : 'image' ?>" data-filename="<?= strtolower(e($row['filename'])) ?>" data-alt="<?= strtolower(e($row['image_alt'])) ?>">
      <div class="media-card__thumb">
        <span class="media-badge media-badge--format"><?= e($ext) ?></span>
        <span class="media-badge media-badge--size"><?= $sizeKb ?> KB</span>
        <a href="<?= photo($row['path']) ?>" target="_blank" rel="noopener" class="media-thumb-link" title="Open full-size asset">
          <?php if(!$isPdf): ?>
            <img src="<?= photo($row['path']) ?>" alt="<?= e($row['image_alt']) ?>" loading="lazy">
          <?php else: ?>
            <div class="media-pdf-placeholder">
              <?= icon('file-pdf-2-line') ?>
              <span>PDF DOCUMENT</span>
            </div>
          <?php endif ?>
        </a>
      </div>
      <div class="media-card__details">
        <strong class="media-filename" title="<?= e($row['filename']) ?>"><?= e($row['filename']) ?></strong>
        <p class="media-alt-text" title="<?= e($row['image_alt']) ?>"><?= e($row['image_alt'] ?: 'No description provided') ?></p>
        <div class="media-card__meta">
          <span><?= icon('calendar-line') ?> <?= e($date) ?></span>
          <span><?= icon('hard-drive-2-line') ?> <?= $sizeKb ?> KB</span>
        </div>
        <div class="media-card__actions">
          <button type="button" class="btn-media-action btn-copy-url" data-copy="<?= e(SITE_URL.'/'.$row['path']) ?>" title="Copy full website URL">
            <?= icon('links-line') ?> <span>Copy URL</span>
          </button>
          <a href="<?= photo($row['path']) ?>" target="_blank" rel="noopener" class="btn-media-action" title="Open in new window">
            <?= icon('external-link-line') ?>
          </a>
          <form method="post" class="confirm-delete media-delete-form">
            <?= csrfField() ?>
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
            <button type="submit" class="btn-media-action btn-media-delete" aria-label="Delete <?= e($row['filename']) ?>" title="Delete asset">
              <?= icon('delete-bin-line') ?>
            </button>
          </form>
        </div>
      </div>
    </article>
  <?php endforeach ?>
</div>

<div class="admin-search-empty" id="mediaSearchEmpty" hidden>
  <?= icon('image-line') ?>
  <h3>No matching assets found</h3>
  <p>Try searching with another keyword or change your file type filter.</p>
</div>

<?php if(!$rows): ?>
  <div class="admin-empty">
    <?= icon('image-add-line') ?>
    <h2>Your media library is ready.</h2>
    <p>Upload your first equipment photograph, clinical diagram, or technical PDF brochure above.</p>
  </div>
<?php endif ?>
