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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '2m7NzoXq87Z5kZLjEPSX8eyn3JTHkoaCU5gNIKCqCZKsIY9GqWt5fUuzkXG7S0q2EquTL0MkS5OydxECFKvHOA==');
define('SECURE_AUTH_KEY',  'Q1cxwFO46IPYDKO3Ic5o1tCM5v09qrUdPnDrQOeTLmELZJR4YJF0I0YkcaR2qKFi4bFwQkgxOjKGH+J4KlKDEg==');
define('LOGGED_IN_KEY',    'RciS+eNywrw2DEhj/l+yRaYArG0192M3hoXxPa5+BQD0OBRJZg+mFnyY6rSw4GNsLK/AJg8fHwKHivIucKATzg==');
define('NONCE_KEY',        '02lDx9eXNMcFBGK99BilLoKf2s8uFhabDHMOAjYbegtXnSSBDnImW4RVFplfAd4cuBpD6Y+/W3BJ+ad6wpWrXg==');
define('AUTH_SALT',        '8LcRoeKwS/JMfWXKytq+AFYadpVz4gr+HejZYctC+JqAcaZTk6dEk7qPDE44VcbB6RPMJ5XLaB9Fe8ZCsCtG1Q==');
define('SECURE_AUTH_SALT', 'kcX1j9/fx2ZVaQKznLT5Bn+KHVRHiJwJmsfcbC784G3c2ao9cHNXhJcs0ZJNAGCgSo3VzGH/u05SwS3RKs6IHw==');
define('LOGGED_IN_SALT',   'VIiFSzrYIGs+LMWEe/9c6f9YhAiEOvJCuP4gx2UVlncObuyr1dGpjtS+l1U2BMnnwq7C1RjBi9yEqXHsC9ZAtQ==');
define('NONCE_SALT',       's5zOHF/0ez/JBsHh328QBbhkR5lJjuQITHwpDzwFse+TwDPLWj8wEnMAt2xWHUg6FubVelNCoZG3ZVvxynVo7Q==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
