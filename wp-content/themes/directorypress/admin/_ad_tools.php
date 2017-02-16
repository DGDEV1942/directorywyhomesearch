<?php if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } global $PPT; PremiumPress_Header(); global $PPT, $wpdb;   


	 
	/*$PPTImport 		= new PremiumPressTheme_Import;
	 
	$PPTImport->FEEDIMPORT('hourly');
	
	die("asd");*/
 	 

?>

<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_setup.png" align="middle"> Tools</h3>  						 
<ul>
	<li><a rel="premiumpress_tab1" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="1"){ echo 'class="active"';}elseif(!isset($_POST['showThisTab'])){ echo 'class="active"'; } ?>>CSV Import</a></li>
    <li><a rel="premiumpress_tab2" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="2"){ echo 'class="active"';} ?>>Category/Taxonomy</a></li>
    <?php if(strtolower(PREMIUMPRESS_SYSTEM) == "directorypress"){ ?> 
    <li><a rel="premiumpress_tab3" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="3"){ echo 'class="active"';} ?>>Database</a></li>
    <li><a rel="premiumpress_tab4" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="4"){ echo 'class="active"';} ?>>Domz</a></li>
    <li><a rel="premiumpress_tab5" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="5"){ echo 'class="active"';} ?>>Broken Links</a></li>
    <?php } ?>
    <li><a rel="premiumpress_tab6" href="#" <?php if(isset($_POST['showThisTab']) && $_POST['showThisTab'] =="6"){ echo 'class="active"';} ?>>Feed Import</a></li>
    
</ul>
</div>

<div id="premiumpress_tab1" class="content"> 


<div class="grid400-left">


<fieldset>
<div class="titleh"> <h3>CSV File Import</h3>  </div>

<form class="fields" method="post" target="_self" enctype="multipart/form-data"  style="padding:10px;">
<input name="csvimport" type="hidden" value="yes" />
 <input type="hidden" value="" name="showThisTab" id="showThisTab" />

<input type="file" name="import"  style="width: 350px; font-size:14px;">
 

<div class="ppt-form-line">	
<span class="ppt-labeltext"><b>OR</b> Select CSV File

<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>How does this work?</b><br>If you are having problems importing large CSV files to your hosting account it maybe easier to upload them via FTP instead. <br><br><b>Where should i put my CSV file?</b></br> Upload your CSV file to your 'thumbs' folder within your theme installation, if uploaded correctly it will be displayed in this list.<br>  Your thumbs folder path: <br /> <small><?php print get_option("imagestorage_path"); ?>thumbs/</small> &quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>

</span>	 
<select   name="file_csv" class="ppt-forminput" style="width:200px;">
<option value="0">-- --- NO CSV FILE -- -- </option>
		<?php
	   
		$path = FilterPath();
	    $HandlePath =  $path."wp-content/themes/".strtolower(constant('PREMIUMPRESS_SYSTEM'))."/thumbs/";
 		$HandlePath = str_replace("//","/",str_replace("wp-admin","",$HandlePath));

	    $count=1;
		if($handle1 = opendir($HandlePath)) {
      
	  	while(false !== ($file = readdir($handle1))){	

		if(substr(strtolower($file),-4) ==".csv"){
	
		?>
			<option value="<?php echo $file; ?>"><?php echo $file; ?></option>
		<?php
		} }}
		?>	 
</select>
<div class="clearfix"></div>
<br  />
<a href="javascript:void(0);" onclick="toggleLayer('mops');" style="float:right;">more options</a>

</div>    
 


<div style="display:none;" id="mops">



