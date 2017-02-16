<?php
/*
 * Props: Matt Van Andel - http://www.mattvanandel.com
 * We used his example plugin as a starting point for the filter list implementation
 * Link: http://wordpress.org/extend/plugins/custom-list-table-example/
/**/

/*************************** LOAD THE BASE CLASS *******************************
 *******************************************************************************
 * The WP_List_Table class isn't automatically available to plugins, so we need
 * to check if it's available and load it if necessary.
 */
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


/************************** CREATE A PACKAGE CLASS *****************************
 *******************************************************************************
 * Create a new list table package that extends the core WP_List_Table class.
 * WP_List_Table contains most of the framework for generating the table, but we
 * need to define and override some methods so that our data can be displayed
 * exactly the way we need it to be.
 *
 * To display this example on a page, you will first need to instantiate the class,
 * then call $yourInstance->prepare_items() to handle any data manipulation, then
 * finally call $yourInstance->display() to render the table to the page.
 *
 * Our theme for this list table is going to be movies.
 */
class AdSanity_Filters_List_Table extends WP_List_Table {

    /** ************************************************************************
     * REQUIRED. Set up a constructor that references the parent constructor. We
     * use the parent reference to set some default configs.
     ***************************************************************************/
    function __construct(){
        global $status, $page;

        //Set parent defaults
        parent::__construct(array(
            'singular'	=> 'adsanity_user_agent',	// singular name of the listed records
            'plural'	=> 'adsanity_user_agents',	//plural name of the listed records
            'ajax'		=> false					//does this table support ajax?
        ));
        add_action( 'admin_init', array( &$this, 'process_bulk_action' ), 11 );
    }

	/**
	 * Get an associative array ( id => link ) with the list
	 * of views available on this table.
	 *
	 * @since 3.1.0
	 * @access protected
	 *
	 * @return array
	 */
	function get_views() {

		$all_class			= ( !isset( $_GET['agent_status'] ) ? ' class="current"' : '' );
		$discovered_class	= ( isset( $_GET['agent_status'] ) && $_GET['agent_status'] == 'discovered' ? ' class="current"' : '' );
		$blacklisted_class	= ( isset( $_GET['agent_status'] ) && $_GET['agent_status'] == 'blacklisted' ? ' class="current"' : '' );
		return array(
			'all'			=> '<a'.$all_class.' href="'.admin_url( 'edit.php?post_type=ads&page=adsanity-tracking-filters' ).'">'.__( 'All', 'adsanity' ).'</a>',
			'discovered'	=> '<a'.$discovered_class.' href="'.admin_url( 'edit.php?agent_status=discovered&post_type=ads&page=adsanity-tracking-filters' ).'">'.__( 'Discovered User Agents', 'adsanity' ).'</a>',
			'blacklisted'	=> '<a'.$blacklisted_class.' href="'.admin_url( 'edit.php?agent_status=blacklisted&post_type=ads&page=adsanity-tracking-filters' ).'">'.__( 'Blacklisted User Agents', 'adsanity' ).'</a>'
		);
	}


    /** ************************************************************************
     * Recommended. This method is called when the parent class can't find a method
     * specifically build for a given column. Generally, it's recommended to include
     * one method for each column you want to render, keeping your package class
     * neat and organized. For example, if the class needs to process a column
     * named 'title', it would first see if a method named $this->column_title()
     * exists - if it does, that method will be used. If it doesn't, this one will
     * be used. Generally, you should try to use custom column methods as much as
     * possible.
     *
     * Since we have defined a column_title() method later on, this method doesn't
     * need to concern itself with any column with a name of 'title'. Instead, it
     * needs to handle everything else.
     *
     * For more detailed insight into how columns are handled, take a look at
     * WP_List_Table::single_row_columns()
     *
     * @param array $item A singular item (one full row's worth of data)
     * @param array $column_name The name/slug of the column to be processed
     * @return string Text or HTML to be placed inside the column <td>
     **************************************************************************/
    function column_default( $item, $column_name ){
        switch( $column_name ) :
            case 'id':
				return $item['ID'];
            case 'blacklisted':
				if( $item[$column_name] == 0 )
                	return '<img src="'.ADSANITY_IMG.'cross-32.png" />';
				else
                	return '<img src="'.ADSANITY_IMG.'tick-32.png" />';
            default:
                return $item;
        endswitch;
    }


