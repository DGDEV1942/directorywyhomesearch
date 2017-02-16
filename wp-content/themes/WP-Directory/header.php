<!--<div class="menu-header"><ul id="menu-top-menu" class="menu"><li id="menu-item-37" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-37"><a href="http://www.wyhomesmag.com/">Home</a></li>
   <li id="menu-item-27" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-27"><a href="http://www.wyhomesmag.com/advance-search/">Search Properties</a></li>
   <li id="menu-item-24" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-24"><a href="http://www.wyhomesmag.com/agents/">Find an Agent</a></li>
   <li id="menu-item-30" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-30"><a href="http://www.wyhomesmag.com/this-months-issue-of-wyoming-homes-magazine/">View Our Magazine</a></li>
   <li id="menu-item-26" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-26"><a href="http://directory.wyhomesearch.com/">Resource Directory</a></li>
   <li id="menu-item-23" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-23"><a href="http://www.wyhomesmag.com/discussion-board/">Discussion Board</a></li>
   <li id="menu-item-28" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-28"><a href="http://www.wyhomesmag.com/tools/">Tools</a></li>
   <li id="menu-item-25" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-25"><a href="http://www.wyhomesmag.com/maps/">Maps</a></li>
   </ul></div>                -->
<!--navbar-->
<section class="nav-top-start">
   <nav class="navbar navbar-inverse">
      <div class="container">
         <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </button>
         </div>
         <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
               <li class="active"><a href="http://23.250.19.146/wyhomesearch/">Home </a></li>
               <li class="menu-item" >
                  <a href="http://23.250.19.146/wyhomesearch/advance-search/">Search Properties</a>                  
               </li>
              <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children dropdown">
			<a href="http://23.250.19.146/wyhomesearch/agents/"><span>Find an Agent</span></a>
			</li>
               <li class="menu-item"><a href="http://23.250.19.146/wyhomesearch/this-months-issue-of-wyoming-homes-magazine/">View Our Magazine</a></li>
               <li class="menu-item"><a href="http://23.250.19.146/wyhomesearch/town-cities/">Cities & Towns</a></li>
               <li class="menu-item"><a href="http://23.250.19.146/wyhomesearch/this-months-issue-of-wyoming-homes-magazine/">Resource  Directory</a></li>
               <li class="menu-item" ><a href="http://23.250.19.146/wyhomesearch/discussion-board/">Discussion Board</a></li>
               <li class="menu-item"><a href="http://23.250.19.146/wyhomesearch/maps/">Maps</a></li>
               <li class="menu-item"><a href="http://23.250.19.146/wyhomesearch/blog/">Blog</a></li>
            </ul>
         </div>
      </div>
   </nav>
</section>
<!--end-navbar-->
<!--banner-->
<section class="banner_top_here">
   <div class="container">
      <div class="row">
         <div class="banner-new">
            <div class="banner_right_side">
               <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="" id="">
                     <a class="navbar-brand" href="#"> <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png"></a>
                  </div>
               </div>
               <div class="col-md-6 col-sm-6 col-xs-12">
                  <div class="bottom-menu">
                     <ul>
                        <li class="menu-item item-menu" ><a href="http://23.250.19.146/directorywyhomesearch/">Directory Home</a></li>
                        <li class="menu-item item-menu" ><a href="http://23.250.19.146/directorywyhomesearch/create-listing/">Add a Business </a></li>
                        <li class="menu-item item-menu"><a href="http://23.250.19.146/wyhomesearch/wp-login.php">Login/</a><a href="http://23.250.19.146/wyhomesearch/wp-login.php?action=register" style="margin-left: -8px;">Register</a></li>
                     </ul>
                  </div>
                  <div class="topper-right-side" id="sidebar">
                     <a href="http://23.250.19.146/directorywyhomesearch/create-listing/">
                     </a>
                     <aside id="create_listing_button-2" class="widget widget_create_listing_button"><a href="http://23.250.19.146/directorywyhomesearch/create-listing/">Add a business now</a></aside>
                  </div>
                  <span class="fb-log">			   
                  <div class="fb-like" data-href="https://www.facebook.com/WYHomesResources/" data-layout="button_count" data-action="like" data-size="large" data-show-faces="true" data-share="false"></div>
                  <!--<a style="padding:8px 0 0 0;" href="https://www.facebook.com/WYHomesResources/"> <img src="<?php echo get_template_directory_uri(); ?>/images/side_img.png" width="120" style="float:right;"></a></span>-->
               </div>
            </div>
            <div class="search-for-box">
               <div class="col-md-8 col-md-push-2 col-lg-8 col-lg-push-2">
                  <div class="search-box-inner">
                     <!--<span class="logo-topprer"><img src="http://wyhomesearch.com/wp-content/uploads/2012/06/logo11.png"></span>--->
                     <h2>Find a Local Service Provider</h2>
                     <div class="form-here-start">
                        <?php if ( !is_page_template( 'create-listing.php' ) ) : ?>
                        <form method="get" action="<?php bloginfo( 'url' ); ?>">
                           <div class=" form-group">
                              <input type="text" class="form-control" name="ls" id="email" placeholder="Search For (e.g. electrician, plumber, mortgages)" value="<?php va_show_search_query_var( 'ls' ); ?>"/>
                           </div>
                           <input type="text" name="location" class="form-control" id="pwd" value="<?php va_show_search_query_var( 'location' ); ?>" placeholder="Near <?php _e( '(city, county, zipcode)', APP_TD ); ?>">
                           <span class="but-search">								<button type="submit" class="btn btn-default"><?php _e( 'Search', APP_TD ); ?></button>				</span>
                        </form>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!--end-banner-->
<!--banner-bottom-->
<div class="container">
   <section class="banner_bottom_part">
      <div class="arrow-1"></div>
      <!---<div class="row">
         <div class="col-xs-12 col-md-3 col-lg-3">
         <span class="inner-icon-set"><h2>FREE Listing</h2></span>
         </div>
         <div class="col-xs-12 col-md-4 col-lg-4">
         <span class="inner-icon-set"><h2>Over 200 Contractors Listed</h2></span>
         </div>
         <div class="col-xs-12 col-md-5 col-lg-5">
         <span class="inner-icon-set"><h2>Used by Over 10 Million Homeowners</h2></span>
         </div>---->
</div>
</div>
</section>
<!--banner-bottom-end-->
<?php 
   $taxonomy  = 'listing_category';
   $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
   
   foreach($tax_terms as $tax_term){
   	
   	//print_r($tax_term->name);
   }
   ?>