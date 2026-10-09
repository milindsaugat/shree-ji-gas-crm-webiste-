<div class="admin-page-heading">
  <div>
    <span class="eyebrow">ACCOUNT &amp; CREDENTIALS</span>
    <h1>Change Administrator Password</h1>
    <p>Choose a strong, unique password with at least 12 characters to protect your administration workspace.</p>
  </div>
  <div>
    <a href="<?= url('admin/settings') ?>#security" class="btn btn-outline btn-sm">
      <?= icon('shield-keyhole-line') ?> <span>Account &amp; Email Settings</span>
    </a>
  </div>
</div>

<?php if($error): ?>
  <div class="admin-alert admin-alert--error" role="alert">
    <?= icon('error-warning-line') ?>
    <span><?= e($error) ?></span>
  </div>
<?php endif ?>

<div class="admin-panel security-card" style="max-width: 680px;">
  <form class="password-form-inner" method="post" action="<?= url('admin/password') ?>">
    <?= csrfField() ?>
    
    <div class="admin-field">
      <label for="pwdCurrentPassword">Current Password <span>*</span></label>
      <div class="password-input-wrap">
        <input id="pwdCurrentPassword" name="current_password" type="password" required autocomplete="current-password" placeholder="Enter your current password">
        <button type="button" class="btn-toggle-pass" data-target="pwdCurrentPassword" aria-label="Toggle password visibility">
          <?= icon('eye-line') ?>
        </button>
      </div>
      <small class="field-hint">Required to verify your current administrator identity.</small>
    </div>

    <div class="admin-field">
      <label for="pwdNewPassword">New Password <span>*</span></label>
      <div class="password-input-wrap">
        <input id="pwdNewPassword" name="password" type="password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="Minimum 12 characters">
        <button type="button" class="btn-toggle-pass" data-target="pwdNewPassword" aria-label="Toggle password visibility">
          <?= icon('eye-line') ?>
        </button>
      </div>
      <small class="field-hint">Must be between 12 and 72 characters long.</small>
    </div>

    <div class="admin-field">
      <label for="pwdConfirmPassword">Confirm New Password <span>*</span></label>
      <div class="password-input-wrap">
        <input id="pwdConfirmPassword" name="confirm_password" type="password" required minlength="12" maxlength="72" autocomplete="new-password" placeholder="Re-type new password">
        <button type="button" class="btn-toggle-pass" data-target="pwdConfirmPassword" aria-label="Toggle password visibility">
          <?= icon('eye-line') ?>
        </button>
      </div>
      <small class="field-hint">Ensure both entries match exactly.</small>
    </div>

    <div class="security-action-row" style="margin-top: 24px;">
      <button type="submit" class="btn btn-primary">
        Update Password <?= icon('check-line') ?>
      </button>
      <a href="<?= url('admin/settings') ?>" class="btn btn-outline">
        Cancel
      </a>
    </div>
  </form>
</div>
