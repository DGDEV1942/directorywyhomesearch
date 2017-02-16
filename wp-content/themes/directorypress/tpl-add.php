<?php
/*
Template Name: [Add/Submit Template]
*/

/* =============================================================================
   THIS FILE SHOULD NOT BE EDITED // UPDATED: 16TH MARCH 2012
   ========================================================================== */ 
   
if(!function_exists('get_currentuserinfo')){ die("No Access"); }

define("PPT-PAGE","add");
$GLOBALS['tpl-add'] = 1;

 

global $PPT,  $PPTDesign, $userdata; get_currentuserinfo(); // grabs the user info and puts into vars

$wpdb->hide_errors(); nocache_headers();

$GLOBALS['page_width'] 	= get_post_meta($post->ID, 'width', true);

if($GLOBALS['page_width'] =="full"){ $GLOBALS['nosidebar-right'] = true; $GLOBALS['nosidebar-left'] = true; }


// PACKAGE ENABLED BUTTONS
$GLOBALS['PACKAGES_ENABLE']  = get_option('pak_enabled');//<!-- IMPORTANT, DONT REMOVE THIS!!


// GET THE USERS MEMBERSHIP DETAILS
$GLOBALS['membershipID'] 		= get_user_meta($userdata->ID, 'pptmembership_level', true);
$GLOBALS['membershipStatus'] 	= get_user_meta($userdata->ID, 'pptmembership_status', true);
$GLOBALS['membershipData'] 		= get_option('ppt_membership'); 
// DISPLAY CURRENT MEMBERSHIP LEVEL
if($GLOBALS['membershipID'] !="" && is_numeric($GLOBALS['membershipID']) && $GLOBALS['membershipID'] !="0"){
	if(is_array($GLOBALS['membershipData']) && isset($GLOBALS['membershipData']['package'])){
		foreach($GLOBALS['membershipData']['package'] as $val){		
			if($GLOBALS['membershipID'] == $val['ID'] && $val['submission'] == "unlimited"){
					$GLOBALS['PACKAGES_ENABLE'] = 0; // SET VALUE TO 0 SO THE USER DOESNT HAVE TO SINGUP
					if(is_numeric($val['packageID'])){
					$_POST['packageID'] = $val['packageID'];
					}
			}	
		} // end for 
	} // end if
} // end if




/* =============================================================================
   HOOKS FOR WP EDITOR // UPDATED: 16TH MARCH 2012
   ========================================================================== */ 
 
	function enable_more_buttons($buttons) {
	 
	  unset($buttons[12]); // link
	  unset($buttons[13]); // unlink
	   
	  $buttons[] = 'hr';
	  //$buttons[] = 'fontselect'; // cause ID error 
	
	  return $buttons;
	}
	function remove_media_library_tab($tabs) {
		unset($tabs['library']);
		return $tabs;
	}
	add_filter('media_upload_tabs', 'remove_media_library_tab');
	add_filter("mce_buttons", "enable_more_buttons");
	add_filter('wp_default_editor', create_function('', 'return "tinymce";'));
		
	/*	This function will check to see if 1 or more packages has the available feature enabled, 	if not it will hide the entire row.	*/
	function CanShowRow($data, $val){
		
		$i=0;
		while($i< 9){
		
		if($val == "freetrialp" && strlen($data[$i][$val]) > 0){
		return true;
		}elseif(isset($data[$i][$val]) && $data[$i][$val] == 1){
			return true;
			}
			$i++;
		}
		return false;	
	}

/* ================================= PAYMENT OPTIONS =============================== */

if(isset($_POST['customgateway'])){
	include(TEMPLATEPATH ."/PPT/class/class_payment.php");
	$PPTPayment 		= new PremiumPressTheme_Payment;
	$PPTPayment->CustomGateway($_POST['customgateway']);
}

/* ============================= PREMIUM PRESS FOR SUBMISSION ========================================= */

if(!isset($_POST['packageID']) && !isset($_GET['eid']) && $GLOBALS['PACKAGES_ENABLE'] ==1){ $GLOBALS['nosidebar'] =1; }

if(isset($_POST['form'])){ $_POST['form'] = PPTOUTPUT($_POST['form']);  }

if(get_option("tpl_add_mustlogin") =="yes" && ( isset($_POST['packageID']) || $GLOBALS['PACKAGES_ENABLE'] != 1) ){ premiumpress_authorize(); }

$PACKAGE_OPTIONS = get_option("packages");

$GLOBALS['PACKAGE_OPTIONS'] = $PACKAGE_OPTIONS;

/* ============================= PREMIUM PRESS FOR SUBMISSION ========================================= */

