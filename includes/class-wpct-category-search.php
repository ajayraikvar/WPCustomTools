<?php
/**
 * Search field for WooCommerce product category checklist.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

/**
 * Adds a client-side search field to the product categories meta box.
 */
final class Category_Search {
	/** @var Category_Search|null */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Category_Search
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/** Register hooks. */
	public function init() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Load assets only on the WooCommerce product editor.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'product' !== $screen->post_type || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		wp_enqueue_style(
			'wpct-category-search',
			WPCUSTOMTOOLS_URL . 'assets/css/category-search.css',
			array(),
			WPCUSTOMTOOLS_VERSION
		);

		wp_enqueue_script(
			'wpct-category-search',
			WPCUSTOMTOOLS_URL . 'assets/js/category-search.js',
			array(),
			WPCUSTOMTOOLS_VERSION,
			true
		);
	}
}
