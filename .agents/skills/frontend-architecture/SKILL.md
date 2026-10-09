---
name: frontend-architecture
description: >-
  Use this skill for structuring scalable frontend code, semantic HTML5, Vanilla CSS token systems,
  fluid responsive typography, component encapsulation, accessibility (WCAG AA), and modern layout patterns.
---

# Frontend Architecture Skill

This skill guides the design and implementation of clean, scalable, high-performance web frontends without bloated CSS frameworks.

## Core Directives

1. **Semantic HTML5 Foundation**:
   - Use meaningful semantic tags (`<header>`, `<nav>`, `<main>`, `<article>`, `<section>`, `<aside>`, `<footer>`).
   - Exactly one `<h1>` per page reflecting the primary intent.
   - Distinct heading hierarchy (`h1` -> `h2` -> `h3` -> `h4`) with no skipped levels.
   - Descriptive `alt` attributes on all meaningful images, empty `alt=""` on purely decorative visuals.

2. **Vanilla CSS Token Architecture**:
   - Centralize design tokens in `:root` CSS custom variables (`--color-primary`, `--color-secondary`, `--font-heading`, `--font-body`, `--radius-sm`, `--shadow-md`).
   - Use fluid responsive typography with `clamp(min, preferred, max)` to ensure harmonious scaling from mobile to 4K displays.
   - Group styles modularly: Base/Reset -> Tokens -> Typography -> Layout Grids -> Components -> Utilities -> Media Queries.

3. **Responsive Breakpoints & Layouts**:
   - Build mobile-first or desktop-down with strict breakpoint consistency (`1200px`, `991px`, `767px`, `480px`).
   - Use CSS Grid for 2D page architecture (`grid-template-columns`) and Flexbox for 1D component flows (`display: flex; gap: ...`).
   - Never allow horizontal scroll overflow on any screen size.

4. **Component Encapsulation & Visual Balance**:
   - Avoid generic, nested, card-in-card "AI-generated" boxed look. Use deliberate, balanced 4-sided borders (`border: 1px solid var(--color-border)`) and reject one-sided borders that make UI look incomplete.
   - Provide generous breathing room (`padding: 56px 0 84px;` on major sections).

5. **Accessibility (WCAG AA)**:
   - Ensure contrast ratio >= 4.5:1 for normal text, >= 3:1 for large text against backgrounds.
   - Provide visible focus indicators (`:focus-visible`).
   - Ensure touch targets are at least 44x44px on mobile devices.
