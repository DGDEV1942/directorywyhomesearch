<?php
/*
Template Name: Home page New
*/
get_header();

?>


 <!--banner-bottom-end-->
 <div class="container">
    <section class="top-category-part">
		
        
			<div class="row">
				<div class="col-xs-12">
				<div class="top-heading-1">
				  <h2>Top Categories</h2>
				</div>
				</div>	
				<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
					<div class="category-list">	
						<ul>
						<?php 
						$num=1;
						$taxonomy  = 'listing_category';
						$tax_terms = get_terms($taxonomy, array('hide_empty' => true));

						foreach($tax_terms as $tax_term){
							if($num==19)
							{
									break;	
							}	
							
						?>
							<a href="<?php echo get_term_link( $tax_term->term_id); ?>">
								<li>
									<span class="icon-inner"><i class="fa fa-home"></i> 
									<?php echo $tax_term->name; ?>
									</span>
								</li>
							</a>
							<?php
							if($num%6==0){
							echo '</ul>
							</div></div><div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
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
    </section>
	
	 <!--section class="right-pro-part">
       <div class="container">
          <div class="row">
          
            <div class="col-sm-10 col-sm-push-1 col-md-8 col-md-push-2 col-lg-6 col-lg-push-3">
            <div class="overlay-right-pro">
            <h2>Find the Right Contractor</h2>
            
            <p>WY Home Search makes it easy to find local contractors who specialize in exactly the type of work you need done. Just tell us a few details about your project and we'll match you to the best pro for the job</p>
            <span class="star-pro"><button type="button" id="search-scroll" class="btn btn-default">Search Now</button></span>
            </div>
            
            </div>
          
          </div>
        </div>
     </section>--->
         
     
   <!--section class="upper-bottom-1">
       <div class="container">
          <div class="row"> 
          
          </div>
        </div>
     </section-->
     <section class="city-part-start">
      <div class="container">
          <div class="row">
          <div class="inner-upper-bottom">
          <div class="row">
          <div class="col-md-6 col-sm-5 col-xs-12 ment_help">
          <img src="<?php echo get_template_directory_uri();?>/images/back-ground-1.png">
          <h2>Are You a Home Improvement Contractor?</h2>
          <p>Find out how WY Home Search can help your business.</p>
          <span class="learn-bt"><a href="<?php echo site_url();?>/create-listing/"><button type="button" class="btn btn-default">Add a Listing</button></a></span>
          </div>
           <div class="col-md-6 col-sm-7 col-xs-12">
          <span class="city-heading"><h2> Top Cities </h2></span>
           <div class="col-xs-12 col-sm-4   col-md-4  col-lg-4">
           <div class="city-list-here">
           <ul>
            <li><a href="http://23.250.19.146/wyhomesearch/cities/casper/">Casper</a></li>
            <li><a href="http://23.250.19.146/wyhomesearch/cities/cheyenne/">Cheyenne</a></li>
			<li><a href="http://23.250.19.146/wyhomesearch/cities/dubois/">Dubois</a></li>	
            <li><a href="http://23.250.19.146/wyhomesearch/cities/douglas/">Douglas</a></li> 
			<li><a href="http://23.250.19.146/wyhomesearch/cities/green-river/">Green River</a></li>
           
		 
           
           </ul>
           </div>
           </div>
            <div class="col-xs-12 col-sm-4    col-md-4  col-lg-4">
           <div class="city-list-here">
           <ul>
           <li><a href="http://23.250.19.146/wyhomesearch/cities/lander/">Lander</a></li>
            <li><a href="http://23.250.19.146/wyhomesearch/cities/laramie/">Laramie</a></li>
			<li class="no-link">Rawlins</li>
			<li><a href="http://23.250.19.146/wyhomesearch/cities/riverton/">Riverton</a></li> 
			<li><a href="http://23.250.19.146/wyhomesearch/cities/rock-springs/">Rock Springs</a></li>
			
           </ul>
           </div>
           </div>
            <div class="col-xs-12 col-sm-4  col-md-4  col-lg-4">
           <div class="city-list-here">
           <ul>
           
           
        <li class="no-link"> Shoshoni</li>
        <li><a href="http://23.250.19.146/wyhomesearch/cities/thermopolis/">Thermopolis</a></li>
		<li class="no-link">Torrington</li>
		<li class="no-link">Wheatland</li>
		<li class="no-link">Worland</li>
			
           

		   
		   
		   
           </ul>
           </div>
           </div>
             <!--div class="col-xs-12 col-sm-4 col-sm-offset-2  col-md-3 col-md-offset-0">
           <div class="city-list-here">
           <ul>
            
            
           </ul>
           </div>
          </div-->
          
          
           
           
           
           
           
           
           
          
          </div>         
          </div> 
          </div>
         

         
          
           
          
           
         
           
           </div>
        </div>
     </section>   
	 

	 <?php wp_footer(); ?>