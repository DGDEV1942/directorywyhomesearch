<h3><?php _e( 'Custom Reports', 'adsanity' ) ?></h3>
<p class="description">
	<?php _e( 'Choose from the options below to customize the results.', 'adsanity' ) ?>
</p>

<div id="customizing-bar">
	<form id="data-export" method="post">
		<?php wp_nonce_field( 'adsanity-stat-export', '_adsanity_export_nonce' ); ?>
		<label for="start_date"><?php _e( 'Date Range', 'adsanity' ) ?></label>
		<input type="text" name="start_date" value="<?php echo date( 'F d, Y', time() - ( DAY_IN_SECONDS * 30 ) ) ?>" id="start_date" /> to
		<input type="text" name="end_date" value="<?php echo date( 'F d, Y' ) ?>" id="end_date" />

		<div id="ad-choices" style="background: url('<?php echo admin_url( 'images/arrows.png' ) ?>') no-repeat scroll 165px 11px #fff">
			<?php _e( 'Select Which Ads to Show', 'adsanity' ) ?>
			<span class="mass-select"><a href="#" class="selectall"><?php _e( 'All', 'adsanity' ); ?></a> <a href="#" class="selectnone"><?php _e( 'None', 'adsanity' ); ?></a></span>
			<input type="text" name="ad-search" value="search ads" id="ad-search" class="widefat" />
			<ul>
			<?php
				$ads = AdSanityQuery::get_all_ads();
				if ( ! is_wp_error( $ads ) ) {
					foreach ( $ads as $ad ) {
						echo sprintf(
							'<li><label for="ad-%1$d">',
							intval( $ad->ID )
						);
						echo sprintf(
							'<input type="checkbox" name="ad-%1$d" id="ad-%d" value="%1$d" /> ',
							intval( $ad->ID )
						);
						echo get_the_title( $ad->ID );
						echo '</label></li>';
					}
				}
			?>
			</ul>
		</div>
	</form>
</div>


<div id="custom-views-container" style="display: none;">
	<h3><?php _e( 'View Data', 'adsanity' ) ?></h3>
	<div id="custom-views-chart"></div>
</div>

<div id="custom-clicks-container" style="display: none;">
	<h3><?php _e( 'Click Data', 'adsanity' ) ?></h3>
	<div id="custom-clicks-chart"></div>
</div>

<div id="custom-report-container">
	<h3>
		<?php _e( 'Detailed Report', 'adsanity' ) ?>
		<a href="#export" id="export-csv" class="add-new-h2">
			<?php _e( 'Export CSV', 'adsanity' ); ?>
		</a>
	</h3>
	<table class="widefat">
	<thead>
		<tr>
			<th><?php _e( 'Ad Name', 'adsanity' ) ?></th>
			<th><?php _e( 'Date', 'adsanity' ) ?></th>
			<th><?php _e( 'Views', 'adsanity' ) ?></th>
			<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			<th><?php _e( 'CTR', 'adsanity' ) ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th><?php _e( 'Totals', 'adsanity' ) ?></th>
			<th>&nbsp;</th>
			<th id="total-views"><?php _e( '0', 'adsanity' ) ?></th>
			<th id="total-clicks"><?php _e( '0', 'adsanity' ) ?></th>
			<th id="total-ctr"><?php _e( '0%', 'adsanity' ) ?></th>
		</tr>
		<tr>
			<th><?php _e( 'Ad Name', 'adsanity' ) ?></th>
			<th><?php _e( 'Date', 'adsanity' ) ?></th>
			<th><?php _e( 'Views', 'adsanity' ) ?></th>
			<th><?php _e( 'Clicks', 'adsanity' ) ?></th>
			<th><?php _e( 'CTR', 'adsanity' ) ?></th>
		</tr>
	</tfoot>
	<tbody id="adsanity-data">
		<tr>
			<td colspan="5">
				<?php _e( 'Make your selections above to see detailed data here.', 'adsanity' ) ?>
			</td>
		</tr>
	</tbody>
	</table>
</div>