<div class="ppt-form-line">	
<span class="ppt-labeltext">Import Type</span>	 
<select   name="type" class="ppt-forminput"><option value="posts" selected>Wordpress Posts</option><option value="users">Wordpress Members</option></select>
<div class="clearfix"></div>
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Column Delimiter</span>	 
<input name="del" type="text" class="ppt-forminput"  value="," size="5">
<div class="clearfix"></div>
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Enclosure</span>	 
<input name="enc" type="text" class="ppt-forminput"  value="/" size="5">
<div class="clearfix"></div>
</div>
<div class="ppt-form-line">	
<span class="ppt-labeltext">Column Headings</span>	 
<select   name="heading"><option value="yes">Yes</option><option value="no" selected>No</option></select>
<div class="clearfix"></div>
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Remove Quotes</span>	 
<select   name="rq"><option value="yes">Yes</option><option value="no" selected>No</option></select>
<div class="clearfix"></div>
</div>
 
 
<br />





<label><b>Select which category to add imported items too.</b></label>
<p class="ppnote">Note: You only need to select a category if your CSV file doesn't include a category already.</p>
<div style="background:#eee; padding:8px;">
<select name="csv[cat][]" multiple="multiple" style="width:350px;">
<?php echo premiumpress_categorylist(explode(",",get_option('article_cats')),false,false,"category",0,true); ?></select><br />
<small>Hold CTRL to select multiple values</small>
<div class="clearfix"></div>   
</div>	 
 
</div>
 <div class="clearfix"></div>
 
<div class="ppt-form-line">          
<input class="premiumpress_button" type="submit" value="<?php _e('Start Import','cp')?>" style="color:white;" />
 </div>
 

</form>


</fieldset>




 <fieldset >
<legend><strong>Download CSV File</strong></legend>
<p>Click the link below to download a ready formatted CSV file for all the existing products/listings on your website.</p>

<p><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/import.png" align="absmiddle" /> <a href="?downloadcsv=1">Download CSV File</a></p>
 <br />
 
<div class="msg msg-info">
  <p>
<b>Note.</b> Before re importing this file, save it as a <u>Windows Format</u> CSV (comma delimited) file using excel.</p>
</div> 
 </fieldset>







</div>
<div class="grid400-left last">
 

<div class="videobox" id="videobox1">
<a href="javascript:void(0);" onclick="PlayPPTVideo('9mWID9hD4hI','videobox1');"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/video/2.jpg" align="absmiddle" /></a>
</div>



</div>




<div class="clearfix"></div>
</div>











<div id="premiumpress_tab2" class="content"> 


<div class="grid400-left">
<fieldset>
<div class="titleh"> <h3>Category/Taxonomy Import Tool</h3>  </div>
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="submitted" type="hidden" value="yes" />
<input name="catme" type="hidden" value="yes" />
<input type="hidden" value="2" name="showThisTab" id="showThisTab" />
<p class="ppnote">Creating lots of categories is time consuming, this tools allows you to create lots of categories quickly. Enter a list of categories below, separating each with a comma. Eg. cat1,cat2,cat3</p>
<textarea name="cats" style="height:100px;width:350px;" class="ppt-forminput"></textarea><br />

<?php /*<label>Parent Category</label>
<small>Select a parent category below if you would like the list to be created as sub categories, leave blank to create parent categories.</small><br />
<select name="pcat" style="width: 250px;" class="ppt-forminput" >
<option value="0">------------</option>
<?php echo premiumpress_categorylist(); ?>
</select> */ ?>

<label>Import Into: </label>
 <select name="tax" style="width: 250px;" class="ppt-forminput" >
<option value="category">Category List</option>
<option value="location">Country/State/City List</option>

<?php if(strtolower(PREMIUMPRESS_SYSTEM) == "couponpress"){ ?> 
 <option value="store">Store List</option>
 <option value="network">Network List</option>
<?php } ?> 
 
</select>


<br /><br />
<input class="premiumpress_button" type="submit" value="Create Categories" style="color:white;" />
 
 <hr />
 
 
<p class="ppnote1">Note. If importing sub-categories you need to refresh the WordPress AFTER import. Do this by simply editing the parent category after import and clicking save, the save process will refresh the WordPress cache and show your sub categories.</p>
</form>
 
</fieldset>
</div>
 
<div class="grid400-left last">
 <div class="videobox" id="videobox2">
<a href="javascript:void(0);" onclick="PlayPPTVideo('IHXvN8kUDAY','videobox2');"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/video/3.jpg" align="absmiddle" /></a>
</div>
</div>


