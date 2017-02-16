<div class="row">
    <div class="large-12 columns">
        <form role="form" id="anet-payment-form" class="custom main" method="post" action="">
            <fieldset>
                <?php if ( ! empty( $message ) ) { ?>
                <div id="payment-form-error" class="error" >
                  <small class="error"><?php echo $message; ?></small>
                </div>
                <?php } ?>

                <p>
                    <?php _e( 'Select one of the saved cards below:', 'appthemes-authorizenet' ); ?>

                <table>
                    <tr>
                        <td><?php _e( 'Select', 'appthemes-authorizenet' ); ?></td>
                        <td><?php _e( 'Card', 'appthemes-authorizenet' ); ?></td>
                        <td><?php _e( 'Delete', 'appthemes-authorizenet' ); ?></td>
                    </tr>
                    <?php 
                    $count = 0;
                    foreach( $profiles as $key => $card ) { 
                        $count++;
                        $cardNumber = $card->payment->creditCard->cardNumber;
                        $expiration = $card->payment->creditCard->expirationDate;
                    ?>
                    <tr>
                        <td><input type="radio" name="an_card_id" value="<?php echo esc_attr( $card->customerPaymentProfileId ); ?>" <?php selected( $count, 1 ); ?>></td>
                        <td><?php printf( __( 'Card ending in %s', 'appthemes-authorizenet' ), $cardNumber ); ?></td>
                        <td><a href="?an_action=delete&amp;an_card_id=<?php echo esc_attr( $card->customerPaymentProfileId ); ?>">X</a></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <td></td>
                        <td colspan="3"><a href="?an_action=add"><?php _e( 'Add new credit card', 'appthemes-authorizenet' ); ?></a></td>
                    </tr>
                </table>
                <div class="form-field">
                    <input type="hidden" name="an_action" value="select" />
                    <button class="button success" type="submit">
                        <?php _e( 'Use this Payment Method', 'appthemes-balanaced-payments' ); ?>
                    </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>

