=== WP Custom Tools ===
Contributors: ajayraikvar
Tags: woocommerce, admin-tools, category-search, widgets, templates
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular collection of practical WordPress tools by Ajay Raikvar.

== Description ==
WP Custom Tools is designed to grow with independently maintained tools, widgets, and templates.

Current feature: adds a search field to the All categories checklist in the WooCommerce product editor, plus an admin menu with a manual GitHub version check. Category search filters the checklist locally in the browser and does not modify stored product or category data.

Plugin author: Ajay Raikvar
Author website: https://www.ajayraikvar.com
Repository: https://github.com/ajayraikvar/WPCustomTools

== Installation ==
1. Upload the wp-custom-tools plugin folder to /wp-content/plugins/ or install the installable ZIP with wp-custom-tools.php at its root.
2. Activate WP Custom Tools in Plugins.
3. Edit a WooCommerce product and use the Search all product categories field inside the All categories tab.
4. Open WP Custom Tools in the WordPress admin menu to check the installed version or manually check GitHub for an update.

== Requirements ==
- WordPress 6.4 or newer.
- PHP 7.4 or newer.
- WooCommerce active for the category search feature.

== Security ==
The category search is read-only. Admin menu and manual version checks require the manage_options capability, and the update-check form uses a WordPress nonce. The version check requests a fixed public GitHub readme URL only after an administrator submits the check form. The plugin does not modify product/category records. Test changes on staging and maintain restorable backups. No software can be guaranteed completely secure.

== Changelog ==
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
