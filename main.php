<?php 
/*
Plugin Name: Magic Fields
Plugin URI: http://magicfields.org
Description: Create custom fields for your post types 
Version: 2.0
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
global $mf_domain;
$mf_domain = 'magic_fields';	

/**
 * Constants
 */ 
require_once( 'mf_constants.php' );

//auto loading files
function __autoload( $name ) {
  //main files
  if( file_exists( MF_PATH.'/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/'.$name.'.php' );
  }

  //admin files
  if( file_exists( MF_PATH.'/admin/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/admin/'.$name.'.php' );
  }
}


/**
 * Activation and Deactivation
 */
register_activation_hook( __FILE__, array('mf_install', 'install' ) ); 


if( is_admin() ) {
  // CSS Files
  wp_register_style( 'mf_admin_css',MF_BASENAME.'css/mf_admin.css' );
  wp_enqueue_style( 'mf_admin_css' );  

  // Settings Page
  add_action( 'admin_menu', 'mf_menu' );
  function mf_menu( ) {
      global $mf_domain;
      add_options_page(__('Magic Fields Settings', $mf_domain ), 'Magic Fields', 'manage_options', 'mf_settings', array('mf_settings','main') );
  }

  //Administration page
  add_action('admin_menu','mf_admin');
  function mf_admin() {
    add_menu_page('Magic Fields','Magic Fields','activate_plugins','mf_dispatcher','mf_dispatcher',MF_BASENAME.'/images/wand-hat.png');
  }

  /**
   * Magic Fields dispatcher
   */ 
  function mf_dispatcher() {
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
    if( is_admin() ) { //this scripts only will be added on the admin area
      wp_enqueue_script( 'jquery.validate',MF_BASENAME.'js/third_party/jquery.validate.min.js', array( 'jquery' ) );
      wp_enqueue_script( 'jquery.metadata',MF_BASENAME.'js/third_party/jquery.metadata.js', array( 'jquery' ) );
      wp_enqueue_script( 'mf_admin',MF_BASENAME.'js/mf_admin.js', array( 'jquery.validate', 'jquery.metadata', 'jquery' ) );

      //and this scripts only will be added on the post types section
      if( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == "mf_posttype" ) {
        wp_enqueue_script( 'mf_posttype', MF_BASENAME.'js/mf_posttypes.js', array('mf_admin') );
      }
    }
  }
}

//hook into the init action and call mf_init_taxonomies when it fires
add_action( 'init', 'mf_init_taxonomies', 0 );
function mf_init_taxonomies(){
  require_once( MF_PATH.'/admin/mf_admin.php' );
  $taxonomies = mf_admin::get_custom_taxonomies();
  foreach($taxonomies as $tax){
    $tax = json_decode($tax['arguments'],true);
    $tax_name = $tax['core']['name'];
    $tax_option = $tax['option'];
    $tax_option['labels'] = $tax['label'];
    $tax_in = $tax['post_types'];

    if($tax_option['rewrite'] && $tax_option['rewrite_slug'])
      $tax_option['rewrite'] = array( 'slug' => $tax_option['rewrite_slug'] );

    register_taxonomy($tax_name,$tax_in, $tax_option);
  }
}
  
//custom taxonomy load
add_action( 'init', 'mf_init_post_type', 0 );
function mf_init_post_type(){
  require_once( MF_PATH.'/admin/mf_admin.php' );
  $post_types = mf_admin::get_post_types();
  foreach($post_types as $pt){ 
    $pt = json_decode($pt['arguments'],true);

    $pt_name = $pt['core']['type'];
    $pt_option = $pt['option'];
    $pt_option['query_var'] = ($pt_option['query_var']) ? true : false;

    $pt_option['labels'] = $pt['label'];

    if( isset($pt['support']) ){
      foreach($pt['support'] as $k => $v){
        $pt_option['supports'][] = $k;          
      }
    }
    
    if($pt_option['rewrite'] && $pt_option['rewrite_slug'])
      $pt_option['rewrite'] = array( 'slug' => $pt_option['rewrite_slug'] );
        
    unset($pt_option['rewrite_slug']);
    register_post_type($pt_name,$pt_option);
  }
}
  
  /** 
  * aux function 
  **/
  if (!function_exists('pr')) {
  	function pr($data){
  		echo "<pre>";
  		print_r($data);
  		echo "</pre>";
  	}
  }
  

