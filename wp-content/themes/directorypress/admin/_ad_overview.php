<?php if (!defined('PREMIUMPRESS_SYSTEM')) {	header('HTTP/1.0 403 Forbidden'); exit; } global $wpdb; global $PPT;  PremiumPress_Header();

 
if( strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" &&  get_option("ppt_load_countries") == ""){
update_option("ppt_load_countries","1");
$a = wp_insert_term("United Kingdom", "location" ); 
 

$state_uk = array('London',
'Bedfordshire',
'Buckinghamshire',
'Cambridgeshire',
'Cheshire',
'Cornwall and Isles of Scilly',
'Cumbria',
'Derbyshire',
'Devon',
'Dorset',
'Durham',
'East Sussex',
'Essex',
'Gloucestershire',
'Greater London',
'Greater Manchester',
'Hampshire',
'Hertfordshire',
'Kent',
'Lancashire',
'Leicestershire',
'Lincolnshire',
'Merseyside',
'Norfolk',
'North Yorkshire',
'Northamptonshire',
'Northumberland',
'Nottinghamshire',
'Oxfordshire',
'Shropshire',
'Somerset',
'South Yorkshire',
'Staffordshire',
'Suffolk',
'Surrey',
'Tyne and Wear',
'Warwickshire',
'West Midlands',
'West Sussex',
'West Yorkshire',
'Wiltshire',
'Worcestershire',
'Flintshire',
'Glamorgan',
'Merionethshire',
'Monmouthshire',
'Montgomeryshire',
'Pembrokeshire',
'Radnorshire',
'Anglesey',
'Breconshire',
'Caernarvonshire',
'Cardiganshire',
'Carmarthenshire',
'Denbighshire',
'Kirkcudbrightshire',
'Lanarkshire',
'Midlothian',
'Moray',
'Nairnshire',
'Orkney',
'Peebleshire',
'Perthshire',
'Renfrewshire',
'Ross & Cromarty',
'Roxburghshire',
'Selkirkshire',
'Shetland',
'Stirlingshire',
'Sutherland',
'West Lothian',
'Wigtownshire',
'Aberdeenshire',
'Angus',
'Argyll',
'Ayrshire',
'Banffshire',
'Berwickshire',
'Bute',
'Caithness',
'Clackmannanshire',
'Dumfriesshire',
'Dumbartonshire',
'East Lothian',
'Fife',
'Inverness',
'Kincardineshire',
'Kinross-shire');

foreach($state_uk as $value){  wp_insert_term($value, "location", array(  'parent' => $a['term_id'] )); }
 
$b = wp_insert_term("United States of America", "location" ); 


$state_usa = array('Alabama', 'Alaska','Arizona','Arkansas','California','Colorado','Connecticut','Delaware','District of Columbia','Florida','Georgia','Hawaii','Idaho','Illinois','Indiana','Iowa','Kansas','Kentucky','Louisiana','Maine','Maryland','Massachusetts','Michigan','Minnesota','Mississippi','Missouri','Montana','Nebraska','Nevada','New Hampshire','New Jersey','New Mexico','New York','North Carolina','North Dakota','Ohio','Oklahoma','Oregon','Pennsylvania','Rhode Island','South Carolina','South Dakota','Tennessee','Texas','Utah','Vermont','Virginia','Washington','West Virginia','Wisconsin','Wyoming');

foreach($state_usa as $value){   wp_insert_term($value, "location", array(  'parent' => $b['term_id'] )); }


wp_insert_term("Afghanistan", "location" ); wp_insert_term("Albania", "location" ); wp_insert_term("Algeria", "location" ); wp_insert_term("American Samoa", "location" ); wp_insert_term("Andorra", "location" ); wp_insert_term("Angola", "location" ); wp_insert_term("Anguilla", "location" ); wp_insert_term("Antarctica", "location" ); wp_insert_term("Antigua and Barbuda", "location" ); wp_insert_term("Argentina", "location" ); wp_insert_term("Armenia", "location" ); wp_insert_term("Aruba", "location" ); 


$state_australia = array("Australian Capital Territory","New South Wales","Northern Territory","Queensland","South Australia","Tasmania","Victoria","Western Australia");

$l = wp_insert_term("Australia", "location" );
foreach($state_australia as $value){   wp_insert_term($value, "location", array(  'parent' => $l['term_id'] )); }


 wp_insert_term("Austria", "location" ); wp_insert_term("Azerbaijan", "location" ); wp_insert_term("Bahamas", "location" );  wp_insert_term("Bahrain", "location" ); wp_insert_term("Bangladesh", "location" ); wp_insert_term("Barbados", "location" ); wp_insert_term("Belarus", "location" ); wp_insert_term("Belgium", "location" ); wp_insert_term("Belize", "location" );
wp_insert_term("Benin", "location" ); wp_insert_term("Bermuda", "location" ); wp_insert_term("Bhutan", "location" ); wp_insert_term("Bolivia", "location" ); wp_insert_term("Bosnia and Herzegovina", "location" ); wp_insert_term("Botswana", "location" ); wp_insert_term("Bouvet Island", "location" ); wp_insert_term("Brazil", "location" ); wp_insert_term("British Indian Ocean Territory", "location" ); wp_insert_term("British Virgin Islands", "location" ); wp_insert_term("Brunei", "location" ); wp_insert_term("Bulgaria", "location" ); wp_insert_term("Burkina Faso", "location" ); wp_insert_term("Burundi", "location" ); wp_insert_term("Cambodia", "location" ); wp_insert_term("Cameroon", "location" ); wp_insert_term("Canada", "location" ); wp_insert_term("Cape Verde", "location" ); wp_insert_term("Cayman Islands", "location" ); 
wp_insert_term("Central African Republic", "location" ); wp_insert_term("Chad", "location" ); wp_insert_term("Chile", "location" ); 


$state_china = array('Anhui','Beijing','Chongqing','Fujian','Gansu','Guangdong','Guangxi','Guizhou','Hainan','Hebei','Heilongjiang','Henan','Hubei','Hunan','Jiangsu','Jiangxi','Jilin','Liaoning','Nei Mongol','Ningxia','Qinghai','Shaanxi','Shandong','Shanghai','Shanxi','Sichuan','Tianjin','Xinjiang','Xizang (Tibet)','Yunnan','Zhejiang');

$e = wp_insert_term("China", "location" ); 

foreach($state_china as $value){   wp_insert_term($value, "location", array(  'parent' => $e['term_id'] )); }


wp_insert_term("Christmas Island", "location" ); wp_insert_term("Cocos (Keeling) Islands", "location" ); wp_insert_term("Colombia", "location" ); wp_insert_term("Comoros", "location" ); wp_insert_term("Congo", "location" ); wp_insert_term("Cook Islands", "location" ); wp_insert_term("Costa Rica", "location" ); wp_insert_term("Croatia", "location" ); wp_insert_term("Cuba", "location" ); wp_insert_term("Cyprus", "location" ); wp_insert_term("Czech Republic", "location" ); wp_insert_term("Democratic Republic of the Congo", "location" ); wp_insert_term("Denmark", "location" ); wp_insert_term("Djibouti", "location" ); wp_insert_term("Dominica", "location" ); wp_insert_term("Dominican Republic", "location" ); wp_insert_term("East Timor", "location" ); wp_insert_term("Ecuador", "location" ); wp_insert_term("Egypt", "location" ); wp_insert_term("El Salvador", "location" ); wp_insert_term("Equatorial Guinea", "location" ); wp_insert_term("Eritrea", "location" ); wp_insert_term("Estonia", "location" ); wp_insert_term("Ethiopia", "location" ); wp_insert_term("Falkland Islands (Malvinas)", "location" ); wp_insert_term("Faroe Islands", "location" ); wp_insert_term("Fiji", "location" ); wp_insert_term("Finland", "location" ); 

$d = wp_insert_term("France", "location" ); 
$state_france = array('Alsace','Aquitaine','Auvergne','Basse-Normandie','Bourgogne','Bretagne','Centre','Champagne-Ardenne','Corse','Franche-Comte','Haute-Normandie','Ile-de-France','Languedoc-Roussillon','Limousin','Lorraine','Midi-Pyrenees','Nord-Pas-de-Calais','Pays de la Loire','Picardie','Poitou-Charentes','Provence-Alpes-Cote dAzur','Rhone-Alpes');

foreach($state_france as $value){   wp_insert_term($value, "location", array(  'parent' => $d['term_id'] )); }

wp_insert_term("French Guiana", "location" ); wp_insert_term("French Polynesia", "location" ); wp_insert_term("French Southern/Antarctic Lands", "location" ); wp_insert_term("Gabon", "location" ); wp_insert_term("Gambia", "location" ); wp_insert_term("Georgia", "location" ); 

$c = wp_insert_term("Germany", "location" );

$state_germany = array('Baden-Wuerttemberg','Bayern','Berlin','Brandenburg','Bremen','Hamburg','Hessen','Mecklenburg-Vorpommern','Niedersachsen','Nordrhein-Westfalen','Rheinland-Pfalz','Saarland','Sachsen','Sachsen-Anhalt','Schleswig-Holstein','Thueringen');
foreach($state_germany as $value){   wp_insert_term($value, "location", array(  'parent' => $c['term_id'] )); }
 

 wp_insert_term("Ghana", "location" ); wp_insert_term("Gibraltar", "location" ); wp_insert_term("Greece", "location" ); 
wp_insert_term("Greenland", "location" ); wp_insert_term("Grenada", "location" ); wp_insert_term("Guadeloupe", "location" ); wp_insert_term("Guam", "location" ); wp_insert_term("Guatemala", "location" ); wp_insert_term("Guinea", "location" ); wp_insert_term("Guinea-Bissau", "location" ); wp_insert_term("Guyana", "location" ); wp_insert_term("Haiti", "location" ); wp_insert_term("Heard and McDonald Islands", "location" ); wp_insert_term("Honduras", "location" ); wp_insert_term("Hong Kong", "location" ); wp_insert_term("Hungary", "location" ); wp_insert_term("Iceland", "location" ); 


$state_india = array("Andaman and Nicobar Islands","Andhra Pradesh","Arunachal Pradesh","Assam","Bengal","Bihar","Chandigarh","Chhattisgarh","Dadra and Nagar Haveli","Daman and Diu","Delhi","Goa","Gujarat","Haryana","Himachal Pradesh","Jharkhand","Karnataka","Kashmir","Kerala","Madhya Pradesh","Maharashtra","Manipur","Meghalaya","Mizoram","Nagaland","Orissa","Pondicherry","Punjab","Rajasthan","Sikkim","Tamil Nadu","Tripura","Uttar Pradesh","Uttarakhand");


$v = wp_insert_term("India", "location" ); 
foreach($state_india as $value){   wp_insert_term($value, "location", array(  'parent' => $v['term_id'] )); }


wp_insert_term("Indonesia", "location" ); wp_insert_term("Iraq", "location" ); wp_insert_term("Ireland", "location" ); wp_insert_term("Islamic Republic of Iran", "location" ); wp_insert_term("Israel", "location" ); wp_insert_term("Italy", "location" ); wp_insert_term("Ivory Coast", "location" ); wp_insert_term("Jamaica", "location" ); wp_insert_term("Japan", "location" ); wp_insert_term("Jordan", "location" ); wp_insert_term("Kazakhstan", "location" ); 
wp_insert_term("Kenya", "location" ); wp_insert_term("Kiribati", "location" ); wp_insert_term("Korea", "location" );   wp_insert_term("Kuwait", "location" ); wp_insert_term("Kyrgyzstan", "location" ); wp_insert_term("Lao Peoples Democratic Republic", "location" ); wp_insert_term("Latvia", "location" ); wp_insert_term("Lebanon", "location" ); wp_insert_term("Lesotho", "location" ); wp_insert_term("Liberia", "location" ); wp_insert_term("Libya", "location" ); wp_insert_term("Liechtenstein", "location" ); wp_insert_term("Lithuania", "location" ); wp_insert_term("Luxembourg", "location" ); 
wp_insert_term("Macau", "location" ); wp_insert_term("Macedonia", "location" ); wp_insert_term("Madagascar", "location" ); wp_insert_term("Malawi", "location" ); 


$state_malaysia = array("Federal Territory of Labuan","Johor","Kedah","Kelantan","Kuala Lumpur","Melaka","Negeri Sembilan","Pahang","Perak","Perlis","Pulau Pinang","Putrajaya","Sabah","Sarawak","Selangor","Terengganu");

$ma = wp_insert_term("Malaysia", "location" );
foreach($state_malaysia as $value){   wp_insert_term($value, "location", array(  'parent' => $ma['term_id'] )); }


wp_insert_term("Maldives", "location" ); wp_insert_term("Mali", "location" ); wp_insert_term("Malta", "location" );  wp_insert_term("Marshall Islands", "location" ); wp_insert_term("Martinique", "location" ); wp_insert_term("Mauritania", "location" ); wp_insert_term("Mauritius", "location" ); wp_insert_term("Mayotte", "location" ); wp_insert_term("Mexico", "location" ); wp_insert_term("Micronesia", "location" ); wp_insert_term("Moldova", "location" ); wp_insert_term("Monaco", "location" ); wp_insert_term("Mongolia", "location" ); wp_insert_term("Monserrat", "location" ); wp_insert_term("Morocco", "location" ); wp_insert_term("Mozambique", "location" ); wp_insert_term("Namibia", "location" ); wp_insert_term("Nauru", "location" ); wp_insert_term("Nepal", "location" ); wp_insert_term("Netherlands", "location" ); wp_insert_term("Netherlands Antilles", "location" ); wp_insert_term("New Caledonia", "location" ); wp_insert_term("New Zealand", "location" ); wp_insert_term("Nicaragua", "location" ); wp_insert_term("Niger", "location" ); wp_insert_term("Nigeria", "location" ); wp_insert_term("Niue", "location" ); wp_insert_term("Norfolk Island", "location" ); wp_insert_term("Northern Mariana Islands", "location" ); wp_insert_term("Norway", "location" ); wp_insert_term("Oman", "location" ); wp_insert_term("Pakistan", "location" ); wp_insert_term("Palau", "location" ); wp_insert_term("Panama", "location" ); wp_insert_term("Papua New Guinea", "location" ); wp_insert_term("Paraguay", "location" ); wp_insert_term("Peru", "location" ); wp_insert_term("Philippines", "location" ); wp_insert_term("Pitcairn", "location" ); wp_insert_term("Poland", "location" );
 wp_insert_term("Portugal", "location" ); wp_insert_term("Puerto Rico", "location" ); wp_insert_term("Qatar", "location" ); wp_insert_term("Reunion", "location" ); wp_insert_term("Romania", "location" ); wp_insert_term("Russia", "location" ); wp_insert_term("Rwanda", "location" ); wp_insert_term("Saint Lucia", "location" ); wp_insert_term("Samoa", "location" ); wp_insert_term("San Marino", "location" ); wp_insert_term("Sao Tome and Principe", "location" ); wp_insert_term("Saudi Arabia", "location" ); wp_insert_term("Scotland", "location" ); wp_insert_term("Senegal", "location" ); wp_insert_term("Seychelles", "location" ); wp_insert_term("Sierra Leone", "location" ); wp_insert_term("Singapore", "location" ); wp_insert_term("Slovakia", "location" ); wp_insert_term("Slovenia", "location" ); wp_insert_term("Solomon Islands", "location" ); wp_insert_term("Somalia", "location" ); wp_insert_term("South Africa", "location" ); wp_insert_term("South Georgia/Sandwich Islands", "location" ); 
 
$f = wp_insert_term("Spain", "location" ); 
 
$state_spain = array('Andaluc','Arag','Asturias','Baleares','Canarias','Cantabria','Castilla - La Mancha','Castilla y Le','Catalu','Comunidad Valenciana','Extremadura','Galicia','La Rioja','Madrid','Navarra','Pa Vasco','Murcia','Ceuta','Melilla');
foreach($state_spain as $value){   wp_insert_term($value, "location", array(  'parent' => $f['term_id'] )); }
 
 
 
 
 wp_insert_term("Sri Lanka", "location" ); wp_insert_term("St. Helena", "location" ); wp_insert_term("St. Kitts and Nevis", "location" ); wp_insert_term("St. Pierre and Miquelon", "location" ); wp_insert_term("St. Vincent and the Grenadines", "location" ); wp_insert_term("Sudan", "location" ); wp_insert_term("Suriname", "location" ); wp_insert_term("Svalbard/Jan Mayen Islands", "location" ); wp_insert_term("Swaziland", "location" ); wp_insert_term("Sweden", "location" ); wp_insert_term("Switzerland", "location" ); wp_insert_term("Syria", "location" ); wp_insert_term("Taiwan", "location" ); wp_insert_term("Tajikistan", "location" ); wp_insert_term("Tanzania", "location" );   wp_insert_term("Thailand", "location" ); wp_insert_term("Togo", "location" ); wp_insert_term("Tokelau", "location" ); wp_insert_term("Tonga", "location" ); 
 wp_insert_term("Trinidad and Tobago", "location" ); wp_insert_term("Tunisia", "location" ); wp_insert_term("Turkey", "location" ); wp_insert_term("Turkmenistan", "location" ); wp_insert_term("Turks and Caicos Islands", "location" ); wp_insert_term("Tuvalu", "location" ); wp_insert_term("U.S. Minor Outlying Islands", "location" ); wp_insert_term("Uganda", "location" ); wp_insert_term("Ukraine", "location" ); wp_insert_term("United Arab Emirates", "location" ); wp_insert_term("Uruguay", "location" ); wp_insert_term("Uzbekistan", "location" ); wp_insert_term("Vanuatu", "location" ); wp_insert_term("Vatican City State (Holy See)", "location" ); wp_insert_term("Venezuela", "location" ); wp_insert_term("Vietnam", "location" ); wp_insert_term("Virgin Islands", "location" ); wp_insert_term("Wallis and Futuna Islands", "location" ); wp_insert_term("Western Sahara", "location" ); wp_insert_term("Wales", "location" ); wp_insert_term("Yemen", "location" ); wp_insert_term("Zambia", "location" ); wp_insert_term("Zimbabwe", "location" ); 

	mysql_query("CREATE TABLE IF NOT EXISTS `".$wpdb->prefix."orderdata` (
  `autoid` mediumint(10) NOT NULL AUTO_INCREMENT,
  `cus_id` varchar(10) NOT NULL,
  `order_id` varchar(50) NOT NULL,
  `order_ip` varchar(100) NOT NULL,
  `order_date` date NOT NULL,
  `order_time` time NOT NULL,
  `order_data` longtext NOT NULL,
  `order_items` longtext NOT NULL,
  `order_address` blob NOT NULL,
  `order_addressShip` blob NOT NULL,
  `order_country` varchar(150) NOT NULL,
  `order_email` varchar(255) NOT NULL,
  `order_total` varchar(10) NOT NULL,
  `order_subtotal` varchar(10) NOT NULL,
  `order_tax` varchar(10) NOT NULL,
  `order_coupon` varchar(10) NOT NULL,
  `order_couponcode` varchar(100) NOT NULL,
  `order_currencycode` varchar(10) NOT NULL,
  `order_shipping` varchar(10) NOT NULL,
  `order_status` int(1) NOT NULL DEFAULT '0',
  `cus_name` varchar(100) NOT NULL,
  `payment_data` blob NOT NULL,
  PRIMARY KEY (`autoid`))");
  
}




  
  
 


