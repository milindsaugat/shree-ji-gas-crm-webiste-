<?php
$user = Auth::user();
?>
<div class="admin-page-heading settings-page-heading">
  <div>
    <span class="eyebrow">SYSTEM &amp; SECURITY CONFIGURATION</span>
    <h1>Site Settings &amp; Security</h1>
    <p>Manage company details, branding assets, SMTP email gateway, search defaults and administrator credentials.</p>
  </div>
  <div class="settings-header-badge">
    <span class="status-indicator-dot"></span>
    <span>Production Admin Workspace · <?= e($user['name'] ?? 'Admin') ?></span>
  </div>
</div>

<?php if($error): ?>
  <div class="admin-alert admin-alert--error" role="alert">
    <?= icon('error-warning-line') ?>
    <span><?= e($error) ?></span>
  </div>
<?php endif ?>

<!-- Settings Tab Navigation -->
<div class="settings-tabs-nav-wrap">
  <nav class="settings-tabs-nav" id="settingsTabNav" role="tablist" aria-label="Settings navigation">
    <button type="button" class="settings-tab-btn is-active" role="tab" aria-selected="true" data-tab="tab-general" id="tabBtnGeneral">
      <?= icon('building-line') ?> <span>General Info</span>
    </button>
    <button type="button" class="settings-tab-btn" role="tab" aria-selected="false" data-tab="tab-branding" id="tabBtnBranding">
      <?= icon('palette-line') ?> <span>Branding &amp; Assets</span>
    </button>
    <button type="button" class="settings-tab-btn" role="tab" aria-selected="false" data-tab="tab-smtp" id="tabBtnSmtp">
      <?= icon('mail-send-line') ?> <span>SMTP Email Gateway</span>
      <span class="tab-pill <?= setting('smtp_enabled')==='1'?'tab-pill--active':'tab-pill--inactive' ?>">
        <?= setting('smtp_enabled')==='1'?'Active':'Disabled' ?>
      </span>
    </button>
    <button type="button" class="settings-tab-btn" role="tab" aria-selected="false" data-tab="tab-security" id="tabBtnSecurity">
      <?= icon('shield-keyhole-line') ?> <span>Account &amp; Security</span>
    </button>
    <button type="button" class="settings-tab-btn" role="tab" aria-selected="false" data-tab="tab-seo" id="tabBtnSeo">
      <?= icon('search-eye-line') ?> <span>Default SEO &amp; Social</span>
    </button>
  </nav>
</div>

