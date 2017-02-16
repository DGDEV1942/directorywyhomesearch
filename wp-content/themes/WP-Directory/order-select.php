<div id="main">
	<div class="section-head">
		<h1><?php _e( 'Order Summary', APP_TD ); ?></h1>
	</div>
	<div class="order-summary">
		<?php the_order_summary(); ?>
		<form action="<?php echo appthemes_get_step_url(); ?>" method="POST">
		<?php 
		$full_dropdown = true;
		$all_gateways = array();
		$checkout_recurring = '';
		$recurring = false;
		/*
		 * Admins should see the full dropdown of gateways,
		 * including disabled gateways.  We only want to run
		 * the rest of our conditionals if current_user is
		 * *NOT* an admin.
		 */

		if ( ! current_user_can( 'manage_options' ) ) {

			// Now get an array of all the gateways
			$all_gateways = APP_Gateway_Registry::get_gateways( $args['service'] );

			// Loop through them and unset the disabled ones
			foreach( $all_gateways as $key => $single_gateway ) {
				if ( ! APP_Gateway_registry::is_gateway_enabled( $single_gateway->identifier(), $args['service'] ) ) {
					unset( $all_gateways[ $key ] );
				}
			}

			// Loop again and remove recurring gateways if needed
			$checkout_recurring = appthemes_get_checkout()->get_data( 'recurring' );
			if ( 'recurring' == $checkout_recurring ) {
				$recurring = true;
			}

			if ( $recurring && ! $single_gateway->is_recurring() ) {
				unset( $all_gateways[ $key ] );
			}

			// If there's only one, see if that one is the credit card
			if( count( $all_gateways ) === 1 ) {
				if( isset( $all_gateways[ 'stripe_credit_card' ] ) ){
					$full_dropdown = false;
				}
			}
		}

		if( $full_dropdown ) {
		?>
			<p><?php _e( 'Please select a method for processing your payment:', APP_TD ); ?></p>
			<?php va_list_gateway_dropdown(); ?>
			<input type="submit" value="<?php _e( 'Submit', APP_TD ); ?>">
		<?php } else { ?>
			<p><?php _e( 'You will be able to enter your credit card information on the next screen.', APP_TD ); ?></p>
			<input type="hidden" name="payment_gateway" value="stripe_credit_card">
			<input type="submit" value="<?php _e( 'Submit', APP_TD ); ?>">
		<?php } ?>
		</form>
	</div>
</div>