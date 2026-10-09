<?php
$userName = explode(' ', Auth::user()['name'])[0];
$unread = $unreadCount ?? 0;
?>

<div class="admin-page-heading">
  <div>
    <span class="eyebrow">CENTRAL OPERATIONS DISPATCH &middot; AGRA DEPOT</span>
    <h1>Executive Operations Hub</h1>
    <p>Operational control center for Shree Ji Gas hospital cylinder supply, equipment catalogue, and customer procurement enquiries.</p>
  </div>
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <a class="btn btn-outline" href="<?= url() ?>" target="_blank" rel="noopener">
      View Live Site <?= icon('arrow-right-up-line') ?>
    </a>
    <a class="btn btn-primary" href="<?= url('admin/content/products/new') ?>">
      <?= icon('add-line') ?> Add Equipment
    </a>
  </div>
</div>

<?php if($unread > 0): ?>
  <div style="background:#fffbeb; border:1px solid #fef3c7; border-radius:4px; padding:14px 20px; margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; box-shadow:0 1px 4px rgba(217,119,6,0.06);">
    <div style="display:flex; align-items:center; gap:12px;">
      <div style="width:36px; height:36px; border-radius:4px; background:#fde68a; color:#92400e; display:flex; align-items:center; justify-content:center; font-size:18px; flex-shrink:0;">
        <?= icon('notification-3-line') ?>
      </div>
      <div>
        <strong style="color:#92400e; font-size:13px; display:block; margin-bottom:2px;">
          <?= $unread ?> New Customer <?= $unread === 1 ? 'Enquiry' : 'Enquiries' ?> Awaiting Procurement Follow-up
        </strong>
        <p style="margin:0; color:#b45309; font-size:12px;">Quick review improves order conversion for clinical oxygen cylinders and hospital equipment.</p>
      </div>
    </div>
    <a href="<?= url('admin/enquiries?status=new') ?>" class="btn btn-outline" style="border-color:#d97706; color:#92400e; padding:8px 16px; font-size:12px; font-weight:600; background:#ffffff;">
      Open Unread Leads <?= icon('arrow-right-line') ?>
    </a>
  </div>
<?php endif ?>

<?php if(DB_DRIVER==='sqlite' || setting('preview_notice')==='1'): ?>
  <div class="admin-notice" style="margin-bottom:24px;">
    <?= icon('information-line') ?>
    <div>
      <strong><?= DB_DRIVER==='sqlite' ? 'Working in local database preview mode.' : 'Review your equipment catalogue before publishing.' ?></strong>
      <p><?= DB_DRIVER==='sqlite' ? 'Database changes are stored in your local storage database. MySQL configuration is active in config/database.php.' : 'Product specifications and brand photos can be managed via the administration tools.' ?></p>
    </div>
  </div>
<?php endif ?>

<!-- Compact Operational Metric Cards -->
<div class="dashboard-stats">
  <!-- Products -->
  <a href="<?= url('admin/content/products') ?>" class="dashboard-stat-card">
    <div class="dashboard-stat-top">
      <span class="dashboard-stat-label">Equipment Catalogue</span>
      <?= icon('archive-line') ?>
    </div>
    <strong class="dashboard-stat-num"><?= (int)($counts['products'] ?? 0) ?></strong>
    <div class="dashboard-stat-bottom">
      <span>Active catalogue items</span>
      <span class="dashboard-stat-link">Manage <?= icon('arrow-right-line') ?></span>
    </div>
  </a>

  <!-- Enquiries -->
  <a href="<?= url('admin/enquiries') ?>" class="dashboard-stat-card" style="<?= $unread > 0 ? 'border-color:#f59e0b; background:#fffdfa;' : '' ?>">
    <div class="dashboard-stat-top">
      <span class="dashboard-stat-label">Customer Inquiries</span>
      <?= icon('inbox-2-line') ?>
    </div>
    <strong class="dashboard-stat-num" style="<?= $unread > 0 ? 'color:#b45309;' : '' ?>"><?= (int)($counts['enquiries'] ?? 0) ?></strong>
    <div class="dashboard-stat-bottom">
      <span style="<?= $unread > 0 ? 'font-weight:600; color:#b45309;' : '' ?>"><?= $unread > 0 ? "$unread new unread" : 'All reviewed' ?></span>
      <span class="dashboard-stat-link">View Inbox <?= icon('arrow-right-line') ?></span>
    </div>
  </a>

  <!-- Categories -->
  <a href="<?= url('admin/content/categories') ?>" class="dashboard-stat-card">
    <div class="dashboard-stat-top">
      <span class="dashboard-stat-label">Taxonomies</span>
      <?= icon('apps-2-line') ?>
    </div>
    <strong class="dashboard-stat-num"><?= (int)($counts['categories'] ?? 0) ?></strong>
    <div class="dashboard-stat-bottom">
      <span>Equipment sectors</span>
      <span class="dashboard-stat-link">View <?= icon('arrow-right-line') ?></span>
    </div>
  </a>

  <!-- Articles & Insights -->
  <a href="<?= url('admin/content/blog_posts') ?>" class="dashboard-stat-card">
    <div class="dashboard-stat-top">
      <span class="dashboard-stat-label">Articles &amp; News</span>
      <?= icon('article-line') ?>
    </div>
    <strong class="dashboard-stat-num"><?= (int)($counts['blog_posts'] ?? 0) ?></strong>
    <div class="dashboard-stat-bottom">
      <span>Published entries</span>
      <span class="dashboard-stat-link">Manage <?= icon('arrow-right-line') ?></span>
    </div>
  </a>

  <!-- Media Library -->
  <a href="<?= url('admin/media') ?>" class="dashboard-stat-card">
    <div class="dashboard-stat-top">
      <span class="dashboard-stat-label">Digital Vault</span>
      <?= icon('image-line') ?>
    </div>
    <strong class="dashboard-stat-num"><?= (int)($counts['media'] ?? 0) ?></strong>
    <div class="dashboard-stat-bottom">
      <span>Verified assets</span>
      <span class="dashboard-stat-link">Media Library <?= icon('arrow-right-line') ?></span>
    </div>
  </a>
