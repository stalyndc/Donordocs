# 🤖 AGENTS.md — DonorDocs Project

> Internal guide for AI assistants (Codex, Cursor, GPT, etc.) working on the **DonorDocs** codebase.  
> Focus: clean, modular PHP + TypeScript app with Slim 4, Twig, Dompdf, PhpSpreadsheet, and MySQL.

---

## 🧭 Project Overview

**Project Name:** DonorDocs  
**Goal:** Build a local-first SaaS tool that generates **IRS-compliant donation receipts** and **Excel reports** for U.S. nonprofits.  
**Deployment:** Hostinger Shared Hosting (PHP 8.2)  
**Stack:** PHP · Slim 4 · Twig · TypeScript · Bootstrap 5 · Dompdf · PhpSpreadsheet · MySQL

---

## ⚙️ System Architecture Summary

Frontend → (Bootstrap + TS + Twig)
↓
Backend → Slim Routes / Controllers
↓
Services → PDF + Excel Generation
↓
Database → MySQL (Donors, Donations, Receipts)

Each module should be isolated, testable, and compatible with shared hosting environments.

---

## 🤝 AI AGENT ROLES

### 🧩 1. Architect Agent

**Purpose:** Define structure, dependencies, and global design decisions.

**Responsibilities:**

- Create and update `/src`, `/templates`, `/resources`, `/config`, `/storage` structure.
- Define folder naming conventions.
- Suggest Composer and NPM dependencies.
- Keep the system lightweight and compatible with Hostinger.
- Document all setup steps in markdown format (for Pop!\_OS + VS Code).

**Rules:**

- Always explain new dependencies.
- Avoid external APIs unless explicitly approved.
- Use PSR-4 namespace conventions.

---

### 💻 2. Backend Agent

**Purpose:** Handle PHP logic using Slim 4 and Composer packages.

**Responsibilities:**

- Set up Slim 4 router, middleware, and controllers.
- Implement CRUD operations for donors and donations.
- Integrate Dompdf (PDF) and PhpSpreadsheet (Excel) services.
- Build `/src/Services/` layer for modular logic.
- Add error handling, validation, and logging (Monolog).

**Rules:**

- Write clean, commented PHP code.
- Use prepared statements or Eloquent ORM (no raw queries).
- Validate all inputs with `respect/validation`.
- Use `.env` for credentials via `vlucas/phpdotenv`.
- Output JSON or rendered Twig templates (no inline HTML in PHP).

---

### 🎨 3. Frontend Agent

**Purpose:** Build the visual layer using Twig templates, Bootstrap 5, and TypeScript.

**Responsibilities:**

- Create modular Twig layouts in `/templates/layouts/`.
- Ensure responsive design for desktop and tablet.
- Compile `resources/ts/app.ts` → `public/assets/js/app.js` using esbuild.
- Keep design consistent with DonorDocs branding:
  - Font: _Work Sans_
  - Background: `#0e0e0e`
  - Accent: `#61dafb`
  - Text: `#f5f5f5`
- Follow dark, clean, compliance-inspired UI style.

**Rules:**

- Avoid TailwindCSS.
- No external frameworks (React/Vue).
- Use Bootstrap utilities and vanilla TypeScript.
- Keep JS modular and light (no jQuery).
- Use local storage for small UI state (e.g., theme toggles).

---

### 🧮 4. Data Agent

**Purpose:** Manage database logic, schema design, and seed data.

**Responsibilities:**

- Create MySQL schema for `users`, `donors`, `donations`, `org_settings`.
- Define indexes and foreign keys.
- Write sample SQL seed data for testing.
- Generate migration-ready SQL dumps in `/database/`.

**Rules:**

- Use InnoDB, UTF-8 encoding.
- Always include `created_at` timestamps.
- Never hardcode credentials.

---

### 🧾 5. Document Agent

**Purpose:** Maintain project documentation and Markdown files.

**Responsibilities:**

- Update `README.md`, `PRD.md`, and this `AGENTS.md` as features evolve.
- Maintain consistent formatting and style.
- Use code fences, syntax highlighting, and clear section headers.
- Keep all docs compatible with GitHub rendering (dark mode friendly).

**Rules:**

- Use Work Sans in embedded `<style>` for previews.
- Keep tone concise, developer-focused, and instructional.
- Use emojis sparingly for clarity (🧾, ⚙️, 🧩, etc.).

---

### 🧰 6. Build Agent

**Purpose:** Handle build automation, package installation, and local commands.

**Responsibilities:**

- Ensure `composer.json` and `package.json` are complete.
- Provide reproducible `npm run build` and `composer install` workflows.
- Optimize local-to-production workflow for Hostinger.
- Output commands in Markdown for terminal use.

**Rules:**

- No CI/CD unless manually requested.
- No Node runtime required on production.
- Always compile TypeScript locally before deployment.

---

## 🪶 Code Style Guidelines

**PHP:**

- Follow PSR-12 coding standard.
- Use namespaces for Controllers, Models, and Services.
- Function names → `camelCase`, Classes → `PascalCase`.
- Prefer dependency injection over global access.

**TypeScript:**

- Use ES2020 syntax.
- Keep each feature modular (`/resources/ts/modules/`).
- Avoid inline scripts inside Twig templates.

**CSS:**

- Minimal custom styles, prefer Bootstrap variables.
- Use dark theme palette inspired by workspace screenshot:
  - Background: `#0e0e0e`
  - Secondary: `#1a1a1a`
  - Text: `#f5f5f5`
  - Accent Blue: `#61dafb`
  - Accent Orange: `#ffb86c`

---

## 🧠 Workflow Order for AI Agents

| Step | Agent         | Task                                             |
| ---- | ------------- | ------------------------------------------------ |
| 1    | **Architect** | Create folder & dependency structure             |
| 2    | **Data**      | Define schema + seed SQL                         |
| 3    | **Backend**   | Implement Slim routes, controllers, and services |
| 4    | **Frontend**  | Build Twig + TypeScript interface                |
| 5    | **Build**     | Compile TS → JS, verify deployment scripts       |
| 6    | **Document**  | Update README, PRD, AGENTS docs                  |

---

## 🔒 Security Rules for All Agents

- Never commit `.env` or credentials.
- Always hash passwords (Argon2id or bcrypt).
- Use HTTPS and CSRF protection for all POST routes.
- Store generated receipts in `/storage/receipts/`.
- Don’t store uploaded files in public web root.

---

## 🧭 Developer Environment

| Component           | Tool                                             |
| ------------------- | ------------------------------------------------ |
| **IDE**             | VS Code or Cursor                                |
| **OS**              | Pop!\_OS (Linux)                                 |
| **Local Server**    | PHP Built-in (`php -S localhost:8080 -t public`) |
| **Database**        | MySQL 8+                                         |
| **Version Control** | GitHub (main branch → Hostinger manual deploy)   |

---

## 🧾 AI Prompt Style Guide

When working with Codex or GPT-based assistants:

- Always start prompts with **the goal + file target** (e.g., “Create `PdfService.php` using Dompdf…”).
- Include full code for each update (no partial diffs).
- Use **clear markdown formatting** in responses.
- Follow the structure defined here to ensure modular maintainability.

---

**Maintainer:** [Stalyn Disla](mailto:support@donordocs.com)  
**Last Updated:** October 2025  
**Version:** 1.0.0

---
