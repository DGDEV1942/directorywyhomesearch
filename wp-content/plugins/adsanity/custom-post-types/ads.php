<?php

/**
 * ads_cpt
 * This class extends the base CPT base class with specific customizations for ads
 *
 * @package adsanity
 * @extends pj_cpt_base
 */
class ads_cpt extends pj_cpt_base {

	var $version = '0.3';
	var $type = 'ads';
	var $singular = 'Ad';
	var $plural = 'Ads';
	var $metafields;

	/**
	 * Kicks off all the hooks required to make this class run
	 */
	function __construct() {
		global $wp_version;

		$this->metafields = array(// embed, str, float, int, date, file, path
			'target'		=> array( '_target',			'bool' ),
			'url'			=> array( '_url',				'url' ),
			'notes'			=> array( '_notes',				'str' ),
			'size'			=> array( '_size',				'str' ),
			'code'			=> array( '_code',				'raw' ),
			'start_date'	=> array( '_start_date',		'date' ),
			'end_date'		=> array( '_end_date',			'date' ),
		);

		add_action( 'after_setup_theme',				array( $this, 'after_setup_theme' ) );
		add_filter( $this->type.'_setup',				array( $this, 'setup' ) );
		add_action( $this->type.'_init',				array( $this, 'taxonomies' ) );
		add_filter( 'enter_title_here',					array( $this, 'enter_title_here' ) );
		add_action( 'edit_form_after_title',			array( $this, 'edit_form_advanced' ) );
		add_action( 'edit_form_advanced',				array( $this, 'edit_form_advanced' ) );
		add_action( 'admin_menu',						array( $this, 'remove_meta_boxes' ) );
		add_action( 'add_meta_boxes_'.$this->type,		array( $this, 'add_meta_boxes' ), 1 );
		add_action( 'template_include',					array( $this, 'template_include' ), 1000 );
		add_filter( 'manage_ads_posts_columns',			array( $this, 'columns' ) );
		add_filter( 'manage_ads_posts_custom_column',	array( $this, 'column_values' ), 10, 2 );
		add_filter( 'manage_edit-ads_sortable_columns',	array( $this, 'sortable_columns' ) );
		add_filter( 'manage_edit-ad-group_columns',		array( $this, 'taxonomy_columns' ) );
		add_filter( 'manage_ad-group_custom_column',	array( $this, 'taxonomy_column_values' ), 10, 3);
		add_filter( 'request',							array( $this, 'request' ) );
		add_filter( 'edit_posts_per_page',				array( $this, 'edit_posts_per_page' ), 10, 2 );

		add_filter( 'admin_post_thumbnail_html',		array( $this, 'admin_post_thumbnail_html' ) );

		if( version_compare( $wp_version, '3.8' ) >= 0 )
			add_action( 'admin_enqueue_scripts',			array( $this, 'global_styles' ) );

		// Main Post list screen
		add_action( 'admin_enqueue_scripts',			array( $this, 'ad_list_scripts' ) );
		// Main Post list screen on WordPress 3.3

		// New Ad screen
		add_action( 'admin_enqueue_scripts', 			array( $this, 'ad_new_scripts' ) );

		// Edit Ad screen
		add_action( 'admin_enqueue_scripts',			array( $this, 'ad_edit_scripts' ) );

		add_action( 'post_submitbox_start',				array( $this, 'post_submitbox_start' ) );

		// for wp33 expired/expiring row highlighting
		if( is_admin() ) add_filter( 'post_class',		array( $this, 'post_class' ) );

		add_action( $this->type.'_init',				array( $this, 'flush_permalinks' ) );
		parent::__construct();
	}

	function after_setup_theme() {
		if( ! current_theme_supports( 'post-thumbnails' ) ) :
	/**
	 * Checks for post-thumbnail support in the theme and enables it it it's not available
	 */
			add_theme_support( 'post-thumbnails' );
		endif;
	}

