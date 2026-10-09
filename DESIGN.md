# Shree Ji Gas Industries — Design Guide

## Visual direction

The interface presents a dependable local medical-equipment partner. It uses editorial spacing, clear hierarchy and direct calls to action instead of an e-commerce treatment. Product information is prominent; price, cart and checkout are intentionally absent.

## Brand tokens

| Token | Value | Use |
| --- | --- | --- |
| Primary navy | `#1B3D6F` | Header, headings, primary actions, footer |
| Teal blue | `#2E8FA8` | Links, icons, accents, active states |
| Light teal | `#7EC8D4` | Highlight details and dark-section accents |
| Page tint | `#F5FCFD` | Page backgrounds |
| Section tint | `#EAF7FA` | Alternating sections and information blocks |
| Text | `#1A2D44` | Main copy |
| Muted text | `#5A7A8A` | Supporting copy and metadata |
| Border | `#D0EEF3` | Dividers and card outlines |

Buttons are square or nearly square, with a maximum 4px radius and crisp tactile states. Structured UI components use balanced 4-sided borders, image-first composition, tabular ledgers, and clean micro-interactions. The CTA section and product page facility banner use the official brand primary navy `#1b3d6f` (`radial-gradient(circle at 95% 15%, rgba(126,200,212,.18) 0%, transparent 55%), linear-gradient(135deg, #1b3d6f 0%, #163663 100%)`) with crisp white `#ffffff` headlines, `#d8e6f0` subheadings, solid white primary action buttons with `#1b3d6f` text, vibrant WhatsApp actions, live dispatch status pill, inline metadata strip (PESO, 2-Hr Turnaround, 99.9% Purity, Institutional Email), and a prominent high-resolution facility visual (`assets/images/facility.jpg`) with an integrated depot badge. Clunky multi-tiered boxes, pitch-black monolithic blocks, and tacky one-sided borders are strictly prohibited.

## Universal UI/UX Rules: Structured UI Architecture (Shopify-Style Minimalist Technical Standard)

1. **Clean Shopify-Style Technical Product Layout**: Discrete metadata, dates, and technical parameters in the buy area must NOT be packaged into heavy, clunky boxed cards with solid header bars. Instead, use minimalist Shopify Plus / Apple engineering standards:
   - **Shopify Product Meta Strip**: Minimalist inline statutory certification tags (`PESO / IS:7285`, `QA Verified`) and clean inline date pairs (`MFC Date` and `Hydro-Test Valid`) with dot (`•`) dividers (`.shopify-product-meta`).
   - **Shopify Specifications Table**: Unboxed clean tabular list with muted parameter labels on left, bold dark values on right, and subtle hairline row borders (`.shopify-specs-table`).
   - **Industrial Technical Specifications Data Sheet**: Tabular data sheet with distinct parameter names (clean bullet indicators, zero raw chevrons), bold certified specifications, and contextual compliance badges (`compliance-tag--qa`, `compliance-tag--peso`, `compliance-tag--purity`, `compliance-tag--pressure`).
   - **Balanced 2x2 Safety Protocol Grid**: Four clean, balanced statutory safety cards (`.safety-rules-grid`) with rule code badges, pastel icon badges, concise regulation text, and Indian Gas Cylinder Rule statutory citations.
   - **Clean Chevron Accordion UX**: FAQ toggles use minimalist rotating chevrons (`arrow-down-s-line`) that rotate 180° smoothly on open without clumsy circle borders or plus-to-cross icon morphing.
   - **Clinical Deployment Register**: Continuous multi-row deployment registry with integrated sector codes and tags (`.deployment-register`).
   - **Taxonomic Directory Strip**: Full-width architectural catalog index strip (`.product-taxonomy-strip`) rather than a floating card box.
   - **Editorial Byline Ribbon**: Seamless typographic inline metadata with dot (`·`) dividers (`.editorial-byline-strip`).
   - **Impact Scoreboard**: Annual-report style typographic stat columns with subtle hairline dividers (`.case-scoreboard`).
