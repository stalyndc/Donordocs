SUGGESTIONS_FOR_CODEX.md

Feedback + next tasks for the new DonorDocs marketing home and app.
Keep the current look & palette. Focus on polish, accessibility, performance, and launch-readiness.

1. Routing, Access & Structure
   Keep marketing routes public; gate the app under /app or /dashboard.
   // src/Routes.php
   $app->get('/', [MarketingController::class, 'home'])->setName('home');
$app->get('/pricing', [MarketingController::class, 'pricing'])->setName('pricing');

// Protect dashboard later with auth middleware
$app->get('/dashboard', [DashboardController::class, 'index'])
->setName('dashboard'); // attach auth middleware when auth lands

Add 404 and /privacy, /terms stubs now (linked in footer).
Ensure Open App CTA points to /dashboard and Get Started points to the same for now.
) Header/Nav & Footer
Make header sticky with subtle border; keep CTA contrast AA.
Add current-page state (aria-current) & keyboard focus styles.
Footer: add real links for Privacy/Terms (stubs), mailto:support@donordocs.com.

{# active link helper #}
<a href="{{ path('pricing') }}" aria-current="{{ app.request.uri.path starts with '/pricing' ? 'page' : 'false' }}">
Pricing
</a>

3. Accessibility (A11y) & Semantics
   Landmarks: header, main, footer, nav[aria-label="Primary"].
   One h1 per page (landing already good). Subsequent sections: h2.
   Buttons vs links: CTAs that navigate → <a class="btn ...">; actions → <button>.
   Provide skip to content link at top.
   Ensure focus ring is visible on dark bg (outline: 2px solid #61dafb).
   Provide descriptive alt text for any future screenshots.

<a href="#content" class="visually-hidden-focusable">Skip to content</a>

Performance (Lighthouse 90+)
Preload Work Sans and provide system fallbacks.

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap">
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" media="print" onload="this.media='all'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap"></noscript>
<style>body{font-family:"Work Sans",-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif}</style>

Defer JS: bundle is already small—ensure it’s loaded at end of <body> with defer.
Use CSS logical properties & prefers-reduced-motion (reduce heavy shadows on low-power devices).
Add image placeholders for dashboard screenshot with proper sizes; when real image is added, loading="lazy" and width/height attributes.

SEO & Social
Unique <title> and <meta name="description">.
Add Open Graph + Twitter cards.
Add basic JSON-LD Organization schema.
Provide robots.txt and sitemap.xml stubs.

<title>DonorDocs — IRS-Compliant Donation Receipts for U.S. Nonprofits</title>
<meta name="description" content="Generate branded, audit-ready receipts and Excel reports in seconds. Built for U.S. 501(c)(3) nonprofits.">

<meta property="og:title" content="DonorDocs — IRS-Compliant Donation Receipts">
<meta property="og:description" content="Branded PDFs, Excel exports, and sequential receipt IDs for U.S. nonprofits.">
<meta property="og:type" content="website">
<meta property="og:url" content="{{ base_url() }}/">
<meta property="og:image" content="{{ base_url() }}/assets/og-image.png">

<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"Organization",
  "name":"DonorDocs",
  "url":"{{ base_url() }}/",
  "logo":"{{ base_url() }}/assets/logo.png",
  "sameAs":[]
}
</script>

robots.txt

User-agent: \*
Disallow:
Sitemap: {{ base_url() }}/sitemap.xml

sitemap.xml (static for now)

<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>{{ base_url() }}/</loc></url>
  <url><loc>{{ base_url() }}/pricing</loc></url>
  <url><loc>{{ base_url() }}/dashboard</loc></url>
  <url><loc>{{ base_url() }}/privacy</loc></url>
  <url><loc>{{ base_url() }}/terms</loc></url>
</urlset>

6. Content Polish (copy & hierarchy)

Hero: current copy is strong. Keep H1 concise (<= 60 chars) for SEO.

Highlights: ensure each card has a short benefit line + one-sentence proof/explanation (already good).

How it Works: keep 3 steps; add small checkmarks for visual rhythm.

Compliance block: keep “Not legal advice.” bold. Link to IRS Pub 1771 later.

Pricing Section
Move plan features into small lists (3–5 bullets each) for scannability.
Add footnote: “Prices in USD. Cancel anytime.”
Primary CTA under plans → Open App, secondary → Contact for Agency (mailto).

heming & CSS
Keep dark palette; add light mode via prefers-color-scheme + toggle later.
Ensure color contrast AA: check gray text vs background.
Extract common tokens to CSS variables:
:root{
--bg:#0e0e0e; --surface:#1a1a1a; --text:#f5f5f5;
--muted:#a1a1a1; --accent:#61dafb; --border:#2b2b2b; --warn:#ffb86c;
}
body{background:var(--bg);color:var(--text)}
.card{background:var(--surface);border:1px solid var(--border)}
.btn-primary{background:var(--accent);border-color:var(--accent);color:#0e0e0e}
.text-muted{color:var(--muted)!important}

Security & Headers (basic hardening)
Add baseline headers via .htaccess (Apache) or PHP middleware.

# public/.htaccess (additions)

Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-Content-Type-Options "nosniff"
Header always set Referrer-Policy "strict-origin-when-cross-origin"
Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"

# Basic CSP (loosen fonts if needed)

Header set Content-Security-Policy "default-src 'self'; img-src 'self' data:; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com data:; script-src 'self' 'unsafe-inline';"

App Readiness Hooks (even before auth)
Create a settings wizard (later) – for now, add a notice on dashboard if org fields (EIN/logo) are empty.
Add receipt ID format preview in settings (DD-YYYY-000001), with year rollover logic.
Add export sanity: when no data, show helpful empty states.

Analytics & Consent (when public)
Add privacy-respecting analytics (Plausible or GA4). Load after consent.
Cookie banner (strictly necessary only; no tracking until user agrees).

Acceptance Checklist (for this iteration)
/ public marketing page passes Lighthouse 90+ (Performance, A11y, Best Practices, SEO).
Keyboard navigation works across all interactive elements.
404, Privacy, Terms pages exist and are linked.
Meta/OG tags present; robots.txt + sitemap.xml added.
CSS variables extracted; font loaded with preload & fallback.
Buttons/links have visible focus and ARIA where needed.
Dashboard remains at /dashboard and is not index.

13. Nice-to-Have (if time permits)

Replace the dashboard screenshot placeholder with a generated SVG gradient card for now (perf-friendly).

Add tiny micro-interactions (CSS only) on cards (:hover lift by 1–2px).

Smooth-scroll for in-page anchors (scroll-behavior: smooth in CSS).
