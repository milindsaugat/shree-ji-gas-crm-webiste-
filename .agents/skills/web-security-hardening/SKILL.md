---
name: web-security-hardening
description: >-
  Use this skill to audit, harden, and prevent security vulnerabilities including XSS, CSRF,
  SQL Injection, unsafe file uploads, directory traversal, session hijacking, and rate limit abuse.
---

# Web Security Hardening Skill

This skill enforces battle-tested defense-in-depth security principles across the full stack.

## Core Security Controls

1. **Cross-Site Scripting (XSS) Prevention**:
   - Escape all user-supplied data at the point of output rendering:
     ```php
     function e(?string $value): string {
         return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
     }
     ```
   - For rich-text fields, sanitize through an allowlisted tag parser and never trust raw HTML.

2. **Cross-Site Request Forgery (CSRF)**:
   - Generate cryptographically secure random session tokens: `bin2hex(random_bytes(32))`.
   - Embed CSRF hidden token in all forms: `<input type="hidden" name="csrf" value="<?= csrfToken() ?>">`.
   - Validate token on every state-altering endpoint and terminate with `403 Forbidden` if missing or invalid.

3. **Secure File Upload Handling**:
   - Never trust `$_FILES['file']['name']` or `$_FILES['file']['type']`.
   - Validate real MIME type on server using `finfo_file(finfo_open(FILEINFO_MIME_TYPE), $tempPath)`.
   - Enforce strict extension allowlist (e.g. `jpg`, `jpeg`, `png`, `webp`, `pdf`).
   - Store uploads outside public executable directories or disable PHP script execution via `.htaccess` (`SetHandler none` / `RemoveHandler .php`).
   - Generate random unique filenames (e.g. `hash('sha256', ...) . '.' . $ext`).

4. **Directory Traversal & Path Traversal Defense**:
   - Strictly prohibit user input in file system operations.
   - Restrict file operations to absolute allowlisted directory boundaries.
   - Use `basename()` and sanitize paths against `../` or null-byte characters.

5. **Session Security & Headers**:
   - Set session cookies with `HttpOnly`, `SameSite=Lax` (or `Strict`), and `Secure` over HTTPS.
   - Regenerate session ID upon privilege escalation (`session_regenerate_id(true)`).
   - Send security headers: `X-Content-Type-Options: nosniff`, `X-Frame-Options: SAMEORIGIN`, `Referrer-Policy: strict-origin-when-cross-origin`.
