const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');
const { execFileSync } = require('node:child_process');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';
const output = path.resolve(__dirname, '../storage/checks');
fs.mkdirSync(output, { recursive: true });

(async () => {
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], {
    stdio: 'pipe',
    env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' }
  });

  const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });
  const context = await browser.newContext({ viewport: { width: 1440, height: 1000 } });
  const page = await context.newPage();

  try {
    // 1. Sign in
    await page.goto(base + '/admin/login', { waitUntil: 'networkidle' });
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin');

    // 2. Blog Post 4
    await page.goto(base + '/admin/content/blog_posts/4', { waitUntil: 'networkidle' });
    await page.locator('.editorial-cover-field-group').scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);
    await page.screenshot({ path: path.join(output, 'admin-blog-post-4-cover.png'), fullPage: false });
    console.log('Saved admin-blog-post-4-cover.png');

    // 3. Case Study 3
    await page.goto(base + '/admin/content/case_studies/3', { waitUntil: 'networkidle' });
    await page.locator('.editorial-cover-field-group').scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);
    await page.screenshot({ path: path.join(output, 'admin-case-study-3-cover.png'), fullPage: false });
    console.log('Saved admin-case-study-3-cover.png');

    // 4. Use Case 3
    await page.goto(base + '/admin/content/use_cases/3', { waitUntil: 'networkidle' });
    await page.locator('.editorial-cover-field-group').scrollIntoViewIfNeeded();
    await page.waitForTimeout(300);
    await page.screenshot({ path: path.join(output, 'admin-use-case-3-cover.png'), fullPage: false });
    console.log('Saved admin-use-case-3-cover.png');

    // 5. Test Media Picker Dialog
    const pickerBtn = page.locator('#btnOpenLibrary');
    if (await pickerBtn.isVisible()) {
      await pickerBtn.click();
      await page.waitForTimeout(500);
      await page.screenshot({ path: path.join(output, 'admin-media-picker-open.png'), fullPage: false });
      console.log('Saved admin-media-picker-open.png');
      await page.locator('#mediaPicker [data-close-dialog]').click();
    }

    // 6. Test Public Blog detail page
    await page.goto(base + '/blog/oxygen-safety-standards-in-up-hospitals', { waitUntil: 'networkidle' });
    await page.screenshot({ path: path.join(output, 'public-blog-post-4.png'), fullPage: false });
    console.log('Saved public-blog-post-4.png');

    // 7. Test Public Case Study detail page
    await page.goto(base + '/case-studies/portable-oxygen-rural-clinic-network', { waitUntil: 'networkidle' });
    await page.screenshot({ path: path.join(output, 'public-case-study-3.png'), fullPage: false });
    console.log('Saved public-case-study-3.png');

    // 8. Test Public Use Case detail page
    await page.goto(base + '/use-cases/supervised-home-care', { waitUntil: 'networkidle' });
    await page.screenshot({ path: path.join(output, 'public-use-case-3.png'), fullPage: false });
    console.log('Saved public-use-case-3.png');

  } catch (err) {
    console.error('Error during test:', err);
  } finally {
    await browser.close();
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'cleanup'], {
      stdio: 'pipe',
      env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' }
    });
  }
})();
