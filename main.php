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
global $mf_domain,$mf_pt_register;
$mf_domain = 'magic_fields';
$mf_pt_register = array();

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

  //field types
  if( file_exists( MF_PATH.'/field_types/'.$name.'/'.$name.'.php' ) ) {
    require_once( MF_PATH.'/field_types/'.$name.'/'.$name.'.php'); 
  }
}


/**
 * Activation and Deactivation
 */
register_activation_hook( __FILE__, array('mf_install', 'install' ) ); 


if( is_admin() ) {
  //add common function
  require_once(MF_PATH.'/mf_common.php');

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

  //Adding metaboxes into the  pages for create posts 
  //Also adding code for save this data
  add_action( 'add_meta_boxes', 'mf_add_meta_boxes');
  function mf_add_meta_boxes() {
     
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
    
      //Adding the files for the sort feature of the custom fields
      if( ( !empty( $_GET['mf_section'] ) && $_GET['mf_section'] == 'mf_custom_fields' ) &&
          ( !empty( $_GET['mf_action'] ) && $_GET['mf_action'] == 'fields_list' ) ) {
        wp_enqueue_script( 'mf_sortable_fields', MF_BASENAME.'js/mf_posttypes_sortable.js', array( 'jquery-ui-sortable' ) );
  
      }
    }
  }
  
  //catch call ajax for new field
  add_action( 'wp_ajax_load_field_type', 'load_field_type_option' );
  function load_field_type_option(){
    if( isset($_POST['field_type']) && ($_POST['field_type'] != NULL) ){
      $name = sprintf('%s_field',$_POST['field_type']);
      $mf_field = new $name();
      $mf_field->get_options();
    }
    die;
  }

  //catch call ajax for sorter the fields
  add_action( 'wp_ajax_mf_sort_fields', 'mf_sort_fields' );
  function mf_sort_fields() {
    if ( !empty( $_POST['order'] ) && !empty( $_POST['group_id'] ) ) {
      $order = $_POST['order'];
      $order = split(',',$order);
      array_walk( $order, create_function( '&$v,$k', '$v =  str_replace("order_","",$v);' ));
    
      if( $thing =  mf_custom_fields::save_order_field( $_POST['group_id'], $order ) ) {
        print "1";
        die;
      }
      print "0"; //error!
    }
    die;
  }
}

//Register Post Types and Custom Taxonomies
$mf_register = new mf_register();

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