function createDateRangeArray($strDateFrom,$strDateTo) {

 $aryRange=array();

  $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
  $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

  if ($iDateTo>=$iDateFrom) {
    array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry

    while ($iDateFrom<$iDateTo) {
      $iDateFrom+=86400; // add 24 hours
      array_push($aryRange,date('Y-m-d',$iDateFrom));
    }
  }
  return $aryRange;
}
 
function ppt_chardata($query=0,$return=false){
 
	global $wpdb; $STRING = "";
	 
	$DATE1 = date("Y-m-d",mktime(0, 0, 0, date("m")-1  , date("d")+10, date("Y")));
	$DATE2 = date("Y-m-d",mktime(0, 0, 0, date("m")  , date("d")+1, date("Y")));	
	
	$dates = createDateRangeArray($DATE1,$DATE2); 
	$newdates = array();
	foreach($dates as $date){	  
	 $newdates[''.$date.''] = 0;
	}
 
	if($return)return $newdates;
 
	// GET ALL DATA FOR THE LAST 31 DAYS
	if($query == 0){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date from ".$wpdb->prefix."posts where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' GROUP BY ID";
 
	}elseif($query == 1){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='1' GROUP BY ID";
	}elseif($query == 2){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='2' GROUP BY ID";
	}elseif($query == 3){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='3' GROUP BY ID";
	}elseif($query == 4){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='4' GROUP BY ID";
	}elseif($query == 5){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='5' GROUP BY ID";
	}elseif($query == 6){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='6' GROUP BY ID";
	}elseif($query == 7){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='7' GROUP BY ID";
	}elseif($query == 8){
	$SQL1 = "select ".$wpdb->prefix."posts.post_date,".$wpdb->prefix."postmeta.meta_value from ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."postmeta ON (".$wpdb->prefix."posts.ID = ".$wpdb->prefix."postmeta.post_id) where ".$wpdb->prefix."posts.post_date >= '".$DATE1."' and ".$wpdb->prefix."posts.post_date < '".$DATE2."' AND post_type='post' AND ".$wpdb->prefix."postmeta.meta_key = 'PackageID'  AND ".$wpdb->prefix."postmeta.meta_value='8' GROUP BY ID";
	}elseif($query == 9){
	$SQL1 = "SELECT order_date AS post_date FROM ".$wpdb->prefix."orderdata LEFT JOIN ".$wpdb->prefix."users ON (".$wpdb->prefix."users.ID = ".$wpdb->prefix."orderdata.cus_id) WHERE ".$wpdb->prefix."orderdata.order_date >= '".$DATE1."' and ".$wpdb->prefix."orderdata.order_date < '".$DATE2."'";
	}
 
 
	$data = $wpdb->get_results($SQL1);
	
	foreach($data as $value){	 
	  $postDate = explode(" ",$value->post_date);	 
		$newdates[$postDate[0]] ++;
	}	 
	 
	// FORMAT RESULTS FOR CHART	
	$i=1;  
	foreach($newdates as $key=>$val){
		$a = $key; 
		if(!is_numeric($val)){$val=0; }
		 	
		$STRING .= '['.$i.','.$val.'], ';
		$i++;		 
	}
	// RETURN DATA	
	return $STRING;
 
}


 


