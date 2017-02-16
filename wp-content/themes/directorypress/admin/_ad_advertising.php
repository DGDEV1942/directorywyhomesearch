<?php 

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 

global $PPT;
PremiumPress_Header();  ?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_advertising.png" align="middle"> Advertising</h3>		 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()"><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/youtube.png" align="middle" style="margin-top:-10px;"> Help Me</a>						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" <?php if(!isset($_POST['ad_zone'])){ ?>class="active"<?php } ?>>General Banners</a></li>
 
	<li><a rel="premiumpress_tab5" href="#" <?php if(isset($_POST['ad_zone'])){ ?>class="active"<?php } ?>>Per Category Banners</a></li>
</ul>
</div>



<form method="post"  target="_self">
<input name="submitted" type="hidden" value="yes" />
<input type="hidden" name="advertise" value="1">
<div id="premiumpress_tab1" class="content">



<div class="grid400-left">

<fieldset>
<div class="titleh"> <h3>Header Advertisement - 468 x 60</h3></div> 
<div class="ppt-form-line">	
<input type="checkbox" class="checkbox" name="advertising_top_checkbox" value="1" <?php if(get_option("advertising_top_checkbox") =="1"){ print "checked";} ?> /> Enable Display
<p>Copy/Paste Banner Code Blow</p>	
<textarea name="adminArray[advertising_top_adsense]" class="ppt-forminput" style="width:380px; height:150px;"><?php echo stripslashes(get_option("advertising_top_adsense")); ?></textarea>
<div class="clearfix"></div>
</div> 
</fieldset>


<fieldset>
<div class="titleh"> <h3>Footer Advertisement - 300 x 300</h3></div> 
<div class="ppt-form-line">	
<input type="checkbox" class="checkbox" name="advertising_footer_checkbox" value="1" <?php if(get_option("advertising_footer_checkbox") =="1"){ print "checked";} ?> /> Enable Display
<p>Copy/Paste Banner Code Blow</p>	
<textarea name="adminArray[advertising_footer_adsense]" class="ppt-forminput" style="width:380px; height:150px;"><?php echo stripslashes(get_option("advertising_footer_adsense")); ?></textarea>
<div class="clearfix"></div>
</div> 
</fieldset>
 
 
</div>
<div class="grid400-left last">

<fieldset>
<div class="titleh"> <h3>Left Sidebar Advertisement - 250 x 250</h3></div> 
<div class="ppt-form-line">	
<input type="checkbox" class="checkbox" name="advertising_left_checkbox" value="1" <?php if(get_option("advertising_left_checkbox") =="1"){ print "checked";} ?> /> Enable Display
<p>Copy/Paste Banner Code Blow</p>	
<textarea name="adminArray[advertising_left_adsense]" class="ppt-forminput" style="width:380px; height:150px;"><?php echo stripslashes(get_option("advertising_left_adsense")); ?></textarea>
<div class="clearfix"></div>
</div> 
</fieldset>

<fieldset>
<div class="titleh"> <h3>Right Sidebar Advertisement - 250 x 250</h3></div> 
<div class="ppt-form-line">	
<input type="checkbox" class="checkbox" name="advertising_right_checkbox" value="1" <?php if(get_option("advertising_right_checkbox") =="1"){ print "checked";} ?> /> Enable Display
<p>Copy/Paste Banner Code Blow</p>	
<textarea name="adminArray[advertising_right_adsense]" class="ppt-forminput" style="width:380px; height:150px;"><?php echo stripslashes(get_option("advertising_right_adsense")); ?></textarea>
<div class="clearfix"></div>
</div> 
</fieldset>



</div>  
<div class="clearfix"></div>

 
 <div class="savebarb clear">
 
 <input class="premiumpress_button" type="submit" value="Save Changes" style="color:#fff;" onclick="document.getElementById('showThisTab').value=1" />
 
 
 </div>

</div> 
</form>


<div id="premiumpress_tab5" class="content">

<form method="post" name="ad" target="_self">


 

<?php if(isset($_POST['ad_zone']) ){ ?>
<input name="submitted" type="hidden" value="yes" />
<?php }else{ ?>
<input type="hidden" name="ad_zone" value="1">
<?php } ?>

<div class="grid400-left">

<fieldset>
<div class="titleh"> <h3>Per Category (Right Sidebar) - 250 x 250</h3></div> 
<?php if(!isset($_POST['ad_zone']) ){ ?>
<div class="ppt-form-line">	
<span class="ppt-labeltext">Select Category</span>	
 	<select name='cat' onchange="document.ad.submit();">
	<option value='-1'>Select One</option>
	 <?php 

		$catlist="";
 		$Maincategories = get_categories('use_desc_for_title=1&hide_empty=0&hierarchical=0');		
		$Maincatcount = count($Maincategories);	 
		foreach ($Maincategories as $Maincat) { 
		if($Maincat->parent ==0){		
 
			$catlist .= '<option value="'.$Maincat->term_id.'">';
			$catlist .= $Maincat->cat_name;
			$catlist .= " (".$Maincat->count.')';
			$catlist .= '</option>';

				$currentcat=get_query_var('cat');
				$categories= get_categories('child_of='.$Maincat->cat_ID.'&amp;depth=1&hide_empty=0');
 
				$catcount = count($categories);		
				$i=1;
	
				if(count($categories) > 0){
				$catlist .="<ul>";
					foreach ($categories as $cat) {		
			
						$catlist .= '<option value="'.$cat->term_id.'"> ---> ';
						$catlist .= $cat->cat_name;
						$catlist .= '</option>';						 
						$i++;		
					}
				 
				}
			
		} 
 }
print $catlist;
 ?>
</select>
<div class="clearfix"></div>
</div>
<?php }elseif(isset($_POST['ad_zone']) && is_numeric($_POST['ad_zone']) ){ ?>
 
<div class="ppt-form-line">	 
<p>Copy/Paste Banner Code Blow</p>	
<textarea name="adminArray[advertising_zone_<?php echo $_POST['cat']; ?>]" class="ppt-forminput" style="width:300px; height:150px;"><?php echo premiumpress_bannerZone($_POST['cat']); ?></textarea>
<div class="clearfix"></div>
</div> 
<p><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></p>
<?php } ?>
</fieldset>


</div>
 <div class="clearfix"></div>
</div>
           
</form>				 
                        
 
</div>
<div class="clearfix"></div>

 