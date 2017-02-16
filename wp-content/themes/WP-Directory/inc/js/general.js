(function($) {
	$( '#accept-toc' ).change( function() {
		if( $( this ).is( ':checked' ) ) {
			$( '.form-field input[type="submit"]' ).prop( 'disabled', false );
		} else {
			$( '.form-field input[type="submit"]' ).prop( 'disabled', true );
		}
	})
})( jQuery );