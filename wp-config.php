<?php
// ===================================================
// Load database info and local development parameters
// ===================================================

if ( ! defined( 'WP_HOME' ) )
	define( 'WP_HOME', 'http://' . $_SERVER['HTTP_HOST'] );


if ( file_exists( dirname( __FILE__ ) . '/wp-local-config.php' ) ) {
	// Local
	include( dirname( __FILE__ ) . '/wp-local-config.php' );
} elseif ( file_exists( dirname( __FILE__ ) . '/wp-test-config.php' ) ) {
	// Test
	include( dirname( __FILE__ ) . '/wp-test-config.php' );
} else {
	// Live
	include( dirname( __FILE__ ) . '/wp-live-config.php' );
}

define( 'NUM_SEARCH_ITEMS',			25 );


// ===================================================
// Table prefix
// ===================================================

$table_prefix  = 'mrd_';


// ===================================================
// Set WP language
// ===================================================

//define( 'WPLANG', 'de_DE');


// ===================================================
// Give mama some more memory
// ===================================================

define( 'WP_MEMORY_LIMIT', '96M');


// ===================================================
// Activate compression
// ===================================================

define( 'COMPRESS_CSS',        true );
define( 'COMPRESS_SCRIPTS',    true );
define( 'ENFORCE_GZIP',        true );


// ===================================================
// Some admin tweeks
// ===================================================

define( 'MEDIA_TRASH', 			false );

define( 'DB_CHARSET', 			'utf8' );
define( 'DB_COLLATE', 			'' );

define( 'WP_ALLOW_REPAIR', 		true );
define( 'DISALLOW_FILE_EDIT', 	true );


// ===================================================
// Authentication Unique Keys and Salts.
// ===================================================

define( 'AUTH_KEY',         'VW-h|&Ew]E8JigXnXKL&qLk+[;%6aa(wp7ItBb9BYDZ]0 SR-a#]DWY^w& >+[.f' );
define( 'SECURE_AUTH_KEY',  'fL?*,^^H/v#)&ae-UOg][?gn6|yP2:br!%B,19G~Fv%FqXDp}|z(8w{V#Oen%wE4' );
define( 'LOGGED_IN_KEY',    'ZBem@mwKIJ4w+|Qa4s+d~8Lh>TYq8ZO*7MuI-_gk[M 7Kw%;J%b8mh%?@G6U_#bb' );
define( 'NONCE_KEY',        'mj`4`8=H8@T.FN$+={<X(jv-(]wiGe+m%`HVqW|T~-n?P:W2X0{ w<42JX2ZEjM8' );
define( 'AUTH_SALT',        'gVtE9:Hy7L0Lhh-0%K|-^768u=jS*sCG[&4t:5 bK^*6h+9OyP7rm~2k/x7YnHNA' );
define( 'SECURE_AUTH_SALT', 'i+0IoTn`|Rd8{Ov_V-=i;|Q}5=3DJt/*9NWP!<-xy)CAu|tXTQ2ecmDx)1jMi^,*' );
define( 'LOGGED_IN_SALT',   'j]z=`knv4&v+sHz`_=kZPHR4@.&6rX|Iusc9HUc$RjcZ/PgQIhw9~;u8S48&(IPH' );
define( 'NONCE_SALT',       'y#=|c(^nk.a_9]xh&_}7;H-M!+c`zKm0+Y@YBY/K{s+OH6KT3{~oyG{D1Y-lm87S' );


define( 'AUTOSAVE_INTERVAL',	86400 );
define( 'WP_POST_REVISIONS', 	false );

// ===================================================
// DO NOT EDIT FORM HERE ! ! !
// ===================================================

if ( !defined('ABSPATH') )
	define( 'ABSPATH', dirname(__FILE__) . '/' );

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

// Disable XML RPC
add_filter( 'xmlrpc_enabled', '__return_false' );
