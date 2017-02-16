<?php
/*
Plugin Name: AdSanity
Plugin URI: http://adsanityplugin.com
Description: Powerfully simple banner advertising management.
Version: 1.0.9.1
Author: Pixel Jar
Author URI: http://www.pixeljar.com
*/

/**
 * Copyright (c) 2011 Pixel Jar. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */

// SET UP PATH CONSTANTS
define( 'ADSANITY',					'adsanity' );
define( 'ADSANITY_VERSION',			'1.0.9.1' );
define( 'ADSANITY_URL',				plugin_dir_url( __FILE__ ) );
define( 'ADSANITY_ABS',				plugin_dir_path( __FILE__ ) );
define( 'ADSANITY_SLUG',			plugin_basename( __FILE__ ) );
define( 'ADSANITY_REL',				basename( dirname( __FILE__ ) ) );
define( 'ADSANITY_CPT',				ADSANITY_ABS . 'custom-post-types/' );
define( 'ADSANITY_THEME',			ADSANITY_ABS . 'theme-templates/' );
define( 'ADSANITY_WIDGETS',			ADSANITY_ABS . 'widgets/' );
define( 'ADSANITY_LANG',			ADSANITY_ABS . 'i18n/' );
define( 'ADSANITY_VIEWS',			ADSANITY_ABS . 'views/' );
define( 'ADSANITY_LIB',				ADSANITY_ABS . 'lib/' );
define( 'ADSANITY_CSS',				ADSANITY_URL . 'css/' );
define( 'ADSANITY_IMG',				ADSANITY_URL . 'images/' );
define( 'ADSANITY_JS',				ADSANITY_URL . 'js/' );
define( 'ADSANITY_ADMIN_OPTIONS',	'adsanity-options' );
define( 'ADSANITY_UPDATE_API',		'http://adsanityplugin.com' );
define( 'ADSANITY_MEMBER_LOGIN',	'http://adsanityplugin.com/wp-login.php?redirect_to=http://adsanityplugin.com/account/' );
define( 'ADSANITY_EOL',				'2082758399' ); // Dec 31, 2035

// INTERNATIONALIZATION
load_plugin_textdomain( ADSANITY, null, ADSANITY_REL );

// SCRIPTS AND STYLES
require_once( ADSANITY_LIB . 'scripts.php' );

// ADMIN ONLY STUFF
if( is_admin() ) :

	// INITIALIZATION
	require_once( ADSANITY_LIB . 'initialization.php' );

	// FUNCTION LIBRARIES
	require_once( ADSANITY_LIB . 'utility.php' );
	require_once( ADSANITY_LIB . 'query.php' );
	require_once( ADSANITY_LIB . 'ajax.php' );
	require_once( ADSANITY_LIB . 'export.php' );

	// SETTINGS
	require_once( ADSANITY_ABS . 'admin-pages/tracking-filters.php' );
	require_once( ADSANITY_ABS . 'admin-pages/stats.php' );

	// UPDATE CHECKS
	require_once( ADSANITY_ABS . 'update-engine/remote-api.php' );
else :
	add_action( 'wp_print_styles', create_function( '', 'wp_enqueue_style("adsanity-default-css");' ) );
endif;

// TRACKING FUNCTIONS
require_once( ADSANITY_LIB . 'tracking.php' );
require_once( ADSANITY_LIB . 'template-tags.php' );
require_once( ADSANITY_LIB . 'shortcodes.php' );

// CUSTOM POST TYPES
require_once( ADSANITY_CPT . 'base.php' );
require_once( ADSANITY_CPT . 'ads.php' );

// WIDGETS
require_once( ADSANITY_WIDGETS . 'single-ad.php' );
require_once( ADSANITY_WIDGETS . 'group-of-ads.php' );
require_once( ADSANITY_WIDGETS . 'random-ad.php' );

// Flush Rewrite Rules on Activation
register_activation_hook( ADSANITY_SLUG, create_function( '', 'require_once( ADSANITY_ABS."activation.php" );' ) );

// Flush Rewrite Rules on Deactivation
register_deactivation_hook( ADSANITY_SLUG, create_function( '', 'require_once( ADSANITY_ABS."deactivation.php" );' ) );

// Remove defaults
register_uninstall_hook( ADSANITY_SLUG, create_function( '', 'require_once( ADSANITY_ABS."uninstall.php" );' ) );
