<?php

/**
 * AdSanityAjax
 *
 * Contains all helper methods for AdSanity ajax.
 *
 * @pkg		AdSanity
 * @since	1.6
 */
class AdSanityAjax {

	/**
	 * Kicks off all actions and filters
	 */
	public static function hooks() {
		add_action(
			'wp_ajax_custom_stats_selection',
			array(
				get_called_class(),
				'custom_stats_selection'
			)
		);
	}

	/**
	 * Processes the custom stats ajax request
	 *
	 * @param array ads an array of ad ids to query
	 * @param string start a string date
	 * @param string end a string date
	 */
	public static function custom_stats_selection() {

		if( ! isset( $_POST['ads'] ) || ! isset( $_POST['start'] ) || ! isset( $_POST['end'] ) ) {
			self::no_stat_data( __( 'You must choose an ad to view.', 'adsanity' ) );
		}

		if( count( $_POST['ads'] ) > 15 ) {
			self::no_stat_data( __( 'For display reasons, please select no more than 15 ads to compare.', 'adsanity' ) );
		}

		$ads = AdSanityQuery::get_ads(array(
			'include'		=> $_POST['ads'],
			'include_meta'	=> true
		));

		$chart = '';
		$table = array();
		$start = strtotime( $_POST['start'] );
		$end = strtotime( $_POST['end'] );
		$total_clicks = $total_views = $total_ctr = 0;
		$all_views = $all_clicks = array();
		$viewable_data = false;

		foreach ( $ads as $ad ) {

			// Sort the meta fields
			ksort( $ad->meta );
			$views = $clicks = array();

			foreach ( $ad->meta as $meta_key => $meta_value ) {

				if ( strpos( $meta_key, 'view' ) !== false ) {

					// Do we have data in the selected date range?
					$timestamp = substr( $meta_key, 7 );
					if ( $start > intval( $timestamp ) || $end < intval( $timestamp ) ) {
						continue;
					}

					$viewable_data = true;

					$clicks_key = '_clicks-' . $timestamp;

					if ( ! isset( $ad->meta[$clicks_key] ) ) {
						$ad->meta[$clicks_key][0] = 0;
					}

					/******************\
					* Setup Table Data *
					\******************/

					$row = array();
					$row[] = '<tr>';
					$row[] = sprintf( '<td>%s</td>', get_the_title( $ad->ID ) ); // title
					$row[] = sprintf( '<td>%s</td>', date( get_option( 'date_format' ), intval( $timestamp ) ) ); // date
					$row[] = sprintf( '<td>%s</td>', number_format_i18n( intval( $meta_value[0] ) ) ); // views
					$row[] = sprintf( '<td>%s</td>', number_format_i18n( intval( $ad->meta[$clicks_key][0] ) ) ); // clicks
					$row[] = sprintf( '<td>%s%%</td>', number_format_i18n( ( intval( $ad->meta[$clicks_key][0] ) / intval( $meta_value[0] ) ) * 100 ) ); // ctr
					$row[] = '</tr>';
					$table[$timestamp . $ad->post_name] = implode( '', $row );

					$total_views += intval( $meta_value[0] );
					$total_clicks += intval( intval( $ad->meta[$clicks_key][0] ) );

					/******************\
					* Setup Chart Data *
					\******************/

					$views[$timestamp] = array(
						'id' => $ad->ID,
						'timestamp' => $timestamp,
						'title' => get_the_title( $ad->ID ),
						'viewcount' => intval( $meta_value[0] )
					);
					$clicks[$timestamp] = array(
						'id' => $ad->ID,
						'timestamp' => $timestamp,
						'title' => get_the_title( $ad->ID ),
						'clickcount' => intval( $ad->meta[$clicks_key][0] )
					);

					$all_views[$ad->ID] = $views;
					$all_clicks[$ad->ID] = $clicks;
				} // endif
			} // endforeach
		} // endforeach

		ksort( $table );

		if ( $viewable_data === false ) {
			self::no_stat_data( __( 'There is no statistical data to show with the given parameters.', 'adsanity' ) );
		}

		echo json_encode(array(
			'table' => implode( '', $table ),
			'total_views' => number_format_i18n( intval( $total_views ) ),
			'total_clicks' => number_format_i18n( intval( $total_clicks ) ),
			'total_ctr' => number_format_i18n( ( intval( $total_clicks ) / intval( $total_views ) ) * 100 ) . '%',
			'chart_data' => array( 'views' => $all_views, 'clicks' => $all_clicks )
		));
		die();
	}

	/**
	 * No data in the result set, so return an empty statement
	 * @param  string $message a custom message to be sent back to the browser
	 */
	public static function no_stat_data( $message = '' ) {
		echo json_encode(array(
			'table' => sprintf( '<tr><td colspan="5">%s</td></tr>', $message ),
			'total_views' => __( '0', 'adsanity' ),
			'total_clicks' => __( '0', 'adsanity' ),
			'total_ctr' => __( '0%', 'adsanity' ),
			'chart_data' => array( 'views' => array(), 'clicks' => array() )
		));
		die();
	}
}

if( is_admin() ) {
	AdSanityAjax::hooks();
}