// LOAD IN DATA VALUES
$packdata = get_option("packages");
$CURRENCYCODE = get_option('currency_code');
$CURRENCYPOST = get_option('display_currency_position');


 
?>


<style>
.info { font-size:13px; background:inherit; }
.info legend { font-weight:bold; }
.info dl {  clear:both;  width:100%;  height:8em; }
.info dt {  font-weight:bold;}
.info dd { width:180px;    float:left;   margin:0;}
.info ul { margin-left:0px; }
.info ul.first {  counter-reset:item 0; }
.info ul.second {  counter-reset:item 5;  } 
.info ul li {  display: block;}
.popularup li { background: url('<?php echo PPT_THEME_URI; ?>/PPT/img/arrow_up.png') top left no-repeat;  padding-left:22px; }
.populardown li { background: url('<?php echo PPT_THEME_URI; ?>/PPT/img/arrow_down.png') top left no-repeat; padding-left:22px; }
.orders li { background: url('<?php echo PPT_THEME_URI; ?>/PPT/img/money.png') top left no-repeat;  padding-left:22px; }
.users li { background: url('<?php echo PPT_THEME_URI; ?>/PPT/img/thumb_up.png') top left no-repeat;  padding-left:22px; } 
 
 </style>
<script language="javascript" type="text/javascript" src="<?php echo PPT_THEME_URI; ?>/PPT/js/jquery.flot.min.js"></script>
    
    
 
   

