<?php

	$post_type = ( isset( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : '' );
	$page = ( isset( $_REQUEST['page'] ) ? $_REQUEST['page'] : '' );
	$paged = ( isset( $_REQUEST['paged'] ) ? $_REQUEST['paged'] : '' );

	require_once( ADSANITY_LIB.'class-tracking-filter-list-table.php' );

	//Create an instance of our package class...
	$filters_table = new AdSanity_Filters_List_Table();

	//Fetch, prepare, sort, and filter our data...
	$filters_table->prepare_items();
?>
<div class="wrap">
	<?php screen_icon() ?>
	<h2><?php _e( 'Tracking Filters', 'adsanity' ) ?></h2>

	<?php if( isset( $_REQUEST['action'] ) && ( isset( $_REQUEST['agent'] ) || isset( $_REQUEST['adsanity_user_agent'] ) ) ) : $messages = array(); ?>
	<div id="message" class="updated"><p>
	<?php
		// Single blacklist add
		if( $_REQUEST['action'] == 'blacklist' && !isset( $_REQUEST['adsanity_user_agent'] ) ) :
			$messages[] = __( "The selected user agent was blacklisted and will no longer be tracked.", "adsanity" );

		// Bulk blacklist add
		elseif( $_REQUEST['action'] == 'blacklist' && isset( $_REQUEST['adsanity_user_agent'] ) ) :
			$messages[] = __( "The selected user agents were blacklisted and will no longer be tracked.", "adsanity" );

		// Single whitelist add
		elseif( $_REQUEST['action'] == 'whitelist' && !isset( $_REQUEST['adsanity_user_agent'] ) ) :
			$messages[] = __( "The selected user agent will now be tracked.", "adsanity" );

		// Bulk whitelist add
		elseif( $_REQUEST['action'] == 'whitelist' && isset( $_REQUEST['adsanity_user_agent'] ) ) :
			$messages[] = __( "The selected user agents will now be tracked.", "adsanity" );

		endif;
		unset( $_REQUEST['action'] );
		unset( $_REQUEST['agent'] );

		if ( $messages )
			echo join( ' ', $messages );
		unset( $messages );

		$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'action', 'agent' ), $_SERVER['REQUEST_URI'] );
	?>
	</p></div>
	<?php endif; ?>

	<?php $filters_table->views() ?>

	<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
	<form id="adsanity-tracking-filter" method="post" action="<?php echo admin_url( 'edit.php?post_type=ads&page=adsanity-tracking-filters' ); ?>">

		<!-- For plugins, we also need to ensure that the form posts back to our current page -->
		<input type="hidden" name="post_type" value="<?php echo esc_html( $post_type ) ?>" />
		<input type="hidden" name="page" value="<?php echo esc_html( $page ) ?>" />
		<input type="hidden" name="paged" value="<?php echo esc_html( $paged ) ?>" />

		<!-- Now we can render the completed list table -->
		<?php $filters_table->display() ?>
	</form>

</div>
