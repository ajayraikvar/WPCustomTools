<?php
/**
 * Elementor Product Card widget.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( '\\Elementor\\Widget_Base' ) ) {
	return;
}

/**
 * Single product card with editable media, copy, pricing, offers and CTA.
 */
final class Elementor_Product_Card extends \Elementor\Widget_Base {
	/**
	 * Widget identifier.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wpct-product-card';
	}

	/**
	 * Widget label shown in Elementor.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WP Custom Product Card', 'wp-custom-tools' );
	}

	/**
	 * Icon shown in the Elementor panel.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product';
	}

	/**
	 * Widget category.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'general' );
	}

	/**
	 * Search keywords.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'product', 'product card', 'woocommerce', 'video', 'price', 'offer' );
	}

	/**
	 * Styles required by this widget.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'wpct-elementor-product-card' );
	}

	/**
	 * Register Elementor editable fields.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'wpct_section_media',
			array(
				'label' => __( 'Product Media', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'media_type',
			array(
				'label'   => __( 'Media Type', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image' => __( 'Product Image', 'wp-custom-tools' ),
					'video' => __( 'Product Video (MP4/WebM URL)', 'wp-custom-tools' ),
				),
			)
		);

		$this->add_control(
			'product_image',
			array(
				'label'     => __( 'Product Image', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'default'   => array(
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				),
				'condition' => array( 'media_type' => 'image' ),
			)
		);

		$this->add_control(
			'image_alt',
			array(
				'label'       => __( 'Image Alt Text', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'Describe the product image', 'wp-custom-tools' ),
				'condition'   => array( 'media_type' => 'image' ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'         => __( 'Video URL (MP4/WebM)', 'wp-custom-tools' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => 'https://example.com/product-video.mp4',
				'show_external' => false,
				'description'   => __( 'Use a direct URL to a self-hosted or media-library MP4/WebM file.', 'wp-custom-tools' ),
				'condition'     => array( 'media_type' => 'video' ),
			)
		);

		$this->add_control(
			'video_poster',
			array(
				'label'     => __( 'Video Poster / Thumbnail', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::MEDIA,
				'condition' => array( 'media_type' => 'video' ),
			)
		);

		$this->add_control(
			'video_controls',
			array(
				'label'        => __( 'Show Video Controls', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'wp-custom-tools' ),
				'label_off'    => __( 'Hide', 'wp-custom-tools' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'media_type' => 'video' ),
			)
		);

		$this->add_control(
			'video_autoplay',
			array(
				'label'        => __( 'Autoplay Video', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'wp-custom-tools' ),
				'label_off'    => __( 'No', 'wp-custom-tools' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => __( 'Autoplay normally requires muted video in modern browsers.', 'wp-custom-tools' ),
				'condition'    => array( 'media_type' => 'video' ),
			)
		);

		$this->add_control(
			'video_muted',
			array(
				'label'        => __( 'Mute Video', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => __( 'Muted', 'wp-custom-tools' ),
				'label_off'    => __( 'Sound', 'wp-custom-tools' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array( 'media_type' => 'video' ),
			)
		);

		$this->add_control(
			'video_loop',
			array(
				'label'        => __( 'Loop Video', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array( 'media_type' => 'video' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_product_info',
			array(
				'label' => __( 'Product Information', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'product_title',
			array(
				'label'       => __( 'Product Title', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'The Power of Your Subconscious Mind', 'wp-custom-tools' ),
				'placeholder' => __( 'Enter product title', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'   => __( 'Title HTML Tag', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'h3',
				'options' => array(
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'div'  => 'div',
					'p'    => 'p',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'product_subtitle',
			array(
				'label'       => __( 'Subtitle / Author', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'by Joseph Murphy', 'wp-custom-tools' ),
				'placeholder' => __( 'Brand, author, or short description', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'show_rating',
			array(
				'label'        => __( 'Show Rating', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'rating_score',
			array(
				'label'     => __( 'Rating Score', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::NUMBER,
				'default'   => 4.4,
				'min'       => 0,
				'max'       => 5,
				'step'      => 0.1,
				'condition' => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'rating_count',
			array(
				'label'       => __( 'Reviews Text', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '(14.6K)',
				'placeholder' => '(1.2K)',
				'condition'   => array( 'show_rating' => 'yes' ),
			)
		);

		$this->add_control(
			'product_variant',
			array(
				'label'       => __( 'Variant / Format', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Paperback', 'wp-custom-tools' ),
				'placeholder' => __( 'Paperback, Colour: Blue, Size M…', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'product_badge',
			array(
				'label'       => __( 'Promotional Badge', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Great Indian Festival', 'wp-custom-tools' ),
				'placeholder' => __( 'Sale, New, Limited offer…', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_price',
			array(
				'label' => __( 'Price & Offers', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'currency_symbol',
			array(
				'label'   => __( 'Currency Symbol', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '₹',
				'classes' => 'elementor-control-direction-ltr',
			)
		);

		$this->add_control(
			'current_price',
			array(
				'label'   => __( 'Current Price', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => '109',
			)
		);

		$this->add_control(
			'show_mrp',
			array(
				'label'        => __( 'Show MRP / Regular Price', 'wp-custom-tools' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'mrp_label',
			array(
				'label'     => __( 'MRP Label', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => 'M.R.P.:',
				'condition' => array( 'show_mrp' => 'yes' ),
			)
		);

		$this->add_control(
			'regular_price',
			array(
				'label'     => __( 'MRP / Regular Price', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => '275',
				'condition' => array( 'show_mrp' => 'yes' ),
			)
		);

		$this->add_control(
			'discount_text',
			array(
				'label'       => __( 'Discount Text', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => '(60% off)',
				'placeholder' => '(20% off)',
			)
		);

		$this->add_control(
			'payment_offer',
			array(
				'label'       => __( 'Payment / Bank Offer', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'Up to 5% back with Amazon Pay ICICI card', 'wp-custom-tools' ),
				'placeholder' => __( 'Bank offer or payment benefit', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'delivery_text',
			array(
				'label'       => __( 'Delivery Text', 'wp-custom-tools' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => __( 'FREE delivery Fri, 23 Oct', 'wp-custom-tools' ),
				'placeholder' => __( 'Delivery estimate or shipping text', 'wp-custom-tools' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'Button Text', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Add to cart', 'wp-custom-tools' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'         => __( 'Button Link', 'wp-custom-tools' ),
				'type'          => \Elementor\Controls_Manager::URL,
				'placeholder'   => 'https://example.com/product',
				'default'       => array( 'url' => '' ),
				'show_external' => true,
				'description'   => __( 'Set the destination URL. The button is non-clickable when the URL is empty.', 'wp-custom-tools' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_card_style',
			array(
				'label' => __( 'Card', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'card_max_width',
			array(
				'label'      => __( 'Card Max Width', 'wp-custom-tools' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 200, 'max' => 800 ),
					'%'  => array( 'min' => 25, 'max' => 100 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 340 ),
				'selectors'  => array(
					'{{WRAPPER}} .wpct-product-card' => 'width: 100%; max-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'card_alignment',
			array(
				'label'   => __( 'Card Alignment', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::CHOOSE,
				'options' => array(
					'left'   => array( 'title' => __( 'Left', 'wp-custom-tools' ), 'icon' => 'eicon-h-align-left' ),
					'center' => array( 'title' => __( 'Center', 'wp-custom-tools' ), 'icon' => 'eicon-h-align-center' ),
					'right'  => array( 'title' => __( 'Right', 'wp-custom-tools' ), 'icon' => 'eicon-h-align-right' ),
				),
				'default'   => 'left',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card-wrap' => 'display: flex; justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_background',
			array(
				'label'     => __( 'Background', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'card_padding',
			array(
				'label'      => __( 'Card Padding', 'wp-custom-tools' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em', '%' ),
				'default'    => array( 'top' => 10, 'right' => 10, 'bottom' => 12, 'left' => 10, 'unit' => 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .wpct-product-card' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} .wpct-product-card',
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => __( 'Border Radius', 'wp-custom-tools' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 40 ),
					'%'  => array( 'min' => 0, 'max' => 20 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 8 ),
				'selectors'  => array(
					'{{WRAPPER}} .wpct-product-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} .wpct-product-card',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_media_style',
			array(
				'label' => __( 'Image / Video', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'media_height',
			array(
				'label'      => __( 'Media Height', 'wp-custom-tools' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array( 'min' => 100, 'max' => 600 ),
					'vh' => array( 'min' => 10, 'max' => 80 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 280 ),
				'selectors'  => array(
					'{{WRAPPER}} .wpct-product-card__media' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'media_fit',
			array(
				'label'   => __( 'Image Fit', 'wp-custom-tools' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'contain',
				'options' => array(
					'contain' => __( 'Contain', 'wp-custom-tools' ),
					'cover'   => __( 'Cover', 'wp-custom-tools' ),
				),
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__media img' => 'object-fit: {{VALUE}};',
					'{{WRAPPER}} .wpct-product-card__media video' => 'object-fit: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'media_background',
			array(
				'label'     => __( 'Media Background', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#f7f7f7',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__media' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_title_style',
			array(
				'label' => __( 'Title & Subtitle', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'Title Color', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .wpct-product-card__title',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'Subtitle Color', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .wpct-product-card__subtitle',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'wpct_section_price_style',
			array(
				'label' => __( 'Price & Labels', 'wp-custom-tools' ),
				'tab'   => \Elementor\Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'price_color',
			array(
				'label'     => __( 'Price Color', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#111111',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__price-current' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'price_typography',
				'selector' => '{{WRAPPER}} .wpct-product-card__price-current',
			)
		);

		$this->add_control(
			'badge_background',
			array(
				'label'     => __( 'Promo Badge Background', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#e60023',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__badge' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'badge_color',
			array(
				'label'     => __( 'Promo Badge Text', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__badge' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_background',
			array(
				'label'     => __( 'Button Background', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#ffd814',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__button' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'button_color',
			array(
				'label'     => __( 'Button Text Color', 'wp-custom-tools' ),
				'type'      => \Elementor\Controls_Manager::COLOR,
				'default'   => '#111111',
				'selectors' => array(
					'{{WRAPPER}} .wpct-product-card__button' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .wpct-product-card__button',
			)
		);

		$this->add_responsive_control(
			'button_radius',
			array(
				'label'      => __( 'Button Radius', 'wp-custom-tools' ),
				'type'       => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array( 'min' => 0, 'max' => 60 ),
					'%'  => array( 'min' => 0, 'max' => 50 ),
				),
				'default'    => array( 'unit' => 'px', 'size' => 30 ),
				'selectors'  => array(
					'{{WRAPPER}} .wpct-product-card__button' => 'border-radius: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Output frontend markup.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$tag      = isset( $settings['title_html_tag'] ) && in_array( $settings['title_html_tag'], array( 'h2', 'h3', 'h4', 'div', 'p', 'span' ), true )
			? $settings['title_html_tag']
			: 'h3';

		echo '<div class="wpct-product-card-wrap"><article class="wpct-product-card">';

		$this->render_media( $settings );

		echo '<div class="wpct-product-card__content">';

		if ( ! empty( $settings['product_title'] ) ) {
			echo '<' . tag_escape( $tag ) . ' class="wpct-product-card__title">' . esc_html( $settings['product_title'] ) . '</' . tag_escape( $tag ) . '>';
		}

		if ( ! empty( $settings['product_subtitle'] ) ) {
			echo '<div class="wpct-product-card__subtitle">' . esc_html( $settings['product_subtitle'] ) . '</div>';
		}

		if ( isset( $settings['show_rating'] ) && 'yes' === $settings['show_rating'] ) {
			$score = isset( $settings['rating_score'] ) ? max( 0, min( 5, (float) $settings['rating_score'] ) ) : 0;
			echo '<div class="wpct-product-card__rating">';
			echo '<span class="wpct-product-card__rating-score">' . esc_html( number_format_i18n( $score, 1 ) ) . '</span> ';
			echo '<span class="wpct-product-card__stars" aria-label="' . esc_attr( sprintf( __( 'Rating: %s out of 5', 'wp-custom-tools' ), number_format_i18n( $score, 1 ) ) ) . '">★★★★★</span> ';
			if ( ! empty( $settings['rating_count'] ) ) {
				echo '<span class="wpct-product-card__review-count">' . esc_html( $settings['rating_count'] ) . '</span>';
			}
			echo '</div>';
		}

		if ( ! empty( $settings['product_variant'] ) ) {
			echo '<div class="wpct-product-card__variant">' . esc_html( $settings['product_variant'] ) . '</div>';
		}

		if ( ! empty( $settings['product_badge'] ) ) {
			echo '<div class="wpct-product-card__badge">' . esc_html( $settings['product_badge'] ) . '</div>';
		}

		echo '<div class="wpct-product-card__pricing">';

		if ( ! empty( $settings['current_price'] ) ) {
			echo '<span class="wpct-product-card__price-current">' . esc_html( $settings['currency_symbol'] . $settings['current_price'] ) . '</span>';
		}

		if ( isset( $settings['show_mrp'] ) && 'yes' === $settings['show_mrp'] && ! empty( $settings['regular_price'] ) ) {
			echo '<span class="wpct-product-card__mrp">';
			echo esc_html( $settings['mrp_label'] . ' ' . $settings['currency_symbol'] . $settings['regular_price'] );
			echo '</span>';
		}

		if ( ! empty( $settings['discount_text'] ) ) {
			echo '<span class="wpct-product-card__discount">' . esc_html( $settings['discount_text'] ) . '</span>';
		}

		echo '</div>';

		if ( ! empty( $settings['payment_offer'] ) ) {
			echo '<div class="wpct-product-card__payment-offer">' . esc_html( $settings['payment_offer'] ) . '</div>';
		}

		if ( ! empty( $settings['delivery_text'] ) ) {
			echo '<div class="wpct-product-card__delivery">' . esc_html( $settings['delivery_text'] ) . '</div>';
		}

		if ( ! empty( $settings['button_text'] ) ) {
			if ( ! empty( $settings['button_link']['url'] ) ) {
				$this->add_link_attributes( 'product_button', $settings['button_link'] );
				echo '<a class="wpct-product-card__button" ' . $this->get_render_attribute_string( 'product_button' ) . '>' . esc_html( $settings['button_text'] ) . '</a>';
			} else {
				echo '<span class="wpct-product-card__button wpct-product-card__button--disabled" aria-disabled="true">' . esc_html( $settings['button_text'] ) . '</span>';
			}
		}

		echo '</div></article></div>';
	}

	/**
	 * Render selected product image or self-hosted product video.
	 *
	 * @param array $settings Widget settings.
	 * @return void
	 */
	private function render_media( $settings ) {
		echo '<div class="wpct-product-card__media">';

		if ( isset( $settings['media_type'] ) && 'video' === $settings['media_type'] && ! empty( $settings['video_url']['url'] ) ) {
			$video_url = esc_url( $settings['video_url']['url'] );
			$poster    = ! empty( $settings['video_poster']['url'] ) ? $settings['video_poster']['url'] : '';

			echo '<video preload="metadata" playsinline';
			if ( 'yes' === ( $settings['video_controls'] ?? '' ) ) {
				echo ' controls';
			}
			if ( 'yes' === ( $settings['video_autoplay'] ?? '' ) ) {
				echo ' autoplay';
			}
			if ( 'yes' === ( $settings['video_muted'] ?? '' ) || 'yes' === ( $settings['video_autoplay'] ?? '' ) ) {
				echo ' muted';
			}
			if ( 'yes' === ( $settings['video_loop'] ?? '' ) ) {
				echo ' loop';
			}
			if ( $poster ) {
				echo ' poster="' . esc_url( $poster ) . '"';
			}
			echo '><source src="' . $video_url . '"></video>';
		} elseif ( ! empty( $settings['product_image']['id'] ) ) {
			$alt = ! empty( $settings['image_alt'] ) ? $settings['image_alt'] : get_post_meta( absint( $settings['product_image']['id'] ), '_wp_attachment_image_alt', true );
			echo wp_get_attachment_image(
				absint( $settings['product_image']['id'] ),
				'large',
				false,
				array(
					'class'   => 'wpct-product-card__image',
					'alt'     => $alt,
					'loading' => 'lazy',
				)
			);
		} elseif ( ! empty( $settings['product_image']['url'] ) && 'image' === ( $settings['media_type'] ?? 'image' ) ) {
			$alt = ! empty( $settings['image_alt'] ) ? $settings['image_alt'] : $settings['product_title'];
			echo '<img class="wpct-product-card__image" src="' . esc_url( $settings['product_image']['url'] ) . '" alt="' . esc_attr( $alt ) . '" loading="lazy">';
		} else {
			echo '<div class="wpct-product-card__placeholder">' . esc_html__( 'Add product media in Elementor', 'wp-custom-tools' ) . '</div>';
		}

		echo '</div>';
	}
}
