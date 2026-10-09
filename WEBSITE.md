# Public Website Guide

## Routes

| Route | Purpose |
| --- | --- |
| `/` | Homepage and section-driven product introduction |
| `/products` | Searchable product catalogue |
| `/products/{slug}` | Product details, gallery, specs and enquiry CTA |
| `/categories/{slug}` | Products filtered by category |
| `/blog` and `/blog/{slug}` | Journal listing and article pages |
| `/case-studies` | Published project stories with demo case studies seeded |
| `/use-cases` and `/use-cases/{slug}` | Care-setting pages with related products |
| `/about` | Company introduction, values and working process |
| `/contact` | Enquiry form, contact details and opt-in map loading |
| `/catalogue` | Printable catalogue; a PDF download is used when uploaded |
| `/sitemap.xml`, `/robots.txt` | Generated SEO endpoints |

## Homepage sections

The homepage includes sticky navigation, hero, category-count stats, live equipment categories, the six newest published products, why-us, the three newest care settings, a case-study spotlight, the three newest journal posts, three editable sections for enquiry steps, practical guidance and local partnership, styled testimonials, an FAQ accordion with clean rotating chevrons, a pauseable client-logo marquee, contact strip, unified light CTA spotlight card and footer. The spotlight CTA features a clean luminous healthcare card background, live dispatch status pill, direct action buttons (emergency phone hotline, WhatsApp procurement desk, quotation action), inline compliance metadata (PESO, 2-hour hospital emergency turnaround, 99.9% purity, institutional email), and a prominent framed visual of the Agra gas depot (`assets/images/facility.jpg`). The contact strip and CTA are kept at the end in that order; the global footer stays visible even if its section-editor row is disabled. Categories in both the homepage and full-width Products menu come from Admin → Product categories. Sections are database-driven and can be hidden, edited or reordered from Admin. The spotlight and logo strip remain empty/hidden until verified records are published.

## Product behaviour

The catalogue has live search, category checkboxes, application/tag filtering, clear filters and alphabetical sorting. Product detail pages feature an equipment inspection gallery with GLightbox zoom, certified equipment badges, a clinical & industrial guarantee strip, live stock availability pill, a Shopify-style minimalist unboxed metadata strip (`MFC Date` & `5-Yr EXP Date` with PESO and QA tags), an unboxed Shopify-style Technical Specifications table with hairline dividers, dual procurement action deck (Quotation modal + WhatsApp order), downloadable technical spec sheet (PDF), tabbed technical dossier (Description, Full Specifications Data Sheet table with contextual verification tags, Clinical Deployment Register, 2x2 Balanced Statutory Safety Protocol Grid), dynamic related products Swiper slider with custom Prev/Next arrow navigation, clean rotating-chevron FAQ accordion with direct engineering consultation strip, a dedicated Taxonomic Directory Strip before the footer, and bottom facility supply CTA banner. Products can be configured via Admin with custom MFC date, EXP date, and comma-separated search keywords. There are no prices or checkout flows.

The journal provides server-rendered search, category and tag filters, removable filter chips, pagination, recent articles, category counts and a tag cloud. Case-study detail pages, Blog posts, and Use Cases feature an executive architectural briefing layout (`#f8fafc` to `#f1f5f9`), typographic metadata kicker, inline partner credit line, framed cinematic visual with an operational caption bar, and an integrated commissioning impact strip. Case studies include structured architectural narrative blocks (01 Challenge, 02 Engineered Solution, 03 Quantified Clinical Outcomes with impact scoreboard and client pull-quote), a sticky project factsheet sidebar, direct engineering contact desk, and project consultation CTA banner. Three demo case studies are seeded: hospital oxygen supply, surgical instrument upgrade, and rural clinic portable oxygen deployment. All elements adhere to the zero-one-sided-border architectural design rule and avoid generic AI boxed cards.

## Enquiries

Required fields are name, phone and message. Email is optional. The form validates on the client and server, stores the enquiry in the database, sends mail only when configured, and can prepare a WhatsApp message only after the visitor checks the option.

## SEO and performance

Pages render metadata, canonical URLs and relevant Organization/LocalBusiness, Product, Article and Breadcrumb JSON-LD. Images lazy-load except for the hero, static files have cache headers and private application folders are blocked by `.htaccess`. Public forms and admin responses are not cached.

## Starter content

Product descriptions and generated equipment images are placeholders for review. Replace them before launch with real names, models, specifications, availability, approved logo, catalogue, testimonials and case studies. Do not treat the sample images as proof of inventory.

## Verification

The browser smoke suite in `tests/browser-smoke.cjs` verifies public routes, product filters, forms, admin workflows, live section preview, mobile navigation and private-directory blocking. `tests/upload-smoke.cjs` verifies MIME rejection, image descriptions, WebP resizing, media deletion and transaction rollback.
