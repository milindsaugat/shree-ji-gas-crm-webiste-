# Deployment and Launch Checklist

## Local XAMPP

1. Start Apache and MySQL/MariaDB.
2. Open `http://localhost/shreejigas/`; SQLite preview mode installs automatically unless `config/local.php` or environment variables select MySQL.
3. For a local MySQL setup, run `php scripts/configure-local.php`, then verify `DB_DRIVER=mysql` and six starter products.
4. Open `/admin/setup` and create the administrator.
5. Back up `C:\xampp\php\php.ini`, enable `extension=gd` and restart Apache for image uploads. The application needs GD to resize and optimise uploaded images.

## MySQL hosting

Create an empty database and a dedicated user. Copy `config/local.example.php` to ignored `config/local.php` or provide equivalent environment variables. Set `DB_DRIVER=mysql`, the database credentials, `APP_URL` with HTTPS, and `APP_BASE_PATH` when the site lives in a subdirectory.

For starter content, run `php scripts/install.php`. To move edited local preview content, run `php scripts/export-preview.php`, create an empty target database, run `php scripts/import-preview.php`, and copy `assets/uploads`. The importer refuses a non-empty target to prevent accidental overwrites.

## Web server

Enable Apache rewrite rules and allow `.htaccess`. Confirm private folders and `schema.sql` return 403. Confirm `assets/uploads` serves images but does not execute PHP/HTML/JS. Set PHP extensions `pdo_mysql`, `gd`, `fileinfo`, `dom`, `mbstring`, `openssl` and `json`.

Set a one-time strong `APP_SETUP_TOKEN` for remote `/admin/setup`, create the admin account, then remove the token. Do not ship `storage/preview.sqlite`, `storage/checks`, `tmp`, browser-test files or real credentials.

## Email and map

Configure the host’s outbound PHP mail transport, set `MAIL_ENABLED=1`, submit a real test enquiry and check both the inbox and delivery. For admin password recovery, create a Google App Password and set `SMTP_ENABLED=1`, `SMTP_HOST=smtp.gmail.com`, `SMTP_PORT=587`, `SMTP_USER`, `SMTP_PASS`, `SMTP_FROM_EMAIL` and optionally `SMTP_FROM_NAME`. Never place a normal Gmail password in `SMTP_PASS`. The contact page loads Google Maps only when the visitor clicks Load Google Map, avoiding an unnecessary request on page load.

## Content before launch

- Replace starter images with approved product photography.
- Add accurate model/capacity/specification data and brochures.
- Upload the approved logo and catalogue.
- Add verified testimonials, client logos and case studies; keep unverified entries hidden.
- Review phone, email, address, WhatsApp, social links and privacy copy.
- Review all metadata, canonical URLs and robots settings.

## Post-launch checks

Run the browser smoke and upload smoke suites against a safe staging copy, check `/sitemap.xml`, submit it in Search Console, test the contact flow on desktop/mobile, test gallery batch uploads and keep database/media backups. The latest recorded local browser run passed 12 public/admin checks with no JavaScript errors; the upload suite passed MIME rejection, descriptions, WebP resizing, deletion and transaction rollback checks.
