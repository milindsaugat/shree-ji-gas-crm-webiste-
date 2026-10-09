---
name: backend-php-clean-code
description: >-
  Use this skill for writing modern PHP (8.2+), MVC application flow, controller actions,
  content model abstractions, secure session state, exception handling, and robust routing.
---

# Backend PHP Clean Code Skill

This skill enforces standards for reliable, maintainable, modern PHP web applications.

## Key Architecture Rules

1. **Lightweight MVC Structure**:
   - **Controllers**: Handle HTTP input, request validation, authentication, and dispatching to models or views. Controllers must remain thin.
   - **Models**: Encapsulate data access, prepared query execution, schema relationships, and data persistence.
   - **Views**: Pure PHP templates with minimal presentation logic, automatic escaping (`e($str)`), and reusable partial components.

2. **Strict Defensive Programming**:
   - Always validate and sanitize user input before passing it to business logic or database queries.
   - Explicit length bounds on text inputs (e.g. `mb_strlen($name) < 2 || mb_strlen($name) > 150`).
   - Use `try ... catch` blocks for file I/O, database queries, and external network interactions.
   - Never expose raw database errors or stack traces to end users; log to files and return friendly messages.

3. **Dual Transport Support (AJAX & Form Post)**:
   - Check `str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json')` to respond with `jsonResponse(['ok' => true, ...])` when invoked asynchronously.
   - Fall back to standard session flash messages and redirects (`redirect('path')`) for standard browser submissions.

4. **Security Enforcements**:
   - Every state-changing request (`POST`, `PUT`, `DELETE`) must verify a valid CSRF token (`checkCsrf()`).
   - Implement honeypot anti-spam fields (`<input name="website" tabindex="-1">`) to trap bots.
   - Apply strict rate limiting (`RateLimit::allow($key, $maxAttempts, $windowSeconds)`).
