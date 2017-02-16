<?php

/**
 * adsanity_stats
 * This class handles all of the custom stats functionality
 *
 * @package adsanity
 */
class adsanity_stats {

	/**
	 * Kicks off all the hooks required to make this class run
	 */
	function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'stats_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'stats_scripts' ) );
		add_action( 'admin_init', array( $this, 'maybe_export_stats' ) );
	}

	function admin_menu() {
	/**
	 * Creates a "Stats" submenu under the main AdSanity menu to allow users to create custom
	 * statistical reports. Also kicks off enqueueing of admin scripts and styles.
	 */
		$stats = add_submenu_page(
			'edit.php?post_type=ads',
			'Stats',
			'Stats',
			'manage_options',
			'adsanity-stats',
			array( $this, 'stats_page' )
		);
	}

	function maybe_export_stats() {
		if( isset( $_POST['_adsanity_export_nonce'] ) && wp_verify_nonce( $_POST['_adsanity_export_nonce'], 'adsanity-stat-export' ) ) {
	/**
	 * Generates a csv download report for the selected statistics
	 */
		// Output the stats
			AdSanityExport::custom_stats_export();
		}
	}

	function stats_page() {
		require_once( ADSANITY_VIEWS . 'stats.php' );
	/**
	 * Renders the custom stats page
	 */
	}
		function stats_styles( $hook_suffix = '' ) {
			if( 'ads_page_adsanity-stats' != $hook_suffix )
				return;

			wp_enqueue_style( 'adsanity-stats' );
	/**
	 * Optionally enqueues the custom stat css and javascript if the request is coming from the
	 * custom stats page.
	 *
	 * @param  string $hook_prefix screen name
	 * @return false|none  returns false if the request is not coming from the stats page
	 */
		}
		function stats_scripts( $hook_suffix = '' ) {
			if( 'ads_page_adsanity-stats' != $hook_suffix )
				return;

			if( isset( $_GET['tab'] ) && $_GET['tab'] == 'custom' ) :
				global $wp_locale;
				wp_enqueue_script( 'adsanity-custom-stats' );
				wp_localize_script(
					'adsanity-custom-stats',
					'adsanity',
					array(
						'adsanity_eol' => ADSANITY_EOL,
						'months' => $wp_locale->month,
						'months_01' => $wp_locale->month['01'],
						'months_02' => $wp_locale->month['02'],
						'months_03' => $wp_locale->month['03'],
						'months_04' => $wp_locale->month['04'],
						'months_05' => $wp_locale->month['05'],
						'months_06' => $wp_locale->month['06'],
						'months_07' => $wp_locale->month['07'],
						'months_08' => $wp_locale->month['08'],
						'months_09' => $wp_locale->month['09'],
						'months_10' => $wp_locale->month['10'],
						'months_11' => $wp_locale->month['11'],
						'months_12' => $wp_locale->month['12'],
					)
				);
			endif;
		// Enqueue Custom Stats Javascripts
		}
}
new adsanity_stats;
