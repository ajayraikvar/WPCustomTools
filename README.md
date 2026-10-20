# WP Custom Tools

A modular WordPress plugin by **Ajay Raikvar** for practical admin tools, widgets, and templates.

- **Plugin:** WP Custom Tools
- **Author:** Ajay Raikvar
- **Website:** https://www.ajayraikvar.com
- **Repository:** https://github.com/ajayraikvar/WPCustomTools
- **Current version:** 1.0.0
- **License:** GPL-2.0-or-later

## Current feature

### WooCommerce product category search
Adds a searchable input above the category checklist in the WooCommerce product editor. It filters the existing checklist in the browser and keeps parent paths visible when a child category matches.

The feature:
- Runs only on WordPress admin product edit/new screens.
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

1. Download the repository as a ZIP, or create a ZIP with `wp-custom-tools.php` at the ZIP root and the `includes/` and `assets/` folders beside it.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin**.
3. Upload the ZIP and activate **WP Custom Tools**.
4. Edit or create a WooCommerce product. A **Search product categories…** field should appear above the category checklist.

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
└── README.md
```

Future functionality should be added as separate modules under `includes/`, with their own assets and narrowly scoped hooks. Planned areas may include additional tools, widgets, and Elementor-oriented product-grid templates.

## Security notes

- All PHP entry points guard against direct access with `defined('ABSPATH') || exit`.
- Assets are enqueued only on the product edit screen, not site-wide.
- The current feature is read-only with respect to WordPress data.
- No network requests, telemetry, REST endpoints, database writes, or settings are created by this feature.
- Future features must validate capabilities, use nonces for state-changing actions, sanitize input, escape output, and use prepared database operations where appropriate.

No software can be guaranteed completely secure. Test upgrades and new features in a staging environment and maintain restorable backups.

## Updating through GitHub

Use Git as the source of truth for development. Commit changes to this repository and deploy a reviewed release ZIP. This initial version does **not** include an automatic in-dashboard updater or a private-token mechanism. A GitHub-hosted update checker can be added as a separate, reviewed module later without embedding personal access tokens in plugin code.

## Development

Run the PHP syntax workflow by pushing or opening a pull request. Local PHP syntax checks can also be run with:

```sh
find . -name '*.php' -print0 | xargs -0 -n1 php -l
```

## License

Licensed under GPL-2.0-or-later. See `LICENSE`.
