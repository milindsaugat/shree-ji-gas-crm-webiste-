# Backend and Data Guide

## Request flow

`index.php` is the front controller. It starts a strict session, applies security headers, loads allowlisted classes and routes public/admin paths to controllers. Public templates are rendered through `views/layouts/main.php`; admin templates use `views/layouts/admin.php`.

## Database

`config/database.php` provides a PDO connection. Local preview mode uses `storage/preview.sqlite`; deployment mode uses MySQL/MariaDB through `DB_DRIVER=mysql`. All application queries use prepared statements. `database/install.php` creates the schema and `database/seed.php` adds starter content.

The primary tables are `settings`, `sections`, `categories`, `products`, `blog_posts`, `case_studies`, `use_cases`, `testimonials`, `client_logos`, `pages`, `seo_meta`, `media`, `admin_users`, `password_resets`, `enquiries` and `rate_limits`. Existing databases are migrated with an admin username, reset table and FAQ section when the installer runs.

## Security controls

- Passwords use bcrypt and sessions regenerate at login. Admin identifiers can be username or email; password-reset OTPs are stored hashed and expire automatically.
- CSRF tokens protect public enquiries and every admin mutation.
- Login and enquiry requests use database-backed rate limits.
- Admin resource types and editable fields are allowlisted in `config/resources.php`.
- Rich text is sanitised through the shared `rich()` helper.
- Uploads use MIME detection, extension allowlists, size limits, GD processing and a non-executable uploads `.htaccess`.
- Output is escaped with `e()`; public links are checked by `safeLink()`.
- CSV export prefixes formula-like values to reduce spreadsheet injection risk.

## Upload model

`helpers/Upload.php` stores approved files under `assets/uploads`, writes a media record and returns a safe relative path. Product gallery values are JSON objects containing `path` and `alt`. The admin editor uploads gallery batches one file at a time through the same protected endpoint, then saves the product transactionally. The browser limits each batch to 12 files and each saved gallery to 30 images.

## Content transactions

Product/blog/case-study saves and inline SEO saves share a transaction. Invalid SEO URLs or duplicate keys roll back the content update. Core pages (`about`, `contact`, `privacy`) are editable but cannot be deleted or moved to arbitrary slugs.

## Maintenance commands

```text
php scripts/install.php --schema       Generate schema.sql
php scripts/install.php                Install/populate MySQL
php scripts/export-preview.php        Export local preview content
php scripts/import-preview.php        Import preview content into an empty MySQL database
```

`scripts/configure-local.php` is a local XAMPP convenience command that creates a dedicated database user and writes ignored `config/local.php`. Do not use it on production hosting. Three demo case studies are seeded directly into the `case_studies` table with published status, covering hospital oxygen supply, surgical instrument upgrade, and rural clinic portable oxygen deployment.

## Operational limitations

PHP’s built-in `mail()` is used for enquiry notifications. Password-reset OTP delivery uses the small STARTTLS Gmail SMTP client when explicitly enabled. Configure queueing, backups and monitoring at the hosting layer if volume requires them. The code stores enquiries regardless of mail acceptance and displays the transport status in Admin.
