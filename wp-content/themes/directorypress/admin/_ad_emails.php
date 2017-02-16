<?php 

if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } 
 
global $PPT,$wpdb, $PPTImport;
 

/* ====================== PREMIUM PRESS FILES CLASS INCLUDE ====================== */

$PPM = new PremiumPress_Membership;

if(get_option("database_table_emails") != "installed2"){

mysql_query("CREATE TABLE IF NOT EXISTS `premiumpress_emails` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `email_type` enum('email','collection') NOT NULL,
  `email_parent` int(11) NOT NULL DEFAULT '0',
  `email_title` varchar(255) NOT NULL,
  `email_description` blob NOT NULL,
  `email_html` int(1) NOT NULL DEFAULT '0',
  `email_interval` int(1) NOT NULL DEFAULT '0',
  `email_modified` date NOT NULL,
  PRIMARY KEY (`ID`)
)");

mysql_query("INSERT INTO `premiumpress_emails` (`ID`, `email_type`, `email_parent`, `email_title`, `email_description`, `email_html`, `email_interval`, `email_modified`) VALUES
(1, 'email', 0, 'Example Signup Email', 0x48656c6c6f2028646973706c61795f6e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f72206a6f696e696e67206f75722077656273697465206f6e2028757365725f72656769737465726564292c20776520686f706520796f7520656e6a6f7920796f757220736572766963652e3c6272202f3e3c6272202f3e596f7572206c6f67696e2064657461696c73206172653b3c6272202f3e3c6272202f3e757365726e616d653a20757365725f6c6f67696e3c6272202f3e70617373776f72643a202873656e7420696e206120736570657261746520656d61696c293c6272202f3e3c6272202f3e496620796f75206861766520616e79207175657374696f6e7320706c65617365206665656c206672656520746f20636f6e746163742075733c6272202f3e3c6272202f3e4b696e6420526567617264733c6272202f3e3c6272202f3e4d616e6167656d656e74, 1, 0, '2011-06-03'),
(2, 'email', 0, 'Example New Orders', 0x48656c6c6f2028757365726e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f7220706c6163696e6720616e206f7264657220776974682075732c207765206172652063757272656e746c7920726576696577696e6720796f757220707572636861736520616e642077696c6c20636f6e7461637420796f75204153415020696620746865726520697320616e797468696e6720746f206469637573732e3c6272202f3e3c6272202f3e4b696e6420526567617264733c6272202f3e3c6272202f3e4d616e6167656d656e74266e6273703b, 1, 0, '2011-06-03'),
(3, 'email', 0, 'Example Paid Order Email', 0x48692028757365726e616d65293c6272202f3e3c6272202f3e5468616e6b20796f7520666f7220796f75266e6273703b, 1, 0, '2011-06-03'),
(4, 'email', 0, 'Admin Contact Form', 0x48692041646d696e3c6272202f3e3c6272202f3e596f7520686176652072656369657665642061206e6577206d6573736167652076696120746865207765627369746520636f6e7461637420666f726d2c2069742072656164733b3c6272202f3e3c6272202f3e266e6273703b, 1, 0, '2011-06-03'),
(5, 'email', 0, 'Admin New Order Received ', 0x48692041646d696e3c6272202f3e3c6272202f3e596f7520686176652072656369657665642061206e6577206f726465722c20706c65617365206c6f67696e20746f207468652061646d696e206172656120746f20636865636b2f75706461746520616e6420636f6e6669726d2e3c6272202f3e266e6273703b, 1, 0, '2011-06-03')");




update_option("email_signup","1");
update_option("email_message_neworder","2");
update_option("email_order_after","3");
update_option("email_admin_contact","4");
update_option("email_admin_neworder","5");


update_option("emailrole1","administrator");
update_option("emailrole2","editor");
update_option("emailrole3","contributor");
update_option("database_table_emails", "installed2");

}

/* ====================== PREMIUM PRESS SWITCH FUNCTIONS ====================== */
if(current_user_can('administrator')){
if(isset($_POST['action'])){ $_GET['action'] = $_POST['action']; }
if(isset($_GET['action'])){

	switch($_GET['action']){
	
		case "massemail": {
		
			if($_POST['email']['package_access'][0] == 0){ // SEND TO ALL MEMBERS
			
				$SQL = "SELECT * FROM $wpdb->users";
				
			}else{
			
					$paks = "";
					
					
					
				if(defined('THEME_WEB_PATH')){	
				
					foreach($_POST['email']['package_access'] as $package_ID){
						$paks .= "premiumpress_subscriptions.package_ID=".$package_ID." OR ";
					}				
				
					$SQL = "SELECT $wpdb->users.ID FROM $wpdb->users INNER JOIN `premiumpress_subscriptions` ON ($wpdb->users.user_login = premiumpress_subscriptions.user_login) 
					WHERE premiumpress_subscriptions.subscription_status = 1 AND ( ".$paks." premiumpress_subscriptions. package_ID=9999999 )
					GROUP BY $wpdb->users.user_login ";
					
				}else{ // FIND THE PACKAGE
			
			
					foreach($_POST['email']['package_access'] as $package_ID){
						$paks .= "  $wpdb->postmeta.meta_key = 'packageID' AND $wpdb->postmeta.meta_value = '".$package_ID."'   OR ";
						 
					}
					
					$SQL = "SELECT $wpdb->posts.post_author AS ID FROM $wpdb->posts 
					INNER JOIN $wpdb->postmeta ON ($wpdb->postmeta.post_id = $wpdb->posts.ID  AND  ".$paks."  $wpdb->postmeta.meta_key = 'packageID' AND $wpdb->postmeta.meta_value = '99999999'   )
					GROUP BY $wpdb->posts.post_author";
					
			 
				}
			
			}
			
			die($SQL);
			
	 
			$emails = $wpdb->get_results($SQL);
	 
			foreach ($emails as $email){
 
				SendMemberEmail($email->ID,$_POST['email']);
				
			}

			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Emails Sent Successfully";
				
		} break;

		case "massdelete": {
		 
			for($i = 1; $i < 50; $i++) { 
				if(isset($_POST['d'. $i]) && $_POST['d'.$i] == "on"){ 
					$data = $wpdb->get_results("DELETE FROM premiumpress_emails WHERE ID='".$_POST['d'.$i.'-id']."' LIMIT 1");					
				}
			}

			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= "Selected Emails Deleted Successfully";
		
		} break;

		case "delete": { 

		$data = $wpdb->get_results("DELETE FROM premiumpress_emails WHERE ID='".$_GET['id']."' LIMIT 1");
		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Email Deleted Sccuessfully.";
		
		} break;
	
		case "edit": { 

		if($_POST['ID'] == 0){
			$wpdb->query("INSERT INTO `premiumpress_emails` ( `email_type`, `email_parent`, `email_title`, `email_description`, `email_html`, `email_interval`, `email_modified`) VALUES( 'email', 0, '', '', 1, 0, '2011-06-03')"); 
			$_POST['ID'] = $wpdb->insert_id;
		} 
		

		$STRING="UPDATE premiumpress_emails SET ";
		foreach($_POST['form'] as $key => $value){
			$STRING .=" ".$key." ='".PPTCLEAN($value)."',";		
		}
		$STRING = substr($STRING,0,-1);
		$STRING .= ", email_modified=NOW() WHERE ID='".$_POST['ID']."' LIMIT 1";
		$data = $wpdb->get_results($STRING);

		$GLOBALS['error'] 		= 1;
		$GLOBALS['error_type'] 	= "ok"; //ok,warn,error,info
		$GLOBALS['error_msg'] 	= "Email Updated Successfully.";

		unset($_GET['edit']);

		} break;
	
	}

}
}

/* ====================== PREMIUM PRESS EDIT PAGE ====================== */


?>


<script type="text/javascript">

function addEmailtemplate(input) {
var TemplateCode = '';
if(input == 1){
TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><TABLE width="510" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;"><br>MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font><br><br> </TD></TR></TABLE></table></body></html>';
}else if(input == 2){
TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><table width="100%" border="0" bgcolor="#666666" style="padding:4px;color:#ffffff;"><tr><td><div style="float:left;color:#ffffff;">(date)</div><div style="float:right; color:#ffffff;">(blog_name)</div></td></tr></table><TABLE width="510" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;"><br>MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font><br><br></TD></TR></TABLE></table></body></html>';
}else if(input == 3){
TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><table width="100%" border="0" bgcolor="#666666" style="padding:4px;color:#ffffff;"><tr><td> <div style="float:left;color:#ffffff;">(date)</div><div style="float:right; color:#ffffff;">(blog_name)</div></td></tr></table><TABLE width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD width="27%" valign="top" bgcolor="#EFEFEF" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;"><br>  <br>  <br></p></TD>  <TD width="73%" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;">MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font></TD></TR></TABLE></table></body></html>';
}else if(input == 4){
 TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><table width="100%" border="0" bgcolor="#666666" style="padding:4px;color:#ffffff;"><tr><td> <div style="float:left;color:#ffffff;">(date)</div><div style="float:right; color:#ffffff;">(blog_name)</div></td></tr></table><TABLE width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD width="73%" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;">MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font></TD><TD width="27%" valign="top" bgcolor="#EFEFEF" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;"><br>  <br>  <br></p></TD> </TR></TABLE></table></body></html>';
}else if(input == 5){
 TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><table width="100%" border="0" bgcolor="#666666" style="padding:4px;color:#ffffff;"><tr><td> <div style="float:left;color:#ffffff;">(date)</div><div style="float:right; color:#ffffff;">(blog_name)</div></td></tr></table><TABLE width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD width="25%" bgcolor="#EFEFEF" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;">&nbsp;</p></TD><TD width="50%" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;">MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font></TD><TD width="25%" valign="top" bgcolor="#EFEFEF" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;"><br><br><br></p></TD> </TR></TABLE></table></body></html>';

}else if(input == 6){
 TemplateCode = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"><html><head><style>a:link {color:#046380;text-decoration:underline;}a:visited {color:#A7A37E;text-decoration:none;}a:hover {color:#002F2F;text-decoration:underline;}a:active {color:#046380;text-decoration:none;}</style></head><body><table align="center" width="600" style="border: #666666 1px solid;" cellpadding="0" cellspacing="0"><tr><td bgcolor="#efefef" style="border-bottom: #666666 1px solid;" ><div align="center" style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666; padding:20px;">TEXT OR LOGO</div></td></tr><tr><td><TABLE width="100%" border="0" cellpadding="0" cellspacing="0" align="center"><TR><TD width="50%" style="padding:10px;"><p style="font-family: arial,helvetica, sans-serif;font-size: 25px;color: #666666;">MAIN EMAIL TITLE</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">Subtitle</p><p style="font-family: arial,helvetica, sans-serif;font-size: 12px;color: #666666;">Your email content here</p><p style="font-family: arial,helvetica, sans-serif;font-size: 15px;color: #666666;">&nbsp;</p><br><font face="Verdana, Arial, Helvetica, sans-serif" color="#666666" size="1">(blog_name)<br /><a href="(blog_link)">(blog_link)</a></font></TD></TR></TABLE><table width="100%" border="0" bgcolor="#666666" style="padding:4px;color:#ffffff;"><tr>  <td><div style="float:left;color:#ffffff;">(date)</div><div style="float:right; color:#ffffff;">(blog_name)</div></td></tr></table></table></body></html>';
}






var currentText = tinyMCE.EditorManager.activeEditor.getContent();
//var NewOne = str.replace("%%%MYCONTENT%%%", currentText);
if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
    tinyMCE.EditorManager.activeEditor.execCommand('mceSetContent', false, TemplateCode);
    console.log(tinyMCE);
}
 
}
	
