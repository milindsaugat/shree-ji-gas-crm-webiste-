const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');
const assert = require('node:assert/strict');
const path = require('node:path');
const crypto = require('node:crypto');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';

(async () => {
  console.log('--- Testing Direct Tabs Navigation (No List Screen) ---');
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });

  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1600, height: 900 } });
  const page = await context.newPage();

  try {
    // 1. Log in
    await page.goto(base + '/admin/login');
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin');
    console.log('1. Logged into admin.');

    // 2. Navigate directly to /admin/sections (the old list URL)
    console.log('2. Visiting /admin/sections...');
    await page.goto(base + '/admin/sections');
    await page.waitForLoadState('networkidle');

    const currentUrl = page.url();
    console.log(' - Current URL after navigating to /admin/sections:', currentUrl);
    assert(currentUrl.includes('/admin/sections/hero'), 'Visiting /admin/sections should directly redirect to /admin/sections/hero');

    // 3. Verify tabs are present and list is absent
    const tabsExist = await page.locator('.section-switcher-container').isVisible();
    const listExists = await page.locator('.sections-list').count();
    console.log(' - Section switcher tabs visible?:', tabsExist);
    console.log(' - Old sections list table count:', listExists);
    assert.equal(tabsExist, true, 'Tabs bar must be visible directly');
    assert.equal(listExists, 0, 'Old sections list table must not be present');

    // 4. Click sidebar "Homepage" menu link from another page (e.g. Dashboard)
    console.log('3. Navigating to Dashboard and clicking Homepage sidebar link...');
    await page.goto(base + '/admin');
    const homepageLink = page.locator('aside.admin-sidebar a', { hasText: 'Homepage' });
    const homepageHref = await homepageLink.getAttribute('href');
    console.log(' - Homepage sidebar href:', homepageHref);
    assert(homepageHref.includes('/admin/sections/hero'), 'Sidebar Homepage link should directly point to /admin/sections/hero');

    await homepageLink.click();
    await page.waitForLoadState('networkidle');
    console.log(' - Landed on URL:', page.url());
    assert(page.url().includes('/admin/sections/hero'), 'Clicking Homepage in sidebar should land directly on /admin/sections/hero');

    // 5. Verify the active tab is Hero and active sidebar link is Homepage
    const activePillText = await page.locator('.section-pill.is-active').innerText();
    console.log(' - Active tab pill:', activePillText.trim());
    assert(activePillText.includes('Hero'), 'Active pill should be Hero');

    const sidebarActiveText = await page.locator('aside.admin-sidebar a.active').innerText();
    console.log(' - Active sidebar link:', sidebarActiveText.trim());
    assert(sidebarActiveText.includes('Homepage'), 'Homepage link in sidebar should be active');

    console.log('\n*** DIRECT TABS NAVIGATION TEST PASSED: LIST SCREEN BYPASSED COMPLETELY ***\n');
  } finally {
    await browser.close();
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'cleanup'], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });
  }
})().catch(e => {
  console.error('TEST FAILED:', e);
  process.exitCode = 1;
});
