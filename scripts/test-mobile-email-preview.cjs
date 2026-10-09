const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const path = require('node:path');
const fs = require('node:fs');
const { execFileSync } = require('node:child_process');

(async () => {
    console.log('Generating updated enquiry email HTML via PHP...');
    const phpScript = `
        require 'config/config.php';
        require 'config/database.php';
        require 'helpers/functions.php';
        require 'models/Setting.php';
        require 'helpers/EmailTemplate.php';

        $data = [
            'name' => 'Milind Ratan Saugat',
            'phone' => '+91 9411404446',
            'email' => 'milindsaugat1122@gmail.com',
            'product' => 'Medical Oxygen Cylinder',
            'message' => "We require 10 B-type and 5 D-type medical oxygen cylinders for our emergency response unit in Agra. Please provide pricing and PESO compliance documentation."
        ];
        $email = EmailTemplate::enquiry($data, 108);
        echo $email['html'];
    `;

    const rawHtml = execFileSync('C:\\xampp\\php\\php.exe', ['-r', phpScript], { encoding: 'utf8' });
    
    // Replace inline cid:logo@shreejigas with base64 for browser preview
    const logoBase64 = fs.readFileSync(path.join(__dirname, '../assets/images/logo.png')).toString('base64');
    const previewHtml = rawHtml.replace('cid:logo@shreejigas', `data:image/png;base64,${logoBase64}`);

    fs.writeFileSync(path.join(__dirname, 'preview-rendered.html'), previewHtml);
    console.log('Saved rendered preview HTML.');

    const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });

    // 1. Mobile Dark Mode (iPhone viewport 390x844, dark color scheme)
    console.log('Rendering Mobile Dark Mode (390x844)...');
    const mobileDarkContext = await browser.newContext({
        viewport: { width: 390, height: 844 },
        deviceScaleFactor: 2,
        colorScheme: 'dark'
    });
    const mobileDarkPage = await mobileDarkContext.newPage();
    await mobileDarkPage.setContent(previewHtml, { waitUntil: 'load' });
    await mobileDarkPage.waitForTimeout(500);
    await mobileDarkPage.screenshot({ path: 'scripts/mobile-email-dark.png', fullPage: true });
    console.log('Saved scripts/mobile-email-dark.png');
    await mobileDarkContext.close();

    // 2. Mobile Light Mode (iPhone viewport 390x844, light color scheme)
    console.log('Rendering Mobile Light Mode (390x844)...');
    const mobileLightContext = await browser.newContext({
        viewport: { width: 390, height: 844 },
        deviceScaleFactor: 2,
        colorScheme: 'light'
    });
    const mobileLightPage = await mobileLightContext.newPage();
    await mobileLightPage.setContent(previewHtml, { waitUntil: 'load' });
    await mobileLightPage.waitForTimeout(500);
    await mobileLightPage.screenshot({ path: 'scripts/mobile-email-light.png', fullPage: true });
    console.log('Saved scripts/mobile-email-light.png');
    await mobileLightContext.close();

    // 3. Desktop Dark Mode (750x900)
    console.log('Rendering Desktop Dark Mode (750x900)...');
    const desktopDarkContext = await browser.newContext({
        viewport: { width: 750, height: 900 },
        deviceScaleFactor: 1,
        colorScheme: 'dark'
    });
    const desktopDarkPage = await desktopDarkContext.newPage();
    await desktopDarkPage.setContent(previewHtml, { waitUntil: 'load' });
    await desktopDarkPage.waitForTimeout(500);
    await desktopDarkPage.screenshot({ path: 'scripts/desktop-email-dark.png', fullPage: true });
    console.log('Saved scripts/desktop-email-dark.png');
    await desktopDarkContext.close();

    await browser.close();
    console.log('All screenshots rendered successfully!');
})();