<div class="clearfix"></div>
</div>











<div id="premiumpress_tab3" class="content"> 


<div class="grid400-left">
<fieldset>
<div class="titleh"><h3>Database Import Tool</h3></div> 
 
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="premiumpress_import" type="hidden" value="yes" />
 <input type="hidden" value="3" name="showThisTab" id="showThisTab" />
<p>Remember, the system you select below must be installed onto the SAME database as your Wordpress installation.</p>
<legend><strong>Select Your System</strong></legend>

<label><input name="system" type="radio" value="esyndicate" checked="checked"> eSyndicate</label>
<small>More information about esyndicat.com can be <a href="http://www.intelliants.com/affiliates/xp.php?id=16800" target="_blank">found here.</a></small><br />

<label><input name="system" type="radio" value="phpld"> phpLD</label>
<small>More information about phplinkdirectory can be <a href="http://www.phplinkdirectory.com" target="_blank"> found here.</a> </small><br />

<label><input name="system" type="radio" value="phplinkbid"> php Link Bid</label>
<small>More information about phplinkbid can be <a href="http://www.phplinkbid.com" target="_blank">found here.</a></small><br />

<label><input name="system" type="radio" value="linkbidscript"> Link Bid Script</label>
<small>More information about phplinkbid can be <a href="http://www.linkbidscript.com/" target="_blank">found here.</a></small><br />


<label><input name="system" type="radio" value="edirectory"> eDirectory</label>
<small>More information about eDirectory can be <a href="http://www.edirectory.com" target="_blank">found here.</a></small><br />

<label><input name="system" type="radio" value="edirectory1"> eDirectory *New Releases*</label>
<small>More information about eDirectory can be <a href="http://www.edirectory.com" target="_blank">found here.</a></small><br />

  
<label>Table Prefix</label>
<input type="text" name="table_prefix" value=""><br />
<small>If you have installed your database with a prefix, enter it above.</small><br />
 

<input class="premiumpress_button" type="submit" value="Import Database" style="color:white;" onclick="document.getElementById('showThisTab').value=3" />
 
</form>
 </fieldset>
</div>

<div class="grid400-left last">

 
<div class="videobox" id="videobox553" >
<a href="javascript:void(0);" onclick="PlayPPTVideo('D5wqyrkamdc','videobox553');"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/video/9.jpg" align="absmiddle" /></a>
</div> 
 
</div>


<div class="clearfix"></div>
</div>






<div id="premiumpress_tab4" class="content"> 


<div class="grid400-left">
<fieldset>
<div class="titleh"><h3>Dmoz Import Tool</h3></div> 
<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="domz" type="hidden" value="yes" />
 <input type="hidden" value="4" name="showThisTab" id="showThisTab" />

<div class="ppt-form-line">	
<span class="ppt-labeltext">DMOZ Link </span>	 
 
<input type="text" name="domz_link" value="" class="ppt-forminput">
<div class="clearfix"></div>
</div>
 
			<p class="ppnote">Select the link from the dmoz website to extract the content, example: http://www.dmoz.org/Arts/Animation/</p>



 <p>Import Into Which Category?</p>
 
 <select name="import[cat][]" multiple="multiple" style="width:350px;">
<?php echo premiumpress_categorylist(explode(",",get_option('article_cats')),false,false,"category",0,true); ?></select><br />
       <small>Hold CTRL to select multiple values</small>
<div class="clearfix"></div>       
 
<input class="premiumpress_button" type="submit" value="Import Now" style="color:white;" onclick="document.getElementById('showThisTab').value=4" />
 
</form> 
</fieldset> 




</div>

<div class="grid400-left last">

 

</div>


<div class="clearfix"></div>
</div>
 


<div id="premiumpress_tab5" class="content"> 


<div class="grid400-left">
<fieldset>
<div class="titleh"><h3>Broken Link Checker </h3></div>  

<form class="fields" style="padding:10px;" method="post" target="_self" >
<input name="borken_links" type="hidden" value="yes" />
 <input type="hidden" value="5" name="showThisTab" id="showThisTab" />

