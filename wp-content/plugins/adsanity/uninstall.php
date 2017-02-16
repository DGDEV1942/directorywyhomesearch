<?php

/**
 * Check to make sure this file is being called on deactivation.
 * Directly calling this file will fail.
 */
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	wp_die( __( 'An unauthorized uninstall has been requested.', 'adsanity' ) );
}

/**
 * Delete the AdSanity options
 */
delete_option( 'adsanity-options' );

/**
 * Flush all rewrite rules
 */
global $wp_rewrite;
$wp_rewrite->flush_rules();
