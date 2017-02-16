<?php


/*
 * Payment Processing Interfaces
 * No declarations necessary because we are not 
 * using the methods.
 */
if ( ! interface_exists( 'APP_Payment_Processor') ) {
	interface APP_Payment_Processor { }
	interface APP_Instant_Payment_Processor { }
	interface APP_Recurring_Payment_Processor { }
	interface APP_Escrow_Payment_Processor { }
}

/*
 * Basic APP_Payment_Gateway Class
 */

if ( class_exists( 'APP_Payment_Gateway' ) ) {
	return;
}

abstract class APP_Payment_Gateway implements APP_Payment_Processor, APP_Instant_Payment_Processor {

	/**
 	 * Unique identifier for this gateway
	 * @var string
	 */
	private $identifier;

	/**
	 * Display names used for this Gateway
	 * @var array
	 */
	private $display;

	/**
	 * Creates the Gateway class with the required information to display it
	 *
	 * @param string  $display_name The display name
	 * @param string  $identifier   The unique indentifier used to indentify your payment type
	 */
	public function __construct( $identifier, $args = array() ) {

		if( ! is_string( $identifier ) )
			trigger_error( 'Identifier must be a string', E_USER_WARNING );

		if( ! is_array( $args ) && ! is_string( $args ) )
			trigger_error( 'Arguments must be an array or url encoded string.', E_USER_WARNING );

		$defaults = array(
			'dropdown' => $identifier,
			'admin' => $identifier,
		);

		$args = wp_parse_args( $args, $defaults );

		$this->display = array(
			'dropdown' => $args['dropdown'],
			'admin' => $args['admin'],
		);

		$this->identifier = $identifier;
	}

	/**	
	 * Returns an array representing the form to output for admin configuration		
	 * @return array scbForms style form array		
	 */		
	public abstract function form();

	/**
	 * Processes an order payment
	 * @param  APP_Order $order   The order to be processed
	 * @param  array $options 	  An array of user-entered options
	 *   							corresponding to the values provided in form()
	 * @return void
	 */
	public abstract function process( $order, array $options );

	/**
	 * Provides the display name for this Gateway
	 *
	 * @return string
	 */
	public final function display_name( $type = 'dropdown' ) {

		if( in_array( $type, array( 'dropdown', 'admin' ) ) )
			return $this->display[$type];
		else
			return $this->display['dropdown'];
	}

	/**
	 * Provides the unique identifier for this Gateway
	 *
	 * @return string
	 */
	public final function identifier() {
		return $this->identifier;
	}

	/**
	 * Returns if the current gateway is able to process
	 * recurring payments
	 * @return bool
	 */
	public function is_recurring(){
		return $this->supports( 'recurring' );
	}

	/**
	 * Returns if the current gateway is able to process
	 * escrow payments
	 * @return bool
	 */
	public function is_escrow(){
		return $this->supports( 'escrow' );
	}

	/**
	 * Checks if the current gateway supports a specific service
	 * @return bool
	 */
	public function supports( $service = 'instant' ){
		switch ( $service ) {
			case 'instant':
				return ( $this instanceof APP_Instant_Payment_Processor );
				break;
			case 'recurring':
				return ( $this instanceof APP_Recurring_Payment_Processor );
				break;
			case 'escrow':
				return ( $this instanceof APP_Escrow_Payment_Processor );
				break;
			default:
				return false;
				break;
		}
	}

}
