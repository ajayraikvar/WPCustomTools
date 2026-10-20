=== WP Custom Tools ===
Contributors: ajayraikvar
Tags: woocommerce, elementor, product-card, product-video, category-search, keywords, templates
Requires at least: 6.4
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modular collection of practical WordPress tools by Ajay Raikvar.

== Description ==
WP Custom Tools is designed to grow with independently maintained tools, widgets, and templates.

Features:
- Search the full All categories list in WooCommerce product editing screens, separate from Most Used.
- Save up to 50 additional keyword phrases on supported public posts, pages, and products.
- Elementor WP Custom Product Card widget with editable product image or self-hosted MP4/WebM video, title, subtitle/author, rating, review text, variant, promotional badge, currency, current price, MRP, discount, payment offer, delivery text, button label, and link.
- Style controls for card width/alignment, background, padding, border, radius, shadow, media height/fit/background, title/subtitle typography and color, price color/type, badge colors, and button colors/type/radius.
- Manually check the latest source version published on GitHub from WP Custom Tools > Updates.

The Elementor widget is optional and only registers when Elementor is active. Product details are entered manually in Elementor; the widget does not automatically fetch live WooCommerce price, stock, rating, or cart behavior.

Additional Keywords are stored in WP Custom Tools post metadata. This feature does not populate AIOSEO's private metadata, bypass AIOSEO Pro, or automatically change AIOSEO's content score.

Plugin author: Ajay Raikvar
Author website: https://www.ajayraikvar.com
Repository: https://github.com/ajayraikvar/WPCustomTools

== Installation ==
1. Upload the wp-custom-tools plugin folder to /wp-content/plugins/ or install the installable ZIP with wp-custom-tools.php at its root.
2. Activate WP Custom Tools in Plugins.
3. Edit a WooCommerce product and use the Search all product categories field inside the All categories tab.
4. Edit a post, page, or product and find the WP Custom Tools — Additional Keywords box.
5. With Elementor active, open a page/template in Elementor and search for WP Custom Product Card.
6. Open WP Custom Tools > Updates to check the version listed on GitHub.

== Requirements ==
- WordPress 6.4 or newer.
- PHP 7.4 or newer.
- WooCommerce active for the category search feature.
- Elementor active to use the Elementor widget (optional for the rest of the plugin).

== Security ==
The category search is read-only. The Additional Keywords editor requires an edit_post capability and a WordPress nonce; text is sanitized and duplicate phrases are removed. The Elementor widget escapes its editable text, URL and image/video attributes. Admin menu and manual version checks require manage_options, and the version check runs only after an administrator submits the nonce-protected form. The plugin does not add third-party code dependencies. Test on staging and maintain restorable backups. No software can be guaranteed completely secure.

== Changelog ==
= 1.0.4 =
* Add the Elementor WP Custom Product Card widget with editable product image or self-hosted video.
* Add editable product text, rating, pricing, offers, delivery and button fields.
* Add styling controls and admin setup instructions.
* Extend automated PHP, JavaScript and ZIP checks for the widget.

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
