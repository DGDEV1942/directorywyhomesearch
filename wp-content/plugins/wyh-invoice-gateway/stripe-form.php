<div class="row">
    <div class="large-12 columns">
        <form role="form" id="stripe-payment-form" class="custom main" method="post">
            <fieldset>
                <div id="payment-form-error" class="error" style="display: none;">
                  <small class="error"></small>
                </div>

                <?php if ( ! empty( $options['check_name'] ) ) : ?>
                <div class="form-field"><label>
                    <?php _e( 'Cardholder Name', 'appthemes-stripe' ); ?> 
                    <input type="text" class="card-name" data-stripe="name" />
                </label></div>
                <?php endif; ?>

                <div class="form-field"><label>
                    <?php _e( 'Card Number', 'appthemes-stripe' ); ?> 
                    <input type="text" size="20" autocomplete="off" class="card-number" data-stripe="number" placeholder="4111111111111111"/>
                </label></div>

                <div class="form-field"><label>
                    <div class="multi-line-field">
                        <?php _e( 'Expiration (MM/YYYY)', 'appthemes-stripe' ); ?> <br />
                        <input type="text" size="2" maxlength="2" placeholder="05" class="card-expiry-month" data-stripe="exp-month" style="display: inline; clear: none;" />
                        <span> / </span>
                        <input type="text" size="4" maxlength="4" placeholder="2010" class="card-expiry-year" data-stripe="exp-year" style="display: inline; clear: none;" />
                    </div>
                    <div class="multi-line-field">
                        <?php _e( 'CVC', 'appthemes-stripe' ); ?> 
                        <input type="text" size="4" maxlength="4" placeholder="123" autocomplete="off" class="card-cvc" data-stripe="cvc"/>
                    </div>
                </label></div>

                <?php if ( ! empty( $options['check_address'] ) ) : ?>

                    <div class="form-field"><label>
                        <?php _e( 'Billing Address', 'appthemes-stripe' ); ?> <br />
                        <input type="text" class="card-address-line1" placeholder="548 Market St." data-stripe="address_line1" />
                    </label></div>

                    <div class="form-field"><label>
                        <?php _e( 'Address Line 2', 'appthemes-stripe' ); ?> <br />
                        <input type="text" class="card-address-line2" data-stripe="address_line2" />
                    </label></div>

                    <div class="form-field">
                        <label class="multi-line-field">
                            <?php _e( 'City', 'appthemes-stripe' ); ?> <br />
                            <input type="text" class="card-city" placeholder="San Fransisco" data-stripe="address_city" size="50"/>
                        </label>
                        <label class="multi-line-field">
                            <?php _e( 'State', 'appthemes-stripe' ); ?> <br />
                            <input type="text" size="2" class="card-state" placeholder="CA" data-stripe="address_state" size="2"/>
                        </label>
                    </div>

                    <div class="form-field"><label>
                        <?php _e( 'Zip Code', 'appthemes-stripe' ); ?> <br/>
                        <input type="text" size="5" class="card-zip" placeholder="94104" data-stripe="address_zip" />
                    </label></div>

                <?php endif; ?>

                <div class="form-field">
                  <button class="button success" type="submit">
                    <?php _e( 'Submit', 'appthemes-balanaced-payments' ); ?>
                  </button>
                </div>
            </fieldset>
        </form>
    </div>
</div>