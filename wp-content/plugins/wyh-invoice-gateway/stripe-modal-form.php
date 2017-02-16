<div class="row escrow-transfer">
    <div class="large-12 columns escrow-form">
        <form action="" method="POST" id="stripe-payment-form" class="modal">
            <p><?php _e( 'Please click here to get started.', 'appthemes-stripe' ); ?></p>
            <script
                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                data-key="<?php echo esc_attr( $public_key ); ?>"
                data-name="<?php echo esc_attr( $site_name ); ?>"
                data-description="<?php echo esc_attr( $order_description ); ?>"
                data-amount="<?php echo esc_attr( $order_amount ); ?>"
                data-currency="<?php echo esc_attr( $order_currency ); ?>"
                data-email="<?php echo esc_attr( $user_email ); ?>"
                data-zip-code="<?php echo esc_attr( $require_zip ); ?>">
            </script>
            <p><?php _e( 'A window will open with instructions on how to proceed.', 'appthemes-stripe' ); ?></p>
        </form>
    </div>
</div>
