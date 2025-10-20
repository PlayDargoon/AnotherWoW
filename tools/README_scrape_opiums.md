Scrape opiums.eu achievements (JS-rendered)

This folder contains a Puppeteer script `scrape_opiums_puppeteer.js` that loads the achievements pages and extracts achievement IDs for items containing "1-й на сервере" or "Впервые на сервере".

Windows PowerShell quick start:

1. Install Node.js (if not installed) from https://nodejs.org/
2. Open PowerShell in the repo root and install puppeteer locally:

```powershell
cd .\tools
npm init -y
npm i puppeteer
```

3. Run the scraper:

```powershell
node .\scrape_opiums_puppeteer.js
```

Output will be written to `tools/opiums_firston.json`.

If you cannot install Node or run puppeteer, ask me to fetch the IDs manually and I'll provide them here.
