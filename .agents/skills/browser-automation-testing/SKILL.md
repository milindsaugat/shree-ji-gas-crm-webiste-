---
name: browser-automation-testing
description: >-
  Use this skill to create and maintain automated Playwright browser tests, visual regression checks,
  mobile viewport validation, form submit assertions, network idle checks, and smoke test suites.
---

# Browser Automation & Testing Skill

This skill guides the creation and execution of reliable end-to-end browser smoke test suites using Playwright.

## Testing Guidelines

1. **Deterministic Test Execution**:
   - Always launch Playwright with headless Chromium:
     ```javascript
     const browser = await chromium.launch({ headless: true, channel: 'chrome', args: ['--disable-gpu'] });
     ```
   - Use `waitUntil: 'networkidle'` or explicit locator wait states (`await locator.waitFor({ state: 'visible' })`) to prevent flaky race conditions.
   - Avoid hardcoded `sleep` or arbitrary pauses; wait on DOM state or network responses.

2. **Mobile Viewport & Overflow Assertions**:
   - Test both Desktop (`1440x900`) and Mobile (`390x844` viewport).
   - Assert zero horizontal scrollbar overflow:
     ```javascript
     assert.ok(await page.evaluate(() => document.documentElement.scrollWidth <= innerWidth), 'Horizontal overflow detected');
     ```

3. **Form & Interactive Flow Assertions**:
   - Verify input filling, dropdown selection, modal opening, checkbox ticking, form submission, and server status feedback:
     ```javascript
     await page.locator('#contact-name').fill('Test Lead');
     await page.locator('.contact-form-panel input[name=consent]').check();
     await page.locator('.contact-form-panel button[type=submit]').click();
     await page.locator('.contact-form-panel .form-status').filter({ hasText: 'Your enquiry has been received' }).waitFor();
     ```

4. **Security Boundary Smoke Checks**:
   - Assert that private system directories and files (`config/config.php`, `database/seed.php`, `schema.sql`, `tests/`) return `403 Forbidden` over HTTP.
   - Assert that unauthorized access to `/admin` returns `302 Found` redirecting to `/admin/login`.
