<?php

/**************** CLAIM LISTING RESULTS *******************/
$GLOBALS['user_info'] 		= get_userdata($post->post_author);
$GLOBALS['claim_email'] 	= get_post_meta($post->ID, 'email', true);
/**************** CLAIM LISTING RESULTS *******************/

// SETUP GLOBAL VALUES FROM CUSTOM DATA
$PostMetaArray = array("images","tagline"); 
foreach($PostMetaArray as $value){
$GLOBALS[$value] 			= get_post_meta($post->ID, $value, true);
} 	

// refresh the thumbnail?
if($_GET['refresh'] == 1 && get_option('stw_2') == 1 && ($post->post_author == $userdata->ID || is_admin())) {
    $path = FilterPath();
    require_once(str_replace("wp-admin","",$path)."/wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/PPT/class/stw_api_code.php");
    refreshThumbnail($link, array());
}

// START OUTPUT
get_header(); ?>

<div id="AJAXRESULTS"></div><!-- AJAX RESULTS -do not delete- -->

 
<?php if (have_posts()) : while (have_posts()) : the_post();  
	
	// TOOLBOX FOR POST AUTHOR
	if($post->post_author == $userdata->ID ){ ?>
  
    <div class="green_box"><div class="green_box_content"> 
    
    <h3 class="left" style="margin:0px; padding:0px; line-height:20px; width:200px"><img src="<?php echo get_template_directory_uri(); ?>/PPT/img/v7/icons/toolbox.png" align="absmiddle" style="padding-right:10px;" />
	<?php echo $PPT->_e(array('title','15')); ?>
    </h3>        
    
    <div class="right">
    
    <?php $IMAGEVALUES = get_option('pptimage'); if(strlen($link) > 2 && $IMAGEVALUES['stw_2'] == 1 && ($post->post_author == $userdata->ID || is_admin())){ ?>
     <a href="<?php the_permalink(); echo '?refresh=1'; ?>" class="button green" rel="nofollow">Refresh Thumbnail</a> |       
    
    <?php } ?> 

	<a href="<?php echo get_option('submit_url'); ?>?eid=<?php echo $post->ID; ?>" class="button green" rel="nofollow"><?php echo $PPT->_e(array('button','2')); ?></a> |    
    <a href="<?php echo get_option('manage_url'); ?>?eid=<?php echo $post->ID; ?>&dd=1" onclick="return ppt_confirm('<?php echo $PPT->_e(array('validate','5')); ?>');" class="button green" rel="nofollow"><?php echo $PPT->_e(array('button','3')); ?></a>   
   
    </div>
    <div class="clearfix"></div>
           
    </div>
    </div> 
           
    <?php } ?>
    
    <?php 
	
	// CLAMIN LISTING BOX
	
	if(isset($GLOBALS['claim_email']) && $GLOBALS['claim_email'] !="" && $post->post_author == 1){ ?>
    
    <div class="green_box"><div class="green_box_content">
    
     
    <div class="right">      
	<a href="javascript:void(0);" <?php if ( $userdata->ID ){ echo 'onclick="document.ClaimListing.submit();"'; }else{ ?>onclick="alert('<?php echo $PPT->_e(array('ajax','1')); ?>');"<?php } ?> class="button green" rel="nofollow"><?php echo $PPT->_e(array('membership','8')); ?></a>  
    </div>
    <img src="<?php echo get_template_directory_uri(); ?>/template_directorypress/images/claimlisting.png" align="absmiddle" style="padding-right:10px;float:left;" />
    <div class="left"> 
    <div style="max-width:400px;">
    <h3><?php echo $PPT->_e(array('membership','6')); ?></h3>    
    <p class="noMargin"><?php echo $PPT->_e(array('membership','7')); ?></p>
    </div>
   	</div>
    
    <div class="clearfix"></div>           
    </div>
    </div> 
    <form action="" method="post" name="ClaimListing" id="ClaimListing"><input type="hidden" name="action" value="claimlisting" /></form>
	
	<?php } ?>
   
    
    
 
    

    