function addShortCode(input) {
var currentText = tinyMCE.EditorManager.activeEditor.getContent();
if ( typeof( tinyMCE ) == "object" && typeof( tinyMCE.execCommand ) == "function" ) {
    tinyMCE.EditorManager.activeEditor.execCommand('mceSetContent', false, currentText+input);
    console.log(tinyMCE);
}
 
}
</script>
	 
	<script type="text/javascript">
 
		function toggleLayer( whichLayer )
		{
		  var elem, vis;
		  if( document.getElementById ) 
			elem = document.getElementById( whichLayer );
		  else if( document.all ) 
			  elem = document.all[whichLayer];
		  else if( document.layers ) 
			elem = document.layers[whichLayer];
		  vis = elem.style;
		
		  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
		}

function hideEmailTools(val){

if(val =="collection"){
toggleLayer('EAG');toggleLayer('email_box');toggleLayer('email_box1');toggleLayer('email_box2');
}


}
	</script>
	<!-- /TinyMCE -->
    
<?php
	
	if(isset($_GET['edit'])){ 
 
if($_GET['edit'] == 0){ 
 
}else{
$data = $wpdb->get_results("SELECT * FROM premiumpress_emails WHERE ID='".$_GET['edit']."' LIMIT 1");
}
PremiumPress_Header();

	
?>


<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_emails.png" align="middle"> Add/ Edit Email</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe('add')">Help Me</a>							 
<ul>
	<li><a rel="premiumpress_tab1" href="#" class="active">Email Details</a></li>
	<li><a href="#" onclick="window.location.href='admin.php?page=emails'">Search Results</a></li>
</ul>
</div>

<style>
select { border-radius: 0px; -webkit-border-radius: 0px; -moz-border-radius: 0px; }
</style>























<div id="premiumpress_tab1" class="content">

<form class="fields" method="post" target="_self" enctype="multipart/form-data">
<input name="action" type="hidden" value="edit" />
<input name="ID" type="hidden" value="<?php echo $_GET['edit']; ?>" />
<input type="hidden" name="form[email_type]" value="email" />

<fieldset>
<div class="titleh"><h3>Email Options</h3></div>

<div class="grid400-left" style="width:550px;">

<div class="ppt-form-line">
<p>Email Subject</p>
<input  name="form[email_title]" value="<?php if(!isset($data[0]->email_title)){$data[0]->email_title=""; $data[0]->email_html =""; } echo stripslashes($data[0]->email_title); ?>"  type="text" class="ppt-forminput"   style="width:500px;" />
<div class="clearfix"></div>
</div>
<?php  if(!isset($data[0]->email_description)){$data[0]->email_description =""; } wp_editor(stripslashes($data[0]->email_description), 'form[email_description]' ); ?>
<p><input class="premiumpress_button" type="submit" value="Save Email" style="color:white;" /></p>
</div>

<div class="grid400-left last" style="width:200px;">
<p style="margin-top:30px;">Personalize Your Email</p>
<p><small>Click the shortcode to add it to your email.</small></p> 
<select name="" multiple class="ppt-forminput" style="width:200px;height:100px;">
<option onclick="javascript:addShortCode('(username)');">Username</option>
<option onclick="javascript:addShortCode('(firstname)');">First Name</option>
<option onclick="javascript:addShortCode('(lastname)');">Last Name</option>
<option onclick="javascript:addShortCode('(email)');">Email Address</option> 
<option onclick="javascript:addShortCode('(user_registered)');">Registration Date</option>   
<option onclick="javascript:addShortCode('(date)');">Today's Date</option>  
<option onclick="javascript:addShortCode('(time)');">Time Now</option> 
</select>
<div class="ppt-form-line"> 
<p>Email Type</p> 
<select name="form[email_html]"  class="ppt-forminput">
			<option value="1" <?php if($data[0]->email_html == 1){ echo "selected"; } ?>>Text/HTML</option>
			<option value="2" <?php if($data[0]->email_html == 2){ echo "selected"; } ?>>Plain Text</option>
</select>    
<div class="clearfix"></div>
</div>   
    
</div>
<div class="clearfix"></div>
</fieldset>

</form>


<fieldset>
<div class="titleh"> <h3>Email Templates</h3></div>
<p>Click to apply preset email. Note: will replace your current email content.</p>
<ul class="ppt_layout_columns"> 
<li><a href="javascript:addEmailtemplate(1);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e1.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(2);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e2.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(3);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e3.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(4);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e4.png" /></a></li> 
<li style="margin-right:9px;"><a href="javascript:addEmailtemplate(5);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e5.png" /></a></li> 
<li class="last"><a href="javascript:addEmailtemplate(6);return false;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e6.png" /></a></li> 
</ul>
</fieldset>
 

<fieldset>

 
</div>
</form>
</div>
</div>
























<?php }else{ 


if(!isset($_GET['p']) || $_GET['p']==""){ $_GET['p']=1; }
$GLOBALS['results_per_page'] = 100;
$GLOBALS['user_fields'] = array();
$GLOBALS['meta_fields'] = array();
$GLOBALS['members_fields'] = array();

$checkbox = 0;
$TotalResults 		= $PPM->scope();
$EMAIL_SEARCH_DATA 	= $PPM->EQuery();
PremiumPress_Header();


$email_collections = $wpdb->get_results("SELECT ID, email_title FROM premiumpress_emails WHERE email_parent=0");
 
?>
 
<div id="premiumpress_box1" class="premiumpress_box premiumpress_box-100"><div class="premiumpress_boxin"><div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/_ad_emails.png" align="middle"> Email Management</h3>	 <a class="premiumpress_button" href="javascript:void(0);" onclick="PPHelpMe()"><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/youtube.png" align="middle" style="margin-top:-10px;"> Help Me</a>	
<ul>	 
<li><a rel="premiumpress_tab1" href="#"  class="active">Email Configuration</a></li>
<li><a rel="premiumpress_tab3" href="admin.php?page=emails">My Emails</a></li>
<li><a href="#" onclick="window.location.href='admin.php?page=emails&edit=0'">Create Email</a></li>
<li><a rel="premiumpress_tab2" href="#">Send Emails</a></li>

</ul>
</div>

<div id="premiumpress_tab3" class="content">

<form class="plain" method="post" name="orderform" id="orderform">
<input type="hidden" name="action" value="massdelete">
<fieldset style="padding:0px;">
<table cellspacing="0"><thead><tr>

<td class="tc"><input type="checkbox" id="data-1-check-all" name="data-1-check-all" value="true" onclick="da(2);return false;" /></td>
<th>Email Subject <br /><small>Order By Subject </small>  <a href="admin.php?page=emails&sort=email_title&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/su.png" align="middle"></a> <a href="admin.php?page=emails&sort=email_title&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/sd.png" align="middle"></a></th>
<td>Email Information <br /> <small>Order By Interval</small> <a href="admin.php?page=emails&sort=email_interval&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/sd.png" align="middle"></a>  <a href="admin.php?page=emails&sort=email_interval&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/sd.png" align="middle"></a> </td>

<!--<td class="tc">Email Group <br /> <small>Order By Group <a href="admin.php?page=emails&sort=email_parent&order=asc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/su.png" align="middle"></a> <a href="admin.php?page=emails&sort=email_parent&order=desc"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/sd.png" align="middle"></a></small></td>
<td class="tc"><?php if(isset($_GET['id'])){ print "Send Order"; }else{ print "Number of Emails"; } ?> </td>-->
<td class="tc">Actions <br /> </td>

</tr></thead><tfoot><tr><td colspan="6">
<label>with selected do:
<select name="data-1-groupaction"><option value="delete">delete</option></select></label>
<input class="button altbutton" type="submit" value="OK" style="color:white;" />
</td></tr></tfoot><tbody>



<?php foreach($EMAIL_SEARCH_DATA as $email) { ?>


<tr class="first">

<td class="tc">
<input type="checkbox" value="on" name="d<?php echo $checkbox; ?>" id="d<?php echo $checkbox; ?>"/>
<input type="hidden" name="d<?php echo $checkbox; ?>-id" value="<?php echo $email->ID ?>">
</td>
<td><?php echo stripslashes($email->email_title); ?>  <br /> <small>Last Modified: <?php echo $email->email_modified; ?></small></td>
<td>
<?php if($email->email_html ==1){ echo "text/HTML"; }else{ echo "plain text"; } ?> 
</td> 

 
<td class="tc">
<ul class="actions">
 
<li><a class="ico" href="admin.php?page=emails&edit=<?php echo $email->ID; ?>" rel="permalink"><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/PPT/img/premiumpress/led-ico/pencil.png" alt="edit" /></a></li>
<li><a class="ico" class="submitdelete" href="admin.php?page=emails&action=delete&id=<?php echo $email->ID; ?>" onclick="if ( confirm('Are you sure you want to delete this email?') ) { return true;}return false;"><img src="<?php if(defined('THEME_WEB_PATH')){ echo THEME_WEB_PATH; }else{ echo $GLOBALS['template_url']; } ?>/PPT/img/premiumpress/led-ico/delete.png" alt="delete" /></a></li>

</ul>
</td>
</tr>


</td>	</tr>
	</tbody>
<?php $checkbox++;  } ?>

</table>




<input type="hidden" name="totalorders" value="<?php echo $checkbox; ?>">
<div class="pagination"><ul><li><?php echo $PPM->viewing('admin.php?page=emails'); ?></li></ul></div>

</form>
</div>




















<div id="premiumpress_tab2" class="content">

<form class="fields" method="post" target="_self" enctype="multipart/form-data">
<input name="action" type="hidden" value="massemail" />


<fieldset>
<div class="titleh"><h3>Email Broadcast</h3></div>

<div class="grid400-left" style="width:550px;">

<div class="ppt-form-line">
<p>Email Subject</p>
<input name="email[subject]" type="text" class="ppt-forminput" value="<?php echo $data[0]->email_title; ?>" style="width:500px;" />
<div class="clearfix"></div>
</div>
<?php  wp_editor(stripslashes($data[0]->email_description), 'email[description]' ); ?>
<p><input class="premiumpress_button" type="submit" value="Send Message" style="color:white;" /></p>
</div>

<div class="grid400-left last" style="width:200px;">
<p style="margin-top:30px;">Personalize Your Email</p>
<p><small>Click the shortcode to add it to your email.</small></p> 
<select name="" multiple class="ppt-forminput" style="width:200px;height:100px;">
<option onclick="javascript:addShortCode('(username)');">Username</option>
<option onclick="javascript:addShortCode('(firstname)');">First Name</option>
<option onclick="javascript:addShortCode('(lastname)');">Last Name</option>
<option onclick="javascript:addShortCode('(email)');">Email Address</option> 
<option onclick="javascript:addShortCode('(user_registered)');">Registration Date</option>   
<option onclick="javascript:addShortCode('(date)');">Today's Date</option>  
<option onclick="javascript:addShortCode('(time)');">Time Now</option> 
</select>
<div class="ppt-form-line"> 
<p>Email Type</p>			 
<select name="email[email_html]" class="ppt-forminput">
<option value="1" <?php if($data[0]->email_html == 1){ echo "selected"; } ?>>Text/HTML</option>
<option value="2" <?php if($data[0]->email_html == 2){ echo "selected"; } ?>>Plain Text</option>
</select>    
<div class="clearfix"></div>
</div>   
<div class="ppt-form-line"> 
<p>Send Email To:</p>
<select name="email[package_access][]" size="2" style="font-size:14px;padding:5px;   height:100px; background:#e7fff3;" class="ppt-forminput" multiple="multiple">
<option value="0">---- ALL MEMBERS ----</option>
<?php if( strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){
 $packdata = get_option("packages");  ?>                
<?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1){ ?><option value="1"><?php echo $packdata[1]['name']; ?></option><?php } ?>
<?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1){ ?><option value="2"><?php echo $packdata[2]['name']; ?></option><?php } ?>
<?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1){ ?><option value="3"><?php echo $packdata[3]['name']; ?></option><?php } ?>
<?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1){ ?><option value="4"><?php echo $packdata[4]['name']; ?></option><?php } ?>
<?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1){ ?><option value="5"><?php echo $packdata[5]['name']; ?></option><?php } ?>
<?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1){ ?><option value="6"><?php echo $packdata[6]['name']; ?></option><?php } ?>
<?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1){ ?><option value="7"><?php echo $packdata[7]['name']; ?></option><?php } ?>
<?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1){ ?><option value="8"><?php echo $packdata[8]['name']; ?></option><?php } ?> 
<?php } ?>
</select>
<p><small>Hold the SHIFT key to select multiple.</small></p>
<div class="clearfix"></div>
</div>    
</div>
<div class="clearfix"></div>
</fieldset>

