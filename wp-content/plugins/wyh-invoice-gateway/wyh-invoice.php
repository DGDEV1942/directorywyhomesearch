<?php
/*
Plugin Name: Invoice Payment Gateway
Description: Allows customers to invoice entries as a payment method.
Version: 1.0
Author: 9seeds
*/
//add_filter('all','monkeypee');
function monkeypee($tag)
{
  //  if(stristr($tag,'stylesheet'))
            echo $tag."<br>";
}


add_action( 'init', 'wyh_invoice_setup' );


function wyh_invoice_setup(){

	if ( !current_theme_supports( 'app-payments' ) || !function_exists('curl_init') || !function_exists('json_decode') ) {
	  	add_action( 'admin_notices', 'wyh_invoice_display_version_warning' );
		return;
	}

	require( __DIR__ . '/compatibility.php' );

	require dirname(__FILE__) . '/wyh-invoice-gateway.php';
	appthemes_register_gateway( 'APP_Wyh_Invoice_Gateway' );
	
	$options = APP_Gateway_Registry::get_gateway_options( 'wyh_invoice' );
	add_filter( 'gform_confirmation_'.$options['invoice_form'], 'APP_Wyh_Invoice_Gateway::post_save', 10, 3 );
}

function wyh_invoice_display_version_warning(){

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
