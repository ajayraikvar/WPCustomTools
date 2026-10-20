<?php
/**
 * Main plugin loader.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

/**
 * Plugin bootstrapper.
 */
final class Plugin {
	/**
	 * Plugin instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register plugin modules.
	 *
	 * @return void
	 */
	public function init() {
		require_once WPCUSTOMTOOLS_PATH . 'includes/class-wpct-category-search.php';
		require_once WPCUSTOMTOOLS_PATH . 'includes/class-wpct-admin-menu.php';

		Category_Search::instance()->init();
		Admin_Menu::instance()->init();
	}
}
