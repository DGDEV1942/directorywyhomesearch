<?php
// Add a parameter to the URL Google uses for geocoding to limit results to near WY

function wyh_limit_results( $url ) {
	$url = $url . '&components=administrative_area:WY';
	return $url;
}

add_filter( 'google_geocode_url', 'wyh_limit_results' );

// We want the reviews to be automatically set to "open" for listings when created
function wyh_enable_reviews( $post ) {

	// We only want this for listings
	if( $post[ 'post_type' ] !== 'listing' ) {
		return $post;
	}

	/*
	 * We only want to run this on post creation, not from the admin
	 * screen.  This allow the admin to manually turn off comments
	 * from the back-end without this function overriding it.
	 */

	if( is_admin() ) {
		return $post;
	}

	$post[ 'comment_status' ] = 'open';

	return $post;
}

add_filter( 'wp_insert_post_data', 'wyh_enable_reviews' );

/*
 * We're temporarily removing the ability to enter coupon codes
 * from the front-end.  When the client wants coupons again, just
 * delete this remove_action and the theme will take care of
 * the rest.
 */

remove_action( 'init', 'appthemes_coupons_setup' );

function whs_enqueue_js() {
	wp_register_script( 'general', get_stylesheet_directory_uri() . '/inc/js/general.js', array( 'jquery' ), '', true );
}

add_action( 'wp_enqueue_scripts', 'whs_enqueue_js');

require_once get_template_directory() . '/framework/scb/PostMetabox.php';
require_once get_template_directory() . '/framework/admin/class-meta-box.php';
require_once get_template_directory() . '/framework/kernel/functions.php';

class VA_Multiple_Listing_Bool_Metabox extends APP_Meta_Box {

	public function __construct(){
		parent::__construct( 'multiple-listings', __( 'Multiple Listings', APP_TD ), 'pricing-plan', 'normal', 'low' );
	}

	public function form_fields(){
		$plan_form = array();

		$plan_form[] = array(
			'title' => __( 'Multiple Listings?', APP_TD ),
			'type' => 'checkbox',
			'name' => 'multiple_listings_bool',
			'desc' => 'Does this plan allow for multiple listings?',
		);

		return $plan_form;
	}
}

appthemes_add_instance( VA_Multiple_Listing_Bool_Metabox );

function get_page_by_slug( $page_slug, $output = OBJECT, $post_type = 'page' ) { 
	global $wpdb; 
	$page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type= %s AND post_status = 'publish'", $page_slug, $post_type ) ); 
	if ( $page ) {
		return get_post($page, $output);
	}
	return null; 
}

function get_multiple_listing_page_url() {
	$multiple_listings_page_args = array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'template-multiple-listings.php',
	);

	$multiple_listings_pages = get_pages( $multiple_listings_page_args );

	/*
	 * There should be only one page using this template, so let's grab the ID from the
	 * first page in the array.
	 */
	// Only run this if there's a page using that template.
	if( isset( $multiple_listings_pages ) ) {
		$multiple_listings_page_id = $multiple_listings_pages[ 0 ]->ID;
		$multiple_listings_page_url = get_permalink( $multiple_listings_page_id );
		return ( $multiple_listings_page_url );
	} else {
		return;
	}
}