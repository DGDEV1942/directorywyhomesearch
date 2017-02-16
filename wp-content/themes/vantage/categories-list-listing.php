<?php
// Template Name: Listing Categories
?>

<div id="main">
	<div class="section-head">
		<h1><?php _e( 'Listing Categories', APP_TD ); ?></h1>
	</div>

	<div class="row">
					
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">
					<div class="category-list">	
						<ul>
						<?php 
						$num=1;
						$taxonomy  = 'listing_category';
						$tax_terms = get_terms($taxonomy, array('hide_empty' => true));

						foreach($tax_terms as $tax_term){
														
						?>
							<a href="<?php echo get_term_link( $tax_term->term_id); ?>">
								<li>
									<span class="icon-inner" style="font-size:15px;"><i class="fa fa-home"></i> 
									<?php echo $tax_term->name; ?>
									</span>
								</li>
							</a>
							<?php
							if($num%10==0){
							echo '</ul>
							</div></div><div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">
					<div class="category-list">	<ul>
							';	
							}
							
							?>

							<?php $num++; ?>	

						
						<?php } ?>
						</ul>
					</div>
				</div>	
			</div>
</div>

<div id="sidebar">
	<?php get_sidebar( app_template_base() ); ?>
</div>
