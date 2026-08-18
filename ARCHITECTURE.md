# Architecture Overview
This document outlines the architectural structure and technical decisions for the **Elynt Contact CTA Button** WordPress plugin. Designed with Object-Oriented Programming (OOP) and Clean Code principles, the plugin provides a robust backend, a modern AJAX-driven admin interface, and an optimized frontend display for WhatsApp Call-to-Action buttons.

## 1. Project Structure
This section details the directory and file structure of the plugin, categorized by architectural layers to ensure a clean separation of concerns.

```text
plugin/
├── elynt-contact-cta-button.php     # Main bootstrap file, handles plugin initialization and activation/deactivation hooks
├── includes/                 # Core plugin logic and backend operations
│   ├── class-activator.php   # Handles installation routines (e.g., creating custom DB tables)
│   ├── class-db-manager.php  # Dedicated Database layer for CRUD operations
│   └── class-elynt-contact-cta-button.php # Core orchestrator class, loads dependencies and hooks
├── admin/                    # Internal Admin UI and AJAX logic
│   ├── class-admin.php       # Registers menu pages and enqueues admin assets
│   ├── class-ajax-handler.php# Processes all AJAX requests from the admin interface
│   ├── vendors/              # 3rd party libraries for the admin panel
│   ├── assets/               # Static assets for the admin panel
│   │   ├── css/              # CSS styles for the admin panel
│   │   ├── js/               # JavaScript for the Admin App (AJAX navigation and form handling)
│   └── views/                # HTML templates for the Admin UI
│       ├── app-container.php # Main wrapper for the SPA, contains the header and an empty container to load partials
│       └── partials/         # Component views loaded dynamically via AJAX
│           ├── list.php      # Displays the grid of existing buttons with edit/delete actions
│           └── form.php      # Form to create/edit buttons, configure layouts, text, number and initial message
├── public/                   # Public-facing Frontend logic
│   ├── class-public.php      # Registers frontend hooks (wp_footer) and shortcodes
│   ├── css/                  # CSS for the WhatsApp buttons
│   └── views/                # HTML templates for rendering the buttons
├── uninstall.php             # Cleanup script, deletes database tables when the plugin is uninstalled
└── ARCHITECTURE.md           # This document
```

## 2. High-Level System Diagram
The plugin follows a modular pattern, isolating database operations from the presentation layer (Admin and Public).

```text
[WordPress Admin] <--(AJAX)--> [Admin AJAX Handler] <--> [DB Manager] <--> [Custom DB Table]
                                                                                |
[Site Visitor] <--(HTML/CSS)--> [Public Shortcode/Footer Hook] <----------------+
```

## 3. Core Components

### 3.1. Core Logic (`includes/`)
**Description:** The heart of the plugin. It initializes the system, registers all necessary hooks, and manages database interactions.
**Key Classes:**
*   `ELYNCOCT_Chat_Button`: The main orchestrator that wires up `Admin`, `Public`, and `AJAX` classes.
*   `ELYNCOCT_Chat_Button_DB_Manager`: Encapsulates all SQL queries. Controllers must use this manager rather than writing raw SQL.
*   `ELYNCOCT_Chat_Button_Activator`: Runs on plugin activation to ensure the required custom table is created using `dbDelta`.
*   `uninstall.php`: Automatically called by WordPress when the plugin is deleted. It performs a complete cleanup by dropping the custom database tables.

### 3.2. Frontend Guides

#### 3.2.1. Internal Admin Panel Frontend (`admin/`)
**Name:** SaaS Admin Dashboard
**Description:** The admin interface operates as a Single Page Application (SPA) within the WordPress dashboard. It uses AJAX to navigate between the "List" and "Form" views without reloading the page, providing a fast, modern, App-like experience.
**Aesthetic:** Clean, professional SaaS design (utilizing whites, soft shadows, rounded borders, and Indigo accents).
**CSS Guidelines (Crucial):**
*   **High Specificity:** To prevent conflicts with WordPress themes, Elementor, or other plugins, **all** CSS selectors must be prefixed with `body .ecb-admin-wrap`.
*   **No Generic Classes:** Use the `ecb-` prefix for all classes (e.g., `.ecb-card`, `.ecb-btn`).
*   **Icons:** Use WordPress native Dashicons for UI elements.

#### 3.2.2. Public Button Frontend (`public/`)
**Name:** WhatsApp CTA Display
**Description:** Responsible for rendering the actual chat buttons to the site visitors. Buttons can be injected automatically globally via the `wp_footer` hook (for Fixed buttons) or manually placed via shortcodes (for Inline buttons).
**Aesthetic:** Recognizable, conversion-optimized WhatsApp styling (classic green `#25D366`, SVG icon, subtle hover animations).
**CSS Guidelines:**
*   Styles are scoped strictly to the `.ecb-whatsapp-button` class and its modifiers (`.ecb-fixed`, `.ecb-inline`, `.ecb-pos-left`, etc.).
*   Keep assets extremely lightweight to avoid impacting the site's frontend load performance.

## 4. Data Stores

### 4.1. Custom Database Table
**Name:** `{prefix}elyncoct_chat_buttons`
**Type:** MySQL (WordPress Custom Table)
**Purpose:** Stores all configured chat buttons independently of the `wp_posts` or `wp_options` tables to guarantee performance and data integrity.
**Key Columns:**
*   `id`: Primary Key (BigInt).
*   `name`: Internal identification name.
*   `type`: Defines the rendering behavior (`fixed` or `inline`).
*   `status`: Toggle visibility (`active` or `inactive`).
*   `options`: A serialized JSON string containing specific configurations:
    *   `text` (string): Button Call-to-Action text.
    *   `number` (string): WhatsApp phone number in international format.
    *   `position` (string): Fixed layout position on screen (`left`, `right`, `center`).
    *   `layout` (string): Visual layout style (`standard` or `icon_only`).
    *   `initial_message` (string): Pre-filled text message for the WhatsApp chat.
    *   `bg_color` / `text_color` / `border_color` (string): Color hex codes.
    *   `icon_size` / `font_size` / `border_size` (int): Element dimensions in pixels.
    *   `display_conditions` (array): Configures conditional visibility for Fixed buttons on the frontend:
        *   `target` (string): `'everywhere'` (default) or `'custom'`.
        *   `post_types` (array): List of target post types (e.g., `'post'`, `'page'`) containing:
            *   `condition` (string): `'all'` (display on all singular pages of this post type) or `'specific'` (display only on manually selected posts).
            *   `ids` (array): Array of integer post IDs to target when the condition is set to `'specific'`.
        *   `taxonomies` (array): List of target taxonomies (e.g., `'category'`, `'post_tag'`) containing:
            *   `condition` (string): `'all'` (display on all archive pages of this taxonomy) or `'specific'` (display only on manually selected term archives).
            *   `ids` (array): Array of integer term IDs to target when condition is set to `'specific'`.
        *   `special_pages` (array): Map of booleans for special archive and error views (`'blog_index'`, `'search'`, `'author'`, `'date'`, `'not_found_404'`).
        *   `exclusions` (array): Configures pages/posts where the button must never be rendered, evaluated before inclusion rules:
            *   `post_types` (array): Map of post type slugs to an array containing `ids` (array of integer post/page IDs to exclude).