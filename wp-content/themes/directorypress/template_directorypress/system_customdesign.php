<?php

/* =============================================================================
   ACTIONS FOR DIRECTORYPRESS THEME
   ========================================================================== */
   
function DirectoryPressActions(){

global $post, $userdata, $wpdb;

	// CLAIM LISTING ACTION
	if( isset($_POST['action']) && $_POST['action'] == "claimlisting"){ 
	
		$em = get_post_meta($post->ID, 'email', true); $em1 = explode("@",$em);

		if( ( $em == $userdata->user_email ) || ( strpos($userdata->user_email, $em1[1]) !== false )  ){	
				 
			$GLOBALS['claimlisting_result'] = true;
			
			$my_post = array(); 
			$my_post['ID'] 				= $post->ID;
			$my_post['post_author'] 	= $userdata->ID;
			wp_update_post( $my_post );			 
		
		}else{
		 //die("".$em );
		$GLOBALS['claimlisting_result'] = false;
		
		}
		
		if($GLOBALS['claimlisting_result']){
			
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "success";
			$GLOBALS['error_msg'] 	= "This listing has successfully been updated and is now listed under your account.";
			
		}else{
			
			$GLOBALS['error'] 		= 1;
			$GLOBALS['error_type'] 	= "error";
			$GLOBALS['error_msg'] 	= "This listing could not be claimed because the email addresss on file does not match the one on your account.";
			
		}		
	
	} // end clamin listing action

} // end function

add_action('premiumpress_action','DirectoryPressActions'); // add in new hook




class Theme_Design { 

 
function SYS_PAGES(){

	global $PPTDesign;
	
	return $PPTDesign->SYS_PAGES();
}
function Directory_CatList($id=0,$showExtraPrice){
 
	global $PPT;
	
	return premiumpress_categorylist();
}

}
/*************************************************************************************************/


 

/* ============================= PREMIUM PRESS REGISTER WIDGETS ========================= */
 
if ( function_exists('register_sidebar') ){
register_sidebar(array('name'=>'Home Page Widget Box',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' => 'This is an empty widget box, its used only with the theme options found under "Display Settings" -> "Home Page" ',
	'id'            => 'sidebar-0',
));
register_sidebar(array('name'=>'Right Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' => 'This is the right column sidebar for your website. Widgets here will display on all right side columns apart from those provided by the other widget blocks below. ',
	'id'            => 'sidebar-1',
));
register_sidebar(array('name'=>'Left Sidebar (3 Column Layouts Only)',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the left column sidebar for your website. Widgets here will display on ALL left sidebars throughout your ENTIRE website.',
	'id'            => 'sidebar-2',
));
register_sidebar(array('name'=>'Listing Page',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your LISTING PAGE only. Widgets here will ONLY display on your listing page. ',
	'id'            => 'sidebar-3',
));
register_sidebar(array('name'=>'Pages Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your website PAGES. All widgets here will display on the right side of your PAGES.',
	'id'            => 'sidebar-4',
));
register_sidebar(array('name'=>'Article/FAQ Page Sidebar',
	'before_widget' => '<div class="itembox" id="%1$s">',
	'after_widget' 	=> '</div></div>',
	'before_title' 	=> '<h2 id="widget-box-id">',
	'after_title' 	=> '</h2><div class="itemboxinner greybg widget">',
	'description' 	=> 'This is the right column sidebar for your website ARTICLES/FAQ PAGES.',
	'id'            => 'sidebar-5',
));
register_sidebar(array('name'=>'Footer Left Block (1/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' 	=> 'This is left footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-6',
));
register_sidebar(array('name'=>'Footer Middle Block (2/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' 	=> 'This is middle footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-7',
));
register_sidebar(array('name'=>'Footer Right Block (3/3)',
	'before_widget' => '',
	'after_widget' 	=> '',
	'before_title' 	=> '<h3>',
	'after_title' 	=> '</h3>',
	'description' => 'This is right footer block, the footer sections is split into 3 blocks each of roughtly 300px width. ',
	'id'            => 'sidebar-8',
));
  
} 
 
 
 
 
 
 /* ============================= OLD DIRECTORYPRESS FUNCTIONS ========================= */

 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 


 

function CheckTrackingID($tracking_id=""){ 

	global $wpdb;

	$SQL = "SELECT count($wpdb->postmeta.meta_key) AS total
			FROM $wpdb->postmeta
			WHERE $wpdb->postmeta.meta_key='tracking_id' AND $wpdb->postmeta.meta_value = '".$tracking_id."'
			LIMIT 1";
 
 	$result = mysql_query($SQL);
	$array = mysql_fetch_assoc($result);

	return $array['total'];

}



