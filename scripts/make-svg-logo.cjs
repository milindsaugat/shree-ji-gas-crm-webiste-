const fs = require('fs');
const path = require('path');

const pngPath = path.resolve(__dirname, '../assets/images/logo.png');
const svgPath = path.resolve(__dirname, '../assets/images/logo.svg');

const b64 = fs.readFileSync(pngPath).toString('base64');
const svgContent = `<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" width="512" height="512">
  <image width="512" height="512" xlink:href="data:image/png;base64,${b64}" />
</svg>
`;

fs.writeFileSync(svgPath, svgContent);
console.log('Successfully updated assets/images/logo.svg with official medical seal data!');
