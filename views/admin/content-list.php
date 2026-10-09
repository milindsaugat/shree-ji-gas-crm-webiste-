<div class="admin-page-heading">
  <div>
    <span class="eyebrow"><?= $type === 'categories' ? 'CATALOGUE TAXONOMY' : 'CONTENT LIBRARY' ?></span>
    <h1><?= e($config['label']) ?></h1>
    <p><?= count($rows) ?> <?= strtolower($config['label']) ?> in your workspace.</p>
  </div>
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <?php if($type==='products'): ?>
      <a class="btn btn-outline" href="<?= url('admin/content/products/1') ?>"><?= icon('layout-4-line') ?> Visual Editor Canvas</a>
      <a class="btn btn-outline" href="<?= url('admin/content/products/catalogue') ?>"><?= icon('store-2-line') ?> Edit Catalogue Page</a>
    <?php endif ?>
    <?php if($type==='categories'): ?>
      <a class="btn btn-outline" href="<?= url('products') ?>" target="_blank" rel="noopener"><?= icon('arrow-right-up-line') ?> View Public Catalogue</a>
    <?php endif ?>
    <?php if(!in_array($type, ['pages', 'seo_meta'], true)): ?>
      <a class="btn btn-primary" href="<?= url('admin/content/'.$type.'/new') ?>"><?= icon('add-line') ?> Add <?= e(strtolower($config['singular'])) ?></a>
    <?php endif ?>
  </div>
</div>

<?php if($type==='seo_meta'): ?>
  <!-- SEO KPI Summary Bar -->
  <div class="admin-kpi-grid">
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#e0f2fe; color:#0284c7;"><?= icon('search-eye-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Core Website Pages</span>
        <strong class="admin-kpi-value"><?= count($rows) ?> Indexed Routes</strong>
      </div>
    </div>
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#ecfdf5; color:#059669;"><?= icon('shield-check-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Search Directives</span>
        <strong class="admin-kpi-value">Index, Follow Active</strong>
      </div>
    </div>
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#fef3c7; color:#d97706;"><?= icon('share-forward-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Social Sharing (OG)</span>
        <strong class="admin-kpi-value">Open Graph Ready</strong>
      </div>
    </div>
  </div>
<?php endif ?>

<?php if($type==='categories'): ?>
  <!-- Category KPI Summary Bar -->
  <div class="admin-kpi-grid">
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#e0f2fe; color:#0284c7;"><?= icon('apps-2-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Active Taxonomies</span>
        <strong class="admin-kpi-value"><?= count($rows) ?> Categories</strong>
      </div>
    </div>
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#ecfdf5; color:#059669;"><?= icon('archive-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Assigned Equipment</span>
        <strong class="admin-kpi-value"><?= array_sum(array_column($rows, 'product_count')) ?> Products Total</strong>
      </div>
    </div>
    <div class="admin-kpi-card">
      <div class="admin-kpi-icon" style="background:#fef3c7; color:#d97706;"><?= icon('navigation-line') ?></div>
      <div class="admin-kpi-info">
        <span class="admin-kpi-label">Website Navigation</span>
        <strong class="admin-kpi-value">Live in Mega-Menu</strong>
      </div>
    </div>
  </div>
<?php endif ?>