function checkDomainAvailability($domain){

$arWhoisServer = array(
    'com'         => array('whois.crsnic.net', 'No match for'),
	'gov'         => array('whois.crsnic.net', 'No match for'),
    'net'         => array('whois.crsnic.net', 'No match for'),   
    'org'         => array('whois.pir.org', 'NOT FOUND'),
    'biz'         => array('whois.biz', 'Not found'),
    'mobi'        => array('whois.dotmobiregistry.net', 'NOT FOUND'),
    'tv'         => array('whois.nic.tv', 'No match for'),
    'in'         => array('whois.inregistry.net', 'NOT FOUND'),
    'info'         => array('whois.afilias.net', 'NOT FOUND'),   
    'co.uk'     => array('whois.nic.uk', 'No match'),       
    'co.ug'     => array('wawa.eahd.or.ug', 'No entries found'),   
    'or.ug'     => array('wawa.eahd.or.ug', 'No entries found'),
    'nl'         => array('whois.domain-registry.nl', 'not a registered domain'),
    'ro'         => array('whois.rotld.ro', 'No entries found for the selected'),
    'com.au'    => array('whois.ausregistry.net.au', 'No data Found'),
    'ca'         => array('whois.cira.ca', 'AVAIL'),
    'org.uk'    => array('whois.nic.uk', 'No match'),
    'name'         => array('whois.nic.name', 'No match'),
    'us'         => array('whois.nic.us', 'Not Found'),
    'ac.ug'     => array('wawa.eahd.or.ug', 'No entries found'),
    'ne.ug'     => array('wawa.eahd.or.ug', 'No entries found'),
    'sc.ug'     => array('wawa.eahd.or.ug', 'No entries found'),
    'ws'        => array('whois.website.ws', 'No Match'),
    'be'         => array('whois.ripe.net', 'No entries'),
    'com.cn'     => array('whois.cnnic.cn', 'no matching record'),
    'net.cn'     => array('whois.cnnic.cn', 'no matching record'),
    'org.cn'     => array('whois.cnnic.cn', 'no matching record'),
    'no'        => array('whois.norid.no', 'no matches'),
    'se'         => array('whois.nic-se.se', 'No data found'),
    'nu'         => array('whois.nic.nu', 'NO MATCH for'),
    'com.tw'     => array('whois.twnic.net', 'No such Domain Name'),
    'net.tw'     => array('whois.twnic.net', 'No such Domain Name'),
    'org.tw'     => array('whois.twnic.net', 'No such Domain Name'),
    'cc'         => array('whois.nic.cc', 'No match'),
    'nl'         => array('whois.domain-registry.nl', 'is free'),
    'pl'         => array('whois.dns.pl', 'No information about'),
    'pt'         => array('whois.dns.pt', 'No match'),
    '.co.nz'         => array('whois.dns.pt', 'No match'),
	'.edu'         => array('whois.edu', 'No match'),
	'.co.il'         => array('whois.edu', 'No match'),
	'.org.il'         => array('whois.edu', 'No match'),	
	'uk'         => array('whois.edu', 'No match'),
'ie'         => array('whois.edu', 'No match'),
'ph'         => array('whois.edu', 'No match'),
'com.ph'         => array('whois.edu', 'No match'),

'fr'         => array('whois.edu', 'No match'),
'gr'         => array('whois.edu', 'No match'),
'com.au'         => array('whois.edu', 'No match'),
'co.za'         => array('whois.edu', 'No match'),
'.eu'         => array('whois.edu', 'No match'),
'.edu'         => array('whois.edu', 'No match'),


'ac'         => array('whois.nic.ac', 'No match'),
'ae'         => array('whois.nic.ae', 'No match'),
'af'         => array('whois.nic.af', 'No match'),  
'ag'         => array('whois.nic.ag', 'No match'),
'al'         => array('whois.ripe.net', 'No match'),  
'am'         => array('whois.amnic.net', 'No match'),
'as'         => array('whois.nic.as', 'No match'),
'at'         => array('whois.nic.at', 'No match'),
'au'         => array('whois.aunic.net', 'No match'),
'az'         => array('whois.ripe.net', 'No match'),
'ba'         => array('whois.ripe.net', 'No match'),
'be'         => array('whois.dns.be', 'No match'),
'bg'         => array('whois.register.bg', 'No match'),
'bi'         => array('whois.nic.bi', 'No match'),
'biz'         => array('whois.neulevel.biz', 'No match'),
'bj'         => array('www.nic.bj', 'No match'),
'br'         => array('whois.nic.br', 'No match'),
'bt'         => array('whois.netnames.net', 'No match'),
'by'         => array('whois.ripe.net', 'No match'),
'bz'         => array('whois.belizenic.bz', 'No match'),
'ca'         => array('whois.cira.ca', 'No match'),
'cc'         => array('whois.nic.cc', 'No match'),
'cd'         => array('whois.nic.cd', 'No match'),
'ch'         => array('whois.nic.ch', 'No match'),
'ck'         => array('whois.nic.ck', 'No match'),
'cl'         => array('nic.cl', 'No match'),
'cn'         => array('whois.cnnic.net.cn', 'No match'),
'co.nl'         => array('whois.co.nl', 'No match'),
'com'         => array('whois.verisign-grs.com', 'No match'),
'coop'         => array('whois.nic.coop', 'No match'),
'cx'         => array('whois.nic.cx', 'No match'),
'cy'         => array('whois.ripe.net', 'No match'),
'cz'         => array('whois.nic.cz', 'No match'),
'de'         => array('whois.denic.de', 'No match'),
'dk'         => array('whois.dk-hostmaster.dk', 'No match'),
'dm'         => array('whois.nic.cx', 'No match'),
'dz'         => array('whois.ripe.net', 'No match'),
'edu'         => array('whois.educause.net', 'No match'),
'ee'         => array('whois.eenet.ee', 'No match'),
'eg'         => array('whois.ripe.net', 'No match'),
'es'         => array('whois.ripe.net', 'No match'),
'eu'         => array('whois.eu', 'No match'),
'fi'         => array('whois.ficora.fi', 'No match'),
'fo'         => array('whois.ripe.net', 'No match'),
'fr'         => array('whois.nic.fr', 'No match'),
'gb'         => array('whois.ripe.net', 'No match'),
'ge'         => array('whois.ripe.net', 'No match'),
'gl'         => array('whois.ripe.net', 'No match'),
'gm'         => array('whois.ripe.net', 'No match'),
'gov'         => array('whois.nic.gov', 'No match'),
'gr'         => array('whois.ripe.net', 'No match'),
'gs'         => array('whois.adamsnames.tc', 'No match'),
'hk'         => array('whois.hknic.net.hk', 'No match'),
'hm'         => array('whois.registry.hm', 'No match'),
'hn'         => array('whois2.afilias-grs.net', 'No match'),
'hr'         => array('whois.ripe.net', 'No match'),
'hu'         => array('whois.ripe.net', 'No match'),
'ie'         => array('whois.domainregistry.ie', 'No match'),
'il'         => array('whois.isoc.org.il', 'No match'),
'in'         => array('whois.inregistry.net', 'No match'),
'info'         => array('whois.afilias.info', 'No match'),
'int'         => array('whois.isi.edu', 'No match'),
'iq'         => array('vrx.net', 'No match'),
'ir'         => array('whois.nic.ir', 'No match'),
'is'         => array('whois.isnic.is', 'No match'),
'it'         => array('whois.nic.it', 'No match'),
'je'         => array('whois.je', 'No match'),
'jp'         => array('whois.jprs.jp', 'No match'),
'kg'         => array('whois.domain.kg', 'No match'),
'kr'         => array('whois.nic.or.kr', 'No match'),
'la'         => array('whois2.afilias-grs.net', 'No match'),
'li'         => array('whois.nic.li', 'No match'),
'lt'         => array('whois.domreg.lt', 'No match'),
'lu'         => array('whois.restena.lu', 'No match'),
'lv'         => array('whois.nic.lv', 'No match'),
'ly'         => array('whois.lydomains.com', 'No match'),
'ma'         => array('whois.iam.net.ma', 'No match'),
'mc'         => array('whois.ripe.net', 'No match'),
'md'         => array('whois.nic.md', 'No match'),
'me'         => array('whois.nic.me', 'No match'),
'mil'         => array('whois.nic.mil', 'No match'),
'mk'         => array('whois.ripe.net', 'No match'),
'mobi'         => array('whois.dotmobiregistry.net', 'No match'),
'ms'         => array('whois.nic.ms', 'No match'),
'mt'         => array('whois.ripe.net', 'No match'),
'mu'         => array('whois.nic.mu', 'No match'),
'mx'         => array('whois.nic.mx', 'No match'),
'my'         => array('whois.mynic.net.my', 'No match'),
'name'         => array('whois.nic.name', 'No match'),
'net'         => array('whois.verisign-grs.com', 'No match'),
'nf'         => array('whois.nic.cx', 'No match'),
'nl'         => array('whois.domain-registry.nl', 'No match'),
'no'         => array('whois.norid.no', 'No match'),
'nu'         => array('whois.nic.nu', 'No match'),
'nz'         => array('whois.srs.net.nz', 'No match'),
'org'         => array('whois.pir.org', 'No match'),
'pl'         => array('whois.dns.pl', 'No match'),
'pr'         => array('whois.nic.pr', 'No match'),
'pro'         => array('whois.registrypro.pro', 'No match'),
'pt'         => array('whois.dns.pt', 'No match'),
'ro'         => array('whois.rotld.ro', 'No match'),
'ru'         => array('whois.ripn.ru', 'No match'),
'sa'         => array('saudinic.net.sa', 'No match'),
'sb'         => array('whois.nic.net.sb', 'No match'),
'sc'         => array('whois2.afilias-grs.net', 'No match'),
'se'         => array('whois.nic-se.se', 'No match'),
'sg'         => array('whois.nic.net.sg', 'No match'),
'sh'         => array('whois.nic.sh', 'No match'),
'si'         => array('whois.arnes.si', 'No match'),
'sk'         => array('whois.sk-nic.sk', 'No match'),
'sm'         => array('whois.ripe.net', 'No match'),
'st'         => array('whois.nic.st', 'No match'),
'su'         => array('whois.ripn.net', 'No match'),
'tc'         => array('whois.adamsnames.tc', 'No match'),
'tel'         => array('whois.nic.tel', 'No match'),
'tf'         => array('whois.nic.tf', 'No match'),
'th'         => array('whois.thnic.net', 'No match'),
'tj'         => array('whois.nic.tj', 'No match'),
'tk'         => array('whois.nic.tk', 'No match'),
'tl'         => array('whois.domains.tl', 'No match'),
'tm'         => array('whois.nic.tm', 'No match'),
'tn'         => array('whois.ripe.net', 'No match'),
'to'         => array('whois.tonic.to', 'No match'),
'tp'         => array('whois.domains.tl', 'No match'),
'tr'         => array('whois.nic.tr', 'No match'),
'travel'     => array('whois.nic.travel', 'No match'),
'tw'         => array('whois.apnic.net', 'No match'),
'tv'         => array('whois.nic.tv', 'No match'),
'ua'         => array('whois.ripe.net', 'No match'),
'uk'         => array('whois.nic.uk', 'No match'),
'gov.uk'     => array('whois.ja.net', 'No match'),
'us'         => array('whois.nic.us', 'No match'),
'uy'         => array('nic.uy', 'No match'),
'uz'         => array('whois.cctld.uz', 'No match'),
'va'         => array('whois.ripe.net', 'No match'),
'vc'         => array('whois2.afilias-grs.net', 'No match'),
've'         => array('whois.nic.ve', 'No match'),
'vg'         => array('whois.adamsnames.tc', 'No match'),
'ws'         => array('www.nic.ws', 'No match'),
'yu'         => array('whois.ripe.net', 'No match'),


); 
    

    // Get the domain without http:// and www.
    $domain = trim($domain);
    preg_match('@^(http://www\.|http://|www\.)?([^/]+)@i', $domain, $matches);
    $domain = $matches[2];
    // Get the tld
    $tld = explode('.', $domain);
	 
	if(isset($tld[2])){
	$tld = strtolower(trim($tld[2]));
	}else{
	$tld = strtolower(trim($tld[1]));
	}
    
	//die($tld." -- ".$domain);
    // If the domain name is valid and we have an entry corresponding to our tld
    if(strlen($domain) <= strlen($tld) + 1){

          return '(Invalid Domain) <b style="color:red;">'.$domain.'</b>';

	}elseif(!array_key_exists($tld, $arWhoisServer)){

		return '(Invalid TLD) <b style="color:red;">'.$domain.'</b>';

    }else{

		return 1;

    }
}
 


 


 


if(!function_exists('fetch_URL')){
function fetch_URL($URL)
{

$URL = str_replace("http://http://","http://",$URL);
	$handle = @fopen ($URL, "r");
	if ($handle === false) return false;

	$buffer = "";
	while (!feof ($handle)) {
	    $buffer .= fgets($handle, 4096);
	}
	fclose ($handle);

	return $buffer;
}}


 
 
 

 
?>