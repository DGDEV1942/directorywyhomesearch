<?php

/**
 * AdSanityExport
 *
 * Generates stat output as a CSV
 *
 * @pkg		AdSanity
 * @since	1.6
 */
class AdSanityExport {

	/**
	 * Processes the custom stats export request
	 *
	 * @param array ads an array of ad ids to query
	 * @param string start a string date
	 * @param string end a string date
	 */
	public static function custom_stats_export() {

		$ads_to_export = array();

		foreach( $_POST as $key => $val ) {
			if( substr( $key, 0, 2 ) == 'ad' && $key != 'ad-search' ) {
				array_push( $ads_to_export, $val );
			}
		}

		if( count( $ads_to_export ) == 0 ) {
			wp_die( __( 'You must choose an ad to export.', 'adsanity' ) );
		}

		$ads = AdSanityQuery::get_ads(array(
			'include'		=> $ads_to_export,
			'include_meta'	=> true
		));

		$table = array();
		$start = strtotime( $_POST['start_date'] );
		$end = strtotime( $_POST['end_date'] );
		$total_clicks = $total_views = $total_ctr = 0;
		$viewable_data = false;

		/*********\
		* Headers *
		\*********/
		$row = array();
		$row[] = __( 'Title', 'adsanity' );
		$row[] = __( 'Date', 'adsanity' );
		$row[] = __( 'Views', 'adsanity' );
		$row[] = __( 'Clicks', 'adsanity' );
		$row[] = __( 'CTR %', 'adsanity' );
		$table[0] = self::prepare_csv_line( $row );

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
					$row[] = get_the_title( $ad->ID ); // title
					$row[] = date( get_option( 'date_format' ), intval( $timestamp ) ); // date
					$row[] = number_format_i18n( intval( $meta_value[0] ) ); // views
					$row[] = number_format_i18n( intval( $ad->meta[$clicks_key][0] ) ); // clicks
					$row[] = number_format_i18n( ( intval( $ad->meta[$clicks_key][0] ) / intval( $meta_value[0] ) ) * 100 ); // ctr
					$table[$timestamp . $ad->post_name] = self::prepare_csv_line( $row );

					$total_views += intval( $meta_value[0] );
					$total_clicks += intval( intval( $ad->meta[$clicks_key][0] ) );
				} // endif
			} // endforeach
		} // endforeach

		ksort( $table );

		if ( $viewable_data === false ) {
			wp_die( __( 'There is no statistical data to show with the given parameters.', 'adsanity' ) );
		}

		/********\
		* Totals *
		\********/
		$row = array();
		$row[] = __( 'Totals', 'adsanity' );
		$row[] = '';
		$row[] = number_format_i18n( intval( $total_views ) );
		$row[] = number_format_i18n( intval( $total_clicks ) );
		$row[] = number_format_i18n( ( intval( $total_clicks ) / intval( $total_views ) ) * 100 ) . '%';
		$table[2147483647] = self::prepare_csv_line( $row );

		header( 'Content-Type: text/csv' );
		header( 'Content-Disposition: attachment; filename="adsanity-stats' . time() . '.csv"' );
		echo implode( PHP_EOL, $table );
		die();
	}

	/**
	 * Combine an array of values into a csv ready format
	 * @param  array  $values columns of data
	 * @return string         comma separated and quoted string of text
	 */
	function prepare_csv_line( $values = array() ) {
		$line = '';

		$values = array_map( function ( $v ) {
			return '"' . str_replace( '"', '""', $v ) . '"';
		}, $values );

		$line .= implode( ',', $values );

		return $line;
	}
}
