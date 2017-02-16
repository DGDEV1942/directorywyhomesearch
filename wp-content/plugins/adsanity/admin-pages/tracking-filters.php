<?php

/**
 * adsanity_tracking_filters
 * This class handles all of the user agent setup and blocking functionality
 *
 * @package adsanity
 */
class adsanity_tracking_filters {
	
	/**
	 * Kicks off all the hooks required to make this class run
	 */
	function __construct() {
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_print_styles-ads_page_adsanity-tracking-filters', array( &$this, 'tracking_filters_styles' ) );
	}
	
	function admin_menu() {
		

	/**
	 * Creates a "Tracking Filters" submenu under the main AdSanity menu to allow users to blacklist
	 * certain user agents from being tracked. Also kicks off enqueueing of admin scripts and styles.
	 */
		$tracking_filters = add_submenu_page(
			'edit.php?post_type=ads',
			'AdSanity Tracking Filters',
			'Tracking Filters',
			'manage_options',
			'adsanity-tracking-filters',
			create_function( '', 'require_once( ADSANITY_VIEWS."tracking-filters.php" );' )
		);
		
	}
		function tracking_filters_styles() {
			wp_enqueue_style( 'adsanity-tracking-filters' );
	/**
	 * Optionally enqueues the tracking filters css and javascript if the request is coming from the
	 * tracking filters page.
	 *
	 * @param  string $hook_prefix screen name
	 * @return false|none  returns false if the request is not coming from the tracking filters page
	 */
		}
}
new adsanity_tracking_filters;