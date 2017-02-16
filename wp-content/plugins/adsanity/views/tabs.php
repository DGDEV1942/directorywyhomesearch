<h2 id="ad-source-tabs" class="nav-tab-wrapper">
	<?php
		$thumbnail_id = get_post_meta( $post->ID, '_thumbnail_id', true );
		$ad_code = get_post_meta( $post->ID, '_code', true );
	?>
	<a href="#internal" class="nav-tab<?php echo ( ( $thumbnail_id || !$ad_code ) ? ' nav-tab-active' : '' ) ?>"><?php _e( 'Ad Hosted On-Site', 'adsanity' ) ?></a>
	<a href="#external" class="nav-tab<?php echo ( $ad_code ? ' nav-tab-active' : '' ) ?>"><?php _e( 'External Ad Network', 'adsanity' ) ?></a>
</h2>