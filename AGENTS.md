# Agent Guidelines

## 1. Project Context & Architecture
* **Always read `./ARCHITECTURE.md`** before planning or executing architectural changes, adding new features, or modifying core flows.
* All plugin runtime source code resides directly in the project root (e.g., `./elynt-contact-cta-button.php`, `./includes/`, `./admin/`, `./public/`).

## 2. Skills & Standards
* Check and apply available skills in `./.agents/skills/` when relevant (e.g., `./.agents/skills/plugin-standards/SKILL.md` for WordPress standards and conventions).

## 3. Code Quality & Conventions
* Comply with WordPress Coding Standards (WPCS) and ensure PHP 7.4 to 8.3 compatibility.
* Use strict escaping (`esc_html`, `esc_attr`, `esc_url`) and sanitization (`sanitize_text_field`, etc.).
* Verify nonces and user capabilities on all admin and AJAX requests.
* Use the text domain `elynt-contact-cta-button` for all internationalized strings.