if(isset($_POST['action']) && !empty($_POST['action'])){

	$GLOBALS['premiumpress']['language'] = get_option("language");
	$PPT->Language();
 	
	/* ============================= ONLY IF RECEP LINK ENABLED ========================================= */

	if(strtolower(PREMIUMPRESS_SYSTEM) == "directorypress" && get_option('display_rlink') == "yes" && !isset($_POST['norec']) && !isset($_GET['eid']) && !isset($_POST['step3'])  ){		
		
		$tcss = explode(" ",stripslashes(strip_tags(get_option("display_rlink_text"))));
			 
			if(strlen($_POST['r_link']) < 5){
			
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
			$GLOBALS['error_msg'] 	= $PPT->_e(array('add','50'));
			
			$canContinue = false;
			
			}else{
			
				$website_data = fetch_URL($_POST['r_link']);
	 
				if(strlen($website_data) > 5){
				
					if(strlen($tcss[0]) == 0){
					
						$checkThis = stripslashes(get_option("display_rlink_text"));
					
					}else{
					
						$checkThis = $tcss[0];
					
					}
				
					$pos = strpos($website_data, $checkThis); 
					if ($pos === false) {

						$GLOBALS['error'] 		= 1;
						$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
						$GLOBALS['error_msg'] 	= $PPT->_e(array('add','50'));

					}else{
					
						$GLOBALS['recpFound'] =1;
					
					}
					
					
				}else{
				
					$GLOBALS['error'] 		= 1;
					$GLOBALS['error_type'] 	= "error"; //ok,warn,error,info
					$GLOBALS['error_msg'] 	= $PPT->_e(array('add','50'));
				
				}
			}
		}			
	
	/* ============================= ONLY IF RECEP LINK ENABLED ========================================= */
	
	
	// CHECK FOR ADDITONAL CATEGORY PRICES
 	if(isset($_POST['step3'])){		
	
		$GLOBALS['ExtraPrice'] = 0; // DEFAULT VALUE
		
		if(is_array($_POST['CatSel'])){
			$runningCount = 0;
			foreach($_POST['CatSel'] as $CATID){	
			 
					$price  = get_option('CatPrice_'.$CATID);
				
					// IF WERE EDITING MAKE SURE WE DONT CHARGE THE USER AGAIN
					// JUST FOR UPDATING , would be nice thought! :)
					if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){
						// CHECK THIS CAT IS NOT PART OF THEIR ORGINAL LISTING
						$mycats =  get_the_terms( $_GET['eid'], 'category' );
						if(isset($mycats) && is_array($mycats)){
						foreach($mycats as $myc){
							 if($CATID == $myc->term_id){
								$price=0;
							 }
						}	
						}			 
					}// end if
					
					if($price == ""){ $GLOBALS['ExtraPrice'] += 0; }else{ $GLOBALS['ExtraPrice'] += $price; }	
			} // end foreacg
		} // end if cat sel
	

	$canContinue = premiumpress_post_validate();

	if($canContinue){
 
		switch($_POST['action']){
		
			case "add": { 			
			  
				$NEW_POST_ID = premiumpress_post('add');
				
				// RESET EXISTING USER DATA
				if(isset($GLOBALS['new_user_id'])){
				$userdata->ID = $GLOBALS['new_user_id'];
				} 
			
				// SEND EMAIL
				$emailID = get_option("email_admin_newlisting");					 
				if(is_numeric($emailID) && $emailID != 0){
					SendMemberEmail("admin", $emailID);
				}
				
				$_POST['eid'] = $NEW_POST_ID;
				if(is_numeric($_POST['eid'])){ }else{ $_POST['eid'] =0; }	
				
				// EXTRA OPTIONS WE MIGHT WANT TO USE LATER TO PLACE HERE JUST INCASE :)
				if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "auctionpress"){
				
					if(isset($_POST['TotalCost']) && is_numeric($_POST['TotalCost']) && $_POST['TotalCost'] > 0){
					 
					mysql_query("UPDATE $wpdb->usermeta SET meta_value=meta_value-".PPTCLEAN($_POST['TotalCost'])." WHERE meta_key='aim' AND user_id='".$userdata->ID."' LIMIT 1"); 				
					
					}
					
					// SEND EMAIL
					$emailID = get_option("email_auction_new");					 
					if(is_numeric($emailID) && $emailID != 0){
						SendMemberEmail($userdata->ID, $emailID);
					}
					
				}			
 	
						
			} break;
	
			case "edit": { 
			
				if(!is_numeric($_POST['eid'])){return; }
				// GET THE CURRENT PACKAGE ID FROM THE DATABASE TO
				// ENSURE USER IS NOT HACKING THE FORM FIELDS
				$_POST['packageID'] = get_post_meta($_POST['eid'], "packageID", true);
			 
				$canContinue = premiumpress_post('edit');  
			
				//  SEND EMAIL
				$emailID = get_option("email_admin_listingedit");					 
				if(is_numeric($emailID) && $emailID != 0){
					SendMemberEmail("admin", $emailID);
				}	
					
				$emailID = get_option("email_user_listingedit");					 
				if(is_numeric($emailID) && $emailID != 0){
					SendMemberEmail($userdata->ID, $emailID);
				}
				
				$NEW_POST_ID = $_POST['eid']; 
 
			} break;
		}
		 
		if(($_POST['action'] == "add" || $_POST['action'] == "edit" ) && $GLOBALS['PACKAGES_ENABLE'] ==1  ){
	 
			$newPrice=0;  $ORDERTYPE = "";
			// WORK OUT ANY PACKAGE PRICES
		 
			
		 	$GLOBALS['packageName'] 	= $PACKAGE_OPTIONS[$_POST['packageID']]['name'];
			$GLOBALS['TotalPriceDue'] 	= $PACKAGE_OPTIONS[$_POST['packageID']]['price'];
			$GLOBALS['freetrialperiod'] = $PACKAGE_OPTIONS[$_POST['packageID']]['freetrialp'];
			$_POST['rec'] 				= $PACKAGE_OPTIONS[$_POST['packageID']]['rec'];
			$_POST['rec_days'] 			= $PACKAGE_OPTIONS[$_POST['packageID']]['expire'];	
			  
			if($_POST['NEWpackageID'] != $_POST['packageID']){
			
				if(isset($_POST['NEWpackageID']) && $_POST['NEWpackageID'] > 0  && $_POST['action']=="edit"){
			
						$newPrice = $PACKAGE_OPTIONS[$_POST['NEWpackageID']]['price'] - $PACKAGE_OPTIONS[$_POST['packageID']]['price'];
						$ORDERTYPE = "UPGRADE";
				}else{
						$newPrice = $GLOBALS['TotalPriceDue'];
						$ORDERTYPE = "NEW";						
				}
				
				// ONLY CHANGE VALUES IF EDITING A LISTING
				if($_POST['action']=="edit"){	
				
						$GLOBALS['freetrialperiod'] = $PACKAGE_OPTIONS[$_POST['NEWpackageID']]['freetrialp'];
						$_POST['rec'] 				= $PACKAGE_OPTIONS[$_POST['NEWpackageID']]['rec'];
						$_POST['rec_days'] 			= $PACKAGE_OPTIONS[$_POST['NEWpackageID']]['expire'];	
				
				}
						
			}
			
			if($_POST['NEWpackageID'] == ""){ $_POST['NEWpackageID'] = $_POST['packageID']; }		
			$_POST['price']['total'] 	= $newPrice+=$GLOBALS['ExtraPrice'];
			$_POST['orderid'] 			= $NEW_POST_ID."-".$userdata->ID."-".$ORDERTYPE."-".$_POST['NEWpackageID'];
			$_POST['description'] 		= "".$_POST['orderid']; //Cart Order ID:
		 			
			if($_POST['price']['total'] > 0){
					 
					// UPDATE PRICE
					if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "realtorpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "classifiedstheme" ){ update_post_meta($NEW_POST_ID, "price", $_POST['price']['total']); }
					
					// SAVE THE ORDER INTO THE DATABASE
					include(str_replace("functions/","",THEME_PATH)."/PPT/func/func_paymentgateways.php");
					
					// HOOK INTO THE PAYMENT GATEWAY ARRAY // V7
					$gatway = premiumpress_admin_payments_gateways($gatway);
					 
					
					include(TEMPLATEPATH ."/PPT/class/class_payment.php");	
					$PPTPayment	= new PremiumPressTheme_Payment;
					
					// BUILD ORDER DESCRIPTION
					$OrderData = "\r\n --------- POST ID: ". $NEW_POST_ID. " ------------- \r\n";
					if(isset($_POST['NEWpackageID']) && $_POST['NEWpackageID'] > 0){
					$OrderData .= "\r\n Package: ".strip_tags($PACKAGE_OPTIONS[$_POST['NEWpackageID']]['name']). "\r\n";
					}elseif(isset($_POST['packageID'])){ 					
					$OrderData .= "\r\n Package: ".strip_tags($PACKAGE_OPTIONS[$_POST['packageID']]['name']). "\r\n";
					}
					$OrderData .= "\r\n Category: ".$PPT->CategoryFromID($_POST['CatSel']). "\r\n";
					$OrderData .= "\r\n Name: ".PPTOUTPUT($_POST['form']['title']). "\r\n";
					$OrderData .= "\r\n Short Description: ".PPTOUTPUT($_POST['form']['short']). "\r\n";
					$OrderData .= "\r\n Order ID: ".$_POST['orderid']. "\r\n";
				 
					$GLOBALS['orderData'] = strip_tags($OrderData);			 
					$GLOBALS['orderItems'] = $NEW_POST_ID."x1";
					// DATA TO ADD TO THE PAYMENT CALL
					$GLOBALS['total'] 		= $_POST['price']['total'];
					$GLOBALS['subtotal'] 	= 0;
					$GLOBALS['shipping'] 	= 0;  
					$GLOBALS['tax'] 		= 0;
						
					$PPTPayment->InsertOrder("",$_POST['orderid'],0);
					
				}		
			} 
		}
	} // end step 3
}

/* ============================= PREMIUM PRESS EDIT DATA ========================================= */

if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){

	$data = premiumpress_post_data($user_ID, $_GET['eid'],$user_ID);
	
	if(isset($data['packageID'])){	$_POST['packageID'] = $data['packageID'][0]; }
 	if(!isset($_POST['packageID']) || $_POST['packageID'] == ""){ $_POST['packageID'] = 1; }
	
	if(isset($_POST['TryPackageID'])){ 
	$_POST['currentID'] = $_POST['packageID'];
	$_POST['packageID'] = $_POST['TryPackageID']; 
	
	} 
}else{
$data="";
} 
 
/* ============================= PREMIUM PRESS DELETE PHOTO ========================================= */

if(isset($_POST['eid']) && isset($_POST['pid']) && is_numeric($_POST['eid']) ){

	$GLOBALS['premiumpress']['language'] = get_option("language");
	$PPT->Language();

	if(isset($_POST['display']) && $_POST['display'] == 1){
	$PPT->SetDisplayPhoto($_POST['eid'],$_POST['pid'],$userdata->ID);
	
	$GLOBALS['error'] 		= 1;
	$GLOBALS['error_type'] 	= "tip"; //ok,warn,error,info
	$GLOBALS['error_msg'] 	= $PPT->_e(array('add','51'));
		
	}else{
	
	premiumpress_upload_delete($_POST['eid'],$_POST['pid'],$userdata->ID);
	
	} 
}
 

// quick fix for clicking the back button when editing a listing to show the new content
if(isset($_GET['eid']) && isset($_POST['action']) and $_POST['action'] == "step1"){
unset($data);
$data=array();
}
 

/* ================ LOAD TEMPLATE FILE =========================== */	
 
$hookContent = premiumpress_pagecontent("add"); /* HOOK V7 */

