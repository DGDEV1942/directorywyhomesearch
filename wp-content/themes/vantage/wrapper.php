<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>    <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />

	<title><?php wp_title(''); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	  <script src="https://use.fontawesome.com/48d8134394.js"></script>
<link rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/themes/WP-Directory/css/style.css">
<link rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/themes/vantage/style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=1675514456050872";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
	<?php wp_head(); ?>
	

	<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
	
	 <script>
   $(document).ready(function(){	
	$("#search-scroll").click(function() {
    $('html, body').animate({
        scrollTop: $(".logo-topprer").offset().top
    }, 2000);
});
});
   </script>
   
	<style>
	.maincat-list {
  display: none;
}
	</style>
</head>

<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

	<?php appthemes_before(); ?>

	<?php appthemes_before_header(); ?>
	<?php get_header( app_template_base() ); ?>
	<?php appthemes_after_header(); ?>

	<?php if(is_front_page()){?>	
	<?php load_template( app_template_path() ); ?>

				<div class="clear"></div>
	<?php
	}else{	
	?>
	<section class="content-responive">
	<div id="content" class="container">
		<?php do_action( 'va_content_container_top' ); ?>
		<div id="content-mid" class="row rounded">
			<div id="content-inner" class="rounded">

				<?php load_template( app_template_path() ); ?>

				<div class="clear"></div>
			</div> <!-- /content-inner -->
		</div> <!-- /content-mid -->
	</div> <!-- /content -->
	</section>
	<?php 
	}
	?>

	<?php get_footer( app_template_base() ); ?>
	<?php appthemes_before_footer(); ?>
	<?php appthemes_after_footer(); ?>

	<?php appthemes_after(); ?>

	<?php wp_footer();?>

</body>
</html>
