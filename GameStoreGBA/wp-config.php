<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'gamestoregba' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '_b=sU:D^9^5cxus }s|R615Z<Sd1 jQxX^A;pzsuA|[WbD-}D-[SqQ4,* $aGj0U' );
define( 'SECURE_AUTH_KEY',  '$n#?rEDkZBqRV^o~ ||my(58i5JYk5QgNsy|7ydy5U&aeTMY9v2S u0.;}db%3;v' );
define( 'LOGGED_IN_KEY',    '$W.nC?-W0#6lS-i%i{x=4 DBWXinaab3:lG_+<1{P 9+/!CFAV<>aP|pNYn]]-av' );
define( 'NONCE_KEY',        '/]Z/{Y2]tFh08],au,9xQE/iCy;rL#qmC]#[KWV[|lAW5{Cz:Ea6<P_*==T)8-Pv' );
define( 'AUTH_SALT',        'vy O%hufePWI<i*p_jV,*bI~z/eOK}pvzS4Q`#2&<nizFA,{X2hH ,U8nl<}^TCt' );
define( 'SECURE_AUTH_SALT', 'Y#^7PW[uL4rMny?~FmNh>II/8Pe8wk=9=w7*AM=dZ(=Du^,oo7n*Y$-?;etDA OR' );
define( 'LOGGED_IN_SALT',   '$+}_TF_*0bAk=^~79cyeNf8dA5GuI^.Gre{I~&LGT Hx_4TO]An/anB*09%rv|l&' );
define( 'NONCE_SALT',       'C/Y?TYlbV=28BGIC@@F8iQwK,7m09Y$e~31r2ah6xnHI6|bSoO0$G4(.n?7G@BI3' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
