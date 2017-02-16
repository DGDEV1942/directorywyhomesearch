<?php

/**
 * Registers the "adsanity_ad_group" shortcode for use wherever shortcodes are allowed
 * @param  array $atts    accepted shortcode attributes: num_ads, num_columns, group_ids (comma
 *                        separated)
 * @return string         html output from the template tag
 */
function adsanity_ad_group( $atts = array() ) {
	$atts['group_ids'] = explode(',', $atts['group_ids'] );
	$atts['return'] = true;
	return '<div class="adsanity-shortcode">'.adsanity_show_ad_group( $atts ).'</div>';
}
add_shortcode('adsanity_group', 'adsanity_ad_group');


/**
 * Registers the "adsanity" (legacy) and "adsanity_ad" shortcodes for use wherever shortcodes are
 * allowed
 * @param  array $atts  accepted shortcode attributes: id, align
 * @return string       html output from the template tag
 */
function adsanity_shortcode( $atts = array() ) {
	$defaults = array(
		'id'			=> 1,
		'align'			=> 'alignnone'
	);
	$atts = extract( shortcode_atts( $defaults, $atts ), EXTR_SKIP );

	$args = array(
		'is_widget'		=> false,
		'post_id'		=> $id,
		'align'			=> $align,
		'return'		=> true
	);
	return adsanity_show_ad( $args );
}
add_shortcode( 'adsanity', 'adsanity_shortcode' );


///////////////////////////////////////////////////////////////////////////////


// registers the buttons for use
function register_adsanity_buttons($buttons) {
	array_push($buttons, "adsanity_ad", "adsanity_ad_group");
	return $buttons;
}

// add the button to the tinyMCE bar
function add_adsanity_tinymce_plugin($plugin_array) {
	$plugin_array['AdSanity'] = ADSANITY_JS . 'tinymce.shortcodes.js';
	return $plugin_array;
}

// filters the tinyMCE buttons and adds our custom buttons
function adsanity_shortcode_buttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
		return;

	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		// filter the tinyMCE buttons and add our own
		add_filter("mce_external_plugins", "add_adsanity_tinymce_plugin");
		add_filter('mce_buttons', 'register_adsanity_buttons');
	}
}

// init process for button control
add_action('init', 'adsanity_shortcode_buttons');


/**
 * Ajax hook for the Single Ad shortcode modal window
 */
function adsanity_shortcode_form() {
	$ads = adsanity_ads_select_list();
	require( ADSANITY_LIB.'ad_popup.php' );
	die();
}
add_action( 'wp_ajax_adsanity_shortcode_form', 'adsanity_shortcode_form' );

	// Helper function to get a list of all ads
	function adsanity_ads_select_list() {

		$now = time();

		// get all non-expired ads
		$ads = get_posts(  array(
			'numberposts'		=>	-1,
			'nopaging'			=> true,
			'orderby'			=>	'ID',
			'order'				=>	'ASC',
			'post_type'			=>	'ads',
			'post_status'		=>	'publish',
			'meta_query' => array(
				array(
					'key' => '_start_date',
					'value' => $now,
					'type' => 'numeric',
					'compare' => '<='
				),
				array(
					'key' => '_end_date',
					'value' => $now,
					'type' => 'numeric',
					'compare' => '>='
				)
// Helper function to get a list of all ads
			),
		));
		$return = array();
		foreach ( $ads as $ad ) :
			$return[] = sprintf( '<option value="%d">%s (%s)</option>', $ad->ID, esc_html( $ad->post_title ), get_post_meta(  $ad->ID, '_size', true ) );
		endforeach;
		return implode( '', $return );
	}

/**
 * Ajax hook for Ad Group shortcode modal
 */
function adsanity_group_shortcode_form() {
	$groups = adsanity_groups_select_list();
	require( ADSANITY_LIB.'ad_group_popup.php' );
	die();
}
add_action( 'wp_ajax_adsanity_group_shortcode_form', 'adsanity_group_shortcode_form' );

	// Helper function to get a list of all ad groups
	function adsanity_groups_select_list() {
// Helper function to get a list of all ad groups

		$args = array(
			'orderby'		=> 'name',
			'order'			=> 'ASC',
			'pad_counts'	=> 1
		);
		$groups = get_terms( 'ad-group', $args );

		$return = array();
		foreach ( $groups as $group ) :
			$return[] = sprintf( '<option value="%d">%s (%s)</option>', $group->term_id, esc_html( $group->name ), sprintf( '%d ads', $group->count ) );
		endforeach;
		return implode( '', $return );
	}
