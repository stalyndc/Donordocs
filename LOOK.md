# DonorDocs — Next Steps

Goal: ship a functional MVP of DonorDocs (local-first, Hostinger-ready) that generates IRS-compliant receipts + Excel exports.

---

## 1. Core MVP Features

### A. Authentication & Roles

- [ ] Session login (Admin, Staff)
- [ ] Password hashing (Argon2id or bcrypt)
- [ ] Auth middleware protecting `/dashboard`, `/donors`, `/donations`, `/receipts`, `/exports`

### B. Organization Settings

- [ ] Org profile: name, address, EIN, city/state/zip, timezone
- [ ] Branding: logo upload, signer name/title, signature image (optional)
- [ ] IRS text presets (editable):
  - “No goods or services were provided…”
  - “Only intangible religious benefits…”
  - “Goods/services provided with estimated value $X…”
- [ ] Receipt numbering preview + scheme: `DD-YYYY-000001` (yearly reset)

### C. Donors & Donations (CRUD)

- [ ] Donors: name, email, address
- [ ] Donations: donor, date, amount (USD), method (cash/check/ACH/card/other), designation, notes
- [ ] Validation (server-side): amount > 0, date required, donor required
- [ ] Empty states + helpful copy (no donors/donations yet)

### D. Receipts (PDF)

- [ ] Generate & store PDF per donation with:
  - Org info + EIN + logo
  - Donor name/address
  - Amount, date, method, designation
  - Receipt number
  - IRS block (chosen preset)
- [ ] Save to `/storage/receipts/{receipt_number}.pdf`
- [ ] “View / Download” actions; immutable once issued

### E. Reports & Exports

- [ ] Excel (.xlsx) export: all donations within date range (PhpSpreadsheet)
- [ ] Year-end donor summary (PDF): list + total of a donor’s gifts for a year

---

## 2. Data Model & Migrations

```sql
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(190) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE org_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  org_name VARCHAR(255) NOT NULL,
  org_address TEXT,
  org_ein VARCHAR(50),
  city VARCHAR(128), state CHAR(2), zip VARCHAR(10),
  logo_path VARCHAR(255), signature_path VARCHAR(255),
  signer_name VARCHAR(128), signer_title VARCHAR(128),
  default_receipt_text TEXT,
  timezone VARCHAR(64) DEFAULT 'America/New_York',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donors (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(190),
  address TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE donations (
  id INT AUTO_INCREMENT PRIMARY KEY,
  donor_id INT NOT NULL,
  donation_date DATE NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  method ENUM('cash','check','card','ach','other') DEFAULT 'other',
  designation VARCHAR(255),
  notes TEXT,
  receipt_number VARCHAR(32) UNIQUE,
  receipt_pdf_path VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (donor_id) REFERENCES donors(id)
);

CREATE INDEX idx_donations_date ON donations(donation_date);
CREATE INDEX idx_donations_donor ON donations(donor_id);


| Endpoint                                           | Description                                          |
| -------------------------------------------------- | ---------------------------------------------------- |
| `GET /dashboard`                                   | Metrics (lifetime total, month total, active donors) |
| `GET /donors` / `POST /donors`                     | CRUD for donors                                      |
| `GET /donations` / `POST /donations`               | CRUD for donations                                   |
| `POST /receipts/{donationId}/generate`             | Create receipt number + PDF                          |
| `GET /receipts/{receiptNumber}`                    | Stream/download receipt                              |
| `GET /exports/excel?from=YYYY-MM-DD&to=YYYY-MM-DD` | Excel export for range                               |
| `GET /summaries/{donorId}/{year}.pdf`              | Year-end PDF summary                                 |


4. Receipt Numbering Logic

Counter resets per calendar year.

Format: DD-YYYY-######

Implementation:

Query last receipt_number for current year.

Increment and left-pad to 6 digits.
```