<?php if(isset($_POST['borken_links'])){ ?>
<table width="100%"  border="0">
  <tr style="background:#666; height:30px;">
    <td style="color:#fff; width:55px; text-align:center;">&nbsp;ID</td>
    <td style="color:#fff;">&nbsp;Title / Link</td>
    <td style="color:#fff;">&nbsp;Edit</td>
  </tr>
<?php
global $wpdb;
$SQL = "SELECT $wpdb->posts.post_title, $wpdb->posts.ID, $wpdb->posts.post_title, $wpdb->postmeta.*
		FROM $wpdb->posts, $wpdb->postmeta
		WHERE $wpdb->postmeta.meta_key ='url' AND ( $wpdb->posts.ID = $wpdb->postmeta.post_id )	";
 
$result = mysql_query($SQL);
while ($row = mysql_fetch_assoc($result)) { 
$error =  checkDomainAvailability($row['meta_value']);
if($error != 1){
?>
  <tr>
    <td style="text-align:center;"><?php echo $row['ID']; ?></td>
    <td><?php echo $row['post_title']; ?> <br /> <?php echo $error; ?> <a href="<?php echo $row['meta_value']; ?>" target="_blank">+</a></td>
    <td><a href="post.php?action=edit&post=<?php echo $row['ID']; ?>">Edit</a></td>
  </tr>
<?php } } ?>
</table>

<?php }else{ ?>
<input class="premiumpress_button" type="submit" value="Check for broken links" style="color:white;" onclick="document.getElementById('showThisTab').value=4" />
<?php } ?>
 
</form>

</fieldset>
</div>

<div class="grid400-left last">

 


</div>

<div class="clearfix"></div>

</div> 

<div id="premiumpress_tab6" class="content"> 

<div id="runfeedbox" style="display:none;">
<div class="yellow_box"><div class="yellow_box_content">
<img src="<?php echo PPT_FW_IMG_URI; ?>/loading.gif" style="float:left; padding-right:30px; padding-bottom:80px;" /> 

<h3>Feed Import Started, Please Wait...</h3>
<p>Feed imports can take along time to process depending on how big the feed file is.</p>
<p><b>Note</b> If you experience any time-out errors such as the page goes blank, white, or you get the message 'Fatal error: Allowed memory size..' you need to contact your hosting provider and ask them to increase your hosting account memory limits.</p>
<div class="clearfix"></div>
</div></div>
</div>

<div class="grid400-left" id="feedboxleft">



<form method="post" target="_self" name="runFeed" id="runFeed">
<input name="runFeedID" id="runFeedID" type="hidden" value="" /> 
 <input type="hidden" value="6" name="showThisTab" id="showThisTab" />
</form>
<form method="post" target="_self" enctype="multipart/form-data">
<input name="submitted" type="hidden" value="yes" />
<input name="submit" type="hidden" value="1" />
 <input type="hidden" value="6" name="showThisTab" id="showThisTab" />

<?php

// STUPID BUT NEEDED FOR CUSTOM FIELDS
 $querystr = "
    SELECT $wpdb->posts.ID 
    FROM $wpdb->posts 
    WHERE $wpdb->posts.post_status = 'publish' 
	AND $wpdb->posts.post_type = 'post' 
    ORDER BY $wpdb->posts.post_date DESC LIMIT 1 ";

 $POO = $wpdb->get_results($querystr, ARRAY_A);
 

 
$selectArray=array();
$selectArray[0] = "---------------";
$selectArray['post_title'] 		= "Post Default - Title";
$selectArray['post_content'] 	= "Post Default - Content (big description)";
$selectArray['post_excerpt'] 	= "Post Default - Excerpt (short description";
$selectArray['post_status'] 	= "Post Default - Status (publish/draft)";
$selectArray['post_type'] 		= "Post Default - Type (post/article/faq)";
$selectArray['post_author'] 	= "Post Default - Author (1)";
$selectArray['category'] 		= "Post Default - Category";
$selectArray['image'] 			= "Post Default - Image";
$selectArray['post_date'] 		= "Post Default - Date";
$selectArray[1] = "---------------";

