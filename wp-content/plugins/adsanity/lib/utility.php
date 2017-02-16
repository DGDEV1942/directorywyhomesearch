<?php

/**
 * AdsanityUtility
 *
 * Contains useful utility functions
 *
 * @pkg		AdSanity
 * @since	1.6
 */
class AdSanityUtility {

	/**
	 * Removes WP Core meta data from a given array of meta keys and values
	 *
	 * @param  array  $meta  an associative array of meta keys and values
	 */
	public static function remove_wp_meta( &$meta = array() ) {
		$wp_meta_keys = array( '_edit_last', '_edit_lock' );
		foreach( $meta as $meta_key => $meta_value ) {
			if ( in_array( $meta_key, $wp_meta_keys ) ) {
				unset( $meta[$meta_key] );
			}
		}
	}
}