	function setup( $setup = array() ) {
	/**
	 * Customizes the custom post type values for ads
	 * @param  array  $setup the default values for the base custom post type
	 * @return array         customized values for the adsanity custom post type
	 */
		$setup['has_archive'] = false;
		$setup['rewrite'] = array( 'with_front' => false, 'slug' => 'ads' );
		$setup['supports'] = array( 'title', 'thumbnail' );
		$setup['exclude_from_search'] = true;
		$setup['show_in_nav_menus'] = false;
		$setup['show_in_admin_bar'] = true;
		$setup['labels']['menu_name'] = __( 'AdSanity' );
		$setup['labels']['all_items'] = __( 'Manage Ads' );
		$setup['labels']['add_new'] = __( 'Create Ad' );
		$setup['labels']['add_new_item'] = __( 'Create Ad' );
		$setup['labels']['new_item'] = __( 'Create Ad' );
		return $setup;
	}
	function taxonomies() {
	/**
	 * Hooks into the base class to add a taxonomy for ad groups
	 * @uses pj_cpt_base::init
	 */
		$labels = array(
			'name' => _x( 'Ad Groups', 'taxonomy general name', 'adsanity' ),
			'singular_name' => _x( 'Group', 'taxonomy singular name', 'adsanity' ),
			'search_items' =>  __( 'Search Ad Groups', 'adsanity' ),
			'all_items' => __( 'All Groups', 'adsanity' ),
			'parent_item' => __( 'Parent Group', 'adsanity' ),
			'parent_item_colon' => __( 'Parent Group:', 'adsanity' ),
			'edit_item' => __( 'Edit Group', 'adsanity' ),
			'update_item' => __( 'Update Group', 'adsanity' ),
			'add_new_item' => __( 'Add New Group', 'adsanity' ),
			'new_item_name' => __( 'New Group Name', 'adsanity' ),
			'menu_name' => __( 'Ad Groups', 'adsanity' ),
		);

		register_taxonomy(
			'ad-group',
			array( 'ads' ),
			array(
				'hierarchical' => true,
				'labels' => $labels,
				'show_ui' => true,
				'query_var' => true,
				'rewrite' => array( 'with_front' => false, 'slug' => 'ad-group' ),
			)
		);
	}
	function taxonomy_columns( $columns = array() ) {
	/**
	 * Adds the taxonomy ID in the ad group list
	 * @param  array  $columns An associative array of slugs and names for the taxonomy table
	 * @return array           the modified array of columns for the taxonomy table
	 */
		$checkbox = array_slice( $columns, 0, 1, true );
		$group_id = array( 'id' => __( 'ID' ) );
		$everything_else = array_slice( $columns, 1, count( $columns ) - 1, true );
		$columns = array_merge( $checkbox, $group_id, $everything_else );
		return $columns;
	}
	function taxonomy_column_values( $output, $column_name, $term_id ) {
	/**
	 * Outputs the values of each column for each row in the taxonomy list
	 * @param  string $output      the output for the column value
	 * @param  string $column_name the slug of the column
	 * @param  int $term_id        the term_id of the row
	 * @return string              the final output for the column value
	 */
		if ( $column_name == 'id' ) {
            $output = $term_id;
        }
	    return $output;
	}

