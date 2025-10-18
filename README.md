<style>
@import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap');

body {
  font-family: 'Work Sans', sans-serif;
  background-color: #0e0e0e;
  color: #f5f5f5;
  line-height: 1.6;
}
h1, h2, h3 {
  color: #ffffff;
  font-weight: 600;
}
code, pre {
  background-color: #1a1a1a;
  color: #ffb86c;
  border-radius: 6px;
  padding: 3px 6px;
}
a {
  color: #61dafb;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
strong {
  color: #ffffff;
}
</style>

# 🧾 DonorDocs

> **IRS-compliant donation receipts and reports for U.S. nonprofits — built with PHP, Composer, and TypeScript.**

![DonorDocs Banner](https://dummyimage.com/1200x300/0e0e0e/ffffff&text=DonorDocs.com)

---

## 🚀 Overview

**DonorDocs** helps U.S.-based nonprofits generate and manage **IRS-compliant donation receipts**, **PDF summaries**, and **Excel exports** — all from a simple web interface that runs on shared hosting (Hostinger, etc.) or locally on your machine.

### 🔑 Key Highlights

- Auto-numbered receipts with your logo and EIN
- Preloaded **IRS Pub 1771** text for compliance
- PDF + Excel exports for accounting
- Simple dashboard and donor tracking
- No external services or APIs required

---

## ⚙️ Tech Stack

| Layer            | Technology               |
| ---------------- | ------------------------ |
| **Language**     | PHP 8.2+                 |
| **Framework**    | Slim 4                   |
| **Templating**   | Twig                     |
| **Database**     | MySQL                    |
| **PDF Engine**   | Dompdf                   |
| **Excel Export** | PhpSpreadsheet           |
| **Frontend**     | TypeScript + Bootstrap 5 |
| **Build Tool**   | esbuild                  |
| **Logging**      | Monolog                  |
| **Validation**   | respect/validation       |
| **Environment**  | vlucas/phpdotenv         |

---

## 🧩 Folder Structure

```bash
donordocs/
├── public/
│   ├── index.php
│   ├── assets/
│   │   ├── js/app.js
│   │   └── css/style.css
├── src/
│   ├── Controllers/
│   ├── Services/
│   │   ├── PdfService.php
│   │   ├── ExcelService.php
│   │   └── OrgSettingsService.php
│   ├── Models/
│   ├── Middleware/
│   └── Helpers/
├── templates/
│   ├── layouts/
│   ├── donors/
│   ├── donations/
│   └── receipts/
├── storage/
│   ├── receipts/
│   ├── exports/
│   └── logs/
├── resources/
│   ├── ts/app.ts
│   └── styles/style.scss
├── config/
│   └── settings.php
├── composer.json
├── package.json
└── README.md
```