2. **Zero One-Sided Borders**: All borders must be balanced 4-sided structural outlines or functional tabular hairlines (`border-bottom` between rows, `border-right` between columns). No isolated `border-left`, `border-top`, or single-edge colored accent bars are permitted anywhere.

## Typography

`Sora` is used for headings and `Plus Jakarta Sans` for body/UI text. Both are bundled in `assets/vendor/fonts`, so the site still renders when external font requests are blocked. Headings are limited to a single page H1 followed by H2/H3 sections.

## Layout rules

- Public content is centered in a maximum 1400px container with a 1240px reading width on large screens.
- Desktop section spacing is 96–100px; mobile spacing is 60px.
- Desktop product grids show three cards, tablet two and mobile one.
- The mobile navigation is a Bootstrap-style right offcanvas drawer with a full navy background.
- Product imagery uses 4:3 contained frames on white/cool-white backgrounds.
- `assets/css/main.css` contains the public system, with final responsive refinements in `assets/css/refinements.css`; `assets/admin/css/admin.css` contains the CMS system.

## Motion and accessibility

GSAP is used for the hero entrance, AOS for subtle section fade-up motion, CountUp for numeric stats, Swiper for product/testimonial sliders, GLightbox for product images and a CSS marquee for active client logos. All are bundled under `assets/vendor`. The logo marquee includes a pause control and reduced-motion users receive static content. Forms include labels, validation messages, CSRF fields and keyboard-accessible controls.

The case-study spotlight uses one large featured story with optional metric cards and smaller linked story rows. Case study detail pages, Blog posts, and Care-Setting Use Cases feature an executive architectural briefing layout (`#f8fafc` to `#f1f5f9`), typographic metadata kicker, inline partner/author credit line, framed cinematic visual with an operational caption bar, and balanced vertical rhythm. Pitch-black monolith blocks, isolated one-sided border lines, and unstyled raw text links are strictly prohibited. Blog and Use-Case pages feature generous top/bottom padding (`padding: 56px 0 96px;`), high-readability 15.5px slate body text (`#334155`), custom rounded checkmark list indicators, a modular 4-sided bordered architectural sidebar (`.sidebar-box` with TOC step pills, Central Dispatch Desk phone/WhatsApp hotline, share buttons, and related article links), and an integrated `#1b3d6f` brand spotlight CTA banner with white action buttons and emergency hospital turnaround trust notes.

Product detail pages feature an equipment inspection gallery with zoom, technical quality seals, live stock pill, a structured Precision Technical Lifecycle Ledger (certified MFC Date & 5-Yr EXP Date), an Engineered Parameter Ledger for certified specifications summary, dual procurement action deck (Quotation & WhatsApp), tabbed documentation dossier (Description, Full Specifications Data Sheet table including verified MFC & EXP rows, Clinical Deployment Register, Statutory Safety Protocol Register), dynamic related products slider with custom navigation controls, technical procurement FAQ accordion with direct engineering consultation strip, a dedicated Taxonomic Directory Strip anchoring the end of the catalog page, and a turnkey bottom facility supply CTA banner. About adds a story block, three value cards and a three-step process. When no verified case studies or testimonials exist, the sections stay useful with a contact prompt instead of fabricated claims.

The Products navigation opens a full-width category panel. Homepage catalogue cards use the current categories and the six latest published products; the insights and care-setting sections show the latest three published records. Three editorial components explain the enquiry process, product planning support and local partnership before the testimonial and FAQ components. The ending order is contact details, final CTA, then the site footer. The footer remains visible independently of the homepage section visibility toggle. The About page uses large editorial typography, an image without a decorative frame or shadow, live category links, values and a three-step process.

## Image guidance

The bundled equipment images are clearly illustrative starter assets. Replace them with approved product photography through Admin → Media library and set meaningful image descriptions. Source notes are in `assets/images/SOURCES.md`.

## Approved content boundary

Do not publish certifications, client logos, testimonials, availability, capacity, uptime, years of experience or case-study outcomes until the business verifies them. The starter data deliberately avoids invented claims.
