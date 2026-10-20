# WP Custom Tools

A modular WordPress plugin by **Ajay Raikvar** for practical tools, widgets, and templates.

- **Plugin:** WP Custom Tools
- **Author:** Ajay Raikvar
- **Website:** https://www.ajayraikvar.com
- **Repository:** https://github.com/ajayraikvar/WPCustomTools
- **Current version:** 1.0.2
- **License:** GPL-2.0-or-later

## Features

### WooCommerce product category search
Adds a search field specifically inside the **All categories** panel in the WooCommerce product editor. It filters the complete category checklist, including nested subcategories. It does not filter the **Most Used** list and does not change the saved product/category data.

### WP Custom Tools admin menu
Adds the **WP Custom Tools** menu for administrators with:
- A simple dashboard for current tools.
- An **Updates** submenu with a manual check against the **Stable tag** in this repository's public readme.txt.
- A status display for up-to-date, newer-version, or failed-check results.

The check happens only when an administrator clicks **Check for Updates**. It uses a fixed HTTPS GitHub raw URL, short timeout, response size limit, nonce validation, and the **manage_options** capability. This checks the published source version; it is not an automatic installer/updater.

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- WooCommerce active for the category-search feature

## Installation

1. Download the installable ZIP with wp-custom-tools.php at the root of the wp-custom-tools folder.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin** and upload the ZIP.
3. Activate **WP Custom Tools**.
4. Edit or create a WooCommerce product. Search appears in the **All categories** panel.
5. Use **WP Custom Tools → Updates → Check for Updates** for a manual GitHub version check.

## Structure

```text
wp-custom-tools/
├── wp-custom-tools.php
├── includes/
│   ├── class-wpct-plugin.php
│   ├── class-wpct-category-search.php
│   └── class-wpct-admin-menu.php
├── assets/
│   ├── css/category-search.css
│   └── js/category-search.js
├── .github/workflows/php-lint.yml
├── LICENSE
├── README.md
└── readme.txt
```

Future features should be added as standalone modules with their own scoped hooks and assets. Planned options include additional tools, widgets, templates, and Elementor-oriented product-grid features.

## Security and data safety

- PHP files exit when accessed outside WordPress.
- Category-search assets load only on product editor pages for users with the WooCommerce edit_products capability.
- Search is entirely client-side and read-only.
- The manual update check requires manage_options and a WordPress nonce.
- It contacts only the fixed public GitHub version-source URL when an administrator explicitly requests a check.
- No product/category records are changed and no third-party JavaScript or PHP dependencies are bundled.

No software can be guaranteed completely secure. Please test in staging and keep a restorable backup before deploying updates.

## GitHub version check limitation

The Updates page compares the installed version with the repository's Stable tag. It reports whether a newer version is listed and links to GitHub Releases / repository. It does not silently install or automatically replace plugin files.

## Development checks

GitHub Actions runs PHP syntax checks on PHP 8.3 and 8.5 and JavaScript syntax validation with Node.js.

## License

GPL-2.0-or-later. See LICENSE.
