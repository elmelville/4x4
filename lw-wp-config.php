<?php

/** LW specific constants **/
define('LWMWP_SITE_ENDPOINT', 'https://app.w7mt6k75-liquidwebsites.com/api/sites/4/');
define('LWMWP_API_TOKEN',     '2036f1a8-4755-4381-a500-d0f4461bbc6b');

/** Core auto updates **/
defined('WP_AUTO_UPDATE_CORE') || define('WP_AUTO_UPDATE_CORE', 'minor');

/** Fail2Ban **/
defined('WP_FAIL2BAN_BLOCK_USER_ENUMERATION') || define('WP_FAIL2BAN_BLOCK_USER_ENUMERATION', true);

/** Always use the direct method for file access **/
defined('FS_METHOD')           || define('FS_METHOD', 'direct');

/* Turn HTTPS 'on' if HTTP_X_FORWARDED_PROTO matches 'https' */
if ( isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strpos($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') !== false) {
    $_SERVER['HTTPS'] = 'on';
}

/** Set Client IP based on HTTP_X_FORWARDED_FOR if provided. **/
if ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
	$ip_list = explode( ',', $_SERVER['HTTP_X_FORWARDED_FOR'] );
	$_SERVER['REMOTE_ADDR'] = trim( $ip_list[0] );
}



// Must be defined for MWP Staging Sync to function
define('LW_PRODUCTION_URL', 'https://the4x4guys.com');
define('LW_STAGING_URL', 'https://4x4guys-staging.w7mt6k75-liquidwebsites.com');
define('LW_SYNC_KEY', 'f7c07cb4fb092da07effe2a22c49ff3a9db3efe35c782e12938c7729195d4355');


defined('LWMWP_STAGING_SITE') || define('LWMWP_STAGING_SITE', true);


defined('JETPACK_STAGING_MODE') || define( 'JETPACK_STAGING_MODE', true );
