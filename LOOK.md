Task: Replace Dashboard as Front Page & Build Marketing Home

The current Dashboard view is showing at / (front page). That should NOT be the public front page.
Build a marketing home (landing) page and move the dashboard under /dashboard.

Goals

Public visitors see a marketing landing page at / (not the app dashboard).

Existing dashboard remains accessible at /dashboard.

Keep branding consistent: Work Sans, dark palette, no Tailwind, minimal Bootstrap 5 + custom CSS.

Add simple nav links and CTAs to guide users to “Get Started” and “Open App (Dashboard)”.

Routes & Controllers

NEW: GET / → MarketingController::home() → templates/marketing/home.twig

MOVE: GET /dashboard → current dashboard controller (keep as is)

(Optional) GET /pricing → MarketingController::pricing() → templates/marketing/pricing.twig (stub)

Update src/Routes.php:

// New public landing
$app->get('/', [\DonorDocs\Controllers\MarketingController::class, 'home'])->setName('marketing.home');

// Dashboard now lives here (keep existing implementation)
$app->get('/dashboard', [\DonorDocs\Controllers\DashboardController::class, 'index'])->setName('dashboard');

Create src/Controllers/MarketingController.php:

home(Request $req, Response $res): Response → render marketing/home.twig

(Optional) pricing(...) → render marketing/pricing.twig

templates/
├─ layouts/
│ └─ marketing_base.twig # separate base for landing pages
├─ marketing/
│ ├─ home.twig # new front page
│ └─ pricing.twig # optional stub
public/
└─ assets/
└─ css/marketing.css # landing-only overrides (imported after bootstrap)

Branding & UI Requirements

Font: Work Sans (Google Fonts)

Colors (from our dark UI):

Background: #0e0e0e

Surface: #1a1a1a

Text: #f5f5f5

Subtext: #a1a1a1

Accent (buttons/links): #61dafb

Borders: #2b2b2b

Optional highlight: #ffb86c

Framework: Bootstrap 5 only. No Tailwind.

JS: Existing TypeScript bundle; no new frameworks.

Accessibility: Sufficient contrast, focus states, aria-labels on CTAs, semantic headings.

Navigation (public pages)

Top navbar (sticky, dark):

Brand: “DonorDocs” (links to /)

Links: Features, How it Works, Pricing, Docs (placeholder), Contact (mailto)

Right side: Open App (links to /dashboard) and Get Started (links to /dashboard for now)

Marketing Home Page Structure (templates/marketing/home.twig)

Header / Hero (Above the fold)

H1: “IRS-Compliant Donation Receipts. In Seconds.”

Subtext: “Generate branded, audit-ready receipts and Excel reports for your U.S. nonprofit.”

Primary CTA: Get Started → /dashboard

Secondary CTA: See How It Works → anchor #how-it-works

Background: dark gradient or subtle pattern; no heavy images by default.

Trust/Highlights (3 cards)

“USA 501(c)(3)-ready templates”

“PDF receipts & Excel exports”

“Simple, affordable, local-friendly”

Feature Grid

Receipt generator

Year-end donor summaries (PDF)

Excel/CSV exports

Offline donations (cash/check)

Sequential receipt numbering

Dashboard totals

How It Works (3 steps)

Step 1: Configure org (EIN, logo)

Step 2: Add donor & record donation

Step 3: Click “Generate Receipt”

Each step with small icon, 1–2 sentences.

Compliance Note

Short block with IRS Pub 1771 mention; editable templates; region: USA.

Callout / Screenshot Placeholder

Placeholder image box with caption: “Your dashboard, at a glance.”

(Keep as a simple bordered rectangle for now)

Pricing Teaser (can be a stub)

Free (25 receipts/mo, watermark)

Pro ($9/mo) — 250 receipts, branding, Excel

Plus ($19/mo) — unlimited, summaries

CTA to “Open App” or “Contact for agency”

Footer

© DonorDocs, Links: Privacy, Terms, Contact (mailto), GitHub (if public later)

marketing_base.twig Requirements

Loads:

Work Sans font

Bootstrap CSS

public/assets/css/marketing.css

Sets <meta name="description"> and OG tags

Dark theme classes with proper contrast

Top nav + footer included via Twig blocks/partials

Expose a {% block content %}{% endblock %} for page-specific sections

SEO & Social Meta (example):

<meta name="description" content="DonorDocs helps U.S. nonprofits generate IRS-compliant donation receipts, year-end PDFs, and Excel reports.">
<meta property="og:title" content="DonorDocs — IRS-Compliant Donation Receipts">
<meta property="og:description" content="Generate branded, audit-ready receipts and Excel reports in seconds.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ base_url() }}/">
<meta property="og:image" content="{{ base_url() }}/assets/og-image.png">

marketing.css Guidelines

Base: dark background, soft card shadows, rounded corners 8px

Buttons:

Primary: accent blue background #61dafb on dark, dark text #0e0e0e

Secondary: transparent with #61dafb border, text #61dafb; hover = subtle fill

Links: #61dafb; underline on hover

Card border: 1px solid #2b2b2b; background #1a1a1a

Example rules:

body { background:#0e0e0e; color:#f5f5f5; }
.navbar { background:#0f0f10; border-bottom:1px solid #2b2b2b; }
.card { background:#1a1a1a; border:1px solid #2b2b2b; border-radius:8px; }
.btn-primary { background:#61dafb; color:#0e0e0e; border-color:#61dafb; }
.btn-outline-primary { color:#61dafb; border-color:#61dafb; }
.text-muted { color:#a1a1a1 !important; }
.section { padding: 64px 0; }

Copy (you can paste directly into the template)

Hero

H1: IRS-Compliant Donation Receipts. In Seconds.

Sub: Generate branded, audit-ready receipts and Excel reports for your U.S. nonprofit.

Buttons: Get Started (primary) / See How It Works (secondary)

Highlights

USA 501(c)(3) templates — Preloaded IRS Pub 1771 wording

PDF + Excel — Download receipts and year-end summaries

Simple & affordable — Built for small nonprofits

How It Works steps

Set up your org — Add EIN, address, and logo

Record donations — Cash, check, ACH, or card

Generate receipts — Branded PDFs and Excel exports

Compliance block

DonorDocs includes default language for U.S. charitable acknowledgments and lets you customize templates. (Not legal advice.)

Acceptance Criteria
/ renders marketing/home.twig using marketing_base.twig
Navbar has links + Open App button → /dashboard
Dashboard is not visible at / anymore; it’s at /dashboard
Colors/typography match spec (Work Sans, dark palette)
No Tailwind; minimal Bootstrap + marketing.css
Page is responsive and accessible (keyboard focus, aria labels)
Meta description + OG tags present
Build outputs remain the same (public/assets/js/app.js, CSS under public/assets/css/)

Notes

Don’t remove existing dashboard logic or metrics.

Keep all public marketing templates under templates/marketing/.

Keep the marketing CSS separate from app CSS to avoid leaks.

If any conflicts arise, prefer marketing_base.twig for landing pages and keep app pages on the existing base layout.