    /** ************************************************************************
     * Recommended. This is a custom column method and is responsible for what
     * is rendered in any column with a name/slug of 'title'. Every time the class
     * needs to render a column, it first looks for a method named
     * column_{$column_title} - if it exists, that method is run. If it doesn't
     * exist, column_default() is called instead.
     *
     * This example also illustrates how to implement rollover actions. Actions
     * should be an associative array formatted as 'slug'=>'link html' - and you
     * will need to generate the URLs yourself. You could even ensure the links
     *
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_title( $item ){

		$paged = '';
		/*
		 * Query arguments
		/**/
		if( isset( $_REQUEST['paged'] ) )
			$paged = '&paged='.urlencode( $_REQUEST['paged'] );

		if( (bool) $item['blacklisted'] ) :
	       //Build row actions
	        $actions = array(
	            'whitelist'	=> sprintf(
					'<a href="?post_type=ads&page=adsanity-tracking-filters&action=whitelist&agent=%s%s">'.
						__( 'Remove from Blacklist', 'adsanity' ).
					'</a>',
					$item['ID'],
					$paged
				)
	        );
		else :
	       //Build row actions
	        $actions = array(
	            'blacklist'	=> sprintf(
					'<a href="?post_type=ads&page=adsanity-tracking-filters&action=blacklist&agent=%s%s">'.
						__( 'Add to Blacklist', 'adsanity' ).
					'</a>',
					$item['ID'],
					$paged
				)
	        );
		endif;

		$icons = array();

