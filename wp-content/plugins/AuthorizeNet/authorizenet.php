<?php
/*
Plugin Name: Authorize.Net Payment Gateway
Description: Allows customers to use AuthorizeNet as a payment method in AppThemes Products.

AppThemes ID: authorize-net-payment-gateway

Version: 1.1
Author: AppThemes
Author URI: http://appthemes.com
Text Domain: appthemes-authorizenet
*/

add_action( 'init', 'appthemes_an_setup' );

$locale = apply_filters( 'plugin_locale', get_locale(), 'appthemes-authorizenet' );
load_textdomain( 'appthemes-authorizenet', WP_LANG_DIR . "/plugins/authorizenet-$locale.mo" );

function appthemes_an_setup(){

	// Check for right version of Vantage
	if( ! current_theme_supports( 'app-payments' ) ){
		add_action( 'admin_notices', 'appthemes_anet_display_version_warning' );
		return;
	}

	require dirname(__FILE__) . '/compatibility.php';

	require dirname(__FILE__) . '/includes/authorize-net-sdk/autoload.php';
	require dirname(__FILE__) . '/an-gateway.php';
	appthemes_register_gateway( 'APP_AuthorizeNet' );
	
}

function appthemes_anet_display_version_warning(){

	$message = __( 'AppThemes Authorize.Net Payment Gateway could not run.', 'appthemes-authorizenet' );

	if( !current_theme_supports( 'app-payments' ) )
		$message = __( 'AppThemes Authorize.Net Payment Gateway does not support the current theme. Please use a compatible AppThemes Product.', 'appthemes-authorizenet' );

	echo '<div class="error fade"><p>' . $message .'</p></div>';
	deactivate_plugins( plugin_basename( __FILE__ ) );
}

class APP_AuthorizeNet_Fields{

	const REQUEST_URL = 'https://secure.authorize.net/gateway/transact.dll';
	const TEST_URL = 'https://test.authorize.net/gateway/transact.dll';

	const API_VERSION = 'x_version';
	const API_LOGIN = 'x_login';
	const FORM_DISPLAY = 'x_show_form';

	const PAYMENT_TYPE = 'x_type';
	const PAYMENT_METHOD = 'x_method';
	const DUPLICATE_WINDOW = 'x_duplicate_window';

	const AMOUNT = 'x_amount';

	const CARD_NUM = 'x_card_num';
	const CARD_CODE = 'x_card_code';
	const EXP_DATE = 'x_exp_date';

	const TRANSACTION_ID = 'x_trans_id';
	const INVOICE_NUMBER = 'x_invoice_num';
	const DESCRIPTION = 'x_description';

	const CUSTOMER_ID = 'x_cust_id';
	const CUSTOMER_IP = 'x_customer_ip';
	const FIRST_NAME = 'x_first_name';
	const LAST_NAME = 'x_last_name';
	const COMPANY = 'x_company';
	const ADDRESS = 'x_address';
	const CITY = 'x_city';
	const STATE = 'x_state';
	const ZIPCODE = 'x_zip';
	const COUNTRY = 'x_country';

	const PHONE = 'x_phone';
	const EMAIL = 'x_email';
	const SEND_EMAIL = 'x_email_customer';

	const FP_HASH = 'x_fp_hash';
	const FP_SEQUENCE = 'x_fp_sequence';
	const FP_TIMESTAMP = 'x_fp_timestamp';

	const DELIM_DATA = 'x_delim_data';
	const RELAY_RESPONSE = 'x_relay_response';
	const RELAY_URL = 'x_relay_URL';

	const RETURN_METHOD = 'x_receipt_link_method';
	const RETURN_URL = 'x_receipt_link_url';
	const RETURN_TEXT = 'x_receipt_link_text ';

	const CANCEL_URL = 'x_cancel_url';
	const CANCEL_TEXT = 'x_cancel_url_text';

	const TEST_MODE = 'x_test_request';

}
