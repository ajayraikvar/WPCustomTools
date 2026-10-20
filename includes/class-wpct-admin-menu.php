<?php
/**
 * Admin dashboard and manual GitHub update checker.
 *
 * @package WPCustomTools
 */

namespace WPCustomTools;

defined( 'ABSPATH' ) || exit;

/**
 * Adds the WP Custom Tools dashboard and a manual update check page.
 */
final class Admin_Menu {
	/**
	 * Main repository readme used as the public version source.
	 *
	 * @var string
	 */
	const VERSION_SOURCE_URL = 'https://raw.githubusercontent.com/ajayraikvar/WPCustomTools/main/readme.txt';

	/**
	 * Repository URL.
	 *
	 * @var string
	 */
	const REPOSITORY_URL = 'https://github.com/ajayraikvar/WPCustomTools';

	/**
	 * Transient lifetime in seconds.
	 *
	 * @var int
	 */
	const RESULT_TTL = 900;

	/**
	 * Singleton instance.
	 *
	 * @var Admin_Menu|null
	 */
	private static $instance = null;

	/**
	 * Get singleton instance.
	 *
	 * @return Admin_Menu
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register admin hooks.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_post_wpct_check_updates', array( $this, 'handle_update_check' ) );
	}

	/**
	 * Register the plugin menu and Updates submenu.
	 *
	 * @return void
	 */
	public function register_menu() {
		add_menu_page(
			__( 'WP Custom Tools', 'wp-custom-tools' ),
			__( 'WP Custom Tools', 'wp-custom-tools' ),
			'manage_options',
			'wp-custom-tools',
			array( $this, 'render_dashboard' ),
			'dashicons-admin-tools',
			58
		);

		add_submenu_page(
			'wp-custom-tools',
			__( 'WP Custom Tools Updates', 'wp-custom-tools' ),
			__( 'Updates', 'wp-custom-tools' ),
			'manage_options',
			'wp-custom-tools-updates',
			array( $this, 'render_updates_page' )
		);
	}

