<?php 
/** 
 * This file content the routines for install/activate  uninstall/deactivate Magic Fields
 */
class mf_install { 

  function install () {
    global $wpdb;

    require_once(ABSPATH.'wp-admin/includes/upgrade.php');
  
    $table_name = $wpdb->prefix."mf_posttypes";
    
    //checking if the table is already installed
    if($wpdb->get_var("SHOW tables LIKE '{$table_name}'") != $table_name) {
      $sql = "CREATE TABLE ".$table_name. " (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1
      );";

      dbDelta($sql);
    }

    // Table custom taxonomy
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_TAXONOMY}'") != MF_TABLE_CUSTOM_TAXONOMY) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_TAXONOMY. " (
        id mediumint(9) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1
      );";

      dbDelta($sql);
    }
    
    // Table custom fields
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_FIELDS}'") != MF_TABLE_CUSTOM_FIELDS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_FIELDS. " (
        id int(19) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(150) NOT NULL,
        label varchar(150) NOT NULL,
        description text,
        post_type varchar(120) NOT NULL,
        custom_group_id int(19) NOT NULL,
        type varchar(100) NOT NULL,
        requiered_field tinyint(1),
        display_order mediumint(9) DEFAULT 0, 
        duplicated tinyint(1),
        active tinyint(1) DEFAULT 1,
        options text
      );";

      dbDelta($sql);
    }

    // Table custom groups
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_GROUPS}'") != MF_TABLE_CUSTOM_GROUPS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_GROUPS. " (
        id int(19) NOT NULL AUTO_INCREMENT PRIMARY KEY,
        name varchar(255) NOT NULL,
        label varchar(255) NOT NULL,
        post_type varchar(255) NOT NULL,
        duplicated tinyint(1) DEFAULT 0,
        expanded tinyint(1) DEFAULT 0
      );";

      dbDelta($sql);
    }

    // Table MF Post Meta
    if( $wpdb->get_var("SHOW tables LIKE '{MF_TABLE_POST_META}'") != MF_TABLE_POST_META ) {
      $sql = "CREATE TABLE ".MF_TABLE_POST_META." ( 
        meta_id INT NOT NULL, 
        field_id INT NOT NULL, 
        field_count INT NOT NULL,  
        group_id INT NOT NULL, 
        group_count  INT NOT NULL, 
        post_id INT NOT NULL
      );";

      dbDelta($sql);
    }
  }
}
