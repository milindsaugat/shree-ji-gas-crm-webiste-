<?php
$from = $filteredCount > 0 ? (($page - 1) * $perPage) + 1 : 0;
$to = min($filteredCount, $page * $perPage);
$baseQueryParams = [];
if ($statusFilter) $baseQueryParams['status'] = $statusFilter;
if ($searchQuery) $baseQueryParams['q'] = $searchQuery;
function buildPageUrl(int $p, array $params): string {
    $params['page'] = $p;
    return url('admin/enquiries') . '?' . http_build_query($params);
}
?>

<div class="admin-page-heading">
  <div>
    <span class="eyebrow">COMMUNICATIONS &amp; LEADS</span>
    <h1>Customer Enquiry Inbox</h1>
    <p>
      Showing <?= $from ?>–<?= $to ?> of <?= $filteredCount ?> enquiries
      <?php if($statusFilter || $searchQuery): ?>
        (filtered from <?= $totalCount ?> total)
      <?php endif ?>
      &middot; <strong style="color:#b45309;"><?= $newCount ?> unread leads</strong>
    </p>
  </div>
  <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
    <a href="<?= url('admin/enquiries') ?>" class="btn btn-outline" title="Refresh inbox">
      <?= icon('refresh-line') ?> Refresh
    </a>
    <form method="post" style="margin:0;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="export">
      <button type="submit" class="btn btn-outline" title="Export all enquiries in CSV format">
        Export CSV <?= icon('download-line') ?>
      </button>
    </form>
  </div>
</div>

<?php if(!MAIL_ENABLED): ?>
  <div class="admin-notice">
    <?= icon('information-line') ?>
    <div>
      <strong>Enquiry email alerts are in local log mode.</strong>
      <p>Customer submissions are saved here in the database. Outbound email dispatch requires configured SMTP credentials under Site Settings.</p>
    </div>
  </div>
<?php endif ?>

<!-- Compact Metric Summary Bar -->
<div class="admin-kpi-grid">
  <a href="<?= url('admin/enquiries') ?>" class="admin-kpi-card <?= empty($statusFilter) ? 'is-active' : '' ?>">
    <div>
      <span class="admin-kpi-label">All Enquiries</span>
      <strong class="admin-kpi-value"><?= $totalCount ?></strong>
    </div>
    <div class="admin-kpi-icon" style="background:#eef6f9; color:#1b3d6f;">
      <?= icon('inbox-2-line') ?>
    </div>
  </a>

  <a href="<?= url('admin/enquiries?status=new') ?>" class="admin-kpi-card <?= $statusFilter === 'new' ? 'is-active' : '' ?>">
    <div>
      <span class="admin-kpi-label">New Leads</span>
      <strong class="admin-kpi-value" style="color:#b45309;"><?= $newCount ?></strong>
    </div>
    <div class="admin-kpi-icon" style="background:#fef3c7; color:#b45309;">
      <?= icon('notification-3-line') ?>
    </div>
  </a>

  <a href="<?= url('admin/enquiries?status=read') ?>" class="admin-kpi-card <?= $statusFilter === 'read' ? 'is-active' : '' ?>">
    <div>
      <span class="admin-kpi-label">Under Review</span>
      <strong class="admin-kpi-value" style="color:#0369a1;"><?= $readCount ?></strong>
    </div>
    <div class="admin-kpi-icon" style="background:#e0f2fe; color:#0369a1;">
      <?= icon('draft-line') ?>
    </div>
  </a>

  <a href="<?= url('admin/enquiries?status=replied') ?>" class="admin-kpi-card <?= $statusFilter === 'replied' ? 'is-active' : '' ?>">
    <div>
      <span class="admin-kpi-label">Replied / Closed</span>
      <strong class="admin-kpi-value" style="color:#166534;"><?= $repliedCount ?></strong>
    </div>
    <div class="admin-kpi-icon" style="background:#dcfce7; color:#166534;">
      <?= icon('check-double-line') ?>
    </div>
  </a>
