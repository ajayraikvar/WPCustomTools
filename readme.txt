=== WP Custom Tools ===
Contributors: ajayraikvar
Tags: woocommerce, admin-tools, category-search, keywords, templates
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular collection of practical WordPress tools by Ajay Raikvar.

== Description ==
WP Custom Tools is designed to grow with independently maintained tools, widgets, and templates.

Features:
- Search the full All categories list in WooCommerce product editing screens, separate from Most Used.
- Save up to 50 additional keyword phrases on supported public posts, pages, and products.
- Copy the keyword list for use in other workflows.
- Manually check the latest source version published on GitHub from the WP Custom Tools > Updates page.

Additional Keywords are stored in WP Custom Tools post metadata. This feature does not populate AIOSEO's private metadata, bypass AIOSEO Pro, or automatically change AIOSEO's content score.

Plugin author: Ajay Raikvar
Author website: https://www.ajayraikvar.com
Repository: https://github.com/ajayraikvar/WPCustomTools

== Installation ==
1. Upload the wp-custom-tools plugin folder to /wp-content/plugins/ or install the installable ZIP with wp-custom-tools.php at its root.
2. Activate WP Custom Tools in Plugins.
3. Edit a WooCommerce product and use the Search all product categories field inside the All categories tab.
4. Edit a post, page, or product and find the WP Custom Tools — Additional Keywords box.
5. Use WP Custom Tools > Additional Keywords for usage instructions, or WP Custom Tools > Updates to check the version listed on GitHub.

== Requirements ==
- WordPress 6.4 or newer.
- PHP 7.4 or newer.
- WooCommerce active for the category search feature.

== Security ==
The category search is read-only. The Additional Keywords editor requires an edit_post capability and a WordPress nonce; text is sanitized and duplicate phrases are removed. Admin menu and manual version checks require manage_options, and the version check runs only after an administrator submits the nonce-protected form. The plugin does not contact external services for keyword management. Test on staging and maintain restorable backups. No software can be guaranteed completely secure.

== Changelog ==
= 1.0.3 =
* Add the Additional Keywords editor tool with per-post storage and Copy Keywords helper.
* Add an Additional Keywords dashboard/menu page.
* Extend CI checks for the new module, JavaScript and package contents.

= 1.0.2 =
* Search only the full All categories taxonomy panel instead of Most Used.
* Add the WP Custom Tools admin menu and manual GitHub version check.
* Add capability and nonce checks to the updater form.

= 1.0.1 =
* Fix category search field insertion in nested WooCommerce category metabox markup.
* Enqueue the feature only for users with the edit_products capability.
* Refresh asset cache version.

= 1.0.0 =
* Initial version with WooCommerce product category search.
