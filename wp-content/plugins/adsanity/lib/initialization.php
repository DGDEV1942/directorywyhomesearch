<?php

/**
 * adsanity_initialization
 *
 * Makes sure AdSanity has baseline ad sizes
 *
 * @pkg		AdSanity
 */
class adsanity_initialization {
	function __construct() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
	}
	
	function admin_init() {
		$options = get_option( ADSANITY_ADMIN_OPTIONS, array() );
		if( isset( $options['sizes'] ) )
			return true;

		/*
		 * Standard Ad Sizes as pulled from
		 * http://www.iab.net/iab_products_and_industry_services/1421/1443/1452
		/**/
		$sizes = array(
			'88x31'		=> '88x31 - Micro Bar',
			'120x60'	=> '120x60 - Button 2',
			'120x90'	=> '120x90 - Button 1',
			'120x240'	=> '120x240 - Vertical Banner',
			'120x600'	=> '120x600 - Skyscraper',
			'125x125'	=> '125x125 - Square Button',
			'160x600'	=> '160x600 - Wide Skyscraper',
			'180x150'	=> '180x150 - Rectangle',
			'200x90'	=> '200x90',
			'200x200'	=> '200x200',
			'234x60'	=> '234x60 - Half Banner',
			'240x400'	=> '240x400 - Vertical Rectangle',
			'250x250'	=> '250x250 - Square Pop-Up',
			'300x100'	=> '300x100 - 3:1 Rectangle',
			'300x250'	=> '300x250 - Medium Rectangle',
			'300x600'	=> '300x600 - Half Page Ad',
			'336x280'	=> '336x280 - Large Rectangle',
			'468x15'	=> '468x15',
			'468x60'	=> '468x60 - Full Banner',
			'720x300'	=> '720x300 - Pop-Under',
			'728x90'	=> '728x90 - Leaderboard'
		);
		$options['sizes'] = $sizes;

		update_option( ADSANITY_ADMIN_OPTIONS, $options );
	}
}
new adsanity_initialization;
