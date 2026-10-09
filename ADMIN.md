# Admin Workspace Guide

## Sign-in and setup

There are no default credentials. On a local XAMPP install, open `/admin/setup` and create the first account with a username, email and 12–72 character password. Remote setup requires `APP_SETUP_TOKEN`; remove that token after the first account is created. Sign-in at `/admin/login` accepts either username or email. Sessions expire after 30 minutes of inactivity.

The **Forgot password** link accepts either identifier. It creates a single-use six-digit OTP and reset link. Configure `SMTP_ENABLED=1`, `SMTP_HOST=smtp.gmail.com`, `SMTP_PORT=587`, `SMTP_USER`, `SMTP_PASS` (a Gmail App Password), and `SMTP_FROM_EMAIL` before using it in production. Codes expire in 10 minutes by default and are limited to five attempts.

## Homepage editor

Admin → Homepage lists every homepage section, including the editable enquiry steps, practical guidance, local partnership and FAQ content. New published products, journal posts and care settings flow into the homepage automatically; the site shows the newest six products and newest three posts/settings. Product categories also populate the homepage category cards, dynamic category counter and full-width Products menu automatically. Each section has:

- an active/hidden toggle;
- editable heading, paragraph, CTA, image and brand-background fields where applicable;
- repeatable rows for stats, trust points and links;
- a live iframe preview using the same PHP template and CSS as the public page;
- drag-and-drop reordering plus keyboard up/down controls.

Click outlined text in the preview to focus its matching field. Save sends a CSRF-protected AJAX request. Header and footer remain structural endpoints; testimonials and client logos are managed in their content libraries.

When published content exists, the homepage renders the case-study spotlight and the client-logo marquee. The marquee can be paused by visitors; it is hidden until a logo is explicitly active.

## Products

Admin → Products supports draft/published status, categories, tags, featured placement, slug editing, rich descriptions, applications, features, specifications, galleries, badges and optional brochures. A product thumbnail can be selected from Media or uploaded and center-cropped to 4:3 in the browser. Gallery images support batches of up to 12 uploads at a time, required 3–255 character descriptions, media-library selection, duplicate prevention and manual arrow/drag ordering. A saved gallery is capped at 30 images.

## Editorial content

Journal posts include category, excerpt, rich content, author, date, tags and featured state. Blog listing supports search, category filters, tag filters, pagination, recent posts, removable filter chips and a sidebar. Use cases can connect related products. Case studies can contain client/industry context, challenge, solution, result metrics and products used. Case study detail pages render a premium layout with hero grid, metrics overlay, visually labelled challenge/solution/results sections, result cards, a sticky project sidebar, and a CTA with WhatsApp option. Three demo case studies are seeded for reference.

Testimonials and client logos are hidden until an administrator marks them active. The homepage testimonial section remains useful while empty by inviting a conversation, and the FAQ section is edited as repeatable question/answer rows in Homepage sections.

## SEO and media

Every editable public record has inline title, description, social-sharing, canonical and robots fields. The SEO editor gives character guidance and never silently truncates copy. Sitemap and robots endpoints are generated from published content.

Media validates MIME type, limits files to 5MB, requires an image description and optimises images to WebP at a maximum 1200px width. Files referenced by published content cannot be deleted until references are removed.

## Enquiry inbox

Admin → Enquiries provides an industrial tabular ledger with real-time search, status filtering (`new`, `read`, `replied`), single & bulk deletion, and CSV export. Key capabilities:

- **Strict Chronological Ordering**: Inquiries are always ordered `ORDER BY id DESC`, ensuring the newest inquiries always appear at the very top of page 1.
- **Pagination**: Paginated at 20 enquiries per page with clean previous/next and numeric page controls that preserve active status and search query filters.
- **Selection & Bulk Operations**: Checkboxes on each row and a master "Select All" control reveal a bulk action bar for deleting selected records or marking them as read/replied in a single transaction.
- **Single Record Deletion**: Individual delete buttons on each row with browser confirmation prompts.
- **Direct Contacts**: Click-to-call (`tel:`), click-to-email (`mailto:`), and direct WhatsApp chat links (`wa.me`).
- **Sequential IDs**: Automated test fixtures reset the MySQL auto-increment counter to `MAX(id) + 1` upon cleanup, preventing synthetic ID inflation.
- **Delivery**: Enquiries are saved in the database before optional email delivery. Email notifications require `MAIL_ENABLED=1` and valid outbound SMTP transport.

## Account and site settings

Site Settings controls phone, email, address, WhatsApp number, logo, catalogue PDF, footer description, social links and default SEO. Change password requires the current password and a new 12–72 character password. Keep production credentials outside version control.
