<?php

function adsanity_view( $post_id ) {
/**
 * Checks to see if the view should be tracked based on rules, then stores the value
 * @param  int $post_id the ID of the ad that's being viewed
 */

	if( ADSANITY_TRACK_THIS ) :

		global $wpdb;

		$meta_key = adsanity_get_meta_key( 'views' );

		if( get_post_meta( $post_id, $meta_key, true ) ) :
			$add_view = $wpdb->query(
				$wpdb->prepare( "
					UPDATE		{$wpdb->prefix}postmeta
					SET			meta_value=meta_value+1
					WHERE		meta_key=%s
					AND			post_id=%s
				", $meta_key, $post_id )
			);
		else :
			update_post_meta( $post_id, $meta_key, '1' );
		endif;

	endif;
}

/**
 * Checks to see if the click should be tracked based on rules, then stores the value
 * @param  int $post_id the ID of the ad that's being viewed
 */
function adsanity_click( $post_id ) {

	if( ADSANITY_TRACK_THIS ) :

		global $wpdb;

		$meta_key = adsanity_get_meta_key( 'clicks' );

		if( get_post_meta( $post_id, $meta_key, true ) ) :
			$add_click = $wpdb->query(
				$wpdb->prepare( "
					UPDATE		{$wpdb->prefix}postmeta
					SET			meta_value=meta_value+1
					WHERE		meta_key=%s
					AND			post_id=%s
				", $meta_key, $post_id )
			);
		else :
			update_post_meta( $post_id, $meta_key, '1' );
		endif;

	endif;
}

// Check to see if we should track views/clicks for this request
add_action( 'init', 'adsanity_should_we_track_this' );
function adsanity_should_we_track_this() {
	global $current_user, $adsanity_tzstring;

	$adsanity_tzstring = adsanity_get_timezone_string();

	// don't track people who can create ads
	if( defined( 'ADSANITY_TRACK_THIS' ) ) :
		return false;
	endif;

	// don't track people who can create ads
	if( user_can( $current_user->ID, 'edit_posts' ) ) :
		define( 'ADSANITY_TRACK_THIS', false );
		return false;
	endif;

	// check the cache for the storage device
	$data_storage_post = wp_cache_get( 'adsanity-data', 'adsanity' );
	if( !$data_storage_post ) :

		$data = new WP_Query( array( 'post_type' => 'adsanity-data', 'posts_per_page' => 1 ) );
		if( $data->have_posts() ) : $data->the_post();
			$data_storage_post = $data->post;
			wp_reset_query();
		else :
			$post_id = wp_insert_post(array(
				'post_type'		=> 'adsanity-data',
				'post_title'	=> 'AdSanity Data Storage',
				'post_content'	=> 'DO NOT DELETE! If you do, the sky will fall on your head.',
				'post_status'	=> 'publish',
			));
			$data_storage_post = get_post( $post_id );
		endif;

		// store the post in the cache for one year
		wp_cache_set( 'adsanity-data', $data_storage_post, 'adsanity', time() + ( 60*60*24*365 ) );
	endif;

	$discovered = get_post_meta( $data_storage_post->ID, '_discovered_agents', false );
	$blacklisted = get_post_meta( $data_storage_post->ID, '_blacklisted_agents', false );
	$agent = strtolower( $_SERVER['HTTP_USER_AGENT'] );

	if(
		strpos( $agent, 'bot' ) === false &&
		strpos( $agent, 'crawler' ) === false &&
		strpos( $agent, 'spider' ) === false &&
		strpos( $agent, 'wordpress' ) === false &&
		!in_array( $agent, (array) $blacklisted )
	) :
		define( 'ADSANITY_TRACK_THIS', true );

		// Add this user agent to the discovered agents for manual tweaking
		if( !in_array( $agent, (array) $discovered ) ) :
			add_post_meta( $data_storage_post->ID, '_discovered_agents', $agent, false );
		endif;

	else :
		define( 'ADSANITY_TRACK_THIS', false );

		// It's a non-human user, don't track it and blacklist that sucka
		if( !in_array( $agent, (array) $blacklisted ) ) :
			add_post_meta( $data_storage_post->ID, '_blacklisted_agents', $agent, false );
		endif;

	endif;
} // adsanity_should_we_track_this


/**
 * Utility function to calculate the timezone and return the appropriate Timezone string
 * @return string the converted timezone string
 */
function adsanity_get_timezone_string() {
	$gmt_offset = get_option( 'gmt_offset' );
	$tzstring = get_option( 'timezone_string' );

	// Remove old Etc/UTC/GMT mappings. Fallback to gmt_offset.
	if( preg_match( '/(UTC[+-]|Etc|GMT)/i', $tzstring ) )
		$tzstring = '';

	if( empty( $tzstring ) ) : // Create a UTC+- zone if no timezone string exists
		$tzstring = timezone_name_from_abbr( null, $gmt_offset, true );
		if( $tzstring === false ) :
			$tzstring = timezone_name_from_abbr( null, $gmt_offset, false );
		endif;
	endif;

	return $tzstring;
} // adsanity_get_timezone_string


/**
 * Get the meta key for the current day
 * @param  string $click_or_view  possible values are 'views' and 'clicks'
 * @return string                the full meta key
 */
function adsanity_get_meta_key( $click_or_view = 'views' ) {

	global $adsanity_tzstring;

	// (DEFAULT) timestamp for midnight UTC today
							                     // h, m, s, m,         d,         y
	$meta_key = sprintf( '_%s-%s', $click_or_view, mktime( 0, 0, 0, date("n"), date("j"), date("Y") ) );

	// check for wp location
	if( $adsanity_tzstring ) :
		// Set local timezone
		date_default_timezone_set( $adsanity_tzstring );
		$local_day = date("j");
		$local_month = date("n");
		$local_year = date("Y");

		// Reset back to UTC.
		date_default_timezone_set( 'UTC' );

		// (OVERRIDE) timestamp for midnight UTC today based on the local day
		$meta_key = sprintf( '_%s-%s', $click_or_view, mktime( 0, 0, 0, $local_month, $local_day, $local_year ) );
	endif;

	return $meta_key;
} // adsanity_get_meta_key