</form>


<fieldset>
<div class="titleh"> <h3>Email Templates</h3></div>
<p>Click to apply preset email. Note: will replace your current email content.</p>
<ul class="ppt_layout_columns"> 
<li><a href="javascript:addEmailtemplate(1);"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e1.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(2);"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e2.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(3);"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e3.png" /></a></li>  
<li><a href="javascript:addEmailtemplate(4);"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e4.png" /></a></li> 
<li style="margin-right:9px;"><a href="javascript:addEmailtemplate(5);;"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e5.png" /></a></li> 
<li class="last"><a href="javascript:addEmailtemplate(6);"><img src="<?php echo $GLOBALS['template_url']; ?>/PPT/img/admin/new/e6.png" /></a></li> 
</ul>
</fieldset>








</div>











<div id="premiumpress_tab1" class="content">

<form class="fields" method="post" target="_self" >
<input name="submitted" type="hidden" value="yes" />
<input type="hidden" name="admin_page" value="email_manager" />



<div class="grid400-left">

<fieldset>
<div class="titleh"><h3>Emails Sent To Website Members / Visitors</h3></div>

 <div class="ppt-form-line">	
<span class="ppt-labeltext">Signup Email</span>	
		<select name="adminArray[email_signup]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_signup"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the user when they first join your website.</small>
