# Agent Guidelines

## 1. Project Context & Architecture
* **Always read `./ARCHITECTURE.md`** before planning or executing architectural changes, adding new features, or modifying core flows.
* All plugin source code resides exclusively in `./plugin/`. Never place plugin runtime code in the project root.

## 2. Skills & Standards
* Check and apply available skills in `./.agent/.skills/` when relevant (e.g., `./.agent/.skills/plugin-standards/SKILL.md` for WordPress standards and conventions).

## 3. Code Quality & Conventions
* Comply with WordPress Coding Standards (WPCS) and ensure PHP 7.4 to 8.3 compatibility.
* Use strict escaping (`esc_html`, `esc_attr`, `esc_url`) and sanitization (`sanitize_text_field`, etc.).
* Verify nonces and user capabilities on all admin and AJAX requests.
* Use the text domain `elynt-contact-cta-button` for all internationalized strings.