<!-- Main Settings Form for General, Branding, SMTP & SEO -->
<form method="post" action="<?= url('admin/settings') ?>" class="settings-main-form" id="settingsForm">
  <?= csrfField() ?>

  <!-- TAB 1: GENERAL INFORMATION -->
  <section class="settings-tab-pane is-active" id="tab-general" role="tabpanel" aria-labelledby="tabBtnGeneral">
    <div class="admin-panel settings-card">
      <div class="settings-card__header">
        <div class="settings-card__icon"><?= icon('building-2-line') ?></div>
        <div>
          <h2>Essential Contact &amp; Operating Information</h2>
          <p>These primary contact channels and business details appear in your website header, footer, contact modal and enquiries.</p>
        </div>
      </div>

      <div class="settings-grid-2">
        <div class="admin-field">
          <label for="settingPhone">Customer Care / Support Phone <span>*</span></label>
          <input type="text" id="settingPhone" name="phone" value="<?= e(setting('phone')) ?>" required placeholder="+91 94122 56068" autocomplete="tel">
          <small class="field-hint">Primary direct voice contact for hospital emergency response and equipment queries.</small>
        </div>

        <div class="admin-field">
          <label for="settingEmail">Official Public Email <span>*</span></label>
          <input type="email" id="settingEmail" name="email" value="<?= e(setting('email', 'shreejigasteam@gmail.com')) ?>" required placeholder="shreejigasteam@gmail.com" autocomplete="email">
          <small class="field-hint">Public inquiry address displayed on the website and where customer enquiries are routed.</small>
        </div>

        <div class="admin-field">
          <label for="settingWhatsapp">WhatsApp Direct Number <span>*</span></label>
          <input type="text" id="settingWhatsapp" name="whatsapp" value="<?= e(setting('whatsapp')) ?>" required placeholder="+91 94122 56068">
          <small class="field-hint">Used for the floating WhatsApp button and instant quotation chats (country code included).</small>
        </div>

        <div class="admin-field">
          <label for="settingOperatingHours">Operating &amp; Delivery Hours</label>
          <input type="text" id="settingOperatingHours" name="operating_hours" value="<?= e(setting('operating_hours')) ?>" placeholder="Open 24/7 for Emergency Medical Oxygen · General Supply: Mon–Sat 9AM–8PM">
          <small class="field-hint">Shown in contact sections and footer trust badges.</small>
        </div>
      </div>

      <div class="admin-field admin-field--wide">
        <label for="settingAddress">Physical Factory / Administrative Address <span>*</span></label>
        <textarea id="settingAddress" name="address" rows="2" required placeholder="33/103, Near District Hospital, Pratappura, Agra, Uttar Pradesh — 282001"><?= e(setting('address')) ?></textarea>
        <small class="field-hint">Complete physical address including PIN code for GST and logistics verification.</small>
      </div>

      <div class="settings-grid-2">
        <div class="admin-field">
          <label for="settingGstNumber">GSTIN / PESO Registration License</label>
          <input type="text" id="settingGstNumber" name="gst_number" value="<?= e(setting('gst_number')) ?>" placeholder="09AAECF1234F1Z5 · PESO License #AGR/GAS/2018">
          <small class="field-hint">Statutory compliance numbers displayed on official invoices and legal pages.</small>
        </div>

        <div class="admin-field">
          <label for="settingTagline">Brand Tagline</label>
          <input type="text" id="settingTagline" name="tagline" value="<?= e(setting('tagline')) ?>" placeholder="Pure Care. Rapid Response. Reliable Medical Gases.">
          <small class="field-hint">Short institutional motto displayed under the brand emblem.</small>
        </div>
      </div>

      <div class="admin-field admin-field--wide">
        <label for="settingDescription">Company Overview / Mission Summary</label>
        <textarea id="settingDescription" name="description" rows="3" placeholder="Brief statement regarding Shree Ji Gas Industries history, medical compliance and manufacturing excellence..."><?= e(setting('description')) ?></textarea>
        <small class="field-hint">Used as default meta text and in corporate footer summaries.</small>
      </div>

      <div class="settings-toggle-row">
        <label class="checkbox-label">
          <input type="checkbox" name="preview_notice" value="1" <?= setting('preview_notice')==='1'?'checked':'' ?>>
          <span>Show starter-content reminder banner on the admin dashboard</span>
        </label>
      </div>
    </div>
  </section>

  <!-- TAB 2: BRANDING & ASSETS -->
  <section class="settings-tab-pane" id="tab-branding" role="tabpanel" aria-labelledby="tabBtnBranding">
    <div class="admin-panel settings-card">
      <div class="settings-card__header">
        <div class="settings-card__icon"><?= icon('image-2-line') ?></div>
        <div>
          <h2>Brand Visual Identity &amp; Downloadable Documents</h2>
          <p>Configure the header logo and official product catalogue PDF available to prospective clients.</p>
        </div>
      </div>

      <div class="settings-asset-group">
        <?php
        $name = 'logo';
        $key = 'logo';
        $value = setting('logo');
        $required = false;
        $kind = 'image';
        $type = 'settings';
        require ROOT.'/views/admin/field.php';
        ?>
      </div>

      <div class="settings-asset-group" style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #eef3f6;">
        <?php
        $name = 'catalogue_pdf';
        $key = 'catalogue_pdf';
        $value = setting('catalogue_pdf');
        $required = false;
        $kind = 'pdf';
        $type = 'settings';
        require ROOT.'/views/admin/field.php';
        ?>
        <p class="admin-help" style="margin-top: 10px;">
          <?= icon('information-line') ?> When a PDF is linked here, clicking "Download Catalogue" on the website initiates an instant PDF download. If left blank, the printable catalogue view is used.
        </p>
      </div>
    </div>
  </section>

  <!-- TAB 3: SMTP EMAIL GATEWAY -->
  <section class="settings-tab-pane" id="tab-smtp" role="tabpanel" aria-labelledby="tabBtnSmtp">
    <div class="admin-panel settings-card">
      <div class="settings-card__header">
        <div class="settings-card__icon"><?= icon('mail-settings-line') ?></div>
        <div>
          <h2>SMTP Email Gateway Configuration</h2>
          <p>Connect your transactional SMTP server (Gmail Workspace, Hostinger, cPanel, SendGrid, Amazon SES) for automated delivery of admin password resets, email change OTPs and enquiry alerts.</p>
        </div>
      </div>

      <div class="smtp-status-banner <?= setting('smtp_enabled')==='1'?'is-enabled':'is-disabled' ?>">
        <div class="smtp-status-icon">
          <?= icon(setting('smtp_enabled')==='1'?'shield-check-line':'information-line') ?>
        </div>
        <div class="smtp-status-text">
          <strong>SMTP Delivery Status: <?= setting('smtp_enabled')==='1'?'ENABLED (Live Mail Delivery)':'DISABLED (Local Simulated / Dev Mode)' ?></strong>
          <p>
            <?php if(setting('smtp_enabled')==='1'): ?>
              Outgoing emails are routed live through <code><?= e(setting('smtp_host') ?: 'configured host') ?>:<?= e(setting('smtp_port') ?: '587') ?></code>.
            <?php else: ?>
              Live SMTP sending is turned off. All generated OTPs and password reset links will work in simulated mode (displayed on screen / logged to <code>storage/logs/mail.log</code>) so you are never locked out.
            <?php endif ?>
          </p>
        </div>
      </div>

      <div class="settings-toggle-row smtp-enable-toggle">
        <label class="checkbox-label checkbox-label--lg">
          <input type="checkbox" name="smtp_enabled" id="smtpEnabledToggle" value="1" <?= setting('smtp_enabled')==='1'?'checked':'' ?>>
          <strong>Enable Outgoing SMTP Mail Dispatch</strong>
        </label>
        <small class="field-hint">Check this box to send genuine emails over socket connection instead of dev simulation.</small>
      </div>

      <div class="settings-grid-3">
        <div class="admin-field">
          <label for="smtpHost">SMTP Host / Server <span>*</span></label>
          <input type="text" id="smtpHost" name="smtp_host" value="<?= e(setting('smtp_host', 'smtp.gmail.com')) ?>" placeholder="smtp.gmail.com">
          <small class="field-hint">e.g. <code>smtp.gmail.com</code> (Google Workspace / Gmail) or <code>smtp.hostinger.com</code></small>
        </div>

        <div class="admin-field">
          <label for="smtpPort">SMTP Port <span>*</span></label>
          <input type="number" id="smtpPort" name="smtp_port" value="<?= e(setting('smtp_port') ?: '587') ?>" placeholder="587" min="1" max="65535">
          <small class="field-hint">Port <code>587</code> for TLS (recommended) or <code>465</code> for SSL.</small>
        </div>

        <div class="admin-field">
          <label for="smtpEncryption">Security / Encryption</label>
          <?php $enc = strtolower((string)setting('smtp_encryption')); ?>
          <select id="smtpEncryption" name="smtp_encryption">
            <option value="tls" <?= ($enc===''||$enc==='tls')?'selected':'' ?>>STARTTLS / TLS (Port 587 - Standard)</option>
            <option value="ssl" <?= ($enc==='ssl')?'selected':'' ?>>SSL / SMTPS (Port 465)</option>
            <option value="none" <?= ($enc==='none')?'selected':'' ?>>None / Unencrypted (Port 25)</option>
          </select>
          <small class="field-hint">Most modern mail servers mandate TLS.</small>
        </div>
      </div>

      <div class="settings-grid-2">
        <div class="admin-field">
          <label for="smtpUser">SMTP Username / Login Account</label>
          <input type="text" id="smtpUser" name="smtp_user" value="<?= e(setting('smtp_user')) ?>" placeholder="e.g. shreejigasteam@gmail.com">
          <small class="field-hint">For Gmail with 2FA, use a 16-character Google App Password.</small>
        </div>

        <div class="admin-field">
          <label for="smtpPass">SMTP Password / App Password</label>
          <div class="password-input-wrap">
            <input type="password" id="smtpPass" name="smtp_pass" value="<?= e(setting('smtp_pass')) ?>" placeholder="••••••••••••••••" autocomplete="new-password">
            <button type="button" class="btn-toggle-pass" data-target="smtpPass" aria-label="Toggle password visibility" title="Show/hide password">
              <?= icon('eye-line') ?>
            </button>
          </div>
          <small class="field-hint">Saved securely in local configuration.</small>
        </div>
      </div>

      <div class="settings-grid-2">
        <div class="admin-field">
          <label for="smtpFromEmail">From Email Address <span>*</span></label>
          <input type="email" id="smtpFromEmail" name="smtp_from_email" value="<?= e(setting('smtp_from_email', 'shreejigasteam@gmail.com')) ?>" placeholder="shreejigasteam@gmail.com">
          <small class="field-hint">The email address that recipients will see in their inbox header.</small>
        </div>

        <div class="admin-field">
          <label for="smtpFromName">From Display Name</label>
          <input type="text" id="smtpFromName" name="smtp_from_name" value="<?= e(setting('smtp_from_name') ?: 'Shree Ji Gas Industries') ?>" placeholder="Shree Ji Gas Industries">
          <small class="field-hint">Friendly sender name shown alongside the address.</small>
        </div>
      </div>

      <!-- Live Test Connection Box -->
      <div class="smtp-test-card" id="smtpTestCard">
        <div class="smtp-test-card__head">
          <div class="smtp-test-icon"><?= icon('send-plane-fill') ?></div>
          <div>
            <h3>Verify &amp; Test SMTP Gateway Connection</h3>
            <p>Send a real-time verification email to ensure server connectivity, port handshake, and credential authentication.</p>
          </div>
        </div>

        <div class="smtp-test-controls">
          <div class="smtp-test-input-wrap">
            <label for="smtpTestRecipient">Send Test Email To:</label>
            <input type="email" id="smtpTestRecipient" value="<?= e($user['email'] ?? 'shreejigasteam@gmail.com') ?>" placeholder="shreejigasteam@gmail.com">
          </div>
          <button type="button" class="btn btn-primary" id="btnRunSmtpTest">
            <?= icon('mail-send-line') ?> <span>Send Test Email</span>
          </button>
        </div>

        <div class="smtp-test-feedback" id="smtpTestFeedback" hidden></div>
      </div>
    </div>
  </section>

  <!-- TAB 5: DEFAULT SEO & SOCIAL -->
  <section class="settings-tab-pane" id="tab-seo" role="tabpanel" aria-labelledby="tabBtnSeo">
    <div class="admin-panel settings-card">
      <div class="settings-card__header">
        <div class="settings-card__icon"><?= icon('search-eye-line') ?></div>
        <div>
          <h2>Global SEO Defaults &amp; Social Channels</h2>
          <p>These default metadata values are used when specific pages or products do not have custom SEO defined.</p>
        </div>
      </div>

      <div class="admin-field admin-field--wide">
        <label for="settingMetaTitle">Default Meta Title</label>
        <input type="text" id="settingMetaTitle" name="meta_title" value="<?= e(setting('meta_title')) ?>" placeholder="Shree Ji Gas Industries | Medical Oxygen Cylinders &amp; Hospital Gas Pipeline Systems" data-counter="60">
        <small class="field-counter"></small>
        <small class="field-hint">Optimal length is 50–60 characters for Google and Bing search snippets.</small>
      </div>

      <div class="admin-field admin-field--wide">
        <label for="settingMetaDesc">Default Meta Description</label>
        <textarea id="settingMetaDesc" name="meta_description" rows="3" placeholder="PESO-certified medical oxygen cylinders, manifold pipeline installations, and hospital respiratory infrastructure across Agra and Uttar Pradesh." data-counter="155"><?= e(setting('meta_description')) ?></textarea>
        <small class="field-counter"></small>
        <small class="field-hint">Recommended length 120–155 characters for high search result click-through rates.</small>
      </div>

      <div class="admin-field admin-field--wide">
        <label for="settingKeywords">Global Focus Keywords (Comma separated)</label>
        <input type="text" id="settingKeywords" name="focus_keywords" value="<?= e(setting('focus_keywords')) ?>" placeholder="medical oxygen cylinder, hospital gas pipeline, manifold system, Agra oxygen supplier, PESO certified gas">
        <small class="field-hint">Included in the HTML <code>&lt;meta name="keywords"&gt;</code> tag and search indexing bots.</small>
      </div>

      <div class="settings-grid-3" style="margin-top: 16px;">
        <div class="admin-field">
          <label for="settingFacebook"><?= icon('facebook-circle-line') ?> Facebook Profile / Page</label>
          <input type="url" id="settingFacebook" name="facebook" value="<?= e(setting('facebook')) ?>" placeholder="https://facebook.com/shreejigas">
        </div>

        <div class="admin-field">
          <label for="settingInstagram"><?= icon('instagram-line') ?> Instagram Profile</label>
          <input type="url" id="settingInstagram" name="instagram" value="<?= e(setting('instagram')) ?>" placeholder="https://instagram.com/shreejigas">
        </div>

        <div class="admin-field">
          <label for="settingLinkedin"><?= icon('linkedin-box-line') ?> LinkedIn Company Page</label>
          <input type="url" id="settingLinkedin" name="linkedin" value="<?= e(setting('linkedin')) ?>" placeholder="https://linkedin.com/company/shreejigas">
        </div>
      </div>
    </div>
  </section>

  <!-- Sticky Save Bar for Tabs 1, 2, 3, 5 -->
  <div class="sticky-save" id="settingsStickySave">
    <span>Site configuration and contact values update instantly across your entire website.</span>
    <button type="submit" class="btn btn-primary" id="btnSaveSiteSettings">
      Save Site Settings <?= icon('check-line') ?>
    </button>
  </div>
