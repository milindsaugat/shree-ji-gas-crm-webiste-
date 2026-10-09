const {chromium}=require('../tmp/browser-check/node_modules/playwright');
const {execFileSync}=require('node:child_process');
const assert=require('node:assert/strict');const path=require('node:path');const crypto=require('node:crypto');const fs=require('node:fs');
const base=process.env.TEST_URL||'http://localhost/shreejigas';
(async()=>{
 const password=crypto.randomBytes(18).toString('base64url');
 execFileSync('C:\\xampp\\php\\php.exe',[path.resolve(__dirname,'fixture.php'),'create-admin',password],{env:{...process.env,ALLOW_LOCAL_DB_TESTS:'1'}});
 const browser=await chromium.launch({headless:true,channel:'chrome'});const context=await browser.newContext();const page=await context.newPage();let mediaId;
 try{
  await page.goto(base+'/admin/login');await page.locator('#adminEmail').fill('browser-admin@example.test');await page.locator('#adminPassword').fill(password);await page.getByRole('button',{name:'Sign in',exact:true}).click();await page.waitForURL('**/admin');
  const token=await page.locator('meta[name=csrf-token]').getAttribute('content');
  const upload=async(name,mimeType,buffer,alt)=>context.request.post(base+'/admin/media',{headers:{Accept:'application/json','X-CSRF-Token':token},multipart:{image_alt:alt,file:{name,mimeType,buffer}}});
  let r=await upload('not-an-image.php','application/x-httpd-php',Buffer.from('<?php echo "do not execute";'),'Test PHP rejection');assert.equal(r.status(),422);console.log('PASS Script upload rejected by actual MIME type');
  r=await upload('test.webp','image/webp',fs.readFileSync(path.resolve(__dirname,'../assets/images/equipment.webp')),'');assert.equal(r.status(),422);console.log('PASS Missing image description rejected');
  r=await upload('browser-test-equipment.webp','image/webp',fs.readFileSync(path.resolve(__dirname,'../assets/images/equipment.webp')),'Browser upload test equipment');assert.equal(r.status(),200);const data=await r.json();assert.equal(data.ok,true);assert.equal(data.media.type,'image/webp');mediaId=data.media.id;
  const fileResponse=await context.request.get(base+'/'+data.media.path);assert.equal(fileResponse.status(),200);assert.equal(fileResponse.headers()['content-type'],'image/webp');
  await page.goto(base+'/'+data.media.path);const dimensions=await page.locator('img').evaluate(i=>({width:i.naturalWidth,height:i.naturalHeight}));assert.equal(dimensions.width,1200);assert.equal(dimensions.height,800);console.log('PASS Image uploaded, resized to 1200px and served as WebP');
  const deleteResponse=await context.request.post(base+'/admin/media',{form:{csrf:token,action:'delete',id:String(mediaId)}});assert.equal(deleteResponse.status(),200);assert.equal((await context.request.get(base+'/'+data.media.path)).status(),404);mediaId=null;console.log('PASS Unreferenced media deletion removes file');
  await page.goto(base+'/admin/content/products/1');const original=await page.locator('[name=name]').inputValue();await page.locator('[name=name]').fill('INVALID SEO TRANSACTION TEST');await page.locator('details.seo-details').evaluate(el=>el.open=true);await page.locator('[name="seo[canonical_url]"]').fill('javascript:alert(1)');await page.getByRole('button',{name:'Save product',exact:true}).click();await page.locator('.admin-alert--error').waitFor();await page.goto(base+'/admin/content/products/1');assert.equal(await page.locator('[name=name]').inputValue(),original);console.log('PASS Invalid SEO URL rolls back the product update');
 }finally{
  if(mediaId){const token=await page.locator('meta[name=csrf-token]').getAttribute('content').catch(()=>null);if(token)await context.request.post(base+'/admin/media',{form:{csrf:token,action:'delete',id:String(mediaId)}});}
  await browser.close();execFileSync('C:\\xampp\\php\\php.exe',[path.resolve(__dirname,'fixture.php'),'cleanup'],{env:{...process.env,ALLOW_LOCAL_DB_TESTS:'1'}});
 }
})().catch(e=>{console.error(e);process.exitCode=1;});
