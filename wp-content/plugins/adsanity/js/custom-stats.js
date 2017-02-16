jQuery(document).ready(function($) {
	
	var previousPoint = null;
	var custom_stats_xhr = null;
	
    // insert checkboxes 
    var choiceContainer = $('#ad-choices');
    choiceContainer.find('input, label').on('click', update_display );

    function update_display(e) {

    	if( custom_stats_xhr && custom_stats_xhr.readystate != 4 ){
            custom_stats_xhr.abort();
        }
        
    	var ads = [];

		$('#ad-choices').find('input:checked').each(function () {
			ads.push( $(this).val() );
		});

    	custom_stats_xhr = $.post( ajaxurl, {
    		'action': 'custom_stats_selection',
    		'ads' : ads,
    		'start': $('#start_date').val(),
    		'end': $('#end_date').val()
    	}, 'json' )
    		.done(function( data, textStatus, jqXHR ) {
    			var results = JSON.parse( data );

    			$('#adsanity-data').html(results.table).parents('#custom-report-container').show();
    			$('#total-views').html(results.total_views);
    			$('#total-clicks').html(results.total_clicks);
    			$('#total-ctr').html(results.total_ctr);

    			var i = 0;
    			var views = [];
    			var arrViews = $.map(results.chart_data.views, function(el) { return el; });
    			if( arrViews.length ) {
    				arrViews.forEach(function(element) {

						var arrAdViews = $.map(element, function(el) { return el; });
						var view_data = [];
						
						arrAdViews.forEach(function(e) {
							view_data.push([e.timestamp * 1000, e.viewcount]);
						});

    					views.push( { 'label' : arrAdViews[0].title, 'data' : view_data, 'color' : i } );
    					++i;
    				});
    			}

    			var clicks = [];
    			var arrClicks = $.map(results.chart_data.clicks, function(el) { return el; });
    			if( arrClicks.length ) {
    				arrClicks.forEach(function(element) {

						var arrAdClicks = $.map(element, function(el) { return el; });
						var click_data = [];
						
						arrAdClicks.forEach(function(e) {
							click_data.push([e.timestamp * 1000, e.clickcount]);
						});

    					clicks.push( { 'label' : arrAdClicks[0].title, 'data' : click_data, 'color' : i } );
    					++i;
    				});
    			}
    			var options = {
					xaxis: {
						mode: "time",
						timeformat: "%d %b %y"
					},
					series: {
						lines: { show: true },
						points: { show: true }
					},
					grid: {
						hoverable: true
					}
				};
				if ( views.length > 0 ) {
					$('#custom-views-container').show();
					$.plot( $('#custom-views-chart'), views, options );
				} else {
					$('#custom-views-container').hide();
				}

				if ( clicks.length > 0 ) {
					$('#custom-clicks-container').show();
					$.plot( $('#custom-clicks-chart'), clicks, options );
				} else {
					$('#custom-clicks-container').hide();
				}
    		});
    }
	
	$("#custom-views-chart, #custom-clicks-chart").bind("plothover", function (event, pos, item) {
		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;
				$("#tooltip").remove();
				y = item.datapoint[1];
				showTooltip(item.pageX, item.pageY, y+' '+( $(this).attr('id') == 'custom-views-chart' ? 'views ' : 'clicks ')+' for '+item.series.label);
			}
		} else {
			$("#tooltip").remove();
			previousPoint = null;
		}
	});
	

	function showTooltip(x, y, contents) {
		jQuery('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 10,
			left: x + 10,
			border: '1px solid #666',
			padding: '5px',
			'background-color': '#ccc',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
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
			
			update_display();
		}
	});
	
	$('.selectall, .selectnone').click(function() {
		if( $(this).hasClass('selectall') ) {
			choiceContainer.find('input').attr('checked', 'checked');
		} else {
			choiceContainer.find('input').removeAttr('checked');
		}
		update_display();
		return false;
	})

	/* ad filtering */

	var timer;
	var delay = 500;

	$('#ad-search').on( 'focus', function() {
		var input_value = $(this).val();
		if( input_value.toLowerCase() == 'search ads' ) {
			$(this).val( '' );
			$(this).css({color: '#000'});
		}
	});

	$('#ad-search').on( 'blur', function() {
		var input_value = $(this).val();
		if( input_value == '' ) {
			$(this).val( 'search ads' );
			$(this).css({color: '#ccc'});
		}
	});

	$('#ad-search').on( 'keypress', function(event) {
		// check for output
		if( (typeof event.which == "undefined") || ( (typeof event.which == "number" && event.which > 0) && !event.ctrlKey && !event.metaKey && !event.altKey ) ) {
			if( timer) window.clearTimeout( timer );
			timer = window.setTimeout( do_ad_search, delay, $(this) );
		}
	});

	function do_ad_search( input ) {

		// the value of the search
		var input_value = input.val().toLowerCase();
		var context = input.next();

		if( input_value.length == 0 || input_value == '' ) {
			$('label', context).parent('li').show();
		} else {
			$('label', context).parent('li').hide();
			$('label', context).each(function( index, self ) {
				var t = $(this);
				var text = t.text();
				if( text.toLowerCase().indexOf( input_value ) != -1 ) t.parent('li').show();
			});
		}
	}

	$('#export-csv').on('click', function(e) {
		e.preventDefault();
		$('#data-export').submit();
		return false;
	});
});
