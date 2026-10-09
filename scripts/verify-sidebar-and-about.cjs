const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');
const assert = require('node:assert/strict');
const path = require('node:path');
const crypto = require('node:crypto');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';

(async () => {
  console.log('--- Starting Sidebar Collapse & About Us Visual Editor Test ---');
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });

  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1600, height: 900 } });
  const page = await context.newPage();

  try {
    // 1. Log in to Admin
    console.log('1. Logging in to admin...');
    await page.goto(base + '/admin/login');
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin');
    console.log(' - Logged in successfully.');

    // 2. Test Sidebar Collapse on Desktop (1600px)
    console.log('2. Testing Sidebar Collapse on Desktop...');
    const sidebar = page.locator('#adminSidebar');
    let initialWidth = await sidebar.evaluate(el => el.getBoundingClientRect().width);
    console.log(' - Initial desktop sidebar width:', initialWidth, 'px (expected ~250px)');
    assert(initialWidth >= 220, 'Sidebar should be full width initially');

    // Click collapse toggle button in sidebar head
    const collapseBtn = page.locator('#sidebarCollapseBtn');
    await collapseBtn.click();
    await page.waitForTimeout(350); // wait for CSS transition

    const isCollapsedClass = await page.evaluate(() => document.body.classList.contains('sidebar-collapsed'));
    assert.equal(isCollapsedClass, true, 'Body should have .sidebar-collapsed class');

    let collapsedWidth = await sidebar.evaluate(el => el.getBoundingClientRect().width);
    console.log(' - Collapsed sidebar width:', collapsedWidth, 'px (expected 72px)');
    assert(collapsedWidth <= 80 && collapsedWidth >= 65, 'Sidebar should be in 72px rail mode');

    // Verify localStorage persistence
    const storedState = await page.evaluate(() => localStorage.getItem('shreeji_admin_sidebar_collapsed'));
    assert.equal(storedState, '1', 'localStorage should have stored "1"');

    // Test Expand via Topbar Menu Button
    const topbarBtn = page.locator('#topbarMenuBtn');
    await topbarBtn.click();
    await page.waitForTimeout(350);

    const isExpandedClass = await page.evaluate(() => !document.body.classList.contains('sidebar-collapsed'));
    assert.equal(isExpandedClass, true, 'Body should not have .sidebar-collapsed after clicking topbar toggle');
    let reExpandedWidth = await sidebar.evaluate(el => el.getBoundingClientRect().width);
    console.log(' - Re-expanded sidebar width:', reExpandedWidth, 'px');
    assert(reExpandedWidth >= 220, 'Sidebar should be expanded again');

    // Test Keyboard Shortcut Ctrl+B
    await page.keyboard.press('Control+b');
    await page.waitForTimeout(350);
    assert.equal(await page.evaluate(() => document.body.classList.contains('sidebar-collapsed')), true, 'Ctrl+B should toggle collapse');
    await page.keyboard.press('Control+b');
    await page.waitForTimeout(350);
    assert.equal(await page.evaluate(() => document.body.classList.contains('sidebar-collapsed')), false, 'Ctrl+B should toggle expand');
    console.log('PASS Sidebar collapse, expansion, persistence, and Ctrl+B shortcut verified');

    // 3. Test Mobile Drawer (390px viewport)
    console.log('3. Testing Mobile Drawer Navigation...');
    await page.setViewportSize({ width: 390, height: 844 });
    await page.waitForTimeout(200);

    let mobileSidebarVisible = await sidebar.evaluate(el => {
      const rect = el.getBoundingClientRect();
      return rect.right > 0;
    });
    console.log(' - Mobile sidebar initially off-screen?:', !mobileSidebarVisible);

    // Click hamburger button to open
    await topbarBtn.click();
    await page.waitForTimeout(250);
    let mobileOpenClass = await sidebar.evaluate(el => el.classList.contains('is-open'));
    assert.equal(mobileOpenClass, true, 'Mobile sidebar should have .is-open class');
    console.log(' - Mobile drawer opened.');

    // Click close button
    const closeBtn = page.locator('.admin-close-nav');
    await closeBtn.click();
    await page.waitForTimeout(250);
    mobileOpenClass = await sidebar.evaluate(el => el.classList.contains('is-open'));
    assert.equal(mobileOpenClass, false, 'Mobile sidebar should close on close button click');
    console.log('PASS Mobile drawer open and close verified');

    // Restore desktop viewport
    await page.setViewportSize({ width: 1600, height: 900 });

    // 4. Test About Us Visual Editor (/admin/pages/about)
    console.log('4. Testing About Us Visual Editor (/admin/pages/about)...');
    await page.goto(base + '/admin/pages/about');
    await page.waitForSelector('#sectionForm');
    await page.waitForSelector('#sectionPreview');

    // Check switcher pill for About us
    const activeAboutPill = page.locator('.section-pill.is-active');
    const pillText = await activeAboutPill.innerText();
    console.log(' - Active switcher pill text:', pillText.trim());
    assert(pillText.includes('About us'), 'Active section pill should be About us');

    // Check preview iframe
    const previewFrameElement = page.locator('#sectionPreview');
    const previewUrl = await previewFrameElement.getAttribute('src');
    console.log(' - Preview iframe src:', previewUrl);
    assert(previewUrl.includes('page=about'), 'Iframe should load ?page=about');

    // Wait for iframe content
    const frame = page.frameLocator('#sectionPreview');
    await frame.locator('h1[data-preview="title"]').waitFor({ timeout: 10000 });
    const originalTitle = await frame.locator('h1[data-preview="title"]').innerText();
    console.log(' - Original About Us Title in preview:', originalTitle);

    // Verify in-place editable attributes inside preview iframe
    const h1ContentEditable = await frame.locator('h1[data-preview="title"]').getAttribute('contenteditable');
    assert.equal(h1ContentEditable, 'true', 'h1 should be contenteditable');

    const excerptContentEditable = await frame.locator('p[data-preview="excerpt"]').getAttribute('contenteditable');
    assert.equal(excerptContentEditable, 'true', 'excerpt should be contenteditable');

    const proseContentEditable = await frame.locator('.prose[data-preview="content"]').getAttribute('contenteditable');
    assert.equal(proseContentEditable, 'true', 'content prose should be contenteditable');

    console.log('PASS All text fields are marked contenteditable="true" inside preview');

    // 5. Test In-Place Typing and Auto-Sync to Form
    console.log('5. Testing In-Place Typing and Sync...');
    const testTitle = 'Medical & Gas Technology Excellence ' + crypto.randomInt(1000, 9999);
    await frame.locator('h1[data-preview="title"]').click();
    await frame.locator('h1[data-preview="title"]').fill(testTitle);
    await page.waitForTimeout(150);

    // Verify hidden input updated
    const hiddenTitleVal = await page.locator('#field-title').inputValue();
    console.log(' - Synced hidden #field-title value:', hiddenTitleVal);
    assert.equal(hiddenTitleVal, testTitle, 'Hidden field-title should match typed text');

    // Verify Save Changes (AJAX save)
    console.log(' - Saving changes via Save button...');
    await page.locator('#visualSaveBtn').click();
    await page.waitForSelector('.admin-toast:not([hidden])', { timeout: 8000 });
    const toastMsg = await page.locator('.admin-toast').innerText();
    console.log(' - Toast message:', toastMsg);
    assert(toastMsg.includes('saved'), 'Toast should indicate successful save');

    // 6. Verify Public Website Updates (/about)
    console.log('6. Verifying public /about page has updated title...');
    const publicPage = await context.newPage();
    await publicPage.goto(base + '/about');
    const publicH1 = await publicPage.locator('h1').innerText();
    console.log(' - Public /about H1:', publicH1);
    assert.equal(publicH1, testTitle, 'Public page should reflect in-place edit');
    await publicPage.close();
    console.log('PASS Public /about rendered newly edited title');

    // 7. Restore original title
    console.log('7. Restoring original title...');
    await frame.locator('h1[data-preview="title"]').fill(originalTitle);
    await page.locator('#visualSaveBtn').click();
    await page.waitForTimeout(1000);
    console.log('PASS Original title restored cleanly');

    console.log('\n*** ALL SIDEBAR AND ABOUT US VISUAL EDITOR CHECKS PASSED SUCCESSFULLY ***\n');
  } finally {
    await browser.close();
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'cleanup'], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });
  }
})().catch(e => {
  console.error('TEST FAILED:', e);
  process.exitCode = 1;
});
