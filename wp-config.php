<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// Fetch the environment-specific config
$env_path = dirname(__FILE__) . '/env.php';
if ( !file_exists( $env_path ) ) {
	die( 'You must create an env.php file to continue! Use env-sample.php as a template.' );
} else {
	require( dirname(__FILE__) . '/env.php' );
}

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'mKZgQhdxGBgNMMp4aI8bGt9Cg9qAjXTzBH3Z3jVNPuWRhxGzg1mOg6iUdMkg6322');
define('SECURE_AUTH_KEY',  'qKs4yKeOnjeIDjkxdnHxMQM0hyuny3rB4Oqs75Fr7EE6pVN94j1gcXPzTH1jH37k');
define('LOGGED_IN_KEY',    'Q9uum23ODGpOWEIJvs8wQUuLQdGgeoPMSn0WndxpbiQ5yjD0ivKf8S9s8gUa552c');
define('NONCE_KEY',        '3Q1EJgE0vWO7mKALBQr33drxDluCbntBYhU2rlwUwdz55we5yGahBpCKOEox1Nmh');
define('AUTH_SALT',        'sXVH9wTS0gIKtKoIincyGlDLd3bkVAAwsCSppz7aIn4C2DwyGOnXsCwjvOX4PLGM');
define('SECURE_AUTH_SALT', 'W3fLXeJENpoN0S3b0khM8IDTp2bDzXM7KU3o7i3WbSWA3CjdSigVv5l1YNCnrupK');
define('LOGGED_IN_SALT',   '4XVPk1UyORlXiOKOuiXDghiPLDDjXhHNfZz8Kfbw0UH8QGmg4PUIBetXOeq7Uvfl');
define('NONCE_SALT',       'C42xTCHvF6G8mcG3PAx3lc50eGWXQxsklrbRJTdNK7JWINKvnZ7P0F1u6nFgID7d');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('SCRIPT_DEBUG', true);
