<?php

class APP_AuthorizeNet extends APP_Payment_Gateway implements APP_Payment_Processor, APP_Instant_Payment_Processor, APP_Recurring_Payment_Processor {	

	protected $options;

	public function __construct(){
		parent::__construct( 'authorize-net', array(
			'admin' => __( 'Authorize.Net', 'appthemes-authorizenet' ),
			'dropdown' => __( 'Authorize.Net', 'appthemes-authorizenet' )
		));

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	public function enqueue_scripts(){
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'anet-authorize', plugins_url('authorize.css', __FILE__) );
	}

	public function process( $order, array $options ) {

		if ( empty($_SERVER['HTTPS']) && !empty( $options['ssl_redirect'] ) ) {
    		$url = str_replace( 'http://', 'https://', $order->get_return_url() );
    		$this->js_redirect( $url, __( 'You are now being redirected to a secure page.', 'appthemes-authorizenet' ) );
    		exit;
		}

		$is_ok = $this->setup_marketplace( $options );
		if ( ! $is_ok ) {
			return false;
		}

		$customer_id = $this->get_customer_id();
		if ( ! $customer_id ) {
			$status = $this->create_customer();
			if ( true !== $status ) {
				$this->display_config_error( $status->getMessageText() );
				return false;
			}
		}

		$customer = $this->get_customer();

		// Recreate customer if token has been corrupted
		if ( ! $customer->isOk() ) {
			$status = $this->create_customer();
			if ( true !== $status ) {
				$this->display_config_error( $status->getMessageText() );
				return false;
			}
			$customer = $this->get_customer();
		}

		if ( ! $this->has_payment_method( $customer ) ) {
			$url = $order->get_return_url();
			$url = $url = preg_replace('/\?.*/', '', $url);
			$response = $this->api->getHostedProfilePageRequest( $customer->getCustomerProfileId(), array(
				'hostedProfileReturnUrl' => $url,
			) );

			$token = $response->xml->token;
			if ( ! empty( $token ) ) {
				$this->redirect_to_create_form( $response->xml->token );
			} else {
				$this->display_select_form( $customer, $response->getMessageText() );
			}
			
			return;
		} 

		if ( ! isset ( $_REQUEST['an_action'] ) ) {
			$this->display_select_form( $customer );
			return;
		}

		$action = $_REQUEST['an_action'];

		if ( 'add' == $action ) {

			$url = $order->get_return_url();
			$url = $url = preg_replace('/\?.*/', '', $url);
			$response = $this->api->getHostedProfilePageRequest( $customer->getCustomerProfileId(), array(
				'hostedProfileReturnUrl' => $url,
			) );
			$this->redirect_to_create_form( $response->xml->token );
			return;
			
		}

		if ( ! isset ( $_REQUEST['an_card_id'] ) ) {
			$this->display_select_form( $customer );
			return;
		}

		$card_id = intval( $_REQUEST['an_card_id'] );
		$card    = $this->api->getCustomerPaymentProfile( $customer->getCustomerProfileId(), $card_id );
		$status  = $card->xml->messages->resultCode;
		if( 'Ok' != $status ) {
			$this->display_select_form( $customer, __( 'The selected payment method could not be found.', 'appthemes-authorizenet' ) );
			return;
		}

		if ( 'delete' == $action ) {

			$response = $this->api->deleteCustomerPaymentProfile( $customer->getCustomerProfileId(), $card_id );

			$customer = $this->get_customer();
			$this->display_select_form( $customer );
			
			return;

		} else if ( 'select' == $action ) {

			$status = $this->process_charge_api( $order, $customer, $card );
			if ( true === $status ) {
				$order->complete();
				if ( $order->is_recurring() ) {
					// We need access to the data API
					$order = appthemes_get_order( $order->get_id() );
					$order->add_data( 'an-card-id', $card->getPaymentProfileId() );
				}

				$this->js_redirect( $order->get_return_url(), __( 'You are now being redirected.', 'appthemes-authorizenet' ) );
			} else {
				$order->failed();
				$this->display_select_form( $customer, $status->error_message );
			}

		} else {

		}	


	}

