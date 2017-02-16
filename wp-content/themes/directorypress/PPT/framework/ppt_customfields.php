<?php

/* =============================================================================
  BUILD CUSTOM FIELDS
   ========================================================================== */

if(!function_exists('get_custom_field')) {
	function get_custom_field($field) {
		global $post;
		$custom_field = get_post_meta($post->ID, $field, true);
		echo $custom_field;
	}
}
/* =============================================================================
  PREMIUMPRESS ADMIN INTERGRATION
   ========================================================================== */

function premiumpress_customfields_box() {

	global $pagenow;
	if($pagenow == "widgets.php"){ return; } 

	if( function_exists( 'add_meta_box' )) {

	wp_register_style( 'lightbox', PPT_PATH.'js/lightbox/jquery.lightbox.css');
	wp_enqueue_style( 'lightbox' );
	
	wp_register_script( 'lightbox', PPT_PATH.'js/lightbox/jquery.lightbox.js');
	wp_enqueue_script( 'lightbox' );
	
	wp_register_style( 'fancyboxCSS', PPT_PATH.'css/css.admin.edit.css');
	wp_enqueue_style( 'fancyboxCSS' );	
	
	wp_register_style( 'msgbox', PPT_PATH.'js/msgbox/jquery.msgbox.css');
	wp_enqueue_style( 'msgbox' );
		
	wp_register_script( 'msgbox', PPT_PATH.'js/msgbox/jquery.msgbox.js');
	wp_enqueue_script( 'msgbox' );
		
	wp_register_script( 'tabs', PPT_PATH.'js/jquery.admin.tabs.js');
	wp_enqueue_script( 'tabs' );     
 
	wp_register_script( 'date-pick', PPT_PATH.'js/jquery.date.js');
	wp_enqueue_script( 'date-pick' );  
 
	wp_register_script( 'date-pick1', PPT_PATH.'js/jquery.date_pick.js');
	wp_enqueue_script( 'date-pick1' ); 
	
	wp_register_style( 'date-pick', PPT_PATH.'css/css.date.css');
	wp_enqueue_style( 'date-pick' );
	
	// CHOSEN
	wp_register_style( 'chosen', PPT_PATH.'js/jquery.chosen.css');
	wp_enqueue_style( 'chosen' );

 	wp_register_script( 'chosen', PPT_PATH.'js/jquery.chosen.min.js');
	wp_enqueue_script( 'chosen' ); 	
	
			
	add_meta_box( 'premiumpress_customfields_0', __( 'Listing Options', 'sp' ), 'premiumpress_listing', 'post', 'normal', 'high' );
	add_meta_box( 'premiumpress_customfields_2', __( 'Page Options', 'sp' ), 	'premiumpress_page', 'page', 'normal', 'high' );
	add_meta_box( 'premiumpress_customfields_3', __( 'Compared Product Options', 'sp' ), 'premiumpress_compare', 'ppt_compare', 'normal', 'high' );
	add_meta_box( 'premiumpress_customfields_5', __( 'Article Options', 'sp' ), 'premiumpress_article', 'article_type', 'normal', 'high' );
	
	 
	}
}
 
/* =============================================================================
  COMPARED PRODUCTS INTEGRATION
   ========================================================================== */