</form>

<!-- TAB 4: ACCOUNT & SECURITY (Admin Email Change via OTP + Password Change) -->
<section class="settings-tab-pane" id="tab-security" role="tabpanel" aria-labelledby="tabBtnSecurity">
  <div class="admin-panel settings-card">
    <div class="settings-card__header">
      <div class="settings-card__icon"><?= icon('shield-user-line') ?></div>
      <div>
        <h2>Active Administrator Profile</h2>
        <p>Your current identity within the Shree Ji Gas management console.</p>
      </div>
    </div>

    <div class="admin-profile-pill-row">
      <div class="profile-meta-item">
        <span class="meta-label">Admin Name</span>
        <strong class="meta-value"><?= e($user['name'] ?? 'Administrator') ?></strong>
      </div>
      <div class="profile-meta-item">
        <span class="meta-label">Username</span>
        <strong class="meta-value"><?= e($user['username'] ?? 'admin') ?></strong>
      </div>
      <div class="profile-meta-item">
        <span class="meta-label">Current Verified Email</span>
        <strong class="meta-value" id="currentAdminEmailDisplay"><?= e($user['email'] ?? 'shreejigasteam@gmail.com') ?></strong>
      </div>
      <div class="profile-meta-item">
        <span class="meta-label">Session Security</span>
        <span class="badge-status-secure"><?= icon('lock-line') ?> HTTPS Session Active</span>
      </div>
    </div>
  </div>

  <div class="security-grid-dual">
    <!-- PANEL A: Change Admin Email Address via OTP -->
    <div class="admin-panel security-card" id="emailChangeCard">
      <div class="security-card__head">
        <div class="security-icon-circle"><?= icon('mail-check-line') ?></div>
        <div>
          <h3>Change Admin Email</h3>
          <p>Requires current password authentication and a 6-digit one-time verification code (OTP).</p>
        </div>
      </div>

      <div id="emailChangeAlertBox" class="security-alert-box" hidden></div>

      <!-- Step 1: Request OTP -->
      <div id="emailChangeStep1" class="email-step-panel">
        <div class="admin-field">
          <label for="newAdminEmail">New Admin Email Address <span>*</span></label>
          <input type="email" id="newAdminEmail" placeholder="e.g. shreejigasteam@gmail.com" required autocomplete="email">
          <small class="field-hint">A 6-digit verification code will be dispatched to this address.</small>
        </div>

        <div class="admin-field">
          <label for="emailCurrentPassword">Current Admin Password <span>*</span></label>
          <div class="password-input-wrap">
            <input type="password" id="emailCurrentPassword" placeholder="Confirm your active password" required autocomplete="current-password">
            <button type="button" class="btn-toggle-pass" data-target="emailCurrentPassword" aria-label="Toggle password visibility">
              <?= icon('eye-line') ?>
            </button>
          </div>
          <small class="field-hint">Required to prevent unauthorized email modifications.</small>
        </div>

        <div class="security-action-row">
          <button type="button" class="btn btn-primary" id="btnSendEmailOtp">
            <?= icon('send-plane-line') ?> <span>Send 6-Digit OTP</span>
          </button>
        </div>
      </div>

      <!-- Step 2: Verify OTP -->
      <div id="emailChangeStep2" class="email-step-panel" hidden>
        <div class="otp-instruction-banner">
          <?= icon('mail-line') ?>
          <div>
            <strong>Verification Code Sent</strong>
            <p>Please enter the 6-digit verification code sent to <code id="displayTargetEmail"></code>.</p>
            <div id="simulatedOtpNotice" class="simulated-otp-box" hidden></div>
          </div>
        </div>

        <div class="admin-field">
          <label for="emailOtpInput">6-Digit Verification Code <span>*</span></label>
          <div class="otp-input-group">
            <input type="text" id="emailOtpInput" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" placeholder="123456" class="otp-input-control" autocomplete="one-time-code">
          </div>
          <small class="field-hint">Code expires in 10 minutes.</small>
        </div>

        <div class="security-action-row">
          <button type="button" class="btn btn-primary" id="btnVerifyEmailOtp">
            <?= icon('check-double-line') ?> <span>Verify &amp; Update Email</span>
          </button>
          <button type="button" class="btn btn-outline" id="btnCancelEmailChange">
            Cancel
          </button>
        </div>
      </div>
    </div>

    <!-- PANEL B: Change Administrator Password -->
    <div class="admin-panel security-card" id="passwordChangeCard">
      <div class="security-card__head">
        <div class="security-icon-circle"><?= icon('lock-password-line') ?></div>
        <div>
          <h3>Change Admin Password</h3>
          <p>Update your administrator access password. Requires at least 12 characters.</p>
        </div>
      </div>

      <div id="passwordAlertBox" class="security-alert-box" hidden></div>

      <form method="post" action="<?= url('admin/password') ?>" id="passwordChangeForm" class="password-form-inner">
        <?= csrfField() ?>
        
        <div class="admin-field">
          <label for="currentPassword">Current Password <span>*</span></label>
          <div class="password-input-wrap">
            <input type="password" id="currentPassword" name="current_password" required autocomplete="current-password" placeholder="Enter current password">
            <button type="button" class="btn-toggle-pass" data-target="currentPassword" aria-label="Toggle password visibility">
              <?= icon('eye-line') ?>
            </button>
          </div>
        </div>

        <div class="admin-field">
          <label for="newPassword">New Password <span>*</span></label>
          <div class="password-input-wrap">
            <input type="password" id="newPassword" name="password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="Min. 12 characters">
            <button type="button" class="btn-toggle-pass" data-target="newPassword" aria-label="Toggle password visibility">
              <?= icon('eye-line') ?>
            </button>
          </div>
          <small class="field-hint">Use a mixture of uppercase, lowercase, numbers and symbols.</small>
        </div>

        <div class="admin-field">
          <label for="confirmPassword">Confirm New Password <span>*</span></label>
          <div class="password-input-wrap">
            <input type="password" id="confirmPassword" name="confirm_password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="Re-type new password">
            <button type="button" class="btn-toggle-pass" data-target="confirmPassword" aria-label="Toggle password visibility">
              <?= icon('eye-line') ?>
            </button>
          </div>
        </div>

        <div class="security-action-row">
          <button type="submit" class="btn btn-primary" id="btnUpdatePassword">
            <?= icon('key-line') ?> <span>Update Password</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>
