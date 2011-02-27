<?php 
/** 
 * This file content the routines for install/activate  uninstall/deactivate Magic Fields
 */
class mf_install { 

  function install () {
    global $wpdb;
  
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

      require_once(ABSPATH.'wp-admin/includes/upgrade.php');
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

      require_once(ABSPATH.'wp-admin/includes/upgrade.php');
      dbDelta($sql);
    }
  }
}
