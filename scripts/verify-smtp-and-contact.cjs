const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const { execFileSync } = require('node:child_process');

function setupAdminFixture() {
    console.log('Ensuring admin fixture...');
    try {
        const phpCode = `require 'config/config.php'; require 'config/database.php'; $pdo = Database::get(); $pdo->prepare('DELETE FROM admin_users WHERE username = ?')->execute(['shreeji-admin-test']); $pdo->prepare('INSERT INTO admin_users (name,username,email,password_hash) VALUES (?,?,?,?)')->execute(['Shree Ji Admin','shreeji-admin-test','admin-test@shreejigas.com',password_hash('Password123456!',PASSWORD_BCRYPT)]); echo 'Admin test fixture ready.' . PHP_EOL;`;
        execFileSync('C:\\xampp\\php\\php.exe', ['-r', phpCode], { stdio: 'inherit' });
    } catch (err) {
        console.error('Fixture setup error:', err);
    }
}

(async () => {
    setupAdminFixture();
    const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });
    const page = await browser.newPage({ viewport: { width: 1440, height: 900 } });

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
        // ==========================================
        // TEST 1: CONTACT FORM SUBMISSION WITH SPINNER & INSTANT CONFIRMATION
        // ==========================================
        console.log('\n--- 1. Testing Contact Form Submission & UI Loader ---');
        await page.goto('http://localhost/shreejigas/contact', { waitUntil: 'networkidle' });

        await page.fill('#contact-name', 'Milind Ratan Saugat');
        await page.fill('#contact-phone', '+919411404446');
        await page.fill('#contact-email', 'milindsaugat1122@gmail.com');
        await page.selectOption('#contact-product', 'Medical Oxygen Cylinder');
        await page.fill('#contact-message', 'Testing medical oxygen cylinder supply enquiry.');
        await page.check('input[name="consent"]');

        const submitBtn = page.locator('.contact-form-panel button[type="submit"]');

        console.log('Clicking submit and checking button loader...');
        // Start submission and catch spinner state immediately
        const submitPromise = page.click('.contact-form-panel button[type="submit"]');
        
        // Check button gets disabled or has spinner
        await page.waitForTimeout(50);
        const isDisabled = await submitBtn.evaluate(el => el.disabled);
        const hasSpinner = await submitBtn.evaluate(el => !!el.querySelector('.btn-spinner'));
        assert(isDisabled, 'Submit button is disabled during submission');
        assert(hasSpinner, 'Submit button displays spinning loader (.btn-spinner)');

        await submitPromise;

        // Wait for response and success card
        await page.waitForSelector('.form-success-card', { timeout: 12000 });
        const successCardText = await page.locator('.form-success-card').innerText();
        assert(successCardText.includes('Thank you, Milind Ratan Saugat!'), `Success card shows personalized Thank You: "${successCardText.split('\n')[0]}"`);
        assert(successCardText.includes('enquiry has been received'), 'Success card confirms enquiry receipt');

        await page.screenshot({ path: 'scripts/contact-success-card.png' });
        console.log('Saved contact success screenshot.');

        // ==========================================
        // TEST 2: CHECK DB RECORD FOR EMAIL_SENT
        // ==========================================
        console.log('\n--- 2. Checking Database Enquiry Record ---');
        const phpCheck = `require 'config/config.php'; require 'config/database.php'; $pdo=Database::get(); $row=$pdo->query('SELECT id, name, email, email_sent FROM enquiries ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_ASSOC); echo json_encode($row);`;
        const dbResult = JSON.parse(execFileSync('C:\\xampp\\php\\php.exe', ['-r', phpCheck], { encoding: 'utf8' }));
        console.log('Latest enquiry in DB:', dbResult);
        assert(dbResult.name === 'Milind Ratan Saugat', 'Enquiry name in DB matches');
        assert(Number(dbResult.email_sent) === 1, 'Enquiry marked as email_sent = 1 (dispatched via SMTP)');

        // ==========================================
        // TEST 3: ADMIN SMTP SETTINGS & TEST DISPATCH
        // ==========================================
        console.log('\n--- 3. Testing Admin Settings SMTP Gateway & Live Test Dispatch ---');
        await page.goto('http://localhost/shreejigas/admin/login');
        await page.fill('#adminEmail', 'shreeji-admin-test');
        await page.fill('#adminPassword', 'Password123456!');
        await page.click('button[type="submit"]');
        await page.waitForURL('**/admin**');

        await page.goto('http://localhost/shreejigas/admin/settings#smtp', { waitUntil: 'networkidle' });
        await page.click('#tabBtnSmtp');
        await page.waitForTimeout(300);

        // Check SMTP settings form fields exist
        assert(await page.locator('#smtpHost').count() > 0, 'SMTP Host input exists');
        assert(await page.locator('#smtpPort').count() > 0, 'SMTP Port input exists');
        assert(await page.locator('#smtpUser').count() > 0, 'SMTP User input exists');
        assert(await page.locator('#smtpPass').count() > 0, 'SMTP Pass input exists');

        // Test sending test email from Admin UI
        console.log('Sending test email via Admin UI button...');
        await page.fill('#smtpTestRecipient', 'devmilind12@gmail.com');
        await page.click('#btnRunSmtpTest');

        await page.waitForSelector('#smtpTestFeedback.smtp-test-feedback--success', { timeout: 25000 });
        const feedbackText = await page.locator('#smtpTestFeedback').innerText();
        console.log('Admin feedback:', feedbackText);
        assert(feedbackText.includes('Success') || feedbackText.includes('successfully'), `Admin SMTP Test dispatches email successfully: "${feedbackText}"`);

        await page.screenshot({ path: 'scripts/admin-smtp-verified.png' });
        console.log('Saved admin SMTP test screenshot.');

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
