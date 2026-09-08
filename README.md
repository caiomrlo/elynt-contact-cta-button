# Elynt Contact CTA Button

WordPress plugin for creating and managing floating and inline Call-to-Action WhatsApp button.

---

## 🚀 Development Environment (Docker)

The environment includes **WordPress (PHP 8.3)**, **MySQL 8.4**, and **WP-CLI** with automatic installation and plugin activation.

### 1. Start the Environment

```bash
docker compose up -d
```

### 2. Access

* **Site:** [http://localhost:8080](http://localhost:8080)
* **Admin Panel:** [http://localhost:8080/wp-admin/](http://localhost:8080/wp-admin/)
  * **Username:** `admin`
  * **Password:** `admin`

> ⚡ **Real-Time Updates:** Plugin files in the repository root are mounted directly into the container. Any edits to PHP, CSS, or JS reflect immediately without restarting Docker.

---

## 🔍 Code Quality & Linter (PHPCS / WPCS)

The container comes with **Composer**, **PHP_CodeSniffer**, and **WordPress Coding Standards** pre-installed.

### Run PHPCS (WordPress Standards):
```bash
docker compose exec -w /var/www/html/wp-content/plugins/elynt-contact-cta-button wordpress phpcs
```

### Auto-fix Formatting Issues (PHPCBF):
```bash
docker compose exec -w /var/www/html/wp-content/plugins/elynt-contact-cta-button wordpress phpcbf
```

### Quick PHP Syntax Check (`php -l`):
```bash
docker compose exec -w /var/www/html/wp-content/plugins/elynt-contact-cta-button wordpress sh -c "find . -name '*.php' -exec php -l {} \;"
```

---

## 🛑 Stop the Environment

* **Stop containers:**
  ```bash
  docker compose down
  ```

* **Reset database and volumes from scratch:**
  ```bash
  docker compose down -v
  ```
