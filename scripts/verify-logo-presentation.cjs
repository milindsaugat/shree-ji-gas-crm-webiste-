const fs = require('fs');
const path = require('path');
const { chromium } = require('../tmp/browser-check/node_modules/playwright');

(async () => {
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();

  const baseUrl = 'http://localhost/shreejigas';
  const outDir = path.resolve(__dirname, '../storage/checks');
  fs.mkdirSync(outDir, { recursive: true });

  console.log('Navigating to homepage...');
  await page.goto(baseUrl, { waitUntil: 'networkidle' });

  // Check favicon links
  const favicons = await page.evaluate(() => {
    return Array.from(document.querySelectorAll("link[rel*='icon'], link[rel*='apple-touch-icon']")).map(el => ({
      rel: el.getAttribute('rel'),
      href: el.getAttribute('href'),
      sizes: el.getAttribute('sizes')
    }));
  });
  console.log('Favicons in DOM:', favicons);

  // 1. Capture Header (initial state)
  const header = page.locator('#siteHeader');
  await header.screenshot({ path: path.join(outDir, 'logo-header-light.png') });
  console.log('Captured logo-header-light.png');

  // 2. Scroll to test is-scrolled sticky state
  await page.evaluate(() => window.scrollTo(0, 500));
  await page.waitForTimeout(300);
  await header.screenshot({ path: path.join(outDir, 'logo-header-scrolled.png') });
  console.log('Captured logo-header-scrolled.png');

  // 3. Capture Footer brand
  const footerBrand = page.locator('.site-footer .footer-about .brand');
  await footerBrand.scrollIntoViewIfNeeded();
  await page.waitForTimeout(200);
  await footerBrand.screenshot({ path: path.join(outDir, 'logo-footer.png') });
  console.log('Captured logo-footer.png');

  // 4. Mobile view header
  await page.setViewportSize({ width: 390, height: 844 });
  await page.evaluate(() => window.scrollTo(0, 0));
  await page.waitForTimeout(200);
  await header.screenshot({ path: path.join(outDir, 'logo-header-mobile.png') });
  console.log('Captured logo-header-mobile.png');

  // 5. Admin Login view
  await page.setViewportSize({ width: 1440, height: 900 });
  await page.goto(baseUrl + '/admin/login', { waitUntil: 'networkidle' });
  const authPanel = page.locator('.auth-brand-panel');
  if (await authPanel.isVisible()) {
    await authPanel.screenshot({ path: path.join(outDir, 'logo-admin-login.png') });
    console.log('Captured logo-admin-login.png');
  }

  await browser.close();
  console.log('All visual verification screenshots captured!');
})();
