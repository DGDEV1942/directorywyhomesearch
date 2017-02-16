<?php
/**
 * new WordPress Widget format
 * Wordpress 2.8 and above
 * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
 */
class adsanity_single_ad_Widget extends WP_Widget {

    /**
     * Constructor
     *
     * @return void
     **/
	function __construct() {
		$widget_ops = array( 'classname' => 'adsanity-single', 'description' => 'Display a single Adsanity Ad unit.' );
		parent::__construct( 'adsanity-single', 'Adsanity - Single Ad', $widget_ops );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}

	function admin_enqueue_scripts( $hook_suffix = '' ) {
		if( $hook_suffix != 'widgets.php' )
			return false;

		wp_enqueue_style( "adsanity-single-widgets-admin" );
		wp_enqueue_script( "adsanity-single-widget-admin" );
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
			'post_id'		=> $instance['id'],
			'widget_args'	=> $args
		);
		adsanity_show_ad( $widget_args );
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
			'id'			=> 1
		);
		$instance = wp_parse_args( (array) $instance, $defaults );

		$now = time();

		// display all ads that are not expired
		$ads = new WP_Query(
			array(
				'post_type' => 'ads',
				'nopaging' => true,
				'meta_query' => array(
					array(
						'key' => '_end_date',
						'value' => $now,
						'type' => 'numeric',
						'compare' => '>='
					)
				)
			)
		);
		if( $ads->have_posts() ) :

			echo '<p><label for="'.$this->get_field_id( 'title' ).'">'.__( 'Title:', 'adsanity' ).'</label>';
				echo '<input class="widefat" id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" type="text" value="'.esc_attr( $instance['title'] ).'" /></p>';

			echo '<label for="search-ads">'.__( 'Search Ads:', 'adsanity' ).'</label>';
				echo ' <input type="text" id="search-ads" class="adsanity-single-ad-search" value="search ads" />';

			echo '<div class="adsanity-single-ad-list" id="'.$this->id.'">';
			while ( $ads->have_posts() ) : $ads->the_post();
				echo '<label for="'.$this->get_field_id( 'id' ).'-'.$ads->post->ID.'">';
				echo '<input type="radio" id="'.$this->get_field_id( 'id' ).'-'.$ads->post->ID.'" name="'.$this->get_field_name( 'id' ).'" value="'.$ads->post->ID. '"' . checked( $ads->post->ID, $instance['id'], false ) . ' /> ';
				echo esc_html( get_the_title( $ads->post->ID ) ) . '<br />';
				echo '<small>' . get_post_meta( $ads->post->ID , '_size', true ) . '</small>';
				if( get_post_meta( $ads->post->ID , '_start_date', true ) > $now )
					echo '<small style="color: red">Scheduled for '.date( 'm/d/Y', get_post_meta( $ads->post->ID , '_start_date', true ) ).'</small>';
				echo '</label>';
			endwhile;
			echo '</div>';
			wp_reset_postdata();
		else :
			echo '<p>No ads found. Go <a href="' . admin_url( 'post-new.php?post_type=ads' ) . '">create one</a>.</p>';
		endif;
	}
}

add_action( 'widgets_init', create_function( '', "register_widget('adsanity_single_ad_Widget');" ) );
