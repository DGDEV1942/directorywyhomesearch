<?php

class APP_Stripe_Credit_Card_Gateway extends APP_Payment_Gateway implements APP_Payment_Processor, APP_Instant_Payment_Processor, APP_Recurring_Payment_Processor {

	public function __construct() {
		parent::__construct( 'stripe_credit_card', array(
			'dropdown' => __( 'Credit Card', 'appthemes-stripe' ),
			'admin'    => __( 'Stripe', 'appthemes-stripe' ),
			'recurring' => true,
		) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts') );
	}

	public function register_scripts(){

		if ( is_singular( APPTHEMES_ORDER_PTYPE ) ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( "stripe", plugins_url('style.css', __FILE__)  );
			wp_enqueue_script( 'stripe_js', 'https://js.stripe.com/v2/' );
			wp_enqueue_script( 'stripe_form_handler', plugin_dir_url( __FILE__ ) . 'form.js' );
			wp_localize_script( 'stripe_form_handler', 'translate', array(
				'invalid_number'        => __('This card number is invalid.', 'appthemes-stripe' ),
				'incorrect_number'      => __('This card number is incorrect.', 'appthemes-stripe' ),
				'invalid_expiry_month'  => __('This card\'s expiration month is invalid.', 'appthemes-stripe' ),
				'invalid_expiry_year'   => __('This card\'s expiration year is invalid.', 'appthemes-stripe' ),
				'invalid_cvc'           => __('This card\'s security code is invalid.', 'appthemes-stripe' ),
				'incorrect_cvc'         => __('This card\'s security code is incorrect.', 'appthemes-stripe' ),
				'expired_card'          => __('This card has expired.', 'appthemes-stripe' ),
				'card_declined'         => __('This card has been declined.', 'appthemes-stripe' ),
				'invalid_amount'        => __('There has been a server error. (invalid_amount)', 'appthemes-stripe' ),
				'missing'               => __('There has been a server error. (missing)', 'appthemes-stripe' ),
				'duplicate_transaction' => __('There has been a server error. (duplicate_transaction).', 'appthemes-stripe' ),
				'processing_error'      => __('There has been a server error. (processing_error)', 'appthemes-stripe' ),
				'exp_month'             => __('The expiration month you have entered is invalid.', 'appthemes-stripe' ),
				'exp_year'              => __('The expiration year you have entered is invalid.', 'appthemes-stripe' ),
				'number'                => __('The card number you have entered is invalid.', 'appthemes-stripe' ),
				'card_name'             => __( 'You must enter a cardholder name.', 'appthemes-stripe' ),
				'card_address'          => __( 'You must enter a billing address.', 'appthemes-stripe' ),
				'card_state'            => __( 'You must enter a billing state.', 'appthemes-stripe' ),
				'card_city'             => __( 'You must enter a billing city.', 'appthemes-stripe' ),
				'card_zip'              => __( 'You must enter a billing zip code.', 'appthemes-stripe' ),
			));
		}

	}

	public function process( $order, array $options ) {

		if ( empty($_SERVER['HTTPS']) && !empty( $options['ssl_redirect'] ) ) {
    		$url = str_replace( 'http://', 'https://', $order->get_return_url() );
    		$this->js_redirect( $url, __( 'You are now being redirected to a secure page.', 'appthemes-stripe' ) );
    		exit;
		}
	
		Stripe::setApiKey( $this->get_secret_key( $options ) );
	
		if ( $this->is_handlable() ) {
			$this->process_token( $_POST['stripeToken'], $order, $options );
		} else {
			$this->display_html_form( $order, $options );
		}

	}

	public function process_recurring( $order, array $options ) {

		Stripe::setApiKey( $this->get_secret_key( $options ) );

		try {
			$customer = $this->get_customer( $order->get_author() );

			$cents_per_dollar = 100;
			$charge = array(
				'amount'      => $order->get_total() * $cents_per_dollar,
				'customer'    => $customer->id,
				'currency'    => $order->get_currency(),
				'description' => $order->get_description(),
			);

			$response = Stripe_Charge::create( $charge );	
			$order->complete();	

		} catch ( Stripe_Error $error ) {

			$response = $error->getJsonBody();
			if ( isset( $response['error']['param'] ) && 'currency' === $response['error']['param'] ) {
				$error_message = sprintf( __( 'Stripe cannot process orders in the given currency \'%s\'', 'appthemes-stripe' ), $order->get_currency() );
			} else {
				$error_message = __('Your order could not be completed. %s', 'appthemes-stripe' );
				$error_message = sprintf( $error_message, $response['error']['message'] );
			}

			//$order->add_log( $error_message );
			
		}

	}
	
	private function process_token( $token, $order, $options ){

		if ( !empty( $options['check_cvc'] ) ) {
			add_filter( 'appthemes_stripe_charge_response', array( $this, 'validate_cvc' ), 10, 2);	
		}

		if ( !empty( $options['check_name'] ) && empty( $options['use_modal'] ) ) {
			add_filter( 'appthemes_stripe_charge_response', array( $this, 'validate_name' ), 10, 2);	
		}
		
		if ( !empty( $options['check_address'] ) ) {

			if ( empty( $options['use_modal'] ) ) {
				add_filter( 'appthemes_stripe_charge_response', array( $this, 'validate_address' ), 10, 2);
			}
			add_filter( 'appthemes_stripe_charge_response', array( $this, 'validate_address_zip' ), 10, 2);	
		}

		$cents_per_dollar = 100;
		$charge = array(
			'amount'      => $order->get_total() * $cents_per_dollar,
			'currency'    => $order->get_currency(),
			'description' => $order->get_description(),
		);
		
		try {

			// Create/Retrieve Customer
			$customer = $this->get_customer( $order->get_author() );
			$charge['customer'] = $customer->id;

			// Create Card
			$card = $customer->sources->create( array(
				'source' => $token 
			) );

			// Validate Card
			$card_status = apply_filters( 'appthemes_stripe_charge_response', true, $card );
			if ( true === $card_status ) {
				$response = Stripe_Charge::create( $charge );	
				$order->complete();	

			} else {
				$message = __( 'Your order could not be completed. %s', 'appthemes-stripe' );
				$error   = is_string( $card_status ) ? $card_status : '';

				$this->fail_order( sprintf( $message, $error ) );
				$this->display_html_form( $order, $options );
			}


		} catch ( Stripe_Error $error ) {

			$response = $error->getJsonBody();
			if ( isset( $response['error']['param'] ) && 'currency' === $response['error']['param'] ) {
				$error_message = sprintf( __( 'Stripe cannot process orders in the given currency \'%s\'', 'appthemes-stripe' ), $order->get_currency() );
			} else {
				$error_message = __('Your order could not be completed. %s', 'appthemes-stripe' );
				$error_message = sprintf( $error_message, $response['error']['message'] );
			}

			$this->fail_order( $error_message );
			$this->display_html_form( $order, $options );
			
		}

	}

	private function get_customer( $user_id = 0 ) {
		
		if( 0 == $user_id ) {
			$user        = wp_get_current_user();
			$customer_id = get_user_meta( $user->ID, 'stripe-customer-id', true );
		} else {
			$user        = get_user_by( 'id', $user_id );
			$customer_id = get_user_meta( $user_id, 'stripe-customer-id', true );
		}
		

		if( empty( $customer_id ) ) {
			$customer_id = $this->create_customer( $user );
		}

		$customer = Stripe_Customer::retrieve( $customer_id );
		return $customer;

	}

	private function create_customer( $user ) {

		$customer = Stripe_Customer::create( array( 
			'description' => $user->display_name . '(' . $user->user_login . ')',
			'email'       => $user->user_email,
			'metadata'    => array(
				'Login'     => $user->user_login,
			)
		) );
		
		update_user_meta( $user->ID, 'stripe-customer-id', $customer->id );
		return $customer->id;

	}

	public function validate_cvc( $status, $response ) {
		if ( 'pass' !== $response->cvc_check ) {
			return __( 'The CVC code given could not be validated.', 'appthemes-stripe' );
		}
		return $status;
	}
	
	public function validate_address( $status, $response ) {
		if ( 'pass' !== $response->address_line1_check ) {
			return __( 'The address given could not be validated.', 'appthemes-stripe' );
		}
		return $status;
	}

	public function validate_address_zip( $status, $response ) {
		if ( 'pass' !== $response->address_zip_check ) {
			return __( 'The address zip given could not be validated.', 'appthemes-stripe' );
		}
		return $status;
	}

	public function validate_name( $status, $response ) {
		if ( '' === $response->name ) {
			return __( 'You must provide a cardholder name.', 'appthemes-stripe' );
		}
		return $status;
	}

	private function is_handlable() {
		return isset( $_POST['stripeToken'] );
	}

	private function fail_order( $message ) {
		appthemes_display_notice( 'error', $message );
		return;
	}
	
	public function form() {

		$live = array(
			'title' => __( 'Live Settings', 'appthemes-stripe' ),
			'fields' => array(
				array(
					'title' => __( 'Live Mode', 'appthemes-stripe' ),
					'desc'  => __( 'Enable to start using your Live API Keys', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'name'  => 'livemode_enabled'
				),
				array(
					'title' => __( 'Secret Key', 'appthemes-stripe' ),
					'type'  => 'text',
					'name'  => 'secret_key',
					'extra' => array( 'size' => 50 )
				),
				array(
					'title' => __( 'Publishable Key', 'appthemes-stripe' ),
					'type'  => 'text',
					'name'  => 'publishable_key',
					'extra' => array( 'size' => 50 )
				)
				
			)
		);
		
		$testing = array(
			'title'  => __( 'Testing Settings', 'appthemes-stripe' ),
			'fields' => array(
				array(
					'title' => __( 'Secret Key', 'appthemes-stripe' ),
					'type'  => 'text',
					'name'  => 'test_secret_key',
					'extra' => array( 'size' => 50 )
				),
				array(
					'title' => __( 'Publishable Key', 'appthemes-stripe' ),
					'type'  => 'text',
					'name'  => 'test_publishable_key',
					'extra' => array( 'size' => 50 )
				)
			)
		);

		$other = array(
			'title'  => __( 'Other Settings', 'appthemes-stripe' ),
			'fields' => array(
				array(
					'title' => __( 'Stripe Checkout Form', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Uses the Stripe Checkout Form instead of a self-hosted form.', 'appthemes-stripe' ),
					'name'  => 'use_modal',
				),
				array(
					'title' => __( 'Secure Connection', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Force SSL Page', 'appthemes-stripe' ),
					'name'  => 'ssl_redirect',
				),
				array(
					'title' => __( 'Validate CVC code', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Refuse charges that report an invalid cvc code', 'appthemes-stripe' ),
					'name'  => 'check_cvc',
				),
				array(
					'title' => __( 'Require valid address/zip code', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Adds address and zipcode boxes to payment form. Refuses charges that report and invalid address or zipcode.', 'appthemes-stripe' ),
					'name'  => 'check_address',
				),
				array(
					'title' => __( 'Require cardholder name', 'appthemes-stripe' ),
					'type'  => 'checkbox',
					'desc'  => __( 'Add cardholder name to form. Will not influence if a charge is accepted or denied.', 'appthemes-stripe' ),
					'name'  => 'check_name',
				),
			)
		);

		return array( $testing, $live, $other );

	}
	
	private function display_html_form( $order, $options ){

		$public_key = $this->get_publishable_key( $options );
		
		if ( !empty( $options['use_modal'] ) ) {

			$site_name         = get_bloginfo('site_name');
			$order_amount      = $order->get_total() * 100;
			$order_description = $order->get_description();
			$order_currency    = $order->get_currency();
			$user_email        = wp_get_current_user()->user_email;
			$require_zip       = ( ! empty( $options['check_address'] ) ) ? 'true' : 'false';

			require dirname(__FILE__) . '/stripe-modal-form.php';

		} else {

			require dirname(__FILE__) . '/stripe-form.php';
			?><script type="text/javascript">
		    	Stripe.setPublishableKey('<?php echo $public_key; ?>');
			</script><?php

		}
		
	}
	
	private function get_publishable_key( $options ){
		$key = $options['livemode_enabled'] ? $options['publishable_key'] : $options['test_publishable_key'];
		return trim( $key );
	}
	
	private function get_secret_key( $options ){
		$key = $options['livemode_enabled'] ? $options['secret_key'] : $options['test_secret_key'];
		return trim( $key );
	}
	
	private function js_redirect( $url, $text ){
	
		$attributes = array(
			'class' => 'redirect-text'
		);
	
		echo html( 'span', $attributes, $text );
		echo html( 'script', array(), 'location.href="' . $url . '"' );
	
	}

}

?>
