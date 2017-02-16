<?php

if ( ! defined( 'ADSANITY_UPDATE_API' ) ) {
	define( 'ADSANITY_UPDATE_API', 'http://adsanityplugin.com' );
}

if ( ! defined( 'ADSANITY_PRODUCT' ) ) {
	define( 'ADSANITY_PRODUCT', 'AdSanity Core' );
}

// load our custom updater
if ( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

/**
 * Loads EDD Updater class and checks for updates
 */
function adsanity_plugin_updater() {

	// retrieve our license key from the DB
	$license_key = trim( get_option( 'adsanity_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( ADSANITY_UPDATE_API, __FILE__, array(
			'version' 	=> '1.0.9', 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => ADSANITY_PRODUCT, 	// name of this plugin
			'author' 	=> 'Pixel Jar'  // author of this plugin
		)
	);
}
add_action( 'admin_init', 'adsanity_plugin_updater', 0 );

function adsanity_license_menu() {

	// Create "License" submenu
	$license = add_submenu_page(
		'edit.php?post_type=ads',
		__( 'License', 'adsanity' ),
		__( 'License', 'adsanity' ),
		'manage_options',
		'adsanity-license',
		'adsanity_license_page'
	);
}
add_action( 'admin_menu', 'adsanity_license_menu' );

function adsanity_license_page() {
	$license 	= get_option( 'adsanity_license_key' );
	$status 	= get_option( 'adsanity_license_status' );
	?>
	<div class="wrap">
		<h2><?php _e( 'License Options', 'adsanity' ); ?></h2>
		<form method="post" action="options.php">

			<?php settings_fields( 'adsanity_license' ); ?>

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row" valign="top">
							<?php _e( 'License Key', 'adsanity' ); ?>
						</th>
						<td>
							<input id="adsanity_license_key" name="adsanity_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
							<label class="description" for="adsanity_license_key"><?php _e( 'Enter your license key', 'adsanity' ); ?></label>
						</td>
					</tr>
					<?php if( false !== $license ) { ?>
						<tr valign="top">
							<th scope="row" valign="top">
								<?php _e( 'Activate License', 'adsanity' ); ?>
							</th>
							<td>
								<?php if( $status !== false && $status == 'valid' ) { ?>
									<span style="color:green;"><?php _e( 'active', 'adsanity' ); ?></span>
									<?php wp_nonce_field( 'adsanity_license_nonce', 'adsanity_license_nonce' ); ?>
									<input type="submit" class="button-secondary" name="adsanity_license_deactivate" value="<?php _e( 'Deactivate License', 'adsanity' ); ?>"/>
								<?php } else {
									wp_nonce_field( 'adsanity_license_nonce', 'adsanity_license_nonce' ); ?>
									<input type="submit" class="button-secondary" name="adsanity_license_activate" value="<?php _e( 'Activate License', 'adsanity' ); ?>"/>
								<?php } ?>
							</td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
			<?php submit_button(); ?>

		</form>
	<?php
}

function adsanity_register_option() {
	// creates our settings in the options table
	register_setting( 'adsanity_license', 'adsanity_license_key', 'adsanity_sanitize_license' );
}
add_action( 'admin_init', 'adsanity_register_option' );

function adsanity_sanitize_license( $new ) {
	$old = get_option( 'adsanity_license_key' );
	if( $old && $old != $new ) {
		delete_option( 'adsanity_license_status' ); // new license has been entered, so must reactivate
	}
	return $new;
}



/************************************
* this illustrates how to activate
* a license key
*************************************/

function adsanity_activate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['adsanity_license_activate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'adsanity_license_nonce', 'adsanity_license_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'adsanity_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'activate_license',
			'license' 	=> $license,
			'item_name' => urlencode( ADSANITY_PRODUCT ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ADSANITY_UPDATE_API, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		// $license_data->license will be either "valid" or "invalid"

		update_option( 'adsanity_license_status', $license_data->license );
	}
}
add_action( 'admin_init', 'adsanity_activate_license' );


/***********************************************
* Illustrates how to deactivate a license key.
* This will descrease the site count
***********************************************/

function adsanity_deactivate_license() {

	// listen for our activate button to be clicked
	if( isset( $_POST['adsanity_license_deactivate'] ) ) {

		// run a quick security check
	 	if( ! check_admin_referer( 'adsanity_license_nonce', 'adsanity_license_nonce' ) )
			return; // get out if we didn't click the Activate button

		// retrieve the license from the database
		$license = trim( get_option( 'adsanity_license_key' ) );


		// data to send in our API request
		$api_params = array(
			'edd_action'=> 'deactivate_license',
			'license' 	=> $license,
			'item_name' => urlencode( ADSANITY_PRODUCT ), // the name of our product in EDD
			'url'       => home_url()
		);

		// Call the custom API.
		$response = wp_remote_post( ADSANITY_UPDATE_API, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

		// make sure the response came back okay
		if ( is_wp_error( $response ) )
			return false;

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		// $license_data->license will be either "deactivated" or "failed"
		//
		if( $license_data->license == 'deactivated' )
			delete_option( 'adsanity_license_status' );

	}
}
add_action( 'admin_init', 'adsanity_deactivate_license' );


/************************************
* this illustrates how to check if
* a license key is still valid
* the updater does this for you,
* so this is only needed if you
* want to do something custom
*************************************/

function adsanity_check_license() {

	global $wp_version;

	$license = trim( get_option( 'adsanity_license_key' ) );

	$api_params = array(
		'edd_action' => 'check_license',
		'license' => $license,
		'item_name' => urlencode( ADSANITY_PRODUCT ),
		'url'       => home_url()
	);

	// Call the custom API.
	$response = wp_remote_post( ADSANITY_UPDATE_API, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );

	if ( is_wp_error( $response ) )
		return false;

	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if( $license_data->license == 'valid' ) {
		echo 'valid'; exit;
		// this license is still valid
	} else {
		echo 'invalid'; exit;
		// this license is no longer valid
	}
}
