import { chromium } from 'playwright';
import path from 'path';
import fs from 'fs';

const outDir = './Wireframe';

const run = async () => {
  console.log("Launching headless browser...");
  const browser = await chromium.launch({ headless: true });
  const page = await browser.newPage();
  
  // Standard desktop viewport for wireframes
  await page.setViewportSize({ width: 1366, height: 768 });

  // Get all HTML files in numerical order
  const files = fs.readdirSync(outDir)
    .filter(f => f.endsWith('.html'))
    .sort((a, b) => {
      const numA = parseInt(a.split('_')[0]);
      const numB = parseInt(b.split('_')[0]);
      return numA - numB;
    });

  console.log(`Found ${files.length} HTML files to capture.`);

  for (const file of files) {
    const name = path.basename(file, '.html');
    const filePath = path.join(outDir, file);
    const absolutePath = path.resolve(filePath);
    const fileUrl = `file:///${absolutePath.replace(/\\/g, '/')}`;

    console.log(`Capturing: ${file} ...`);
    await page.goto(fileUrl, { waitUntil: 'load' });
    
    // For wireframes, wait a tiny bit to ensure everything is rendered
    await page.waitForTimeout(500);

    const screenshotPath = path.join(outDir, `${name}.png`);
    await page.screenshot({ path: screenshotPath, fullPage: false });
    console.log(`Saved: ${screenshotPath}`);
  }

  await browser.close();
  console.log("Headless browser closed. All screenshots captured.");
};

run().catch(err => {
  console.error("Capture failed:", err);
  process.exit(1);
});