<div class="admin-panel">
  <div class="admin-table-toolbar">
    <div class="search-field">
      <?= icon('search-line') ?>
      <input type="search" class="admin-table-search" placeholder="Search <?= e(strtolower($config['label'])) ?>..." aria-label="Search content">
    </div>
    <div class="table-toolbar-meta">
      <span class="badge-total"><?= count($rows) ?> <?= strtolower($config['label']) ?> total</span>
    </div>
  </div>

  <div class="table-responsive">
    <table class="admin-table <?= $type === 'categories' ? 'admin-table--categories' : '' ?>">
      <thead>
        <tr>
          <?php if($type==='categories'): ?>
            <th style="width: 38%;">Category & Details</th>
            <th style="width: 18%;">Assigned Inventory</th>
            <th style="width: 14%;">Sort Order</th>
            <th style="width: 18%;">Public URL</th>
            <th style="width: 12%; text-align: right;">Actions</th>
          <?php elseif($type==='seo_meta'): ?>
            <th style="width: 30%;">Website Page & Route</th>
            <th style="width: 40%;">Search Snippet (Title & Meta Desc)</th>
            <th style="width: 18%;">Focus Keywords</th>
            <th style="width: 12%; text-align: right;">Actions</th>
          <?php else: ?>
            <th><?= e($config['singular']) ?></th>
            <th>Status</th>
            <th>URL / Details</th>
            <th style="text-align: right;">Actions</th>
          <?php endif ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach($rows as $row): ?>
          <tr>
            <?php if($type==='categories'): ?>
              <!-- Enhanced Category Profile Cell -->
              <td>
                <div class="cat-cell-profile">
                  <div class="cat-icon-badge">
                    <i class="ri-<?= e(preg_replace('/^ri-/', '', $row['icon'] ?: 'apps-2-line')) ?>"></i>
                  </div>
                  <div class="cat-cell-info">
                    <a class="cat-title-link" href="<?= url('admin/content/categories/'.$row['id']) ?>" title="Edit category">
                      <?= e($row['name']) ?>
                    </a>
                    <?php if(!empty($row['description'])): ?>
                      <span class="cat-cell-desc"><?= e($row['description']) ?></span>
                    <?php else: ?>
                      <span class="cat-cell-desc is-empty">No description set</span>
                    <?php endif ?>
                  </div>
                </div>
              </td>
              <!-- Assigned Inventory Count -->
              <td>
                <div class="cat-inventory-pill">
                  <?= icon('archive-line') ?>
                  <strong><?= (int)($row['product_count'] ?? 0) ?></strong>
                  <span><?= (int)($row['product_count'] ?? 0) === 1 ? 'Product' : 'Products' ?></span>
                </div>
              </td>
              <!-- Sort Order Sequence -->
              <td>
                <span class="cat-order-pill">
                  <span class="order-dot"></span>
                  Priority #<?= (int)($row['sort_order'] ?? 0) ?>
                </span>
              </td>
              <!-- Public URL Route -->
              <td>
                <a class="cat-route-link" href="<?= url('categories/'.$row['slug']) ?>" target="_blank" rel="noopener" title="Open public category page">
                  <span class="route-prefix">/categories/</span><strong class="route-slug"><?= e($row['slug']) ?></strong>
                  <?= icon('arrow-right-up-line') ?>
                </a>
              </td>
              <!-- Actions -->
              <td style="text-align: right;">
                <div class="table-actions">
                  <a href="<?= url('admin/content/categories/'.$row['id']) ?>" class="btn-action-pill" aria-label="Edit <?= e($row['name']) ?>" title="Edit category">
                    <?= icon('edit-line') ?>
                  </a>
                  <a href="<?= url('categories/'.$row['slug']) ?>" target="_blank" rel="noopener" class="btn-action-pill" aria-label="View <?= e($row['name']) ?>" title="View on website">
                    <?= icon('arrow-right-up-line') ?>
                  </a>
                  <form class="confirm-delete" method="post" action="<?= url('admin/content/categories/'.$row['id']) ?>">
                    <?= csrfField() ?>
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="btn-action-pill btn-action-pill--delete" aria-label="Delete <?= e($row['name']) ?>" title="Delete category">
                      <?= icon('delete-bin-line') ?>
                    </button>
                  </form>
                </div>
              </td>
            <?php elseif($type==='seo_meta'): ?>
              <?php 
                $corePages = Seo::corePages();
                $pageMeta = $corePages[$row['page_key']] ?? [
                  'name' => trim($row['page_key'], '/') ?: 'Home',
                  'icon' => 'file-text-line',
                  'desc' => 'Website route',
                  'url' => url(ltrim($row['page_key'], '/'))
                ];
              ?>
              <td>
                <div class="cat-cell-profile">
                  <div class="cat-icon-badge" style="background:#e0f2fe; color:#0284c7;">
                    <i class="ri-<?= e($pageMeta['icon']) ?>"></i>
                  </div>
                  <div class="cat-cell-info">
                    <a class="cat-title-link" href="<?= url('admin/content/seo_meta/'.$row['id']) ?>" title="Edit SEO for <?= e($pageMeta['name']) ?>">
                      <?= e($pageMeta['name']) ?>
                    </a>
                    <span class="cat-cell-desc" style="font-family:monospace; color:#0284c7; font-weight:600;"><?= e($row['page_key']) ?></span>
                  </div>
                </div>
              </td>
              <td>
                <div style="display:flex; flex-direction:column; gap:4px;">
                  <strong style="color:#1b3d6f; font-size:13px; line-height:1.4;"><?= e($row['meta_title'] ?: 'Default site title') ?></strong>
                  <span style="color:#64748b; font-size:11px; line-height:1.5;"><?= e(mb_strimwidth($row['meta_desc'] ?: 'No custom meta description set.', 0, 130, '...')) ?></span>
                </div>
              </td>
              <td>
                <?php if(!empty($row['focus_keyword'])): ?>
                  <div style="display:flex; flex-wrap:wrap; gap:4px;">
                    <?php foreach(array_slice(explode(',', $row['focus_keyword']), 0, 2) as $kw): ?>
                      <span style="font-size:10px; background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:4px; font-weight:600;"><?= e(trim($kw)) ?></span>
                    <?php endforeach ?>
                  </div>
                <?php else: ?>
                  <span style="color:#94a3b8; font-size:11px;">—</span>
                <?php endif ?>
              </td>
              <td style="text-align: right;">
                <div class="table-actions">
                  <a href="<?= url('admin/content/seo_meta/'.$row['id']) ?>" class="btn-action-pill" aria-label="Edit SEO for <?= e($pageMeta['name']) ?>" title="Edit SEO">
                    <?= icon('edit-line') ?>
                  </a>
                  <a href="<?= e($pageMeta['url']) ?>" target="_blank" rel="noopener" class="btn-action-pill" aria-label="View <?= e($pageMeta['name']) ?> on website" title="View live page">
                    <?= icon('arrow-right-up-line') ?>
                  </a>
                </div>
              </td>
            <?php else: ?>
              <!-- Standard Resource Row -->
              <td>
                <a class="table-title" href="<?= url('admin/content/'.$type.'/'.$row['id']) ?>">
                  <?php $thumb = $row['thumbnail'] ?? ($row['avatar'] ?? ($row['logo_path'] ?? '')); ?>
                  <?php if(!empty($thumb)): ?>
                    <img src="<?= photo($thumb) ?>" alt="" loading="lazy" width="44" height="44" style="object-fit:cover; border-radius:<?= $type==='testimonials'?'50%':'6px' ?>; flex-shrink:0;">
                  <?php elseif($type==='testimonials'): ?>
                    <span style="display:inline-grid; place-items:center; width:44px; height:44px; border-radius:50%; background:#e0f2fe; color:#0284c7; font-weight:700; font-size:15px; flex-shrink:0;"><?= e(mb_strtoupper(mb_substr(trim((string)$row['client_name']),0,1))) ?></span>
                  <?php endif ?>
                  <span><?= e($row[$config['title']]) ?></span>
                </a>
              </td>
              <td>
                <?php $status=$row['status']??(isset($row['is_active'])?($row['is_active']?'active':'hidden'):'published'); ?>
                <span class="status-badge <?= in_array($status,['draft','hidden'])?'status-badge--muted':'' ?>">
                  <?= e(ucfirst($status)) ?>
                </span>
                <?php if(!empty($row['is_featured'])): ?>
                  <span class="featured-dot" title="Featured on homepage"><?= icon('star-fill') ?></span>
                <?php endif ?>
              </td>
              <td>
                <small><?= e($row['slug']??$row['organization']??$row['robots']??'—') ?></small>
              </td>
              <td style="text-align: right;">
                <div class="table-actions">
                  <a href="<?= url('admin/content/'.$type.'/'.$row['id']) ?>" class="btn-action-pill" aria-label="Edit <?= e($row[$config['title']]) ?>" title="Edit">
                    <?= icon('edit-line') ?>
                  </a>
                  <?php if($config['route']&&($row['status']??'published')==='published'): ?>
                    <a href="<?= url($config['route'].'/'.$row['slug']) ?>" target="_blank" rel="noopener" class="btn-action-pill" aria-label="View <?= e($row[$config['title']]) ?>" title="View on website">
                      <?= icon('arrow-right-up-line') ?>
                    </a>
                  <?php endif ?>
                  <?php if(!in_array($type, ['pages', 'seo_meta'], true)): ?>
                    <form class="confirm-delete" method="post" action="<?= url('admin/content/'.$type.'/'.$row['id']) ?>">
                      <?= csrfField() ?>
                      <input type="hidden" name="action" value="delete">
                      <button type="submit" class="btn-action-pill btn-action-pill--delete" aria-label="Delete <?= e($row[$config['title']]) ?>" title="Delete">
                        <?= icon('delete-bin-line') ?>
                      </button>
                    </form>
                  <?php endif ?>
                </div>
              </td>
            <?php endif ?>
          </tr>
        <?php endforeach ?>
      </tbody>
    </table>
  </div>

  <?php if(!$rows): ?>
    <div class="admin-empty">
      <?= icon('file-add-line') ?>
      <h2>Your next story starts here.</h2>
      <p>Add your first <?= e(strtolower($config['singular'])) ?> to get started.</p>
    </div>
  <?php endif ?>

  <div class="admin-search-empty" hidden>No matching items. Try another search.</div>
</div>
