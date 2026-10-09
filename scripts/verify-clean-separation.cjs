const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');
const assert = require('node:assert/strict');
const path = require('node:path');
const crypto = require('node:crypto');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';

(async () => {
  console.log('--- Testing Clean Separation of Home vs About Us Tabs ---');
  const password = crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });

  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const context = await browser.newContext({ viewport: { width: 1600, height: 900 } });
  const page = await context.newPage();

  try {
    // 1. Log in to admin
    await page.goto(base + '/admin/login');
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in' }).click();
    await page.waitForURL('**/admin');
    console.log('1. Logged into admin.');

    // 2. Check Homepage editor tabs (/admin/sections/hero)
    console.log('2. Inspecting Homepage section tabs...');
    await page.locator('aside.admin-sidebar a', { hasText: 'Homepage' }).click();
    await page.waitForURL('**/admin/sections/hero');
    await page.waitForSelector('.section-switcher-scroll');

    const homePillTexts = await page.locator('.section-switcher-scroll .section-pill').allInnerTexts();
    const cleanHomePills = homePillTexts.map(t => t.trim());
    console.log(' - Homepage tabs count:', cleanHomePills.length);
    console.log(' - First 5 Homepage tabs:', cleanHomePills.slice(0, 5));
    
    // Assert Homepage contains Hero, At a glance, etc.
    assert(cleanHomePills.includes('Hero'), 'Homepage tabs must include Hero');
    assert(cleanHomePills.includes('At a glance'), 'Homepage tabs must include At a glance');
    assert(!cleanHomePills.includes('About us'), 'Homepage tabs must NOT include About us!');
    console.log('PASS Homepage contains ONLY Homepage sections (About us correctly removed)');

    await page.screenshot({ path: path.resolve(__dirname, '../screenshot-home-clean-tabs.png') });

    // 3. Check About Us editor tabs (/admin/pages/about)
    console.log('3. Inspecting About Us tabs...');
    await page.locator('aside.admin-sidebar a', { hasText: 'About us' }).click();
    await page.waitForURL('**/admin/pages/about');
    await page.waitForSelector('.section-switcher-scroll');

    const aboutPillTexts = await page.locator('.section-switcher-scroll .section-pill').allInnerTexts();
    const cleanAboutPills = aboutPillTexts.map(t => t.trim());
    console.log(' - About Us tabs count:', cleanAboutPills.length);
    console.log(' - About Us tabs:', cleanAboutPills);

    // Assert About Us contains its own sections and NO Homepage sections
    assert(cleanAboutPills.includes('Overview & Intro'), 'About Us tabs must include Overview & Intro');
    assert(cleanAboutPills.includes('Our Story'), 'About Us tabs must include Our Story');
    assert(cleanAboutPills.includes('Equipment Range'), 'About Us tabs must include Equipment Range');
    assert(cleanAboutPills.includes('Care Settings'), 'About Us tabs must include Care Settings');
    assert(cleanAboutPills.includes('Our Approach'), 'About Us tabs must include Our Approach');
    assert(cleanAboutPills.includes('Consultation Journey'), 'About Us tabs must include Consultation Journey');
    assert(cleanAboutPills.includes('Local Partner'), 'About Us tabs must include Local Partner');
    assert(cleanAboutPills.includes('Call to Action'), 'About Us tabs must include Call to Action');

    assert(!cleanAboutPills.includes('Hero'), 'About Us tabs must NOT include Homepage Hero!');
    assert(!cleanAboutPills.includes('At a glance'), 'About Us tabs must NOT include Homepage At a glance!');
    assert(!cleanAboutPills.includes('Why choose us'), 'About Us tabs must NOT include Homepage Why choose us!');
    console.log('PASS About Us contains ONLY About Us sections (Zero Homepage tabs mixed in)');

    await page.screenshot({ path: path.resolve(__dirname, '../screenshot-about-clean-tabs.png') });

    // 4. Test clicking on an About Us tab (e.g. Our Story)
    console.log('4. Testing tab click on "Our Story"...');
    const storyTab = page.locator('.section-switcher-scroll .section-pill', { hasText: 'Our Story' });
    await storyTab.click();
    await page.waitForTimeout(300);
    const isStoryActive = await storyTab.evaluate(el => el.classList.contains('is-active'));
    assert.equal(isStoryActive, true, 'Our Story tab should be marked is-active');
    console.log('PASS In-page section tab click works smoothly');

    // 5. Test that clicking breadcrumb or links inside preview iframe does NOT navigate away to home
    console.log('5. Testing iframe link click protection...');
    const frame = page.frameLocator('#sectionPreview');
    const breadcrumbHome = frame.locator('nav.breadcrumb a', { hasText: 'Home' });
    await breadcrumbHome.click({ force: true });
    await page.waitForTimeout(500);

    const currentUrl = page.url();
    console.log(' - Current top URL after clicking breadcrumb in iframe:', currentUrl);
    assert(currentUrl.includes('/admin/pages/about'), 'Clicking inside iframe must NOT navigate top window away from About Us');
    console.log('PASS All iframe anchor clicks safely prevented from navigating away');

    console.log('\n*** ALL CLEAN SEPARATION CHECKS PASSED SUCCESSFULLY ***\n');
  } finally {
    await browser.close();
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'cleanup'], { env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' } });
  }
})().catch(e => {
  console.error('TEST FAILED:', e);
  process.exitCode = 1;
});