if(strlen($hookContent) > 20 ){ // HOOK DISPLAYS CONTENT

	get_header();
	
	echo $hookContent;
	
	get_footer();

}elseif(file_exists(str_replace("functions/","",THEME_PATH)."themes/".get_option('theme')."/_tpl_add.php")){
		
		include(str_replace("functions/","",THEME_PATH)."themes/".get_option('theme').'/_tpl_add.php');
		
}else{

/* =============================================================================
   LOAD IN PAGE DEFAULT DISPLAY // UPDATED: 25TH MARCH 2012
   ========================================================================== */ 
 
/* =============================================================================
   1. LOAD THE PACKAGE OPTIONS
   ========================================================================== */ 
 
if(!isset($_POST['packageID']) && !isset($_GET['eid']) && $GLOBALS['PACKAGES_ENABLE'] ==1 ){ 

	// ADD IN STYLES
	wp_register_style( 'packages',  PPT_THEME_URI."/PPT/css/css.packages.css");
	wp_enqueue_style( 'packages' );
	
	/*echo "<!--[if IE]> <script src='".PPT_THEME_URI."/PPT/js/html5.js'></script> <![endif]-->";	*/

	// DEFINE VALUES FOR USE
	$packdata 		= get_option("packages");  // PACKAGES DATA
	$customdata 	= get_option("customfielddata"); // CUSTOM FIELDS DATA
	$extraText 		= get_option("pak_text"); // PACKAGE TEXT AT THE BOTTOM
	$STRING 		= "";
	
	// TURN OF THE LEFT/RIGHT SIDEBARS FOR FULL PAGE DISPLAY
	$GLOBALS['nosidebar-left']=1;
	$GLOBALS['nosidebar-right']=1;
	get_header(); 

?>


<div class="padding">

<?php premiumpress_packages_before(); /* HOOK */ ?>
 
    <h1><?php echo $PPT->_e(array('add','1')); ?></h1>
    
    <?php if(strlen($post->post_content) > 2){ echo wpautop($post->post_content); }else{  // display the template page content regardless ?>
    
    <p><?php echo $PPT->_e(array('add','2')); ?></p> 
    
    <?php } ?>
 
<?php $i=1; $div=1; 

foreach($packdata as $package){
	
	if($div == 1 ){ $STRING .= '<div class="grid col4" id="griddler">'; } 
 
	if(isset($package['enable']) && $package['enable'] == 1){ 	

	$STRING .= '<article>

		<header>

			<hgroup class="top">

				<h1>'.$package['name'].'</h1>

			</hgroup>

			<hgroup class="price">

				<h2>';
				
				if($package['price'] > 0){ 
				
				$STRING .= premiumpress_price($package['price'],"<span class='currency'>".$GLOBALS['premiumpress']['currency_symbol']."</span>",$GLOBALS['premiumpress']['currency_position'],1); 
				
				}else{ 
				
				$STRING .= $PPT->_e(array('add','3'));
				
				}
				
				$STRING .= '</h2>

			</hgroup>

		</header>

		<section>
        
		<div class="clearfix"></div>

			<ul>
            <li class="time tooltip-holder">';
			
			// IF ITS A RECURRING PACKAGE
			if(isset($packdata[$i]['rec']) && $packdata[$i]['rec'] ==1){ 			
			
				$STRING .= str_replace("%a",$packdata[$i]['expire'],$PPT->_e(array('add','4')));  
			
            	$STRING .= '<div class="tooltip">
						<div>
							<h3>'.$PPT->_e(array('add','5')).'</h3>
							<p>'.str_replace("%a",$packdata[$i]['expire']." ".$PPT->_e(array('date','1')),$PPT->_e(array('add','6'))).'</p>
						</div>
					</div>';
            
			// IF IT HAS AN EXPIRY VALUE
			}elseif( isset($packdata[$i]['expire']) && $packdata[$i]['expire'] > 1 ){
			
			
				$STRING .= str_replace("%a",$packdata[$i]['expire'],$PPT->_e(array('add','4'))); 
			 
            
             	$STRING .= '<div class="tooltip">
						<div>
							<h3>'.$PPT->_e(array('add','7')).'</h3>
							<p>'.str_replace("%a",$packdata[$i]['expire']." ".strtolower($PPT->_e(array('date','2'))), $PPT->_e(array('add','8'))).'</p>
						</div>
					</div>';
            
            }else{ $STRING .= $PPT->_e(array('add','9')); /* NO TIME LIMIT */ }			 
            	
            
            $STRING .= '</li>';			
			
			
			// MAYBE IT HAS A FREE TRIAL?
            if(isset($packdata[$i]['freetrialp']) && $packdata[$i]['freetrialp'] !=""){ 
				$STRING .= " <li class='star'>".$packdata[$i]['freetrialp']." Day Free Trial</li>"; 					
			}			
        
          	// DISPLAY THE HARD CODED PACKAGE FEATURED // HTML // CATEGORIES ETC
			if(CanShowRow($packdata, "a1")){  if( isset($packdata[$i]['a1']) && $packdata[$i]['a1'] == 1 ){ $a1 = "yes"; }else{ $a1 = "no"; } $STRING .= '<li class="'.$a1.'" id="a1">'.$PPT->_e(array('add','10')).'</li>'; }
			if(CanShowRow($packdata, "a2")){  if( isset($packdata[$i]['a2']) && $packdata[$i]['a2'] == 1 ){ $a2 = "yes"; }else{ $a2 = "no"; } $STRING .= '<li class="'.$a2.'" id="a2">'.$PPT->_e(array('add','11')).'</li>'; }
			if(CanShowRow($packdata, "a3")){  if( isset($packdata[$i]['a3']) && $packdata[$i]['a3'] == 1 ){ $a3 = "yes"; }else{ $a3 = "no"; } $STRING .= '<li class="'.$a3.'" id="a3">';
			if($a3 == "yes"){	
			if(!isset($packdata[$i]['uploadlimit'])){ $packdata[$i]['uploadlimit'] = ""; }
			$STRING .= str_replace("%a",$packdata[$i]['uploadlimit'],$PPT->_e(array('add','12'))); }else{ $STRING .= $PPT->_e(array('add','15')); }
			$STRING .= '</li>';			
			}
			if(CanShowRow($packdata, "a4")){  if( isset($packdata[$i]['a4']) && $packdata[$i]['a4'] == 1 ){ $a4 = "yes"; }else{ $a4 = "no"; } $STRING .= '<li class="'.$a4.'" id="a4">'.$PPT->_e(array('add','14')).'</li>'; }
			if(CanShowRow($packdata, "a5")){  if( isset($packdata[$i]['a5']) && $packdata[$i]['a5'] == 1 ){ $a5 = "yes"; }else{ $a5 = "no"; } $STRING .= '<li class="'.$a5.'" id="a5">'.$PPT->_e(array('add','13')).'</li>'; } 
           
			// HERE WE WILL SHOW THE CUSTOM FIELDS SETUP IN THE ADMIN
     		if(get_option('pak_show_fields') == 1){
			
			$customdata = multisort( $customdata, array('order') );
			
            if(is_array($customdata)){ $cc =0; 
			
			
			foreach($customdata as $cdata){			 
			 
            if( strlen($cdata['name']) > 0 ){
			
			// echo $i."-------------";
			// print_r($cdata);
			// echo "<br><br>";
            
            	if( isset($cdata['pack'.$i]) && $cdata['pack'.$i] == 1 ){ $astyle = 'yes'; }else{ $astyle = 'no';}
                
				// DONT SHOW THE FIELD TITLES
				if(isset($cdata['fieldtitle']) && $cdata['fieldtitle'] == 1){  }else{
				
					// tooltip extra                    
					if(strlen($cdata['desc1']) > 1){ $exp ="tooltip-holder"; }else{  $exp =""; }
										
						$STRING .= '<li class="'.$astyle.' '.$exp.'" id="c'.$cc.'">'. $cdata['name'];
					
						if(strlen($cdata['desc1']) > 1){
						
							$STRING .= '<div class="tooltip">
								<div>
									<h3>'. $cdata['name'].'</h3>
									<p>'.$cdata['desc1'].'</p>
								</div>
							</div>';	
								 
						}// end if
						
						$STRING .= '</li>';
						
					}  // end tooltip
				
				} //end skip title
				
				$cc++;
			
              }
			
			}
             
			}           

			$STRING .= '</ul>

			<div class="griddler-controls"><a class="button" href="#" onclick="document.getElementById(\'packageID\').value=\''.$i.'\';document.hiddenPackage.submit();">'.$PPT->_e(array('add','16')).'</a></div>			

		</section>

	</article>';
    
     } 
    
    if($div == 4){ $STRING .= '</div><div class="clearfix"></div>'; $div=0; } 
    
    $i++; $div++; } 
	
	echo premiumpress_packages_inside($STRING);
	
?> 


<?php if(strlen($extraText) > 2){ ?><br /><div class="extrainfo"><?php echo stripslashes($extraText); ?></div><?php } ?>
 
<?php premiumpress_packages_after(); /* HOOK */ ?>

<form name="hiddenPackage" action="" method="post">
<input type="hidden" name="packageID" id="packageID" value="1" />
</form> 
 
<script type="text/javascript">
Date.firstDayOfWeek = 0;
Date.format = 'yyyy-mm-dd';
jQuery(document).ready(function() {  
	var $gridSections = jQuery("#griddler article");	
	$gridSections.hover
	(
		function()
		{
			$gridSections.removeClass("selected");
		}
	);
});
</script><?php 

get_footer();

/* =============================================================================
   2. LOAD THE SUBMISSION FORM
   ========================================================================== */ 


}else{

$GLOBALS['UploadForm']=1; 

// LIGHTBOX FOR IMAGE POPUP
wp_register_script( 'colorbox',  get_template_directory_uri() .'/PPT/js/jquery.colorbox-min.js');
wp_enqueue_script( 'colorbox' );

wp_register_style( 'colorbox',  get_template_directory_uri() .'/PPT/css/css.colorbox.css');
wp_enqueue_style( 'colorbox' );

// DATE PICKER STYLES			
wp_register_script( 'datepicker',  PPT_THEME_URI."/PPT/js/jquery.date.js");
wp_enqueue_script( 'datepicker' );

wp_register_script( 'datepicker1',  PPT_THEME_URI."/PPT/js/jquery.date_pick.js");
wp_enqueue_script( 'datepicker1' );

wp_register_style( 'datepicker',  PPT_THEME_URI."/PPT/css/css.date.css");
wp_enqueue_style( 'datepicker' );

if(isset($_POST['packageID']) && isset($GLOBALS['PACKAGE_OPTIONS'][$_POST['packageID']]['a4']) && $GLOBALS['PACKAGE_OPTIONS'][$_POST['packageID']]['a4'] ==1 || $GLOBALS['PACKAGES_ENABLE'] !=1 ){ 

wp_register_script( 'googlemap',  'http://maps.googleapis.com/maps/api/js?sensor=false');
wp_enqueue_script( 'googlemap' );
			
		 
			// LOAD IN GOOGLE MAP CODE
			function ppt_newmapcode(){
			?>
			<script type="text/javascript"> 
            var geocoder;var map;var marker = '';
            function initialize() {var myLatlng = new google.maps.LatLng(0,0);var myOptions = { zoom: 1,  center: myLatlng,  mapTypeId: google.maps.MapTypeId.ROADMAP}
            map = new google.maps.Map(document.getElementById("ppt_map_location"), myOptions);google.maps.event.addListener(map, 'click', function(event){
			document.getElementById("map-long-lat").value = 'lat:' + event.latLng.lat() + ',long:' + event.latLng.lng();	getMyAddress(event.latLng);	addMarker(event.latLng);});}
            function getMapLocation(location){var geocoder = new google.maps.Geocoder();if (geocoder) {	geocoder.geocode({"address": location}, function(results, status) {	if (status == google.maps.GeocoderStatus.OK) {
			document.getElementById("form_map_location").value = results[0].formatted_address;	map.setCenter(results[0].geometry.location);map.setZoom(9);}});}}
            function getMyAddress(location){var geocoder = new google.maps.Geocoder();if (geocoder) {geocoder.geocode({"latLng": location}, function(results, status) { if (status == google.maps.GeocoderStatus.OK) {
			document.getElementById("form_map_location").value = results[0].formatted_address;map.setCenter(results[0].geometry.location);		}		});	}} 
            function addMarker(location) {if (marker=='') {	marker = new google.maps.Marker({	position: location, 	map: map,	});}
            marker.setPosition(location);map.setCenter(location);  }
            jQuery(document).ready(function() {  initialize();  })
            </script>            
            <?php
			}
			add_action('wp_footer',  'ppt_newmapcode' , 10);
}
			


 
					
			

get_header();

?> 
 
<div id="step2box" style="display:visible;"> 
 

<form action="" name="refreshPIDform" id="refreshPIDform" method="post"> 
<input type="hidden" name="packageID" value="<?php echo $_POST['packageID']; ?>" />
<input type="hidden" name="TryPackageID" value="<?php echo $_POST['packageID']; ?>" id="refreshPID" />
</form> 

       
<form action="" name="SUBMITFORM" id="SUBMITFORM" method="post" onsubmit="return CheckFormData();" enctype="multipart/form-data"> 
<input type="hidden" name="action" value="step1" />
<input type="hidden" name="packageID" value="<?php echo $_POST['packageID']; ?>" />
<input type="hidden" name="step1" value="1" />
<?php if(isset($_POST['TryPackageID']) && is_numeric($_POST['TryPackageID']) ){ echo '<input type="hidden" name="TryPackageID" value="'.strip_tags($_POST['TryPackageID']).'" />'; } ?>
<?php if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){ ?><input type="hidden" name="eid" value="<?php echo $_GET['eid']; ?>" /><?php } ?>
<?php if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['a1']) && $PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1 || $GLOBALS['PACKAGES_ENABLE'] != 1){ ?><input type="hidden" name="htmlcode" value="1" /><?php } ?>

 
<div class="itembox">
    
	<h1><?php if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['name'])){ echo strip_tags($PACKAGE_OPTIONS[$_POST['packageID']]['name']); }else{ echo $PPT->_e(array('add','52')); } ?></h1>
        
	<div class="itemboxinner">
 
	<fieldset> 
  
	<?php  premiumpress_packages_step1_before(); /* HOOK */
	
	// HIDE DEFAULT FIELDS // V7.0.9.5 // MAY 1ST	
	$dfs = get_option('default_form_fields');


	// DISPLAY THE PACKAGE SELECTION BOX FOR EDITING PACKAGES
	$o=0; unset($field); $field = array(); $GLOBALS['numcounter'] = 1;
	
	if($GLOBALS['PACKAGES_ENABLE'] ==1  && isset($_GET['eid'])  ){	
	
		$field[$o]['title'] 	= "";
		$field[$o]['name'] 		= "";
		$field[$o]['dataname'] 	= "";
		$field[$o]['type'] 		= "packageselect";
		$field[$o]['required'] 	= true;
		$o++;
	
	}
	
	if($dfs['title'] == "1" && $dfs['category'] == "1" && $dfs['tagline'] == "1" && $dfs['excerpt'] == "1"){
	}else{
	$field[$o]['title'] 	= $PPT->_e(array('add','17'));
	$field[$o]['name'] 		= "titlebar";
	$field[$o]['num'] 		= $GLOBALS['numcounter'];
	$o++; $GLOBALS['numcounter']++;
	}
 
 	if($dfs['title'] != "1"){ 
		$field[$o]['title'] 	= $PPT->_e(array('add','18'));
		$field[$o]['name'] 		= "title";
		$field[$o]['dataname'] 	= "post_title";
		$field[$o]['type'] 		= "text";
		$field[$o]['required'] 	= true;
		$o++;
	}
	if($dfs['category'] != "1"){ 
		$field[$o]['title'] 	= $PPT->_e(array('add','19'));
		$field[$o]['name'] 		= "";
		$field[$o]['type'] 		= "category";
		$field[$o]['required'] 	= true;
		$o++;
	}
	if($dfs['tagline'] != "1"){ 
		$field[$o]['title'] 	= $PPT->_e(array('add','20'));
		$field[$o]['name'] 		= "tagline";
		$field[$o]['dataname'] 	= "tagline";
		$field[$o]['type'] 		= "longtext";
		$field[$o]['subtext'] 	= $PPT->_e(array('add','21'));	
		$field[$o]['required'] 	= true;	
		$o++;
	}
	if($dfs['excerpt'] != "1"){
		$field[$o]['title'] 	= $PPT->_e(array('add','22'));
		$field[$o]['name'] 		= "short";
		$field[$o]['dataname'] 	= "post_excerpt";
		$field[$o]['type'] 		= "textarea";
		$field[$o]['required'] 	= true;
		$field[$o]['subtext'] 	= $PPT->_e(array('add','23'));
		$o++;
	}	
	
	if($dfs['content'] == "1" && $dfs['tags'] == "1"){
	}else{
	$field[$o]['title'] 	= $PPT->_e(array('add','24'));
	$field[$o]['name'] 		= "titlebar";
	$field[$o]['num'] 		= $GLOBALS['numcounter'];
	$o++; $GLOBALS['numcounter']++;
 	}
	
 	if($dfs['content'] != "1"){
		$field[$o]['title'] 	= $PPT->_e(array('title','19'));
		$field[$o]['name'] 		= "description";
		$field[$o]['dataname'] 	= "post_content";
		$field[$o]['type'] 		= "textarea";
		if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['a1']) && $PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1 ||  $GLOBALS['PACKAGES_ENABLE'] != 1){ 
		$field[$o]['editor'] 	= true;
		}
		$field[$o]['required'] 	= true;
		$o++;
	}
	if($dfs['tags'] != "1"){
		$field[$o]['title'] 	= $PPT->_e(array('add','25'));
		$field[$o]['name'] 		= "tags";
		$field[$o]['dataname'] 	= "tags";
		$field[$o]['type'] 		= "text";
		$field[$o]['required'] 	= true;
		$field[$o]['subtext'] 	= $PPT->_e(array('add','26'));
		$o++;
	}
	
	
	$field[$o]['title'] 	= $PPT->_e(array('add','27'));
	$field[$o]['name'] 		= "titlebar";
	$field[$o]['num'] 		= $GLOBALS['numcounter'];
	$o++; $GLOBALS['numcounter']++;


	if($dfs['email'] != "1"){
		$field[$o]['title'] 	= $PPT->_e(array('add','28'));
		$field[$o]['name'] 		= "email";
		$field[$o]['dataname'] 	= "email";
		$field[$o]['type'] 		= "text";
		$field[$o]['required'] 	= true;
		$o++;
	}
	if($dfs['url'] != "1"){
		$field[$o]['title'] 	= $PPT->_e(array('add','29'));
		$field[$o]['name'] 		= "url";
		$field[$o]['dataname'] 	= "url";
		$field[$o]['type'] 		= "text";
		$field[$o]['subtext'] 	= $PPT->_e(array('add','30'));
		$o++; 
	}
 
	// OUTPUT FIELDS
	$field = premiumpress_packages_step1_fields($field);
	echo $PPTDesign->BuildFields($field,$PACKAGE_OPTIONS, $data);
	unset($field);
	
	// DISPLAY COUNTRY FIELD
	if(get_option("display_country") =="yes"){  echo $PPTDesign->DisplayCountry($data); }

	// ADMIN CUSTOM FIELDS 
	$CUSTOMFIELDDATA = "<div class='clearfix'></div>";
	$CUSTOMFIELDDATA .= premiumpress_packages_step1_fields($PPTDesign->TPL_ADD_CUSTOMFIELDS($data,$_POST['packageID']));
	if(strlen($CUSTOMFIELDDATA) > 2){
		echo $CUSTOMFIELDDATA;
	}
	
	// GOOGLE MAPS EXTRA
 	if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['a4']) && $PACKAGE_OPTIONS[$_POST['packageID']]['a4'] ==1 || $GLOBALS['PACKAGES_ENABLE'] !=1 ){ 
	
		$o=0; unset($field); $field = array();
		$field[$o]['title'] 	= $PPT->_e(array('add','31'));
		$field[$o]['name'] 		= "titlebar";
		$field[$o]['num'] 		= $GLOBALS['numcounter'];
		$o++; $GLOBALS['numcounter']++;
	 
		$field[$o]['title'] 	= $PPT->_e(array('add','32'));
		$field[$o]['name'] 		= "map_location";
		$field[$o]['dataname'] 	= "map_location";
		$field[$o]['type'] 		= "text";
		$field[$o]['required'] 	= true;
		$field[$o]['subtext'] 	= $PPT->_e(array('add','33'));
		$o++;
	
		// OUTPUT FIELDS
		$field = premiumpress_packages_step1_fields($field);
		echo $PPTDesign->BuildFields($field,$PACKAGE_OPTIONS, $data);
		unset($field);
	
	}	