if(is_numeric($POO[0]['ID'])){
$custom_field_keys = get_post_custom_keys($POO[0]['ID']);
if(is_array($custom_field_keys)){
  foreach ( $custom_field_keys as $value ) { 
    $valuet = trim($value);
      if ( '_' == $valuet{0} )
      continue;
	  $selectArray[$valuet] = "Custom Field - ".$valuet."";
    
  } 
}
}
$selectArray['price'] 			= "Custom Field - Price (NOW)";
$selectArray['old_price'] 		= "Custom Field - Old Price (WAS)";
$selectArray['link'] 			= "Custom Field - Buy Link";
$selectArray[2] = "---------------";
$selectArray['custom'] = "Custom Field - SAVE AS NEW";
$selectArray['SKU'] 	= "SKU - Unique ID to prevent duplicates";
$catlist = premiumpress_categorylist('',false,false);

$i=0; $viscount=1; 

// LOAD AT THE TOP SO WE CAN USE THOUGHOUT
$feeddata = get_option("feeddatadata");  
if(is_array($feeddata)){

	foreach($feeddata as $feed){ //print_r($feed);
?>




<input type="hidden" name="feeddata[ID][]" value="<?php echo $feed['ID']; ?>" />

<div id="CF<?php echo $i; ?>">

<?php if(isset($feed['format']) && substr($feed['format'],-7) == "unknown"){ ?>
<br /><div class="msg msg-warn"><p>Your XML feed <b>format</b> could not be auto detected. You will need to manually adjust the format below for the import to be successfully imported.</p></div>

<?php } ?>  

<?php if(isset($feed['format']) && isset($feed['mapdata']) && !is_array($feed['mapdata']) ){  ?>
<br /><div class="msg msg-warn"><p>No data could be retrieved for this feed. Try adjusting the format below or try a new feed.</p></div>
<?php } ?>

<div class="green_box"><div class="green_box_content">

<p><b><?php if(isset($feed['name'])){ echo $feed['name']; } ?></b> <span style="float:right;">Feed ID:#<?php echo $feed['ID']; ?></span></p>
 
 
 
<p><b>Feed Data Source</b> (anything over 200k we recommend upload)
<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>What is this?</b><br>Feed source is a file or link to a file that contains the data you are going to be importing. <br><br>If the file size is small you can simply enter the http:// web path to the file and the system will import it directly however if the file is over 200KB we strongly recommend you upload it to your thumbs folder and select it from the drop down list.<br><br><b>Where do i upload it?</b> Upload all feed files to your 'thumbs' folder;<br><br> (/wp-content/themes/<?php echo strtolower(PREMIUMPRESS_SYSTEM); ?>/thumbs/) <br><br>once uploaded you can then select it from the feed list.&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>


</p>
<table width="250" border="0">
  <tr>
    <td  style="padding:0px; margin:0px;"><input name="feeddata[url][]" type="text" id="feedurl<?php echo $i; ?>"  class="ppt-forminput" 
    
    <?php if(strlen($feed['url']) == 0 && $feed['csv'] == ""){ ?>onfocus="this.value=''" style="color:#999;width:170px" value="Feed Link (http://)" <?php }else{ ?> style="width:170px" value="<?php  echo $feed['url'];  ?>" <?php } ?>  /></td>
    <td  style="padding:0px; margin:0px;font-size:9px;">&nbsp; OR &nbsp;</td>
    <td  style="padding:0px; margin:0px;"><select name="feeddata[csv][]" class="ppt-forminput" style="width:170px" onChange="document.getElementById('feedurl<?php echo $i; ?>').value='';document.getElementById('forMatFeed<?php echo $i; ?>').value='';jQuery('#csvbox<?php echo $i; ?>').show();">
<option value="0">----- XML/CSV FILE ----</option>
		<?php
 
	    $HandlePath =  get_option('imagestorage_path'); 

	    $count=1;
		if($handle1 = opendir($HandlePath)) {
      
	  	while(false !== ($file = readdir($handle1))){	

		if(substr(strtolower($file),-4) ==".csv" || substr(strtolower($file),-4) ==".xml"){
		
			if(isset($feed['csv']) &&  $feed['csv'] == $file){
				echo '<option value="'.$file.'" selected=selected>'.$file.'</option>';
			}else{
				echo '<option value="'.$file.'">'.$file.'</option>';
			}
	
		 
		} }}
		?>	 
</select></td>
  </tr>
</table>

 

<div class="clearfix"></div> 
<a href="javascript:void(0);" onclick="toggleLayer('delim<?php echo $i; ?>');" style="margin-left:140px;float:right; <?php if($feed['csv'] == ""){ ?>display:none;<?php } ?>" id="csvbox<?php echo $i; ?>">csv options</a>
<div class="clearfix"></div> 
<span style="margin-left:140px;font-size:10px;display:none;" id="delim<?php echo $i; ?>" >CSV Delimiter: <input name="feeddata[delimiter][]" class="ppt-forminput" style="width:40px; font-size:10px;" value="<?php if(isset($feed['delimiter']) && strlen($feed['delimiter']) > 0 ){ echo $feed['delimiter']; } ?>"></span>
 

 
<div class="clearfix"></div>
<br /> 
<a href="javascript:void(0);" onclick="document.getElementById('feedname<?php echo $i; ?>').value=''; jQuery('#CF<?php echo $i; ?>').hide();" class="button tagadd" style="float:right">Delete Feed</a>

<a href="javascript:void(0);" onclick="toggleLayer('options<?php echo $i; ?>').show();" class="button tagadd">Show Options</a> 




<?php 

// CHECK TO SEE IF A MAP DATA VALUE HAS BEEN FILLED
$canimport = false; 

if(isset($feed['mapdata']) && is_array($feed['mapdata']) ){ foreach($feed['mapdata'] as $data){   if(isset($feed[$feed['ID']][$data['key']]) && strlen($feed[$feed['ID']][$data['key']]) > 1){ $canimport = true; }  } } 

if($canimport){

?>
<a href="javascript:void(0);" onclick="jQuery('#runfeedbox').show();jQuery('#feedboxleft').hide();jQuery('#feedboxright').hide();document.getElementById('runFeedID').value=<?php echo $i; ?>;document.runFeed.submit();" class="button-primary">Run Import</a>
 	 
<?php } ?>
 
 

 
<div id="options<?php echo $i; ?>" style="display:none; margin-top:40px;">




<h3>-------------------------- Import Settings --------------------------</h3> 
<p>Here are additional feed settings for you to configure.</p> 
 
<div class="ppt-form-line">	
<span class="ppt-labeltext">Feed Name

<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;This is the display caption for your feed. Remove the name to delete the field..&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>

</span>
<input type="text" class="ppt-forminput" id="feedname<?php echo $i; ?>" name="feeddata[name][]"  value="<?php if(isset($feed['name'])){ echo $feed['name']; } ?>" size="35"> 
<div class="clearfix"></div>     
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Feed Format

<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;Remove value to auto detected format.&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>
</span>
<input type="text" id="forMatFeed<?php echo $i; ?>" class="ppt-forminput" name="feeddata[format][]"  value="<?php if(isset($feed['format'])){ echo $feed['format']; } ?>" size="35">
<div class="clearfix"></div>
</div>
 
<div class="ppt-form-line">	
<span class="ppt-labeltext">Import Category

<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;The is the category the system will use if no category is added as part of the feed import.&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>
</span>
<select name="feeddata[category][]" class="ppt-forminput"><?php echo str_replace('value="'.$feed['category'].'"','value="'.$feed['category'].'" selected=selected',$catlist); ?></select>  
<div class="clearfix"></div>     
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Scheduled Import
<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;This option will tell the system to import the same file on a timely bases, ideal if your updating the file every day..&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>


</span>
<select name="feeddata[period][]" class="ppt-forminput">
<option value="0" <?php if($feed['period'] == 0){ echo "selected=selected"; } ?>>------------</option>
<option value="hourly" <?php if($feed['period'] == 'hourly'){ echo "selected=selected"; } ?>>Import Every Hour</option>
<option value="twicedaily" <?php if($feed['period'] == 'twicedaily'){ echo "selected=selected"; } ?>>Import Twice Daily</option>
<option value="daily" <?php if($feed['period'] == 'daily'){ echo "selected=selected"; } ?>>Import Once Daily</option>
</select>
<div class="clearfix"></div>
 
</div>
 
<?php  if(isset($feed['mapdata']) && is_array($feed['mapdata']) ){  ?>


<h3>---------------------- Feed Value Mapping ----------------------</h3>
<p>Here you select which WordPress field to import your feed data into.</p>

<div style="width:350px; overflow:hidden;">

	<?php foreach($feed['mapdata'] as $data){ //if(strlen($data['value']) > 0 && $data['value'] != "0"){ ?>
    
    <div class="ppt-form-line">	
    <span class="ppt-labeltext"><?php echo str_replace("-"," ",str_replace("_"," ",$data['key'])); ?>
    
<a href="javascript:void(0);" onmouseover="this.style.cursor='pointer';" 
onclick="PPMsgBox(&quot;<b>Example</b>: <?php echo substr(htmlentities(str_replace('"','',$data['value'])),0,200); ?>.&quot;);"><img src="<?php echo PPT_FW_IMG_URI; ?>help.png" style="float:right;" /></a>
    
    </span>
    <select name="feeddata[<?php echo $feed['ID']; ?>][<?php echo $data['key']; ?>]"  class="ppt-forminput">
    <?php foreach($selectArray as $key=>$val){ ?><option value="<?php echo $key; ?>" <?php if($feed[$feed['ID']][$data['key']] == $key){ echo "selected=selected"; } ?>><?php echo $val; ?></option><?php } ?>
    </select> 
    <div class="clearfix"></div>            
     
    </div>
    <?php } ?>



 
<div class="ppt-form-line">
<a href="javascript:void(0);" onclick="toggleLayer('options<?php echo $i; ?>').show();" class="button tagadd">Hide Options</a> 
<input class="button-primary" type="submit" value="<?php _e('Save changes','cp')?>" style="float:right;" /></div>
</div>
<?php }else{ ?>

<?php } ?>


  

 
 
 </fieldset>
</div>

<div class="clearfix"></div>  
</div></div></div>


<?php $i++; } } // end if ?>




