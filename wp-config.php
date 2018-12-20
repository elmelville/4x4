<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define('DB_NAME', 's4');

/** MySQL database username */
define('DB_USER', 's4');

/** MySQL database password */
define('DB_PASSWORD', 'nKbGTM3NlFZt9bbOkvTzILYdrjCG5FrQxCCYIuQAVGh4gSju');

/** MySQL hostname */
define( 'DB_HOST', 'localhost:/var/run/mysqld/mysqld.sock' );

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'od5KC<hGI7,[R,osXV5]^ BZL_K_^2JoZ[0#qvW)V^@a67.-pfoa5t?Qh-7D)^+@' );
define( 'SECURE_AUTH_KEY',  'b*t2s:#How=V;/nV.&v:RLRE8YT$I2h{wTS0C8fR%H&-FT`emq_J3YO;lmXl0@g=' );
define( 'LOGGED_IN_KEY',    'JaxKL:NbgfSx!I7aHK@$OCx/;bL4Y:lB[?VAX{_z<U8Tshj!n*q?cEF/~4%LWLuL' );
define( 'NONCE_KEY',        'Q^^ci.BZ;EgS +/KMBZr#)yQd1>Wj@N.z/)FTO5X-X`$@)>8Xx!gw4 fTZ02Iv@s' );
define( 'AUTH_SALT',        'z iQPQ29^!aKJ/Kd7YIz<3D|u>14?pXL}[AFc0v&^o{`Au!yZ`lL4cadk>/!Y@D/' );
define( 'SECURE_AUTH_SALT', 'hlLztKV[hNKXJi]<]R;;G;f1!6dJ*@~9larS|vByHppzr9AGnv[utLbSPEBI9V?N' );
define( 'LOGGED_IN_SALT',   'oSGnRXi#7?QA+bE7t$~jnkfleN[~`W; {{fmO&CoU)srA^>txt||b_f1y&Fu/KHn' );
define( 'NONCE_SALT',       '*!DwL4sos/MgoVqIW24l|V<],Bw|NvE!,)hYY [=776JFXE<1@E&eN8t%KF}^Zj`' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = '34y0jF_';


require_once ABSPATH . 'lw-wp-config.php';

define('WP_MEMORY_LIMIT', '512M');


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';