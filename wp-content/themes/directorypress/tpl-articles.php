<?php
/*
Template Name: [Articles Template]
*/

/* =============================================================================
   THIS FILE SHOULD NOT BE EDITED // UPDATED: 16TH MARCH 2012
   ========================================================================== */

global $PPT, $PPTDesign, $ThemeDesign, $userdata; get_currentuserinfo();

/* =============================================================================
   LOAD IN PAGE CONTENT // V7 // 16TH MARCH
   ========================================================================== */

$hookContent = premiumpress_pagecontent("articles"); /* HOOK V7 */

if(strlen($hookContent) > 20 ){ // HOOK DISPLAYS CONTENT

	get_header();
	
	echo $hookContent;
	
	get_footer();

}elseif(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme')."/_tpl_articles.php")){
		
	include(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme').'/_tpl_articles.php');
		
}else{ 
	
/* =============================================================================
   LOAD IN PAGE DEFAULT DISPLAY // UPDATED: 25TH MARCH 2012
   ========================================================================== */ 
 
get_header();  ?>

<div class="itembox">

<h1><?php the_title(); ?></h1>

	<div class="itemboxinner greybg">
    
    <?php if(strlen($post->post_content) > 2){ ?><p><?php echo $post->post_content; // display the template page content regardless ?></p><?php } ?>

	<div class="FAQ_Content">

			<div class="item categories">
	
				<h3 class="texttitle border_b"><?php echo $PPT->_e(array('title','7')); ?></h3>

                <div class="full clearfix">
                    
                <?php 
                
                    $taxonomy     = 'article';
                    $orderby      = 'name'; 
                    $show_count   = 1;      // 1 for yes, 0 for no
                    $pad_counts   = 1;      // 1 for yes, 0 for no
                    $hierarchical = 1;      // 1 for yes, 0 for no
                    $title        = '';
                    $fcats 		  = '';
                    $i			  = 0;
                    $args = array(
                      'taxonomy'     => $taxonomy,
                      'orderby'      => $orderby,
                      'show_count'   => $show_count,
                      'pad_counts'   => $pad_counts,
                      'hierarchical' => $hierarchical,
                      'title_li'     => $title,
                      'hide_empty'	=> 0
                    );
                    
                    $cats  = get_categories( $args );
                    foreach($cats as $cat){   if($cat->parent == 0){ $fcats .= $cat->cat_ID.",";
                    
                    if($i%2){ $ex ="space"; }else{ $ex =""; }
                    if($i == 3){ print '<div class="clearfix"></div>'; $ex =""; $i=0;}
                                
                
                    print '<div class="categoryItem '.$ex.'">
                                
                            <a href="'.get_term_link( $cat,$cat->taxonomy  ).'" title="'.$cat->category_nicename.'">'.$cat->cat_name.'</a>
                            <p>'.$cat->description.'</p>
                        
                        </div>';
                
                    $i++; } }
                
                    print '<div class="clearfix"></div></div>';
                    
                    
                print '	<div class="item featured"><h2>'.$PPT->_e(array('articles','1')).'</h2><ul>';
                
                $posts = query_posts('posts_per_page=10&post_type=article_type&orderby=rand');
                
                    foreach($posts as $post){  
                    
                        print '<li class="featuredPosts"><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
                    
                    }
                    
                    print '</ul></div>
                
                        <div class="item latest-half">
                        
                            <h2>'.$PPT->_e(array('articles','2')).'</h2>
                            <ul>';
                            
                            $posts = query_posts('posts_per_page=10&post_type=article_type&orderby=ID&order=desc');
                            foreach($posts as $post){  
                            
                                print '<li><a href="'.get_permalink($post->ID).'" title="'.$post->post_title.'"><strong>'.$post->post_title.'</strong>'.get_the_date().'</a></li>';
                                        
                            }			
                                        
                            
                    print '</ul></div></div><div class="clearfix"></div>';
                
                
                 ?>
                
                </div><!-- end category block --> 

	</div><!-- end itemboxinner -->
 
</div> <!-- end itembox -->
 
<?php get_footer(); 
	
}
/* =============================================================================
   -- END FILE
   ========================================================================== */
?>