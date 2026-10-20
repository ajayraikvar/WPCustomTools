<?php
/**
 * Plugin Name: WP Custom Tools
 * Plugin URI: https://www.ajayraikvar.com
 * Description: A modular collection of practical WordPress tools. Includes searchable WooCommerce product categories and is designed for future tools, widgets, and templates.
 * Version: 1.0.0
 * Requires at least: 6.4
 * Requires PHP: 7.4
 * Author: Ajay Raikvar
 * Author URI: https://www.ajayraikvar.com
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-custom-tools
 * Domain Path: /languages
 *
 * @package WPCustomTools
 */

defined( 'ABSPATH' ) || exit;

define( 'WPCUSTOMTOOLS_VERSION', '1.0.0' );
define( 'WPCUSTOMTOOLS_FILE', __FILE__ );
define( 'WPCUSTOMTOOLS_PATH', plugin_dir_path( __FILE__ ) );
define( 'WPCUSTOMTOOLS_URL', plugin_dir_url( __FILE__ ) );

require_once WPCUSTOMTOOLS_PATH . 'includes/class-wpct-plugin.php';

add_action(
	'plugins_loaded',
	static function () {
		\WPCustomTools\Plugin::instance()->init();
	}
);