<div class="clearfix"></div>
</div> 


  <div class="ppt-form-line">	
<span class="ppt-labeltext">Email Alert</span>	
		<select name="adminArray[email_alter]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_alter"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the user when a new listing is added to a category.</small>
<div class="clearfix"></div>
</div> 

			
  
<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>

 
 <div class="ppt-form-line">	
<span class="ppt-labeltext">New Message</span>	
<select name="adminArray[email_message_new]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_message_new"),$type="!=0"); ?></select>
            <br /><br />
            <small>Sent to the user when they receive a new message/ contact form message.</small>
<div class="clearfix"></div>
</div>  
<?php }else{ ?>
 <div class="ppt-form-line">	
<span class="ppt-labeltext">New Order Placed</span>	
<select name="adminArray[email_message_neworder]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_message_neworder"),$type="!=0"); ?></select>
            <br /><br />
            <small>Sent to the user when they place an order (NOT yet Paid)</small>
<div class="clearfix"></div>
</div>  
<?php } ?> 

 <div class="ppt-form-line">	
<span class="ppt-labeltext">After Payment</span>	
<select name="adminArray[email_order_after]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_order_after"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the user after they have paid for their listing.</small>


<div class="clearfix"></div>
</div> 

<?php if(get_option('pptuser_default') == "pending"){ ?>
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Pending Registration</span>	
<select name="adminArray[email_register_pending]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_register_pending"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the user after they register.</small>
<div class="clearfix"></div>
</div>  
<?php } ?>

