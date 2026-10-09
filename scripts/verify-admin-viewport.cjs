const fs = require('fs');
const path = require('path');
const crypto = require('crypto');
const { execFileSync } = require('child_process');
const { chromium } = require('../tmp/browser-check/node_modules/playwright');

(async () => {
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], {
    stdio: 'pipe',
    env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' }
  });

  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1920, height: 1080 } });
  const page = await context.newPage();

  const baseUrl = 'http://localhost/shreejigas';
  const outDir = path.resolve(__dirname, '../storage/checks');
  fs.mkdirSync(outDir, { recursive: true });

  console.log('Logging in as browser-admin@example.test...');
  await page.goto(baseUrl + '/admin/login', { waitUntil: 'networkidle' });
  await page.locator('#adminEmail').fill('browser-admin@example.test');
  await page.locator('#adminPassword').fill(password);
  await page.getByRole('button', { name: 'Sign in', exact: true }).click();
  await page.waitForURL('**/admin**');

  // Navigate to /admin/sections/hero
  console.log('Navigating to /admin/sections/hero on 1920x1080 Desktop...');
  await page.goto(baseUrl + '/admin/sections/hero', { waitUntil: 'networkidle' });
  await page.waitForTimeout(1000);

  // Check visibility of .admin-menu-button and .admin-close-nav on Desktop (1920px)
  const menuBtnVisible = await page.locator('.admin-menu-button').isVisible();
  const closeNavVisible = await page.locator('.admin-close-nav').isVisible();
  console.log('Desktop 1920px check:');
  console.log(' - Hamburger button (.admin-menu-button) visible?:', menuBtnVisible);
  console.log(' - Close button (.admin-close-nav) visible?:', closeNavVisible);

  // Capture full section editor screen on desktop
  await page.screenshot({ path: path.join(outDir, 'admin-section-hero-1600.png'), fullPage: false });
  console.log('Captured admin-section-hero-1600.png');

  // Check iframe size and preview content
  const previewVp = page.locator('.preview-viewport');
  const vpBox = await previewVp.boundingBox();
  console.log('Preview Viewport Box on Desktop:', vpBox);

  // Test mobile viewport toggle
  console.log('Testing Mobile viewport button...');
  await page.locator('.viewport-btn[data-viewport="390"]').click();
  await page.waitForTimeout(500);
  await page.screenshot({ path: path.join(outDir, 'admin-section-hero-mobile-vp.png'), fullPage: false });

  // Test Desktop 1600px viewport button toggle back
  console.log('Testing Desktop 1600px button...');
  await page.locator('.viewport-btn[data-viewport="1600"]').click();
  await page.waitForTimeout(500);
  await page.screenshot({ path: path.join(outDir, 'admin-section-hero-desktop-vp.png'), fullPage: false });

  await browser.close();
  console.log('Admin viewport checks completed successfully!');
})();
