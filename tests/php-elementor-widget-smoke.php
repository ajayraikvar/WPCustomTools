<?php
/**
 * Dependency-free smoke tests for the Elementor product card widget.
 *
 * Run with: php tests/php-elementor-widget-smoke.php
 */

namespace Elementor {

class Controls_Manager {
	const TAB_CONTENT = 'content';
	const TAB_STYLE   = 'style';
	const SELECT      = 'select';
	const MEDIA       = 'media';
	const TEXT        = 'text';
	const SWITCHER    = 'switcher';
	const NUMBER      = 'number';
	const URL         = 'url';
	const SLIDER      = 'slider';
	const CHOOSE      = 'choose';
	const COLOR       = 'color';
	const DIMENSIONS  = 'dimensions';
}

class Utils {
	public static function get_placeholder_image_src() {
		return 'https://example.test/placeholder.jpg';
	}
}

class Group_Control_Border {
	public static function get_type() { return 'border'; }
}
class Group_Control_Box_Shadow {
	public static function get_type() { return 'box_shadow'; }
}
class Group_Control_Typography {
	public static function get_type() { return 'typography'; }
}

class Widget_Base {
	protected $controls = array();
	private $test_settings = array();

	public function __construct( $settings = array() ) {
		$this->test_settings = $settings;
	}

	protected function start_controls_section( $id, $args ) {
		$this->controls[ $id ] = array( 'section' => $args );
	}
	protected function end_controls_section() {}
	protected function add_control( $id, $args ) {
		$this->controls[ $id ] = $args;
	}
	protected function add_responsive_control( $id, $args ) {
		$this->controls[ $id ] = $args;
	}
	protected function add_group_control( $type, $args ) {
		$this->controls[ 'group_' . $args['name'] ] = array( 'type' => $type, 'args' => $args );
	}
	protected function get_settings_for_display() {
		return $this->test_settings;
	}
	protected function add_link_attributes( $key, $link ) {
		$this->link_attributes = '';
		if ( ! empty( $link['url'] ) ) {
			$this->link_attributes = ' href="' . htmlspecialchars( $link['url'], ENT_QUOTES, 'UTF-8' ) . '"';
			if ( ! empty( $link['is_external'] ) ) {
				$this->link_attributes .= ' target="_blank"';
			}
		}
	}
	protected function get_render_attribute_string( $key ) {
		return isset( $this->link_attributes ) ? $this->link_attributes : '';
	}
	public function test_register_controls() {
		$this->register_controls();
		return $this->controls;
	}
	public function test_render() {
		$this->render();
	}
}

}

namespace {
	define( 'ABSPATH', __DIR__ . '/' );

	function __( $text, $domain = null ) { return $text; }
	function esc_html__( $text, $domain = null ) { return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
	function esc_attr__( $text, $domain = null ) { return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' ); }
	function esc_html( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
	function esc_attr( $text ) { return htmlspecialchars( (string) $text, ENT_QUOTES, 'UTF-8' ); }
	function esc_url( $url ) {
		$url = filter_var( (string) $url, FILTER_SANITIZE_URL );
		return preg_match( '#^https?://#i', $url ) ? $url : '';
	}
	function tag_escape( $tag ) {
		return in_array( $tag, array( 'h2', 'h3', 'h4', 'div', 'p', 'span' ), true ) ? $tag : 'h3';
	}
	function number_format_i18n( $number, $decimals = 0 ) {
		return number_format( (float) $number, (int) $decimals, '.', ',' );
	}
	function get_post_meta( $id, $key, $single = false ) { return ''; }
	function absint( $value ) { return abs( (int) $value ); }
	function wp_get_attachment_image( $id, $size, $icon = false, $attr = array() ) { return ''; }

	require_once __DIR__ . '/../includes/class-wpct-elementor-product-card.php';

	function wpct_widget_assert( $condition, $message ) {
		if ( ! $condition ) {
			fwrite( STDERR, 'FAIL: ' . $message . PHP_EOL );
			exit( 1 );
		}
	}

	$widget = new \WPCustomTools\Elementor_Product_Card(
		array(
			'media_type'       => 'image',
			'product_image'    => array( 'id' => 0, 'url' => '' ),
			'image_alt'        => '',
			'product_title'    => '<script>alert("x")</script>Sample Product',
			'title_html_tag'   => 'h3',
			'product_subtitle' => 'by Sample Author',
			'show_rating'      => 'yes',
			'rating_score'     => 4.4,
			'rating_count'     => '(14.6K)',
			'product_variant'  => 'Paperback',
			'product_badge'    => 'Festival Offer',
			'currency_symbol'  => '₹',
			'current_price'    => '109',
			'show_mrp'         => 'yes',
			'mrp_label'        => 'M.R.P.:',
			'regular_price'    => '275',
			'discount_text'    => '(60% off)',
			'payment_offer'    => 'Bank offer text',
			'delivery_text'    => 'Free delivery',
			'button_text'      => 'View Product',
			'button_link'      => array( 'url' => '' ),
		)
	);

	$controls = $widget->test_register_controls();
	foreach (
		array(
			'media_type',
			'product_image',
			'video_url',
			'video_poster',
			'video_controls',
			'video_autoplay',
			'video_muted',
			'video_loop',
			'product_title',
			'product_subtitle',
			'rating_score',
			'rating_count',
			'product_variant',
			'product_badge',
			'currency_symbol',
			'current_price',
			'regular_price',
			'discount_text',
			'payment_offer',
			'delivery_text',
			'button_text',
			'button_link',
			'card_max_width',
			'card_background',
			'card_padding',
			'media_height',
			'media_fit',
			'title_typography',
			'price_typography',
			'button_background'
		) as $control_id
	) {
		wpct_widget_assert( isset( $controls[ $control_id ] ) || isset( $controls[ 'group_' . $control_id ] ), 'Expected Elementor control missing: ' . $control_id );
	}

	wpct_widget_assert( 'wpct-product-card' === $widget->get_name(), 'Widget slug should be stable.' );
	wpct_widget_assert( 'WP Custom Product Card' === $widget->get_title(), 'Widget title should be clear.' );

	ob_start();
	$widget->test_render();
	$html = ob_get_clean();

	wpct_widget_assert( false !== strpos( $html, 'wpct-product-card' ), 'Render should output the product card wrapper.' );
	wpct_widget_assert( false !== strpos( $html, 'Sample Product' ), 'Render should output product title text.' );
	wpct_widget_assert( false === strpos( $html, '<script>' ), 'Product title should be HTML-escaped.' );
	wpct_widget_assert( false !== strpos( $html, 'aria-disabled="true"' ), 'Button without URL should not become a broken link.' );
	wpct_widget_assert( false !== strpos( $html, '109' ), 'Render should output the current price.' );

	echo "Elementor Product Card widget smoke tests passed." . PHP_EOL;
}