	public function process_recurring( $order_receipt, array $options ) {

		$is_ok = $this->setup_marketplace( $options );
		if ( ! $is_ok ) {
			return false;
		}

		// We need access to the data API
		$order = appthemes_get_order( $order_receipt->get_id() );

		$parent_id = $order->get_parent();
		$parent    = appthemes_get_order( $parent_id );
		$card_id   = $parent->get_data( 'an-card-id' );

		$customer = $this->get_customer();
		$card     = $this->api->getCustomerPaymentProfile( $customer->getCustomerProfileId(), $card_id );

		$status = $this->process_charge_api( $order, $customer, $card );
		if ( true == $status ) {
			$order->complete();
			$order->add_data( 'an-card-id', $card->getPaymentProfileId() );
		} else {
			$order->failed();
		}

	}

	public function display_select_form( $customer, $message = '' ) {
		$profiles = $customer->xml->profile->paymentProfiles;
		include dirname( __FILE__ ) .'/select-form.php';
	}

	public function process_charge_api( $order, $customer, $card ) {

		$transaction = new AuthorizeNetTransaction;
		$transaction->amount = $order->get_total();
		$transaction->customerProfileId = $customer->getCustomerProfileId();
		$transaction->customerPaymentProfileId = $card->getPaymentProfileId();

		$response = $this->api->createCustomerProfileTransaction( "AuthCapture", $transaction );
		if ( ! $response->isOk() && 'E00009' == $response->getMessageCode() ) {
			$this->display_config_error( __( 'You must turn off TEST MODE on your Authorize.Net Account to process transactions.', 'appthemes-authorizenet') );
			return false;
		}

		$transactionResponse = $response->getTransactionResponse();
		$transactionId = $transactionResponse->transaction_id;

		if ( $transactionResponse->approved == 1 ) {
			return true;
		} else {
			return $transactionResponse;
		}
	}

	protected function redirect_to_create_form( $request_token ) {

		if ( AUTHORIZENET_SANDBOX ) {
			$redirect_url     = "https://test.authorize.net/profile/addPayment";	
		} else {
			$redirect_url     = "https://secure.authorize.net/profile/addPayment";
		}
		
		$redirect_message = __( 'Redirecting to AuthorizeNet', 'appthemes-authorizenet' );
		$form_values      = array(
			'token' => $request_token
		);

		$this->display_redirect_form( array( 'action' => $redirect_url, 'name' => 'anet' ), $form_values, $redirect_message );
	}

	protected function display_redirect_form( $form_attributes, $values, $message = '' ){

		if( ! is_array( $form_attributes ) )
			trigger_error( 'Form Attributes must an array', E_USER_WARNING );

		if( ! is_array( $values ) )
			trigger_error( 'Form Values must be an array', E_USER_WARNING );

		if( ! is_string( $message ) )
			trigger_error( 'Redirect Message must be a string', E_USER_WARNING );

		$defaults = array(
			'action' => '',
			'name' => $this->identifier(),
			'id' => $this->identifier(),
			'method' => 'POST'
		);
		$form_attributes = wp_parse_args( $form_attributes, $defaults );

		$form = $this->get_form_inputs( $values );
		$form .= html( 'input', array(
			'type' => 'submit',
			'style' => 'display: none;'
		) );

		if ( empty( $message ) )
			$message = __( 'You are now being redirected.', 'appthemes-authorizenet' );

		$form .= html( 'span', array( 'class' => 'redirect-text' ),  $message );

		echo html( 'form', $form_attributes, $form );
		echo html( 'script', array(), 'jQuery(function(){ document.' . $form_attributes['name'] . '.submit(); });' );

	}

	/**
	 * Generates an array of hidden form inputs.
	 * @param  array  $values An associative array of fields
	 * @return array          An array of resulting hidden form inputs
	 */
	protected function get_form_inputs( $values ){

		if( ! is_array( $values ) )
			trigger_error( 'Form values must be an array', E_USER_WARNING );

		$form = '';
		foreach ( $values as $name => $value ){

			$attributes = array(
				'type' => 'hidden',
				'name' => $name,
				'value' => $value
			);

			$form .= html( 'input', $attributes, '' );

		}

		return $form;

	}

	protected function has_payment_method( $customer ) {
		$paymentProfiles = $customer->xml->profile->paymentProfiles;
		if( empty( $paymentProfiles ) ) {
			return false;
		} else {
			return true;
		}
	}

