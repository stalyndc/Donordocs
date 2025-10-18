<style>
@import url('https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap');

body {
  font-family: 'Work Sans', sans-serif;
  background-color: #0e0e0e;
  color: #f5f5f5;
  line-height: 1.6;
}
h1, h2, h3, h4 {
  color: #ffffff;
  font-weight: 600;
}
code, pre {
  background-color: #1a1a1a;
  color: #ffb86c;
  border-radius: 6px;
  padding: 2px 5px;
}
a {
  color: #61dafb;
  text-decoration: none;
}
a:hover {
  text-decoration: underline;
}
table {
  border-collapse: collapse;
  width: 100%;
}
th, td {
  border: 1px solid #2b2b2b;
  padding: 8px;
}
th {
  background-color: #1c1c1c;
}
tr:nth-child(even) {
  background-color: #141414;
}
strong {
  color: #ffffff;
}
</style>

# 🧾 DonorDocs — Product Requirements Document (PRD)

---

## 1. Overview

**Product Name:** DonorDocs  
**Stage:** MVP — Local build first  
**Goal:** Help U.S.-based nonprofits generate IRS-compliant donation receipts and donor reports with ease.

DonorDocs automates **receipt generation**, **year-end summaries**, and **Excel/CSV exports**.  
It’s designed for small organizations that can’t afford large donor CRMs but need accuracy and compliance.

---

## 2. Target Audience

**Who it’s for:**

- Small and mid-sized U.S. nonprofits
- Churches, PTAs, charities, community centers

**Roles:**

- **Admin:** Configures org, manages staff
- **Staff:** Logs donations, generates receipts/reports

---

## 3. Key Value Proposition

- 📄 **Compliant** — Preloaded IRS Pub 1771 text
- ⚙️ **Automated** — Generate receipts in seconds
- 🧮 **Report-Ready** — PDF & Excel exports for audits
- 💾 **Offline-Safe** — Works on shared hosting or localhost
- 🧠 **Expandable** — AI and email modules planned for future versions

---

## 4. Core Features (MVP)

| Category      | Feature       | Description                                              |
| ------------- | ------------- | -------------------------------------------------------- |
| **Auth**      | Secure login  | Session-based login, hashed passwords                    |
| **Org Setup** | Org info      | EIN, address, logo, signature, default IRS text          |
| **Donors**    | CRUD          | Add, edit, search donors                                 |
| **Donations** | CRUD          | Record donations with donor, date, amount, method        |
| **Receipts**  | PDF generator | IRS-compliant, branded, auto-numbered (`DD-YYYY-000001`) |
| **Reports**   | Excel / CSV   | Export donor data & yearly summaries                     |
| **Dashboard** | Overview      | Monthly totals, donor count, top donors                  |

---

## 5. Future Enhancements

| Feature                     | Description                              |
| --------------------------- | ---------------------------------------- |
| **Email Receipts (SMTP)**   | Send receipts automatically              |
| **AI Thank-You Writer**     | Personalized thank-you message generator |
| **Batch Generation**        | Multi-receipt PDF generation             |
| **Agency Mode**             | Manage multiple orgs under one account   |
| **Accounting Integrations** | QuickBooks or Zapier connection          |

---

## 6. Tech Stack

| Layer            | Technology               |
| ---------------- | ------------------------ |
| **Language**     | PHP 8.2+                 |
| **Framework**    | Slim 4                   |
| **Templating**   | Twig                     |
| **Database**     | MySQL                    |
| **PDF Engine**   | Dompdf                   |
| **Excel Engine** | PhpSpreadsheet           |
| **Validation**   | respect/validation       |
| **Logging**      | Monolog                  |
| **Config**       | vlucas/phpdotenv         |
| **Frontend**     | TypeScript + Bootstrap 5 |
| **Charts**       | Chart.js                 |
| **Security**     | CSRF + Argon2/Bcrypt     |

---

## 7. File & Folder Structure

```bash
donordocs/
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── assets/
│   │   ├── js/app.js
│   │   └── css/style.css
│   └── uploads/
├── src/
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   │   ├── PdfService.php
│   │   ├── ExcelService.php
│   │   └── OrgSettingsService.php
│   ├── Middleware/
│   └── Helpers/
├── templates/
│   ├── layouts/base.twig
│   ├── dashboard.twig
│   ├── donors/*.twig
│   ├── donations/*.twig
│   └── receipts/*.twig
├── storage/
│   ├── receipts/
│   ├── exports/
│   └── logs/app.log
├── resources/
│   ├── ts/app.ts
│   └── styles/style.scss
├── config/settings.php
├── .env.example
├── composer.json
├── package.json
└── README.md
```

Author: Stalyn Disla
Environment: Local build (Pop!\_OS) → Hostinger (shared PHP)
Version: PRD v1.0 (October 2025)
License: Proprietary / SaaS Internal