?>         
          
       
<div class="clearfix"></div> 


       <?php 
	   
	   
	   // DIRECTORYPRESS ONLY sorry :(
	   
	   if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "directorypress"){ if(get_option('display_rlink') =="yes"  && $PACKAGE_OPTIONS[$_POST['packageID']]['a6'] != 1 && strlen(get_option("display_rlink_text")) > 1 ){
	   
	    $o=0; unset($field); $field = array();
	   	$field[$o]['title'] 	= $PPT->_e(array('add','34'));
		$field[$o]['name'] 		= "titlebar";
		$field[$o]['num'] 		= $GLOBALS['numcounter'];
		$o++; $GLOBALS['numcounter']++;
		
		// OUTPUT FIELDS
		$field = premiumpress_packages_step1_fields($field);
		echo $PPTDesign->BuildFields($field,$PACKAGE_OPTIONS, $data);
		unset($field);
		
	    ?>
       
       <div class="clearfix"></div>
       
        
        
       <div class="green_box"><div class="green_box_content"><?php echo $PPT->_e(array('add','35')); ?><div class="clearfix"></div></div></div>
        
        
       <p class="full clearfix box">
         <label><?php echo $PPT->_e(array('add','36')); ?></label>
         <textarea style="height:60px; overflow:auto; border:1px solid #ccc; background:#efefef; padding:10px;"  class="long" tabindex="12" ><?php echo stripslashes(get_option("display_rlink_text")); ?></textarea> 
       </p>
       <p class="full clearfix box"> 
       
        <label><?php echo $PPT->_e(array('add','37')); ?></label><small><?php echo $PPT->_e(array('add','38')); ?></small>
        <input type="text" name="r_link" class="long"  value="<?php if(isset($_POST['action']) && !isset($data) ){ echo $_POST['r_link']; } ?>" />	             
       
       </p>
		  
        
       <?php }else{ print "<input type='hidden' name='norec' value='1'>"; } if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['a6']) && $PACKAGE_OPTIONS[$_POST['packageID']]['a6'] == 1){ print "<input type='hidden' name='norec' value='1'>"; } } 
	   
	   // END DIRECTORYPRESS ONLY
	 
	 
       // FILE UPLOAD FORM INTEGRATED INTO VERSION 7	
        $canShow=1;  $STRING = "";		
        if(get_option("display_fileupload") =="yes" ){  if($GLOBALS['PACKAGES_ENABLE'] ==1 && $PACKAGE_OPTIONS[$_POST['packageID']]['a3'] !=1){  $canShow=0; }  }	
   
        if($canShow ==1){
        
            // DETERMIN THE UPLOAD LIMIT
			if($GLOBALS['PACKAGES_ENABLE'] ==1 && isset($PACKAGE_OPTIONS[$_POST['packageID']]['uploadlimit']) ){			
				$UploadLimitHere = $PACKAGE_OPTIONS[$_POST['packageID']]['uploadlimit'];			
			}else{			
				$UploadLimitHere = get_option('display_fileupload_max');			
			}
		 	if($UploadLimitHere == ""){ $UploadLimitHere=10; }
			
			// GET THE CURRENT TOTAL OF IMAGES UPLOADED BY USER
			$CURRENTT = 0;
			if(isset($_GET['eid'])){
			$CIMG = explode(",",substr(get_post_meta($_GET['eid'], 'images', true),0,-1));			 
			if(is_array($CIMG)){ foreach($CIMG as $img1){if(strlen($img1) > 1){ $CURRENTT++; } } }
			
			} 
			
			//echo $CURRENTT."< ".$UploadLimitHere.substr(get_post_meta($_GET['eid'], 'images', true),0,-1);
			 
            // DISPLAY THE UPLOAD FORM
            if(get_option("display_fileupload") =="yes" && $CURRENTT <  $UploadLimitHere){ 
			 
            $STRING .= '<h4><span>'.$GLOBALS['numcounter'].'</span>'.$PPT->_e(array('add','39')).'</h4>';
            $STRING .= '<div class="green_box"><div class="green_box_content"> 
            <div class="f1 left">
            <input type="hidden" name="upa" id="upa" value="1">
            <div id="pptupload" class="button green">'.$PPT->_e(array('add','40')).'</div>            
            <span id="pptstatus"></span>				
            </div>
            <div class="f4 left"> 
            <ul id="pptfiles">';		
			
				   
				  // RE DISPLAY IMAGES FOR THOSE USING THE BACK BUTTON 
				  if(isset($_POST['files']) && is_array($_POST['files']) && !empty($_POST['files']) && isset($_POST['action'])){
				  foreach($_POST['files'] as $file){
				  
				  $STRING .= '<li class="pptsuccess"><div class="yellow_box"><div class="yellow_box_content"><span><small>'.strip_tags($file).'</small></span><input type="hidden" name="files[]" value="'.strip_tags($file).'">
					<a href="'.get_option('imagestorage_link').strip_tags($file).'" target="_blank" class="lightbox">
					  <img src="'.get_option('imagestorage_link').strip_tags($file).'"></a><div class="clearfix"></div></div></div><div class="clearfix"></div></li>';
					 
				  
				  }
				  }
			
			
			$STRING .= '</ul>			
            </div><div class="clearfix"></div></div>			
            </div>'; 
			$GLOBALS['numcounter']++;
            
            }else{  $STRING .= '<input type="hidden" id="pptupload">'; } 
                
            // SHOW EDIT FORM
            if(isset($_GET['eid'])){
                   $ES = '<h2>'.$PPT->_e(array('add','41')).'</h2>';
                   $ES .= '<div class="PhotoSwitcher1 clearfix"><ul>';
                   $ES1 = premiumpress_upload_edit($_GET['eid']); 
				   $ES .= $ES1;
                   $ES .= '</ul></div><div class="clearfix"></div>'; 
            }
			// HIDE DISPLAY IF NO IMAGES  
			if(strlen($ES1) > 1){
				$STRING .= $ES;
			}
                
         }else{  $STRING .= '<input type="hidden" id="pptupload">'; } 
         
         echo premiumpress_packages_step2_images($STRING); /* HOOK */
         
         ?>
           
           
         
      	<?php if(strlen(get_option("tc_url")) > 1){ ?>
        
        <h4><span><?php echo $GLOBALS['numcounter']; ?></span><?php echo $PPT->_e(array('add','42')); ?></h4>
        <script type="text/javascript"> jQuery(document).ready(function() { jQuery('#submitMe').attr('disabled', true);  }); 
        
        function UnDMe(){
	
			if ( jQuery('#submitMe').is(':disabled') === false) { 
			
				jQuery('#submitMe').attr('disabled', true);  jQuery('#submitMe').removeClass('green'); jQuery('#submitMe').addClass('gray');
				
			} else {
			
				jQuery('#submitMe').attr('disabled', false); jQuery('#submitMe').addClass('green');
			
			}		
		
		}
        </script>
        <!-- START TERMS BOX -->     
        <div class="green_box"><div class="green_box_content">         
            <input type="checkbox" id="agreeTC" name="interests" class="radio" tabindex="8" onclick="javascript:UnDMe();">
            <a href="<?php echo get_option("tc_url"); ?>" style="color:blue; text-decoration:underline;" target="_blank"><?php echo $PPT->_e(array('add','43')); ?></a>
           
        </div>
        </div> 
        <!-- END TERMS BOX -->    
            
		<?php } ?> 
            
           
    
         <?php premiumpress_packages_step1_after(); ?>  
        
    
	</fieldset> <!-- end fieldset form-->

	</div><!-- end inner itembox -->
    
	<!-- start buttons -->
    <div class="enditembox inner">
    <input type="button" onclick="window.location='<?php echo get_option('submit_url'); ?>'"  class="button gray right" tabindex="15" value="<?php echo $PPT->_e(array('button','8')); ?>" />
    <input type="submit" name="submit" id="submitMe" class="button <?php if(strlen(get_option("tc_url")) > 1){ echo "gray"; }else{ echo "green"; }?> left" tabindex="15" value="<?php echo $PPT->_e(array('button','10')); ?>"/>
    </div><!-- end buttons -->
    
