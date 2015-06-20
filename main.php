<?php
/*
Plugin Name: Magic Fields
Plugin URI: http://magicfields.org
Description: Create custom fields for your post types
Version: 2.3.1
Author:  Hunk and Gnuget
Author URI: http://magicfields.org
License: GPL2
*/

/*  Copyright 2011 Magic Fields Team

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * i18n
 */
global $mf_domain,$mf_pt_register;
$mf_domain = 'magic_fields';
$mf_pt_register = array();
$mf_pt_unique = array();

/**
 * Constants
 */
require_once( 'mf_extra.php' );
require_once( 'mf_constants.php' );

//auto loading files
function mf_autoload( $name ) {
  //main files
  if( file_exists( MF_PATH.'/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/'.$name.'.php' );
  }

  //admin files
  if( file_exists( MF_PATH.'/admin/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/admin/'.$name.'.php' );
  }

  //field types
  if( file_exists( MF_PATH.'/field_types/'.$name.'/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/field_types/'.$name.'/'.$name.'.php');
  }
}
if (function_exists("__autoload")) {
	spl_autoload_register("__autoload");
}
spl_autoload_register("mf_autoload");

/**
 * Activation and Deactivation
 */
register_activation_hook( __FILE__, array('mf_install', 'install' ) );

//In wp 3.1 and newer the register_activation_hook is not called
//when the plugin is updated so we need call the upgrade
//function by hand
function mf_update_db_check() {
  if ( get_option(MF_DB_VERSION_KEY) != MF_DB_VERSION ) {
    mf_install::upgrade();
  }
}
add_action('plugins_loaded','mf_update_db_check');


//MF in mode plusing multinetwork
if( mf_mu2() ){
  mf_install::install();
}


//Register Post Types and Custom Taxonomies
$mf_register = new mf_register();

//Adding metaboxes, and hooks for save the data when is created a new post
$mf_post = new mf_post();