</div>

<!-- Search & Status Filter Toolbar -->
<form method="get" action="<?= url('admin/enquiries') ?>" class="enquiry-toolbar-row">
  <div class="enquiry-toolbar-left">
    <div class="search-field" style="max-width:380px; width:100%;">
      <?= icon('search-line') ?>
      <input type="search" name="q" value="<?= e($searchQuery) ?>" placeholder="Search by name, phone, email, equipment..." aria-label="Search enquiries">
    </div>
    <?php if($searchQuery || $statusFilter): ?>
      <a href="<?= url('admin/enquiries') ?>" class="btn btn-outline" style="padding:10px 14px; font-size:11px;">
        Clear Filters
      </a>
    <?php endif ?>
  </div>

  <div class="enquiry-toolbar-right">
    <label for="enquiryStatusFilter" style="margin:0; font-size:11px; font-weight:700; text-transform:uppercase; color:#5a7a8a;">Filter Status:</label>
    <select id="enquiryStatusFilter" name="status" onchange="this.form.submit()" style="font-size:12px; padding:8px 12px; border:1px solid #cadde4; border-radius:3px; background:#ffffff; color:#1a2d44;">
      <option value="">All statuses (<?= $totalCount ?>)</option>
      <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>New leads (<?= $newCount ?>)</option>
      <option value="read" <?= $statusFilter === 'read' ? 'selected' : '' ?>>Under review (<?= $readCount ?>)</option>
      <option value="replied" <?= $statusFilter === 'replied' ? 'selected' : '' ?>>Replied (<?= $repliedCount ?>)</option>
    </select>
  </div>
</form>

<!-- Bulk Action Toolbar (Appears on item selection) -->
<div id="bulkLedgerBar" class="bulk-ledger-bar" style="display: none;">
  <div class="bulk-ledger-left">
    <?= icon('checkbox-circle-fill') ?>
    <span class="bulk-badge" id="selectedCountBadge">0 selected</span>
    <span>Select an action for marked enquiries:</span>
  </div>
  <div class="bulk-ledger-actions">
    <!-- Bulk Delete -->
    <form method="post" id="bulkDeleteForm" style="margin:0;" onsubmit="return confirmBulkDelete();">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="bulk_delete">
      <div id="bulkDeleteInputsContainer"></div>
      <button type="submit" class="btn-bulk-danger">
        <?= icon('delete-bin-line') ?> Delete Selected
      </button>
    </form>

    <!-- Bulk Mark as Read -->
    <form method="post" id="bulkMarkReadForm" style="margin:0;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="bulk_status">
      <input type="hidden" name="status" value="read">
      <div id="bulkMarkReadInputsContainer"></div>
      <button type="submit" class="btn-bulk-secondary">
        <?= icon('mail-open-line') ?> Mark Read
      </button>
    </form>

    <!-- Bulk Mark as Replied -->
    <form method="post" id="bulkMarkRepliedForm" style="margin:0;">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="bulk_status">
      <input type="hidden" name="status" value="replied">
      <div id="bulkMarkRepliedInputsContainer"></div>
      <button type="submit" class="btn-bulk-secondary">
        <?= icon('check-double-line') ?> Mark Replied
      </button>
    </form>

    <button type="button" id="clearSelectionBtn" style="border:0; background:none; color:#c7dce6; font-size:11px; cursor:pointer; text-decoration:underline;">
      Deselect all
    </button>
  </div>
</div>