</div> <!-- end itembox -->

</form>         
        
</div><!-- end step box 1 -->













<!-- STEP 2 SECTION -->
<div id="step3box" style="display:none;">


<div class="itembox">
    
	<h2><?php echo $PPT->_e(array('add','44')); ?></h2>
        
	<div class="itemboxinner"> 
    
    <?php premiumpress_packages_step2_before(); ?> 
 

	<script language="javascript" type="text/javascript"> function SaveDD(){ document.getElementById("description_hidden").value = document.getElementById("description_display").innerHTML; return true; } </script>

    <form action="#" name="SaveListing" method="post" enctype="multipart/form-data" onSubmit="return SaveDD()"> 
    <input type="hidden" name="step3" value="1" />
    <?php if(isset($_POST['files']) && is_array($_POST['files'])){ foreach($_POST['files'] as $file){ echo '<input type="hidden" name="files[]" value="'.$file.'" />'; } } ?> 
    <?php if(is_array($_POST['CatSel'])){ $a=0; foreach($_POST['CatSel'] as $key=>$val){ echo '<input type="hidden" name="CatSel['.$a.']" value="'.$val.'" />'; $a++; } } ?> 
    <input type="hidden" name="action" value="<?php if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){ ?>edit<?php }else{ ?>add<?php } ?>" />
    <?php if(isset($_POST['eid']) && is_numeric($_POST['eid']) ){ ?><input type="hidden" name="eid" value="<?php echo $_POST['eid']; ?>" /><?php } ?>
    <input type="hidden" name="packageID" value="<?php echo $_POST['packageID']; ?>" />
    <input type="hidden" name="NEWpackageID" value="<?php echo $_POST['NEWpackageID']; ?>" />
    
    <?php if(isset($PACKAGE_OPTIONS[$_POST['packageID']]['a1']) && $PACKAGE_OPTIONS[$_POST['packageID']]['a1'] == 1 || $GLOBALS['PACKAGES_ENABLE'] != 1){ ?><input type="hidden" name="htmlcode" value="1" /><?php } ?>
    
     <?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) == "directorypress" && isset($_POST['r_link'])){ ?><input type="hidden" name="reclink" value="<?php echo strip_tags(strip_tags(str_replace("http://","",$_POST['r_link']))); ?>" /><?php } ?>
     
    <?php $keysArray = array(
	
	'title' 		=> $PPT->_e(array('add','18')),
	'short' 		=> $PPT->_e(array('add','22')),
	'description' 	=> $PPT->_e(array('add','24')),
	'tags' 			=> $PPT->_e(array('add','25')),
	'country'		=> $PPT->_e(array('myaccount','15')),
	'state' 		=> $PPT->_e(array('myaccount','30')),
	'city'			=> $PPT->_e(array('myaccount','16')),
	'zip'			=> $PPT->_e(array('myaccount','18')),
	
	'url' 			=> $PPT->_e(array('add','29')),	
	'email' 		=> $PPT->_e(array('add','28')),
	'map_location'	=> $PPT->_e(array('add','32')),
	'tagline'		=> $PPT->_e(array('add','20')), 
	
	);  
	$keysArray = premiumpress_packages_step2_keys($keysArray);
	
	$termArray = array ( 'country','state','city' );
	
	?>
    
    
    <?php if(!isset($_POST['form']['trackingID'])){ ?>	<input type="hidden" name="form[trackingID]" value="<?php echo RandomID(7); ?>" /><?php } ?>
   
   
    <div class="full clearfix  box">   
      <p class="f1 left"><b><?php echo $PPT->_e(array('add','19')); ?></b></p>
	  <p class="f4 left"> <?php echo $PPT->CategoryFromID($_POST['CatSel']); ?></p>      
    </div>
        
        
     
    <?php if(is_array($_POST['form'])){ foreach($_POST['form'] as $key=>$value){ if($value != ""){ ?>
        
    <div class="full clearfix border_t box">  
      <div class="f1 left"><p><b><?php echo $keysArray[$key]; ?></b></p></div>
	  <div class="f4 left" id="<?php echo $key; ?>_display"><p><?php if(in_array($key,$termArray)){ $n = get_term_by('id', $value, 'location'); echo $n->name; }else{ echo $PPTDesign->TPLADD_Value($key,$value); } ?>&nbsp;</p></div>      
    </div>         
    
   <input type="hidden" name="form[<?php echo $key; ?>]" id="<?php echo $key; ?>_hidden" value="<?php echo strip_tags(PPTCLEAN($value,$key)); //htmlentities() ?>" />
 
   <?php }else{ echo "<input type='hidden' name='form[".$key."]' id='". $key."_hidden' value='' />"; }  } } ?>
   
   
   
   <?php $i = 0; if(is_array($_POST['custom'])){  foreach($_POST['custom'] as $cus){ if($cus['value'] != ""){ ?>
   
    <div class="full clearfix border_t box">  
      <div class="f1 left"><p> <b><?php echo $PPTDesign->GL_CustomKeyName($cus['name']); ?></b></p></div>
	  <div class="f4 left"><p><?php echo nl2br(stripslashes(strip_tags($cus['value']))); ?>&nbsp;</p></div>      
    </div>   
  	<input type="hidden" name="custom[<?php echo $i; ?>][name]" value="<?php echo $cus['name']; ?>" />
    <input type="hidden" name="custom[<?php echo $i; ?>][value]" value="<?php echo strip_tags(PPTCLEAN($cus['value'],$cus['type'])); ?>" />
   
   <?php }else{ echo '<input type="hidden" name="custom['.$i.'][name]" value="'.$cus['name'].'" /><input type="hidden" name="custom['.$i.'][value]" value="" />'; }  $i++; } }  ?>
 
 
 
   
    <?php $i = 0; if(is_array($_POST['taxonomy'])){ foreach($_POST['taxonomy'] as $key=>$value){ if($value != "gggggggggggg"){ ?>
        
    <div class="full clearfix border_t box">  
      <div class="f1 left"><p><b><?php echo $keysArray[$key]; ?></b></p></div>
	  <div class="f4 left" id="<?php echo $key; ?>_display"><p><?php
	  
	  if($value == "new"){ echo strip_tags($_POST[$key.'_new']); }else{ $n = get_term_by('id', $value, $key); echo $n->name; } ?>&nbsp;</p></div>      
    </div>         
    
   <input type="hidden" name="taxonomy[<?php echo $key; ?>]" id="<?php echo $key; ?>_hidden" value="<?php echo strip_tags(PPTCLEAN($value,$key));?>" /> 
  <?php if($value == "new"){?>
  <input type="hidden" name="<?php echo $key; ?>_new" id="<?php echo $key; ?>_hidden" value="<?php echo strip_tags($_POST[$key.'_new']);?>" />
  <?php } ?>
   <?php }else{ echo "<input type='hidden' name='form[".$key."]' id='". $key."_hidden' value='' />"; }  } } ?>
     
    
    <br />
    
    <div class="clearfix"></div>

   
    <?php premiumpress_packages_step2_before(); ?>    
    
    </div><!-- step 2 item box -->
    
    <!-- start buttons -->
    <div class="enditembox inner">
    
    <input type="submit" class="button green left" tabindex="15" value="<?php echo $PPT->_e(array('button','10')); ?>" />
    
    <input type="button" onclick="jQuery('#step2box').show();jQuery('#step3box').hide()"  class="button gray right" tabindex="15" value="<?php echo $PPT->_e(array('button','7')); ?>" />         
    
    </div>
    <!-- end buttons -->
    
    </form>
    
	</div>  
 
 
     <?php if(isset($_GET['eid'])){ ?>
    <form name="editimageform" id="editimageform" method="post" action="" target="upload_target">
    <input type="hidden" value="" name="display" id="display" />
    <input type="hidden" value="" name="pid" id="pid" />
    <input type="hidden" value="<?php echo $_GET['eid']; ?>" name="eid" id="eid" /> 
    </form>
    <iframe id="upload_target" name="upload_target" src="#" style="width:0px;height:0px;border:0px solid #fff; margin-left:0px;margin-top:0px; "></iframe>
	<?php } ?>
 
</div> 
<!-- end STEP 2 SECTION --> 












 
<!-- STEP 4 SECTION -->
<div id="step4box" style="display:none;">

<?php if(isset($_POST['step3'])){ if(isset($_POST['price']['total']) && $_POST['price']['total'] > 0){ ?>

<div class="itembox">
    
	<h2><?php echo $PPT->_e(array('add','45')); ?></h2>
        
	<div class="itemboxinner greybg"> 
    
    <?php premiumpress_packages_step3_before(); /* HOOK */ ?> 

    <div class="full clearfix box"> <br />
    
    <p style="font-size:16px;"><?php echo $PPT->_e(array('add','46'));  ?> <?php echo premiumpress_price($_POST['price']['total'],$GLOBALS['premiumpress']['currency_symbol'],$GLOBALS['premiumpress']['currency_position'],1); ?></p>
     
    <?php premiumpress_packages_step3_inside_payment(); /* HOOK */  
	
	$i=1;
    if(is_array($gatway)){
		
		foreach($gatway as $Value){
		
			if(get_option($Value['function']) =="yes" ){ // GATEWAY IS ENABLED 
      
				if( $Value['function'] == "gateway_bank"){ // bank details form  ?>                
                
                <div class="gray_box"><div class="gray_box_content"> 
                
                	<h3><?php echo get_option($Value['function']."_name"); ?></h3>
                
                	<p><?php echo nl2br(get_option("bank_info")); ?></p>
                    
                	<div class="clearfix"></div>
                
                </div></div>              
                
                
       	<?php  }elseif( $Value['function'] != "gateway_paypalpro" && $Value['function'] != "gateway_ewayAPI" && $Value['function'] !="gateway_blank_form"){  ?>  
                
               <div class="gray_box"><div class="gray_box_content">
               
               <?php if(strlen(get_option($Value['function']."_icon")) > 5){ echo "<a href='javascript:void();' class='frame left' style='margin-right:10px;'><img src='".get_option($Value['function']."_icon")."' /></a>"; } ?> 
                
                	<h3 class="left" style="margin-top:2px;"><?php echo get_option($Value['function']."_name"); ?></h3>
                
                	<?php echo $Value['function']($_POST); ?>
                    
                <div class="clearfix"></div>
                
                </div></div> 
                
         <?php }else{ ?>
                
                <div class="gray_box"><div class="gray_box_content">
                
                <?php echo $Value['function']($_POST); ?>
                
                <div class="clearfix"></div>
                
                </div></div> 
                		
        <?php } } } }    ?>
             
        <?php premiumpress_packages_step3_after(); /* HOOK */ ?>              
      
    
    </div> </div> 
                
    <?php if($userdata->wp_user_level == "10"){  ?>
 
     <p style="padding:6px; color:white;background:red; margin-top:20px;"><b>You are only seeing this because you are the admin :)</b> - <br /> <a href="#" onclick="document.AdminTest.submit();" style="color:white;">Click here to skip payment and test callback link.</a> </p>


    <form name="AdminTest" id="AdminTest" action="<?php echo $GLOBALS['bloginfo_url']; ?>/callback/" method="post">
    <input type="hidden" name="custom" value="<?php echo $_POST['orderid']; ?>">
    <input type="hidden" name="payment_status" value="Completed">
    <input type="hidden" name="mc_gross" value="<?php echo $_POST['price']['total']; ?>" />
    </form> 


	<?php } }else{ ?>   
    
<div class="itembox">
    
	<h1><?php echo $PPT->_e(array('add','47')); ?></h1>
        
	<div class="itemboxinner greybg">
    
    <?php premiumpress_packages_step3_before(); /* HOOK */ ?>
    
    <div class="full clearfix box">
    
    <h3><?php echo $PPT->_e(array('add','48')); ?></h3>
    
    <?php premiumpress_packages_step3_inside_updated(); /* HOOK */ ?>
    
    <p class="topper"><a href="<?php echo get_option("manage_url"); ?>" style="text-decoration:underline;"><?php echo $PPT->_e(array('add','49')); ?></a></p>       

    </div>
    
    <?php premiumpress_packages_step3_after(); /* HOOK */ ?>  
    
    </div>
    
    <?php } ?>             

</div>

<?php }else{ ?>

<div class="itembox">

    <h1><?php echo $PPT->_e(array('add','47')); ?></h1>
    
    <div class="itemboxinner greybg">
    
    <?php premiumpress_packages_step3_before(); /* HOOK */ ?>
    
    <div class="full clearfix border_t box"> <br />
    
    <?php premiumpress_packages_step3_inside_updated(); /* HOOK */ ?>
    
    <p><?php echo $PPT->_e(array('add','48')); ?></p>
    
    <p><a href="<?php echo get_option("manage_url"); ?>"><?php echo $PPT->_e(array('add','49')); ?></a></p>
    
    </div>
    
    <?php premiumpress_packages_step3_after(); /* HOOK */ ?>
    
    </div>

</div>

<?php } ?>


 
</div> 
<!-- end STEP 4 SECTION -->        
        

 

 