if( is_admin() ) {

  //load_plugin_textdomain($mf_domain    , '/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang', basename(dirname(__FILE__)).'/lang');
load_plugin_textdomain('magic_fields', '/'.PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)).'/lang', basename(dirname(__FILE__)).'/lang');
  //check folders
  add_action('admin_notices', array('mf_install', 'folders'));

  //add common function
  require_once(MF_PATH.'/mf_common.php');

  add_action( 'admin_enqueue_scripts', 'mf_enqueue_scripts' );
  function mf_enqueue_scripts() {
    // CSS Files
    wp_register_style( 'mf_admin_css',MF_BASENAME.'css/mf_admin.css' );
    wp_enqueue_style( 'mf_admin_css' );
  }

  //unique post type calll
  add_action('admin_menu', array('mf_menu', 'unique_post_type'));

  // Settings Page
  add_action( 'admin_menu', 'mf_menu' );
  function mf_menu( ) {
      global $mf_domain;
      add_options_page(__('Magic Fields Settings', $mf_domain ), 'Magic Fields', 'manage_options', 'mf_settings', array('mf_settings','main') );
  }

  //Administration page
  add_action('admin_menu','mf_admin');
  function mf_admin() {
    global $mf_domain;

    add_menu_page('Magic Fields','Magic Fields','activate_plugins','mf_dispatcher','mf_dispatcher',MF_BASENAME.'/images/wand-hat.png');

    add_submenu_page('mf_dispatcher', __('import',$mf_domain), __('Import',$mf_domain), 'edit_pages','mf_dispatcher&mf_section=mf_posttype&mf_action=import_form_post_type', 'mf_dispatcher');
  }

  add_action( 'admin_menu', 'hide_panels' );
  function hide_panels() {
    //hidding the post or page panel
    //accord with the settings

    if( mf_settings::get('hide_post_panel') ) {
      mf_admin::mf_unregister_post_type('post');
    }

    if( mf_settings::get('hide_page_panel') ) {
      mf_admin::mf_unregister_post_type('page');
    }

  }

  //Adding metaboxes into the  pages for create posts
  //Also adding code for save this data
  add_action( 'add_meta_boxes', 'mf_add_meta_boxes');
  function mf_add_meta_boxes() {

  }

  /**
   * Magic Fields dispatcher
   */
  function mf_dispatcher() {

    //is user loged?
    if ( !is_user_logged_in() ) {
      die;
    }

    //same capabilities for the menu
    if (!current_user_can('activate_plugins') ) {
      die;
    }

    $section = "mf_dashboard";
    $action = "main";

    //Section
    if( !empty( $_GET['mf_section'] ) ) {
      $section = urlencode($_GET['mf_section']);
    }

    //Action
    if( !empty( $_GET['mf_action'] ) ) {
      $action = urlencode( $_GET['mf_action'] );
    }

    //check only mf_section has prefix mf    
    if ( !(strpos($section, "mf_") === 0) ) {
      die;
    }

    //exist class
    if (!class_exists($section)) {
      die;
    }

    if (!method_exists($section,$action)) {
      die;
    }

    $tmp = new $section();
    $tmp->$action();
    //call_user_func( array( $section, $action ) );
  }



  /**
   * Init Hook
   */
  add_action( 'init', 'mf_init' );
  function mf_init() {
    //Sometimes is neccesary execute the mf_dispatcher function in the init hook
    //because we want use a custom headers or a redirect (wp_safe_redirect for eg)
    if(!empty($_GET['init']) &&  $_GET['init'] == "true" ) {
      mf_dispatcher();
    }
  }

  //Including javascripts files
  add_action( 'init', 'mf_add_js');
  function mf_add_js() {
    global $mf_domain;

    if( is_admin() ) { //this scripts only will be added on the admin area
      wp_enqueue_script( 'jquery.validate',MF_BASENAME.'js/third_party/jquery.validate.min.js', array( 'jquery' ) );
      wp_enqueue_script( 'jquery.metadata',MF_BASENAME.'js/third_party/jquery.metadata.js', array( 'jquery' ) );
      wp_enqueue_script( 'mf_admin',MF_BASENAME.'js/mf_admin.js', array( 'jquery.validate', 'jquery.metadata', 'jquery' ) );

      // add stringToSlug
      if( isset($_GET['mf_action']) && in_array($_GET['mf_action'],array('add_field','edit_field', 'add_post_type', 'add_group', 'add_custom_taxonomy') )  ){
        wp_enqueue_script( 'jquery.stringToSlug', MF_BASENAME.'js/third_party/jquery.stringToSlug.min.js', array('mf_admin') );
      }

			//and this scripts only will be added on the default categories
      if( !empty( $_GET['mf_action'] ) && $_GET['mf_action'] == "set_categories" ) {
        wp_enqueue_script( 'mf_set_categories', MF_BASENAME.'js/mf_set_categories.js', array('mf_admin') );
      }

      //and this scripts only will be added on the post types section
      if( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == "mf_posttype" ) {
        wp_enqueue_script( 'mf_posttype', MF_BASENAME.'js/mf_posttypes.js', array('mf_admin') );
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
      }

			if( !empty( $_GET['page'] ) && $_GET['page'] == "mf_dispatcher" ) {
				wp_enqueue_script('thickbox');
				wp_enqueue_style('thickbox');
      }

      //and this scripts only will be added on the custom taxonomy section
      if( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == "mf_custom_taxonomy" ) {
        wp_enqueue_script( 'mf_taxonomy', MF_BASENAME.'js/mf_taxonomy.js', array('mf_admin') );
      }

      //Adding the files for the sort feature of the custom fields
      if( ( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == 'mf_custom_fields' ) &&
          ( !empty( $_GET['mf_action'] ) && $_GET['mf_action'] == 'fields_list' ) ) {
        wp_enqueue_script( 'mf_sortable_fields', MF_BASENAME.'js/mf_posttypes_sortable.js', array( 'jquery-ui-sortable' ) );
      }

      // scripts needed for the custom groups
      if( ( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == 'mf_custom_group' ) ){
        wp_enqueue_script( 'mf_custom_group', MF_BASENAME.'js/mf_custom_group.js', array('mf_admin') );
      }

      //Adding Css files for the post-new.php section (where is created a new post in wp)
      if( strstr( $_SERVER['REQUEST_URI'], 'post-new.php' ) !== FALSE  || strstr( $_SERVER['REQUEST_URI'],  'wp-admin/post.php') !== FALSE ) {
        /* Load JS and CSS for post page */
        $css_js = new mf_post();
        $css_js->load_js_css_base();
        $css_js->load_js_css_fields();
        $css_js->general_option_multiline();
				$css_js->set_categories();

      }
    }
  }

  add_action('wp_ajax_mf_call','mf_ajax_call'); // does this have any meaning?
  /* estara sera la funcion principal de llamadas js de MF*/
  function mf_ajax_call(){
    $call = new mf_ajax_call();
    $call->resolve($_POST);
  }

  add_filter('attachment_fields_to_edit', 'charge_link_after_upload_image', 10, 2);
  function charge_link_after_upload_image($fields){
    $wp_version = floatval(get_bloginfo('version'));


    if(
        $wp_version < 3.5 ||
        (( isset($_REQUEST['fetch']) && $_REQUEST['fetch'] ) ||
        ( isset($_REQUEST['tab']) && $_REQUEST['tab'] == 'library' ))
      ){
    printf("
      <script type=\"text/javascript\">
      //<![CDATA[
        load_link_in_media_upload();
      //]]>
      </script>");
    }
      return $fields;
  }

}else{
  /* load front-end functions */
  require_once( 'mf_front_end.php' );
}


add_filter('plugin_action_links', 'mf_action_links', 10, 2);

// output a settings a link on the plugins page
function mf_action_links($links, $file){
	//Static so we don't call plugin_basename on every plugin row.
	static $this_plugin;
  global $mf_domain;
	if (!$this_plugin) $this_plugin = plugin_basename(dirname(__FILE__).'/main.php');

	if ($file == $this_plugin){
		$settings_link = '<a href="options-general.php?page=mf_settings">' . __('Settings', $mf_domain) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}


/**
 *  This hack give a custom post type the hability
 *  to choose a custom template
 */
add_action('template_redirect','mf_change_template');
function mf_change_template() {
  global $post;

  // Check global post
  if ( empty( $post ) ) {
    return;
  }


  // Process feeds and trackbacks even if not using themes.
  if ( is_robots() ) :
    do_action('do_robots');
    return;
  elseif ( is_feed() ) :
    do_feed();
    return;
  elseif ( is_trackback() ) :
    include( ABSPATH . 'wp-trackback.php' );
    return;
  endif;



  // Check if the post has a special template
  $template = get_post_meta($post->ID, '_wp_mf_page_template', true);

  if (!$template || $template == 'default') {
    return;
  }

  $template = TEMPLATEPATH.'/'.$template;

  if ( $template = apply_filters( 'template_include', $template ) ) {
    include($template);
    die();
  }
  return;
}
