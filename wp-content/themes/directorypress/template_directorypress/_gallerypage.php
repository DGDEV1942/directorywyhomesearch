<?php  get_header(); $defaulttypw = get_option('display_liststyle');    ?>

<div id="AJAXRESULTS"></div><!-- AJAX RESULTS -do not delete- -->

<div class="itembox"> 
 
	<h1><?php if(isset($_GET['s'])){ echo $PPT->_e(array('button','11')).": ".strip_tags($_GET['s']); }
	elseif( isset($_GET['search-class'])) {  echo $PPT->_e(array('button','11')).": ".strip_tags($_GET['cs-all-0']); }else{ echo $GLOBALS['premiumpress']['catName']; } ?>
    </h1>       

	<div class="itemboxinner nopadding">
    
    <div class="searchresultsbar">
    
        <div class="left">  
         
            <p><?php echo str_replace("%a",$GLOBALS['query_total_num'],$PPT->_e(array('gallerypage','1'))); ?></p>
            
     		<?php echo $PPTDesign->breadcrumbs(); ?> 
            
        </div>
        
        <!-- end left items box -->
        <?php if($GLOBALS['query_total_num'] > 0 && !isset($GLOBALS['setflag_article']) && !isset($GLOBALS['tag_search']) && !isset($GLOBALS['setflag_faq']) ){  ?>    
        <div class="right"> 
           
            <ol class="pag_switch right">
            <li><a class="pag_switch_button <?php if($defaulttypw == "list" || $defaulttypw ==""){ echo "selected"; } ?>" id="pages1" href="javascript:void(0);" onclick="jQuery('#itemsbox').removeClass('three_columns').addClass('list_style'); jQuery('#pages2').removeClass('selected'); jQuery('#pages1').addClass('selected');">
            <img src="<?php echo get_template_directory_uri(); ?>/template_directorypress/images/list.png" alt="list" /></a></li>
            <li><a class="pag_switch_button <?php if($defaulttypw == "gal"){ echo "selected"; } ?>" id="pages2" href="javascript:void(0);" onclick="jQuery('#itemsbox').removeClass('list_style').addClass('three_columns');jQuery('#pages1').removeClass('selected'); jQuery('#pages2').addClass('selected'); ">
            <img src="<?php echo get_template_directory_uri(); ?>/template_directorypress/images/grid.png" alt="grid" /></a></li>
            </ol>
            
            <?php  echo $PPTDesign->GL_ORDERBY(); ?>       
     
 		</div>
        <?php } ?>
        <!-- end right box -->
    
  
      <div class="clearfix"></div>
            
    <hr style="margin:8px;" /> 
       
      <?php if(get_option("display_gallery_saveoptions") != "no"){ ?>
           
          <a href="javascript:PPTSaveSearch('<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/','<?php echo htmlentities(str_replace("http://","",str_replace("&","---",curPageURL()))); ?>','AJAXRESULTS');" class="iconss" rel="nofollow">
          <?php echo $PPT->_e(array('gallerypage','2')); ?></a> 
          
          <a class="iconvss" href="javascript:PPTGetSaveSearch('<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/','AJAXRESULTS');" rel="nofollow">
          <?php echo $PPT->_e(array('gallerypage','3')); ?></a> 
          
          <?php if(isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID'])){ ?>
          <a  class="iconemail" href="javascript:PPTEmailAlter('<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/','<?php echo $GLOBALS['premiumpress']['catID']; ?>','AJAXRESULTS');" rel="nofollow">
          <?php echo $PPT->_e(array('gallerypage','4')); ?></a>
          <?php } ?>
      
      <?php } ?>
       
      
    
      	<?php 
		
		/* SUB CATEGORIES BLOCK */
		
		if($GLOBALS['query_total_num'] != 0 && isset($GLOBALS['premiumpress']['catID']) && is_numeric($GLOBALS['premiumpress']['catID']) && get_option("display_sub_categories") =="yes" ){
	   
		$STRING = $PPTDesign->HomeCategories(); if(strlen($STRING) > 5){ ?> <div id="subcategories"> <?php echo $STRING; ?> </div>   <div class="clearfix"></div>
    
		<?php } } 
		
		/* END SUB CATEGORIES BLOCK */		
		?>       
        
        
	   <?php 
       
       /* CUSTOM CATEGORY DESCRIPTION */
       
       if(isset($GLOBALS['catText']) && strlen($GLOBALS['catText']) > 1){   if(strlen($GLOBALS['catImage']) > 2){ ?><img src="<?php echo $GLOBALS['catImage']; ?>" style="float:left; padding-right:20px;" /><?php }   echo "<br />".$GLOBALS['catText'];  }
       
       /* END CUSTOM CAT DESCRIPTION */
       
       ?>
     
    
    </div>
    
	
    <div class="clearfix"></div>
    
    <?php if($GLOBALS['query_total_num'] > 0 ){ ?>  
    <!-- start results -->  
	<ul class="items <?php if($defaulttypw == "gal"){ echo "three_columns"; }else{ echo "list_style"; } ?>" id="itemsbox"> 
        
    	<?php echo $PPTDesign->GALLERYBLOCK(); ?>
        
    </ul>
    <!-- end results -->
    <?php } ?>          
           
           
            
    <div class="clearfix"></div>
    </div><!-- end item box inner -->
    
	<?php if($GLOBALS['query_total_num'] > 0 ){ ?>
    <div class="enditembox inner">
    
    <?php if(isset($GLOBALS['premiumpress']['catID'])){ ?><a class="button gray" target="_blank" href="<?php echo $GLOBALS['bloginfo_url']; ?>/?cat=<?php echo $GLOBALS['premiumpress']['catID']; ?>&amp;feed=rss2"><?php echo $PPT->_e(array('gallerypage','5')); ?> <img src="<?php echo get_template_directory_uri(); ?>/template_directorypress/images/icons/rss.png" alt="rss feed" /></a><?php } ?>
     
        
	<ul class="pagination paginationD paginationD10"><?php echo $PPTDesign->PageNavigation(); ?></ul>
    
    </div>
    
	<?php }else{ ?>
    
 	<div class="yellow_box">
    	<div class="yellow_box_content">        
        	<div align="center"><img src="<?php echo get_template_directory_uri(); ?>/PPT/img/exclamation.png" align="absmiddle" alt="nr" />  <?php echo $PPT->_e(array('gallerypage','11')); ?> </div>       
        <div class="clearfix"></div>
        </div>    
 	</div>    
    
    <?php } ?>

</div>
 

<?php  get_footer(); ?>