<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>

 <div class="ppt-form-line">	
<span class="ppt-labeltext">Edit/Updated Listing</span>	
<select name="adminArray[email_user_listingedit]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_user_listingedit"),$type="!=0"); ?></select>
        <br /><br /><small>Sent when a user edits/updates a free/paid listing.</small>	
<div class="clearfix"></div>
</div> 

 <div class="ppt-form-line">	
<span class="ppt-labeltext">Membership Expired</span>	
<select name="adminArray[email_user_membershipexpired]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_user_membershipexpired"),$type="!=0"); ?></select>
        <br /><br /><small>Sent to the user once their membership has expired.</small>	
<div class="clearfix"></div>
</div> 
 
<?php } ?>


<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "auctionpress" && get_option("pak_enabled") ==1 ){ ?>
 
 
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Package Expires in 7 days</span>	
	<select name="adminArray[email_user_expire7]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_user_expire7"),$type="!=0"); ?></select>
            <br /><small>Sent to the user when their package is set to expiry within 7 days.</small>

 
<div class="clearfix"></div>
</div>   

  <div class="ppt-form-line">	
<span class="ppt-labeltext">Package Just Expired</span>	
   <select name="adminArray[email_user_expired]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_user_expired"),$type="!=0"); ?></select>
        <br /><br /><small>Sent to a user when their package has expired.</small>	