<?php } ?>


<?php 

// JQUERY FOR PAGE DISPLAY
if(isset($_POST['action']) || isset($_GET['eid']) ){ ?> 
         
			<script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery('#step1box').hide();
            <?php if(isset($_GET['eid']) && !isset($_POST['action']) ){ ?>jQuery('#step2box').show();<?php }elseif(isset($_POST['step3'])){ ?>jQuery('#step4box').show();<?php }else{ ?>jQuery('#step3box').show();<?php } ?>
            
			<?php if(isset($_GET['eid']) && !isset($_POST['step1']) && !isset($_POST['step3'])){ ?>jQuery('#step3box').hide();jQuery('#steptable').hide();
			<?php }elseif(isset($_POST['step1'])){ ?>jQuery('#step2box').hide();jQuery('#step3box').show();
			<?php }elseif(isset($_POST['step3'])){ ?>jQuery('#step2box').hide();jQuery('#step4box').show();
			<?php }else{ ?>jQuery('#step3box').show();<?php } ?>
			
			});
            </script>
            <?php } ?>
            
 




 
 

<script language="javascript" type="text/javascript">

jQuery(document).ready(function() {
	// tooltip
    jQuery("a.tooltip").hover(function(e){
        this.tmptitle = this.title;
        this.title = "";
        jQuery("body").append("<div id='tooltip'>" + this.tmptitle + "<em></em></div>");
        jQuery("#tooltip").css("top", (e.pageY - 15 - jQuery('#tooltip').innerHeight()) + "px").css("left", (e.pageX - jQuery('#tooltip').innerWidth() / 2) + "px").fadeIn("fast");
    },function(e) {
        this.title = this.tmptitle;
        jQuery("#tooltip").remove();  
    });
});

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-+\s]+")|([\w-+]+(?:\.[\w-+]+)*)|("[\w-+\s]+")([\w-+]+(?:\.[\w-+]+)*))(@((?:[\w-+]+\.)*\w[\w-+]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][\d]\.|1[\d]{2}\.|[\d]{1,2}\.))((25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\.){2}(25[0-5]|2[0-4][\d]|1[\d]{2}|[\d]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
};

