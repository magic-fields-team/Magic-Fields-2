<?php 
/** 
 * This file content the routines for install/activate  uninstall/deactivate Magic Fields
 */
class mf_install { 

  function install () {
    global $wpdb;
    
    require_once(ABSPATH.'wp-admin/includes/upgrade.php');

		// Get collation info
		$charset_collate = "";
		if ( ! empty($wpdb->charset) )
			$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
		if ( ! empty($wpdb->collate) )
			$charset_collate .= " COLLATE $wpdb->collate";

    //checking if the table is already installed

    if($wpdb->get_var( sprintf("SHOW tables LIKE '%s'",MF_TABLE_POSTTYPES) ) != MF_TABLE_POSTTYPES) {
      $sql = "CREATE TABLE ".MF_TABLE_POSTTYPES. " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1,
        PRIMARY KEY id (id) ) $charset_collate
      ";
      dbDelta($sql);
    }

    // Table custom taxonomy
    if($wpdb->get_var( sprintf("SHOW tables LIKE '%s'",MF_TABLE_CUSTOM_TAXONOMY) ) != MF_TABLE_CUSTOM_TAXONOMY) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_TAXONOMY. " (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type varchar(20) NOT NULL,
        name varchar(50) NOT NULL,
        description text,
        arguments text,
        active tinyint(1) DEFAULT 1,
        PRIMARY KEY id (id) ) $charset_collate
      ";
      dbDelta($sql);
    }
    
    // Table custom fields
    if($wpdb->get_var( sprintf("SHOW tables LIKE '%s'",MF_TABLE_CUSTOM_FIELDS) ) != MF_TABLE_CUSTOM_FIELDS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_FIELDS. " (
        id int(19) NOT NULL AUTO_INCREMENT,
        name varchar(150) NOT NULL,
        label varchar(150) NOT NULL,
        description text,
        post_type varchar(120) NOT NULL,
        custom_group_id int(19) NOT NULL,
        type varchar(100) NOT NULL,
        required_field tinyint(1),
        display_order mediumint(9) DEFAULT 0, 
        duplicated tinyint(1),
        active tinyint(1) DEFAULT 1,
        options text,
        PRIMARY KEY id (id) ) $charset_collate
      ";
      dbDelta($sql);
    }

    // Table custom groups
    if($wpdb->get_var( sprintf("SHOW tables LIKE '%s'",MF_TABLE_CUSTOM_GROUPS) ) != MF_TABLE_CUSTOM_GROUPS) {
      $sql = "CREATE TABLE ".MF_TABLE_CUSTOM_GROUPS. " (
        id integer NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        label varchar(255) NOT NULL,
        post_type varchar(255) NOT NULL,
        duplicated tinyint(1) DEFAULT 0,
        expanded tinyint(1) DEFAULT 0,
        PRIMARY KEY id (id) ) $charset_collate
      ";
      dbDelta($sql);

    }

    // Table MF Post Meta
    if( $wpdb->get_var( sprintf("SHOW tables LIKE '%s'",MF_TABLE_POST_META) ) != MF_TABLE_POST_META ) {
      $sql = "CREATE TABLE ".MF_TABLE_POST_META." ( 
        meta_id INT NOT NULL, 
        field_name VARCHAR(255) NOT NULL, 
        field_count INT NOT NULL,  
        group_count  INT NOT NULL, 
        post_id INT NOT NULL,
        PRIMARY KEY meta_id (meta_id),
        INDEX idx_post_field (post_id, meta_id) ) $charset_collate
				";

      dbDelta($sql);
    }
    
    if ( get_option( MF_DB_VERSION_KEY, FALSE ) === FALSE ) update_option(MF_DB_VERSION_KEY, MF_DB_VERSION);

    if (get_option(MF_DB_VERSION_KEY) < MF_DB_VERSION){
      self::upgrade();
      update_option(MF_DB_VERSION_KEY, MF_DB_VERSION);
    }
    
    
  }
  
  public function upgrade(){  
    global $wpdb;

    $db_version = get_option(MF_DB_VERSION_KEY); 

    if( $db_version < 2 ) {
      $sql = "ALTER TABLE ".MF_TABLE_CUSTOM_FIELDS. " CHANGE COLUMN requiered_field required_field tinyint(1)";
      $wpdb->query( $sql );
    }
    if ($db_version < 3) {
      //add index for mf post meta
      $sql = "ALTER TABLE ".MF_TABLE_POST_META. " ADD INDEX idx_post_field (post_id, meta_id)";
      $wpdb->query( $sql );
    }

    update_option(MF_DB_VERSION_KEY, MF_DB_VERSION);
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

  //delete cache
  public function clear_cache(){
    if (is_dir(MF_CACHE_DIR)) {
      if ($dh = opendir(MF_CACHE_DIR)) {
        while (($file = readdir($dh)) !== false) {
          if(!is_dir($file) && !in_array($file,array('.','..','.DS_Store') ) ){
            @unlink(MF_CACHE_DIR.$file);
          }
        }
        closedir($dh);
      }
    }
  }
  
  public function delete_files(){
    if (is_dir(MF_FILES_DIR)) {
      if ($dh = opendir(MF_FILES_DIR)) {
        while (($file = readdir($dh)) !== false) {
          if(!is_dir($file) && !in_array($file,array('.','..','.DS_Store') ) ){
            @unlink(MF_FILES_DIR.$file);
          }
        }
        closedir($dh);
      }
    }
  }
  
  //unistall MF (delete thumbs, tables and settings)
  public function uninstall(){
    global $wpdb;

    self::clear_cache();
    self::delete_files();
    delete_option(MF_SETTINGS_KEY);
    //DB version
    delete_option(MF_DB_VERSION_KEY);

    $sql = "DELETE a.* FROM $wpdb->postmeta AS a, ".MF_TABLE_POST_META." AS b WHERE b.meta_id = a.meta_id";
    $wpdb->query($sql);

    $sql = "DROP TABLE " . MF_TABLE_POSTTYPES;
    $wpdb->query($sql);
    
    $sql = "DROP TABLE " . MF_TABLE_CUSTOM_TAXONOMY;
    $wpdb->query($sql);
    
    $sql = "DROP TABLE " . MF_TABLE_CUSTOM_FIELDS;
    $wpdb->query($sql);

    $sql = "DROP TABLE " . MF_TABLE_CUSTOM_GROUPS;
    $wpdb->query($sql);

    $sql = "DROP TABLE " . MF_TABLE_POST_META;
    $wpdb->query($sql);
    
    $current = get_option('active_plugins');
    $plugin = plugin_basename(MF_PATH.'/main.php');
    array_splice($current, array_search( $plugin, $current), 1 );
    do_action('deactivate_' . trim( $plugin ));
    update_option('active_plugins', $current);

    wp_redirect('options-general.php');
  }

}