<div class="clearfix"></div>
</div>   
<?php } ?> 


<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "employeepress"){ ?>

<div class="ppt-form-line">	
<span class="ppt-labeltext">New Bid  - sent to job owner</span>	
  <select name="adminArray[email_ep_newbid]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_ep_newbid"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the job owner when someone bids/sends them a resume.</small>
               <p><b></b></p>
<div class="clearfix"></div>
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Job Accepted  - sent to bidder</span>	
  <select name="adminArray[email_bid_accepted]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_bid_accepted"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the bidder once their bid is accepted</small>
               <p><b></b></p>
<div class="clearfix"></div>
</div>

<div class="ppt-form-line">	
<span class="ppt-labeltext">Job Declined  - sent to bidder</span>	
  <select name="adminArray[email_bid_declined]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_bid_declined"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the bidder once their bid is declined</small>
               <p><b></b></p>
<div class="clearfix"></div>
</div>       

<?php } ?> 

<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "auctionpress"){ ?>

 <div class="ppt-form-line">	
<span class="ppt-labeltext">New Bid  - sent to auction item owner</span>	
  <select name="adminArray[email_bid_new]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_bid_new"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user when they have made a new bid on an item.</small>
               <p><b></b></p>
<div class="clearfix"></div>
</div>   


 <div class="ppt-form-line">	
<span class="ppt-labeltext">New Auction Item Submission</span>	
<select name="adminArray[email_auction_new]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_auction_new"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user after they submit a new auction item.</small>
            
<div class="clearfix"></div>
</div>  
 
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Auction Ended </span>	
  <select name="adminArray[email_auction_end]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_auction_end"),$type="!=0"); ?></select>
            <br /><small>This email will be sent to the user when their auction has ended.</small>
            
