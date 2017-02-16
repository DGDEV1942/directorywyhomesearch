<?php
	global $wp_locale, $wpdb;

	$total_views	= get_transient( 'adsanity-alltime-total-views' );
	$total_clicks	= get_transient( 'adsanity-alltime-total-clicks' );
	$top_ten_clicks	= get_transient( 'adsanity-alltime-top-ten-clicks' );
	$top_ten_ctr	= get_transient( 'adsanity-alltime-top-ten-ctr' );

	if (
		false == $total_views ||
		false == $total_clicks ||
		false == $top_ten_clicks ||
		false == $top_ten_ctr
	) {
		$total_views = $total_clicks = 0;
		$top_ten = array();
		$args = array(
			'post_type'	=> 'ads',
			'nopaging'	=> true,
		);
		$ads = new WP_Query( $args );
		if ( $ads->have_posts() ) {
			while ( $ads->have_posts() ) {
				$ads->the_post();
				$top_ten[$ads->post->ID] = array( 'views' => 0, 'clicks' => 0, 'ctr' => 0.00 );

				$meta = get_post_custom( $ads->post->ID );
				foreach ( $meta as $meta_key => $meta_val ) {
					if ( substr( $meta_key, 0, 7 ) == '_clicks' ) {
						$total_clicks += $meta_val[0];
						$top_ten[$ads->post->ID]['clicks'] += $meta_val[0];
					} elseif ( substr( $meta_key, 0, 6 ) == '_views' ) {
						$total_views += $meta_val[0];
						$top_ten[$ads->post->ID]['views'] += $meta_val[0];
					}
				}

				global $wp_locale;
				$top_ten[$ads->post->ID]['ctr'] = number_format(
					(
						(int) $top_ten[$ads->post->ID]['clicks'] > 0 &&
						(int) $top_ten[$ads->post->ID]['views'] > 0
					) ?
						( (int) $top_ten[$ads->post->ID]['clicks'] / (int) $top_ten[$ads->post->ID]['views'] * 100 )
						:
						'0',
					2,
					$wp_locale->number_format['decimal_point'],
					$wp_locale->number_format['thousands_sep']
				);
			}

			uasort(
				$top_ten,
				create_function(
					'$a, $b',
					'if ( $a["clicks"] == $b["clicks"] ) return 0; return ( $a["clicks"] > $b["clicks"] ) ? -1 : 1;'
				)
			);
			$top_ten_clicks = array_slice( $top_ten, 0, 10, true );

			uasort(
				$top_ten,
				create_function(
					'$a, $b',
					'if ( $a["ctr"] == $b["ctr"] ) return 0; return ( $a["ctr"] > $b["ctr"] ) ? -1 : 1;'
				)
			);
			$top_ten_ctr = array_slice( $top_ten, 0, 10, true );

		}

		set_transient( 'adsanity-alltime-total-views',		$total_views, HOUR_IN_SECONDS );
		set_transient( 'adsanity-alltime-total-clicks',		$total_clicks, HOUR_IN_SECONDS );
		set_transient( 'adsanity-alltime-top-ten-clicks',	$top_ten_clicks, HOUR_IN_SECONDS );
		set_transient( 'adsanity-alltime-top-ten-ctr',		$top_ten_ctr, HOUR_IN_SECONDS );
		wp_reset_query();
	}

	$discovered_agents = $wpdb->get_var($wpdb->prepare("
		SELECT		count(pm.meta_id) as discovered_agents
		FROM		{$wpdb->postmeta} pm
		INNER JOIN	{$wpdb->posts} p
			ON		p.ID = pm.post_id
		WHERE		p.post_type = %s
		AND			pm.meta_key = '_discovered_agents'
	", 'adsanity-data' ));

	$blacklisted_agents = $wpdb->get_var($wpdb->prepare("
		SELECT		count(pm.meta_id) as blacklisted_agents
		FROM		{$wpdb->postmeta} pm
		INNER JOIN	{$wpdb->posts} p
			ON		p.ID = pm.post_id
		WHERE		p.post_type = %s
		AND			pm.meta_key = '_blacklisted_agents'
	", 'adsanity-data' ));
?>

	<!-- COLUMN 1 -->
	<div style="width: 32%; float: left; margin-right: 1.3333%">
		<h3><?php _e( 'All-Time Summary', 'adsanity' ) ?></h3>
		<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Statistic', 'adsanity' ) ?></th>
				<th><?php _e( 'Value', 'adsanity' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php _e( 'Total Views', 'adsanity' ) ?></td>
				<td><?php echo esc_html(
					number_format(
						$total_views,
						0,
						$wp_locale->number_format['decimal_point'],
						$wp_locale->number_format['thousands_sep']
					)
				); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Total Clicks', 'adsanity' ) ?></td>
				<td><?php echo esc_html(
					number_format(
						$total_clicks,
						0,
						$wp_locale->number_format['decimal_point'],
						$wp_locale->number_format['thousands_sep']
					)
				); ?></td>
			</tr>
			<tr>
				<td><?php _e( 'Total CTR', 'adsanity' ) ?></td>
				<td><?php
					$ctr = ( (int)$total_clicks > 0 && (int)$total_views > 0 ) ? ( (int)$total_clicks / (int)$total_views * 100 ) : '0';
					echo esc_html(
						number_format(
							$ctr,
							2,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						).'%' );
				?></td>
			</tr>
			<tr>
				<td><?php _e( 'Bots/Spiders Identified', 'adsanity' ) ?></td>
				<td><?php
					echo esc_html(
						number_format(
							$discovered_agents,
							0,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					);
				?></td>
			</tr>
			<tr>
				<td><?php _e( 'Potential Bots/Spiders', 'adsanity' ) ?></td>
				<td><?php
					echo esc_html(
						number_format(
							$blacklisted_agents,
							0,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					);
				?></td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Statistic', 'adsanity' ) ?></th>
				<th><?php _e( 'Value', 'adsanity' ) ?></th>
			</tr>
		</tfoot>
		</table>
	</div>

	<!-- COLUMN 2 -->
	<div style="width: 32%; float: left; margin-right: 1.3333%">
		<h3><?php _e( 'All-Time Top 10 Clicks', 'adsanity' ) ?></h3>
		<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if( isset( $top_ten_clicks ) && !empty( $top_ten_clicks ) > 0 ) :
				foreach( (array)$top_ten_clicks as $ad_id => $tracking ) : ?>
				<tr>
					<td><a href="<?php echo admin_url( '/post.php?post='.$ad_id.'&action=edit' ) ?>"><?php echo esc_html( get_the_title( $ad_id ) ); ?></a></td>
					<td><?php echo esc_html(
						number_format(
							$tracking['clicks'],
							0,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					); ?></td>
				</tr>
				<?php
				endforeach;
			else :
			?>
				<tr>
					<td colspan="2"><?php _e( 'No clicks have been collected yet.' , 'adsanity' ) ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			</tr>
		</tfoot>
	</table>
	</div>

	<!-- COLUMN 3 -->
	<div style="width: 32%; float: left; margin-right: 1.3333%">
		<h3><?php _e( 'All-Time Top 10 Click Through Rate', 'adsanity' ) ?></h3>
		<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Views', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
				<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			if( isset( $top_ten_ctr ) && !empty( $top_ten_ctr ) > 0 ) :
				foreach( (array)$top_ten_ctr as $ad_id => $tracking ) : ?>
				<tr>
					<td><a href="<?php echo admin_url( '/post.php?post='.$ad_id.'&action=edit' ) ?>"><?php echo esc_html( get_the_title( $ad_id ) ); ?></a></td>
					<td><?php echo esc_html(
						number_format(
							$tracking['views'],
							0,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					); ?></td>
					<td><?php echo esc_html(
						number_format(
							$tracking['clicks'],
							0,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					 ); ?></td>
					<td><?php echo esc_html(
						number_format(
							$tracking['ctr'],
							2,
							$wp_locale->number_format['decimal_point'],
							$wp_locale->number_format['thousands_sep']
						)
					); ?>%</td>
				</tr>
				<?php
				endforeach;
			else :
			?>
				<tr>
					<td colspan="4"><?php _e( 'No views or clicks have been collected yet.' , 'adsanity' ) ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
		<tfoot>
			<tr>
				<th><?php _e( 'Ad Title', 'adsanity' ) ?></th>
				<th><?php _e( 'Views', 'adsanity' ) ?></th>
				<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
				<th><?php _e( 'CTR', 'adsanity' ) ?></th>
			</tr>
		</tfoot>
		</table>
	</div>
