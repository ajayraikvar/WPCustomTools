<?php
/**
 * Additional Keywords manager for supported content editors.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

/**
 * Stores per-content keywords owned by WP Custom Tools.
 *
 * This module intentionally does not alter AIOSEO's private metadata or Pro UI.
 */
final class Additional_Keywords {
	/**
	 * Private post meta key owned by this plugin.
	 *
	 * @var string
	 */
	const META_KEY = '_wpct_additional_keywords';

	/**
	 * Maximum number of keywords stored for one post.
	 *
	 * @var int
	 */
	const MAX_KEYWORDS = 50;

	/**
	 * Singleton instance.
	 *
	 * @var Additional_Keywords|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Additional_Keywords
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register WordPress hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'register_meta_box' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save_keywords' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Determine whether a post type has a public editor UI.
	 *
	 * @param string $post_type Post type name.
	 * @return bool
	 */
	private function is_supported_post_type( $post_type ) {
		$object = get_post_type_object( $post_type );

		if ( ! $object || empty( $object->public ) || empty( $object->show_ui ) ) {
			return false;
		}

		return ! in_array( $post_type, array( 'attachment', 'revision', 'nav_menu_item' ), true );
	}

	/**
	 * Add the keyword editor to supported public post types.
	 *
	 * @param string  $post_type Current post type.
	 * @param WP_Post $post      Current post object.
	 * @return void
	 */
	public function register_meta_box( $post_type, $post ) {
		unset( $post );

		if ( ! $this->is_supported_post_type( $post_type ) ) {
			return;
		}

		add_meta_box(
			'wpct-additional-keywords',
			__( 'WP Custom Tools — Additional Keywords', 'wp-custom-tools' ),
			array( $this, 'render_meta_box' ),
			$post_type,
			'normal',
			'default'
		);
	}

	/**
	 * Render the additional keywords field.
	 *
	 * @param WP_Post $post Current post.
	 * @return void
	 */
	public function render_meta_box( $post ) {
		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			echo '<p>' . esc_html__( 'You do not have permission to edit keywords for this content.', 'wp-custom-tools' ) . '</p>';
			return;
		}

		$keywords = get_post_meta( $post->ID, self::META_KEY, true );
		if ( ! is_array( $keywords ) ) {
			$keywords = array();
		}

		$keywords = array_values(
			array_filter(
				array_map( 'sanitize_text_field', $keywords ),
				static function ( $keyword ) {
					return '' !== trim( $keyword );
				}
			)
		);
		?>
		<div class="wpct-keywords-tool">
			<p><?php esc_html_e( 'Save extra keyword phrases for this post, page, or product. Enter one phrase per line, or separate phrases with commas.', 'wp-custom-tools' ); ?></p>
			<textarea
				class="large-text code wpct-additional-keywords-input"
				id="wpct-additional-keywords"
				name="wpct_additional_keywords"
				rows="5"
				maxlength="20000"
				aria-describedby="wpct-keywords-help"
			><?php echo esc_textarea( implode( "\n", $keywords ) ); ?></textarea>
			<p id="wpct-keywords-help" class="description">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: maximum keyword count. */
						__( 'Maximum %d phrases. Duplicate phrases are removed. These keywords are stored by WP Custom Tools and do not automatically populate AIOSEO or change its content score.', 'wp-custom-tools' ),
						self::MAX_KEYWORDS
					)
				);
				?>
			</p>
			<p class="wpct-keywords-toolbar">
				<span class="wpct-keywords-count" aria-live="polite"><?php echo esc_html( sprintf( __( '%d phrases', 'wp-custom-tools' ), count( $keywords ) ) ); ?></span>
				<button type="button" class="button wpct-copy-keywords"><?php esc_html_e( 'Copy Keywords', 'wp-custom-tools' ); ?></button>
				<span class="wpct-copy-status" role="status" aria-live="polite"></span>
			</p>
			<?php wp_nonce_field( 'wpct_save_additional_keywords', 'wpct_additional_keywords_nonce' ); ?>
		</div>
		<?php
	}

	/**
	 * Save posted keywords only after normal WordPress editor save and permission checks.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Current post object.
	 * @return void
	 */
	public function save_keywords( $post_id, $post ) {
		if ( ! is_object( $post ) || ! isset( $post->post_type ) || ! $this->is_supported_post_type( $post->post_type ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if (
			! isset( $_POST['wpct_additional_keywords_nonce'] ) ||
			! is_string( $_POST['wpct_additional_keywords_nonce'] ) ||
			! wp_verify_nonce(
				sanitize_text_field( wp_unslash( $_POST['wpct_additional_keywords_nonce'] ) ),
				'wpct_save_additional_keywords'
			)
		) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) || ! isset( $_POST['wpct_additional_keywords'] ) || ! is_string( $_POST['wpct_additional_keywords'] ) ) {
			return;
		}

		$raw_keywords = wp_unslash( $_POST['wpct_additional_keywords'] );
		if ( strlen( $raw_keywords ) > 20000 ) {
			$raw_keywords = substr( $raw_keywords, 0, 20000 );
		}

		$parts = preg_split( '/[\r\n,]+/u', $raw_keywords );
		if ( ! is_array( $parts ) ) {
			return;
		}

		$keywords = array();
		$seen     = array();

		foreach ( $parts as $part ) {
			$keyword = trim( sanitize_text_field( $part ) );
			if ( '' === $keyword ) {
				continue;
			}

			$dedupe_key = function_exists( 'mb_strtolower' ) ? mb_strtolower( $keyword, 'UTF-8' ) : strtolower( $keyword );
			if ( isset( $seen[ $dedupe_key ] ) ) {
				continue;
			}

			$seen[ $dedupe_key ] = true;
			$keywords[] = $keyword;

			if ( count( $keywords ) >= self::MAX_KEYWORDS ) {
				break;
			}
		}

		// Empty input intentionally saves an empty list; no unrelated metadata is touched.
		update_post_meta( $post_id, self::META_KEY, $keywords );
	}

	/**
	 * Load the copy/count helper only on supported content editors.
	 *
	 * @param string $hook Current admin hook.
	 * @return void
	 */
	public function enqueue_assets( $hook ) {
		if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || ! $this->is_supported_post_type( $screen->post_type ) ) {
			return;
		}

		wp_enqueue_style(
			'wpct-additional-keywords',
			WPCUSTOMTOOLS_URL . 'assets/css/additional-keywords.css',
			array(),
			WPCUSTOMTOOLS_VERSION
		);

		wp_enqueue_script(
			'wpct-additional-keywords',
			WPCUSTOMTOOLS_URL . 'assets/js/additional-keywords.js',
			array(),
			WPCUSTOMTOOLS_VERSION,
			true
		);
	}
}