<div class="premiumpress_box altbox"><div class="premiumpress_boxin">
<div class="header">
<h3><img src="<?php echo PPT_FW_IMG_URI; ?>/admin/new/block7.png" align="middle"> Website Summary </h3>							
</div>
 
<div style="background:url('<?php echo PPT_THEME_URI; ?>/PPT/img/content_pane-gradient.gif') bottom left repeat-x; border-bottom:1px solid #ddd; ">
 <br />
<div id="placeholder" style="width:800px;height:300px; margin-left:20px; margin-bottom:20px; margin-top:10px;"></div>
 

 
<script type="text/javascript">
jQuery(function () {
        
    var datasets = {
        "a": {
            label: "New Listings",
            data: [<?php echo ppt_chardata(0); ?>]
        },
		<?php if(isset($packdata[1]['enable']) && $packdata[1]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "b": {
            label: "<?php echo strip_tags($packdata[1]['name']); ?>",
            data: [<?php echo ppt_chardata(1); ?>]
        },
		<?php } ?>		 
		<?php if(isset($packdata[2]['enable']) && $packdata[2]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "c": {
            label: "<?php echo strip_tags($packdata[2]['name']); ?>",
            data: [<?php echo ppt_chardata(2); ?>]
        },
		<?php } ?>
		<?php if(isset($packdata[3]['enable']) && $packdata[3]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "d": {
            label: "<?php echo strip_tags($packdata[3]['name']); ?>",
            data: [<?php echo ppt_chardata(3); ?>]
        },
		<?php } ?>		
		<?php if(isset($packdata[4]['enable']) && $packdata[4]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "e": {
            label: "<?php echo strip_tags($packdata[4]['name']); ?>",
            data: [<?php echo ppt_chardata(4); ?>]
        },
		<?php } ?>
		<?php if(isset($packdata[5]['enable']) && $packdata[5]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "f": {
            label: "<?php echo strip_tags($packdata[5]['name']); ?>",
            data: [<?php echo ppt_chardata(5); ?>]
        },
		<?php } ?>
		<?php if(isset($packdata[6]['enable']) && $packdata[6]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "g": {
            label: "<?php echo strip_tags($packdata[6]['name']); ?>",
            data: [<?php echo ppt_chardata(6); ?>]
        },
		<?php } ?>		
		<?php if(isset($packdata[7]['enable']) && $packdata[7]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "h": {
            label: "<?php echo strip_tags($packdata[7]['name']); ?>",
            data: [<?php echo ppt_chardata(7); ?>]
        },
		<?php } ?>		
		<?php if(isset($packdata[8]['enable']) && $packdata[8]['enable'] ==1 && strtolower(PREMIUMPRESS_SYSTEM) != "shopperpress" && strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>
        "i": {
            label: "<?php echo strip_tags($packdata[8]['name']); ?>",
            data: [<?php echo ppt_chardata(8); ?>]
        },
		<?php } ?>
		
		<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>						
        "j": {
            label: "New Orders",
            data: [<?php echo ppt_chardata(9); ?>]
        },
		<?php } ?>		
		 };

                    // hard-code color indices to prevent them from shifting as
            // countries are turned on/off
            var i = 0;
           jQuery.each(datasets, function(key, val) {
                val.color = i;
                ++i;
            });
            
            // insert checkboxes 
            var choiceContainer =jQuery("#choices");
    jQuery.each(datasets, function(key, val) {
        choiceContainer.append('<div style="float:left;width:150px; margin-bottom:10px;"><input style="float:left; margin-top:8px; margin-right:4px;" type="checkbox" name="' + key +
                               '" checked="checked" id="id' + key + '">' +
                               '<label for="id' + key + '">'
                                + val.label + '</label></div>');
    });
            choiceContainer.find("input").click(plotAccordingToChoices);

            
            function plotAccordingToChoices() {
                var data = [];

                choiceContainer.find("input:checked").each(function () {
                    var key =jQuery(this).attr("name");
                    if (key && datasets[key])
                        data.push(datasets[key]);
                });

                if (data.length > 0)
                   jQuery.plot(jQuery("#placeholder"), data, {
                        shadowSize: 0,
                        yaxis: {   },
                        xaxis: {   ticks: [0, <?php $s = ppt_chardata(0,true); $i=1;foreach($s as $val=>$da){ echo '['.$i.', "'.substr($val,5,5).'"],'; $i++;  } ?>  ],  
						lines: { show: true },
						label: 'string' },						
						selection: { mode: "xy" },
                                                grid: { hoverable: true, clickable: true },
                                                bars: { show: true,lineWidth:3,autoScale: true, fillOpacity: 1 },
                                        points: { show: true },
                                        legend: {container:jQuery("#LegendContainer")    }
             
                                


                        
                    });
            }
                var previousPoint = null;
   		jQuery("#placeholder").bind("plothover", function (event, pos, item) {
       jQuery("#x").text(pos.x.toFixed(2));
       jQuery("#y").text(pos.y.toFixed(2));

       
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;
                    
                   jQuery("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1];
                    if (y==1)
                    {
                    showTooltip(item.pageX, item.pageY, y + " " + item.series.label );
                    }
                    else
                    {
                    showTooltip(item.pageX, item.pageY, y + " " + item.series.label );
                    }
                }
                }
                else {
               jQuery("#tooltip").remove();
                previousPoint = null;            
            
            
        }
    });
