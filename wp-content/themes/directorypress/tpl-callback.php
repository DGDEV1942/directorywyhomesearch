<?php
/*
Template Name: [Callback Template]
*/

/***************** DO NOT EDIT THIS FILE *************************
******************************************************************

INFORMATION:
------------

This is a core theme file, you should not need to edit 
this file directly. Code changes maybe lost during updates.

LAST UPDATED: June 26th 2011
EDITED BY: MARK FAIL
------------------------------------------------------------------

******************************************************************/
$GLOBALS['tpl-callback'] = 1;
 
$wpdb->hide_errors(); nocache_headers();
global $PPT, $userdata; get_currentuserinfo(); // grabs the user info and puts into vars
 
/* ==================== INCLUDES ===================== */

include(TEMPLATEPATH ."/PPT/class/class_payment.php");

$PPTPayment 		= new PremiumPressTheme_Payment;

/* =================== TEST PAYPAL DATA ================== */

/*
1. check that the order ID is value
2. if valid check the status else if invalid show error

ORDER ID STRUCTURE IS: [post ID]-[user ID]-[type]-[new package ID]
*/
/*if($userdata->wp_user_level == "10" && isset($_GET['order_id'])){ 
$_POST['mc_gross'] 			= $_GET['a'];
$_POST['custom'] 			= $_GET['order_id']; 
$_POST['payment_status'] 	= "Completed";
$_POST['payment_status'] 	= "Completed";
$_POST['mc_currency']		= "USD";
}*/
 

/* =================== CALLBACK EVENTS ================== */
$order_status = "error"; $returnValues = array("thankyou","error","pending"); 


if($PPTPayment->CheckOrderID()){

	$orderID 		=	$PPTPayment->CheckOrderID(1);
		
	if(in_array($orderID,$returnValues)){
	$order_status = $orderID;
	}else{
	 
	$order_status 	= 	$PPTPayment->PaymentStatus($orderID);
	}	
	
	$userID 		=	$PPTPayment->CheckUserID($orderID,$userdata);
	$GLOBALS['PPTorderID'] = $orderID;
	
	//die("orderID: ".$orderID."<br>Status: ".$order_status."<br>UserID: ".$userID);
 
} 

// RESET SESSIONS
@session_destroy();

if(isset($_GET['force']) || isset($_GET['auth']) ){
$order_status = "thankyou";
}

/* =================== START DISPLAY ================== */

$hookContent = premiumpress_pagecontent("callback"); /* HOOK V7 */

if(strlen($hookContent) > 20 ){ // HOOK DISPLAYS CONTENT

	get_header();
	
	echo $hookContent;
	
	get_footer();

}elseif(file_exists(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme')."/_tpl_callback.php")){
		
	include(str_replace("functions/","",THEME_PATH)."/themes/".get_option('theme').'/_tpl_callback.php');
		
}else{ 
 
/* =============================================================================
   LOAD IN PAGE DEFAULT DISPLAY // UPDATED: 25TH MARCH 2012
   ========================================================================== */ 
 
get_header(); if($order_status == "thankyou"){ ?> 

<div class="itembox">
	<h1><?php echo $PPT->_e(array('callback','1')); ?></h1>
	<div class="itemboxinner greybg">    
    <h3><?php echo $PPT->_e(array('callback','2')); ?></h3>
    <p><?php echo $PPT->_e(array('callback','3')); ?></p>
    <?php premiumpress_callback_thankyou(); ?>
	</div>
</div>

<?php }elseif($order_status =="pending"){ ?> 

<div class="itembox">
	<h1><?php echo $PPT->_e(array('callback','4')); ?></h1>
	<div class="itemboxinner greybg">    
	<h3><?php echo $PPT->_e(array('callback','5')); ?></h3>
	<p><?php echo $PPT->_e(array('callback','6')); ?></p>
    <?php premiumpress_callback_pending(); ?>
	</div>
</div>
 
<?php }elseif($order_status =="error"){ ?> 

<div class="itembox">
	<h1><?php echo $PPT->_e(array('callback','7')); ?></h1>
	<div class="itemboxinner greybg">    
	<h3><?php echo $PPT->_e(array('callback','8')); ?></h3>
	<p><?php echo $PPT->_e(array('callback','9')); ?></p>
    <?php premiumpress_callback_error(); ?>
	</div>
</div>

<?php }else{ ?>

<div class="itembox">
	<h1><?php echo $PPT->_e(array('callback','10')); ?></h1>
	<div class="itemboxinner greybg">    
	<h3><?php echo $PPT->_e(array('callback','11')); ?></h3>
	<p><?php echo $PPT->_e(array('callback','12')); ?></p>
    <?php premiumpress_callback_error(); ?>
	</div>
</div> 
 
<?php }  get_footer();  }?>