<input type="hidden" name="VisC" value="<?php echo $viscount; ?>" id="VisC" />
<script>
function shownb(){

var ev = document.getElementById('VisC').value;
var nxt = parseInt(ev)+1;
document.getElementById('VisC').value = nxt;
toggleLayer('CF'+nxt);

}
</script>



<div id="PACKAGEDISPLAYHERE"></div>

<input class="button-primary" type="submit" value="<?php _e('Save changes','cp')?>" />  
 
<a href="javascript:void(0);" onclick="jQuery('#packagebox').clone().appendTo('#PACKAGEDISPLAYHERE');" class="button tagadd" style="float:right;">Add New Feed</a>
 
 
</form>

 









</div>
<div class="grid400-left last" id="feedboxright">
 
<div class="videobox" id="videobox1a" style="margin-bottom:10px;">
<a href="javascript:void(0);" onclick="PlayPPTVideo('mUkoDZmQD2M','videobox1a');"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/video/14.jpg" align="absmiddle" /></a>
</div>  
</div>
<div class="clearfix"></div>
</div> 

 
</div> 

 

<!------------------------------------ PACKAGE BLOCK ------------------------------>


<div style="display:none;">
<div id="packagebox">
<div class="green_box"><div class="green_box_content">
 
<span class="ppt-labeltext">Feed Name</span>
 <input name="feeddata[name][]" type="text" class="ppt-forminput" />
<div class="clearfix"></div>
 
<div class="clearfix"></div></div></div>
</div>
</div> 