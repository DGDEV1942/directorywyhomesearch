<?php $is_dashboard_tab = ( !isset( $_GET['tab'] ) || ( isset( $_GET['tab'] ) && $_GET['tab'] == 'dashboard' ) ) ? true : false; ?>
<div class="wrap">
	<?php screen_icon( 'ads' ); ?>
	<h2 class="nav-tab-wrapper">
		<a class="nav-tab<?php echo ( $is_dashboard_tab ? ' nav-tab-active' : '' ) ?>" href="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-stats&tab=dashboard' ) ?>"><?php _e( 'Stats Dashboard', 'adsanity' ) ?></a>
		<a class="nav-tab<?php echo ( !$is_dashboard_tab ? ' nav-tab-active' : '' ) ?>" href="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-stats&tab=custom' ) ?>"><?php _e( 'Custom Reports', 'adsanity' ) ?></a>
	</h2>
	
	<?php
		if( $is_dashboard_tab ) :
			require_once( ADSANITY_VIEWS.'stats-dashboard.php' );
		else :
			require_once( ADSANITY_VIEWS.'stats-custom.php' );
		endif;
	?>
	
</div>
<div class="clear"></div>