<div class="itembox">

	<?php
    // FEATURED GRAPHIC
    if(get_post_meta($post->ID, "featured", true) == "yes" && strlen($featured_text) > 1){ ?>      
    <div class="group corner" style="margin-right:-2px;margin-top:-2px;">
    <div class="wrap-ribbon right-corner strip lgreen"><span><?php echo $featured_text; ?></span></div>			
    </div>
    <?php } ?> 

	<div class="itemboxinner ">
    

    <div id="begin" class="inner">
    
        <div class="right frame">  
        
        <?php
        // IMAGE DISPLAY // V7 
        echo premiumpress_image($post->ID,"",array('alt' => $post->post_title,  'link' => 'self', 'link_class' => 'lightbox', 'width' => '110', 'height' => '110', 'style' => 'max-width:100px; max-height:100px;' ));  
        ?>  
        
        </div>
     
        <h1><?php the_title(); ?></h1>
        
        <?php if(strlen($GLOBALS['tagline']) > 1){ ?><p class="tagline"><?php echo $GLOBALS['tagline'] ; ?></p><?php }else{ ?><br /><?php } ?>
        
        <ol class="page_tabs">
        
            <li><a href="#tab1"><?php echo $PPT->_e(array('title','17')); ?></a></li> 
                        
            <?php if(strlen($GLOBALS['images']) > 4){ ?><li><a href="#tab2"><?php echo $PPT->_e(array('title','16')); ?></a></li><?php } ?>
           
        </ol>
                            
    </div>
    
    
	<div class="page_container">

		<div id="tab1" class="page_content nopadding">
        
         	<h3 class="texttitle"><?php echo $PPT->_e(array('title','19')); ?></h3>
            
            <hr style="margin-top:0px;" />
              
            <div class="entry article"><?php the_content(); ?></div>
            
            <hr />    
            
            <div class="buttonbox">
               	 
                <?php if(strlen($link) > 2){ ?>
                    
                    <a class="button gray" href="<?php echo $link; ?>" target="_blank" <?php if($GLOBALS['premiumpress']['nofollow'] =="yes"){ ?>rel="nofollow"<?php } ?> title="<?php the_title(); ?>">
                         
                     <?php echo $PPT->_e(array('button','12')); ?>
                             
                    </a>        
             
                <?php } ?>
                    
                <?php if(get_option("display_contactform") =="yes"){ ?>
                
                <a href="#contactForm" class="button gray showform" rel="nofollow"><?php echo $PPT->_e(array('button','14')); ?></a>
                
				<?php } ?>
                
                <?php if(get_option("display_gallery_saveoptions") != "no"){ ?>
                
                  <a class="button gray"  href="javascript:void(0);" <?php if($userdata->ID){ ?>onclick="UpdateWishlist(<?php echo $post->ID; ?>,'add','AJAXRESULTS','<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/','wishlist');" 
        		<?php }else{ ?>onclick="alert('<?php echo $PPT->_e(array('ajax','1')) ?>','WishlistAlert');"<?php } ?> rel="nofollow"><?php echo $PPT->_e(array('fav','3')) ?></a> 
        
        		<?php } ?>
                
                <?php if(get_option("display_social") =="yes"){ ?> 
                
                	<a class="button gray addthis_button" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="nofollow">
                
                		<?php echo $PPT->_e(array('button','15')); ?>
                        
        			</a>
                    
                 <?php } ?>
                 
            </div>
                    
            <hr />
            
            <div class="clearfix"></div>        
                  
            <?php $PPTDesign->CustomFields($post,""); ?>  
                   
            <?php the_tags("<div class='texttitle tags'>".$PPT->_e(array('title','20'))." ",' ','</div>') ?>
           
             <?php if(strlen(get_post_meta($post->ID, "map_location", true)) > 1  && $PPT->CanShow($post->ID, "map_location")){ ?><div id="map_sidebar2" style="margin-top:10px;"></div><?php } ?>    
    
             <hr />
             
            <?php 
			
			/* COMMENTS FORM */
			
			if(get_option("display_single_comments") =="yes"){  comments_template(); }  ?>
            
        
        </div>
        
        <!-- end tab 1 -->        
        
		<div id="tab2" class="page_content"> 
        
            <?php echo $PPTDesign->Attachments($post->ID); ?>
        
        </div>        
 
        <!-- end tab 2 -->              
    
	</div> <!-- end page_container -->
    
 </div><!-- end item inner box --> 
     
