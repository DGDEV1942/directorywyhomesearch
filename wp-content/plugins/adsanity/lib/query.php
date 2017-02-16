<?php

/**
 * AdSanityQuery
 *
 * Contains all helper methods for query AdSanity data.
 *
 * @pkg		AdSanity
 * @since	1.6
 */
class AdSanityQuery {

	/**
	 * Retrieves all ad posts
	 *
	 * @uses AdSanityQuery::get_ads returns ads based on parameters
	 * @return  array|wp_error  returns an array of WP_Post objects on success and a wp_error object
	 * on failure
	 */
	public static function get_all_ads() {
		return self::get_ads( array( 'nopaging' => true ) );
	}

	/**
	 * Retrieves all ad posts
	 *
	 * @return  array|wp_error  returns an array of WP_Post objects on success and a wp_error object
	 * on failure
	 */
	public static function get_ads( $args = array() ) {
		$defaults = array(
			'post_type'		=> 'ads'
		);
		$ad_args = wp_parse_args( $args, $defaults );

		$ads = get_posts( $ad_args );
		if ( $ads ) {

			// Attach all post meta to the WP_Post object under 'meta'
			if( isset( $ad_args['include_meta'] ) && $ad_args['include_meta'] ) {
				self::attach_meta( $ads );
			}

			return $ads;

		} else {
			return new WP_Error( 'adsanity_query_no_ads', __( 'No ads found.', 'adsanity' ) );
		}
	}

	/**
	 * Attaches all meta assigned to an ad to the ad object.
	 *
	 * @param  array  $ads  An array of WP_Post objects
	 */
	public static function attach_meta( &$ads = array() ) {
		foreach( $ads as $ad ) {
			$meta = get_post_meta( $ad->ID );
			AdSanityUtility::remove_wp_meta( $meta );
			$ad->meta = apply_filters( 'adsanity_attach_meta', $meta );
		}
	}
}
