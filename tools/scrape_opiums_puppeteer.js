// tools/scrape_opiums_puppeteer.js
// Usage: node scrape_opiums_puppeteer.js
// Fetches achievements pages (with JS) and extracts achievement IDs where title contains
// "1-й на сервере" or "Впервые на сервере".

const fs = require('fs');
const puppeteer = require('puppeteer');

async function scrape() {
  const base = 'https://base.opiums.eu/?achievements=81';
  const pages = [base, base + '&start=100'];
  const browser = await puppeteer.launch({ headless: true });
  const page = await browser.newPage();
  const found = {};

  for (const url of pages) {
    console.log('Opening', url);
    await page.goto(url, { waitUntil: 'networkidle2', timeout: 30000 });
    // Give additional time for any lazy-load
    await page.waitForTimeout(500);

    const res = await page.evaluate(() => {
      const results = [];
      const textSelectors = Array.from(document.querySelectorAll('td, tr, div, li'));
      const re = /1-?й на сервере|Впервые на сервере|Впервые на сервере/i;
      for (const el of textSelectors) {
        const txt = (el.innerText || '').trim();
        if (!txt) continue;
        if (re.test(txt)) {
          // Try to find anchors inside this element or nearby
          const anchors = Array.from(el.querySelectorAll('a'));
          if (anchors.length === 0) {
            // look up to parent then siblings
            const parent = el.parentElement;
            if (parent) anchors.push(...Array.from(parent.querySelectorAll('a')));
          }
          const hrefs = anchors.map(a => a.href).filter(Boolean);
          results.push({ text: txt, hrefs });
        }
      }
      return results;
    });

    for (const item of res) {
      for (const href of item.hrefs) {
        const m = href.match(/[?&]achievement=(\d+)/i) || href.match(/\/achievement\/(\d+)/i);
        if (m) {
          const id = parseInt(m[1], 10);
          found[id] = item.text.replace(/\s+/g, ' ').slice(0, 200);
        }
      }
    }
  }

  await browser.close();

  const ids = Object.keys(found).map(x => parseInt(x,10)).sort((a,b)=>a-b);
  const out = ids.map(id => ({ id, title: found[id] }));
  fs.writeFileSync('tools/opiums_firston.json', JSON.stringify(out, null, 2));
  console.log('Saved', out.length, 'ids to tools/opiums_firston.json');
  console.log('IDs:', ids.join(', '));
}

scrape().catch(err => {
  console.error(err);
  process.exit(1);
});
