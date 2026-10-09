const { chromium } = require('../tmp/browser-check/node_modules/playwright');

(async () => {
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
        console.log('--- Navigating to /about ---');
        await page.goto('http://localhost/shreejigas/about', { waitUntil: 'networkidle' });

        // 1. Check EQUIPMENT WITH A PURPOSE is removed
        const purposeLabelCount = await page.locator('.company-visual-label').count();
        assert(purposeLabelCount === 0, '.company-visual-label element is completely removed from About page');

        const pageContent = await page.content();
        assert(!pageContent.includes('EQUIPMENT WITH A PURPOSE'), 'String "EQUIPMENT WITH A PURPOSE" is not found on About page');

        // Screenshot hero section
        const heroVisual = page.locator('.company-hero-visual');
        await heroVisual.screenshot({ path: 'scripts/about-hero-visual.png' });
        console.log('Hero visual screenshot saved.');

        // 2. Check product cards in #company-range
        const companyRange = page.locator('#company-range');
        const rangeExists = await companyRange.count() > 0;
        assert(rangeExists, '#company-range section exists');

        const oldCategoryCards = await companyRange.locator('.company-range-card').count();
        assert(oldCategoryCards === 0, 'Old static .company-range-card category cards are removed');

        const productCards = companyRange.locator('.product-card');
        const productCount = await productCards.count();
        assert(productCount > 0, `#company-range has ${productCount} dynamic .product-card items`);

        // Check product card structure matches product page
        const firstCard = productCards.first();
        const hasImage = await firstCard.locator('.product-card__image img').count() > 0;
        assert(hasImage, 'Product card contains product image');

        const firstTitle = await firstCard.locator('h3 a').innerText();
        assert(firstTitle.length > 0, `First product card title is dynamic: "${firstTitle}"`);

        const firstLink = await firstCard.locator('.product-card__image').getAttribute('href');
        assert(firstLink.includes('/products/'), `Product card links to product detail: ${firstLink}`);

        const hasEyebrow = await firstCard.locator('.product-card__content .eyebrow').count() > 0;
        assert(hasEyebrow, 'Product card has category eyebrow');

        const hasExploreLink = await firstCard.locator('.text-link').innerText();
        assert(hasExploreLink.toLowerCase().includes('explore'), `Product card has explore link: "${hasExploreLink}"`);

        // Take screenshot of #company-range
        await companyRange.screenshot({ path: 'scripts/about-product-cards.png' });
        console.log('#company-range product cards screenshot saved.');

        // 3. Test mobile viewport
        console.log('--- Testing Mobile Viewport (375x812) ---');
        await page.setViewportSize({ width: 375, height: 812 });
        await page.waitForTimeout(500);

        const mobileCards = await companyRange.locator('.product-card').count();
        assert(mobileCards === productCount, `Mobile viewport renders all ${mobileCards} product cards cleanly`);
        await companyRange.screenshot({ path: 'scripts/about-product-cards-mobile.png' });
        console.log('Mobile screenshot saved.');

    } catch (err) {
        console.error('Test execution error:', err);
        failed++;
    } finally {
        await browser.close();
        console.log(`\n========================================`);
        console.log(`RESULTS: ${passed} PASSED, ${failed} FAILED`);
        console.log(`========================================`);
        process.exit(failed > 0 ? 1 : 0);
    }
})();
