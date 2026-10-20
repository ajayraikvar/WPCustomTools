<?php
/**
 * Elementor widget registration module.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

/**
 * Registers the custom widget only when Elementor is active.
 */
final class Elementor_Widget_Module {
	/**
	 * Singleton instance.
	 *
	 * @var Elementor_Widget_Module|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Elementor_Widget_Module
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register hooks. Elementor remains an optional dependency.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'elementor/widgets/register', array( $this, 'register_widget' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );
	}

	/**
	 * Register widget stylesheet for Elementor's dependency loader.
	 *
	 * @return void
	 */
	public function register_styles() {
		wp_register_style(
			'wpct-elementor-product-card',
			WPCUSTOMTOOLS_URL . 'assets/css/elementor-product-card.css',
			array(),
			WPCUSTOMTOOLS_VERSION
		);
	}

	/**
	 * Register widget after Elementor makes its widget manager available.
	 *
	 * @param object $widgets_manager Elementor widget manager.
	 * @return void
	 */
	public function register_widget( $widgets_manager ) {
		if ( ! class_exists( '\\Elementor\\Widget_Base' ) || ! is_object( $widgets_manager ) || ! method_exists( $widgets_manager, 'register' ) ) {
			return;
		}

		require_once WPCUSTOMTOOLS_PATH . 'includes/class-wpct-elementor-product-card.php';
		$widgets_manager->register( new Elementor_Product_Card() );
	}
}