function showTooltip(x, y, contents) {
       jQuery('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }
            plotAccordingToChoices();
        });
</script>
<div id="LegendContainer" style="float:right; margin-right:20px;margin-top:-10px;"></div>
<div id="choices" style="padding:10px;">&nbsp;</div>
<div class="clearfix"></div>
</div>

 
 
<div class="grid400-left" style="margin-top:10px;">

 
<fieldset class="info">
<div class="titleh"> <h3>Popular Listings</h3>  </div>
 
 

<dd>
<p><b>Most Popular</b></p>
<ul class="first popularup">
<?php
// The Query
$posts = query_posts( "meta_key=hits&orderby=meta_value_num&order=DESC&showposts=10" );
foreach($posts as $post){
	echo '<li><a href="'.get_permalink($post->ID).'" target="_blank">'.$post->post_title."</a> (".get_post_meta($post->ID, 'hits', true)." hits)".'</li>';
}
// Reset Query
wp_reset_query();
?>
</ul>
</dd>
<dd>
<p><b>Least Popular</b></p>
<ul class="second populardown">
<?php
// The Query
$posts = query_posts( "meta_key=hits&orderby=meta_value_num&order=ASC&showposts=10" );
foreach($posts as $post){
	echo '<li><a href="'.get_permalink($post->ID).'" target="_blank">'.$post->post_title."</a> (".get_post_meta($post->ID, 'hits', true)." hits)".'</li>';
}
// Reset Query
wp_reset_query();
?>
</ul>
</dd>
</fieldset>



