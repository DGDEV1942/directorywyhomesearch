<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>

<head profile="http://gmpg.org/xfn/11">

<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

<title><?php wp_title('&laquo;', true, 'right'); ?></title>    

<?php wp_head(); ?>

</head> 

<body <?php ppt_body_class(); ?>>
 
	<div class="wrapper <?php $PPTDesign->CSS("ppt_layout_width"); ?>"> 
	
		<div id="header" class="full">
        	
           <div class="w_960">
        
            <div class="f_half left" id="logo"> 
            
             <a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>">
             
			 	<img src="<?php echo $PPT->Logo(); ?>" alt="<?php bloginfo('name'); ?>" />
                
			 </a>
            
            </div>        
        
            <div class="left padding5" id="banner"> 
            
           	 <?php echo premiumpress_banner("top"); ?>
             
            </div>
           
           </div> <!-- end header w_960 -->       
        
        <div class="clearfix"></div>
        
        </div> <!-- end header -->
 
        <div class="menu" id="menubar"> 
            
            	<div class="w_960">
                
				<?php if(has_nav_menu('PPT-CUSTOM-MENU-PAGES')){ wp_nav_menu( $GLOBALS['blog_custom_menu'] ); }else{ ?>
                
                    <ul> 
                                    
                    <li class="first"><a href="<?php echo $GLOBALS['bloginfo_url']; ?>/" title="<?php bloginfo('name'); ?>"><?php echo SPEC($GLOBALS['_LANG']['_home']) ?></a></li> 
                    <?php echo premiumpress_pagelist(); ?>
                    
                    </ul>                    
                    
                 <?php } ?>
                 
             	</div><!-- end  menubar w_960 -->
           
        </div><!-- end menubar -->
    
        
        <div id="submenubar"> 
        
			<div class="w_960">
       
            <form method="get"  action="<?php echo $GLOBALS['bloginfo_url']; ?>/" name="searchBox" id="searchBox">
              <table width="100%" border="0" id="SearchForm">
              <tr>
                <td><input type="text" value="<?php echo $PPT->_e(array('head','2')) ?>" name="s" id="s" onfocus="this.value='';" style="width:300px;height:28px;"/></td>
                <td><select id="catsearch" name="cat" style="width:250px;"><option value="">&nbsp;</option><?php echo premiumpress_categorylist(0,'toponly'); ?></select></td>
                <td><div class="searchBtn" onclick="document.searchBox.submit();"> &nbsp;</div> </td>
                <td>&nbsp;&nbsp;<?php if(get_option("display_advanced_search") ==1){ ?><a href="javascript:jQuery('#AdvancedSearchBox').show(); javascript:void(0);" class="white"><small><?php echo $PPT->_e(array('head','3')); ?></small></a><?php } ?></td>
              </tr>
            </table>
            </form>
     
             
           <?php
		   
		           $string = '<ul class="submenu_account">';
                       
            if ( $userdata->ID ){ 
			
				$string .= '<li><a href="'.wp_logout_url().'">'.$PPT->_e(array('head','4')).'</a></li>
				<li><a href="'.$GLOBALS['premiumpress']['dashboard_url'].'">'.$PPT->_e(array('head','5')).'</a></li>
				<li><b>'.$userdata->user_login.'</b></li>';
			
            }else{
            
            	$string .= '<li><a href="'.$GLOBALS['bloginfo_url'].'/wp-login.php" rel="nofollow">'. $PPT->_e(array('head','6')).'</a> |  
				<a href="'.$GLOBALS['bloginfo_url'].'/wp-login.php?action=register" rel="nofollow">'.$PPT->_e(array('head','7')).'</a></li>';
			
            }
             
            $string .= '</ul> '; 
			
		   echo $string;
		   ?>
            
            <a href="<?php echo $GLOBALS['premiumpress']['submit_url']; ?>" id="submitButton"><img src="<?php echo $GLOBALS['template_url']; ?>/themes/<?php echo $GLOBALS['premiumpress']['theme']; ?>/images/submitlisting.png" alt="add directory" /></a> 
        
        </div>
        
        
        	</div> <!-- end w_960 -->
            
       </div><!-- end submenubar -->
        
 
		<div id="page" class="clearfix full">
        
        <div class="w_960">
        
        <?php $PPTDesign->AdvancedSearchBox(); ?> 
  
		<?php if(get_option("PPT_slider") =="s1"  && is_home() && !isset($_GET['s']) && !isset($_GET['search-class']) ){ echo $PPTDesign->SLIDER(); } ?>
        
        <?php premiumpress_content_before(); ?> 
        
        <div id="content" <?php $PPTDesign->CSS("padding"); ?>>       	

			<?php
    
                if(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php") &&  !isset($GLOBALS['nosidebar-left']) ){
                
                    include(str_replace("functions/","",THEME_PATH)."/themes/".$GLOBALS['premiumpress']['theme']."/_sidebar1.php");
                
                }elseif(!isset($GLOBALS['nosidebar-left']) ){ ?>                
                
                
                <div id="sidebar-left" class="<?php $PPTDesign->CSS("columns-left"); ?>">
                
                <?php premiumpress_sidebar_left_top(); /* HOOK */ ?> 
                
                <?php if(is_single() && !isset($GLOBALS['ARTICLEPAGE']) && isset($GLOBALS['nosidebar-right']) && get_option("display_listinginfo") =="yes"){  echo $PPTDesign->GetObject('authorinfo'); }
                
                /****************** INCLUDE WIDGET ENABLED SIDEBAR *********************/
                
                if(function_exists('dynamic_sidebar')){ dynamic_sidebar('Left Sidebar (3 Column Layouts Only)');  }
                
                /****************** end/ INCLUDE WIDGET ENABLED SIDEBAR *********************/
                
                if(get_option('advertising_left_checkbox') =="1"){ 
                
                 echo premiumpress_banner("left");
                
                } ?>
                
                <?php premiumpress_sidebar_left_bottom(); /* HOOK */ ?> 
                
                &nbsp;&nbsp; 
                </div>
                
                <!-- end left sidebar -->                
                
           <?php } ?>
			
        <div class="<?php $PPTDesign->CSS("columns"); ?>">
        
        <?php echo $PPTDesign->GL_ALERT($GLOBALS['error_msg'],$GLOBALS['error_type']); ?>
		
        <?php premiumpress_middle_top(); /* HOOK */ ?>  