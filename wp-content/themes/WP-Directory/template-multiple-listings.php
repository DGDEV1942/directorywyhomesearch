<?php
/*
 * Template Name: Page for Multiple Listings Form
 */
?>
<div id="main">

	<?php appthemes_before_blog_loop(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

	<?php appthemes_before_blog_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<?php appthemes_before_blog_post_title(); ?>

			<h1 class="post-heading"><span class="left-hanger"><?php the_title(); ?></span></h1>

			<?php appthemes_after_blog_post_title(); ?>


		<section class="overview">
			<?php appthemes_before_blog_post_content(); ?>
			<?php 
			$user_has_multiple_listings = get_user_meta( get_current_user_id(), 'user_has_multiple_listings', true );
			// Admins should always see the form, no matter if they have multiple listings or not.
			if( ! $user_has_multiple_listings && ! current_user_can( 'manage_options' ) ) {
				$out = '';
				$out .= '<p>This form is only for users who have purchased a plan with an option for multiple locations.</p>';
				if( ! is_user_logged_in() ) {
					$out .= '<p>If you have an account, please <a href="' . wp_login_url( get_permalink() ) . '">log in</a> to see the form.</a>';
				}
				$out .= '<p>If you feel you have reached this page in error, please <a href="mailto:info@wyhomesmag.com">email us</a> and we\'ll be happy to help you.</p>';
				echo $out;
			} else {
				the_content();
			} ?>
			<?php appthemes_after_blog_post_content(); ?>
		</section>

	<small><?php va_the_post_byline(); ?></small>
	<?php edit_post_link( __( 'Edit', APP_TD ), '<span class="edit-link">', '</span>' ); ?>

	<?php if ( function_exists( 'sharethis_button' ) && $va_options->blog_post_sharethis ): ?>
		<div class="sharethis"><?php sharethis_button(); ?></div>
	<?php endif; ?>

    <?php comments_template(); ?>

	</article>

	<?php appthemes_after_blog_post(); ?>

	<?php endwhile; ?>

	<?php appthemes_after_blog_loop(); ?>

</div><!-- /#main -->

<div id="sidebar" class="threecol last">
	<?php get_sidebar( app_template_base() ); ?>
</div>