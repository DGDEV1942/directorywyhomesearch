<?php
class APP_Wyh_Invoice_Gateway extends APP_Payment_Gateway implements APP_Payment_Processor, APP_Instant_Payment_Processor {

	/*
	 * Setting 'recurring' to FALSE will keep this from displaying in the drop-down
	 * menu for Monthly billing.  This is what we want, mmm-kay.  "Invoice Me" should
	 * only appear for Annual billing.
	 */

	public function __construct() {
		parent::__construct( 'wyh_invoice', array(
			'dropdown' => __( 'Invoice Me', 'appthemes-stripe' ),
			'admin'    => __( 'Invoice', 'appthemes-stripe' ),
			'recurring' => FALSE, // Mmm-kay
		) );
	}

	public function process( $order, array $options ) {
		$out = '';
		$wyh_order_total = '';

		// Get the order total to pass as a parameter through the shortcode
		$wyh_order_total = $order->get_total();

		//show GF
		$out .= '<div class="section-head"><h1>Create an Invoice</h1></div>';
		$out .= '<div class="order-summary">';
			$out .= 'Please fill out the form below.  Required fields are marked with a <span class="gfield_required">*</span>.';
			$out .= do_shortcode('[gravityform id='.$options['invoice_form'].' field_values="order_id='.$order->get_id().'&amp;wyh_order_total=' . $wyh_order_total . '" title=false description=false ajax=true]');
		$out .= '</div>';

		echo $out;
	}

	public static function post_save( $conf, $form, $entry ){
        $fields = array();
        foreach ( $form['fields'] as $field ) {
                $fields[$field['inputName']] = $field['id'];
        }

		$order_id = $_REQUEST['input_'.$fields['order_id']];
		update_post_meta($order_id,'entry_id',$entry['id']);
		$order = APP_Order_Factory::retrieve( $order_id );	
		$order->complete();
		return array( 'redirect' => get_permalink($order_id) );	
	}

	public function process_recurring( $order, array $options ) {
		$this->process($order, $options);
	}

	public function form() {
		$opts = array();
		$forms = GFAPI::get_forms();
		foreach($forms as $f)
			$opts[$f['id']]=$f['title'];
							

		$other = array(
			'title' => __( 'Invoice Settings'),
			'fields' => array(
				array(
					'title' => __( 'Form'),
					'type' => 'select',
					'name' => 'invoice_form',
					'values' => $opts
				),
			)
		);

		return array( $other );

	}
	
	/*
	
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
			echo '<pre>'.print_r($customer,true).'</pre>';exit;
			$charge['customer'] = $customer->id;

			// Create Card
			$card = $customer->cards->create( array(
				'card' => $token 
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
	private function js_redirect( $url, $text ){
	
		$attributes = array(
			'class' => 'redirect-text'
		);
	
		echo html( 'span', $attributes, $text );
		echo html( 'script', array(), 'location.href="' . $url . '"' );
	
	}
	 */
	
}

add_action( 'edit_form_advanced', 'wyh_display_order_summary_table' );
function wyh_display_order_summary_table() {
	global $post;

	if ( APPTHEMES_ORDER_PTYPE != $post->post_type )
		return;

	$order = appthemes_get_order( $_GET['post'] );
	$gateway = $order->get_gateway();

	if($gateway != 'wyh_invoice')
		return;

	$entry_id = get_post_meta($_GET['post'],'entry_id',true);
	if(!$entry_id)
		return;

	$entry = GFAPI::get_entry( $entry_id );
	$form = GFAPI::get_form($entry['form_id']);
	$fields = array();
	foreach ( $form['fields'] as $field ) {
			$fields[$field['label']] = $field['id'];
	}

	echo '<table class="widefat">';
	echo '<thead>';
	echo '<tr><th colspan="2">Invoice Information</th></tr>';
	echo '</thead>';
	echo '<tbody>';
	foreach($fields as $k=>$v){
		echo '<tr><td>'.$k.'</td><td>'.$entry[$v].'</td></tr>';
	}
	echo '</tbody>';
	echo '</table>';
}

?>
