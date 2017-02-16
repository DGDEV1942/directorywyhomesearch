<?php
/**
 * Google maps class.
 *
 * @package Geo
 *
 * @since 1.0.0
 */

/**
 * Map provider APP_Google_Map_Provider class
 *
 * @since   1.0.0
 */
class APP_Google_Map_Provider extends APP_Map_Provider {

	/**
	 * Constructor
	 */
	public function __construct() {
		parent::__construct( 'google', array(
			'dropdown' => __( 'Google', APP_TD ),
			'admin'    => __( 'Google', APP_TD ),
		) );
	}

	/**
	 * Init
	 */
	public function init() {}

	/**
	 * Check to see if there are required variables.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function has_required_vars() {

		if ( empty( $this->options['geo_region'] ) && empty( $_POST['map_provider_settings']['google']['geo_region'] ) ) {
			return __( 'Region', APP_TD );
		}

		if ( empty( $this->options['geo_language'] ) && empty( $_POST['map_provider_settings']['google']['geo_language'] ) ) {
			return __( 'Language', APP_TD );
		}

		if ( empty( $this->options['geo_unit'] ) && empty( $_POST['map_provider_settings']['google']['geo_unit'] ) ) {
			return __( 'Unit', APP_TD );
		}

		return true;
	}

	/**
	 * Load up the map scripts.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function _enqueue_scripts() {

		$url = '//maps.googleapis.com/maps/api/js';

		$defaults = array(
			'geo_region'   => 'US',
			'geo_language' => 'en',
			'geo_unit'     => 'mi',
			'api_key'      => '',
		);

		$options = wp_parse_args( $this->options, $defaults );

		$args = array(
			'libraries' => 'geometry,places',
			'region'    => strtolower( $options['geo_region'] ),
			'language'  => strtolower( $options['geo_language'] ),
		);

		if ( ! empty( $options['api_key'] ) ) {
			$args['key'] = $options['api_key'];
		}

		wp_enqueue_script( 'google-maps-api', add_query_arg( $args, $url ), array(), null, false );
		wp_localize_script( 'google-maps-api', 'appthemes_map_settings', $this->_map_settings() );
		wp_enqueue_script( 'appthemes-maps', get_template_directory_uri() . '/includes/geo/map-providers/google-maps.js', array(), '20160609', true );
	}

	/**
	 * Default settings for the map.
	 *
	 * These can be overridden via filters. Then call them from your js script.
	 * e.g. styles: appthemes_map_settings.mapOptions.styles
	 *
	 * @since  2.0.0
	 *
	 * @return array
	 */
	public function _map_settings() {

		// Setup global map params to access via js.
		$settings = array(
			'imgPath'    => get_template_directory_uri() . '/assets/images/',
			'mapOptions' => array(
				'backgroundColor' => '#f7f7f7',
				'styles'          => $this->_map_styles(),
			),
		);

		/**
		 * Filters the Google map settings array variables.
		 *
		 * @since 2.0.0
		 *
		 * @param array $settings The settings array for Google maps.
		 */
		return apply_filters( 'appthemes_google_map_settings', $settings );
	}

	/**
	 * Custom styles for the map.
	 *
	 * @since  2.0.0
	 *
	 * @return array
	 */
	public function _map_styles() {

		$styles   = '';
		$filename = 'default.json';

		// Grab the style json file from the schemes folder.
		$scheme = trailingslashit( dirname( __FILE__ ) ) . trailingslashit( 'schemes' ) . $filename;

		/**
		 * Filters the Google map default style file.
		 *
		 * @since 2.0.0
		 *
		 * @param array $scheme The path to the default style file.
		 */
		$scheme = apply_filters( 'appthemes_google_map_styles_file', $scheme );

		if ( file_exists( $scheme ) ) {
			$data = file_get_contents( $scheme );
		}

		// Convert the json file to a php array.
		if ( ! is_null( $data ) && $data ) {
			$styles = json_decode( $data, true );
		}

		/**
		 * Filters the Google map style array variables.
		 *
		 * Tutorial: https://developers.google.com/maps/documentation/javascript/styling#creating_a_styledmaptype
		 *
		 * @since 2.0.0
		 *
		 * @param array $styles The style array for Google maps.
		 */
		return apply_filters( 'appthemes_google_map_styles', $styles );
	}

	/**
	 * Map provider variables.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function _map_provider_vars() {
		$this->map_provider_vars = wp_parse_args( $this->options, array(
			'text_directions_error' => __( 'Could not get directions to the given address. Please make your search more specific.', APP_TD ),
		) );
	}

	/**
	 * Generates the admin option fields.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function form() {

		$general = array(
			'title'  => __( 'General', APP_TD ),
			'fields' => array(
				array(
						'title' => __( 'Region Biasing', APP_TD ),
						'desc'  => sprintf( __( 'Find your two-letter <a href="%s" target="_blank">region code</a>', APP_TD ), 'http://en.wikipedia.org/wiki/ISO_3166-1#Current_codes' ),
						'type'  => 'text',
						'name'  => 'geo_region',
						'tip'   => __( "If you set this to 'IT' and a user enters 'Florence' in the location search field, it will target 'Florence, Italy' rather than 'Florence, Alabama'.", APP_TD ),
						'extra' => array(
							'class' => 'small-text',
						)
					),
					array(
						'title' => __( 'Language', APP_TD ),
						'desc'  => sprintf( __( 'Find your two-letter <a href="%s" target="_blank">language code</a>', APP_TD ), 'http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes' ),
						'type'  => 'text',
						'name'  => 'geo_language',
						'tip'   => __( 'Used to format the address and map controls.', APP_TD ),
						'extra' => array(
							'class' => 'small-text',
						),
					),
					array(
						'title'  => __( 'Distance Unit', APP_TD ),
						'type'   => 'select',
						'name'   => 'geo_unit',
						'values' => array(
							'km' => __( 'Kilometers', APP_TD ),
							'mi' => __( 'Miles', APP_TD ),
						),
						'tip'    => '',
					),
					array(
						'title' => __( 'API Key', APP_TD ),
						'desc'  => sprintf( __( 'Get started using the <a href="%s" target="_blank">Maps API</a>', APP_TD ), 'https://developers.google.com/maps/documentation/javascript/tutorial#api_key' ),
						'type'  => 'text',
						'name'  => 'api_key',
						'tip'   => __( 'Activate your Google Maps JavaScript API Service and paste in the API key here. This field is optional but recommended.', APP_TD ),
					),
			)
		);

		return $general;
	}
}

appthemes_register_map_provider( 'APP_Google_Map_Provider' );
