---
name: micro-interactions-animations
description: >-
  Use this skill to implement smooth 60fps CSS transitions, hover lift effects, pulsating status dots,
  tactile button feedback, card elevations, and prefers-reduced-motion accessibility.
---

# Micro-Interactions & CSS Animations Skill

This skill governs the design of subtle, delightful, performance-conscious micro-animations that make an interface feel responsive and alive.

## Principles of Micro-Interactions

1. **Hardware-Accelerated Properties**:
   - Animate ONLY `transform` and `opacity`. Avoid animating layout-triggering properties (`width`, `height`, `margin`, `padding`, `top`, `left`).
   - Use cubic-bezier easing or standard ease curves (`transition: transform .2s ease, box-shadow .2s ease`).

2. **Tactile Hover & Elevation States**:
   - Interactive cards: Subtle lift (`transform: translateY(-2px); box-shadow: 0 8px 24px rgba(15,41,74,.08)`).
   - Buttons: Gentle scale or translate with active depression state (`:active { transform: translateY(0); }`).
   - Action arrows: Directional slide on hover (`transform: translateX(3px)` or `translate(2px, -2px)` for diagonal arrows).

3. **Status Indicators & Pulse Animations**:
   - For live operational status pills, use a soft pulsating dot:
     ```css
     .pulse-dot {
         width: 7px;
         height: 7px;
         border-radius: 50%;
         background: #10b981;
         box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
         animation: pulse-ring 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
     }
     @keyframes pulse-ring {
         0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
         70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
         100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
     }
     ```

4. **Accessibility (Reduced Motion)**:
   - Always respect user system preferences:
     ```css
     @media (prefers-reduced-motion: reduce) {
         *, ::before, ::after {
             animation-duration: 0.01ms !important;
             animation-iteration-count: 1 !important;
             transition-duration: 0.01ms !important;
             scroll-behavior: auto !important;
         }
     }
     ```
