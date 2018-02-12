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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'mkrtich');

/** MySQL database password */
define('DB_PASSWORD', 'azerty007m');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'kQv+ZO] @kaYOf3?Dg/I*2IZEZf@l+m4Bu/go%9ewK%Le/h:S/!Tc=+2ZG-!G?yo');
define('SECURE_AUTH_KEY',  '{) =m`s.1y{%5sdVJ5|.&RyWI*b4|aoe)_KZ[L:$his$B+-Va.ZwKc%)/qQrbGOF');
define('LOGGED_IN_KEY',    '/}G.|ixKuY|#hfJ+?J6}YWh.J0(kn99E99=>LTG#Ri`p+3uQK.V<DE+4N9(<NM2b');
define('NONCE_KEY',        'v/9^A<2kHV;rO`vcBDK<XvIc+cZ.$2v-x.2n[xn!+vdPt-UUfC!Aa-Fny|O@;Jh?');
define('AUTH_SALT',        '[dAX!r$Q:A,-{_SB]^`;tBzp*>^oFnteM{Jp_LJUYM=7n^AU<j|vjSqK.z,lx#mB');
define('SECURE_AUTH_SALT', 'X2L)D~,x8MaBm+wsS?J*x|/4@:,M2!JBUpw=i_Rci8+C/GCw*mWERr0$`C*I1^!i');
define('LOGGED_IN_SALT',   '+Xk^yZYqx+(nIhs=-A{`BBntQ yUlZtMkpXV|tnUko!^j+$k%zbN-lEi8f,)u:yx');
define('NONCE_SALT',       'OE+q+,,H2`%)&0+T3eEmMwpTyA{9cV4Kd2zk$rC4VA}}p7BH~njg{y:I~|[=[#r%');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
