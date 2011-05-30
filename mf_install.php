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
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1,
        PRIMARY KEY (id) ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
      ";

      dbDelta($sql);
    }

    // Table custom taxonomy
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_TAXONOMY}'") != MF_TABLE_CUSTOM_TAXONOMY) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_TAXONOMY. " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1,
        PRIMARY KEY (id) ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
      ";

      dbDelta($sql);
    }
    
    // Table custom fields
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_FIELDS}'") != MF_TABLE_CUSTOM_FIELDS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_FIELDS. " (
        id int(19) NOT NULL AUTO_INCREMENT,
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
        options text,
        PRIMARY KEY (id) ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
      ";

      dbDelta($sql);
    }

    // Table custom groups
    if($wpdb->get_var("SHOW tables LIKE '{MF_TABLE_CUSTOM_GROUPS}'") != MF_TABLE_CUSTOM_GROUPS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_GROUPS. " (
        id integer NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        label varchar(255) NOT NULL,
        post_type varchar(255) NOT NULL,
        duplicated tinyint(1) DEFAULT 0,
        expanded tinyint(1) DEFAULT 0,
        PRIMARY KEY (id) ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
      ";

      dbDelta($sql);
    }

    // Table MF Post Meta
    if( $wpdb->get_var("SHOW tables LIKE '{MF_TABLE_POST_META}'") != MF_TABLE_POST_META ) {
      $sql = "CREATE TABLE ".MF_TABLE_POST_META." ( 
        meta_id INT NOT NULL, 
        field_name VARCHAR(255) NOT NULL, 
        field_count INT NOT NULL,  
        group_count  INT NOT NULL, 
        post_id INT NOT NULL
      );";

      dbDelta($sql);
    }
  }

  public function folders(){
    global $mf_domain;
    
    $dir_list = "";
    $dir_list2 = "";

    wp_mkdir_p(MF_FILES_DIR);
    wp_mkdir_p(MF_CACHE_DIR);
    
    if (!is_dir(MF_CACHE_DIR)){
      $dir_list2.= "<li>".MF_CACHE_DIR . "</li>";
    }elseif (!is_writable(MF_CACHE_DIR)){
      $dir_list.= "<li>".MF_CACHE_DIR . "</li>";
    }
    
    if (!is_dir(MF_FILES_DIR)){
      $dir_list2.= "<li>".MF_FILES_DIR . "</li>";
    }elseif (!is_writable(MF_FILES_DIR)){
      $dir_list.= "<li>".MF_FILES_DIR . "</li>";
    }
    
    if ($dir_list2 != ""){
      echo "<div id='magic-fields-install-error-message' class='error'><p><strong>".__('Magic Fields is not ready yet.', $mf_domain)."</strong> ".__('must create the following folders (and must chmod 777):', $mf_domain)."</p><ul>";
      echo $dir_list2;
      echo "</ul></div>";
    }
    if ($dir_list != ""){
      echo "<div id='magic-fields-install-error-message-2' class='error'><p><strong>".__('Magic Fields is not ready yet.', $mf_domain)."</strong> ".__('The following folders must be writable (usually chmod 777 is neccesary):', $mf_domain)."</p><ul>";
      echo $dir_list;
      echo "</ul></div>";
    }

  }
}