<fieldset class="info">
<div class="titleh"> <h3>Active Users</h3>  </div>
 

<dd>
<p><b>Most Active</b></p>
<ul class="first users">
<?php
// The Query
$SQL = "SELECT count(".$wpdb->prefix."posts.post_author) AS total,".$wpdb->prefix."posts.post_author, ".$wpdb->prefix."users.user_nicename FROM ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."users ON (".$wpdb->prefix."posts.post_author = ".$wpdb->prefix."users.ID AND ".$wpdb->prefix."posts.post_status='publish' AND ".$wpdb->prefix."posts.post_type='post') WHERE ".$wpdb->prefix."users.user_nicename != '' 
GROUP BY post_author ORDER BY count(post_author) DESC LIMIT 10"; 
$posts = $wpdb->get_results($SQL); 
foreach($posts as $post){
	echo '<li><a href="'.get_author_posts_url( $post->post_author, $post->user_nicename ).'" target="_blank">'.$post->user_nicename."</a> (".$post->total." listings)".'</li>';
}
// Reset Query
wp_reset_query();
?>
</ul>
</dd>
<dd>
<p><b>Least Active</b></p>
<ul class="second">
<?php
// The Query
$SQL = "SELECT count(".$wpdb->prefix."posts.post_author) AS total,".$wpdb->prefix."posts.post_author, ".$wpdb->prefix."users.user_nicename FROM ".$wpdb->prefix."posts LEFT JOIN ".$wpdb->prefix."users ON (".$wpdb->prefix."posts.post_author = ".$wpdb->prefix."users.ID AND ".$wpdb->prefix."posts.post_status='publish' AND ".$wpdb->prefix."posts.post_type='post') WHERE ".$wpdb->prefix."users.user_nicename != '' GROUP BY post_author ORDER BY count(post_author) ASC LIMIT 10"; 
$posts = $wpdb->get_results($SQL); 
foreach($posts as $post){
	echo '<li><a href="'.get_author_posts_url( $post->post_author, $post->user_nicename ).'" target="_blank">'.$post->user_nicename."</a> (".$post->total." listings)".'</li>';
}
// Reset Query
wp_reset_query();
?>
</ul>
</dd>
 
 
</fieldset>




