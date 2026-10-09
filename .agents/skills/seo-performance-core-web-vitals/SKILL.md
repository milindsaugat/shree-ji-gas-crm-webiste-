---
name: seo-performance-core-web-vitals
description: >-
  Use this skill to optimize technical SEO, schema.org JSON-LD structured data, Open Graph meta tags,
  image formats (WebP), fast LCP, zero CLS, critical rendering path, and Core Web Vitals compliance.
---

# Technical SEO & Core Web Vitals Skill

This skill ensures pages achieve maximum search visibility, rich snippets, and sub-second page performance.

## Technical SEO Directives

1. **Meta Architecture**:
   - Distinct, compelling `<title>` (50–60 characters) and `<meta name="description">` (120–160 characters) on every page.
   - Canonical URL tag: `<link rel="canonical" href="...">` on all public pages.
   - Open Graph tags (`og:title`, `og:description`, `og:image`, `og:url`, `og:type`) for social and chat preview card unfurling.
   - Self-updating XML sitemap at `/sitemap.xml` with `<loc>`, `<lastmod>`, `<changefreq>`.

2. **Structured Data (Schema.org JSON-LD)**:
   - Embed valid, rich JSON-LD scripts on every page type:
     - **Home**: `LocalBusiness`, `Organization` (name, address, telephone, logo, geo coordinates, opening hours).
     - **Product**: `Product` (name, image, description, brand, offers, availability).
     - **Editorial / Blog**: `Article`, `TechArticle` (headline, datePublished, author, publisher).
     - **Breadcrumbs**: `BreadcrumbList` on inner pages.

3. **Core Web Vitals & Rendering Performance**:
   - **Largest Contentful Paint (LCP)**:
     - Preload hero images using `<link rel="preload" as="image" href="..." fetchpriority="high">`.
     - Avoid blocking scripts; defer non-critical JS (`defer` or placed at end of `<body>`).
   - **Cumulative Layout Shift (CLS)**:
     - Always set explicit `width` and `height` attributes on `<img>` elements to reserve aspect ratio layout boxes.
     - Avoid inserting dynamic content above existing content without reserved container heights.
   - **First Input Delay (FID) / Interaction to Next Paint (INP)**:
     - Keep main-thread JavaScript execution lightweight (< 50ms per task).
     - Use Vanilla JS instead of heavy client libraries where possible.

4. **Modern Image Formats**:
   - Serve modern compressed formats (WebP, AVIF) with fallback.
   - Apply `loading="lazy"` on all offscreen images.
