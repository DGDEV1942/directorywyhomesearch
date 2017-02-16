jQuery(document).ready(function($) {
	
	/*
	 * HACK to highlight expired/expiring ads.
	 * When WP3.3 drops, we'll be using
	 * a new filter for this instead
	/**/
	$(adsanity.expired_ads).addClass('expired');
	$(adsanity.expiring_ads).addClass('expiring');
	
});