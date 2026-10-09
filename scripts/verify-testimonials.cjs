const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');
const { execFileSync } = require('node:child_process');

const base = process.env.TEST_URL || 'http://localhost/shreejigas';
const output = path.resolve(__dirname, '../storage/checks');
fs.mkdirSync(output, { recursive: true });

(async () => {
  const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });
  const context = await browser.newContext({ viewport: { width: 1440, height: 900 } });
  const page = await context.newPage();

  const results = [];

  try {
    console.log('Testing Home Page Testimonial Slider & Avatars...');
    await page.goto(base + '/', { waitUntil: 'networkidle' });

    // Scroll to testimonial section
    const testimonialSec = page.locator('#testimonials');
    await testimonialSec.scrollIntoViewIfNeeded();
    await page.waitForTimeout(600);

    // Verify avatar images exist and render
    const avatarImages = page.locator('#testimonials .testimonial-avatar img');
    const avatarCount = await avatarImages.count();
    console.log(`Found ${avatarCount} avatar images in testimonial slider.`);

    const firstAvatarSrc = await avatarImages.first().getAttribute('src');
    console.log('First avatar src:', firstAvatarSrc);

    // Check first slide person name
    const activeSlide = page.locator('#testimonials .swiper-slide-active');
    const personName1 = await activeSlide.locator('.testimonial-person strong').textContent();
    console.log('Slide 1 person:', personName1.trim());

    // Screenshot initial slide
    const sliderWrap = page.locator('#testimonials .testimonial-section');
    await sliderWrap.screenshot({ path: path.join(output, 'testimonial-home-slide-1.png') });
    console.log('Saved testimonial-home-slide-1.png');

    // Click next button
    console.log('Clicking Next Button (.testimonial-next)...');
    const nextBtn = page.locator('#testimonials .testimonial-next');
    await nextBtn.click();
    await page.waitForTimeout(600);

    const activeSlide2 = page.locator('#testimonials .swiper-slide-active');
    const personName2 = await activeSlide2.locator('.testimonial-person strong').textContent();
    console.log('Slide 2 person after Next click:', personName2.trim());

    await sliderWrap.screenshot({ path: path.join(output, 'testimonial-home-slide-2.png') });
    console.log('Saved testimonial-home-slide-2.png');

    if (personName1.trim() !== personName2.trim()) {
      results.push('PASS: Testimonial slider successfully advanced on .testimonial-next click!');
    } else {
      results.push('FAIL: Testimonial slider did not advance on .testimonial-next click.');
    }

    // Click next button again
    console.log('Clicking Next Button again...');
    await nextBtn.click();
    await page.waitForTimeout(600);

    const activeSlide3 = page.locator('#testimonials .swiper-slide-active');
    const personName3 = await activeSlide3.locator('.testimonial-person strong').textContent();
    console.log('Slide 3 person:', personName3.trim());

    // Click Prev button
    console.log('Clicking Prev Button (.testimonial-prev)...');
    const prevBtn = page.locator('#testimonials .testimonial-prev');
    await prevBtn.click();
    await page.waitForTimeout(600);

    const activeSlideBack = page.locator('#testimonials .swiper-slide-active');
    const personNameBack = await activeSlideBack.locator('.testimonial-person strong').textContent();
    console.log('Slide person after Prev click:', personNameBack.trim());

    if (personNameBack.trim() === personName2.trim()) {
      results.push('PASS: Testimonial slider successfully moved back on .testimonial-prev click!');
    } else {
      results.push('FAIL: Testimonial slider did not move back as expected.');
    }

    // Now test Admin Panel
    console.log('Testing Admin Panel Testimonials Management...');
    const password = crypto.randomBytes(18).toString('base64url');
    execFileSync('C:\\xampp\\php\\php.exe', [path.resolve(__dirname, '../tests/fixture.php'), 'create-admin', password], {
      stdio: 'pipe',
      env: { ...process.env, ALLOW_LOCAL_DB_TESTS: '1' }
    });

    await page.goto(base + '/admin/login', { waitUntil: 'networkidle' });
    await page.locator('#adminEmail').fill('browser-admin@example.test');
    await page.locator('#adminPassword').fill(password);
    await page.getByRole('button', { name: 'Sign in', exact: true }).click();
    await page.waitForURL('**/admin');

    // 1. Check Testimonials List in Admin
    await page.goto(base + '/admin/content/testimonials', { waitUntil: 'networkidle' });
    await page.screenshot({ path: path.join(output, 'admin-testimonials-list.png') });
    console.log('Saved admin-testimonials-list.png');

    // 2. Check Testimonial Edit (id 1)
    await page.goto(base + '/admin/content/testimonials/1', { waitUntil: 'networkidle' });
    const avatarInput = page.locator('#input-avatar');
    const avatarValue = await avatarInput.inputValue();
    console.log('Admin Testimonial 1 avatar input value:', avatarValue);

    const avatarPreviewImg = page.locator('.asset-field-preview-wrap img.asset-preview');
    const previewVisible = await avatarPreviewImg.isVisible();
    console.log('Avatar preview image visible in edit form:', previewVisible);

    await page.screenshot({ path: path.join(output, 'admin-testimonial-edit-1.png') });
    console.log('Saved admin-testimonial-edit-1.png');

    // 3. Open Media Picker from avatar field
    const pickerBtn = page.locator('.asset-field .asset-picker-button').first();
    await pickerBtn.click();
    await page.waitForTimeout(400);

    const mediaPickerModal = page.locator('#mediaPicker');
    const isModalOpen = await mediaPickerModal.evaluate(el => el.open);
    console.log('Media library modal opened from avatar picker:', isModalOpen);

    await page.screenshot({ path: path.join(output, 'admin-testimonial-avatar-media-modal.png') });
    console.log('Saved admin-testimonial-avatar-media-modal.png');

    if (isModalOpen) {
      // Close media modal
      await page.locator('#mediaPicker [data-close-dialog]').click();
      await page.waitForTimeout(300);
    }

    // 4. Check Section Edit Testimonial Quick Modal
    await page.goto(base + '/admin/sections/testimonials', { waitUntil: 'networkidle' });
    const addTestimonialBtn = page.locator('#openQuickTestimonialBtn');
    if (await addTestimonialBtn.isVisible()) {
      await addTestimonialBtn.click();
      await page.waitForTimeout(500);
      const quickModal = page.locator('#quickTestimonialModal');
      const isQuickOpen = await quickModal.evaluate(el => el.open);
      console.log('Quick testimonial modal opened:', isQuickOpen);
      if (isQuickOpen) {
        await page.screenshot({ path: path.join(output, 'admin-section-quick-testimonial-modal.png') });
        console.log('Saved admin-section-quick-testimonial-modal.png');
      }
    }

    console.log('\n--- VERIFICATION SUMMARY ---');
    results.forEach(r => console.log(r));

  } catch (err) {
    console.error('Test error:', err);
  } finally {
    await browser.close();
  }
})();
