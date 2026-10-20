# WP Custom Tools

A modular WordPress plugin by **Ajay Raikvar** for practical admin tools, widgets, and templates.

- **Plugin:** WP Custom Tools
- **Author:** Ajay Raikvar
- **Website:** https://www.ajayraikvar.com
- **Repository:** https://github.com/ajayraikvar/WPCustomTools
- **Current version:** 1.0.1
- **License:** GPL-2.0-or-later

## Current feature

### WooCommerce product category search
Adds a searchable input before the category checklist within the WooCommerce product categories panel. It filters the existing checklist in the browser and keeps ancestor categories visible when a child category matches.

The feature:
- Runs only on WordPress admin product edit/new screens.
- Enqueues assets only when the current user has the WooCommerce `edit_products` capability.
- Does not change product, category, term, or database records.
- Does not send category data to external services.
- Does not add public-facing assets.
- Does not replace or modify WooCommerce files.
- Preserves WordPress checkboxes and normal category selection behaviour.

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- WooCommerce installed and active for the category-search feature

## Installation

1. Download the installable ZIP from the repository or create a ZIP with `wp-custom-tools.php` at the root of the plugin folder, beside the `includes/` and `assets/` directories.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the ZIP and activate **WP Custom Tools**.
4. Edit or create a WooCommerce product. A **Search product categories…** field should appear immediately before the checklist in the **All categories** panel.

## Structure

```text
wp-custom-tools/
├── wp-custom-tools.php
├── includes/
│   ├── class-wpct-plugin.php
│   └── class-wpct-category-search.php
├── assets/
│   ├── css/category-search.css
│   └── js/category-search.js
├── .github/workflows/
│   └── php-lint.yml
├── .gitignore
├── LICENSE
├── README.md
└── readme.txt
```

Future functionality should be added as separate modules under `includes/`, with their own assets and narrowly scoped hooks. Planned areas may include additional tools, widgets, and Elementor-oriented product-grid templates.

## Security notes

- All PHP entry points guard against direct access with `defined('ABSPATH') || exit`.
- Assets are enqueued only on the product edit screen and only for users authorized to edit products.
- The current feature is read-only with respect to WordPress data.
- No network requests, telemetry, REST endpoints, database writes, or settings are created by this feature.
- Future features must validate capabilities, use nonces for state-changing actions, sanitize input, escape output, and use prepared database operations where appropriate.

No software can be guaranteed completely secure. Test updates and new features in a staging environment and maintain restorable backups.

## Updating through GitHub

Use Git as the source of truth for development. Commit changes to this repository and deploy a reviewed release ZIP. This release does **not** include an automatic in-dashboard updater. A GitHub-hosted update checker can be added as a separate reviewed module later.

## Development

The GitHub Actions workflow checks PHP syntax on PHP 8.3 and 8.5, and JavaScript syntax with Node.js.

## License

Licensed under GPL-2.0-or-later. See `LICENSE`.
