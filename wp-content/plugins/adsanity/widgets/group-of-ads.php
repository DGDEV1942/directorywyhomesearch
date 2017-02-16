<?php
/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class adsanity_group_ad_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
	function __construct() {
		$widget_ops = array( 'classname' => 'adsanity-group', 'description' => 'Display a group of Adsanity Ad units.' );
		parent::__construct( 'adsanity-group', 'Adsanity - Ad Group', $widget_ops );
		add_action( 'admin_print_styles-widgets.php', create_function( '', 'wp_enqueue_style( "adsanity-group-widgets-admin" );' ) );
	}

    /**
     * Outputs the HTML for this widget.
     *
     * @param array  An array of standard parameters for widgets in this theme
     * @param array  An array of settings for this widget instance
     * @return void Echoes it's output
     **/
	function widget( $args, $instance ) {
		extract( $args, EXTR_SKIP );

		$widget_args = array(
			'is_widget'		=> true,
			'title'			=> $instance['title'],
			'group_ids'		=> $instance['id'],
			'num_ads'		=> $instance['num-ads'],
			'num_columns'	=> $instance['num-columns'],
			'widget_args'	=> $args
		);
		adsanity_show_ad_group( $widget_args );
	}

    /**
     * Deals with the settings when they are saved by the admin. Here is
     * where any validation should be dealt with.
     *
     * @param array  An array of new settings as submitted by the admin
     * @param array  An array of the previous settings
     * @return array The validated and (if necessary) amended settings
     **/
	function update( $new_instance, $old_instance ) {
		// update logic goes here
		$updated_instance = $new_instance;
		return $updated_instance;
	}

    /**
     * Displays the form for this widget on the Widgets page of the WP Admin area.
     *
     * @param array  An array of the current settings for this widget
     * @return void Echoes it's output
     **/
	function form( $instance ) {
		$defaults = array(
			'title'			=> '',
			'id'			=> array( 1 ),
			'num-ads'		=> 4,
			'num-columns'	=> 1
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		echo '<div class="adsanity-group-options">';
			echo '<label for="'.$this->get_field_id( 'title' ).'">'.__( 'Title:', 'adsanity' ).'</label> ';
				echo '<input type="text" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.esc_attr( $instance['title'] ).'" />';
		echo '</div>';

		// Display all ad groups
		$args = array(
			'orderby' => 'name',
			'order' => 'ASC'
		);
		$groups = get_terms( 'ad-group', $args );
		if( count( $groups ) > 0 ) :
			echo '<div class="adsanity-group-list">';
			foreach( $groups as $group ) :
				echo '<label for="term-'.$group->term_id.'">';
				echo '<input type="checkbox" id="term-'.$group->term_id.'" name="'.$this->get_field_name('id').'[]" value="'.esc_attr( $group->term_id ).'"'.checked( in_array( $group->term_id, (array) $instance['id'] ), true, false ).' /> ';
				echo esc_html( $group->name ).'<br />';
				echo '</label>';
			endforeach;
			echo '</div>';
		else :
			echo '<p>'.sprintf( __( 'No ad groups found. Go <a href="%s">create one</a>', 'adsanity' ), admin_url( 'edit-tags.php?taxonomy=ad-group&post_type=ads' ) ).'</p>';
		endif;

		echo '<div class="adsanity-group-additional-options">';

		echo '<label for="'.$this->get_field_id('num-ads').'">'.__( 'Number of ads to show:', 'adsanity' ).'</label>';
		echo '<input type="text" id="'.$this->get_field_id('num-ads').'" name="'.$this->get_field_name('num-ads').'" value="'.esc_attr( (int)$instance['num-ads'] ).'" />';

		echo '<label for="'.$this->get_field_id('num-columns').'">'.__( 'Columns:', 'adsanity' ).'</label>';
		echo '<input type="text" id="'.$this->get_field_id('num-columns').'" name="'.$this->get_field_name('num-columns').'" value="'.esc_attr( (int)$instance['num-columns'] ).'" />';

		echo '</div>';
	}
}

add_action( 'widgets_init', create_function( '', "register_widget('adsanity_group_ad_Widget');" ) );
