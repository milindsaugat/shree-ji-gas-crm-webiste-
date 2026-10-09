const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');

const testPassword = 'Password123456!';

function setupAdminFixture() {
    console.log('Setting up temporary admin fixture...');
    try {
        const phpCode = `require 'config/config.php'; require 'config/database.php'; $pdo = Database::get(); $pdo->prepare('DELETE FROM admin_users WHERE email = ? OR username = ?')->execute(['seo-admin@example.test', 'seo-test']); $pdo->prepare('INSERT INTO admin_users (name,username,email,password_hash) VALUES (?,?,?,?)')->execute(['SEO Test','seo-test','seo-admin@example.test',password_hash('${testPassword}',PASSWORD_BCRYPT)]); echo 'Temporary test administrator created.' . PHP_EOL;`;
        execFileSync('C:\\xampp\\php\\php.exe', ['-r', phpCode], { stdio: 'inherit' });
    } catch (err) {
        console.error('Fixture setup error:', err);
    }
}

(async () => {
    setupAdminFixture();
    const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });
    const context = await browser.newContext({
        viewport: { width: 1440, height: 900 }
    });
    const page = await context.newPage();

    let passed = 0;
    let failed = 0;
    function assert(cond, msg) {
        if (cond) {
            console.log('✅ PASS: ' + msg);
            passed++;
        } else {
            console.error('❌ FAIL: ' + msg);
            failed++;
        }
    }

    try {
        console.log('\n=== 1. CHECK PRODUCTS PAGE HERO HEADING COLOR (#1b3d6f) ===');
        await page.goto('http://localhost/shreejigas/products', { waitUntil: 'networkidle' });
        
        const h1Text = await page.locator('.page-hero h1').innerText();
        console.log('Products h1 text:', h1Text.trim());
        assert(h1Text.includes('Our product range'), 'Products hero heading contains "Our product range"');

        const titleColor = await page.locator('.page-hero [data-preview="catalogue_title"]').evaluate(el => window.getComputedStyle(el).color);
        console.log('Title text computed color:', titleColor);
        // #1b3d6f in rgb is rgb(27, 61, 111)
        assert(titleColor === 'rgb(27, 61, 111)', 'Hero title text color is #1b3d6f (rgb(27, 61, 111))');

        const dotColor = await page.locator('.page-hero h1 span:last-child').evaluate(el => window.getComputedStyle(el).color);
        console.log('Dot span computed color:', dotColor);
        assert(dotColor === 'rgb(27, 61, 111)', 'Hero dot color is #1b3d6f (rgb(27, 61, 111))');

        console.log('\n=== 2. CHECK FOOTER COLOR (#1b3d6f) & SITEMAP LINK ABSENCE ===');
        const footerBg = await page.locator('.site-footer').evaluate(el => window.getComputedStyle(el).backgroundColor);
        console.log('Footer background computed color:', footerBg);
        assert(footerBg === 'rgb(27, 61, 111)', 'Footer background color is #1b3d6f (rgb(27, 61, 111))');

        const sitemapLinkCount = await page.locator('.site-footer a[href*="sitemap.xml"]').count();
        console.log('Footer sitemap link count:', sitemapLinkCount);
        assert(sitemapLinkCount === 0, 'Sitemap link has been removed from footer');

        const legalLinks = await page.locator('.footer-bottom__links a').allInnerTexts();
        console.log('Footer bottom links:', legalLinks);
        assert(legalLinks.length === 4, 'Footer bottom has exactly 4 statutory legal links');
        assert(legalLinks.includes('Privacy Policy'), 'Footer has Privacy Policy');
        assert(legalLinks.includes('Terms & Conditions'), 'Footer has Terms & Conditions');
        assert(legalLinks.includes('Safety & Compliance'), 'Footer has Safety & Compliance');
        assert(legalLinks.includes('Delivery Policy'), 'Footer has Delivery Policy');

        console.log('\n=== 3. CHECK HOMEPAGE PRODUCTS SECTION EYEBROW COLOR ===');
        await page.goto('http://localhost/shreejigas/', { waitUntil: 'networkidle' });
        const homeEyebrowColor = await page.locator('.section-products .section-heading .eyebrow').evaluate(el => window.getComputedStyle(el).color);
        console.log('Homepage product section eyebrow color:', homeEyebrowColor);
        assert(homeEyebrowColor === 'rgb(27, 61, 111)', 'Homepage product section eyebrow color is #1b3d6f (rgb(27, 61, 111))');

        console.log('\n=== 4. CHECK PUBLIC CONTACT & ABOUT PAGES ===');
        await page.goto('http://localhost/shreejigas/contact', { waitUntil: 'networkidle' });
        const contactH1 = await page.locator('h1').innerText();
        console.log('Contact page h1:', contactH1);
        assert(contactH1.length > 0, 'Contact page renders cleanly without 404');

        await page.goto('http://localhost/shreejigas/about', { waitUntil: 'networkidle' });
        const aboutH1 = await page.locator('h1').innerText();
        console.log('About page h1:', aboutH1);
        assert(aboutH1.length > 0, 'About page renders cleanly without 404');

        console.log('\n=== 5. CHECK PUBLIC SEO TAGS (TITLE, DESCRIPTION, OG TAGS) ===');
        await page.goto('http://localhost/shreejigas/', { waitUntil: 'networkidle' });
        const homeTitle = await page.evaluate(() => document.title);
        console.log('Home title:', homeTitle);
        assert(homeTitle.includes('Shree Ji Gas Industries'), 'Home page title pulls from seo_meta');

        const homeMetaDesc = await page.locator('meta[name="description"]').getAttribute('content');
        console.log('Home meta description:', homeMetaDesc);
        assert(homeMetaDesc && homeMetaDesc.includes('PESO-approved'), 'Home page description pulls from seo_meta');

        await page.goto('http://localhost/shreejigas/products', { waitUntil: 'networkidle' });
        const prodTitle = await page.evaluate(() => document.title);
        console.log('Products title:', prodTitle);
        assert(prodTitle.includes('Our Product Range'), 'Products page title pulls from seo_meta');

        console.log('\n=== 6. ADMIN LOGIN & VERIFY PAGES LISTING ===');
        // Sign in to admin
        await page.goto('http://localhost/shreejigas/admin/login', { waitUntil: 'networkidle' });
        if (await page.locator('input[name="identifier"]').count() > 0) {
            await page.fill('input[name="identifier"]', 'seo-test');
            await page.fill('input[name="password"]', testPassword);
            await Promise.all([
                page.waitForNavigation({ waitUntil: 'networkidle' }),
                page.click('button[type="submit"]')
            ]);
        }

        await page.goto('http://localhost/shreejigas/admin/content/pages', { waitUntil: 'networkidle' });
        const pageRows = await page.locator('.admin-table tbody tr').count();
        console.log('Admin /content/pages row count:', pageRows);
        assert(pageRows === 4, 'Pages section lists exactly 4 legal pages (contact & about removed)');

        const pageTitles = await page.locator('.admin-table tbody tr .table-title span').allInnerTexts();
        console.log('Page titles listed:', pageTitles);
        assert(!pageTitles.some(t => t.toLowerCase().includes('about') || t.toLowerCase().includes('partner')), 'About is not listed in /content/pages');
        assert(!pageTitles.some(t => t.toLowerCase().includes('contact') || t.toLowerCase().includes('equipment for you')), 'Contact is not listed in /content/pages');

        console.log('\n=== 7. ADMIN VERIFY SEO SETTINGS (11 CORE WEBSITE PAGES) ===');
        await page.goto('http://localhost/shreejigas/admin/content/seo_meta', { waitUntil: 'networkidle' });
        const seoRows = await page.locator('.admin-table tbody tr').count();
        console.log('Admin /content/seo_meta row count:', seoRows);
        assert(seoRows === 11, 'SEO settings lists exactly the 11 core website pages');

        const seoPageKeys = await page.locator('.admin-table tbody tr .cat-cell-desc').allInnerTexts();
        console.log('SEO page keys listed:', seoPageKeys);
        assert(seoPageKeys.includes('/'), 'SEO list includes Homepage (/)');
        assert(seoPageKeys.includes('/about'), 'SEO list includes /about');
        assert(seoPageKeys.includes('/contact'), 'SEO list includes /contact');
        assert(seoPageKeys.includes('/products'), 'SEO list includes /products');
        assert(seoPageKeys.includes('/use-cases'), 'SEO list includes /use-cases');
        assert(seoPageKeys.includes('/case-studies'), 'SEO list includes /case-studies');
        assert(seoPageKeys.includes('/blog'), 'SEO list includes /blog');
        assert(seoPageKeys.includes('/privacy'), 'SEO list includes /privacy');
        assert(seoPageKeys.includes('/terms'), 'SEO list includes /terms');
        assert(seoPageKeys.includes('/compliance'), 'SEO list includes /compliance');
        assert(seoPageKeys.includes('/shipping-policy'), 'SEO list includes /shipping-policy');

        // Verify NO individual entity clutter exists in the list
        const hasIndividualItems = seoPageKeys.some(k => k.startsWith('/products/') || k.startsWith('/blog/') || k.startsWith('/case-studies/') || k.startsWith('/use-cases/'));
        assert(!hasIndividualItems, 'No individual products, blogs, or cases pollute SEO settings list');

        // Check Add button absence
        const addSeoBtnCount = await page.locator('.admin-page-heading a.btn-primary:has-text("Add")').count();
        assert(addSeoBtnCount === 0, 'No "Add SEO entry" button in toolbar for fixed website routes');

        console.log('\n=== 8. EDIT SEO ENTRY & TEST LIVE GOOGLE SERP PREVIEW ===');
        // Click Edit on Homepage (/)
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('.admin-table tbody tr:first-child a.btn-action-pill[title="Edit SEO"]')
        ]);
        console.log('Current edit URL:', page.url());
        
        const serpTitleVisible = await page.locator('#serpLiveTitle').isVisible();
        assert(serpTitleVisible, 'Live Google SERP Preview card is visible');

        const initialSerpTitle = await page.locator('#serpLiveTitle').innerText();
        console.log('Initial SERP title preview:', initialSerpTitle);

        // Test typing in meta_title updates preview live
        await page.fill('input[name="meta_title"]', 'Updated Medical Oxygen & Gas Supply in Agra | Shree Ji Gas');
        const updatedSerpTitle = await page.locator('#serpLiveTitle').innerText();
        console.log('Updated SERP title preview:', updatedSerpTitle);
        assert(updatedSerpTitle === 'Updated Medical Oxygen & Gas Supply in Agra | Shree Ji Gas', 'Live SERP title updates instantaneously as user types');

        // Save changes
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button[type="submit"]')
        ]);
        console.log('Post-save URL:', page.url());
        assert(page.url().includes('/admin/content/seo_meta/'), 'SEO changes saved successfully');

        // Restore clean title
        await page.fill('input[name="meta_title"]', 'Shree Ji Gas Industries | Medical & Industrial Gas Supply in Agra');
        await Promise.all([
            page.waitForNavigation({ waitUntil: 'networkidle' }),
            page.click('button[type="submit"]')
        ]);

        console.log('\n=======================================');
        console.log(`TOTAL CHECKS: ${passed + failed}`);
        console.log(`PASSED: ${passed}`);
        console.log(`FAILED: ${failed}`);
        console.log('=======================================\n');

    } catch (err) {
        console.error('Test execution error:', err);
        failed++;
    } finally {
        await browser.close();
    }
})();