<!-- Structured Enquiries Ledger Table -->
<div class="enquiries-table-wrap">
  <table class="enquiry-table admin-table" id="enquiryLedgerTable">
    <thead>
      <tr>
        <th style="width: 38px; text-align: center;">
          <input type="checkbox" id="selectAllEnquiries" style="cursor:pointer;" aria-label="Select all enquiries">
        </th>
        <th style="width: 60px;">Ref</th>
        <th style="width: 24%;">Customer &amp; Contact</th>
        <th style="width: 20%;">Equipment Requirement</th>
        <th>Message Details</th>
        <th style="width: 110px;">Status</th>
        <th style="width: 140px;">Received Date</th>
        <th style="width: 130px; text-align: right;">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if(!$rows): ?>
        <tr>
          <td colspan="8" style="text-align: center; padding: 48px 20px; color: #5a7a8a;">
            <?= icon('inbox-2-line') ?>
            <h3 style="font-size: 16px; margin: 10px 0 4px; color: #1b3d6f;">No enquiries found</h3>
            <p style="margin: 0; font-size: 12px;">There are no enquiries matching the current filter or search criteria.</p>
          </td>
        </tr>
      <?php else: ?>
        <?php foreach($rows as $row): ?>
          <?php
            $cleanPhone = preg_replace('/[^+0-9]/', '', (string)$row['phone']);
            $msgExcerpt = mb_strimwidth(strip_tags((string)$row['message']), 0, 110, '…');
          ?>
          <tr class="enquiry-row enquiry-item" id="enquiry-row-<?= $row['id'] ?>" data-id="<?= $row['id'] ?>">
            <!-- Checkbox -->
            <td style="text-align: center; vertical-align: middle;">
              <input type="checkbox" class="enquiry-item-checkbox" value="<?= $row['id'] ?>" aria-label="Select enquiry #<?= $row['id'] ?>">
            </td>

            <!-- Reference ID -->
            <td style="vertical-align: middle;">
              <span class="enquiry-id-tag">#<?= $row['id'] ?></span>
            </td>

            <!-- Customer & Contact -->
            <td>
              <span class="enquiry-customer-name"><?= e($row['name']) ?></span>
              <div class="enquiry-contact-links">
                <?php if($row['phone']): ?>
                  <a href="tel:<?= e($cleanPhone) ?>" class="contact-link-pill" title="Call <?= e($row['phone']) ?>">
                    <?= icon('phone-line') ?> <?= e($row['phone']) ?>
                  </a>
                  <?php if($cleanPhone): ?>
                    <a href="https://wa.me/<?= e(ltrim($cleanPhone, '+')) ?>" target="_blank" rel="noopener" class="contact-link-pill contact-wa-link" title="Open WhatsApp chat">
                      <?= icon('whatsapp-line') ?>
                    </a>
                  <?php endif ?>
                <?php endif ?>
                <?php if(!empty($row['email'])): ?>
                  <a href="mailto:<?= e($row['email']) ?>" class="contact-link-pill" title="Email <?= e($row['email']) ?>">
                    <?= icon('mail-line') ?> <?= e($row['email']) ?>
                  </a>
                <?php endif ?>
              </div>
            </td>

            <!-- Equipment Requirement -->
            <td>
              <span class="enquiry-product-badge">
                <?= icon('archive-line') ?>
                <?= e($row['product'] ?: 'General enquiry') ?>
              </span>
            </td>

            <!-- Message Excerpt -->
            <td>
              <div class="enquiry-msg-snippet" style="cursor:pointer;" onclick='openEnquiryModal(<?= htmlspecialchars(json_encode([
                'id' => (int)$row['id'],
                'name' => (string)$row['name'],
                'phone' => (string)$row['phone'],
                'email' => (string)$row['email'],
                'product' => (string)($row['product'] ?: 'General enquiry'),
                'message' => (string)$row['message'],
                'status' => (string)$row['status'],
                'created_at' => date('d M Y, h:i A', strtotime($row['created_at']))
              ]), ENT_QUOTES, 'UTF-8') ?>)' title="Click to view full message">
                <?= nl2br(e($row['message'])) ?>
              </div>
              <button type="button" class="enquiry-msg-btn-more" onclick='openEnquiryModal(<?= htmlspecialchars(json_encode([
                'id' => (int)$row['id'],
                'name' => (string)$row['name'],
                'phone' => (string)$row['phone'],
                'email' => (string)$row['email'],
                'product' => (string)($row['product'] ?: 'General enquiry'),
                'message' => (string)$row['message'],
                'status' => (string)$row['status'],
                'created_at' => date('d M Y, h:i A', strtotime($row['created_at']))
              ]), ENT_QUOTES, 'UTF-8') ?>)'>
                <?= icon('eye-line') ?> View full message &raquo;
              </button>
            </td>

            <!-- Status Badge -->
            <td style="vertical-align: middle;">
              <span class="status-pill status-pill--<?= e($row['status']) ?>" id="status-pill-<?= $row['id'] ?>">
                <?= e(ucfirst($row['status'])) ?>
              </span>
            </td>

            <!-- Received Date -->
            <td style="vertical-align: middle; white-space: nowrap; color: #5a7a8a; font-size: 11px;">
              <?= date('d M Y, H:i', strtotime($row['created_at'])) ?>
            </td>

            <!-- Row Actions -->
            <td style="vertical-align: middle; text-align: right;">
              <div class="enquiry-table-actions">
                <!-- Eye Button to inspect full details & message -->
                <button type="button" class="btn-table-view" title="View & inspect complete enquiry" onclick='openEnquiryModal(<?= htmlspecialchars(json_encode([
                  'id' => (int)$row['id'],
                  'name' => (string)$row['name'],
                  'phone' => (string)$row['phone'],
                  'email' => (string)$row['email'],
                  'product' => (string)($row['product'] ?: 'General enquiry'),
                  'message' => (string)$row['message'],
                  'status' => (string)$row['status'],
                  'created_at' => date('d M Y, h:i A', strtotime($row['created_at']))
                ]), ENT_QUOTES, 'UTF-8') ?>)'>
                  <?= icon('eye-line') ?>
                </button>

                <!-- Status Changer Form -->
                <form method="post" class="enquiry-quick-status-form" id="quick-form-<?= $row['id'] ?>">
                  <?= csrfField() ?>
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <select name="status" id="status-select-<?= $row['id'] ?>" onchange="handleRowStatusChange(<?= $row['id'] ?>, this.value)" title="Change enquiry status">
                    <option value="new" <?= $row['status'] === 'new' ? 'selected' : '' ?>>New</option>
                    <option value="read" <?= $row['status'] === 'read' ? 'selected' : '' ?>>Read</option>
                    <option value="replied" <?= $row['status'] === 'replied' ? 'selected' : '' ?>>Replied</option>
                  </select>
                </form>

                <!-- Single Delete Form -->
                <form method="post" style="margin:0;" onsubmit="return confirm('Are you sure you want to delete enquiry #<?= $row['id'] ?> from <?= e(addslashes($row['name'])) ?>?');">
                  <?= csrfField() ?>
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= $row['id'] ?>">
                  <button type="submit" class="btn-table-delete" title="Delete enquiry">
                    <?= icon('delete-bin-line') ?>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        <?php endforeach ?>
      <?php endif ?>
    </tbody>
  </table>
