<?php
	$classes = array( 'ad-'.get_post_meta( $post->ID, '_size', true ) );
	
	if( isset( $column ) )
		$classes[] = 'column-'.esc_attr( $column );
		
	if( isset( $align ) )
		$classes[] = esc_attr( $align );
	
	$adsanity_post_class = apply_filters( 'adsanity_post_class', $classes, $post );
?>
<div id="ad-<?php echo $post->ID ?>" class="<?php echo implode( ' ', $adsanity_post_class ) ?>">
	<?php if( has_post_thumbnail( $post->ID ) ) : ?>
		<a rel="nofollow" href="<?php echo get_permalink( $post->ID ) ?>"<?php echo ( (bool) get_post_meta( $post->ID, '_target', true ) ? ' target="_blank"' : '' ) ?>><?php echo get_the_post_thumbnail( $post->ID, 'full' ) ?></a>
	<?php
		else :
			$code = get_post_meta( $post->ID, '_code', true );
			$code = str_replace( '%link%', get_permalink( $post->ID ), $code );
			$code = str_replace( '[link]', get_permalink( $post->ID ).'?r=', $code );
			$code = str_replace( '[timestamp]', time(), $code );
			echo $code;
		endif;
	?>
</div>