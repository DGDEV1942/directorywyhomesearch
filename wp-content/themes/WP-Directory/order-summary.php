<div id="main">

<?php do_action( 'appthemes_notices' ); ?>

<div class="section-head">
	<h1><?php _e( 'Order Summary', APP_TD ); ?></h1>
</div>

<div class="order-summary">
	<?php the_order_summary(); ?>

	<p><?php _e( 'Your order has been completed.', APP_TD ); ?></p>
	
	<?php
		$post_id = _va_get_order_post_id( get_order() );

		$post_type_obj = get_post_type_object( get_post( $post_id )->post_type );

		$url = get_permalink( $post_id );

		// See if this order has items which have multiple locations
		$order = get_order();
		$order_items = $order->get_items();
		$has_multiple_listings = false;

		foreach( ( array ) $order_items as $item ) {
			// The "type" is the slug for the plan
			$item_name = $item[ 'type' ];

			// Find the plan with the slug that matches that name
			$plan = get_page_by_slug( $item_name, OBJECT, 'pricing-plan' );
			
			// Does this plan have the multiple_listings_bool set?
			$multiple_listings_bool = get_post_meta( $plan->ID, 'multiple_listings_bool', true );

			if( $multiple_listings_bool === true || $multiple_listings_bool === 1 || $multiple_listings_bool === '1' ) {
				$has_multiple_listings = true;
			}
			
		}

		if( $has_multiple_listings ) {
			update_user_meta( get_current_user_id(), 'user_has_multiple_listings', true );
			$multiple_listings_page_url = get_multiple_listing_page_url();

			if( $multiple_listings_page_url ) {
		?>
			<a href="<?php echo $multiple_listings_page_url; ?>" class="multiple-listings-button">Submit Additional Listing Information</a>	
		<? } // Endif $multiple_listings_page_url
		} // Endif $has_multiple_listings
		?>

	<input type="submit" value="<?php printf( __('Continue to %s', APP_TD ), $post_type_obj->labels->singular_name ); ?>" onClick="location.href='<?php echo $url; ?>';return false;">
</div>

</div>