</div><div class="grid400-left last" style="margin-top:10px;">


<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){  ?>


<fieldset class="info">
<div class="titleh"> <h3>Recent Orders</h3>  </div>
 



<dd>
<p><b>Order ID</b></p>
<ul class="first orders">
<?php
$checkbox=0;
$SQL = "SELECT order_id, order_total, order_date FROM ".$wpdb->prefix."orderdata LEFT JOIN $wpdb->users ON ($wpdb->users.ID = ".$wpdb->prefix."orderdata.cus_id) LIMIT 10 "; 
$data = $wpdb->get_results($SQL);
foreach($data as $order){ ?>
	<li><a href="admin.php?page=orders&id=<?php echo $order->order_id; ?>" target="_blank"><?php echo $order->order_id; ?></a></li>
<?php } ?> 
</ul>
<dd>
<p><b>Date / Price</b></p>
<ul class="second">
<?php
$data = $wpdb->get_results($SQL);
foreach($data as $order){ ?>
	<li><?php echo $order->order_date; ?> / <?php echo $CURRENCYCODE.$order->order_total; ?> </li>
<?php } ?> 
</ul>
</dd>
</fieldset>
<?php } ?>


<fieldset class="info">
<div class="titleh"> <h3>Website Summary</h3>  </div>
 


