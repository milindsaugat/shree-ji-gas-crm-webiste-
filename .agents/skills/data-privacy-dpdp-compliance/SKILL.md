---
name: data-privacy-dpdp-compliance
description: >-
  Use this skill to implement legal data privacy compliance, Digital Personal Data Protection (DPDP Act 2023)
  guidelines, explicit user consent checkboxes, data minimization, and privacy-first web forms.
---

# Data Privacy & DPDP Compliance Skill

This skill enforces technical and legal standards for collecting, processing, and storing personal user data under modern data privacy frameworks (DPDP Act 2023 & GDPR).

## Core Compliance Principles

1. **Explicit, Affirmative Consent**:
   - Consent must be freely given, specific, informed, and unambiguous.
   - Pre-ticked checkboxes are strictly illegal. Checkboxes must default to unchecked (`checked="false"`).
   - The consent notice must explicitly state who is collecting the data (e.g. `Shree Ji Gas Industries`), what purpose it serves, and link to the full Privacy Policy:
     ```html
     <label class="checkbox-label consent-checkbox">
         <input type="checkbox" name="consent" value="1" required>
         <span>I consent to Shree Ji Gas Industries collecting and processing my contact details and equipment requirements in accordance with applicable Data Protection (DPDP) regulations and privacy guidelines. <small class="req">*</small></span>
     </label>
     ```

2. **Data Minimization & Purpose Limitation**:
   - Collect only data strictly necessary for fulfilling the user's request (e.g. name, phone, optional email, message).
   - Do not demand irrelevant personal identifiers (e.g. personal Aadhaar/ID) on initial inquiry forms.
   - Enforce server-side validation rejecting any request lacking verified affirmative consent.

3. **User Rights & Transparency**:
   - Every page footer and form must link to a transparent Privacy Policy (`/privacy`) detailing:
     - Categories of data collected.
     - Lawful basis of processing (consent / contract fulfillment).
     - Retention period.
     - Mechanism for requesting data correction or deletion (e.g. contact email).
   - Third-party tracking and external services must be explicitly disclosed.
