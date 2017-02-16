<?php
	/*
		WHEN AN AD HAS BEEN CLICKED
		INCREASE THE CLICK COUNT FOR TODAY
	*/
	if( have_posts() ) : the_post();
		adsanity_click( $post->ID );
		if( isset( $_GET['r'] ) ) :
			wp_redirect( $_GET['r'] );
		else :
			$url = get_post_meta( $post->ID, '_url', true );
			$url = str_replace( '[link]', get_permalink( $post->ID ), $url );
			$url = str_replace( '[timestamp]', time(), $url );
			wp_redirect( $url );
		endif;
		die();
	endif;