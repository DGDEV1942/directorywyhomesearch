<?php

// ** MySQL settings ** //
define('DB_NAME', 'habitech_ directorywhy');
define('DB_USER', 'habitech_directo');
define('DB_PASSWORD', '[b?pHDPG*c$}');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');
define('WP_MEMORY_LIMIT', '64M');

$table_prefix = 'wp_';

define('AUTH_KEY',         'N_%I&E|(Z|zPv 9|a:K3$d^gJ8 )%66W&E[j?cVy-+dZ9 %w,H`Wb-9dPd.[M|Pg');
define('SECURE_AUTH_KEY',  '*4Kit#qJLf4f^Hj#os-jb-13eVjOJjb2#_j#mVE{fI4TjP/|Z`n(3/[gl()c[Z[6');
define('LOGGED_IN_KEY',    'G ~yY{s E=U+z)= Ie-iMJ=W9.g6&N[,&qSa3`sOnxYa-_mlYBh!gBzQBtMNlM6&');
define('NONCE_KEY',        'dz-6+U^)8-}?&bS-]p+uBsS]I]eFEU?T2wz{fUy9`rAIi)[NI^HLhn#X?(pZWN}i');
define('AUTH_SALT',        '5|uA+ahgK<[+]/E1fN[Ipm5O/`q||Yb/8CLZtVGjZD}ByL}vaaV.wDL+yG~G&2SI');
define('SECURE_AUTH_SALT', '2^awW$^{/F1BS^9XDq|f~:{nGth$^Lkik,=*[Sk^x,+bAs~RWXy4kc(q-~g]+9bh');
define('LOGGED_IN_SALT',   'D=EwwAQNmkgfL?ip6M6e+Lej7M%vl@&a ;XaFnjGm ;yQW^m$2O+@{3k>_f?0yLh');
define('NONCE_SALT',       's~@ZMX~PiK*[TvY*olD&Y?!_qOPELsBZL+K^W&82XK!P0)4L~Y,.PV-:hA8+~)6Q');


//define('WP_HOME','http://directory.wyhomesearch.com');
//define('WP_SITEURL','http://directory.wyhomesearch.com');
//define('WP_HOME','https://directory.wyhomesearch.com');
//define('WP_SITEURL','https://directory.wyhomesearch.com');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');


//update_option( 'siteurl', 'https://directory.wyhomesearch.com' );
//update_option( 'home', 'https://directory.wyhomesearch.com' );