function premiumpress_compare(){

global $post, $PPT;
$type = get_post_meta($post->ID, 'type', true);

echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; ?>
 
<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script type="application/javascript">
function ChangeImgBlock(divname){document.getElementById("imgIdblock").value = divname;}

jQuery(document).ready(function() {
jQuery('#upload_g_image').click(function() {
 ChangeImgBlock('g_image');
 formfield = jQuery('#g_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});
window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {

	if(document.getElementById("imgIdblock").value !=""){
	
	 imgurl = jQuery('img',html).attr('src'); 
	 cvbalue = document.getElementById(document.getElementById("imgIdblock").value).value;
	 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl);
	 document.getElementById("imgIdblock").value = "";
	 tb_remove();
	 
	} else {
	
	  window.original_send_to_editor(html);
	
	}   
} 

 
 
});

function PPMsgBox(text){jQuery.msgbox(text, {  type: "info",   buttons: [    {type: "submit", value: "OK"}  ]}, function(result) {  });} 			

</script>
<?php

echo '<script type="text/javascript">jQuery(document).ready(function(){ jQuery(\'.lightbox\').lightbox(); });</script>';
		
echo '<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" />'; 


//echo '<div class="grid400-left" style="margin-left:-5px; margin-right:5px;">';
 
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		if(isset($_GET['pid'])){ 
		$pD = $_GET['pid'];
		}else{
		$pD = get_post_meta($post->ID, 'pID', true);
		}
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Parent Product ID ", 'sp' ) . ' <a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;Enter the POST ID for the product that your setting up the comparison for. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<input type="text" name="field[pID]" value="'.$pD.'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div><div class="clearfix"></div>';
		
		

		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Regular Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[price]" value="'.get_post_meta($post->ID, 'price', true).'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div>';
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Old Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[old_price]" value="'.get_post_meta($post->ID, 'old_price', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';
		
		
		echo '<div class="clearfix"></div><div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link / Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link]" value="'.get_post_meta($post->ID, 'buy_link', true).'" class="ppt-forminput" style="width:350px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';
		
 


//echo '</div><div class="grid400-left last">';

 if(strlen(get_post_meta($post->ID, 'image', true)) > 3){
 
 
	 echo "<div style='width:350px; padding:10px;display:block;'>".premiumpress_image($post->ID,"",array('alt' => $post->post_title,  'width' => '110', 'height' => '110', 'style' => 'max-height:150px; max-width:150px; margin:auto auto; display:block;' ))."</div><div class='clearfix'></div>";
} 

echo '<div class="ppt-form-line"><span class="ppt-labeltext">Image</span>';
echo '<input type="text" name="field[image]" id="g_image" value="'.get_post_meta($post->ID, 'image', true).'" class="ppt-forminput"/> ';

?>

 
<input style="margin-left:140px;" type="button" class="button tagadd" size="36"   value="View Images" onclick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');" />
 
        
<input id="upload_g_image" type="button" class="button tagadd" size="36" name="upload_g_image" value="Upload New Image" />
 </div>
<?php

//echo '</div><div class="clearfix"></div>';

}
/* =============================================================================
  ARTICLE INTEGRATION
   ========================================================================== */

function premiumpress_article(){

global $post;
$type = get_post_meta($post->ID, 'type', true);

echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
?> 
<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script type="application/javascript">
function ChangeImgBlock(divname){document.getElementById("imgIdblock").value = divname;}

jQuery(document).ready(function() {
jQuery('#upload_g_image').click(function() {
 ChangeImgBlock('g_image');
 formfield = jQuery('#g_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});
window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {

	if(document.getElementById("imgIdblock").value !=""){
	
	 imgurl = jQuery('img',html).attr('src'); 
	 cvbalue = document.getElementById(document.getElementById("imgIdblock").value).value;
	 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl);
	 document.getElementById("imgIdblock").value = "";
	 tb_remove();
	 
	} else {
	
	  window.original_send_to_editor(html);
	
	}   
} 

});
</script>
<?php

echo '<script type="text/javascript">jQuery(document).ready(function(){            jQuery(\'.lightbox\').lightbox();          });        </script>';
		
echo '<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" /> <table width="100%"  border="0"><tr width="50%"><td valign="top">';

 

echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Article Image", 'sp' ) . '</span>';
echo '<input type="text" name="field[image]" id="g_image" value="'.get_post_meta($post->ID, 'image', true).'" class="ppt-forminput"/><br /><br />';?>

<a href='javascript:void(0);' onclick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');" class="button tagadd"> View Uploaded Images
</a>
        
<input id="upload_g_image" type="button" class="button tagadd" size="36" name="upload_g_image" value="Upload Image" />
<br />
</fieldset>
<?php
echo '</td><td width="50%" valign="top">';
echo '</td></tr></table>';

} 

/* =============================================================================
  PAGE INTEGRATION
   ========================================================================== */

function premiumpress_page(){

global $post;

	$fea 		= get_post_meta($post->ID, 'width', true);
	if($fea == ""){ $a1 = 'selected'; $a2=""; }else{$a1 = ''; $a2="selected"; } 

	echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' .wp_create_nonce( plugin_basename(__FILE__) ) . '" />'; 		
	echo '<table width="100%"  border="0"><tr width="50%"><td valign="top">';		 	
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Page Width", 'sp' ) . '</span>';
		echo '<select name="field[width]" class="ppt-forminput">
		<option value="" '.$a1.'>inherit from theme</option>
		<option value="full" '.$a2.'>full page</option></select><div class="clearfix"></div></div><br /><br />';
	echo '</td><td width="50%" valign="top">'; 
	echo '</td></tr></table>';
	
?>

<?php

	if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"){  

	//1. GET PACKAGE DATA	
	$nnewpakarray 	= array();
	$packagedata 	= get_option('ppt_membership');
	if(is_array($packagedata) && isset($packagedata['package']) ){ foreach($packagedata['package'] as $val){		
		$nnewpakarray[] =  $val['ID'];		
	} }
	
	//2. GET POST - PACKAGE DATA
	$postpackagedata 	= get_post_meta($post->ID, 'package_access', true);
	if(!is_array($postpackagedata)){ $postpackagedata = array(0); }
	
	?>    
	
	<label style="font-size:14px; line-height:30px;"><img src="<?php echo get_template_directory_uri(); ?>/PPT/img/pakicon.png" align="absmiddle" alt="nr" /> Membership Package Access (not applied to template pages) - <small><a href="admin.php?page=membership">edit packages here</a></small></label>
	<select name="package_access[]" size="2" style="font-size:14px;padding:5px; width:100%; height:150px; background:#e7fff3;" multiple="multiple"  > 
  	<option value="0" <?php if(in_array(0,$postpackagedata)){ echo "selected=selected"; } ?>>All Package Access</option>
    <?php 
	$i=0;
	if(is_array($packagedata) && isset($packagedata['package']) ){
	foreach($packagedata['package'] as $package){	
		
		if(is_array($postpackagedata) && in_array($package['ID'],$postpackagedata)){ 
		echo "<option value='".$package['ID']."' selected=selected>".$package['name']." ( package ID: ".$package['ID'].")</option>";
		}else{ 
		echo "<option value='".$package['ID']."'>".$package['name']." ( package ID: ".$package['ID'].")</option>";		
		}
		
	$i++;		
	} } // end foreach
	
    ?>
	</select>
    <br /><small>Hold CTRL to select multiple packages.</small> 
    
    <?php } ?>
<?php	
	

} 

/* =============================================================================
  LISTING INTEGRATION
   ========================================================================== */
 
function premiumpress_listing(){

	global $post, $PPT;
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){
	
		$uea = get_post_meta($post->ID, 'allowupload', true);
		if($uea == "yes"){ $u1 = 'selected'; $u2=""; }else{$u1 = ''; $u2="selected"; } 
		
		$fea = get_post_meta($post->ID, 'redirect', true);
		if($fea == "yes"){ $a1 = 'selected'; $a2=""; }else{$a1 = ''; $a2="selected"; } 
	
		$fea1 		= get_post_meta($post->ID, 'featured', true);
		if($fea1 == "yes"){ $f1 = 'selected'; $f2=""; }else{$f1 = ''; $f2="selected"; } 
		 
		$tt 		= get_post_meta($post->ID, 'file_type', true);
		if($tt == "free"){ $t1 = 'selected'; $t2=""; $t3=""; }elseif($tt == "cart"){ $t1 = ''; $t2=""; $t3="selected"; }else{$t1 = ''; $t2="selected"; $t3=""; } 	
		
		
		$nou = get_post_meta($post->ID, 'amazon_noupdate', true);
		if($nou == "yes"){ $nou1 = 'selected'; $nou2=""; }else{$nou1 = ''; $nou2="selected"; } 
		
	}else{
	
		$ThisPack 	= get_post_meta($post->ID, 'packageID', true);
		$packdata 	= get_option("packages");
		$cf1 		= get_option("customfielddata"); 
		$tdC =1;  
		
		$couponType = get_post_meta($post->ID, 'type', true);
	
		$fea 		= get_post_meta($post->ID, 'featured', true);	
		if($fea == "yes"){ $a1 = 'selected'; $a2=""; }else{$a1 = ''; $a2="selected"; }
		
	} 

// Use nonce for verification ... ONLY USE ONCE!
echo '<input type="hidden" name="sp_noncename" id="sp_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
  
echo'<script type="text/javascript" charset="utf-8">
Date.firstDayOfWeek = 0;
Date.format = \'yyyy-mm-dd\';
jQuery(function()
{
	jQuery(\'.date-pick\').datePicker()
	jQuery(\'#start-date\').bind(
		\'dpClosed\',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				jQuery(\'#end-date\').dpSetStartDate(d.addDays(1).asString());
			}
		}
	);
	jQuery(\'#end-date\').bind(
		\'dpClosed\',
		function(e, selectedDates)
		{
			var d = selectedDates[0];
			if (d) {
				d = new Date(d);
				jQuery(\'#start-date\').dpSetEndDate(d.addDays(-1).asString());
			}
		}
	);
});
function PPMsgBox(text){
		jQuery.msgbox(text, {  type: "info",   buttons: [    {type: "submit", value: "OK"}  ]}, function(result) {  });
		
		} 			
</script>        
';

echo '<script type="text/javascript">jQuery(document).ready(function(){ jQuery(\'.lightbox\').lightbox(); });</script>';

 
?>
 
 
<input type="hidden" value="" name="imgIdblock" id="imgIdblock" />
<script>

function ChangeImgBlock(divname){
document.getElementById("imgIdblock").value = divname;
}

jQuery(document).ready(function() {

jQuery('#upload_g_featured_image').click(function() {
 ChangeImgBlock('g_featured_image');
 formfield = jQuery('#g_featured_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

jQuery('#upload_g_image').click(function() {
 ChangeImgBlock('g_image');
 formfield = jQuery('#g_image').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
});

<?php $a=0; while($a < 21){ ?>

jQuery('#upload_galimg<?php echo $a; ?>').click(function() {
 ChangeImgBlock('galimg<?php echo $a; ?>');
 formfield = jQuery('#galimg<?php echo $a; ?>').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
}); 
<?php $a++; } ?>	
 
jQuery('#upload_g_images').click(function() {
 ChangeImgBlock('g_images');
 formfield = jQuery('#g_images').attr('name');
 tb_show('', <?php if(defined('MULTISITE') && MULTISITE != false){ ?>'admin.php?page=images&amp;tab=nw&amp;TB_iframe=true'<?php }else{ ?>'media-upload.php?type=image&amp;TB_iframe=true'<?php } ?>);
 return false;
}); 
 

window.original_send_to_editor = window.send_to_editor;
window.send_to_editor = function(html) {

	if(document.getElementById("imgIdblock").value !=""){
	
	 imgurl = jQuery('img',html).attr('src'); 
	 cvbalue = document.getElementById(document.getElementById("imgIdblock").value).value;
	 jQuery('#'+document.getElementById("imgIdblock").value).val(imgurl+","+cvbalue);
	 document.getElementById("imgIdblock").value = "";
	 tb_remove();
	 
	} else {
	
	  window.original_send_to_editor(html);
	
	}   
}



});
</script>


        

<script type="text/javascript">jQuery(function(){jQuery(".ppt_customfields").LeTabs();});</script>

<div id="DisplayImages" style="display:none;"></div><input type="hidden" id="searchBox1" name="searchBox1" value="" />

<div id="ppt-tabs" class="ppt_customfields oldlook">

			<div id="ppt-tabs_tab_container">
				<a class="active">General</a>
<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?><a class="enabled">Attributes</a><?php } ?>
<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?><a class="enabled">File Downloads</a><?php } ?>
<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?><a class="enabled">Affiliate Settings</a><?php } ?>                
<?php if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"  ){ ?><a class="enabled">Custom Fields</a><?php } ?>
<?php if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"  ){ ?><a class="enabled">Packages</a><?php } ?>

<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?><a class="enabled">Shipping</a><?php } ?>

 <a class="enabled">Images</a> 


			</div>
			
			<div id="ppt-tabs_content_container" style="height: 100%; ">
				<div id="ppt-tabs_content_inner">
                
					<div class="ppt-tabs_content" style="left: 0px; "> 
                    
 
<?php 

 
 
 if(strtolower(PREMIUMPRESS_SYSTEM) == "resumepress"){
		
	 
		 
	 
 
}
 
 
 
if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" ){

  echo '<div class="gray_box"><div class="gray_box_content" style="padding:0px; padding-left:10px;">';
		 
		echo '<div style="width:48%;height:40px; float:left;">';
		
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Featured Listing", 'sp' ) . '</span>';
				echo '<select name="field[featured]" class="ppt-forminput">
				<option value="yes" '.$a1.'>yes </option>
				<option value="no" '.$a2.'>no</option></select></div>';
				
		echo '</div>';		
				
		 if(strtolower(PREMIUMPRESS_SYSTEM) != "couponpress" ){
		 
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">Featured Text</span>';
		echo '<input type="text" name="field[featured_text]" value="'.get_post_meta($post->ID, 'featured_text', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		}
		
    echo '<div class="clearfix"></div></div></div> ';	
 
		
}		

if(strtolower(PREMIUMPRESS_SYSTEM) == "moviepress"){

 
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Hits/View Counter", 'sp' ) . '</span>';
		echo '<input type="text" name="field[hits]" value="'.get_post_meta($post->ID, 'hits', true).'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Video Duration ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[duration]" value="'.get_post_meta($post->ID, 'duration', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div>';
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	
	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Video Filename</span>';
		echo '<input type="text" name="field[filename]" id="g_filename" value="'.get_post_meta($post->ID, 'filename', true).'"class="ppt-forminput" /><br /><br />';

		?>
        
        
        
		<a href='javascript:void(0);' onclick="toggleLayer('DisplayImages'); add_video_next(0,'<?php echo get_option("imagestorage_path"); ?>videos/','<?php echo get_option("imagestorage_link"); ?>videos/','g_filename');"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/premiumpress/led-ico/find.png" align="middle"> View Video Files</a> <a href="admin.php?page=images&tab=nw&lightbox[iframe]=true&lightbox[width]=400&lightbox[height]=250"  class="lightbox" target="_blank" style="margin-left:50px;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/premiumpress/led-ico/monitor.png" align="middle"> Upload Video </a> 
		<br />
        
        
        
        1212
        
		<?php
		
		
		echo ' <div class="clearfix"></div> </div>';
		
		echo '</div>';
	
	 
   
} // if moviepress
 
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){
		
		
		echo '<div class="blue_box"><div class="blue_box_content" style="padding:0px; padding-left:10px;">';


		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';	
		
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Featured Product", 'sp' ) . ' <a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;The product will be displayed with a highlighted background in search results. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
				echo '<select name="field[featured]" class="ppt-forminput" style="width:150px;font-size:16px;">
				<option value="yes" '.$f1.'>yes </option>
				<option value="no" '.$f2.'>no</option></select><div class="clearfix"></div></div>';
				
		echo '</div> ';	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
			echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Ribbon Text", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;Will add a pink ribbon to the item display in search results. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
			echo '<input type="text" name="field[ribbon]" value="'.get_post_meta($post->ID, 'ribbon', true).'" class="ppt-forminput" style="width:150px;" />
			<div class="clearfix"></div></div>';
				
		
		echo '</div>';		
		
		echo '<div class="clearfix"></div></div></div>';
		
		
		echo '<div class="green_box"><div class="green_box_content" style="padding:0px; padding-left:10px;">';
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Regular Price (".get_option('currency_symbol').")", 'sp' ) . '</span>';
		echo '<input type="text" name="field[price]" value="'.get_post_meta($post->ID, 'price', true).'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';

		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">Old Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[old_price]" value="'.get_post_meta($post->ID, 'old_price', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';		
		
		echo '</div>';		
		
		
		echo '<div class="clearfix"></div></div></div>';
		
 
		 
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Allow File Uploads", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This option will allow the user to attach a file to the item. This is typically used for custom websites such as t-shirt webistes where you may want the user to attach a file that will be used to create the t-shirt. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<select name="field[allowupload]" class="ppt-forminput" style="width:150px; font-size:16px;">
		<option '.$u1.'>yes</option>
		<option '.$u2.'>no</option></select>		
		<div class="clearfix"></div></div>'; 

 		echo '</div> ';
		

		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("SKU <small>(optional)</small>", 'sp' ) . '</span>';
		echo '<input type="text" name="field[SKU]" value="'.get_post_meta($post->ID, 'SKU', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div>'; 
		
		echo '</div> ';	
		
		
	
	

		


		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';	
			
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Quantity (QTY)", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This is only used if you are managing stock levels. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<input type="text" name="field[qty]" value="'.get_post_meta($post->ID, 'qty', true).'" class="ppt-forminput" style="width:150px;" />
		<div class="clearfix"></div></div>';
					
		echo '</div>';
		
		
		//if(get_option('display_single_related') == "yes"){ 	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("VAT Cost % ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[vat]" value="'.get_post_meta($post->ID, 'vat', true).'" class="ppt-forminput" style="width:150px;" />
		<div class="clearfix"></div> </div>';	 
			
		echo '</div>';	
		 
		//}		
		
		//if(get_option('enable_VAT') == "yes"){ 
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Related Product ID's", 'sp' ) . '</span>';
		echo '<input type="text" name="field[related]" value="'.get_post_meta($post->ID, 'related', true).'" class="ppt-forminput" style="width:150px;"  />
		<div class="clearfix"></div></div>';				
	
		echo '</div> '; 
 
	 
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("View Counter ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[hits]" value="'.get_post_meta($post->ID, 'hits', true).'" class="ppt-forminput" style="width:150px;" />
		<div class="clearfix"></div></div>';	
		 
		echo '</div>'; 
 
	}
		
		
		
		
	if(strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress"){
				
	 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	 		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Property Price ", 'sp' ) . '</span>';
		echo get_option('currency_code').'<input type="text" name="field[price]" value="'.get_post_meta($post->ID, 'price', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';

	echo '</div>';

	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Beds <small>(numeric value)</small>", 'sp' ) . '</span>';
		echo '<input type="text" name="field[bedrooms]" value="'.get_post_meta($post->ID, 'bedrooms', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '<div class="clearfix"></div></div>';

	echo '</div>';	
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Baths <small>(numeric value)</small>", 'sp' ) . '</span>';
		echo '<input type="text" name="field[bathrooms]" value="'.get_post_meta($post->ID, 'bathrooms', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '<div class="clearfix"></div></div>';
		
 	echo '</div>';
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
 
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Listing Type ", 'sp' ) . '</span>';
		echo '<select name="field[listtype]" class="ppt-forminput"><option value="sale"';
		if(get_post_meta($post->ID, 'listtype', true) == "sale"){ echo 'selected'; }
		echo '>For Sale</option>';
		
		echo '<option value="rent"';
		if(get_post_meta($post->ID, 'listtype', true) == "rent"){ echo 'selected'; }
		echo '>For Rent (long term - monthly)</option>';
		
		echo '<option value="rent-short"';
		if(get_post_meta($post->ID, 'listtype', true) == "rent-short"){ echo 'selected'; }
		echo '>For Rent (short term - weekly)</option>';
 
		echo '<option value="lease"';
		if(get_post_meta($post->ID, 'listtype', true) == "lease"){ echo 'selected'; }
		echo '>For Lease</option>';		
		
		echo '<option value="rent-buy"';
		if(get_post_meta($post->ID, 'listtype', true) == "rent-buy"){ echo 'selected'; }
		echo '>Rent To Buy</option>';	
		
		echo '</select></div> ';
		
	echo '</div>';
		
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';	
		
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Listing Status ", 'sp' ) . '</span>';
		echo '<select name="field[liststatus]" class="ppt-forminput">';
 
		echo '<option value="active"';
		if(get_post_meta($post->ID, 'liststatus', true) == "active"){ echo 'selected'; }
		echo '>Active</option>';	
		
		echo '<option value="sold"';
		if(get_post_meta($post->ID, 'liststatus', true) == "sold"){ echo 'selected'; }
		echo '>Sold</option>';		

		echo '<option value="rented"';
		if(get_post_meta($post->ID, 'liststatus', true) == "rented"){ echo 'selected'; }
		echo '>Rented</option>';	

		echo '<option value="leased"';
		if(get_post_meta($post->ID, 'liststatus', true) == "leased"){ echo 'selected'; }
		echo '>Leased</option>';
		
		echo '<option value="pending"';
		if(get_post_meta($post->ID, 'liststatus', true) == "pending"){ echo 'selected'; }
		echo '>Contract Pending</option>';
		
		echo '<option value="vacation"';
		if(get_post_meta($post->ID, 'liststatus', true) == "vacation"){ echo 'selected'; }
		echo '>vacation property</option>';
		 
		
		echo '</select><div class="clearfix"></div></div> ';
		
	 echo '</div>';
		
		}
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "employeepress"){	
		
		
		$JobVisible = get_post_meta($post->ID, 'visible', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Visibility", 'sp' ) . '</span>';
		echo '<select name="field[visible]" class="ppt-forminput"><option ';
				
		if($JobVisible == "public"){ echo "selected=selected"; }		
		echo ' value="public">Public</option><option ';		
		if($JobVisible == "private"){ echo "selected=selected"; }		
 	
		echo ' value="private">Private (Members Only)</option>	 
		</select><div class="clearfix"></div></div>';
		
		echo '</div>';		
		
		
		$JobType = get_post_meta($post->ID, 'positiontype', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Position Type", 'sp' ) . '</span>';
		echo '<select name="field[positiontype]" class="ppt-forminput"><option ';
				
		echo '<option '; if($JobType == "fulltime"){ echo "selected=selected"; } echo ' value="fulltime">Full-time</option>	';  
		
		echo '<option '; if($JobType == "parttime"){ echo "selected=selected"; } echo ' value="parttime">Part-time</option>	';
		
		echo '<option '; if($JobType == "contract"){ echo "selected=selected"; } echo ' value="contract">Contract</option>	';
		
		echo '<option '; if($JobType == "internship"){ echo "selected=selected"; } echo ' value="internship">Internship</option>	';
		
		echo '<option '; if($JobType == "temporary"){ echo "selected=selected"; } echo ' value="temporary">Temporary</option>	';
		  
		echo '</select><div class="clearfix"></div></div>';
		
		echo '</div>';	
		

			
		
		$JobType = get_post_meta($post->ID, 'paytype', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Salary Type", 'sp' ) . '</span>';
		echo '<select name="field[paytype]" class="ppt-forminput"><option ';
				
		echo '<option '; if($JobType == "hourly"){ echo "selected=selected"; } echo ' value="hourly">Hourly</option>	'; 
			
		echo '<option '; if($JobType == "fixed-monthly"){ echo "selected=selected"; } echo ' value="fixed-monthly">Fixed Price (Monthly)</option>	';		
		echo '<option '; if($JobType == "fixed-yearly"){ echo "selected=selected"; } echo ' value="fixed-yearly">Fixed Price (Yearly)</option>	';		
		echo '<option '; if($JobType == "budget"){ echo "selected=selected"; } echo ' value="budget">Budget</option>	';		
		 
		echo '</select><div class="clearfix"></div></div>';
		
		echo '</div>';	
		
		
	 
		
		
		$JobType = get_post_meta($post->ID, 'jobtype', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Job Type", 'sp' ) . '</span>';
		echo '<select name="field[jobtype]" class="ppt-forminput"><option ';
				
		echo '<option '; if($JobType == "bid"){ echo "selected=selected"; } echo ' value="bid">Bidding</option>	'; 
			
		echo '<option '; if($JobType == "resume"){ echo "selected=selected"; } echo ' value="resume">Resumes</option>	';		
		 
		echo '</select><div class="clearfix"></div></div>';
		
		echo '</div>';	
		
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Salary  (Hourly Rate)", 'sp' ) . '</span>';
		echo '<input type="text" name="field[hourly_value]" value="'.get_post_meta($post->ID, 'hourly_value', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';
		
		
		
		$JobStart = get_post_meta($post->ID, 'starting', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Position Available From", 'sp' ) . '</span>';
		echo '<select name="field[starting]" class="ppt-forminput"><option ';
				
		if($JobStart == "hourly"){ echo "selected=selected"; }		
		echo ' value="immediately">immediately</option><option ';		
		if($JobStart == "date"){ echo "selected=selected"; }		
 	
		echo ' value="date">specific date (below)</option>	 
		</select><div class="clearfix"></div></div>';
		
		echo '</div>';	
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Salary (Fixed)", 'sp' ) . '</span>';
		echo '<input type="text" name="field[salary]" value="'.get_post_meta($post->ID, 'salary', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';		
		
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Start Date", 'sp' ) . '</span>';		
		echo '<input type="text" name="field[starting_date]" id="start-date" class="date-pick dp-applied" value="'.get_post_meta($post->ID, 'starting_date', true).'" class="ppt-forminput" /><div class="clearfix"></div><small>Format yyyy-mm-dd</small></div>';
		
		echo '</div>';
		
		
		
		
				
		
		}


		if(strtolower(PREMIUMPRESS_SYSTEM) == "comparisonpress"){		
		
		 		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("SKU <small>(optional)</small>", 'sp' ) . '</span>';
		echo '<input type="text" name="field[SKU]" value="'.get_post_meta($post->ID, 'SKU', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div>'; 
		
		echo '</div> ';		
				
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Regular Price (".get_option('currency_symbol').")", 'sp' ) . '</span>';
		echo '<input type="text" name="field[price]" value="'.get_post_meta($post->ID, 'price', true).'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div>  ';
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Old Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[old_price]" value="'.get_post_meta($post->ID, 'old_price', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div><div class="clearfix"></div> ';
		
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';

	 	
		}
		
				
		if(strtolower(PREMIUMPRESS_SYSTEM) == "auctionpress"){		
		
		 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Listing Status", 'sp' ) . '</span>';
		echo '<select name="field[bid_status]" class="ppt-forminput">';
		
		echo '<option value="open"';
		if(get_post_meta($post->ID, 'bid_status', true) == "option"){ echo 'selected'; }
		echo '>Active (Open for bids)</option>';	
		
		echo '<option value="closed"';
		if(get_post_meta($post->ID, 'bid_status', true) == "closed"){ echo 'selected'; }
		echo '>Temporary Closed (Visible but bidding is disabled)</option>';	
		
		echo '<option value="payment"';
		if(get_post_meta($post->ID, 'bid_status', true) == "payment"){ echo 'selected'; }
		echo '>Pending Payment (Auction ended, winner accepted)</option>';	
		
		echo '<option value="finished"';
		if(get_post_meta($post->ID, 'bid_status', true) == "finished"){ echo 'selected'; }
		echo '>Finished (Auction Ended & Payment Completed)</option>';	
		
 
		echo '</select><div class="clearfix"></div></div>';
		
	echo '</div>';
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Visibility", 'sp' ) . '</span>';
		echo '<select name="field[display]" class="ppt-forminput">
		<option '.$d1.' value="public">Public - Visible to everyone</option>
		<option '.$d2.' value="private">Private - Members Only</option></select><div class="clearfix"></div></div>';
		
	echo '</div>';
	
		$makeoffer = get_post_meta($post->ID, 'makeoffer', true);
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Make Offer", 'sp' ) . '</span>';
		echo '<select name="field[makeoffer]" class="ppt-forminput"><option ';
				
		echo '<option '; if($makeoffer == "yes"){ echo "selected=selected"; } echo ' value="yes">Yes</option>	'; 
			
		echo '<option '; if($makeoffer == "no"){ echo "selected=selected"; } echo ' value="no">No</option>	';		
		 
		 	echo '<option '; if($makeoffer == "pending"){ echo "selected=selected"; } echo ' value="pending">Pending Offer (waiting user acceptance)</option>	';
			
		echo '</select><div class="clearfix"></div></div>';
		
		echo '</div>';	
	
		

	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Current Price  ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[price_current]" value="'.get_post_meta($post->ID, 'price_current', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
		
	echo '</div>';
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Reserve Price ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[price_reserve]" value="'.get_post_meta($post->ID, 'price_reserve', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
		
	echo '</div>';
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
 		
 		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("BIN Price", 'sp' ) . '</span>';
		echo '<input type="text" name="field[price_bin]" value="'.get_post_meta($post->ID, 'price_bin', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
		
	echo '</div>';
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';	
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Bid Count ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[bid_count]" value="'.get_post_meta($post->ID, 'bid_count', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
		
	echo '</div>';	
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Related Product ID's", 'sp' ) . '</span>';
		echo '<input type="text" name="field[related]" value="'.get_post_meta($post->ID, 'related', true).'" class="ppt-forminput" style="width:150px;"  />
		<div class="clearfix"></div></div>';				
	
		echo '</div> '; 
		
		
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';	
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Hightest Bidder ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[bidder_username]" value="'.get_post_meta($post->ID, 'bidder_username', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
		
	echo '</div>';	
	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Bidder User ID", 'sp' ) . '</span>';
		echo '<input type="text" name="field[bidder_ID]" value="'.get_post_meta($post->ID, 'bidder_ID', true).'" class="ppt-forminput" style="width:150px;"  />
		<div class="clearfix"></div></div>';				
	
		echo '</div> '; 
		
	 	
		}
		
	if(strtolower(PREMIUMPRESS_SYSTEM) == "directorypress"){	
		
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Website Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[url]" value="'.get_post_meta($post->ID, 'url', true).'" class="ppt-forminput"/>';	
		echo '<div class="clearfix"></div></div>';	
	
	echo "</div>";	
	
	
			echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';	
		
	echo "</div>";	
	
	}
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress"){
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Coupon Type", 'sp' ) . '</span>';
		echo '<select name="field[type]" class="ppt-forminput"><option ';		
		if($couponType == "coupon"){ echo "selected=selected"; }		
		echo ' value="coupon">Coupon</option><option ';		
		if($couponType == "print"){ echo "selected=selected"; }		
		echo ' value="print">Printable Coupon</option><option ';		
		if($couponType == "offer"){ echo "selected=selected"; }		
		echo ' value="offer">Offer</option>	 
		</select><div class="clearfix"></div></div>';
		
	echo '</div>';	
	
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';		
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Coupon Code ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[code]" value="'.get_post_meta($post->ID, 'code', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';	
		
		echo '</div>';
		
			echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';	
		
	echo "</div>";	
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Start Date", 'sp' ) . '</span>';		
		echo '<input type="text" name="field[starts]" id="start-date" class="date-pick dp-applied" value="'.get_post_meta($post->ID, 'starts', true).'" class="ppt-forminput" /><div class="clearfix"></div><small>Format yyyy-mm-dd</small></div>';
		
		echo '</div>';
		
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("End Date", 'sp' ) . '</span>';
		echo '<input type="text" name="field[pexpires]" id="end-date" class="date-pick dp-applied" value="'.htmlentities(get_post_meta($post->ID, 'pexpires', true)).'" class="ppt-forminput" /><div class="clearfix"></div><small>Format yyyy-mm-dd</small></div>'; 
		
		
		echo '</div>';
		
		
		
	 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Website Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[url]" value="'.get_post_meta($post->ID, 'url', true).'" class="ppt-forminput"/>';	
		echo '<div class="clearfix"></div></div>';	
	
	echo "</div>";	
		

		
		 
		
		}
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "classifiedstheme"){
		
		
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';	
		
		echo "</div>";	
	 	
		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Regular Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[price]" value="'.get_post_meta($post->ID, 'price', true).'" class="ppt-forminput" style="width:150px;"/> ';
		echo '</div> <div class="clearfix"></div>';
		
		echo '</div> ';

		echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">Old Price ('.get_option('currency_symbol').')</span>';
		echo '<input type="text" name="field[old_price]" value="'.get_post_meta($post->ID, 'old_price', true).'" class="ppt-forminput" style="width:150px;"/>';
		echo '</div> <div class="clearfix"></div>';		
		
		echo '</div>';
		
	 
		 
		}
 

?>

</div><!-- end first tab -->

<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?>
<!-- ***********************************************************
LIST BOX SETTINGS
*************************************************************** -->


<div class="ppt-tabs_content" style="left: -750px; ">

            
<?php

		$fff=0;		
		$curencyCode = get_option("currency_code");
		$dd=1; while($dd < 7){
		
		
		echo '<div class="green_box"><div class="green_box_content" style="padding:2px;">';
 
 	
		echo '<div id="cf'.$dd.'" >'; 
		
  		echo '<div>';
		
		$nna = get_option("custom_field".$dd);
		if($nna == ""){$nna = "---"; }
		
		echo ' 
		
		<div style="font-size:18px;padding:2px; padding-bottom:10px; " id="h2'.$dd.'">Listbox '.$dd.': '.$nna.'</div> ';
		
		echo '<div id="customfieldbox'.$dd.'" style="display:none;">
		
		 
		Display Caption: <input name="adminArray[custom_field'.$dd.']" type="text" class="ppt-forminput" value="'.get_option("custom_field".$dd).'" /> <a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd">Save</a> <a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd">Cancel</a>
		 
 
		<input type="checkbox" name="custom_field'.$dd.'_required" value="1"'; if(get_option("custom_field".$dd."_required") =="1"){ print "checked";} echo ' style="margin-left:140px;" />Required User Select A Value 
		
		<hr>
		 </div>
		
		</div>
		'; 
		
		if(strlen(get_option("custom_field".$dd)) > 0){
		
		// SEPERATE THE STRING AND CREATE LIST	  	
		$data1 = get_post_meta($post->ID, 'customlist'.$dd, true); $data1bits = explode(",",$data1); $c=1;
		
		echo '<select name="marks_cust_field'.$dd.'[]" style="width:450px; " multiple="" tabindex="3" id="sel'.$dd.'" data-placeholder="Choose a Country..."  class="chzn-select"> ';	
		
		foreach($data1bits as $value){ 
		
			if(strlen($value) > 0){
			
				if($dd == 3 || $dd == 6){ 
				$gg = explode("=",$value);   $value = $gg[1]; $pricebit = $gg[0];
				echo '<option selected=selected value="'.$gg[0]."=".$value.'">'.$gg[1].' ('.$curencyCode.$gg[0].')</option>';
				}else{
				echo '<option selected=selected value="'.$value.'">'.$value.'</option>';
				}	
					
			} // end if
		
		} // end foreach
		
		echo '</select>';
		
		}
		
		// INPUT BUTTON
		if($dd == 3 || $dd == 6){ $btnaa = 'pptinputbox1'; }else{ $btnaa = 'pptinputbox'; }
		
		
		echo '<div class="clearfix"></div> </div>  <div  style="margin-top:10px; margin-bottom:10px;"><a href="javascript:void(0);" onclick="'.$btnaa.'(\'sel'.$dd.'\');"  class="button tagadd">Add Listbox Value</a>
		<a href="javascript:void(0);" onclick="toggleLayer(\'customfieldbox'.$dd.'\');toggleLayer(\'h2'.$dd.'\');"  class="button tagadd">Change Title Value</a> <a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;<b>How do listbox attributes work?</b><br>This section let\'s you setup listbox values for website visitors to personalize product selections. Example, product colors and sizes. <br><br><b>Title Value</b><br> The title value is simply a display caption for your listbox. This title will be used for ALL listbox '.$dd.' fields on your website.  <br><br><b>Add Listbox Value</b><br /> Here you add a new value for the user to select. For example, if you enter the value \'red\' this will allow the user to select the value red on the product page. <br><br><b>Listbox 3 and 6</b><br/> These two listbox values are different because they offer you the extra option of attaching a price value to the listbox. For example, if the user selected a value \'red\' you can also apply an extra pricing value to this selection. (Red = extra $100) All extra prices values MUST be unique.  &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="margin-left:10px;" /></a>';
	
		echo '</div>'; 
			
			
		echo '<div class="clearfix"></div></div></div>';
		
		$dd++;$fff=0;
		} // end for loop

?>

<script>
function pptinputbox(div){

	jQuery.msgbox("Enter a new listbox value below: ", {
	  type: "prompt"
	}, function(result) {
	  if (result) {
	  jQuery('#'+div).append('<option value="'+result+'" selected="selected">'+result+'</option>');
		jQuery('#'+div).trigger("liszt:updated");
	  }
	});	

} 
function pptinputbox1(div){

	jQuery.msgbox("Enter a new listbox value and price below:", {
	  type: "prompt",
	   inputs  : [
      	{type: "text", label: "Display Caption:", value: "example", required: true},
      	{type: "text", label: "Price: (must be unique and not blank) (<?php echo $curencyCode; ?>)", value: "100", required: true}
    	],
	}, function(r1, r2) {
	  if (r1) {
	  
	   jQuery('#'+div).append('<option value="'+r2+'='+r1+'" selected="selected">'+r1+'(<?php echo $curencyCode; ?>'+r2+')</option>');	   
		jQuery('#'+div).trigger("liszt:updated");
	  }
	});	

} 
</script>
<div class="clearfix"></div>
</div>
<?php } ?>



<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?>
<!-- ***********************************************************
FILE DOWNLOAD SETTINGS
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px; ">

<?php


echo '<div class="green_box"><div class="green_box_content" style="padding:2px;">';
 

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Filename", 'sp' ) . '</span>';
		echo '<input type="text" name="field[file]" value="'.get_post_meta($post->ID, 'file', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
	 
		echo get_option('file_type').'<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Download Access ", 'sp' ) . '</span>';
		echo '<select name="field[file_type]" class="ppt-forminput">
		<option value="free" '.$t1.'>free download</option>
		<option value="paid" '.$t2.'>paid download</option>
		<option value="cart" '.$t3.'>add to cart (download after purchase)</option>
		</select><div class="clearfix"></div></div> '; 
		
echo '<div class="clearfix"></div></div></div>';

 echo "<div class='clearfix'></div>
  <a href=\"admin.php?page=setup&testdownloadID=".$post->ID."\" target='_blank' class='button tagadd'>Test Download Link</a> ";  
     

?>
</div>
<?php } ?>

<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){  ?>
<!-- ***********************************************************
AFFILIATE SETTINGS
<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;123 &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a>
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px; ">

<?php




echo '<div class="green_box"><div class="green_box_content">';


echo "<p class='titlep'>Amazon Product Data</p> <p>If you are importing Amazon products the imported data will be displayed below.You do not need to fill in these fields manually they are auto completed when you import products using the import tool.</p><hr>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon ASIN ", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_guid]" value="'.get_post_meta($post->ID, 'amazon_guid', true).'" class="ppt-forminput"/>';		
		echo '<div class="clearfix"></div></div>';
echo "</div>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon Button Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_link]" value="'.get_post_meta($post->ID, 'amazon_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Dont Update Price", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This option tells the Amazon price update tool NOT to update this products price. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<select name="field[amazon_noupdate]" class="ppt-forminput">
		<option '.$nou1.'>yes</option>
		<option '.$nou2.'>no</option></select><div class="clearfix"></div></div>'; 
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Amazon Reviews Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[amazon_reviews_link]" value="'.get_post_meta($post->ID, 'amazon_reviews_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	
	

echo '<div class="clearfix"></div></div></div>';



	 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Website Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[url]" value="'.get_post_meta($post->ID, 'url', true).'" class="ppt-forminput"/>';	
		echo '<div class="clearfix"></div></div>';	
	
	echo "</div>";	
		
	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Affiliate Link", 'sp' ) . '</span>';
		echo '<input type="text" name="field[link]" value="'.get_post_meta($post->ID, 'link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';	
		
	echo "</div>";	
		
 
	
echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';


		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Redirect Visitor", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This option will redirect the user to the buy_link instead of showing the product page. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<select name="field[redirect]" class="ppt-forminput">
		<option '.$a1.'>yes</option>
		<option '.$a2.'>no</option></select><div class="clearfix"></div></div>';
		
echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

		
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 1", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This is a custom buy link. Enter a http:// link here for the user to click on to buy this product. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span></span>';

		echo '<input type="text" name="field[buy_link]" value="'.get_post_meta($post->ID, 'buy_link', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
		
echo "</div>";	
		
echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
		
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 2", 'sp' ) . '<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;This is a custom buy link. Enter a http:// link here for the user to click on to buy this product. &quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a></span>';
		echo '<input type="text" name="field[buy_link1]" value="'.get_post_meta($post->ID, 'buy_link1', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
 
 echo "</div>";	
 
 echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

 
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 3", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link2]" value="'.get_post_meta($post->ID, 'buy_link2', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';
 
 
echo "</div>";

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
	
 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 4", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link3]" value="'.get_post_meta($post->ID, 'buy_link3', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';

echo "</div>"; 

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
 
 		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Buy Link 5", 'sp' ) . '</span>';
		echo '<input type="text" name="field[buy_link4]" value="'.get_post_meta($post->ID, 'buy_link4', true).'" class="ppt-forminput"/>';
		echo '<div class="clearfix"></div></div>';		
			
echo "</div>";	

?>

</div>	
<?php } ?>


<?php if( strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"){ ?>
<!-- ***********************************************************
CUSTOM FIELDS
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px; ">
					
<?php

 	$i=1; while($i < 50){
		
		if(isset($cf1[$i]['name']) && strlen($cf1[$i]['name']) > 0){ 		
			$Value= get_post_meta($post->ID, $cf1[$i]['key'], true);
			
			// make package string
			$td = "";
			if(isset($cf1[$i]['pack1']) && $cf1[$i]['pack1'] == 1){ $td .= strip_tags($packdata[1]['name'])."<br />"; }
			if(isset($cf1[$i]['pack2']) && $cf1[$i]['pack2'] == 1){ $td .= strip_tags($packdata[2]['name'])."<br />"; }
			if(isset($cf1[$i]['pack3']) && $cf1[$i]['pack3'] == 1){ $td .= strip_tags($packdata[3]['name'])."<br />"; }
			if(isset($cf1[$i]['pack4']) && $cf1[$i]['pack4'] == 1){ $td .= strip_tags($packdata[4]['name'])."<br />"; }
			if(isset($cf1[$i]['pack5']) && $cf1[$i]['pack5'] == 1){ $td .= strip_tags($packdata[5]['name'])."<br />"; }
			if(isset($cf1[$i]['pack6']) && $cf1[$i]['pack6'] == 1){ $td .= strip_tags($packdata[6]['name'])."<br />"; }
			if(isset($cf1[$i]['pack7']) && $cf1[$i]['pack7'] == 1){ $td .= strip_tags($packdata[7]['name'])."<br />"; }
			if(isset($cf1[$i]['pack8']) && $cf1[$i]['pack8'] == 1){ $td .= strip_tags($packdata[8]['name'])."<br />"; }
			
			if($cf1[$i]['type'] !="textarea"){ $ffc = "height:60px;"; }else{$ffc = "";  }
					
			echo '<div style="width:48%;'.$ffc.' margin-bottom:10px; float:left;"> <div class="ppt-form-line"><span class="ppt-labeltext" style="width:160px;">' .stripslashes($cf1[$i]['name']) . '
			
			<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;<p>This field will be displayed for the following packages</p><p>'.$td.'</p>&quot;);"><img src="'.PPT_FW_IMG_URI.'help.png" style="float:right;" /></a>
			
			</span>';
			 
			
			switch($cf1[$i]['type']){
				 case "textarea": {
					echo '<textarea class="adfields" name="'.$cf1[$i]['key'].'" style="font-size:14px;padding:5px; width:95%; height:40px;">';
					echo $Value;
					echo '</textarea>';
				 } break;
				 case "list": {
					$listval = $Value; 
					$listvalues = explode(",",$cf1[$i]['value']);
					echo '<select name="'.$cf1[$i]['key'].'" class="ppt-forminput">';
					foreach($listvalues as $value){ 
						if($listval ==  $value){ 
						echo '<option value="'.$value.'" selected>'.$value.'</option>'; 
						}else{
						echo '<option value="'.$value.'">'.$value.'</option>'; 
						}
					}
					echo '</select>';		
		
				 } break;
				 default: {
					echo '<input type="text" class="ppt-forminput" name="'.$cf1[$i]['key'].'" size="55" maxlength="100" value="'.$Value.'">';
				 }	
				} 

				echo '<input type="hidden"  name="custom['.$i.'][name]" value="'.$cf1[$i]['key'].'" /><div class="clearfix"></div></div> </div>'; 
			}
			
			$i++;
		}
 
echo '<div class="clearfix"></div><div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

	echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("View Counter ", 'sp' ) . '</span>';
	echo '<input type="text" name="field[hits]" value="'.get_post_meta($post->ID, 'hits', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';	 

echo "</div>";	

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

 	echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Google Map Location", 'sp' ) . '</span>';
	echo '<input type="text" name="field[map_location]" value="'.get_post_meta($post->ID, 'map_location', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
	
	
	
echo "</div>";		

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

 	echo '<div class="ppt-form-line"><span class="ppt-labeltext">Tagline</span>';
	echo '<input type="text" name="field[tagline]" value="'.get_post_meta($post->ID, 'tagline', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
	
	
	
echo "</div>";


if(strtolower(PREMIUMPRESS_SYSTEM) == "directorypress"){ 

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';

 	echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Listing Email", 'sp' ) . '</span>';
	echo '<input type="text" name="field[email]" value="'.get_post_meta($post->ID, 'email', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
	
echo "</div>";	

}
	 
?>

</div><!-- end custom fields --> 
<?php } ?>

<?php if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"  ){ ?>					

<!-- ***********************************************************
PACKAGES SETUP
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px; "> 
<?php

echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Package", 'sp' ) . '</span>';

		echo '<select name="field[packageID]" class="ppt-forminput"><option value="">---------</option>';
		
		$pp=1; 
		$ThisPack = get_post_meta($post->ID, 'packageID', true);		
		while($pp < 10){		
		if(isset($packdata[$pp]['name']) && strlen($packdata[$pp]['name']) > 0){ echo '<option value="'.$pp.'"'; if($ThisPack == $pp){ echo 'selected'; } echo '>'.$packdata[$pp]['name'].'</option>'; }
		$pp++;
		}	
		echo '</select><div class="clearfix"></div></div>';
		
echo "</div>";				
		
		if(strtolower(PREMIUMPRESS_SYSTEM) != "moviepress"){
		
			echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
		
			echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Purchased Price", 'sp' ) . '</span>';
			echo '<input type="text" name="field[purchaseprice]" value="'.get_post_meta($post->ID, 'purchaseprice', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
			
			echo "</div>";
	
			if(strtolower(PREMIUMPRESS_SYSTEM) == "directorypress"){
			
			echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
			
			echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Reciprocal link", 'sp' ) . '</span>';
			echo '<input type="text" name="field[reclink]" value="'.get_post_meta($post->ID, 'reclink', true).'" class="ppt-forminput" /><div class="clearfix"></div></div>';
			
			echo "</div>";
			 
			}	
				
		 	echo '<div style="width:48%;height:40px; margin-bottom:10px; float:left;">';
			
				if(is_numeric(get_post_meta($post->ID, 'expires', true)) && strlen(get_post_meta($post->ID, 'expires', true)) > 0){
			$EPDate = date('Y-m-d h:i:s',strtotime(date("Y-m-d h:i:s", strtotime($post->post_date )) . " +".get_post_meta($post->ID, 'expires', true)." days"));
			echo "<small>Set to expire on ".$EPDate."</small>";
			}
			
			echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Day's Till Expire", 'sp' ) . '</span>';
			echo '<input type="text" name="field[expires]" value="'.get_post_meta($post->ID, 'expires', true).'" class="ppt-forminput" />';			
			
		
			
			echo '<div class="clearfix"></div></div></div>';
		
		}


		
		?>
</div><!-- end packages tab -->
<?php } ?>





<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"){ ?>
<!-- ***********************************************************
SHIPPING OPTIONS jQuery('.smallBox').clone().insertAfter('#newshippingcountrylist');
*************************************************************** -->
<div class="ppt-tabs_content" style="left: -750px;">




<div class="green_box"><div class="green_box_content">


 <p class="titlep">Product Shipping Attributes</p>
 
 <p>Here you enter the product attributes used within the shipping calculations.</p>
 
 <hr />

<?php
 
 	 
		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Weight (".get_option('shipping_weight_metric').")", 'sp' ) . '</span>';
		echo '<input type="text" name="field[weight]" value="'.get_post_meta($post->ID, 'weight', true).'" class="ppt-forminput" style="width:50px;" />
				<div class="clearfix"></div></div>';

		echo '<div class="ppt-form-line"><span class="ppt-labeltext">' . __("Dimensions (cm)", 'sp' ) . '</span>';
		echo 'L:<input type="text" name="field[length]" value="'.get_post_meta($post->ID, 'length', true).'" class="ppt-forminput" style="width:50px; margin-left:10px;" /> 
		W:<input type="text" name="field[width]" value="'.get_post_meta($post->ID, 'width', true).'" class="ppt-forminput" style="width:50px;margin-left:10px;" /> 
		H:<input type="text" name="field[height]" value="'.get_post_meta($post->ID, 'height', true).'" class="ppt-forminput" style="width:50px; margin-left:10px; " />
	 
		<div class="clearfix"></div></div>';			
 
?>
</div></div>








 <div class="blue_box"><div class="blue_box_content">

 <p class="titlep">Item Based Shipping</p>
 
 <p>Here you can enter an additional shipping cost for this item. It will be added ontop of all other shipping calculations at checkout and is applied to each item.</p>
 
 <hr />


 <?php

echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">' . __("Fixed Price (".get_option('currency_symbol').") ", 'sp' ) . '</span>';
echo '<input type="text" name="field[shipping]" value="'.get_post_meta($post->ID, 'shipping', true).'" class="ppt-forminput" style="width:150px;" />
		<div class="clearfix"></div></div>';	

?>
 </div></div>
 
<div class="blue_box"><div class="blue_box_content">

 <p class="titlep">Country Shipping Price</p>
 
 <p>Here you can add an additional shipping price based on the shipping country selected at checkout. Select a country from the list to setup extra shipping values.</p>
 
 <hr />


 <table width="100%" border="0">
  <tr>
    <td>
    
 <select style="width:200px;height:100px;" multiple="multiple" onchange="jQuery('#selv').html(this.value);GetCountryShip(this.value, '<?php echo $post->ID; ?>', 'AJAXCOUNTRYSHIP','<?php echo str_replace("http://","",PPT_THEME_URI); ?>/PPT/ajax/');">
<?php include(str_replace("functions/","",THEME_PATH)."_countrylist.php"); ?>
</select>
   
    </td>
    <td style="width:100%;" valign="top">
 
 

<div id="selv" style="font-size:16px; font-weight:bold;"></div> 
<div id="AJAXCOUNTRYSHIP"></div>
 
  </td></tr></table>

</div></div>

 
 
<div class="clearfix"></div>
</div>
<?php } ?>








 
<!-- ***********************************************************
 IMAGES TAB SETUP
*************************************************************** -->

<div class="ppt-tabs_content" style="left: 650px; ">

		
<?php 
if(strlen(get_post_meta($post->ID, 'image', true)) > 3){

echo premiumpress_image($post->ID,"",array('alt' => $post->post_title,  'link' => 'self', 'link_class' => 'lightbox', 'width' => '110', 'height' => '110', 'style' => 'max-height:50px; max-width:50px; float:right; border:1px solid #ddd; padding:1px;' ));
 
}

		echo '<div class="green_box"><div class="green_box_content" style="padding:0px; padding-left:10px;">';

 		
?>

    <div class="ppt-form-line nob">
    <span class="ppt-labeltext"> Display Image</span> 
    <input type="text" name="field[image]" id="g_image" value="<?php echo get_post_meta($post->ID, 'image', true); ?>" class="ppt-forminput"/>
    <input onclick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_image');" type="button" class="button tagadd" value="View Images">
    <input id="upload_g_image" type="button" class="button tagadd" size="36" name="upload_g_image" value="Upload Image" />
    </div>
    
    <?php echo '<div class="clearfix"></div></div></div>'; ?>
         

	<?php if(strtolower(PREMIUMPRESS_SYSTEM) != "moviepress"){	
 
	if(strlen(get_post_meta($post->ID, 'featured_image', true)) > 3){
	echo "<a href='".premiumpress_image_check(get_post_meta($post->ID, 'featured_image', true))."' target='_blank'><img src='".premiumpress_image_check(get_post_meta($post->ID, 'featured_image', true))."' style='max-height:50px; max-width:50px; float:right; border:1px solid #ddd; padding:1px;' /></a>";	
	
	
	
	}
	
	
	
    ?>
    
    	<?php echo '<div class="gray_box"><div class="gray_box_content" style="padding:0px; padding-left:10px;">'; ?>
 
 		<div class="ppt-form-line">
        <span class="ppt-labeltext">Featured Image
        
        	<a href="javascript:void(0);" onmouseover="this.style.cursor=\'pointer\';" 
onclick="PPMsgBox(&quot;<p>The featured image is only used for the home page sliders and will replace the default image if set.</p>&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>/help.png" style="float:right;" /></a>
			
        </span>
		<input type="text" name="field[featured_image]" id="g_featured_image" value="<?php echo get_post_meta($post->ID, 'featured_image', true); ?>" class="ppt-forminput"/>
        
        <input onclick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','g_featured_image');" type="button" class="button tagadd" value="View Images">       
        <input id="upload_g_featured_image" type="button" class="button tagadd" size="36" name="upload_g_featured_image" value="Upload Image" />
      	<div class="clearfix"></div></div>
        
        <?php echo '<div class="clearfix"></div></div></div> '; ?>
        
        
        
        
        
      
		<?php
		
	 
		$images = get_post_meta($post->ID, 'images', true);
		if(strlen($images) > 1){ $mimg = explode(",",get_post_meta($post->ID, 'images', true));}else{ $images=""; $mimg=""; }
		 if(!is_array($mimg)){ $mimg= array(); }
		
		$crrent_total = count($mimg);
		$ff = 0;
		while($ff < (20+$crrent_total) ){ 
		 
		
			if(isset($mimg[$ff]) && strlen($mimg[$ff]) > 3 || $ff < 1){
			echo '<div id="gbg'.$ff.'"><div class="green_box"><div class="green_box_content" style="padding:0px; padding-left:10px;">';
			}else{
			echo '<div id="gbg'.$ff.'" style="display:none;"><div class="green_box"><div class="green_box_content" style="padding:2px;">'; // 
			}
			
			$num = $ff+1;
			
			if(isset($mimg[$ff]) && strlen(trim($mimg[$ff])) > 3){
			echo "<a href='".premiumpress_image_check($mimg[$ff],"full")."' class='lightbox'><img src='".premiumpress_image_check($mimg[$ff],"full")."' style='max-height:50px; max-width:50px; float:right; border:1px solid #ddd; padding:1px; margin-top:7px;'></a>";
			}
			
			
		echo '<div class="ppt-form-line nob"><span class="ppt-labeltext">Gallery Image '.$num;
		
			if(isset($mimg[$ff]) && strlen(trim($mimg[$ff])) > 3){
			$bv = $mimg[$ff];
			
			}else{
			$bv="";
			}  
		echo '</span><input type="text" name="galimg[]" value="'.$bv.'" class="ppt-forminput" id="galimg'.$ff.'"/>';?>
		
		<input onclick="toggleLayer('DisplayImages'); add_image_next(0,'<?php echo get_option("imagestorage_path"); ?>','<?php echo get_option("imagestorage_link"); ?>','galimg<?php echo $ff; ?>');" type="button" class="button tagadd" value="View Images"	>
		<input id="upload_galimg<?php echo $ff; ?>" type="button" class="button tagadd" size="36" name="upload_galimg<?php echo $ff; ?>" value="Upload Image" />
        
        
		<?php	
		 
		$trynext = $ff+1;
		if(isset($mimg[$trynext]) && strlen($mimg[$trynext]) > 3){ }else{
		$divH = $ff*100+400;
		
		echo ' </div>
		
		<div id="BUTCF'.$ff.'" >		
		<a href="javascript:void(0);" onclick="toggleLayer(\'gbg'.$trynext.'\');toggleLayer(\'BUTCF'.$ff.'\');document.getElementById(\'ppt-tabs_content_container\').style.height = \''.$divH.'px\'"   class="button tagadd">Add New Gallery Image</a>
		';
		  
		}
		
		echo '<div class="clearfix"></div></div></div> </div>   </div> ';
 
		
		$ff++;
		}  
		

} // end moviepress 
		 
 
		echo '<div class="clearfix"></div>';
		 echo "</div>";
			
		
		?>
 
</div><!-- end images tab -->






  
 



 
<div class="clearfix"></div>
</div>   
<p><input type="submit" value="Save Changes" class="button-primary"  /> </p>
</div> 
<div class="clearfix"></div>







<?php

	if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress"){  

	//1. GET PACKAGE DATA	
	$nnewpakarray 	= array();
	$packagedata 	= get_option('ppt_membership');
	if(is_array($packagedata) && isset($packagedata['package']) ){ foreach($packagedata['package'] as $val){		
		$nnewpakarray[] =  $val['ID'];		
	} }
	
	//2. GET POST - PACKAGE DATA
	$postpackagedata 	= get_post_meta($post->ID, 'package_access', true);
	if(!is_array($postpackagedata)){ $postpackagedata = array(0); }
	
	?>    
	
	<label style="font-size:14px; line-height:30px;"><img src="<?php echo get_template_directory_uri(); ?>/PPT/img/pakicon.png" align="absmiddle" alt="nr" /> Membership Package Access - <small><a href="admin.php?page=membership">edit packages here</a></small></label>
	<select name="package_access[]" size="2" style="font-size:14px;padding:5px; width:100%; height:150px; background:#e7fff3;" multiple="multiple"  > 
  	<option value="0" <?php if(in_array(0,$postpackagedata)){ echo "selected=selected"; } ?>>All Package Access</option>
    <?php 
	$i=0;
	if(is_array($packagedata) && isset($packagedata['package']) ){
	foreach($packagedata['package'] as $package){	
		
		if(is_array($postpackagedata) && in_array($package['ID'],$postpackagedata)){ 
		echo "<option value='".$package['ID']."' selected=selected>".$package['name']." ( package ID: ".$package['ID'].")</option>";
		}else{ 
		echo "<option value='".$package['ID']."'>".$package['name']." ( package ID: ".$package['ID'].")</option>";		
		}
		
	$i++;		
	} } // end foreach
	
    ?>
	</select>
    <br /><small>Hold CTRL to select multiple packages.</small> 
    
    <?php } ?>

<script type="text/javascript"> jQuery(".chzn-select").chosen(); jQuery(".chzn-select-deselect").chosen({allow_single_deselect:true}); </script>

<?php
 

} 
 
 

 


 

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

function premiumpress_postdata($post_id, $post) {

global $wpdb, $post;
		
	if ( isset($_POST['sp_noncename']) && !wp_verify_nonce( $_POST['sp_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}   
 
 
	// Is the user allowed to edit the post or page?
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post->ID ))
		return $post->ID;
	} else {
		if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	}
	
	

	$mydata = array();
 
	// CUSTOM FIELDS
	if(is_array($_POST['field']) && !empty($_POST['field']) ){
		foreach($_POST['field'] as $key=>$val){ 
			if(!is_array($val) && substr($val,-1) == ","){			
				$mydata[$key] = substr($val,0,-1);	
			}else{
				$mydata[$key] = $val;	
			}						
		}	
	}	
 
	// PACKAGE ACCESS
	if(isset($_POST['package_access'])){
 
	 update_post_meta($post->ID, "package_access", $_POST['package_access']);
	} 
	
	// CUSTOM FIELDS
	if(isset($_POST['custom']) && is_array($_POST['custom']) && !empty($_POST['custom']) ){
		foreach($_POST['custom'] as $in_array){	
			$mydata[$in_array['name']] = $_POST[$in_array['name']];				
		}	
	} 

	
	foreach ($mydata as $key => $value) {
		if( $post->post_type == 'revision' ) return;
		$value = implode(',', (array)$value); 
		if(get_post_meta($post->ID, $key, FALSE)) { 
			update_post_meta($post->ID, $key, $value);
		} else { 
			add_post_meta($post->ID, $key, $value);
		}
		if(!$value) delete_post_meta($post->ID, $key);
	}
	
	// IMAGES	
	if(is_array($_POST['galimg'])){
	$sString = "";
	foreach($_POST['galimg'] as $img){ if(strlen($img) > 0){
	$sString .= $img.",";
	} }
	update_post_meta($post->ID, 'images', $sString);
	}
	
	
	if(isset($_POST['field']['qty'])){	
	  
		if(isset($_POST['custom_field1_required'])){ $pack1_v=1; }else{ $pack1_v=0; }
		 
		update_option("custom_field1_required", $pack1_v);
 
		if(isset($_POST['custom_field2_required'])){ $pack1_b=1; }else{ $pack1_b=0; }
		update_option("custom_field2_required", $pack1_b);
		 
		
		if(isset($_POST['custom_field3_required'])){ $pack1_b=1; }else{ $pack1_b=0; }
		update_option("custom_field3_required", $pack1_b);
		
		if(isset($_POST['custom_field4_required'])){ $pack1_b=1; }else{ $pack1_b=0; }
		update_option("custom_field4_required", $pack1_b);
		
		if(isset($_POST['custom_field5_required'])){ $pack1_b=1; }else{ $pack1_b=0; }
		update_option("custom_field5_required", $pack1_b);	
			
		if(isset($_POST['custom_field6_required'])){ $pack1_b=1; }else{ $pack1_b=0; }
		update_option("custom_field6_required", $pack1_b);			
		
	}
	
	
	// UPDATE POST DATA
	$update_options = $_POST['adminArray']; 
	if(is_array($update_options )){
	foreach($update_options as $key => $value){
		update_option( trim($key), trim($value) );
	} }

 	
	// CUSTOM SELECTION BOXES
	if(isset($_POST['adminArray'])){ 
	
		$f=1;
		while($f < 7){
		
			//customfielddata	
			$sString = "";
			if(isset($_POST['marks_cust_field'.$f]) && is_array($_POST['marks_cust_field'.$f]) ){ // 
				
				$o=0; 
				foreach($_POST['marks_cust_field'.$f] as $val){ 
					
					$sString .= trim($val).",";						 
					$o++;
				} // end foreach
					
			} // end if				
			
			update_post_meta($post->ID, 'customlist'.$f, $sString);
			$f++;
			 
		}  // end while
	
	}
	
	
	
	if(isset($_POST['pts_post_type']) && strlen($_POST['pts_post_type']) > 1){	
	 
		if(isset($post->post_type) && $post->post_type != $_POST['pts_post_type']){ 
			mysql_query("UPDATE ".$wpdb->prefix."posts SET post_type='".$_POST['pts_post_type']."' WHERE ID='".$post->ID."' LIMIT 1");
		}
	   
	}	

}

/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */

add_action('admin_menu', 'premiumpress_customfields_box');
add_action('save_post',  'premiumpress_postdata', 1, 2);

















/* ============================ PREMIUM PRESS CUSTOM FIELD FUNCTION ======================== */


add_action('manage_posts_custom_column', 'premiumpress_custom_column', 10, 2);


function ppt_remove_columns($defaults) {

if(isset($_GET['post_type']) ){

}else{

unset($defaults['title']);
unset($defaults['categories']);
unset($defaults['date']);
unset($defaults['comments']);
unset($defaults['author']);
unset($defaults['tags']);

}
 
    return $defaults;
}
add_filter('manage_posts_columns', 'ppt_remove_columns');

//if(!isset($_GET['action'])){
add_action('admin_head', 'hidey_admin_head');
//}

function MoveProducts(){
?>
<form action="" method="post" name="movemenow" id="movemenow">
<input name="movePID" id="movePID" type="hidden" value="" />
<input name="movetoPID" id="movetoPID" type="hidden" value="" />
</form>
<?php

}
if(strtolower(PREMIUMPRESS_SYSTEM) == "comparisonpress"){
add_action('admin_footer', 'MoveProducts');
}


function HeaderPOSTData(){

	global $wpdb;
	// MOVE THE POST TO THE NEW SUB POST LOCATION
	if(isset($_POST['movePID']) && is_numeric($_POST['movePID']) ){
	 
	// UPDATE POST TYPE
	$SQL = "UPDATE $wpdb->posts SET post_type='ppt_compare' WHERE ID='".$_POST['movePID']."' LIMIT 1"; 
	mysql_query($SQL);
	add_post_meta($_POST['movePID'], 'pID', $_POST['movetoPID']);
	
	
	}

}
add_action('wp_loaded', 'HeaderPOSTData');









function hidey_admin_head() {



    echo '<style type="text/css">';
	echo '.sorting-indicator { display:none !important; }';
    echo 'table #image { width:80px; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/simages.png) 8px 8px no-repeat; border-right:1px solid #ddd; border-left:1px solid #ddd; }';
	echo 'table #qty { width:80px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/cqty.png) 8px 8px no-repeat; border-right:1px solid #ddd;  } ';
	echo 'table #bids { width:80px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/auctionpress.png) 8px 8px no-repeat; border-right:1px solid #ddd;  } ';

	echo 'table #found { width:100px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/comparisonpress.png) 8px 8px no-repeat; border-right:1px solid #ddd;  } ';

	echo 'table #title { width:25%; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/ctitle.png) 8px 8px no-repeat;   }';
	echo 'table #rptype { width:10%; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/chouse.png) 8px 8px no-repeat; border-right:1px solid #ddd;   }';
	echo 'table #cptype { width:10%; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/cptype.png) 8px 8px no-repeat; border-right:1px solid #ddd;   }';


	
	echo 'table #pak { width:15%; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/cpak.png) 8px 8px no-repeat; border-right:1px solid #ddd;   }';
	echo 'table #categories { width:10%; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/ccat.png) 8px 8px no-repeat;    }';

	echo 'table #price { width:70px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/cprice.png) 8px 8px no-repeat; border-right:1px solid #ddd;  }';
	echo 'table #hits { width:60px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/c1.png) 8px 8px no-repeat; border-right:1px solid #ddd;  }';

	echo 'table #ID { 100px; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/cid.png) 8px 8px no-repeat;border-right:1px solid #ddd;  }';
	echo 'table #SKU { 100px; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/csku.png) 8px 8px no-repeat;border-right:1px solid #ddd;  }';

	echo 'table #date { width:100px; padding-left:26px; background:url('.get_template_directory_uri().'/PPT/img/admin/cdate.png) 8px 8px no-repeat;  }';

	echo '.column-price, .column-hits, .column-qty, .column-bids  { width:100px; font-size:10px; text-align:center; border-right:1px solid #ddd !important; } .column-SKU, .column-found, .column-title,.column-categories,.column-pak, .column-cptype, .column-ID,.column-rptype{ border-right:1px solid #ddd !important; } ';
	echo 'table #userphoto { width:60px;  }';
	echo '.column-image { border-right:1px solid #ddd !important; border-left:1px solid #ddd !important; align:center; } .column-image { width:80px !important; } .column-image img { margin-left:auto; margin-right:auto; display:block; }';
    //echo '#hits { width:80px; padding-left:30px; background:url('.get_template_directory_uri().'/PPT/img/admin/simages.png) 8px 10px no-repeat; }';
	
	// PHOTO
	echo '.pptphoto { max-width:50px; max-height:50px; }';
	//SKU
	echo '.column-ID, .column-SKU { width:60px; font-size:10px !important; text-align:center; } .column-found { text-align:center; }'; 
    echo '</style>';
}

function ppt_custom_columns($defaults) { 

	if(isset($_GET['post_type']) ){
	
		if($_GET['post_type'] == "ppt_compare"){
		
		//$defaults['image'] 		= 'Image';
		
		}
	
		return $defaults;
		
	}else{
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "auctionpress"){
	
	$defaults['image'] 		= 'Image';
	$defaults['price'] 		= "Price";
	$defaults['hits'] 		= 'Views';
	$defaults['bids'] 		= "Bids";
	$defaults['title'] 		= 'Name';
 	$defaults['categories'] = 'Categories';	
    $defaults['apexpire'] 	= "Auction Status";
	$defaults['ID'] 		= "ID"; 
	$defaults['date'] 		= 'Date';	
 

	return $defaults;
	
	}	
	

 
	if(strtolower(PREMIUMPRESS_SYSTEM) == "employeepress1"){
	
	$defaults['title'] 		= 'Name';
	$defaults['eptype'] 	= 'Job Type';
	$defaults['epstatus'] 	= 'Job Status';
	$defaults['date'] 		= 'Date';
	return $defaults;
	
	}	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "comparisonpress"){
	
	$defaults['image'] 		= 'Image';
	$defaults['price'] 		= "Price";
	$defaults['hits'] 		= 'Views';
	$defaults['found'] 		= 'Similar';
	$defaults['title'] 		= 'Name';
 	$defaults['categories'] = 'Categories';	
	$defaults['ID'] 		= "ID"; 
	$defaults['SKU'] 		= 'SKU';
	$defaults['date'] 		= 'Date';	
	return $defaults;
	
	}	
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "resumepress"){
	
	$defaults['userphoto'] 		= 'Image';
	
	}else{
	
	$defaults['image'] 		= 'Image';
	
	}
	
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress"  || strtolower(PREMIUMPRESS_SYSTEM) == "classifiedstheme" || strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress"){
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress" && get_option("display_ignoreQTY") == "yes"){ $defaults['qty'] 		= __('QTY');  }	 
	
	$defaults['price'] 		= "Price";
	}
	$defaults['hits'] 		= 'Views';	
	$defaults['title'] 		= 'Name';
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "realtorpress"){
	$defaults['rptype'] 	= 'Listing Type';
	}
	
	if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress"){
	$defaults['cptype'] 	= 'Coupon Type';
	}
	
	$defaults['categories'] = 'Categories';
 
	
	if(strtolower(PREMIUMPRESS_SYSTEM) != "comparisonpress" && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && get_option('pak_enabled') == 1){ $defaults['pak'] 		= "Package"; }
	
	$defaults['ID'] 		= "ID";
	if(strtolower(PREMIUMPRESS_SYSTEM) == "shopperpress" ){
	$defaults['SKU'] 		= 'SKU';
	}
	
 
	
	$defaults['date'] 		= 'Date';
 		
	}
    return $defaults;
}
add_filter( 'manage_posts_columns', 'ppt_custom_columns' );

function price_column_register_sortable( $columns ) {
	$columns['ID'] 		= 'ID';
	$columns['hits'] 	= 'Views';
 	$columns['price'] 	= 'price';
	$columns['qty'] 	= 'qty';
 	$columns['cptype'] 	= 'cptype';
	$columns['found'] 	= 'found';
	$columns['bids'] 	= 'bid_count';
	return $columns;
}
add_filter( 'manage_edit-post_sortable_columns', 'price_column_register_sortable' );
function price_column_orderby( $vars ) {

	if ( isset( $vars['orderby'] ) ) {
	
	if('views' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'hits','orderby' => 'meta_value_num',	'order' => $_GET['order']) );

	}elseif ( 'price' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'price','orderby' => 'meta_value_num',	'order' => $_GET['order']) );
	
	}elseif ( 'qty' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'qty','orderby' => 'meta_value_num',	'order' => $_GET['order']) );
		
	}elseif ( 'cptype' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'type', 'order' => $_GET['order']) );
		
	}elseif ( 'found' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'found', 'order' => $_GET['order']) );

	}elseif ( 'bid_count' == $vars['orderby'] ){
	
		$vars = array_merge( $vars, array(	'meta_key' => 'bid_count', 'order' => $_GET['order']) );
		
				
	}	
		
		
	}
 
	return $vars;
}
add_filter( 'request', 'price_column_orderby' );

