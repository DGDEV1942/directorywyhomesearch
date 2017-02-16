<?php

/*
 * Plugin Name: JP Omnisearch
 * Plugin URI: http://wordpress.org/plugins/jetpack-omnisearch/
 * Description: Search your entire database from a single field in your Dashboard.
 * Author: JP
 * Version: 3.9.6
 * Text Domain: jetpack
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Module Name: Omnisearch
 * Module Description: Search your entire database from a single field in your Dashboard.
 * Sort Order: 16
 * First Introduced: 2.3
 * Requires Connection: No
 * Auto Activate: Yes
 * Module Tags: Developers
 * Additional Search Queries: search
 */

add_action('init','jp_omnisearch_register_styles');
function jp_omnisearch_register_styles() {
	if ( ! wp_style_is( 'genericons', 'registered' ) ) {
		wp_register_style( 'genericons', plugins_url( '_inc/genericons/genericons/genericons.css', __FILE__ ), false, '3.1' );
	}
}

// Only do Jetpack Omnisearch if there isn't already a Core WP_Omnisearch Class.
if ( ! class_exists( 'WP_Omnisearch' ) ) {
	require_once( dirname( __FILE__ ) . '/omnisearch/omnisearch-core.php' );
}

function jetpack_omnisearch_load_textdomain() {
	load_plugin_textdomain( 'jetpack', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'jetpack_omnisearch_load_textdomain' );

function jetpack_omnisearch_link($actions) {
	return array_merge(
		array( 'settings' => sprintf( '<a href="%s">%s</a>', 'admin.php?page=omnisearch', __( 'Search', 'jetpack' ) ) ),
		$actions
	);
	return $actions;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'jetpack_omnisearch_link' );