</div>

<!-- Pagination Navigation Bar -->
<?php if($totalPages > 1): ?>
  <div class="admin-pagination-strip">
    <div class="pagination-summary">
      Showing <?= $from ?> to <?= $to ?> of <?= $filteredCount ?> enquiries
    </div>

    <div class="pagination-links">
      <!-- Previous Page -->
      <a href="<?= $page > 1 ? buildPageUrl($page - 1, $baseQueryParams) : '#' ?>" 
         class="pagination-link <?= $page <= 1 ? 'is-disabled' : '' ?>" 
         aria-label="Previous page">
        <?= icon('arrow-left-s-line') ?>
      </a>

      <!-- Page Numbers -->
      <?php
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $page + 2);
        if ($startPage > 1) {
          echo '<a href="'.buildPageUrl(1, $baseQueryParams).'" class="pagination-link">1</a>';
          if ($startPage > 2) echo '<span style="color:#8ca2b6; padding:0 4px;">…</span>';
        }
        for ($p = $startPage; $p <= $endPage; $p++) {
          $isActive = ($p === $page) ? 'is-active' : '';
          echo '<a href="'.buildPageUrl($p, $baseQueryParams).'" class="pagination-link '.$isActive.'">'.$p.'</a>';
        }
        if ($endPage < $totalPages) {
          if ($endPage < $totalPages - 1) echo '<span style="color:#8ca2b6; padding:0 4px;">…</span>';
          echo '<a href="'.buildPageUrl($totalPages, $baseQueryParams).'" class="pagination-link">'.$totalPages.'</a>';
        }
      ?>

      <!-- Next Page -->
      <a href="<?= $page < $totalPages ? buildPageUrl($page + 1, $baseQueryParams) : '#' ?>" 
         class="pagination-link <?= $page >= $totalPages ? 'is-disabled' : '' ?>" 
         aria-label="Next page">
        <?= icon('arrow-right-s-line') ?>
      </a>
    </div>
  </div>
