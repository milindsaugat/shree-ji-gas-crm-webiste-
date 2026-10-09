const { chromium } = require('../tmp/browser-check/node_modules/playwright');
const fs = require('node:fs');
const path = require('node:path');
const crypto = require('node:crypto');
const { execFileSync } = require('node:child_process');
const assert = require('node:assert/strict');
const base = process.env.TEST_URL || 'http://localhost/shreejigas';
const output = path.resolve(__dirname, '../storage/checks');
fs.mkdirSync(output, {recursive:true});
const failures=[]; const passed=[]; const jsErrors=[];
const record=async(name,fn)=>{try{await fn();passed.push(name);console.log('PASS '+name);}catch(e){failures.push(name+': '+e.message);console.error('FAIL '+name+': '+e.message);}};
(async()=>{
 const browser=await chromium.launch({headless:true,channel:'chrome',args:['--disable-gpu']});
 const context=await browser.newContext({viewport:{width:1440,height:1000}});
 const page=await context.newPage();page.on('pageerror',e=>jsErrors.push(e.message));
 try {
 await record('Homepage renders with local images and one H1',async()=>{
  const r=await page.goto(base+'/',{waitUntil:'networkidle'});assert.equal(r.status(),200);assert.equal(await page.locator('h1').count(),1);assert.equal(await page.locator('h1').textContent(),'Dependable equipment.Exceptional care.');
  await page.locator('img[loading=lazy]').evaluateAll(images=>images.forEach(i=>i.loading='eager'));await page.waitForFunction(()=>[...document.images].every(i=>i.complete));assert.equal(await page.locator('img').evaluateAll(images=>images.filter(i=>i.naturalWidth===0).length),0);assert.ok(!(await page.content()).includes('<b>Warning</b>'));
  await page.screenshot({path:output+'/homepage-desktop.png',fullPage:true});
 });
 await record('Public routes and SEO endpoints',async()=>{
  for(const route of ['/products','/categories/medical-oxygen','/products/medical-oxygen-cylinder','/blog','/blog/a-better-equipment-enquiry','/case-studies','/use-cases','/use-cases/hospitals-critical-care','/about','/contact','/privacy','/catalogue','/sitemap.xml','/robots.txt']){const r=await context.request.get(base+route);assert.equal(r.status(),200,route);assert.ok(!/Fatal error|<b>Warning<\/b>/.test(await r.text()),route);}
  const r=await context.request.get(base+'/missing-page');assert.equal(r.status(),404);
  const sitemap=await context.request.get(base+'/sitemap.xml');assert.ok((await sitemap.text()).includes(base+'/products/medical-oxygen-cylinder'));
 });
 await record('Product search, category filtering, reset and sorting',async()=>{
  await page.goto(base+'/products',{waitUntil:'networkidle'});await page.locator('#productSearch').fill('surgical');assert.equal(await page.locator('#resultCount').textContent(),'2');
  await page.locator('#productSearch').fill('no-such-inventory');await page.locator('#noProducts').waitFor({state:'visible'});await page.locator('#resetEmpty').click();assert.equal(await page.locator('#resultCount').textContent(),'6');
  await page.locator('.category-filter').first().check();assert.equal(await page.locator('#resultCount').textContent(),'2');await page.locator('#clearFilters').click();await page.locator('#productSort').selectOption('az');assert.equal(await page.locator('#productGrid h3').first().textContent(),'Medical Gas Regulator');
  await page.screenshot({path:output+'/products-desktop.png',fullPage:true});
 });
 await record('Product enquiry modal, tab switching and validation',async()=>{
  await page.goto(base+'/products/medical-oxygen-cylinder',{waitUntil:'networkidle'});await page.getByRole('button',{name:'Enquire now',exact:true}).click();await page.locator('#enquiryModal').waitFor({state:'visible'});assert.equal(await page.locator('#modal-product').inputValue(),'Medical Oxygen Cylinder');await page.locator('#enquiryModal .btn-close').click();await page.locator('#enquiryModal').waitFor({state:'hidden'});await page.getByRole('tab',{name:'Applications',exact:true}).click();await page.locator('#panel-applications').waitFor({state:'visible'});assert.equal(await page.locator('#panel-description').isVisible(),false);
 });
 await record('Enquiry server rejects missing CSRF and invalid input',async()=>{
  let r=await context.request.post(base+'/enquiry',{form:{name:'No token'}});assert.equal(r.status(),403);await page.goto(base+'/contact',{waitUntil:'networkidle'});const token=await page.locator('.contact-form-panel input[name=csrf]').inputValue();r=await context.request.post(base+'/enquiry',{form:{csrf:token,name:'X',phone:'1',message:'short'},headers:{Accept:'application/json'}});assert.equal(r.status(),422);
 });
 await record('Enquiry persists with privacy consent',async()=>{
  await page.locator('#contact-name').fill('Browser smoke test');await page.locator('#contact-phone').fill('+91 9000000000');await page.locator('#contact-email').fill('browser-smoke@example.test');await page.locator('#contact-product').selectOption('Medical Oxygen Cylinder');await page.locator('#contact-message').fill('Automated test enquiry. Please remove this test record.');await page.locator('.contact-form-panel input[name=consent]').check();await page.locator('.contact-form-panel button[type=submit]').click();await page.locator('.contact-form-panel .form-status').filter({hasText:'Your enquiry has been received'}).waitFor();
 });
 await record('Private directories and upload scripts are blocked',async()=>{
  for(const route of ['/config/config.php','/storage/preview.sqlite','/database/seed.php','/schema.sql','/tests/browser-smoke.cjs'])assert.equal((await context.request.get(base+route)).status(),403,route);
  const r=await context.request.get(base+'/admin',{maxRedirects:0});assert.equal(r.status(),302);assert.ok(r.headers().location.endsWith('/admin/login'));
 });
 await record('Mobile layout, offcanvas and product filters',async()=>{
  await page.setViewportSize({width:390,height:844});await page.goto(base+'/',{waitUntil:'networkidle'});assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),'Homepage horizontal overflow');await page.screenshot({path:output+'/homepage-mobile.png',fullPage:true});await page.getByRole('button',{name:'Open navigation',exact:true}).click();await page.locator('#mobileMenu').waitFor({state:'visible'});await page.locator('#mobileMenu').getByRole('link',{name:'Products',exact:true}).click();await page.waitForURL('**/products');assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),'Catalogue horizontal overflow');await page.screenshot({path:output+'/products-mobile.png',fullPage:true});
 });
 if(process.env.TEST_ADMIN==='1'){
  const password=crypto.randomBytes(18).toString('base64url');
  execFileSync('C:\\xampp\\php\\php.exe',[path.resolve(__dirname,'fixture.php'),'create-admin',password],{stdio:'pipe',env:{...process.env,ALLOW_LOCAL_DB_TESTS:'1'}});
  await record('Admin authentication, dashboard and content editors',async()=>{
   await page.setViewportSize({width:1440,height:1000});await page.goto(base+'/admin/login',{waitUntil:'networkidle'});await page.locator('#adminEmail').fill('browser-admin@example.test');await page.locator('#adminPassword').fill(password);await page.getByRole('button',{name:'Sign in',exact:true}).click();await page.waitForURL('**/admin');await page.screenshot({path:output+'/admin-dashboard.png',fullPage:true});
   for(const route of ['/admin/sections','/admin/content/products','/admin/content/products/1','/admin/content/blog_posts/1','/admin/content/categories','/admin/content/seo_meta/new','/admin/media','/admin/settings','/admin/enquiries']){await page.goto(base+route,{waitUntil:'networkidle'});assert.ok(!/Fatal error|<b>Warning<\/b>/.test(await page.content()),route);}
   await page.goto(base+'/admin/enquiries',{waitUntil:'networkidle'});assert.equal(await page.locator('.enquiry-item').filter({hasText:'Browser smoke test'}).count(),1);
  });
  await record('Homepage preview updates without publishing',async()=>{
   await page.goto(base+'/admin/sections/hero',{waitUntil:'networkidle'});await page.locator('#field-heading').fill('Preview smoke test.');await page.waitForTimeout(900);await page.frameLocator('#sectionPreview').locator('h1').filter({hasText:'Preview smoke test.'}).waitFor();const publicResponse=await context.request.get(base+'/');assert.ok((await publicResponse.text()).includes('Dependable equipment.'));await page.screenshot({path:output+'/admin-section-editor.png',fullPage:true});await page.locator('#field-heading').fill('Dependable equipment.');await page.locator('#sectionForm button[type=submit]').click();await page.getByText('All changes saved',{exact:true}).waitFor();
  });
  await record('Product and inline SEO save together',async()=>{
   await page.goto(base+'/admin/content/products/1',{waitUntil:'networkidle'});await page.getByRole('button',{name:'Save product',exact:true}).click();await page.waitForURL('**/admin/content/products/1');await page.locator('.admin-alert').filter({hasText:'Product saved.'}).waitFor();assert.equal(await page.locator('.admin-alert--error').count(),0);
  });
  await record('Admin mobile navigation and section editor',async()=>{
   await page.setViewportSize({width:390,height:844});await page.goto(base+'/admin/sections/hero',{waitUntil:'networkidle'});assert.ok(await page.evaluate(()=>document.documentElement.scrollWidth<=innerWidth),'Admin overflow');await page.screenshot({path:output+'/admin-mobile.png',fullPage:true});await page.getByRole('button',{name:'Open admin navigation'}).click();assert.ok((await page.locator('#adminSidebar').getAttribute('class')).includes('is-open'));await page.getByRole('button',{name:'Close navigation',exact:true}).click();
  });
 }
 }finally{
  if(process.env.TEST_ADMIN==='1')execFileSync('C:\\xampp\\php\\php.exe',[path.resolve(__dirname,'fixture.php'),'cleanup'],{stdio:'pipe',env:{...process.env,ALLOW_LOCAL_DB_TESTS:'1'}});
  await browser.close();
 }
 if(jsErrors.length)failures.push('Browser JavaScript errors: '+[...new Set(jsErrors)].join('; '));
 fs.writeFileSync(output+'/results.json',JSON.stringify({passed,failures,jsErrors},null,2));
 console.log(JSON.stringify({passed:passed.length,failures},null,2));if(failures.length)process.exitCode=1;
})().catch(e=>{console.error(e);process.exitCode=1;});
