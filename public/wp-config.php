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
define('AUTH_KEY',         'ngnrdIJzm+CQqS8ZlWl/4bH1A8Iq31pR7I6DCWXNnxq/mgrNm12lbYQOOUp3KD8hP9bz+Juf/3QbqxxKiUlJPA==');
define('SECURE_AUTH_KEY',  'InKG2cgJBm3mZe47frKKUEOqsVA9nAuNolgxXN5R+I6vQcduezN9ct3N9bMDECHj0j/47VO1f1WXaAKz2tULng==');
define('LOGGED_IN_KEY',    'N1geZrfdkqfNMIaH2AZcfqrk8dezQ176LQjN6q+cTqWD0K8YS3/3VcCO3zIn6SMjns3k3gNVhaMsDE8J6T861A==');
define('NONCE_KEY',        'gPpKBl/kdaAVb3MeqV49jL9k2C6riBZDhXqGc4CjbSWL1Uv5AKzQGhBU+zzdRv4kCjs2o9FruNGBeX2qqaGUUA==');
define('AUTH_SALT',        '2zlFsYehc59iA6TxjYLrexV+WTQIfhvQQmkHo0AqdvQ8VjyeC7mzJS7qkD5kquX5lDYYqon5oo4XOdk4NboeYw==');
define('SECURE_AUTH_SALT', 'yAyjGlid4FkZ8XTBDsXlk1vYZgoFZwKF6HAEG4M0W6SYw5ZfNxRtoXTM1Rfjo6O5aVhdyiA+0t59d8rFqxAfCQ==');
define('LOGGED_IN_SALT',   'gBQdTSkFwUqePIT+AMLz3Ms6ZS9u1z0u0XkYTLXvhG6n004u+R5MI2Uj3LFaYofEQrQbt7o4F/SW0JF7nhGNNw==');
define('NONCE_SALT',       'RetTW+Cox8ZrQusgmpFHPJFwMCbHasyp/zVfyhJ9DtGw7aqJGzZUbW5iOJd5aTpImHcRK4LTgxYXLTx1O3bXWA==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


define('JWT_AUTH_SECRET_KEY', '#B9twL6b$Xr6ejW&@N}KXw`(g.D9g(&iNXs[Mxnu`YcWcLFZha!pMpRtO}hwgv/]');
define('JWT_AUTH_CORS_ENABLE', true);


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
