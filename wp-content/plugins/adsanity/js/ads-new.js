jQuery(document).ready(function($) {
	/*
	 * EDIT SCHEDULE LINK
	 * - show the inputs						(check)
	 * - hide the edit link						(check)
	 * - if we have custom values				(check)
	 *     show the custom values				(check)
	 * - else									(check)
	 *     show a date one year in the future	(check)
	/**/
	$('#is_scheduled').click(function() { // show the scheduling inputs

		// Create a date object from the start date
		var start = new Date( $('#start_date').val() );

		// Create a date object from the start date
		var end = new Date( $('#end_date').val() );

		// Create a date object from the eol date
		var eol = new Date( adsanity.adsanity_eol*1000 );

		// there is no custom value, show a date one year in the future
		if( end.getMonth() == eol.getMonth() && end.getDate() == eol.getDate()+1 && end.getFullYear() == eol.getFullYear() ) {
			
			now_month = start.getMonth()+1;
			// Fix zero base
			if( now_month < 10 )
				now_month = "0"+now_month;
			
			now_day = start.getDate();
			// Fix zero base
			if( now_day < 10 )
				now_day = "0"+now_day;

			// Add a year from the start date
			start.setFullYear( start.getFullYear() + 1 );
		
			// Set the end date to a year from the start date
			if( adsanity.months != "Array" ) {
				$('#end_date').val( adsanity.months[now_month] + ' ' + now_day + ', ' + start.getFullYear() );
			} else {
				$('#end_date').val( adsanity['months_'+now_month] + ' ' + now_day + ', ' + start.getFullYear() );
			}
		}
		
		// hide the edit link
		$(this).addClass( 'hidden' );
		
		// show the inputs
		$('#for_scheduled_only').slideDown();
		return false;

	});
	
	/*
	 * CANCEL SCHEDULE BUTTON
	 * - reset schedule								(check)
	 * - hide the inputs							(check)
	 * - change the text to show no expiration date	(check)
	 * - show the edit link							(check)
	/**/
	$('#cancel_schedule_change').click(function() { // hide the scheduling inputs

		// hide the inputs
		$('#for_scheduled_only').slideUp( '400', function() {
			
			// change the text to show no expiration date
			$('.expires-text').html( adsanity.forever_text );
			
			// show the edit link
			$('#is_scheduled').removeClass( 'hidden' );
			
			// Create a date object from the start date
			var now = new Date( ( adsanity.adsanity_eol ) * 1000 );
		
			now_month = now.getMonth() + 1;
			now_day = now.getDate() + 1;
			now_year = now.getFullYear();
		
			// Set the end date to a year from the start date
			if( adsanity.months != "Array" ) {
				$('#end_date').val( adsanity.months[now_month] + ' ' + now_day + ', ' + now_year );
			} else {
				$('#end_date').val( adsanity['months_'+now_month] + ' ' + now_day + ', ' + now_year );
			}
		});
		
		return false;
	});
	
	/*
	 * ACCEPT SCHEDULE BUTTON
	 * - keep selected schedule						(check)
	 * - hide the inputs							(check)
	 * - change the text to show expiration date	(check)
	 * - show the edit link							(check)
	/**/
	$('#accept_schedule_change').click(function() { // hide the scheduling inputs

		// hide the inputs
		$('#for_scheduled_only').slideUp( '400', function() {
			
			// change the text to show expiration date
			$('.expires-text').html( adsanity.expires_text + '<b>' + $('#end_date').val() + '</b>' );
			
			// show the edit link
			$('#is_scheduled').removeClass( 'hidden' );

		});
		return false;
	});

	// put the tabs at the top
	$('#ad-source-tabs').insertBefore($('form#post')).show();
	
	// tab swapping
	$('.nav-tab').click(function() {
		
		// tab is already active. don't do anything
		if( $(this).hasClass( 'nav-tab-active' ) )
			return false;
		
		$('.nav-tab-active').removeClass( 'nav-tab-active' );
		$(this).addClass( 'nav-tab-active' );
		
		if( $(this).attr( 'href' ) == '#internal' ) {
			$('#ad-code').hide();
			$('#postimagediv').show();
		} else {
			$('#ad-code').show();
			$('#postimagediv').hide();
		}
		
		return false;
	});
	
	// tab initialization
	if( $('.nav-tab-active').attr( 'href' ) == '#internal' ) {
		$('#ad-code').hide();
		$('#postimagediv').show();
	} else {
		$('#ad-code').show();
		$('#postimagediv').hide();
	}
	
	var dates = $( '#start_date, #end_date' ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'MM dd, yy',
		onSelect: function( selectedDate ) {
			var option = this.id == "start_date" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
			if( $(this).attr('id') == 'end_date' )
				$('.expires-text').html( adsanity.expires_text + '<b>' + selectedDate + '</b>' );
		}
	});
	
});
