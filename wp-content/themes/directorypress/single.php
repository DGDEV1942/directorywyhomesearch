<?php
 
/* =============================================================================
   THIS FILE SHOULD NOT BE EDITED // UPDATED: 16TH MARCH 2012
   ========================================================================== */ 
   
if(!function_exists('get_currentuserinfo')){ die("No Access"); }

global $PPT,$ThemeDesign, $post, $PPTDesign, $user_ID, $userdata; get_currentuserinfo();

/* =============================================================================
   ONLOAD OPTIONS // V7 // 16TH MARCH
   ========================================================================== */
 
 	// SET FLAG JUST IN CASE WP DOESNT
	$GLOBALS['IS_SINGLEPAGE'] = true;

	// SOME TIMES WP DOESNT COPY IT ALL THE WAY THROUGH
	$GLOBALS['authorID'] = $post->post_author;

	// CHECK PAGE VISIBILITY
	$VISIBILITY = get_post_meta($post->ID, "visible", true);
	if($VISIBILITY == "private"){
		premiumpress_authorize(); nocache_headers();
	}

	// UPDATE THE HIT COUNTER
	$PPT->UpdateHits($post->ID,get_post_meta($post->ID, "hits", true));

	// CHECK IF THE LISTING HAS EXPIRED
	premiumpress_expired($post->ID,$post->post_date);			
			
	// CHECK IF THE LISTING NEEDS PRUNING
	premiumpress_prune($post->ID);
	
	// CHECK USER - PAGE ACCESS
	if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"){
	
		// GET MEMBERSHIP OPTIONS
		$MEMBERSHIPDATA = get_option('ppt_membership');
		
		// CHECK IF WE HAVE ENABLED THE SYSTEM
		if(isset($MEMBERSHIPDATA['enable']) && $MEMBERSHIPDATA['enable'] == "no"){
	 
	 		//NOTHING TODO
			 
		}else{		 
	 
			$GLOBALS['page_package_acecss'] = get_post_meta($post->ID, "package_access", true);	
			if(is_array($GLOBALS['page_package_acecss']) && !in_array(0,$GLOBALS['page_package_acecss'])){
			
				// GET USER ACCESS
				$GLOBALS['membershipID'] 		= get_user_meta($userdata->ID, 'pptmembership_level', true);			
			
			}
		
		}		

		$GLOBALS['packageID'] 		= get_post_meta($post->ID, "packageID", true);
		if(strlen($GLOBALS['packageID']) > 0){
			$packdata = get_option("packages");
			$GLOBALS['packageName'] = $packdata[$GLOBALS['packageID']]['name'];
			if(isset($packdata[$GLOBALS['packageID']]['icon'])){ $GLOBALS['packageIcon'] = $packdata[$GLOBALS['packageID']]['icon']; }
		 
		}
		
	} // end if shopperpress
	
	
	if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && is_numeric($userdata->ID) && $userdata->ID > 0 ){
	
		// GET MEMBERSHIP ID SO WE CAN SEE IF THEY ARE ABLE TO SEND MESSAGES
		$GLOBALS['canSendMessages']		= true;
		$GLOBALS['userMID'] 		= get_user_meta($userdata->ID, 'pptmembership_level', true);
		if(is_numeric($GLOBALS['userMID']) && $GLOBALS['userMID'] > 0){
		
			// CHECK IF WE CAN SEND MESSAGES 
			$GLOBALS['membershipData'] 		= $MEMBERSHIPDATA;
			foreach($GLOBALS['membershipData']['package'] as $package){	
				if($package['ID'] == $GLOBALS['userMID'] ){ // && $package['duration'] !=""
						if($package['messages'] == "no"){
						 $GLOBALS['canSendMessages'] = false;
						}
					}		
				}
		}
	
	}
 
 
/* =============================================================================
   LOAD IN PAGE CONTENT // V7 // 16TH MARCH
   ========================================================================== */

$hookContent = premiumpress_pagecontent("single");
 
