<?php

add_action( 'wp_enqueue_scripts', 'adsanity_scripts' );
add_action( 'admin_enqueue_scripts', 'adsanity_scripts' );
function adsanity_scripts() {
	global $wp_version;

	// load uncompressed screipts and styles
	$dev = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.dev' : '';

	// REGISTER STYLES AND SCRIPTS
	wp_register_script( 'jquery-flot', ADSANITY_JS . 'jquery.flot.min.js', array( 'jquery' ), '0.8.3', true );
	wp_register_script( 'jquery-flot-time', ADSANITY_JS . 'jquery.flot.time.min.js', array( 'jquery-flot' ), '0.8.3', true );

	$jquery_ui_datepicker = 'jquery-ui-datepicker';
	// WordPress version below 3.3
	if( version_compare( $wp_version, '3.3' ) == -1 ) :
		$jquery_ui_datepicker = 'adsanity-jqueryui-datepicker';
		wp_register_script( 'adsanity-jqueryui-datepicker', ADSANITY_JS . 'jquery-ui-1.8.16.custom.min.js', array( 'jquery' ), '1.8.16', true );
	endif;

	wp_register_script( 'ads-list', ADSANITY_JS . "ads-list{$dev}.js", array( 'jquery' ), ADSANITY_VERSION, true );
	wp_register_script( 'ads-new', ADSANITY_JS . "ads-new{$dev}.js", array( $jquery_ui_datepicker ), ADSANITY_VERSION, true );
	wp_register_script( 'ads-edit', ADSANITY_JS . "ads-edit{$dev}.js", array( 'jquery-flot-time', $jquery_ui_datepicker ), ADSANITY_VERSION, true );
	wp_register_script( 'adsanity-single-widget-admin', ADSANITY_JS . "single-widgets-admin{$dev}.js", array( 'jquery', 'suggest' ), ADSANITY_VERSION, true );
	wp_register_script( 'adsanity-custom-stats', ADSANITY_JS . "custom-stats{$dev}.js", array( 'jquery-flot-time', $jquery_ui_datepicker ), ADSANITY_VERSION, true );
	wp_register_script( 'adsanity-authorization', ADSANITY_JS . "authorization{$dev}.js", array( 'jquery' ), ADSANITY_VERSION, true );

	wp_register_style( 'adsanity-admin-global', ADSANITY_CSS . "admin-global{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-single-widgets-admin', ADSANITY_CSS . "single-widgets-admin{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-group-widgets-admin', ADSANITY_CSS . "group-widgets-admin{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-random-widgets-admin', ADSANITY_CSS . "random-widgets-admin{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-tracking-filters', ADSANITY_CSS . "tracking-filters{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-jqueryui-datepicker', ADSANITY_CSS . "smoothness/jquery-ui-1.8.16.custom{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-stats', ADSANITY_CSS . "stats{$dev}.css", array( 'adsanity-jqueryui-datepicker' ), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-default-css', ADSANITY_CSS . "widget-default{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
	wp_register_style( 'adsanity-setup', ADSANITY_CSS . "setup{$dev}.css", array(), ADSANITY_VERSION, 'screen' );
}
