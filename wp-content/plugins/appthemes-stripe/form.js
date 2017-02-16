var transStripeError = function( error ){
	switch( error.type ){
		case "invalid_request_error":
			return "There was a server error.";
			break;
		case "api_error":
			return "There was a server error.";
			break;
		case "card_error":
			return transCardError( error );
			break;
	}
};

var transCardError = function( error ){

	if( error.code === undefined ){
		return translate[error.param];
	}
	return translate[error.code];

};

var stripeResponseHandler = function(status, response){

	if( status != 200 ){
		var message = transStripeError( response.error );
		stripeError(message);
		
	}else{
		var form = jQuery("#stripe-payment-form");
        form.append("<input type='hidden' name='stripeToken' value='" + response.id + "' />");
        form.get(0).submit();
	}

};

jQuery(document).ready(function($) {
	$("#stripe-payment-form").submit(function(event) {
		event.preventDefault();

	    $('.submit-button').attr("disabled", "disabled");
	    if ( $('#stripe-payment-form .card-name').length && '' == $('#stripe-payment-form .card-name').val() ) {
	    	stripeError( translate["card_name"] );
	    	return false;
	    }

	    if ( $('#stripe-payment-form .card-address-line1').length && '' == $('#stripe-payment-form .card-address-line1').val() ) {
	    	stripeError( translate["card_address"] );
	    	return false;
	    }
	    if ( $('#stripe-payment-form .card-city').length && '' == $('#stripe-payment-form .card-city').val() ) {
	    	stripeError( translate["card_city"] );
	    	return false;
	    }
	    if ( $('#stripe-payment-form .card-state').length && '' == $('#stripe-payment-form .card-state').val() ) {
	    	stripeError( translate["card_state"] );
	    	return false;
	    }

	    if ( $('#stripe-payment-form .card-zip').length && '' == $('#stripe-payment-form .card-zip').val() ) {
	    	stripeError( translate["card_zip"] );
	    	return false;
	    }

	    Stripe.createToken( $("#stripe-payment-form"), stripeResponseHandler );
	    return false;
	});
});

function stripeError( message ) {

	var errorBox = jQuery( "#stripeError" );
	if( errorBox.length === 0 ){
		errorBox = jQuery( "<div>", {
			"class" : "notice error",
			"html" : jQuery( "<span>", {
				"id" : "stripeError",
				"text" : message
			})
		});

		jQuery( "#stripe-payment-form" ).prepend( errorBox );
	}else{
		jQuery( errorBox ).text( message );
	}

	jQuery('.submit-button').removeAttr("disabled");
}