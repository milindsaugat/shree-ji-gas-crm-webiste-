const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const crypto = require('node:crypto');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';

(async () => {
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });

  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1600, height: 950 } });
  const page = await context.newPage();

  try {
    await page.goto(base + '/admin/login');
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin');

    // Screenshot 1: About us editor (expanded sidebar)
    await page.goto(base + '/admin/pages/about');
    await page.waitForSelector('#sectionForm');
    await page.waitForTimeout(1000);
    await page.screenshot({ path: path.resolve(__dirname, '../admin-about-editor.png'), fullPage: false });
    console.log('Saved admin-about-editor.png');

    // Screenshot 2: Collapsed Sidebar (Rail mode)
    await page.locator('#sidebarCollapseBtn').click();
    await page.waitForTimeout(500);
    await page.screenshot({ path: path.resolve(__dirname, '../admin-sidebar-collapsed.png'), fullPage: false });
    console.log('Saved admin-sidebar-collapsed.png');

  } finally {
    await browser.close();
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'cleanup'], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });
  }
})();
