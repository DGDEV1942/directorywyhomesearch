jQuery(document).ready(function($) {
	
	var timer;
	var delay = 500;
	
	$('.adsanity-single-ad-search').live( 'focus', function() {
		var input_value = $(this).val();
		if( input_value.toLowerCase() == 'search ads' ) {
			$(this).val( '' );
			$(this).css({color: '#000'});
		}
	});
	
	$('.adsanity-single-ad-search').live( 'blur', function() {
		var input_value = $(this).val();
		if( input_value == '' ) {
			$(this).val( 'search ads' );
			$(this).css({color: '#ccc'});
		}
	});
	
	$('.adsanity-single-ad-search').live( 'keypress', function(event) {
		// check for output
		if( (typeof event.which == "undefined") || ( (typeof event.which == "number" && event.which > 0) && !event.ctrlKey && !event.metaKey && !event.altKey ) ) {
			if( timer) window.clearTimeout( timer );
			timer = window.setTimeout( do_ad_search, delay, $(this) );
		}
	});
	
	function do_ad_search( input ) {
		
		// the value of the search
		var input_value = input.val();
		var context = input.next();
		
		if( input_value.length == 0 || input_value == '' ) {
			$('label', context).fadeIn();
		} else {
			$('label', context).fadeOut();
			$('label', context).each(function( index, self ) {
				var text = $(this).text();
				if( text.toLowerCase().indexOf( input_value.toLowerCase() ) != -1 ) $(this).fadeIn();
			});
		}
	}
});