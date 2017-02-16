<?php

// GET THE DEFAULT POST CUSTOM FIELDS
if($featured == "yes"){ $ex = "featuredlisting"; }else{ $ex = ""; }
 

?>
<li class="<?php echo $ex; ?> <?php if(function_exists('wp_gdsr_render_article')){ echo "minH"; } ?>">

<?php
// FEATURED GRAPHIC
if(get_post_meta($post->ID, "featured", true) == "yes" && strlen($featured_text) > 1){ ?>      
<div class="group corner" style="margin-right:-2px;margin-top:-2px;">
<div class="wrap-ribbon right-corner strip lgreen"><span><?php echo $featured_text; ?></span></div>			
</div>
<?php } ?> 

<div class="padding">

<div class="preview"> 
<?php
// IMAGE DISPLAY // V7 
echo premiumpress_image($post->ID,"",array('alt' => $post->post_title,  'link' => true, 'link_class' => 'frame', 'width' => '160', 'height' => '110', 'style' => 'auto' ));  
?>   

<?php if(function_exists('wp_gdsr_render_article')){ wp_gdsr_render_article();}  ?> 
               
</div>

<!-- end preview box -->
 
<a class="title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>

<?php if(strlen($tagline) > 1){ ?><p class="tagline"><?php echo $tagline ; ?></p><?php } ?>
               
<p class="excerpt"><?php echo trim(substr(strip_tags($post->post_excerpt),0,300)); ?></p>

<div class="tags1">
<?php if(get_option("display_search_tags") =="yes"){ the_tags( '', ', ', ''); } ?> 


<?php if(!isset($GLOBALS['setflag_faq']) && !isset($GLOBALS['setflag_article'])){  ?>
 
	<?php  if($custom1 != "" || $custom2 != ""){?>
    <p><small>
    <?php echo get_option("display_custom_display1")." ".$custom1;  } ?>
    <?php if($custom2 != ""){  echo get_option("display_custom_display2")." ".$custom2; } ?>
    </small></p> 
 
<?php } ?>
</div>

<div class="actions">

	<a class="<?php if($featured == "yes"){ echo "green"; }else{ echo "gray"; } ?> button right" href="<?php the_permalink(); ?>"><?php echo $PPT->_e(array('button','13')); ?></a>
    
	<?php if(get_option("display_search_publisher") == "yes" && strlen($link) > 2){ ?>
    
     <a class="<?php if($featured == "yes"){ echo "green"; }else{ echo "gray"; } ?> button right" style="margin-right:10px;" href="<?php echo $link; ?>" title="<?php the_title(); ?>" target="_blank" <?php if($GLOBALS['premiumpress']['nofollow'] =="yes"){ ?>rel="nofollow"<?php } ?>>
	 <?php echo $PPT->_e(array('button','12')); ?>
     </a>  
                          
    <?php } ?>
	 
</div>

<div class="clearfix"></div>
</div>

<!-- end actions box -->

</li>