	/**
	 * Render the plugin dashboard.
	 *
	 * @return void
	 */
	public function render_dashboard() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'wp-custom-tools' ), '', array( 'response' => 403 ) );
		}
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Custom Tools', 'wp-custom-tools' ); ?></h1>
			<p><?php esc_html_e( 'A modular collection of tools, widgets, and templates by Ajay Raikvar.', 'wp-custom-tools' ); ?></p>

			<div class="card" style="max-width: 760px;">
				<h2><?php esc_html_e( 'WooCommerce Category Search', 'wp-custom-tools' ); ?></h2>
				<p><?php esc_html_e( 'Searches categories from the All categories tab in the WooCommerce product editor. This tool filters the checklist in your browser and does not change saved product or category data.', 'wp-custom-tools' ); ?></p>
				<p><strong><?php esc_html_e( 'Status:', 'wp-custom-tools' ); ?></strong> <?php esc_html_e( 'Available', 'wp-custom-tools' ); ?></p>
			</div>

			<div class="card" style="max-width: 760px;">
				<h2><?php esc_html_e( 'Plugin Updates', 'wp-custom-tools' ); ?></h2>
				<p><?php esc_html_e( 'Manually check the version listed in the public GitHub repository.', 'wp-custom-tools' ); ?></p>
				<p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=wp-custom-tools-updates' ) ); ?>"><?php esc_html_e( 'Open Updates', 'wp-custom-tools' ); ?></a></p>
			</div>

			<p>
				<a href="<?php echo esc_url( self::REPOSITORY_URL ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'GitHub Repository', 'wp-custom-tools' ); ?></a>
				&nbsp;|&nbsp;
				<a href="https://www.ajayraikvar.com" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Author Website', 'wp-custom-tools' ); ?></a>
			</p>
			<p><?php echo esc_html( sprintf( __( 'Installed version: %s', 'wp-custom-tools' ), WPCUSTOMTOOLS_VERSION ) ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render the manual update check page.
	 *
	 * @return void
	 */
	public function render_updates_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to access this page.', 'wp-custom-tools' ), '', array( 'response' => 403 ) );
		}

		$result = get_transient( $this->get_result_transient_key() );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'WP Custom Tools — Updates', 'wp-custom-tools' ); ?></h1>
			<p><?php esc_html_e( 'Click the button to compare the installed version with the Stable tag in the public GitHub readme. No check runs automatically.', 'wp-custom-tools' ); ?></p>

			<div class="card" style="max-width: 760px;">
				<p><strong><?php esc_html_e( 'Installed version:', 'wp-custom-tools' ); ?></strong> <?php echo esc_html( WPCUSTOMTOOLS_VERSION ); ?></p>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="wpct_check_updates">
					<?php wp_nonce_field( 'wpct_check_updates' ); ?>
					<?php submit_button( __( 'Check for Updates', 'wp-custom-tools' ), 'primary', 'submit', false ); ?>
				</form>
				<?php
				if ( is_array( $result ) && isset( $result['status'], $result['message'] ) ) {
					$notice_class = 'notice-info';
					if ( 'error' === $result['status'] ) {
						$notice_class = 'notice-error';
					} elseif ( 'update_available' === $result['status'] ) {
						$notice_class = 'notice-warning';
					} elseif ( 'up_to_date' === $result['status'] ) {
						$notice_class = 'notice-success';
					}
					?>
					<div class="notice <?php echo esc_attr( $notice_class ); ?> inline">
						<p><?php echo esc_html( $result['message'] ); ?></p>
						<?php if ( ! empty( $result['latest_version'] ) ) : ?>
							<p><strong><?php esc_html_e( 'Latest GitHub version:', 'wp-custom-tools' ); ?></strong> <?php echo esc_html( $result['latest_version'] ); ?></p>
						<?php endif; ?>
						<?php if ( 'update_available' === $result['status'] ) : ?>
							<p><?php esc_html_e( 'A newer source version is available. Review the repository and its release notes before installing an update.', 'wp-custom-tools' ); ?></p>
							<p><a class="button" href="<?php echo esc_url( self::REPOSITORY_URL . '/releases' ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View GitHub Releases', 'wp-custom-tools' ); ?></a> <a href="<?php echo esc_url( self::REPOSITORY_URL ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open Repository', 'wp-custom-tools' ); ?></a></p>
						<?php endif; ?>
						<?php if ( ! empty( $result['checked_at'] ) ) : ?>
							<p><small><?php echo esc_html( sprintf( __( 'Last checked: %s', 'wp-custom-tools' ), wp_date( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), (int) $result['checked_at'] ) ) ); ?></small></p>
						<?php endif; ?>
					</div>
					<?php
				}
				?>
			</div>

			<p><a href="<?php echo esc_url( self::REPOSITORY_URL ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Open WP Custom Tools on GitHub', 'wp-custom-tools' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Process a nonce-protected manual update check.
	 *
	 * @return void
	 */
	public function handle_update_check() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to check plugin updates.', 'wp-custom-tools' ), '', array( 'response' => 403 ) );
		}

		check_admin_referer( 'wpct_check_updates' );

		$response = wp_safe_remote_get(
			self::VERSION_SOURCE_URL,
			array(
				'timeout'             => 8,
				'redirection'         => 2,
				'limit_response_size' => 100000,
				'headers'             => array(
					'Accept'     => 'text/plain',
					'User-Agent' => 'WP-Custom-Tools/' . WPCUSTOMTOOLS_VERSION,
				),
			)
		);

		$result = array(
			'status'         => 'error',
			'message'        => __( 'Unable to check GitHub right now. Please try again later.', 'wp-custom-tools' ),
			'checked_at'     => time(),
			'latest_version' => '',
		);

		if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			if ( preg_match( '/^Stable tag:\s*([0-9]+(?:\.[0-9]+){1,3})\s*$/mi', $body, $matches ) ) {
				$latest_version = sanitize_text_field( $matches[1] );
				$result['latest_version'] = $latest_version;

				if ( version_compare( $latest_version, WPCUSTOMTOOLS_VERSION, '>' ) ) {
					$result['status']  = 'update_available';
					$result['message'] = sprintf(
						/* translators: %s: newer plugin version. */
						__( 'Update available: version %s is listed on GitHub.', 'wp-custom-tools' ),
						$latest_version
					);
				} elseif ( version_compare( $latest_version, WPCUSTOMTOOLS_VERSION, '=' ) ) {
					$result['status']  = 'up_to_date';
					$result['message'] = __( 'WP Custom Tools is up to date according to the GitHub Stable tag.', 'wp-custom-tools' );
				} else {
					$result['status']  = 'up_to_date';
					$result['message'] = __( 'The installed version is newer than the Stable tag currently listed on GitHub.', 'wp-custom-tools' );
				}
			} else {
				$result['message'] = __( 'GitHub responded, but a valid Stable tag was not found in readme.txt.', 'wp-custom-tools' );
			}
		} elseif ( is_wp_error( $response ) ) {
			$result['message'] = sanitize_text_field( $response->get_error_message() );
		} else {
			$result['message'] = sprintf(
				/* translators: %d: HTTP status code. */
				__( 'GitHub returned HTTP status %d. Please try again later.', 'wp-custom-tools' ),
				(int) wp_remote_retrieve_response_code( $response )
			);
		}

		set_transient( $this->get_result_transient_key(), $result, self::RESULT_TTL );

		wp_safe_redirect( add_query_arg( 'page', 'wp-custom-tools-updates', admin_url( 'admin.php' ) ) );
		exit;
	}

	/**
	 * Build a transient key scoped to the current user.
	 *
	 * @return string
	 */
	private function get_result_transient_key() {
		return 'wpct_update_check_' . (int) get_current_user_id();
	}
}