	function flush_permalinks() {
	/**
	 * During the activation phase, we set an option in the database to let us know that we need to
	 * flush permalinks to pick up the new custom post type permalink structure
	 */
		$adsanity_options = get_option( ADSANITY_ADMIN_OPTIONS, array() );
		if( isset( $adsanity_options['update-permalinks'] ) && $adsanity_options['update-permalinks'] == 1 ) :
			/*
			 * Flush all rewrite rules
			/**/
			global $wp_rewrite;
			$wp_rewrite->flush_rules();

			unset( $adsanity_options['update-permalinks'] );
			update_option( ADSANITY_ADMIN_OPTIONS, $adsanity_options );
		endif;
	}
	function columns( $columns = array() ) {
	/**
	 * Sets up the columns that show in the ad post list
	 * @param  array  $columns An associative array of slugs and names for the posts table
	 * @return array           the modified array of columns for the posts table
	 */
		$columns = array(
			'cb'		=> '<input type="checkbox" />',
			'id'		=> __( 'ID', 'adsanity' ),
			'title'		=> __( 'Ad Title', 'adsanity' ),
			'size'		=> __( 'Dimensions', 'adsanity' ),
			'stats'		=> __( "Today's Stats", 'adsanity' ),
			'start'		=> __( 'Display From', 'adsanity' ),
			'expires'	=> __( 'Until', 'adsanity' ),
		);
		return apply_filters( 'adsanity_ads_posts_columns', $columns );
	}
	function sortable_columns() {
	/**
	 * a definition of columns that are deemed sortable
	 * @param  array the default columns that are sortable
	 * @return array the modified array of columns that are sortable
	 */
		$columns = array(
			'id'		=> 'id',
			'title'		=> 'title',
			'size'		=> 'size',
			'start'		=> 'start_date',
			'expires'	=> 'end_date',
		);
		return apply_filters( 'adsanity_ads_sortable_posts_columns', $columns );
	}
	function column_values( $column = '', $post_id = 1 ) {

		if( 'id' == $column ) :
	/**
	 * Outputs the values of each column for each row in the post list
	 * @param  string $column_name the slug of the column
	 * @param  int $post_id        the post_id of the row
	 */
		// Output the post ID
			$value = $post_id;
		elseif( 'size' == $column ) :
			$options = get_option( ADSANITY_ADMIN_OPTIONS, array( 'sizes' => array() ) );
			$sizes = apply_filters( 'adsanity_ad_sizes', $options['sizes'] );
			$size = get_post_meta( $post_id, '_size', true );
			$value = ( !$size ? __( '- not set -', 'adsanity' ) : $sizes[$size] );
		elseif( 'stats' == $column ) :
			global $wp_locale;

			$now = mktime( 0,0,0, date("n"), date("j"), date("Y") );
			$view_key = '_views-'.$now;
			$views = get_post_meta( $post_id, $view_key, true );
			$views = ( !$views ? 0 : $views );
			$views = number_format(
				$views,
				0,
				$wp_locale->number_format['decimal_point'],
				$wp_locale->number_format['thousands_sep']
			);

			$click_key = '_clicks-'.$now;
			$clicks = get_post_meta( $post_id, $click_key, true );
			$clicks = ( !$clicks ? 0 : $clicks );
			$clicks = number_format(
				$clicks,
				0,
				$wp_locale->number_format['decimal_point'],
				$wp_locale->number_format['thousands_sep']
			);

			$value = $clicks. ' clicks / '.$views.' views / ';
			$value.= number_format(
				( (int)$clicks > 0 && (int)$views > 0 ) ? ( (int)$clicks / (int)$views * 100 ) : '0',
				2,
				$wp_locale->number_format['decimal_point'],
				$wp_locale->number_format['thousands_sep']
			).'%';
		elseif( 'start' == $column ) :
			$start_date = get_post_meta( $post_id, '_start_date', true );
			$value = ( !$start_date ) ?  __( '- not set -', 'adsanity' ) : date( 'd F Y', $start_date );
		elseif( 'expires' == $column ) :
			$end_date = get_post_meta( $post_id, '_end_date', true );
			if( !$end_date ) :
				$value = __( '- not set -', 'adsanity' );
			else :
				if( $end_date != ADSANITY_EOL ) :
					$value = date( 'd F Y', $end_date );
				else :
					$value = __( 'Forever', 'adsanity' );
				endif;
			endif;
		endif;

		echo apply_filters( 'adsanity_ads_posts_columns_'.$column.'_value', $value, $column, $post_id );
	}
	function request( $vars = array() ) {

		// Only show when we're looking at ads
		if( ! is_admin() || ! isset( $vars['post_type'] ) || $vars['post_type'] != 'ads' )
	/**
	 * Provides sorting functionaliyt the for ads post list table
	 * @param  array  $vars query vars
	 * @return array        modified query vars with new sorting options
	 */
		// Only run when we're looking at ads
			return $vars;

		$vars = wp_parse_args( $vars, array( 'orderby' => 'id' ) );

		// Order by post ID
		if ( isset( $vars['orderby'] ) && 'id' == $vars['orderby'] ) :
			$vars = array_merge(
				$vars,
				array(
					'orderby' => 'ID'
				)
			);

		// Order by size custom meta field
		elseif ( isset( $vars['orderby'] ) && 'size' == $vars['orderby'] ) :
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_size',
					'orderby' => 'meta_value'
				)
			);

		// Order by the start date custom meta field
		elseif( isset( $vars['orderby'] ) && 'start_date' == $vars['orderby'] ) :
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_start_date',
					'orderby' => 'meta_value_num'
				)
			);

		// Order by the expiring date
		elseif( isset( $vars['orderby'] ) && 'end_date' == $vars['orderby'] ) :
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => '_end_date',
					'orderby' => 'meta_value_num'
				)
			);

		endif;

		return apply_filters( 'adsanity_ads_posts_sortable_by_'.$vars['orderby'] , $vars );
	}

	function edit_posts_per_page( $perpage = '1', $post_type = 'ads' ) {
		if( $post_type == $this->type ) return -1;
	/**
	 * Shows all posts in the post list
	 * @param  string $perpage   how many posts to be shown per page
	 * @param  string $post_type the post type we're changing
	 * @return int               the number of posts to show per page
	 */
		return $perpage;
	}

	function global_styles() {
	/**
	 * Enqueues global admin styles
	 */
		wp_enqueue_style( 'adsanity-admin-global' );
	}

	function ad_list_scripts( $hook_prefix = '' ) {
		if( 'edit.php' != $hook_prefix )
	/**
	 * Enqueues admin scripts and styles for the ad list view. This is only necessary for WordPress
	 * prior to version 3.3. After version 3.3 a new hook was introduced to handle post classes
	 * @param  string $hook_prefix the page hook in the admin
	 */
		// Only run on the edit screen
			return false;

		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
		// Only run for the ad custom post type

			// flag expired ads for pre-WP3.3
			$now = time();
			$expired_ads = get_posts(
				array(
					'post_type' => 'ads',
					'post_status' => 'publish',
					'meta_query' => array(
						array(
							'key' => '_end_date',
							'value' => $now,
							'type' => 'numeric',
							'compare' => '<'
						)
					),
					'nopaging' => true
				)
			);
			$expired_selectors = array();
			foreach( $expired_ads as $expired_ad ) :
				$expired_selectors[] = '#post-'.$expired_ad->ID;
			endforeach;

			// flag expiring ads for pre-WP3.3
			$expiring_ads = get_posts(
				array(
					'post_type' => 'ads',
					'post_status' => 'publish',
					'meta_query' => array(
						array(
							'key' => '_end_date',
							'value' => array( $now, $now + ( 60 * 60 * 24 * 7 ) ),
							'type' => 'numeric',
							'compare' => 'BETWEEN'
						)
					),
					'nopaging' => true
				)
			);
			$expiring_selectors = array();
			foreach( $expiring_ads as $expiring_ad ) :
				$expiring_selectors[] = '#post-'.$expiring_ad->ID;
			endforeach;

			// Enqueue scripts to highlight ads for pre-WP3.3
			wp_enqueue_script( 'ads-list' );
			wp_localize_script(
				'ads-list',
				'adsanity',
				array(
					'expired_ads'	=> implode( ', ', $expired_selectors ),
					'expiring_ads'	=> implode( ', ', $expiring_selectors )
				)
			);
			// END hack to highlight expired/expiring ads
		endif;
	}

	function ad_new_scripts( $hook_prefix = '' ) {
		if( 'post-new.php' != $hook_prefix )
	/**
	 * Enqueue jQuert UI and custom scripts and styles for the new ad page
	 * @param  string $hook_prefix the page hook in the admin
	 */
		// Only run on the new post screen
			return false;

		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
		// Only run for the ad custom post type
			global $wp_locale;

			wp_enqueue_style( 'adsanity-jqueryui-datepicker' );
			wp_enqueue_script( 'ads-new' );
			wp_localize_script(
				'ads-new',
				'adsanity',
				array(
					'adsanity_eol'	=> ADSANITY_EOL,
					'months'		=> $wp_locale->month,
					'months_01'		=> $wp_locale->month['01'],
					'months_02'		=> $wp_locale->month['02'],
					'months_03'		=> $wp_locale->month['03'],
					'months_04'		=> $wp_locale->month['04'],
					'months_05'		=> $wp_locale->month['05'],
					'months_06'		=> $wp_locale->month['06'],
					'months_07'		=> $wp_locale->month['07'],
					'months_08'		=> $wp_locale->month['08'],
					'months_09'		=> $wp_locale->month['09'],
					'months_10'		=> $wp_locale->month['10'],
					'months_11'		=> $wp_locale->month['11'],
					'months_12'		=> $wp_locale->month['12'],
					'expires_text'	=> __( 'Ad Expires on ', 'adsanity' ),
					'forever_text'	=> __( 'Publish <b class="expiring-date">forever</b> ', 'adsanity' ),
				)
			);
			// END hack to highlight expired/expiring ads
		endif;
	}

	function ad_edit_scripts( $hook_prefix = '' ) {
		if( 'post.php' != $hook_prefix )
	/**
	 * Enqueue jQuery UI and custom scripts and styles for the edit ad page
	 * @param  string $hook_prefix the page hook in the admin
	 */
			return false;

		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
		// Only run for the ad custom post type
			global $wp_locale, $post;

			$script = ( $post->post_status == 'publish' ) ? 'ads-edit' : 'ads-new';

			wp_enqueue_style( 'adsanity-jqueryui-datepicker' );
			wp_enqueue_script( $script );
			wp_localize_script(
				$script,
				'adsanity',
				array(
					'adsanity_eol'	=> ADSANITY_EOL,
					'months'		=> $wp_locale->month,
					'months_01'		=> $wp_locale->month['01'],
					'months_02'		=> $wp_locale->month['02'],
					'months_03'		=> $wp_locale->month['03'],
					'months_04'		=> $wp_locale->month['04'],
					'months_05'		=> $wp_locale->month['05'],
					'months_06'		=> $wp_locale->month['06'],
					'months_07'		=> $wp_locale->month['07'],
					'months_08'		=> $wp_locale->month['08'],
					'months_09'		=> $wp_locale->month['09'],
					'months_10'		=> $wp_locale->month['10'],
					'months_11'		=> $wp_locale->month['11'],
					'months_12'		=> $wp_locale->month['12'],
					'expires_text'	=> __( 'Ad Expires on ', 'adsanity' ),
					'forever_text'	=> __( 'Publish <b class="expiring-date">forever</b> ', 'adsanity' ),
				)
			);
			// END hack to highlight expired/expiring ads
		endif;
	}

	function datepicker_css( $hook_prefix = '' ) {
		if( 'post-new.php' != $hook_prefix && 'post.php' != $hook_prefix )
			return false;

		wp_enqueue_style( 'adsanity-jqueryui-datepicker' );
	}

	function enter_title_here( $placeholder = '' ) {
		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
	/**
	 * Filters the title field placeholder text for ad post types
	 * @param  string $placeholder the default placeholder text
	 * @return string              the modified placeholder text
	 */
			return __( 'Give this ad a title', 'adsanity' );
		endif;
		return $placeholder;
	}

	function remove_meta_boxes() {
	/**
	 * Removes the featured image metabox rom the sidebar
	 */
		remove_meta_box( 'postimagediv', 'ads', 'side' );
	}
	function add_meta_boxes( $post = null ) {
	/**
	 * Adds all of our custom meta boxes
	 * @param WP_Post $post the post object that is being edited
	 */

		/*
		 * COMMON
		/**/
		add_meta_box( // ad size
			'ad-size',
			'Ad Size',
			array( $this, 'ad_size_metabox' ),
			$this->type,
			'normal',
			'high'
		);
		add_meta_box( // notes
			'adsanity-notes',
			'Notes',
			array( &$this, 'ad_notes_metabox' ),
			$this->type,
			'normal',
			'low'
		);

		/*
		 * INTERNAL ADS
		/**/
		add_meta_box( // post thumbnail
			'postimagediv',
			__( 'Ad Image' ),
			'post_thumbnail_meta_box',
			null,
			'normal',
			'high'
		);

		add_meta_box( // tracking url
			'internal-ad-details',
			'Ad Details',
			array( $this, 'internal_ad_details_metabox' ),
			$this->type,
			'normal',
			'high'
		);
		if( $post->post_status == 'publish' ) :
			add_meta_box( // views & clicks chart
				'ad-viewsclicks',
				'Ad Stats',
				array( $this, 'ad_viewsclicks_metabox' ),
				$this->type,
				'normal',
				'high'
			);
		endif;

		/*
		 * EXTERNAL ADS
		/**/
		add_meta_box( // ad code
			'ad-code',
			'Ad Code',
			array( $this, 'ad_code_metabox' ),
			$this->type,
			'normal',
			'high'
		);
		if( $post->post_status == 'publish' ) :
			add_meta_box( // views only chart
				'ad-viewsonly',
				'Ad Stats',
				array( $this, 'ad_views_metabox' ),
				$this->type,
				'normal',
				'high'
			);
		endif;

	}
		function admin_post_thumbnail_html( $content = '' ) {
			global $post;
			if( get_post_type() == $this->type || get_query_var('post_type') == $this->type || ( isset( $post->post_type ) && $post->post_type == $this->type ) ) :
				$content = str_replace( __( 'Set featured image' ), __( 'Set banner ad image', 'adsanity' ), $content );
				$content = str_replace( __( 'Remove featured image' ), __( 'Remove banner ad image', 'adsanity' ), $content );
			endif;

			return $content;
	/**
	 * Changes the link text in the featured image box
	 * @param  string $content the link text before modification
	 * @return string          the link text after modfication
	 */
		}
		function ad_code_metabox( $post = null ) {

			wp_nonce_field( $this->type.'-save_postmeta', $this->type.'_nonce' );

			echo '<p>'.__( "If you have code from an advertising partner, paste that code here. If you'd like to create your own text based ads, you can write custom HTML in the field below. To track clicks on links in the HTML, use the placeholder %link% as your href values. For example, &lt;a href=\"%link%&gt;Link Text&lt;/a&gt;\". Be sure to enter your destination URL in the Tracking URL field above." ).'</p>';
			echo '<textarea name="code" id="ad-code">'.get_post_meta( $post->ID, '_code', true ).'</textarea>';
		}
	/**
	 * Produces the metabox for external ads ad code
	 * @param  WP_Post $post the post object for the post being edited
	 */

		function post_submitbox_start() {
			global $post;
			if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
				// SCHEDULE CHECKBOX
				$start_date = get_post_meta( $post->ID, '_start_date', true );
				$end_date = get_post_meta( $post->ID, '_end_date', true );

				$is_scheduled = ( !$end_date || $end_date == ADSANITY_EOL ) ? false : true;
				echo '<div id="ad-scheduling" class="misc-pub-section curtime">';
				echo '<span id="timestamp" class="expires-text">';
				if( $is_scheduled ) :
					echo __( 'Ad Expires on ', 'adsanity' ).'<b class="expiring-date">'.date( 'F d, Y', $end_date ).'</b>';
				else :
					echo __( 'Publish <b class="expiring-date">forever</b>', 'adsanity' );
				endif;
				echo '</span>';
				echo ' <a href="#" id="is_scheduled">Edit</a>';
	/**
	 * Outputs additional fields to the publish box for an ad unit
	 */

				echo '<div id="for_scheduled_only" style="display: none;">';
		// Only add functionality for Ads

				// START DATE
				echo '<label for="start_date">Display From</label>';
				echo '<input type="text" name="start_date" value="'.( !$start_date ? date( 'F d, Y' ) : date( 'F d, Y', $start_date ) ).'" id="start_date" />';
			// SCHEDULE CHECKBOX

				// END DATE
				echo '<label for="end_date">Until</label>';
				echo '<input type="text" name="end_date" value="'.( !$end_date ? date( 'F d, Y', ADSANITY_EOL ) : date( 'F d, Y', $end_date ) ).'" id="end_date" />';
			// VIEW EXPIRATION DATE
			// EDIT EXPIRATION DATE
			// START DATE

				echo '<p>';
				echo '<a href="#" id="accept_schedule_change" class="button">OK</a> ';
				echo '<a href="#" id="cancel_schedule_change">Cancel</a>';
				echo '</p>';
			// END DATE

				echo '</div>';
				echo '</div>';
			endif;
		}
		function ad_size_metabox( $post = null ) {

			// SIZE
			$options = get_option( ADSANITY_ADMIN_OPTIONS, array() );
			$sizes = apply_filters( 'adsanity_ad_sizes', $options['sizes'] );
			echo '<label for="size">Ad Size</label> ';
			echo '<select name="size" id="size" size="1">';
				$size = get_post_meta( $post->ID, '_size', true );
				foreach( $sizes as $val => $name ) :
					echo '<option value="' . $val . '"' . selected( $size, $val ).'>' . $name . '</option>';
				endforeach;
			echo '</select>';
		}
	/**
	 * Renders the metabox to select a size for an Ad
	 * @param  WP_Post $post the complete post object for the ad
	 */
	/**
	 * Renders the metabox that contains notes about the Ad or Advertiser
	 * @param  WP_Post $post the complete post object for the ad
	 */
		// NOTES
	/**
	 * Renders the metabox with details specific to hosted ads
	 * @param  WP_Post $post the complete post object for the ad
	 */
		// OPEN IN A NEW WINDOW?

		/**
		 * Notes about the Ad or Advertiser
		 */
		function ad_notes_metabox( $post = null ) {
		// URL

			// NOTES
			echo '<p>'.__( "If you'd like to store internal notes about this ad, enter them here. Anything entered in the field below is only visible on this screen." ).'</p>';
			echo '<textarea name="notes" id="ad-notes">'.get_post_meta( $post->ID, '_notes', true ).'</textarea>';
	/**
	 * Renders the stats metabox for hosted ads. Shows views, clicks, and click through rates
	 * @param  WP_Post $post the complete post object for the ad
	 */
		// views js data array
		}
		function internal_ad_details_metabox( $post = null ) {
			// OPEN IN A NEW WINDOW?
			echo '<label for="target">';
			echo '<input name="target" type="checkbox" value="1" id="target" '.checked( '1', get_post_meta( $post->ID, '_target', true ), false ).'> Open in a new window?';
			echo '</label>';

			// URL
			echo '<label for="url">Tracking URL</label>';
			echo '<input type="text" name="url" value="'.esc_attr( get_post_meta( $post->ID, '_url', true ) ).'" id="url">';
		// clicks js data array
		}
		function ad_viewsclicks_metabox( $post = null ) {
			global $wp_locale;
			$today = mktime( 0,0,0, date("n"), (int)date("j")+1, date("Y") );
			$start = strtotime( '-8 days', $today );

			echo '<script type="text/javascript">'."\n";
			// views data array
			$views = array();
			for( $i = $start; $i < $today; $i += ( 60*60*24 ) ) :
				$viewcount = get_post_meta( $post->ID, '_views-'.$i, true );
				$views[] = '['.( $i * 1000 ).','.( !$viewcount ? 0 : $viewcount ).']';
			endfor;
			echo 'var views = { data: ['.implode( ',', $views ).'], label: "Views", color: "#e9275d" };'."\n";

			// clicks data array
			$clicks = array();
			for( $i = $start; $i < $today; $i += ( 60*60*24 ) ) :
				$clickcount = get_post_meta( $post->ID, '_clicks-'.$i, true );
				$clicks[] = '['.( $i * 1000 ).','.( !$clickcount ? 0 : $clickcount ).']';
			endfor;
			echo 'var clicks = { data: ['.implode( ',', $clicks ).'], label: "Clicks", color: "#4fb5d2" };'."\n";
			echo '</script>'."\n";


			$custom = get_post_custom( $post->ID );
			$clickcount = $viewcount = 0;
			foreach( $custom as $key => $arr ) :
				if( strpos( $key, '_views' ) !== false ) :
					$viewcount += (int)$arr[0];
				elseif( strpos( $key, '_clicks' ) !== false ) :
					$clickcount += (int)$arr[0];
				endif;
			endforeach;
			$ctr = ( (int)$clickcount > 0 && (int)$viewcount > 0 ) ? ( (int)$clickcount / (int)$viewcount * 100 ) : '0';

			echo '<h4>'.__( 'Stats Summary', 'adsanity' ).'</h4>';
			echo '<ul class="subsubsub" style="float: none;">';
			echo '<li>'.number_format( $viewcount, 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).' '.__( 'total views', 'adsanity' ).' | </li>';
			echo '<li>'.number_format( $clickcount, 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).' '.__( 'total clicks', 'adsanity' ).' | </li>';
			echo '<li>'.number_format( $ctr, 2, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).'% '.__( 'total CTR', 'adsanity' ).'</li>';
			echo '</ul>';

			echo '<h4 class="clear">'.__( 'Views in the past 7 days', 'adsanity' ).'</h4>';
			echo '<div id="views-chart" class="chart"></div>';

			echo '<h4 class="clear">'.__( 'Clicks in the past 7 days', 'adsanity' ).'</h4>';
			echo '<div id="clicks-chart" class="chart"></div>';
		}
		function ad_views_metabox( $post = null ) {
			global $wp_locale;
			$today = mktime( 0,0,0, date("n"), (int)date("j")+1, date("Y") );
			$start = strtotime( '-8 days', $today );

			echo '<script type="text/javascript">'."\n";
			// views data array
			$views = array();
			for( $i = $start; $i < $today; $i += ( 60*60*24 ) ) :
				$viewcount = get_post_meta( $post->ID, '_views-'.$i, true );
				$views[] = '['.( $i * 1000 ).','.( !$viewcount ? 0 : $viewcount ).']';
			endfor;
			echo 'var views = { data: ['.implode( ',', $views ).'], label: "Views", color: "#e9275d" };'."\n";
			echo '</script>'."\n";


			$custom = get_post_custom( $post->ID );
			$viewcount = 0;
			foreach( $custom as $key => $arr ) :
				if( strpos( $key, '_views' ) !== false ) :
					$viewcount += (int)$arr[0];
				endif;
			endforeach;
		// Views Chart

			echo '<h4>'.__( 'Stats Summary', 'adsanity' ).'</h4>';
			echo '<ul class="subsubsub" style="float: none;">';
			echo '<li>'.number_format( $viewcount, 0, $wp_locale->number_format['decimal_point'], $wp_locale->number_format['thousands_sep'] ).' '.__( 'total views', 'adsanity' ).'</li>';
			echo '</ul>';
		// Clicks Chart

			echo '<h4 class="clear">'.__( 'Views in the past 7 days', 'adsanity' ).'</h4>';
			echo '<div id="viewsonly-chart" class="chart"></div>';
		}
	function edit_form_advanced() {
	/**
	 * Renders the stats metabox for network ads. Only shows views since clicks are handled by the
	 * network.
	 * @param  WP_Post $post the complete post object for the ad
	 */
		// views js data array
		// Views Chart
	/**
	 * Hooks into the edit form for the Ads CPT and injects the Ad Type tab bar
	 */
		global $post;
		if( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) :
			require_once( ADSANITY_VIEWS.'tabs.php' );
		endif;
	}
	function template_include( $template = null ) {
		if( is_single() && ( get_post_type() == $this->type || get_query_var('post_type') == $this->type ) ) :
			return ADSANITY_THEME.'single-ads.php';
		endif;
	/**
	 * Include custom tracking template for Single Ad CPT on the front end
	 * @param  string $template the full path to the template file
	 * @return string           the full path to the template file
	 */
		return $template;
	}

	function post_class( $classes ) {
	/**
	 * Adds expiration status to the post list in the WordPress dashboard for styling
	 * @param  array $classes an array of class names
	 * @return array          an array of class names
	 */
		global $post, $current_screen;
		if( $current_screen->id == 'edit-ads' ) :

			$start_date = get_post_meta( $post->ID, '_start_date', true );
			$end_date = get_post_meta( $post->ID, '_end_date', true );

			if( $start_date && $end_date ) :
				if( time() > $end_date ) :
				// Ad is already expired
					$classes[] = 'expired';
				elseif( time() >= $end_date - ( 60 * 60 * 24 * 7 ) /* 7 days */ ) :
					$classes[] = 'expiring';
				endif;
			endif;

		endif;

		return $classes;
	}
}
new ads_cpt;