<div class="clearfix"></div>
</div>   
<?php } ?> 





 <div class="ppt-form-line"><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></div> 
 


</fieldset>
</div>

<div class="grid400-left last">
 
 <fieldset>
<div class="titleh">
  <h3>Emails Sent To Website Admin's</h3>
</div>

<?php if(get_option('pptuser_default') == "pending"){ ?>
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Pending Registration</span>	
<select name="adminArray[email_register_pending_admin]" class="ppt-forminput">
			<option value="0">---------</option>
			<?php echo $PPM->collections(get_option("email_register_pending_admin"),$type="!=0"); ?></select>
            <br /><br /><small>Sent to the admin after a new user registers.</small>
<div class="clearfix"></div>
</div>  
<?php } ?>
  
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Contact Form</span>	
    <select name="adminArray[email_admin_contact]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_contact"),$type="!=0"); ?></select>
        <br /><br /><small>Sent to the admin when a visitor completes the contact page form.</small>	
        
<div class="clearfix"></div>
</div>  
      
        
        <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>
        
         <div class="ppt-form-line">	
<span class="ppt-labeltext">New Listing Added</span>	
 <select name="adminArray[email_admin_newlisting]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_newlisting"),$type="!=0"); ?></select>
        <br /><br /><small>Sent to the admin when a new listing has been submitted.</small>