		/*
		 * Browser icon
		/**/
		if( strpos( $item['title'], 'firefox' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'firefox-32.png" />';

		elseif( strpos( $item['title'], 'chrome' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'chrome-32.png" />';

		elseif( strpos( $item['title'], 'msie' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'ie-32.png" />';

		elseif( strpos( $item['title'], 'opera' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'opera-32.png" />';

		elseif( strpos( $item['title'], 'safari' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'safari-32.png" />';

		elseif( strpos( $item['title'], 'webkit' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'webkit-32.png" />';

		elseif( strpos( $item['title'], 'wordpress' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'wordpress-32.png" />';

		else :
			$icons[] = '<img src="'.ADSANITY_IMG.'unknown-32.png" />';
		endif;

		/*
		 * Operating System icon
		/**/
		if( strpos( $item['title'], 'macintosh' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'apple-32.png" />';

		elseif( strpos( $item['title'], 'windows' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'windows-32.png" />';

		elseif( strpos( $item['title'], 'nix' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'linux-32.png" />';

		elseif( ( strpos( $item['title'], 'ipad' ) !== false || strpos( $item['title'], 'ipod' ) !== false || strpos( $item['title'], 'iphone' ) !== false ) && strpos( $item['title'], 'os 4' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'ios4-32.png" />';

		elseif( ( strpos( $item['title'], 'ipad' ) !== false || strpos( $item['title'], 'ipod' ) !== false || strpos( $item['title'], 'iphone' ) !== false ) && strpos( $item['title'], 'os 5' ) !== false ) :
			$icons[] = '<img src="'.ADSANITY_IMG.'ios5-32.png" />';

		else :
			$icons[] = '<img src="'.ADSANITY_IMG.'bot-32.png" />';
		endif;

        //Return the title contents
        return sprintf('<div class="icons">%1$s</div> %2$s %3$s',
            /*$1%s*/ implode( ' ', $icons ),
            /*$2%s*/ $item['title'],
            /*$3%s*/ $this->row_actions( $actions )
        );
    }

    /** ************************************************************************
     * REQUIRED if displaying checkboxes or using bulk actions! The 'cb' column
     * is given special treatment when columns are processed. It ALWAYS needs to
     * have it's own method.
     *
     * @see WP_List_Table::::single_row_columns()
     * @param array $item A singular item (one full row's worth of data)
     * @return string Text to be placed inside the column <td> (movie title only)
     **************************************************************************/
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }


    /** ************************************************************************
     * REQUIRED! This method dictates the table's columns and titles. This should
     * return an array where the key is the column slug (and class) and the value
     * is the column's title text. If you need a checkbox for bulk actions, refer
     * to the $columns array below.
     *
     * The 'cb' column is treated differently than the rest. If including a checkbox
     * column in your table you must create a column_cb() method. If you don't need
     * bulk actions or checkboxes, simply leave the 'cb' entry out of your array.
     *
     * @see WP_List_Table::::single_row_columns()
     * @return array An associative array containing column information: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_columns(){
        $columns = array(
            'cb'			=> '<input type="checkbox" />', //Render a checkbox instead of text
            'id'			=> 'ID',
            'title'			=> 'User Agent',
            'blacklisted'	=> 'Blacklisted?'
        );
        return $columns;
    }

    /** ************************************************************************
     * Optional. If you need to include bulk actions in your list table, this is
     * the place to define them. Bulk actions are an associative array in the format
     * 'slug'=>'Visible Title'
     *
     * If this method returns an empty value, no bulk action will be rendered. If
     * you specify any bulk actions, the bulk actions box will be rendered with
     * the table automatically on display().
     *
     * Also note that list tables are not automatically wrapped in <form> elements,
     * so you will need to create those manually in order for bulk actions to function.
     *
     * @return array An associative array containing all the bulk actions: 'slugs'=>'Visible Titles'
     **************************************************************************/
    function get_bulk_actions() {
        $actions = array(
            'blacklist'	=> __( 'Add to Blacklist', 'adsanity' ),
            'whitelist'	=> __( 'Remove from Blacklist', 'adsanity' )
        );
        return $actions;
    }


    /** ************************************************************************
     * Optional. You can handle your bulk actions anywhere or anyhow you prefer.
     * For this example package, we will handle it in the class to keep things
     * clean and organized.
     *
     * @see $this->prepare_items()
     **************************************************************************/
    function process_bulk_action() {
		global $wpdb;

        // Detect when a bulk action is being triggered...
        if( 'blacklist' === $this->current_action() ) :

			// Blacklist a Single User Agent
			if( isset( $_GET['agent'] ) && ( intval( $_GET['agent'] ) == $_GET['agent'] ) ) :
				$wpdb->update(
					$wpdb->postmeta,
					array( 'meta_key'	=> '_blacklisted_agents' ),
					array( 'meta_id'	=> intval( $_GET['agent'] ) ),
					array( '%s' ),
					array( '%d' )
				);

			// Bulk Blacklist
			elseif( isset( $_POST['adsanity_user_agent'] ) && count( $_POST['adsanity_user_agent'] ) > 0 ) :
				foreach( (array) $_POST['adsanity_user_agent'] as $agent_id ) :
					$wpdb->query($wpdb->prepare("
						UPDATE		{$wpdb->postmeta}
						SET			meta_key = %s
						WHERE		meta_id = %d
						", '_blacklisted_agents', $agent_id
					));
				endforeach;
			endif;

        elseif( 'whitelist' === $this->current_action() ) :

			// Whitelist a Single User Agent
			if( isset( $_GET['agent'] ) && ( intval( $_GET['agent'] ) == $_GET['agent'] ) ) :
				$wpdb->update(
					$wpdb->postmeta,
					array( 'meta_key'	=> '_discovered_agents' ),
					array( 'meta_id'	=> intval( $_GET['agent'] ) ),
					array( '%s' ),
					array( '%d' )
				);

			// Bulk Whitelist
			elseif( isset( $_POST['adsanity_user_agent'] ) && count( $_POST['adsanity_user_agent'] ) > 0 ) :
				foreach( (array) $_POST['adsanity_user_agent'] as $agent_id ) :
					$wpdb->query($wpdb->prepare("
						UPDATE		{$wpdb->postmeta}
						SET			meta_key = %s
						WHERE		meta_id = %d
						", '_discovered_agents', $agent_id
					));
				endforeach;
			endif;
        endif;

    }


    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     *
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {

        /**
         * First, lets decide how many records per page to show
         */
        $per_page = apply_filters( 'adsanity_user_agents_per_page', 20 );


        /**
         * REQUIRED. Now we need to define our column headers. This includes a complete
         * array of columns to be displayed (slugs & titles), a list of columns
         * to keep hidden, and a list of columns that are sortable. Each of these
         * can be defined in another method (as we've done here) before being
         * used to build the value for our _column_headers property.
         */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        /**
         * REQUIRED. Finally, we build an array to be used by the class for column
         * headers. The $this->_column_headers property takes an array which contains
         * 3 other arrays. One for all columns, one for hidden columns, and one
         * for sortable columns.
         */
        $this->_column_headers = array( $columns, $hidden, $sortable );


        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();


        /**
         * Instead of querying a database, we're going to fetch the example data
         * property we created for use in this plugin. This makes this example
         * package slightly different than one you might build on your own. In
         * this example, we'll be using array manipulation to sort and paginate
         * our data. In a real-world implementation, you will probably want to
         * use sort and pagination data to build a custom query instead, as you'll
         * be able to use your precisely-queried data immediately.
         */
		global $wpdb;

		$where = '';
		if( isset( $_GET['agent_status'] ) ) :
			if( $_GET['agent_status'] == 'discovered' ) :
				$where = $wpdb->prepare( "AND	pm.meta_key = %s", '_discovered_agents' );
			elseif( $_GET['agent_status'] == 'blacklisted' ) :
				$where = $wpdb->prepare( "AND	pm.meta_key = %s", '_blacklisted_agents' );
			endif;
		endif;

		$user_agents = $wpdb->get_results($wpdb->prepare("
			SELECT		pm.meta_id,
						pm.meta_key,
						pm.meta_value,
						p.ID
			FROM		{$wpdb->postmeta} pm
			INNER JOIN	{$wpdb->posts} p
				ON		p.ID = pm.post_id
			WHERE		p.post_type = %s
			{$where}
			ORDER BY	pm.meta_id DESC
		", 'adsanity-data' ));

		array_walk(
			$user_agents,
			create_function(
				'&$val',
				'$val = array( "ID" => $val->meta_id, "title" => $val->meta_value, "blacklisted" => ( $val->meta_key == "_discovered_agents" ? 0 : 1 ) );'
			)
		);
		$data = $user_agents;


        /**
         * REQUIRED for pagination. Let's figure out what page the user is currently
         * looking at. We'll need this later, so you should always include it in
         * your own package classes.
         */
        $current_page = $this->get_pagenum();

        /**
         * REQUIRED for pagination. Let's check how many items are in our data array.
         * In real-world use, this would be the total number of items in your database,
         * without filtering. We'll need this later, so you should always include it
         * in your own package classes.
         */
        $total_items = count( $data );


        /**
         * The WP_List_Table class does not handle pagination for us, so we need
         * to ensure that the data is trimmed to only the current page. We can use
         * array_slice() to
         */
        $data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );



        /**
         * REQUIRED. Now we can add our *sorted* data to the items property, where
         * it can be used by the rest of the class.
         */
        $this->items = $data;


        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  // WE have to calculate the total number of items
            'per_page'    => $per_page,                     // WE have to determine how many items to show on a page
            'total_pages' => ceil( $total_items / $per_page )   // WE have to calculate the total number of pages
        ) );
    }

}