function CheckFormData(){


//var descrip = document.getElementById("form_description");
//var website = document.getElementById("form_url");

  <?php if($dfs['title'] != "1"){ ?>
  var title 	= document.getElementById("form_title"); 
	if(title.value == ''){
					alert('<?php echo $PPT->_e(array('validate','7')); ?>');
					title.style.border = 'thin solid red';
					title.focus();
					return false;
	}
	<?php } ?>
	<?php if($dfs['excerpt'] != "1"){ ?>
	var short 	= document.getElementById("form_short");
	if(short.value == ''){
					alert('<?php echo $PPT->_e(array('validate','8')); ?>');
					short.style.border = 'thin solid red';
					short.focus();
					return false;
	}
	<?php } ?>
	<?php  if($dfs['tag'] != "1"){ ?>
	var tag 	= document.getElementById("form_tags");
	if(tag.value == ''){
					alert('<?php echo $PPT->_e(array('validate','9')); ?>');
					tag.style.border = 'thin solid red';
					tag.focus();
					return false;
	} 
	<?php }  ?>
	<?php if($dfs['email'] != "1"){ ?>
	var email1 	= document.getElementById("form_email");
	if(email1.value == ''){
					alert('<?php echo $PPT->_e(array('validate','3')); ?>');
					email1.style.border = 'thin solid red';
					email1.focus();
					return false;
	} else {
	
		if( !isValidEmailAddress( email1.value ) ) {
	
			alert('<?php echo $PPT->_e(array('validate','3')); ?>');
			email1.style.border = 'thin solid red';
			email1.focus();
			return false;
		}
	}
	<?php  } ?>	
			
return true;
}

 
</script>  