<div class="clearfix"></div>
</div>

        
       
        <?php }else{ ?>
        
 <div class="ppt-form-line">	
<span class="ppt-labeltext">New Order Received</span>	
  <select name="adminArray[email_admin_neworder]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_neworder"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a new order has been placed on the website.</small> 
<div class="clearfix"></div>
</div>          
  <?php } ?>		
            
 
      
 
 
<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "shopperpress"){ ?>
 <div class="ppt-form-line">	
<span class="ppt-labeltext">Edit/Updated Listing</span>	
   <select name="adminArray[email_admin_listingedit]" class="ppt-forminput">
		<option value="0">---------</option>
		<?php echo $PPM->collections(get_option("email_admin_listingedit"),$type="!=0"); ?></select>
        <br /><br /><small>Email sent when a member edits/updates a free/paid listing.</small>	
        
<div class="clearfix"></div>
</div>   
<?php } ?>


 <?php $PPTroles = array('administrator' => 'Super Admin','editor' => 'Site Manager','contributor' => 'Employee' ); // ?>
 <div class="ppt-form-line">
  <p>Send admin emails to:</p>


<div class="clearfix"></div>

<?php $r=1;foreach($PPTroles as $key=>$name){ ?>
        
        <input name="emailrole<?php echo $r; ?>" type="checkbox" value="<?php echo $key; ?>" <?php if(get_option('emailrole'.$r) == $key){ echo "checked=checked";} ?>/> <?php echo $name; ?>  
    
        <?php $r++; } ?>
</div> 
        
 <div class="ppt-form-line"><input class="premiumpress_button" type="submit" value="Save Changes" style="color:white;" /></div> 
    

</fieldset>
 
 


 
</form>
</div>

</div>

 



<?php
 

}

 
?>