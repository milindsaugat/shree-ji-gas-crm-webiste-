<?php 
$corePages = Seo::corePages();
$recordPageKey = $record['page_key'] ?? '';
$pageInfo = ($type === 'seo_meta' && !empty($recordPageKey)) ? ($corePages[$recordPageKey] ?? null) : null;
$publicUrl = !empty($record['slug']) ? ($type === 'pages' ? url($record['slug']) : ($config['route'] ? url($config['route'].'/'.$record['slug']) : '')) : ''; 
if ($type === 'seo_meta' && !empty($recordPageKey)) {
    $publicUrl = $recordPageKey === '/' ? url() : url(ltrim($recordPageKey, '/'));
}
$displayHeading = $pageInfo ? 'Edit SEO: ' . $pageInfo['name'] : $title;
?>
<div class="admin-page-heading">
  <div>
    <a class="back-link" href="<?= url('admin/content/'.$type) ?>"><?= icon('arrow-left-line') ?> <?= e($config['label']) ?></a>
    <h1><?= e($displayHeading) ?> <?php if($pageInfo): ?><small style="font-size:16px; font-weight:normal; color:#0284c7; font-family:monospace;">(<?= e($recordPageKey) ?>)</small><?php endif ?></h1>
  </div>
  <?php if($publicUrl): ?>
    <a class="btn btn-outline" href="<?= e($publicUrl) ?>" target="_blank" rel="noopener">View live page <?= icon('arrow-right-up-line') ?></a>
  <?php endif ?>
</div>
<?php if($error): ?><div class="admin-alert admin-alert--error" role="alert"><?= e($error) ?></div><?php endif ?>

<?php if($type === 'seo_meta'): ?>
  <!-- Live Google SERP Preview Card -->
  <div class="admin-panel" style="margin-bottom:24px; border-left:4px solid #1b3d6f;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
      <span style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.12em; color:#0284c7; display:flex; align-items:center; gap:6px;">
        <?= icon('search-eye-line') ?> Live Google Search Result Preview
      </span>
      <span style="font-size:11px; color:#64748b;">Instant preview as you type</span>
    </div>
    <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:8px; padding:18px; max-width:680px; box-shadow:0 1px 3px rgba(0,0,0,0.04);">
      <div style="font-size:12px; color:#202124; margin-bottom:5px; display:flex; align-items:center; gap:8px;">
        <img src="<?= photo(setting('logo')) ?>" width="18" height="18" style="border-radius:50%; object-fit:contain; border:1px solid #e2e8f0;" alt="">
        <div>
          <span style="color:#202124; font-weight:600; font-size:12px;"><?= e(SITE_NAME) ?></span>
          <span style="color:#5f6368; font-size:11px; margin-left:4px;">https://shreejigas.com<?= e($recordPageKey === '/' ? '' : $recordPageKey) ?></span>
        </div>
      </div>
      <h3 id="serpLiveTitle" style="margin:0 0 6px; font-size:18px; color:#1a0dab; font-family:arial,sans-serif; font-weight:400; line-height:1.3; text-decoration:none;">
        <?= e(($record['meta_title'] ?? '') ?: ($pageInfo['name'] ?? 'Website Page').' | '.SITE_NAME) ?>
      </h3>
      <p id="serpLiveDesc" style="margin:0; font-size:13px; color:#4d5156; font-family:arial,sans-serif; line-height:1.5;">
        <?= e(($record['meta_desc'] ?? '') ?: 'Configure a tailored meta description to encourage clicks from healthcare practitioners and industrial clients searching in Agra & NCR.') ?>
      </p>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const titleInput = document.getElementById('input-meta_title') || document.getElementById('input-meta-title');
      const descInput = document.getElementById('input-meta_desc') || document.getElementById('input-meta-desc');
      const liveTitle = document.getElementById('serpLiveTitle');
      const liveDesc = document.getElementById('serpLiveDesc');
      if (titleInput && liveTitle) {
        titleInput.addEventListener('input', function() {
          liveTitle.textContent = this.value.trim() || '<?= e(($pageInfo['name'] ?? 'Website Page').' | '.SITE_NAME) ?>';
        });
      }
      if (descInput && liveDesc) {
        descInput.addEventListener('input', function() {
          liveDesc.textContent = this.value.trim() || 'Configure a tailored meta description to encourage clicks from healthcare practitioners and industrial clients.';
        });
      }
    });
  </script>
<?php endif ?>

<form method="post" class="content-edit-form">
<div class="admin-panel">
  <?= csrfField() ?>
  <div class="edit-fields">
    <?php foreach($config['fields'] as $key=>$rule): $name=$key;$value=$record[$key]??'';$required=str_ends_with($rule,'!');$kind=rtrim($rule,'!');require ROOT.'/views/admin/field.php'; endforeach ?>
  </div>
</div>
<?php if($config['route'] || $type === 'pages'): ?>
  <details class="admin-panel seo-details">
    <summary><?= icon('search-eye-line') ?> Search engine &amp; social sharing settings</summary>
    <div class="edit-fields">
      <?php $seoConfig=$resources['seo_meta']['fields']; foreach($seoConfig as $key=>$rule): if($key==='page_key')continue;$name='seo['.$key.']';$value=$seo[$key]??'';$required=false;$kind=rtrim($rule,'!');require ROOT.'/views/admin/field.php';endforeach ?>
    </div>
  </details>
<?php endif ?><div class="visual-sticky-action-bar category-save-bar">
    <div class="visual-sticky-left">
      <a class="btn-back-link" href="<?= url('admin/content/'.$type) ?>">
        <?= icon('arrow-left-line') ?> Back to <?= e(strtolower($config['label'])) ?>
      </a>
    </div>
    <div class="visual-sticky-right">
      <button class="btn btn-primary" type="submit">
        Save <?= e(strtolower($config['singular'])) ?> <?= icon('check-line') ?>
      </button>
    </div>
</div>
</form>
