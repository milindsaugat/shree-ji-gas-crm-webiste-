const fs = require('fs');
const path = require('path');
const { chromium } = require('../tmp/browser-check/node_modules/playwright');

(async () => {
  const browser = await chromium.launch({ headless: true, channel: 'chrome' });
  const page = await browser.newPage();
  
  const imgPath = path.resolve(__dirname, '../Shree Ji Gas Industries Medical Seal.png');
  const imgBase64 = fs.readFileSync(imgPath).toString('base64');
  
  await page.setContent(`
    <!DOCTYPE html>
    <html>
      <head><style>body { margin:0; background: transparent; }</style></head>
      <body>
        <img id="logo" src="data:image/png;base64,${imgBase64}">
      </body>
    </html>
  `);

  const info = await page.evaluate(async () => {
    const img = document.getElementById('logo');
    await img.decode();
    const canvas = document.createElement('canvas');
    canvas.width = img.naturalWidth;
    canvas.height = img.naturalHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(img, 0, 0);

    // Check corners
    const corner = ctx.getImageData(5, 5, 1, 1).data;
    return {
      w: img.naturalWidth,
      h: img.naturalHeight,
      corner: Array.from(corner)
    };
  });
  console.log('Image details:', info);

  // Generate:
  // 1. High-res logo in assets/images/logo.png (512x512)
  // 2. High-res WebP in assets/images/logo.webp (512x512)
  // 3. Crisp Favicon in assets/images/favicon.png (64x64, 32x32, 180x180 for apple-touch-icon)
  // 4. Update assets/images/logo.svg if needed or keep PNG / WebP as primary

  await page.evaluate(() => {
    const img = document.getElementById('logo');
    // Function to generate resized canvas with crisp anti-aliasing
    window.resizeLogo = (size) => {
      const c = document.createElement('canvas');
      c.width = size;
      c.height = size;
      const ctx = c.getContext('2d');
      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';
      ctx.drawImage(img, 0, 0, size, size);
      return c.toDataURL('image/png');
    };

    window.resizeLogoWebp = (size) => {
      const c = document.createElement('canvas');
      c.width = size;
      c.height = size;
      const ctx = c.getContext('2d');
      ctx.imageSmoothingEnabled = true;
      ctx.imageSmoothingQuality = 'high';
      ctx.drawImage(img, 0, 0, size, size);
      return c.toDataURL('image/webp', 0.95);
    };
  });

  const saveBase64 = (dataUrl, filePath) => {
    const base64Data = dataUrl.replace(/^data:image\/\w+;base64,/, '');
    fs.writeFileSync(filePath, Buffer.from(base64Data, 'base64'));
    console.log('Saved:', filePath);
  };

  // 1. Standard logo: assets/images/logo.png (512x512)
  const logo512 = await page.evaluate(() => window.resizeLogo(512));
  saveBase64(logo512, path.resolve(__dirname, '../assets/images/logo.png'));

  // 2. Webp version: assets/images/logo.webp (512x512)
  const logoWebp = await page.evaluate(() => window.resizeLogoWebp(512));
  saveBase64(logoWebp, path.resolve(__dirname, '../assets/images/logo.webp'));

  // 3. Favicon standard 32x32: assets/images/favicon.png
  const favicon32 = await page.evaluate(() => window.resizeLogo(32));
  saveBase64(favicon32, path.resolve(__dirname, '../assets/images/favicon.png'));

  // 4. Favicon 192x192: assets/images/favicon-192.png
  const favicon192 = await page.evaluate(() => window.resizeLogo(192));
  saveBase64(favicon192, path.resolve(__dirname, '../assets/images/favicon-192.png'));

  // 5. Apple Touch Icon 180x180: assets/images/apple-touch-icon.png
  const appleTouch = await page.evaluate(() => window.resizeLogo(180));
  saveBase64(appleTouch, path.resolve(__dirname, '../assets/images/apple-touch-icon.png'));

  // Also put a copy in root favicon.ico / favicon.png
  saveBase64(favicon32, path.resolve(__dirname, '../favicon.png'));

  await browser.close();
  console.log('All logo assets successfully generated!');
})();