<?php endif ?>

<!-- Executive Enquiry Detail & Message Modal -->
<dialog id="enquiryModal" class="enquiry-detail-dialog">
  <div class="enquiry-modal-header">
    <div style="display:flex; align-items:center; gap:10px;">
      <span class="enquiry-id-tag" id="modalRefTag" style="font-size:14px; background:#eef6f9; padding:3px 8px; border-radius:3px;">#--</span>
      <div>
        <h2 id="modalLeadTitle" style="font-size:16px; margin:0; color:#1b3d6f; font-weight:700;">Customer Enquiry</h2>
        <span id="modalLeadDate" style="font-size:11px; color:#5a7a8a;">Received Date</span>
      </div>
    </div>
    <div style="display:flex; align-items:center; gap:8px;">
      <span id="modalStatusBadge" class="status-pill status-pill--new">New</span>
      <button type="button" class="btn-dialog-close" onclick="document.getElementById('enquiryModal').close()" aria-label="Close modal">
        <?= icon('close-line') ?>
      </button>
    </div>
  </div>

  <!-- Contact Details Grid -->
  <div class="enquiry-modal-grid">
    <div class="enquiry-modal-card">
      <small>Customer Contact</small>
      <strong id="modalCustomerName" style="font-size:14px; color:#1b3d6f; display:block; margin:2px 0 4px;">—</strong>
      <div style="display:flex; flex-direction:column; gap:4px; margin-top:6px;">
        <a id="modalPhoneLink" href="#" class="modal-contact-link">
          <?= icon('phone-line') ?> <span id="modalPhoneText">—</span>
        </a>
        <a id="modalWaLink" href="#" target="_blank" rel="noopener" class="modal-contact-link modal-wa-link">
          <?= icon('whatsapp-line') ?> <span>Chat on WhatsApp</span>
        </a>
        <a id="modalEmailLink" href="#" class="modal-contact-link">
          <?= icon('mail-line') ?> <span id="modalEmailText">—</span>
        </a>
      </div>
    </div>

    <div class="enquiry-modal-card">
      <small>Equipment Requirement</small>
      <div style="margin-top:6px;">
        <span class="enquiry-product-badge" id="modalProductBadge" style="font-size:12px; padding:6px 10px;">
          <?= icon('archive-line') ?> <span id="modalProductText">—</span>
        </span>
      </div>
      <div style="margin-top:14px;" id="modalAutoReadNotice">
        <span class="modal-read-indicator" id="modalReadStatusNotice">
          <?= icon('check-line') ?> Marked as read
        </span>
      </div>
    </div>
  </div>

  <!-- Full Message Content Section -->
  <div class="enquiry-modal-msg-wrap">
    <div class="enquiry-modal-msg-head">
      <span><?= icon('message-3-line') ?> Full Enquiry Message</span>
      <button type="button" class="btn-copy-msg" onclick="copyModalMessage()">
        <?= icon('file-copy-line') ?> Copy Text
      </button>
    </div>
    <div class="enquiry-modal-msg-body" id="modalLeadBody"></div>
  </div>

  <!-- Modal Action Footer -->
  <div class="enquiry-modal-footer">
    <div class="modal-status-actions">
      <span style="font-size:11px; font-weight:700; text-transform:uppercase; color:#5a7a8a; margin-right:4px;">Quick Status:</span>
      <button type="button" class="btn-modal-status btn-status-read" onclick="setModalStatus('read')">
        <?= icon('mail-open-line') ?> Mark as Read
      </button>
      <button type="button" class="btn-modal-status btn-status-replied" onclick="setModalStatus('replied')">
        <?= icon('check-double-line') ?> Mark as Replied
      </button>
      <button type="button" class="btn-modal-status btn-status-new" onclick="setModalStatus('new')">
        <?= icon('refresh-line') ?> Mark as New
      </button>
    </div>
    <button type="button" class="btn btn-outline" onclick="document.getElementById('enquiryModal').close()" style="padding:6px 14px; font-size:12px;">
      Close
    </button>
  </div>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const selectAllCheckbox = document.getElementById('selectAllEnquiries');
  const itemCheckboxes = document.querySelectorAll('.enquiry-item-checkbox');
  const bulkBar = document.getElementById('bulkLedgerBar');
  const countBadge = document.getElementById('selectedCountBadge');
  const clearSelectionBtn = document.getElementById('clearSelectionBtn');
  const csrfToken = document.querySelector('input[name="csrf"]')?.value || '';

  let currentModalData = null;

  const updateBulkBar = () => {
    const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);
    const count = checked.length;
    if (countBadge) countBadge.textContent = count + ' selected';

    if (count > 0) {
      bulkBar.style.display = 'flex';
      const ids = checked.map(cb => cb.value);
      ['bulkDeleteInputsContainer', 'bulkMarkReadInputsContainer', 'bulkMarkRepliedInputsContainer'].forEach(cId => {
        const container = document.getElementById(cId);
        if (container) {
          container.innerHTML = ids.map(id => `<input type="hidden" name="ids[]" value="${id}">`).join('');
        }
      });
    } else {
      bulkBar.style.display = 'none';
    }

    itemCheckboxes.forEach(cb => {
      const row = cb.closest('tr');
      if (row) row.classList.toggle('is-selected', cb.checked);
    });

    if (selectAllCheckbox) {
      const allChecked = itemCheckboxes.length > 0 && Array.from(itemCheckboxes).every(cb => cb.checked);
      const someChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
      selectAllCheckbox.checked = allChecked;
      selectAllCheckbox.indeterminate = !allChecked && someChecked;
    }
  };

  selectAllCheckbox?.addEventListener('change', () => {
    const isChecked = selectAllCheckbox.checked;
    itemCheckboxes.forEach(cb => cb.checked = isChecked);
    updateBulkBar();
  });

  itemCheckboxes.forEach(cb => {
    cb.addEventListener('change', updateBulkBar);
  });

  clearSelectionBtn?.addEventListener('click', () => {
    itemCheckboxes.forEach(cb => cb.checked = false);
    updateBulkBar();
  });

  window.confirmBulkDelete = () => {
    const checked = Array.from(itemCheckboxes).filter(cb => cb.checked);
    if (checked.length === 0) {
      alert('Please select at least one enquiry to delete.');
      return false;
    }
    return confirm('Are you sure you want to delete ' + checked.length + ' selected enquiries? This cannot be undone.');
  };

  // Update status via AJAX and sync UI
  window.updateEnquiryStatus = async (id, status) => {
    try {
      const formData = new FormData();
      formData.append('id', id);
      formData.append('status', status);
      formData.append('csrf', csrfToken);

      const response = await fetch('<?= url("admin/enquiries") ?>', {
        method: 'POST',
        headers: {
          'Accept': 'application/json'
        },
        body: formData
      });

      if (response.ok) {
        const res = await response.json();
        // Update table row status pill
        const pill = document.getElementById('status-pill-' + id);
        if (pill) {
          pill.className = 'status-pill status-pill--' + status;
          pill.textContent = status.charAt(0).toUpperCase() + status.slice(1);
        }
        // Update table row select dropdown
        const sel = document.getElementById('status-select-' + id);
        if (sel) sel.value = status;

        // Update modal status badge if open
        const modalBadge = document.getElementById('modalStatusBadge');
        if (modalBadge && currentModalData && currentModalData.id === id) {
          modalBadge.className = 'status-pill status-pill--' + status;
          modalBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
          currentModalData.status = status;
        }

        // Show notice
        const notice = document.getElementById('modalReadStatusNotice');
        if (notice) {
          notice.style.display = 'inline-flex';
          notice.innerHTML = '<i class="ri-check-line"></i> Status: ' + status.toUpperCase();
        }
      }
    } catch (e) {
      console.warn('Status update sync error:', e);
    }
  };

  window.handleRowStatusChange = (id, status) => {
    updateEnquiryStatus(id, status);
  };

  window.setModalStatus = (status) => {
    if (!currentModalData) return;
    updateEnquiryStatus(currentModalData.id, status);
  };

  window.copyModalMessage = () => {
    if (!currentModalData) return;
    navigator.clipboard?.writeText(currentModalData.message).then(() => {
      alert('Message copied to clipboard.');
    });
  };

  window.openEnquiryModal = (data) => {
    currentModalData = data;
    const dialog = document.getElementById('enquiryModal');
    if (!dialog) return;

    document.getElementById('modalRefTag').textContent = '#' + data.id;
    document.getElementById('modalLeadTitle').textContent = data.name;
    document.getElementById('modalLeadDate').textContent = 'Received: ' + data.created_at;

    const modalBadge = document.getElementById('modalStatusBadge');
    modalBadge.className = 'status-pill status-pill--' + data.status;
    modalBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);

    document.getElementById('modalCustomerName').textContent = data.name;

    const cleanPhone = (data.phone || '').replace(/[^+0-9]/g, '');
    const phoneLink = document.getElementById('modalPhoneLink');
    const phoneText = document.getElementById('modalPhoneText');
    if (data.phone) {
      phoneLink.href = 'tel:' + cleanPhone;
      phoneText.textContent = data.phone;
      phoneLink.style.display = 'inline-flex';
    } else {
      phoneLink.style.display = 'none';
    }

    const waLink = document.getElementById('modalWaLink');
    if (cleanPhone) {
      waLink.href = 'https://wa.me/' + cleanPhone.replace(/^\+/, '');
      waLink.style.display = 'inline-flex';
    } else {
      waLink.style.display = 'none';
    }

    const emailLink = document.getElementById('modalEmailLink');
    const emailText = document.getElementById('modalEmailText');
    if (data.email) {
      emailLink.href = 'mailto:' + data.email;
      emailText.textContent = data.email;
      emailLink.style.display = 'inline-flex';
    } else {
      emailLink.style.display = 'none';
    }

    document.getElementById('modalProductText').textContent = data.product || 'General enquiry';
    document.getElementById('modalLeadBody').textContent = data.message;

    const notice = document.getElementById('modalReadStatusNotice');
    // If the enquiry was currently 'new', auto-mark as read on open!
    if (data.status === 'new') {
      updateEnquiryStatus(data.id, 'read');
      if (notice) {
        notice.style.display = 'inline-flex';
        notice.innerHTML = '<i class="ri-check-line"></i> Auto-marked as read';
      }
    } else {
      if (notice) {
        notice.style.display = 'inline-flex';
        notice.innerHTML = '<i class="ri-check-line"></i> Status: ' + data.status.toUpperCase();
      }
    }

    dialog.showModal();
  };
});
</script>
