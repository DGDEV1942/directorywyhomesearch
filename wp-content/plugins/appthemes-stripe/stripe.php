<?php
/*
Plugin Name: Stripe Payment Gateway
Description: Allows customers to use Stripe.com as a payment method in AppThemes Products.

AppThemes ID: stripe-payment-gateway

Version: 1.1.2
Author: AppThemes
Author URI: http://appthemes.com
Text Domain: appthemes-stripe
*/

add_action( 'init', 'appthemes_stripe_setup' );

$locale = apply_filters( 'plugin_locale', get_locale(), 'appthemes-stripe' );
load_textdomain( 'appthemes-stripe', WP_LANG_DIR . "/plugins/stripe-$locale.mo" );

function appthemes_stripe_setup(){

	if ( !current_theme_supports( 'app-payments' ) || !function_exists('curl_init') || !function_exists('json_decode') ) {
	  	add_action( 'admin_notices', 'appthemes_stripe_display_version_warning' );
		return;
	}

	require( __DIR__ . '/compatibility.php' );

	require dirname(__FILE__) . '/lib/Stripe.php';
	require dirname(__FILE__) . '/stripe-gateway.php';
	appthemes_register_gateway( 'APP_Stripe_Credit_Card_Gateway' );
	
}

function appthemes_stripe_display_version_warning(){

	$message = __( 'AppThemes Stripe Payment Gateway could not run.', 'appthemes-stripe' );

	if( !current_theme_supports( 'app-payments' ) ) {
		$message = __( 'AppThemes Stripe Payment Gateway does not support the current theme. Please use a compatible AppThemes Product.', 'appthemes-stripe' );
	}

	if ( !function_exists('curl_init') ) {
	  	$message = __( 'AppThemes Stripe Payment Gateway requires the CURL PHP Extension.', 'appthemes-stripe' );
	}

	if ( !function_exists('json_decode') ) {
	  	$message = __( 'AppThemes Stripe Payment Gateway requires the JSON PHP Extension.', 'appthemes-stripe' );
	}

	echo '<div class="error fade"><p>' . esc_html( $message ) .'</p></div>';
	deactivate_plugins( plugin_basename( __FILE__ ) );
}