// CHECK WE CAN ACCESS THIS
if( ($userdata->ID == 0 &&  is_array($GLOBALS['page_package_acecss']) && !in_array(0,$GLOBALS['page_package_acecss']) ) || ( $userdata->ID !=0 && isset($GLOBALS['membershipID']) && is_numeric($GLOBALS['membershipID']) && !in_array($GLOBALS['membershipID'],$GLOBALS['page_package_acecss']) && $GLOBALS['authorID'] !=  $userdata->ID  )  ){

	// REDIRECT GUEST TO LOGIN PAGE
	if($userdata->ID == 0){		
		
		header("location: ".$GLOBALS['bloginfo_url']."/wp-login.php?action=register&noaccess=1");	
	}
	
	get_header();
	
	echo "<h3>".$PPT->_e(array('membership','1'))."</h3>";
	
	echo "<p>".$PPT->_e(array('membership','2'))."</p> ";
	
	echo "<hr />";
	
	echo $PPTDesign->Memberships($GLOBALS['membershipID'],$GLOBALS['page_package_acecss']);
	
	get_footer(); 
		 
}elseif(strlen($hookContent) > 20 ){ // HOOK DISPLAYS CONTENT

	get_header();
	
	echo $hookContent;
	
	get_footer();

}elseif($post->post_type !="post"){ // LOAD THE ARTICLE PAGE INSTEAD IF THIS IS AN ARTICLE

	$GLOBALS['ARTICLEPAGE'] = 1;

	if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme')."/_articlepage.php")){
			
		include(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme').'/_articlepage.php');
			
	}else{ 
	
	$date_format = get_option('date_format') . ' ' . get_option('time_format');
	
	/* =============================================================================
	   LOAD IN PAGE DEFAULT DISPLAY // UPDATED: 25TH MARCH 2012
	   ========================================================================== */ 
	   
	 get_header(); if (have_posts()) : while (have_posts()) : the_post();  
	 
	 $my_date = the_date('d-M', '', '', FALSE);
	 $datebits = explode("-",$my_date); 
	 $date = mysql2date($date_format, $post->post_date, false);  ?>
	
	<div class="itembox">
	
		<h1><?php the_title(); ?></h1>
		
		<div class="itemboxinner greybg article entry">
        
           	<ol class="info">
					<li><a class="user icon" href="#"><?php the_author(); ?></a></li>
                   <!-- <li><a class="globe icon" href="#"><?php  echo get_the_term_list( $post->ID, 'category', '', ', ', ', ' );   ?></a></li>-->
					<li><a class="clock icon" href="#"><?php  echo $date; ?></a></li>
					<?php the_tags("<li><ul class='tags icon'>",' ','</ul></li>') ?>
			</ol>
            <!-- end info box -->
            
            <div class="addthis_toolbox addthis_default_style addthis_25x25_style" style="margin-top:-15px;width:120px;float:right;">
            <a class="addthis_button_twitter"></a>
            <a class="addthis_button_facebook"></a>     
            <a class="addthis_button_compact"></a>
            <a class="addthis_counter addthis_bubble_style"></a>
            </div>
            
            <!-- end addthis -->
            
            <hr style="margin-top:10px;" />
            
            <div class="clearfix"></div>
		
			<?php if(strlen(get_post_meta($post->ID, 'image', true)) > 1){ ?>
            
            <?php echo premiumpress_image($post,"",array('alt' => $post->post_title, 'style' => 'float:right; margin-right:30px; margin-top:10px; max-width:150px; max-height:150px;' )); ?>			   
			
			<?php } ?>			   
		
			<div id="articlepage" class="entry"><?php the_content(); ?></div>
		 
			<?php comments_template(); ?>
			
			<div class="clearfix"></div>
			
		</div><!-- end inner item box -->
	
	</div><!-- end item box -->
	 
	<?php endwhile; else :  endif; get_footer();  
		 
		
	} 
	
}else{

	// REGISTER COLOURBOX
	wp_register_script( 'colorbox',  get_template_directory_uri() .'/PPT/js/jquery.colorbox-min.js');
	wp_enqueue_script( 'colorbox' );
	
	wp_register_style( 'colorbox',  get_template_directory_uri() .'/PPT/css/css.colorbox.css');
	wp_enqueue_style( 'colorbox' );

	// FEATURED TEXT
	$featured_text = get_post_meta($post->ID, "featured_text", true);
	if($featured_text == ""){
		$featured_text = get_option('ppt_featuredtext');
	}
	
	// GET CUSTOM FIELD DATA
	$CustomFields 	= get_option("customfielddata");
	
	// CHECK FOR THE WEBSITE LINK
	$link = premiumpress_link($post->ID);

	if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme')."/_single.php")){
			
		include(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme').'/_single.php');
			
	}else{ 
 
		include("template_".strtolower(PREMIUMPRESS_SYSTEM)."/_single.php");
		
	} 

} 
/* =============================================================================
   -- END FILE
   ========================================================================== */
?>