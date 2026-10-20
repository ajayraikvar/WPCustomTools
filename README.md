# WP Custom Tools

A modular WordPress plugin by **Ajay Raikvar** for practical tools, widgets, and templates.

- **Plugin:** WP Custom Tools
- **Author:** Ajay Raikvar
- **Website:** https://www.ajayraikvar.com
- **Repository:** https://github.com/ajayraikvar/WPCustomTools
- **Current version:** 1.0.3
- **License:** GPL-2.0-or-later

## Features

### WooCommerce product category search
Adds a search field specifically inside the **All categories** panel in the WooCommerce product editor. It filters the full category checklist, including nested subcategories. It does not filter the **Most Used** list and does not change stored product/category data.

### Additional Keywords
Adds a **WP Custom Tools — Additional Keywords** meta box to public post types with an editor UI, including posts, pages, and WooCommerce products.

- Enter one phrase per line or separate phrases with commas.
- Stores up to 50 unique, sanitized phrases per content item in the private post meta key `_wpct_additional_keywords`.
- Shows a phrase count and a **Copy Keywords** button.
- Access the usage guide at **WP Custom Tools → Additional Keywords**.

This is a standalone keyword manager. It **does not** unlock or bypass AIOSEO Pro, write into AIOSEO's private metadata, or automatically affect AIOSEO's content score. You can copy the phrase list and use it where permitted by the SEO plugin and your licence.

### WP Custom Tools admin menu and update check
Adds the **WP Custom Tools** menu for administrators with:
- Dashboard and tools overview.
- An Additional Keywords usage page.
- An Updates submenu with a manual check against the **Stable tag** in the public repository's readme.txt.

The version check happens only when an administrator clicks **Check for Updates**. It uses a fixed HTTPS GitHub raw URL, a short timeout and response size limit, nonce validation, and the **manage_options** capability. It reports version status and links to the GitHub workflow artifact / Releases page; it does not install or replace plugin files automatically.

## Requirements

- WordPress 6.4 or newer
- PHP 7.4 or newer
- WooCommerce active for the product category-search feature

## Installation

1. Download the installable ZIP with `wp-custom-tools.php` at the root of the `wp-custom-tools/` folder.
2. In WordPress, open **Plugins → Add New Plugin → Upload Plugin** and upload the ZIP.
3. Activate **WP Custom Tools**.
4. Edit a WooCommerce product and use search inside **All categories**.
5. Edit a post, page, or product and locate **WP Custom Tools — Additional Keywords** in the editor meta boxes.
6. Use **WP Custom Tools → Updates → Check for Updates** to compare with GitHub's published Stable tag.

## Structure

```text
wp-custom-tools/
├── wp-custom-tools.php
├── includes/
│   ├── class-wpct-plugin.php
│   ├── class-wpct-category-search.php
│   ├── class-wpct-admin-menu.php
│   └── class-wpct-additional-keywords.php
├── assets/
│   ├── css/category-search.css
│   ├── css/additional-keywords.css
│   ├── js/category-search.js
│   └── js/additional-keywords.js
├── .github/workflows/php-lint.yml
├── LICENSE
├── README.md
└── readme.txt
```

Future features should be added as standalone modules with their own scoped hooks and assets. Planned options include more tools, widgets, templates, and Elementor-oriented product-grid features.

## Security and data safety

- PHP files exit when accessed outside WordPress.
- Category search is entirely client-side and read-only.
- Additional Keywords saving requires a valid WordPress nonce and the current user's `edit_post` capability; entered phrases are sanitized, de-duplicated, and capped at 50.
- Additional Keywords are saved only when a user saves the associated post/page/product in the editor. Clearing the field and saving replaces that plugin-owned list with an empty list.
- The manual update check requires `manage_options` and a WordPress nonce.
- Keyword management creates no network calls and no third-party JavaScript/PHP dependencies.
- No AIOSEO internal metadata is modified.

No software can be guaranteed completely secure. Test in staging and maintain a restorable backup before deploying updates.

## Downloading a build

Each successful GitHub Actions run creates the installable ZIP as the **WPCustomTools-installable** artifact. Open [the workflow](https://github.com/ajayraikvar/WPCustomTools/actions/workflows/php-lint.yml), choose a successful run, and download the artifact. Artifacts are retained for 30 days.

## Development checks

GitHub Actions runs PHP syntax checks on PHP 8.3 and 8.5, JavaScript syntax checks, and installable ZIP integrity/structure checks.

## License

GPL-2.0-or-later. See `LICENSE`.
