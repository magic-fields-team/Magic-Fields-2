<?php
/**
 * @todo make a good description of this class
 */
class mf_admin {

  public $name = 'mf_admin';
  
  function _get_url(  $section = 'mf_dashboard', $action = 'main', $vars = array( ) ){
    $url = home_url();

    //the admin area of Magic Fields always should pass through the dispatcher:
    $url .= '/wp-admin/admin.php?page=mf_dispatcher';

    //section
    $url .= '&mf_section='.$section;

    //action
    $url .= '&mf_action='.$action;

    if( !empty( $vars ) ) {
      foreach($vars as $param => $value) {
        $url .= '&'.$param.'='.$value;
      }
    }
    
    return $url;
  }
  
  /**
   * is a wrapper of wp_safe_redirect 
   */
  function mf_redirect( $section = 'mf_dashboard', $action = 'main', $vars = array( ) ) {
    
    $url = $this->_get_url(  $section , $action , $vars );
    wp_safe_redirect($url);
    exit;
  }

  /**
   * Display a friendly message of error
   *
   * @param string message the messages to will be displayed
   * @param mixed  can be a array or a string  
   * @param string the css class to will be added into the message (e.j  'error','info','ok','alert')
   */

  function mf_flash( $message = 'Return dashoard', $url = array('section' => 'mf_dashboard', 'action' => 'main', 'vars' => ''), $type = 'error') { 
    if( is_array($url) ) {
      $url = $this->_get_url(  $url['section'] , $url['action'] , $url['vars'] );
    } 
    
    printf(' <div class="wrap"><div id="message" class="%s below-h2"><p><a href="%s">%s</a></p></div> </div>', $type, $url, $message );
    
    //
    if(!WP_DEBUG){
      printf('<script type="text/javascript">
        function js_mf_redirect(){
           window.location = "%s";
        }
        setTimeout(js_mf_redirect,5000);
        </script>',$url);
    }
    
    die;
  }

  /**
   * return all post types
   *
   *  This function is a wrapper of  wordpress's get_post_types function 
   *  here be  filter  a non-related post types of magic fields
   * 
   *  @return array 
   */
  public function mf_get_post_types( $args = array('public' => true), $output = 'object', $operator = 'and' ){
    global $wpdb;
    
    $post_types = get_post_types( $args, $output, $operator );

    foreach ( $post_types as $key => $type ) {
      if( $output == 'names' ) {
        if( $type == 'attachment' ) {
          unset($post_types[$key]);
        }
      } else if ($output == 'object' ) {
        unset($post_types['attachment']);
      }
    }

    return $post_types;
  }

   /**
   * return all custom_taxonomy
   */
  public function get_custom_taxonomies(){
    global $wpdb;
    
    $query = sprintf('SELECT * FROM %s ORDER BY id',MF_TABLE_CUSTOM_TAXONOMY);
    $custom_taxonomies = $wpdb->get_results( $query, ARRAY_A );
    return $custom_taxonomies;
  }

  /**
   * return all gruops of post type
   */
  public function get_groups_by_post_type($post_type){
    global $wpdb;

    $query = sprintf("SELECT * FROM %s WHERE post_type = '%s' ORDER BY id",MF_TABLE_CUSTOM_GROUPS,$post_type);
    $groups = $wpdb->get_results( $query, ARRAY_A);
    return $groups;
  }

  /**
   * retun a group
   */
  public function get_group($group_id){
    global $wpdb;

    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_CUSTOM_GROUPS." WHERE id = %d", array( $group_id ) );
    $group = $wpdb->get_row( $query, ARRAY_A);
    return $group;
  }

  /**
   * Return id of default group for post type 
   */
  public function get_default_custom_group($post_type){
    global $wpdb;
    
    $query = sprintf("SELECT id FROM %s WHERE name = '__default' AND post_type = '%s' ",MF_TABLE_CUSTOM_GROUPS,$post_type);
    $group = $wpdb->get_col($query);

    //exists default group?
    if(!$group){
      $wpdb->insert(
        MF_TABLE_CUSTOM_GROUPS,
        array(
          'name' => '__default',
          'label' => 'Magic Fields',
          'post_type' => $post_type
        ),
        array(
          '%s', 
          '%s',
          '%s'
        )
      );
      $custom_group_id = $wpdb->insert_id;
    }else{
      $custom_group_id = $group[0];
    }
 
    return $custom_group_id;

  }

  /** 
   * Return True if the group has at least one custom field
   * 
   * return @bool
   **/
  public static function group_has_fields($group_id) {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT COUNT(1) FROM ".MF_TABLE_CUSTOM_FIELDS. " WHERE custom_group_id = %d",$group_id);
  
    return $wpdb->get_var( $sql ) > 0;
  }


   /**
   * return all fields of group
   */
  public function get_custom_fields_by_group($id){
    global $wpdb;

    $query = sprintf("SELECT * FROM %s WHERE custom_group_id = '%s' ORDER BY display_order",MF_TABLE_CUSTOM_FIELDS,$id);
    $fields = $wpdb->get_results( $query, ARRAY_A);
    return $fields;
  }

  /**
   * return all the fields of post type
   */
  public static function get_custom_fields_by_post_type($post_type = "post") {
    global $wpdb;

    $query = "SELECT * FROM ".MF_TABLE_CUSTOM_FIELDS. " WHERE post_type = '".$post_type."'";

    $fields = $wpdb->get_results($query, ARRAY_A);
    return $fields;
  }

  /**
   * return a group
   */
  public function get_custom_field($custom_field_id){
    global $wpdb;

    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_CUSTOM_FIELDS." WHERE id = %d", array( $custom_field_id ) );
    $field = $wpdb->get_row( $query, ARRAY_A);
    return $field;
  }
}