</div>

<!-- 2-Column Ledger Grid -->
<div class="dashboard-grid">
  <!-- Left: Homepage Sections Control -->
  <section class="admin-panel" style="margin-bottom:0;">
    <div class="panel-header">
      <h2>
        <?= icon('layout-4-line') ?>
        Homepage Storytelling &amp; Architecture
      </h2>
      <a class="text-link" href="<?= url('admin/sections') ?>">
        All sections <?= icon('arrow-right-line') ?>
      </a>
    </div>
    <p style="font-size:12px; color:#5a7a8a; margin-bottom:14px;">Review narrative flow, section ordering, and live public visibility.</p>

    <div class="dashboard-section-links">
      <?php foreach(array_slice(Section::all(), 0, 5) as $section): ?>
        <a class="dashboard-section-item" href="<?= url('admin/sections/'.$section['section_key']) ?>">
          <span>
            <?= icon('edit-line') ?>
            <strong><?= e($section['title']) ?></strong>
            <small style="color:#8ca2b6; font-family:monospace; margin-left:4px;">(<?= e($section['section_key']) ?>)</small>
          </span>
          <span style="display:flex; align-items:center; gap:8px;">
            <span class="status-badge <?= $section['is_active'] ? '' : 'status-badge--muted' ?>">
              <?= $section['is_active'] ? 'Live' : 'Hidden' ?>
            </span>
            <i class="ri-arrow-right-line" style="color:#8ca2b6;"></i>
          </span>
        </a>
      <?php endforeach ?>
    </div>

    <div style="display:flex; gap:10px; align-items:center; padding-top:12px; border-top:1px solid #f1f5f8; flex-wrap:wrap;">
      <a href="<?= url('admin/sections') ?>" class="btn btn-outline" style="font-size:11px; padding:8px 14px;">
        <?= icon('list-check') ?> Reorder Sections
      </a>
      <a href="<?= url('admin/content/products/1') ?>" class="btn btn-outline" style="font-size:11px; padding:8px 14px;">
        <?= icon('macbook-line') ?> Visual Canvas
      </a>
    </div>
  </section>

  <!-- Right: Recent Enquiries Ledger -->
  <section class="admin-panel" style="margin-bottom:0;">
    <div class="panel-header">
      <h2>
        <?= icon('inbox-2-line') ?>
        Recent Customer Inquiries
      </h2>
      <a class="text-link" href="<?= url('admin/enquiries') ?>">
        Full Inbox <?= icon('arrow-right-up-line') ?>
      </a>
    </div>

    <?php if(!$enquiries): ?>
      <div class="admin-empty" style="padding:32px 16px;">
        <?= icon('inbox-2-line') ?>
        <h3 style="font-size:15px; margin:8px 0 4px;">No customer enquiries yet</h3>
        <p style="font-size:12px; margin:0;">Inquiries submitted through the website will appear here in real time.</p>
      </div>
    <?php else: ?>
      <div class="enquiry-preview-list">
        <?php foreach($enquiries as $enquiry): ?>
          <a class="enquiry-preview-item" href="<?= url('admin/enquiries') ?>#enquiry-row-<?= $enquiry['id'] ?>">
            <div class="enquiry-preview-meta">
              <strong>#<?= $enquiry['id'] ?> &middot; <?= e($enquiry['name']) ?></strong>
              <small><?= e($enquiry['product'] ?: 'General enquiry') ?></small>
            </div>
            <div class="enquiry-preview-right">
              <span class="status-pill status-pill--<?= e($enquiry['status']) ?>">
                <?= e(ucfirst($enquiry['status'])) ?>
              </span>
              <span style="font-size:11px; color:#8ca2b6; font-family:monospace;"><?= date('d M, H:i', strtotime($enquiry['created_at'])) ?></span>
              <i class="ri-arrow-right-line" style="color:#8ca2b6; font-size:13px;"></i>
            </div>
          </a>
        <?php endforeach ?>
      </div>
    <?php endif ?>
  </section>
</div>

<!-- Bottom Brand & Site Controls Bar -->
<div class="quick-start-panel">
  <div class="quick-start-content">
    <h2><?= icon('settings-3-line') ?> Operational Controls &amp; Directives</h2>
    <p>Configure company contact details, emergency WhatsApp hotline, statutory policies, and search indexing directives.</p>
  </div>
  <div class="quick-start-actions">
    <a class="btn btn-light" href="<?= url('admin/settings') ?>">
      Site Settings
    </a>
    <a class="btn btn-outline" style="border-color:rgba(255,255,255,0.3); color:#ffffff;" href="<?= url('admin/content/seo_meta') ?>">
      SEO Directives
    </a>
  </div>
</div>
