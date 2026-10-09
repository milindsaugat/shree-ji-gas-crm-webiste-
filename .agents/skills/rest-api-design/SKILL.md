---
name: rest-api-design
description: >-
  Use this skill to design consistent RESTful APIs, standardized JSON response envelopes,
  proper HTTP status codes, error payloads, pagination, query filtering, and idempotent actions.
---

# REST API Design Skill

This skill defines rules for clean, intuitive, standards-compliant JSON APIs and AJAX endpoints.

## Core API Principles

1. **Uniform Response Envelope**:
   - Every API response must return valid JSON with `Content-Type: application/json; charset=UTF-8`.
   - Use a standardized envelope:
     ```json
     {
       "ok": true,
       "data": { ... },
       "message": "Operation completed successfully"
     }
     ```
   - On error:
     ```json
     {
       "ok": false,
       "error": "Validation failed",
       "message": "Please enter a valid phone number",
       "errors": { "phone": "Invalid format" }
     }
     ```

2. **Accurate HTTP Status Codes**:
   - `200 OK`: Successful read or update.
   - `201 Created`: Resource successfully created.
   - `400 Bad Request`: Malformed JSON or syntax error.
   - `401 Unauthorized`: Authentication required.
   - `403 Forbidden`: Authenticated but unauthorized, or invalid CSRF.
   - `404 Not Found`: Target resource does not exist.
   - `422 Unprocessable Content`: Domain/validation errors (e.g. invalid phone, short message, missing consent).
   - `429 Too Many Requests`: Rate limit threshold exceeded.
   - `500 Internal Server Error`: Unexpected server exception.

3. **Filtering, Sorting & Pagination**:
   - Support standard query parameters: `?search=...`, `?category=...`, `?sort=az|za|newest`, `?page=1&per_page=12`.
   - Provide pagination metadata: `{ "page": 1, "per_page": 12, "total": 48, "total_pages": 4 }`.