function premiumpress_custom_column($column_name, $post_id) {

global $wpdb, $PPT, $post; 
 
if(strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" || strtolower(PREMIUMPRESS_SYSTEM) != "comparisonpress"){ $PACKAGE_OPTIONS = get_option("packages"); }

// SWITCH THE LIST OF COLUMNS	

 
switch($column_name){

	case "title": {  } break; // automatic
	case "ID": {  echo $post_id; } break;	
	case "SKU": {  echo get_post_meta($post_id, "SKU", true); } break;	
	case "bids": {  echo get_post_meta($post_id, "bid_count", true); } break;	
	case "found": {  echo get_post_meta($post_id, "found", true);	
 
	echo "<br> <a href='post-new.php?post_type=ppt_compare&pid=".$post_id."' style='background-color:yellow;font-size:8px;'>Add Comparison</a>
	<input type='hidden' value='".$post_id."' name='moveme'>
	<input name='newcompareID' id='newcompareID".$post_id."' onfocus=\"this.value='';\" value='post ID' type='text' style='width:40px;font-size:9px;' />
	<input type='button' value='move' onclick=\"document.getElementById('movetoPID').value=document.getElementById('newcompareID".$post_id."').value;document.getElementById('movePID').value='".$post_id."';document.movemenow.submit();\"   />
	";
	 
	
	} break;
	
	case "eptype": { echo get_post_meta($post_id, "jobtype", true); } break;	
	case "epstatus": { echo get_post_meta($post_id, "jobstatus", true); } break;	

	
	case "rutype": { echo get_post_meta($post_id, "type", true); } break;
	
	case "price": {  
		
		if(strtolower(PREMIUMPRESS_SYSTEM) == "auctionpress"){
		
		$price = get_post_meta($post_id, "price_current", true);
		echo premiumpress_price($price,$CurrencySymbol,get_option('display_currency_position'),1);
		
		}else{
		
		$CurrencySymbol = get_option("currency_symbol");
		$price = get_post_meta($post_id, "price", true);
		$old_price = get_post_meta($post_id, "old_price", true);
		
		if ( !empty( $price ) ) {
		
		echo premiumpress_price($price,$CurrencySymbol,get_option('display_currency_position'),1);
 
		if($old_price !=""){
		echo " <br /> <strike>".premiumpress_price($old_price,$CurrencySymbol,get_option('display_currency_position'),1)."</strike>";
		}
		} else {
			echo "Free!";
		}
		}	
	
	} break;
	
	case "cptype": {
	
		$couponType = get_post_meta($post_id, 'type', true);
		if($couponType == "coupon"){ echo "Coupon <br><small>".get_post_meta($post_id, 'code', true)."</small>"; }
		if($couponType == "print"){ echo "Printable Coupon"; }
		if($couponType == "offer"){ echo "Offer"; }
	
	} break;	
	case "rptype": {
	
		if(get_post_meta($post_id, 'listtype', true) == "sale"){ echo 'For Sale'; }	 
		else if(get_post_meta($post_id, 'listtype', true) == "rent"){ echo 'For Rent (long term)'; }		 
		else if(get_post_meta($post_id, 'listtype', true) == "rent-short"){ echo 'For Rent (short term )'; }		 
		else if(get_post_meta($post_id, 'listtype', true) == "lease"){ echo 'For Lease'; }
	 
	
	} break;
	
	case "qty": { 
	
		$qty = get_post_meta($post_id, "qty", true);
		if ( !empty( $qty ) ) { echo $qty." in Stock"; }else{ echo "<strike>out of stock</strike>"; }	
	
	} break;
	
	case "apexpire": { 
	 
		$bidStatus = get_post_meta($post_id, "bid_status", true);
		
		switch($bidStatus){
		
		case "open": {
		
			$expires = get_post_meta($post_id, "expires", true);
			 
			echo "<b>Active Auction</b><br />";
			if($expires !=""){
			print "<small style='color:green; font-size:13px;'>".$expires." day listing</small>";
			}	
		
		}  break;
		
		case "closed": {
		
		print "<b style='color:blue;'>Temporary Closed<br><small>(Visible but bidding is disabled)</small></b>";
		
		}  break;
		
		case "payment": {
		
		print "<b style='color:blue;'>Ended - Pending Delivery</b>";
		
		}  break;
		
		case "finished": {
			print "<b style='color:orange;'>Completed - Item Delivered</b>";
		}  break;
		
		 default: { echo $bidStatus; }
		}
		
	
	} break;
	
	case "pak": { 
	
			$pak = get_post_meta($post_id, "packageID", true);
			if ( !empty( $pak ) ) {

				print strip_tags($PACKAGE_OPTIONS[$pak]['name']);
			} else {
				echo 'No Package Set';  //No Taxonomy term defined
			}	
	
	} break;

	case "hits": { 
	 
	
		echo get_post_meta($post_id, "hits", true);
		
	} break;
	
	case "image": {	
	
		 
		echo "<a href='post.php?post=".$post_id."&action=edit'>".premiumpress_image($post_id,"",array('alt' => "",  'width' => '50', 'height' => '50', 'style' => 'max-height:50px; max-width:50px; padding:1px; border:1px solid #ddd;' ))."</a>";		 
	} break;
	
	case "userphoto": { 
	  
	  	// GET USER PHOTO
        $img = get_user_meta($post->post_author, "pptuserphoto",true);
		if($img == ""){
			$img = get_avatar($post->post_author,52);
		}else{
			$img = "<img src='".get_option('imagestorage_link').$img."' class='photo' alt='user ".$post->post_author."' style='max-width:50px; max-height:50px;' />";
		}
		 
		echo $img;
		
	} break;
	
	case "tax": {
	
	
	
	} break;
	
 	
	}	 // end switch

} 

?>