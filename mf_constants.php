<?php
if ( !defined('DS') ){
  if (strpos(php_uname('s'), 'Win') !== false )
    define('DS', '\\');
  else 
    define('DS', '/');
}

//useful for get quickly the path for  images/javascript files and css files
//return something like: http://wordpress.local/wp-content/plugins/Magic-Fields/
define('MF_BASENAME',plugins_url().'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)));
define('MF_URL',MF_BASENAME);
//return something like: /Users/user/sites/wordpres/wp-content/plugins/Magic-Fields
define("MF_PATH", dirname(__FILE__));

define("PHPTHUMB",MF_URL."thirdparty/phpthumb/phpThumb.php");

define('MF_FILES_NAME','files_mf');
define('MF_CACHE_NAME','cache');
//ToDo: falta probar que en MU este colocando el path correcto
define('MF_FILES_DIR', WP_CONTENT_DIR.DS.MF_FILES_NAME.DS);
define('MF_FILES_URL', WP_CONTENT_URL.'/'.MF_FILES_NAME.'/');

define('MF_CACHE_DIR', MF_FILES_DIR.MF_CACHE_NAME.DS);
define('MF_CACHE_URL', MF_FILES_URL.MF_CACHE_NAME.'/');

global $supports,$wpdb;

$supports = array(
    'title','editor','author',
    'thumbnail','excerpt','trackbacks',
    'custom_fields','comments','revisions',
    'page_attributes'
);

define( 'MF_TABLE_POSTTYPES', $wpdb->prefix . 'mf_posttypes' );
define( 'MF_TABLE_CUSTOM_TAXONOMY', $wpdb->prefix . 'mf_custom_taxonomy' );
define( 'MF_TABLE_CUSTOM_FIELDS',$wpdb->prefix . 'mf_custom_fields' );
define( 'MF_TABLE_CUSTOM_GROUPS',$wpdb->prefix . 'mf_custom_groups' );
define( 'MF_TABLE_POST_META', $wpdb->prefix.'mf_post_meta' );

//define name for settings MF
define('MF_SETTINGS_KEY', 'mf_settings');
define('MF_DB_VERSION_KEY', 'mf_db_version');
define('MF_DB_VERSION', 1);