	protected function get_customer( $user_id = 0 ) {

		if( 0 == $user_id ) {
			$customer_id = $this->get_customer_id();
		} else {
			$customer_id = $this->get_customer_id( $user_id );
		}

		return $this->api->getCustomerProfile( $customer_id );

	}

	protected function get_customer_id( $user_id = 0 ) {
		
		if( 0 == $user_id ) {
			$user        = wp_get_current_user();
			$customer_id = get_user_meta( $user->ID, 'authorize-customer-id', true );
		} else {
			$customer_id = get_user_meta( $user_id, 'authorize-customer-id', true );
		}

		return $customer_id;
	}

	protected function create_customer() {

		$user = wp_get_current_user();

		$customer = new AuthorizeNetCustomer;
		$customer->merchantCustomerId = $user->ID . '_' . time();
		$customer->description        = $user->display_name . '(' . $user->user_login . ')';
		$customer->email              = $user->user_email;

		$response    = $this->api->createCustomerProfile( $customer );
		if ( ! $response->isOk() ) {
			return $response;
		}

		$customer_id = $response->getCustomerProfileID();
		update_user_meta( $user->ID, 'authorize-customer-id', $customer_id );
		return true;

	}

	public static function get_request_url(){
		$options = APP_Gateway_Registry::get_gateway_options( 'authorize-net' );
		return !empty( $options['enable_sandbox'] ) ? APP_AuthorizeNet_Fields::TEST_URL : APP_AuthorizeNet_Fields::REQUEST_URL;
	}

	public static function is_sandbox(){
		$options = APP_Gateway_Registry::get_gateway_options( 'authorize-net' );
		return !empty( $options['enable_sandbox'] );
	}

	public function form(){
	
		return array(
			array(
				'title' => __( 'Authorize.Net', 'appthemes-authorizenet' ),
				'fields' => array(  
					array(
						'title' => __( 'API Login ID', 'appthemes-authorizenet' ),
		                'name'   => 'api_login_id',
		                'type' => 'text',
		            ),
		            array(  
		            	'title' => __( 'Transaction Key', 'appthemes-authorizenet' ),
		                'name' => 'transaction_key',
		                'type' => 'text',
		            ),
		            array(
		            	'title' => __( 'Sandbox Mode', 'appthemes-authorizenet' ),
		                'name' => 'enable_sandbox',
		                'type' => 'checkbox',
		                'desc' => __( 'Enable', 'appthemes-authorizenet' )
		            ),
		           ),
			),
			array(
				'title' => __( 'Payment Form', 'appthemes-authorizenet'),
				'fields' => array(
                    array(
						'title' => __( 'Secure Connection', 'appthemes-authorizenet' ),
						'type' => 'checkbox',
						'desc' => __( 'Force SSL Page', 'appthemes-authorizenet' ),
						'name' => 'ssl_redirect',
					),
				)
			)
		);
	
	}

	private function setup_marketplace( $options ) {

		if ( empty( $options['api_login_id'] ) ) {
			$this->display_config_error( 'Invalid Login ID Given.' );
			return false;
		}

		if ( empty( $options['transaction_key'] ) ) {
			$this->display_config_error( 'Invalid Transaction Key Given.' );
			return false;
		}

		$sandbox = false;
		if ( ! empty( $options['enable_sandbox'] ) ) {
			$sandbox = true;
		}

		define( "AUTHORIZENET_API_LOGIN_ID", $options['api_login_id'] );
		define( "AUTHORIZENET_TRANSACTION_KEY", $options['transaction_key'] );
		define( "AUTHORIZENET_SANDBOX", $sandbox );

		$this->api = new AuthorizeNetCIM;

		return true;
	}

	// @todo display general message for non-admin users
	private function display_config_error( $error_message ) {

		$config_message = __( 'Gateway Configuration Error: %s', 'appthemes-authorizenet' );
		printf( $config_message, $error_message );

	}

	/**
	 * Redirects a user via javascript
	 * @param  string $url  URL to redirect to
	 * @param  string $text Message to display to user
	 * @return void
	 */
	protected function js_redirect( $url, $text ){

		$attributes = array(
			'class' => 'redirect-text'
		);

		echo html( 'span', $attributes, $text );
		echo html( 'script', array(), 'jQuery(function(){ location.href="' . $url . '" });' );

	}

}

?>
