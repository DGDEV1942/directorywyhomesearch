<?php

/**
 * Ad group template tag
 * @param  array  $args accepted args:
 *                      - (bool) is_widget: outputs widget wrap/title
 *                      - (array) widget_args: only used in widget mode
 *                      - (string) title: only used in widget mode
 *                      - (array) group_ids: array of ad-group term ids
 *                      - (int) num_ads: number of ads to show
 *                      - (int) num_columns: number of columns
 *                      - (bool) return: whether to return or directly output
 * @return string       if return is set to true, will return the html. used in shortcodes.
 */
function adsanity_show_ad_group( $args = array() ) {
	$defaults = array(
		'is_widget'		=> false,
		'widget_args'	=> array(),
		'title'			=> false,
		'group_ids'		=> array(),
		'num_ads'		=> 0,
		'num_columns'	=> 0,
		'return'		=> false
	);
	extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

	if( count( $group_ids ) < 1 )
		return false;

	if( $return )
		ob_start();

	$now = time();

	// Get the cached version of the ad first
	$ads = wp_cache_get( 'group-of-ads-'.implode( '-', $group_ids ), ADSANITY );
	if ( false == $ads ) :

		$ads = new WP_Query(
			array(
				'post_type' => 'ads',
				'tax_query' => array(
					array(
						'taxonomy' => 'ad-group',
						'field' => 'id',
						'terms' => implode( ',', $group_ids )
					)
				),
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
				),
				'posts_per_page' => $num_ads,
				'orderby' => 'rand'
			)
		);
		wp_cache_set( 'group-of-ads-'.implode( '-', $group_ids ), $ads, ADSANITY, 60 ); // cache for 60 seconds
	endif;
	if( $ads->have_posts() ) :
		// cache for 60 seconds

		if( $is_widget ) :
			echo $widget_args['before_widget'];

			if( $title && !empty( $title ) ) :
				echo $widget_args['before_title'].$title.$widget_args['after_title'];
			endif;
		endif;

		$index = 0;
		while( $ads->have_posts() ) : $ads->the_post();

			if( $num_columns > 1 ) :
				$column = ( $index % $num_columns ) + 1;
			else :
				$column = 1;
			endif;

			// Count the view
			adsanity_view( $ads->post->ID );

			$size = get_post_meta( $ads->post->ID, '_size', true );

			$post = $ads->post;

			// override in a parent or child theme
			$custom_template = locate_template( array( "ad-{$ads->post->ID}.php", "ad-{$size}.php", "ad.php" ) );
			if( ! empty( $custom_template ) ) :
				require( $custom_template );

			// generic ad size template in the plugin
			else :
				require( ADSANITY_THEME.'ad.php' );
			endif;

			$index++;
		endwhile;

		echo '<div class="clear clearfix clearboth"></div>';

		if( $is_widget ) :
			echo $widget_args['after_widget'];
		endif;

		wp_reset_postdata();
	endif;

	if( $return ) :
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	endif;

}

/**
 * Single ad template tag
 * @param  array  $args accepted args:
 *                      - (bool) is_widget: outputs widget wrap/title
 *                      - (array) widget_args: only used in widget mode
 *                      - (string) title: only used in widget mode
 *                      - (int) post_id: ID of ad to show
 *                      - (string) align: alignnone|alignleft|aligncenter|alignright
 *                      - (bool) return: whether to return or directly output
 * @return string       if return is set to true, will return the html. used in shortcodes.
 */
function adsanity_show_ad( $args = array() ) {
	$defaults = array(
		'is_widget'		=> false,
		'widget_args'	=> array(),
		'title'			=> false,
		'post_id'		=> 1,
		'align'			=> false,
		'return'		=> false
	);
	$display = extract( wp_parse_args( $args, $defaults ), EXTR_SKIP );

	if( (int)$post_id != $post_id )
		return false;

	if( $return )
		ob_start();

	$now = time();

	// Get the cached version of the ad first
	$ad = wp_cache_get( 'single-ad-'.$post_id, ADSANITY );
	if ( false == $ad ) :

		// Get the ad using start/end dates
		$ad = new WP_Query(
			array(
				'p' => $post_id,
				'post_type' => 'ads',
				'meta_query' => array(
					array(
						'key'		=> '_start_date',
						'value'		=> $now,
						'type'		=> 'numeric',
						'compare'	=> '<='
					),
					array(
						'key'		=> '_end_date',
						'value'		=> $now,
						'type'		=> 'numeric',
						'compare'	=> '>='
					)
				),
				'posts_per_page' => 1
			)
		);
		wp_cache_set( 'single-ad-'.$post_id, $ad, ADSANITY, 60*60*12 ); // cache for 12 hours
	endif;
	if( $ad->have_posts() ) : $ad->the_post();
		// cache for 24 hours

		// Count the view
		adsanity_view( $post_id );

		if( $is_widget ) :
			echo $widget_args['before_widget'];

			if( $title && !empty( $title ) ) :
				echo $widget_args['before_title'].$title.$widget_args['after_title'];
			endif;
		endif;

		$size = get_post_meta( $post_id, '_size', true );

		$post = $ad->post;

		// override in a parent or child theme
		$custom_template = locate_template( array( "ad-{$ad->post->ID}.php", "ad-{$size}.php", "ad.php" ) );
		if( ! empty( $custom_template ) ) :
			require( $custom_template );

		// generic ad size template in the plugin
		else :
			require( ADSANITY_THEME.'ad.php' );
		endif;

		if( $is_widget ) :
			echo $widget_args['after_widget'];
		endif;

		wp_reset_postdata();
	endif;

	if( $return ) :
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	endif;
}
