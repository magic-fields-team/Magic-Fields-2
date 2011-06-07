<?php
global $wpdb,$blog_id;

//useful for get quickly the path for  images/javascript files and css files
//return something like: http://wordpress.local/wp-content/plugins/Magic-Fields/
define('MF_BASENAME',plugins_url().'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)));
define('MF_URL',MF_BASENAME);

//return something like: /Users/user/sites/wordpres/wp-content/plugins/Magic-Fields
define("MF_PATH", dirname(__FILE__));

define("PHPTHUMB",MF_URL."thirdparty/phpthumb/phpThumb.php");

define('MF_FILES_NAME','files_mf');
define('MF_CACHE_NAME','cache');

if( mf_mu2() ){
  /* MU path /wp-content/blogs.dir/{id_blog}/files_mf */
  define('MF_FILES_DIR', WP_CONTENT_DIR.DS.'blogs.dir'.DS.$blog_id.DS.MF_FILES_NAME.DS);
  define('MF_FILES_URL', WP_CONTENT_URL.'/blogs.dir/'.$blog_id.'/'.MF_FILES_NAME.'/');

  define('MF_CACHE_DIR', MF_FILES_DIR.MF_CACHE_NAME.DS);
  define('MF_CACHE_URL', MF_FILES_URL.MF_CACHE_NAME.'/');

}else{
  define('MF_FILES_DIR', WP_CONTENT_DIR.DS.MF_FILES_NAME.DS);
  define('MF_FILES_URL', WP_CONTENT_URL.'/'.MF_FILES_NAME.'/');

  define('MF_CACHE_DIR', MF_FILES_DIR.MF_CACHE_NAME.DS);
  define('MF_CACHE_URL', MF_FILES_URL.MF_CACHE_NAME.'/');
}

//Todo: poner aqui una opcion para ver si los post types son globales o no
// solo seria cuestion de cambiar base_prefix por prefix y ya

define( 'MF_TABLE_POSTTYPES', $wpdb->base_prefix . 'mf_posttypes' );
define( 'MF_TABLE_CUSTOM_TAXONOMY', $wpdb->base_prefix . 'mf_custom_taxonomy' );
define( 'MF_TABLE_CUSTOM_FIELDS',$wpdb->base_prefix . 'mf_custom_fields' );
define( 'MF_TABLE_CUSTOM_GROUPS',$wpdb->base_prefix . 'mf_custom_groups' );
define( 'MF_TABLE_POST_META', $wpdb->prefix.'mf_post_meta' );

//define name for settings MF
define('MF_SETTINGS_KEY', 'mf_settings');
define('MF_DB_VERSION_KEY', 'mf_db_version');
define('MF_DB_VERSION', 1);