</div><!-- end itembox -->



<?php if(get_option("display_contactform") =="yes"){ ?> 

<form action="#" id="contactForm" name="contactForm" method="post"  style="display:none;" onsubmit="return CheckMessageData(this.message_name.value,this.message_subject.value,this.message_message.value,'<?php echo $PPT->_e(array('validate','0')); ?>');" class="padding"> 
<input type="hidden" name="action" value="sidebarcontact" />
<input type="hidden" name="message_name" value="<?php echo the_author_meta('login'); ?>" />
<input type="hidden" name="author_ID" value="<?php echo get_the_author_meta('ID'); ?>" />
<?php wp_nonce_field('ContactForm') ?>

<h3><?php echo $PPT->_e(array('contact','9')); ?></h3>
<p><?php echo $PPT->_e(array('contact','10')); ?></p>

        <fieldset> 
                                 
            <div class="full clearfix box"> 
            <p class="f_half left"> 
                <label for="name"><?php echo $PPT->_e(array('contact','1')); ?> <span class="required">*</span></label>
                <input type="text" name="message_from" id="message_name"  class="short" tabindex="1" />
            </p> 
            <p class="f_half left"> 
                <label for="email"><?php echo $PPT->_e(array('contact','2')); ?><span class="required">*</span></label> 
                <input type="text" name="message_subject" id="message_subject" class="short" tabindex="2" />               
            </p> 
            </div> 
     
                  
            <div class="full clearfix border_t box"> 
            <p>
                <label for="comment"><?php echo $PPT->_e(array('contact','3')); ?> <span class="required">*</span></label> 
                <textarea tabindex="3" class="long" rows="4" name="message_message" id="message_message"></textarea>                    
            </p>
            </div>   
            
            <?php $email_nr1 = rand("0", "9");$email_nr2 = rand("0", "9"); ?>
            <div class="full clearfix border_t box"> 
            <p class="f_half left"> 
                <label for="name"><?php echo str_replace("%a",$email_nr1,str_replace("%b",$email_nr2,$PPT->_e(array('validate','6')))) ?> </label> 
                <input type="text" name="code" value="" class="long" tabindex="4" /> 
                <input type="hidden" name="code_value" value="<?php echo $email_nr1+$email_nr2; ?>" />
            </p> 
             </div>               
            
            <div class="full clearfix border_t box"> 
            <p class="full clearfix"> 
                <input type="submit" name="submit1" class="button gray" tabindex="5" value="<?php echo $PPT->_e(array('contact','4')); ?>" />  
            </p> 
            </div>	
        
        </fieldset> 
</form>
 <?php } ?>
 

<script language="javascript">
jQuery(document).ready(function(){

	jQuery(".lightbox").colorbox();	 	
	jQuery(".showform").colorbox({inline:true, width:"600px"});				
	jQuery(".showform").colorbox({
		onOpen:function(){ jQuery('#contactForm').show();  }, //jQuery('#tab1').hide();					 
		onClosed:function(){ jQuery('#contactForm').hide();  } //jQuery('#tab1').show();
	});
				 
});
</script> 
<?php endwhile; else :  endif;   get_footer(); ?>