<?php 
	
	// CHECK IF WE CAN SHOW THE UPLOAD FORM
	$canShow=1;
	if(get_option("display_fileupload") =="yes" && isset($GLOBALS['UploadForm']) ){ 	
 
	// SETUP UPLOAD OPTIONS 
	if($GLOBALS['PACKAGES_ENABLE'] ==1 && isset($_POST['packageID']) && isset($GLOBALS['PACKAGE_OPTIONS'][$_POST['packageID']]['uploadlimit'])){
	$uploadlimit = $GLOBALS['PACKAGE_OPTIONS'][$_POST['packageID']]['uploadlimit'];
	}else{
	$uploadlimit = get_option('display_fileupload_max');
	}
	
	// DEFAULTS
	if($uploadlimit == ""){ $uploadlimit = 10; }	
	
	 
	 ?>  
		 
	<script type='text/javascript' src='<?php echo PPT_THEME_URI ?>/PPT/js/jquery.upload.js'></script>
	<script type="text/javascript">
	
		jQuery(document).ready(function(){jQuery(".lightbox").colorbox();	}); 
	
        jQuery(function(){
            var btnUpload=jQuery('#pptupload');
            var status=jQuery('#pptstatus');
            new AjaxUpload(btnUpload, {
               action: '<?php echo $GLOBALS['bloginfo_url']; ?>/index.php<?php if(isset($_GET['eid']) && is_numeric($_GET['eid']) ){ echo "?eid=".$_GET['eid']; } ?>',
                name: 'pptfileupload',
                onSubmit: function(file, ext){
				
                     if (! (ext && /^(jpg|png|jpeg|gif|pdf|flv)$/.test(ext))){ 
                        // extension is not allowed 
                        jQuery('<li></li>').appendTo('#pptfiles').html('<div class="red_box"><div class="red_box_content"><?php echo $PPT->_e(array('validate','10')); ?><div class="clearfix"></div></div></div>');
                        return false;
                    }
                    status.html('<img src="<?php echo get_template_directory_uri(); ?>/PPT/img/loading.gif" align="middle"> <?php echo $PPT->_e(array('validate','11')); ?>');
                },
                onComplete: function(file, response){
                    //On completion clear the status
                    status.text('');
                    //Add uploaded file to list
					
					// UPDATE EPA
					var upa = document.getElementById('upa').value;
					if(upa >= <?php echo $uploadlimit; ?>){
					jQuery('#pptupload').hide();
					}else{
					document.getElementById('upa').value = parseInt(upa)+1;
					}
					
					if(response==="invalid"){
						jQuery('<li></li>').appendTo('#pptfiles').html("<div class='red_box'><div class='red_box_content'><?php echo $PPT->_e(array('validate','12'))." ".$PPT->_e(array('validate','10')); ?><div class='clearfix'></div></div></div>").addClass('error');
                    }else if(response==="error"){
                        jQuery('<li></li>').appendTo('#pptfiles').html("<?php echo $PPT->_e(array('validate','13')); ?> ("+file+")").addClass('error');
                    }else if(response==="writable"){
                        jQuery('<li></li>').appendTo('#pptfiles').html("<?php echo $PPT->_e(array('validate','14')); ?>").addClass('error');
					} else{
					
						var fileType = response.split('.').pop();				
						var image; 
						if(fileType =="pdf"){
						image = '<img src="<?php echo get_template_directory_uri(); ?>/PPT/img/pdf.png">';
						
						}else if(fileType =="flv"){
						
						image = '<img src="<?php echo get_template_directory_uri(); ?>/PPT/img/video.png">';
						
						}else{
						image = '<a href="<?php echo get_option('imagestorage_link'); ?>'+response+'" target="_blank" class="lightbox"><img src="<?php echo get_option('imagestorage_link'); ?>'+response+'"></a>';
						}
					
                        jQuery('<li></li>').appendTo('#pptfiles').html('<div class="yellow_box"><div class="yellow_box_content"><span>'+file+'</span><input type="hidden" name="files[]" value="'+response+'">'+image+'<div class="clearfix"></div></div></div><div class="clearfix"></div>').addClass('pptsuccess');
                    }
					
					if(upa >= <?php echo $uploadlimit; ?>){
					jQuery('<li></li>').appendTo('#pptfiles').html('<?php echo $PPT->_e(array('add','add2')); ?>').addClass('error');
					}
					
                }
            });
            
        });
    </script> 

<?php }  get_footer();  
	
}
/* =============================================================================
   -- END FILE
   ========================================================================== */	
?>