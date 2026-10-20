<?php
/**
 * Dependency-free smoke test for Additional_Keywords persistence and safety guards.
 *
 * Run with: php tests/php-additional-keywords-smoke.php
 */

define( 'ABSPATH', __DIR__ . '/' );

$GLOBALS['wpct_test_saved_meta'] = array();
$GLOBALS['wpct_test_can_edit']   = true;

function get_post_type_object( $post_type ) {
	return (object) array(
		'name'   => $post_type,
		'public' => true,
		'show_ui' => true,
	);
}

function add_action() {}
function wp_is_post_autosave( $post_id ) { return false; }
function wp_is_post_revision( $post_id ) { return false; }
function wp_unslash( $value ) { return $value; }
function sanitize_text_field( $value ) { return trim( strip_tags( (string) $value ) ); }
function wp_verify_nonce( $nonce, $action ) {
	return 'valid-nonce' === $nonce && 'wpct_save_additional_keywords' === $action;
}
function current_user_can( $capability, $post_id = null ) {
	return 'edit_post' === $capability && 123 === (int) $post_id && ! empty( $GLOBALS['wpct_test_can_edit'] );
}
function update_post_meta( $post_id, $key, $value ) {
	$GLOBALS['wpct_test_saved_meta'][ $post_id ][ $key ] = $value;
	return true;
}

require_once __DIR__ . '/../includes/class-wpct-additional-keywords.php';

function wpct_assert( $condition, $message ) {
	if ( ! $condition ) {
		fwrite( STDERR, "FAIL: " . $message . "\n" );
		exit( 1 );
	}
}

$post = (object) array( 'post_type' => 'post' );
$module = \WPCustomTools\Additional_Keywords::instance();

$_POST = array(
	'wpct_additional_keywords_nonce' => 'valid-nonce',
	'wpct_additional_keywords'       => "BANC 131 solved assignment\n IGNOU assignment \n bAnC 131 SOLVED ASSIGNMENT \n\n<em>Hindi notes</em>",
);
$module->save_keywords( 123, $post );
$saved = $GLOBALS['wpct_test_saved_meta'][123]['_wpct_additional_keywords'];

wpct_assert( 3 === count( $saved ), 'duplicate phrases should be removed case-insensitively' );
wpct_assert( 'BANC 131 solved assignment' === $saved[0], 'first phrase should be trimmed and preserved' );
wpct_assert( 'IGNOU assignment' === $saved[1], 'second phrase should be preserved' );
wpct_assert( 'Hindi notes' === $saved[2], 'HTML tags should be removed from saved phrases' );

// Invalid nonce must not change the existing keyword list.
$before = $saved;
$_POST = array(
	'wpct_additional_keywords_nonce' => 'invalid-nonce',
	'wpct_additional_keywords'       => 'do not save this',
);
$module->save_keywords( 123, $post );
wpct_assert( $before === $GLOBALS['wpct_test_saved_meta'][123]['_wpct_additional_keywords'], 'invalid nonce must not update saved metadata' );

// A user without edit_post permission must not overwrite data.
$GLOBALS['wpct_test_can_edit'] = false;
$_POST = array(
	'wpct_additional_keywords_nonce' => 'valid-nonce',
	'wpct_additional_keywords'       => 'unauthorized change',
);
$module->save_keywords( 123, $post );
wpct_assert( $before === $GLOBALS['wpct_test_saved_meta'][123]['_wpct_additional_keywords'], 'unauthorized user must not update saved metadata' );

// Keyword storage is capped.
$GLOBALS['wpct_test_can_edit'] = true;
$many = array();
for ( $i = 1; $i <= 65; $i++ ) {
	$many[] = 'Keyword ' . $i;
}
$_POST = array(
	'wpct_additional_keywords_nonce' => 'valid-nonce',
	'wpct_additional_keywords'       => implode( "\n", $many ),
);
$module->save_keywords( 123, $post );
wpct_assert( 50 === count( $GLOBALS['wpct_test_saved_meta'][123]['_wpct_additional_keywords'] ), 'saved keywords should be capped at 50 phrases' );

echo "PHP Additional Keywords smoke tests passed.\n";
