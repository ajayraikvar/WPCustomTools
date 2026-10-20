=== WP Custom Tools ===
Contributors: ajayraikvar
Tags: woocommerce, admin-tools, category-search, widgets, templates
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular collection of practical WordPress tools by Ajay Raikvar.

== Description ==
WP Custom Tools is designed to grow with independently maintained tools, widgets, and templates.

Current feature: adds a search box to the WooCommerce product category checklist on the classic WordPress product editor screen. Search filters the checklist locally in your browser and does not modify stored product or category data.

Plugin author: Ajay Raikvar
Author website: https://www.ajayraikvar.com
Repository: https://github.com/ajayraikvar/WPCustomTools

== Installation ==
1. Upload the plugin folder to /wp-content/plugins/wp-custom-tools/ or install a ZIP with wp-custom-tools.php at its root.
2. Activate WP Custom Tools in Plugins.
3. Edit a WooCommerce product and use the Search product categories field above the category checklist.

== Requirements ==
- WordPress 6.4 or newer.
- PHP 7.4 or newer.
- WooCommerce active for the category search feature.

== Security ==
The current feature is read-only and runs only on WooCommerce product edit/new screens for users with the WooCommerce edit_products capability. It does not create database records, network requests, settings, or REST endpoints. Test in staging and maintain backups before deploying software updates. No software can be guaranteed completely secure.

== Changelog ==
= 1.0.1 =
* Fix category search field insertion in nested WooCommerce category metabox markup.
* Enqueue the feature only for users with the edit_products capability.
* Refresh asset cache version.

= 1.0.0 =
* Initial version with WooCommerce product category search.
