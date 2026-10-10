# Shree Ji Gas Industries — Enterprise Medical Equipment & CRM Web Platform

[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-blue.svg)](https://php.net/)
[![Database](https://img.shields.io/badge/Database-MySQL%20%7C%20MariaDB%20%7C%20SQLite-teal.svg)](https://mariadb.org/)
[![UI Standard](https://img.shields.io/badge/UI%2FUX-Industrial%20Clinical%20Standard-1b3d6f.svg)](DESIGN.md)
[![Data Privacy](https://img.shields.io/badge/Compliance-DPDP%20Act%202023-emerald.svg)](WEBSITE.md)

An enterprise-grade, high-performance web platform and administrative CRM designed specifically for **Shree Ji Gas Industries** (Agra, Uttar Pradesh) — certified suppliers of medical oxygen cylinders, hospital gas pipeline systems, surgical instrument kits, and respiratory care equipment.

Built with pure, modern **PHP 8.2+ MVC architecture**, Vanilla CSS design tokens, prepared PDO transactions, and strict security hardening. Zero heavy bloated Node.js runtimes or build steps required for production.

---

## 🏛️ System Architecture & Technology Stack

- **Core Engine**: PHP 8.2+ with clean MVC structure (`controllers/`, `models/`, `views/`, `helpers/`).
- **Database Layer**: PDO prepared statements with transaction integrity. Dual-mode support for **MySQL / MariaDB** (Production) and **SQLite** (Local Zero-Config Preview).
- **Frontend Architecture**: Bespoke Vanilla CSS token system adhering to [DESIGN.md](DESIGN.md) and clinical UI standards. Zero generic AI templates, zero unboxed borders.
- **Typography & Assets**: Modern geometric headings (**Sora**) paired with ergonomic UI text (**Plus Jakarta Sans**), self-hosted in `assets/vendor/fonts/` for offline and air-gapped clinical depot environments.
- **Micro-Interactions**: Tactile 60fps animations, Remix Icon SVG glyphs, Swiper carousels, and GLightbox modal previews.
- **Email Gateway**: Native dual-dispatch engine supporting authenticated Google Workspace / TLS SMTP (`SmtpMailer.php`) alongside standard server mail transports.
- **Security & Compliance**: Indian Digital Personal Data Protection (**DPDP Act 2023**) compliant consent workflows, CSRF token validation on all mutating requests, sliding-window Rate Limiting, MIME-verified media uploads, and XSS sanitization.

---

## ✨ Key Platform Features

### 1. Central Communications & CRM Enquiry Inbox (`/admin/enquiries`)
- **Strict Chronological Ordering**: Queries ordered by `ORDER BY id DESC`, ensuring the newest incoming inquiries always appear at the very top of Page 1.
- **Industrial Data Ledger**: Replaces generic cards with a dense, clinical table ledger displaying `#Ref` ID tags, customer contact details, equipment tags, message previews, status badges, timestamps, and row actions.
- **Interactive Detail Modal & Eye Button**:
  - Each inquiry features a quick-action **Eye Button** (`icon('eye-line')`).
  - Clicking opens an executive modal displaying the complete customer dossier, telephone link (`tel:`), direct WhatsApp button (`wa.me`), email link (`mailto:`), and the full highlighted message.
  - **Instant Status Synchronization**: Opening an unread lead automatically synchronizes status to `Read` via background AJAX. Includes dedicated tactile buttons for **Mark as Read**, **Mark as Replied**, and **Mark as New** with real-time UI updates.
- **20-Record Pagination**: Clean pagination bar (`Showing 1–20 of X enquiries`) preserving search queries and status filters across page turns.
- **Selection & Bulk Processing**: Master "Select All" checkbox triggers a navy `#1b3d6f` bulk action toolbar for mass-deleting or batch-updating leads.
- **Direct Multi-Channel Email Dispatch**: Submissions are automatically forwarded to:
  1. Dedicated Enquiry Notification Alert Email (`setting('alert_email')`)
  2. Official Corporate Email (`setting('email')`)
  3. Configured SMTP Administrator (`setting('smtp_user')`)
- **Sequential Auto-Increment Maintenance**: Automated fixtures reset MySQL auto-increment to `MAX(id) + 1` to prevent synthetic test ID inflation.

### 2. Executive Operations Dashboard (`/admin`)
- **Real-Time Operational Metrics**: 5-card operational ledger tracking Active Equipment Catalogue, Customer Inquiries (with unread counter), Taxonomies, Published Articles, and Verified Media Assets.
- **Priority Attention Alert**: Prominent operational callout highlighting unread procurement leads awaiting hospital turnaround.
- **Storytelling & Narrative Controller**: Quick-access status monitor for homepage sections with live visibility indicators and direct visual editing links.
- **Recent Inquiries Data Stream**: Live tabular stream of latest inbound hospital procurement requests.
- **System Directives Dock**: Deep navy operations panel for instant access to Site Settings, Emergency WhatsApp Hotlines, and Search Engine Indexing directives.

### 3. Technical Catalogue & Shopify-Grade Product Dossier (`/products`)
- **Minimalist Technical Header**: Clean certification meta strip (`PESO / IS:7285`, `QA Verified`) and certified date pairs (`MFC Date` and `Hydro-Test Valid`).
- **Unboxed Hairline Specifications Table**: Structured parameters with crisp row dividers, distinct parameter names, and certified values.
- **Technical Dossier Tabs**: Multi-panel dossier covering Comprehensive Description, Engineered Parameter Ledger, Clinical Deployment Register, and Statutory Indian Gas Cylinder Safety Protocols.
- **Dual Procurement Action Deck**: Instant formal quotation request modal alongside direct WhatsApp chat initiation.

### 4. Live Homepage Narrative Editor (`/admin/sections`)
- Granular CMS editor featuring a synchronized live iframe preview.
- Drag-and-drop section reordering alongside accessible keyboard controls.
- Instant AJAX persistence with zero full-page reloads.

---

## 📁 Repository Directory Structure

```text
shreejigas/
├── .agents/               # Antigravity agent skills & UI/UX design directives
│   └── skills/            # Specialized development workflows & compliance rules
├── assets/                # Public frontend & admin assets
│   ├── admin/             # CMS stylesheets, scripts, and canvas controls
│   ├── css/               # Core design tokens, layout, and refinement CSS
│   ├── images/            # Certified product photography, logos, and medical seal
│   ├── js/                # Client-side components, forms, and search filters
│   └── vendor/            # Bundled local fonts, RemixIcon, Swiper, and GLightbox
├── config/                # Platform configuration
│   ├── config.php         # Application constants and environment readers
│   ├── database.php       # PDO connection factory (MySQL / SQLite)
│   └── resources.php      # CMS schemas, field validation, and navigation definitions
├── controllers/           # MVC Controllers
│   ├── Admin/             # AdminController (CMS, CRM, SEO, Media, Auth)
│   ├── EditorialController.php
│   ├── HomeController.php
│   ├── PageController.php # Static pages & multi-destination enquiry dispatch
│   └── ProductController.php
├── database/              # Database migration & starter seeding
│   ├── install.php        # Unified database table creator & migrator
│   └── seed.php           # Default content, categories, and settings
├── helpers/               # Framework utilities
│   ├── Auth.php           # Secure PBKDF2/Bcrypt authentication & session manager
│   ├── Database.php       # Singleton PDO wrapper
│   ├── EmailTemplate.php  # High-contrast responsive email templates (Light/Dark)
│   ├── RateLimit.php      # Sliding-window rate limiter
│   ├── SmtpMailer.php     # Pure PHP TLS/STARTTLS SMTP socket client
│   └── functions.php      # View helpers, CSRF tokens, asset URL builders
├── models/                # Data abstractions
│   ├── ContentModel.php   # Generic CRUD abstraction for database entities
│   └── Setting.php        # Cached key-value site configuration store
├── scripts/               # Production installation & database export utilities
│   ├── configure-local.php# Local setup helper
│   ├── export-preview.php # Database content export utility
│   ├── import-preview.php # Database content import utility
│   ├── install.php        # CLI database installer
│   └── optimise-image.php # WebP asset optimizer
├── storage/               # Application runtime storage (protected via .htaccess)
├── views/                 # PHP presentation templates
│   ├── admin/             # Dashboard, Enquiries ledger, Content editors
│   ├── layouts/           # Structural headers, footers, and offcanvas navigation
│   └── pages/             # Public pages (Home, Products, About, Contact, Blog)
├── .gitignore             # Strict exclusion rules for logs, uploads & local credentials
├── .htaccess              # Apache URL rewrite engine and security lockdown
├── ADMIN.md               # Complete CMS & Admin guide
├── BACKEND.md             # Architecture, session handling, and database docs
├── DESIGN.md              # Brand tokens, layout standards, and Shopify-style rules
├── index.php              # Front controller & request router
├── schema.sql             # MariaDB / MySQL production schema
└── WEBSITE.md             # Content strategy and legal policy documentation
```

---

## 🚀 Installation & Local Setup

### Prerequisites
- **Web Server**: Apache 2.4+ (with `mod_rewrite` enabled) via XAMPP, WampServer, or Docker.
- **PHP**: PHP 8.2 or higher with extensions: `pdo_mysql`, `pdo_sqlite`, `gd`, `fileinfo`, `mbstring`, `openssl`, `json`.
- **Database**: MariaDB 10.4+ or MySQL 8.0+.

### 1. Clone & Configure
Clone the repository into your local web root:
```bash
git clone https://github.com/milindsaugat/shree-ji-gas-crm-webiste-.git c:/xampp/htdocs/shreejigas
```

### 2. Configure Database & Environment
Create a local configuration file at `config/local.php` (this file is automatically excluded from Git to prevent credential leaks):
```php
<?php
return [
    'DB_DRIVER'    => 'mysql',
    'DB_HOST'      => '127.0.0.1',
    'DB_PORT'      => '3306',
    'DB_NAME'      => 'shreejigas',
    'DB_USER'      => 'root',
    'DB_PASS'      => '',
    'APP_URL'      => 'http://localhost/shreejigas',
    'APP_BASE_PATH'=> '/shreejigas',
    'MAIL_ENABLED' => '1',
];
```

### 3. Initialize Database Schema
Import `schema.sql` into your database using phpMyAdmin, or run the automated installer:
```bash
php -f database/install.php
```

### 4. Create the Initial Administrator Account
Navigate to:
```text
http://localhost/shreejigas/admin/setup
```
Enter your desired administrative credentials (Username, Official Email, and a 12–72 character password). Once the first administrator is created, the setup endpoint automatically locks.

---

## ⚙️ Configuration Reference

| Parameter | Default | Description |
|---|---|---|
| `DB_DRIVER` | `sqlite` | Database engine (`mysql` for production, `sqlite` for preview). |
| `DB_HOST` | `127.0.0.1` | MySQL server host address. |
| `DB_PORT` | `3306` | MySQL port. |
| `DB_NAME` | `shreejigas` | Database name. |
| `DB_USER` | `root` | Database username. |
| `DB_PASS` | `""` | Database password. |
| `APP_URL` | Auto-detected | Canonical base URL for SEO and notifications. |
| `MAIL_ENABLED` | `0` | Global toggle for outbound mail notifications. |
| `SMTP_ENABLED` | `0` | Enable authenticated TLS socket SMTP dispatch. |
| `SMTP_HOST` | `smtp.gmail.com`| SMTP server host. |
| `SMTP_PORT` | `587` | SMTP port (587 for TLS, 465 for SSL). |
| `SMTP_USER` | `""` | SMTP login account username. |
| `SMTP_PASS` | `""` | 16-character Google Workspace App Password. |
| `OTP_EXPIRY_MINUTES`| `10` | Password-reset OTP expiration window (5–30 min). |

---

## 🩺 Quality Assurance & Reliability Standards

The application is engineered and audited against 12 core clinical and operational standards:
1. **WCAG & Single H1 Architecture**: Every public endpoint features semantic HTML5 hierarchy with zero header collisions.
2. **Deterministic Canonical SEO**: Dynamic XML sitemap and robots directives generated from active database records.
3. **Instant Interactive Filtering**: Sector-based catalogue faceted search and sorting with pure client-side acceleration.
4. **Resilient Lead Capture**: DPDP Act 2023 compliant explicit privacy consent and dual-mode email & WhatsApp forwarding.
5. **Strict Server-Side Input Validation**: Cryptographic CSRF token checks and sliding-window rate limiting on all forms.
6. **Isolated Upload Ecosystem**: Uploads directory strictly restricted via `.htaccess` from executing scripts.
7. **Fluid Responsive Viewports**: Tailored layouts verified across mobile (390px), tablet (768px), and desktop (1440px).
8. **Enterprise Authentication**: Secure PBKDF2/Bcrypt hash verification with 30-minute idle session expiry.
9. **Zero Premature Publishing**: Homepage section drafting and iframe sandbox previews before public publishing.
10. **Atomic Content Persistence**: Product specifications and custom search metadata update in synchronized transactions.
11. **Sequential Auto-Increment Integrity**: Enquiry records adhere to sequential ordering without ID inflation.
12. **Cross-Platform Compatibility**: Bundled Google Fonts and SVG assets function seamlessly in air-gapped environments.

---

## 🔒 Security Hardening

- **SQL Injection Prevention**: 100% of queries use PDO parameter binding. No raw concatenation.
- **CSRF Defense**: Synchronizer Token Pattern on all POST endpoints with cryptographic token verification.
- **File Upload Protection**: Files in `assets/uploads/` are restricted via `.htaccess` from executing PHP, CGI, or scripts (`Options -Indexes -ExecCGI`). All uploaded media is validated via MIME inspection and converted to secure WebP images.
- **Directory Traversal Protection**: Direct HTTP access to `config/`, `storage/`, `database/`, and SQL schemas is forbidden by Apache configuration.
- **Session Security**: Admin sessions use `HttpOnly`, `SameSite=Lax`, strict session fixation regeneration, and 30-minute inactivity timeouts.

---

## 📄 License & Attribution

&copy; 2026 **Shree Ji Gas Industries**. All Rights Reserved.  
Crafted with precision for certified hospital and industrial gas distribution.