<?php 
$count_posts 	= wp_count_posts(); 
$count_pages 	= wp_count_posts('page');
$comments 		= $wpdb->get_row("SELECT count(*) as count FROM $wpdb->comments");
$articles 		= $wpdb->get_row("SELECT count(*) AS count FROM $wpdb->posts WHERE post_type='article_type'");
$order_total 	= $wpdb->get_row("SELECT sum(order_total) AS total FROM ".$wpdb->prefix."orderdata");
wp_reset_query();

if(PREMIUMPRESS_SYSTEM == "ShopperPress"){

$txt['label1'] = "Total Store Products";
$txt['label2'] = "Live Products";
$txt['label3'] = "Pending/Draft Products";
$txt['label4'] = "Deleted Products";
$txt['label5'] = "Monthly Sales";


}elseif(PREMIUMPRESS_SYSTEM == "DirectoryPress"){

$txt['label1'] = "Total Directory Listings";
$txt['label2'] = "Live Websites";
$txt['label3'] = "Pending/Draft Websites";
$txt['label4'] = "Deleted Websites";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "CouponPress"){

$txt['label1'] = "Total Website Coupons";
$txt['label2'] = "Live Coupons";
$txt['label3'] = "Pending/Draft Coupons";
$txt['label4'] = "Deleted Coupons";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "ClassifiedsTheme"){

$txt['label1'] = "Total Website Classifieds";
$txt['label2'] = "Live Classifieds";
$txt['label3'] = "Pending/Draft Classifieds";
$txt['label4'] = "Deleted Classifieds";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "RealtorPress"){

$txt['label1'] = "Total Real Estate";
$txt['label2'] = "Live Real Estate";
$txt['label3'] = "Pending/Draft Property";
$txt['label4'] = "Deleted Real Estate";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "AuctionPress"){

$txt['label1'] = "Total Auctions";
$txt['label2'] = "Live Auctions";
$txt['label3'] = "Pending/Draft Auctions";
$txt['label4'] = "Deleted Auctions";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "MoviePress"){

$txt['label1'] = "Total Videos";
$txt['label2'] = "Live Videos";
$txt['label3'] = "Pending/Draft Videos";
$txt['label4'] = "Deleted Videos";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "EmployeePress"){

$txt['label1'] = "Total Jobs";
$txt['label2'] = "Live Jobs";
$txt['label3'] = "Pending/Draft Jobs";
$txt['label4'] = "Deleted Jobs";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "ResumePress"){

$txt['label1'] = "Total Resumes";
$txt['label2'] = "Live Resumes";
$txt['label3'] = "Pending/Draft Resumes";
$txt['label4'] = "Deleted Resumes";
$txt['label5'] = "New Each Month";

}elseif(PREMIUMPRESS_SYSTEM == "AgencyPress"){

$txt['label1'] = "Total Profiles";
$txt['label2'] = "Live Profiles";
$txt['label3'] = "Pending/Draft Profiles";
$txt['label4'] = "Deleted Profiles";
$txt['label5'] = "New Each Month";


}
?>

<div id="info">
<dd style="width:330px;">
<ul class="first">
<li><?php echo $txt['label1']; ?></li>
<li>--- <?php echo $txt['label2']; ?></li>
<li>--- <?php echo $txt['label3']; ?></li>
<li>--- <?php echo $txt['label4']; ?></li>
<li>Total Articles</li>
<li>Total Pages</li>
<li>Total Comments</li>
</ul>
</dd>
<dd style="width:40px;">
<ul class="second">
<li><a class="ico-comms" href="edit.php"><?php echo $count_posts->publish+$count_posts->draft+$count_posts->pending+$count_posts->trash; ?></li>
<li><a class="ico-comms" href="edit.php"><?php echo $count_posts->publish; ?></li>
<li><a class="ico-comms" href="edit.php?post_status=pending"><?php echo $count_posts->draft+$count_posts->pending; ?></a></li>
<li><a class="ico-comms" href="edit.php?post_status=trash"><?php echo $count_posts->trash; ?></a></li>
<li><a class="ico-comms" href="edit.php?post_type=article_type"><?php echo $articles->count; ?></a></li>
<li><a class="ico-comms" href="edit.php?post_type=page"><?php echo $count_pages->publish; ?></a></li>
<li><a class="ico-comms" href="edit-comments.php?comment_status=all"><?php echo $comments->count; ?></a></li>
</ul>
</dd> 
</div>
</fieldset>

<?php if(strtolower(constant('PREMIUMPRESS_SYSTEM')) != "comparisonpress"){ ?>	
<fieldset style="background:#e7f6dc; ">
<legend><strong>Sales Summary </strong></legend>
Order Total: <?php if($order_total->total ==""){ $oT = "0"; }else{ $oT = $order_total->total;} echo premiumpress_price($oT,$CURRENCYCODE,$CURRENCYPOST,1,true);   ?>
</fieldset> 
<?php } ?>

</div>
<div class="clearfix"></div>
 

</div>
</div> 