<?php
/**
 * @todo make a good description of this class
 */
class mf_admin {

  public $name = 'mf_admin';

  function _get_url(  $section = 'mf_dashboard', $action = 'main', $vars = array( ) ){
    $url = site_url();

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
   * get a specific custom_taxonomy
   */
  public function get_custom_taxonomy_by_type($type_taxonomy){
    global $wpdb;

    $query = sprintf(
      "SELECT * FROM %s WHERE type = '%s'",
      MF_TABLE_CUSTOM_TAXONOMY,
      $type_taxonomy
    );

    $custom_taxonomy = $wpdb->get_row( $query, ARRAY_A );
    if($custom_taxonomy){
      $id = $custom_taxonomy['id'];
      $custom_taxonomy = unserialize($custom_taxonomy['arguments'],true);
      $custom_taxonomy['core']['id'] = $id;
      return $custom_taxonomy;
    }
    return false;
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
   * retun a group
   */
  public function get_group_by_name($name_group,$post_type){
    global $wpdb;

    $query = sprintf(
      'SELECT * FROM %s WHERE name = "%s" AND post_type = "%s" ',
      MF_TABLE_CUSTOM_GROUPS,
      $name_group,
      $post_type
    );
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
   * return a unique type the fields of post type
   */
  public static function get_unique_custom_fields_by_post_type($post_type = "post") {
    global $wpdb;

    $query = "SELECT DISTINCT(type) FROM ".MF_TABLE_CUSTOM_FIELDS. " WHERE post_type = '".$post_type."'";

    $fields = $wpdb->get_col($query);
    return $fields;
  }

  /**
   * return a field
   */
  public function get_custom_field($custom_field_id){
    global $wpdb;

    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_CUSTOM_FIELDS." WHERE id = %d", array( $custom_field_id ) );
    $field = $wpdb->get_row( $query, ARRAY_A);
    return $field;
  }

  /**
   * return a field by name and post type
   */
  public function get_custom_field_by_name($name_custom_field, $post_type){
    global $wpdb;

    $query = sprintf(
      'SELECT * FROM %s WHERE name = "%s" AND post_type = "%s" ',
      MF_TABLE_CUSTOM_FIELDS,
      $name_custom_field,
      $post_type
    );
    $field = $wpdb->get_row( $query, ARRAY_A);
    return $field;
  }
  
  public function mf_resolve_linebreaks($data = NULL){
    $data = preg_replace(array("/\r\n/","/\r/","/\n/"),"\\n",$data);
    return $data;
  }

  public function js_css_post($post_type){

    $fields = $this->get_unique_custom_fields_by_post_type($post_type);

    foreach($fields as $field) {
      //todo: Este método debería también de buscar en los paths donde los usuarios ponen sus custom fields
      $type = $field."_field";
      $type = new $type();
      $properties = $type->get_properties();

      if ( $properties['js'] ) {
        wp_enqueue_script(
          'mf_field_'.$field,
          MF_BASENAME.'field_types/'.$field.'_field/'.$field.'_field.js',
          $properties['js_dependencies'],
          null,
          true
        );

        /* idear forma por si se necesita mas de dos js*/
        if( isset($properties['js_internal']) ){
          wp_enqueue_script(
            'mf_field_'. preg_replace('/\./','_',$properties['js_internal']),
            MF_BASENAME.'field_types/'.$field.'_field/'.$properties['js_internal'],
            $properties['js_internal_dependencies'],
            null,
            true
          );
        }
      }

      if ( $properties['css'] ) {
        wp_enqueue_style(
          'mf_field_'.$field,
          MF_BASENAME.'field_types/'.$field.'_field/'.$field.'_field.css'
        );
      }

      if ( !empty($properties['css_dependencies'] )) {
        foreach($properties['css_dependencies'] as $css_script) {
          wp_enqueue_style($css_script);
        }
      }

      /* load css internal */
      if(isset($properties['css_internal'])){
        wp_enqueue_style(
          'mf_field_'.preg_replace('/\./','_',$properties['css_internal']),
          MF_BASENAME.'field_types/'.$field.'_field/'.$properties['css_internal']
        );
      }
    }
  }

  public function import($file_path,$overwrite){

    $data = unserialize(file_get_contents($file_path));

    $name       = $data['name'];
    $post_type  = $data['post_type'];
    $groups     = $data['groups'];
    $taxonomies = $data['taxonomy'];

    /* variables of debugin
    $name = $data['name'].'bla';
    $overwrite = 1;
    $post_type['core']['type'] = $name;
    */

    /* begin register post type */
    if( in_array($name,array('post','page')) ){
      if(!$overwrite){
        $i = 2;
        $temp_name = $name . "_1";
        while ($this->get_post_type($temp_name)){
          $temp_name = $name. "_" . $i++;
        }
        $name = $temp_name;
        $post_type['core']['type'] = $temp_name;
        $post_type['core']['label'] = $temp_name;
        $post_type['label']['name'] = $temp_name;
        $post_type['core']['id'] = NULL;

        /*save post type*/
        $this->new_posttype($post_type);
      }
    }else{
      if($overwrite){
        $tmp = $this->get_post_type($name);
        if($tmp){
          $post_type['core']['id'] = $tmp['core']['id'];
          $this->update_post_type($post_type);
        }else{
          $post_type['core']['id'] = NULL;
          $this->new_posttype($post_type);
        }
      }else{
        if($this->get_post_type($name)){
          $i = 2;
          $temp_name = $name . "_1";
          while ($this->get_post_type($temp_name)){
            $temp_name = $name. "_" . $i++;
          }
          $name = $temp_name;
          $post_type['core']['type'] = $temp_name;
          $post_type['core']['label'] = $temp_name;
          $post_type['label']['name'] = $temp_name;
          $post_type['label']['menu_name'] = $temp_name;
        }
        
        $post_type['core']['id'] = NULL;

        $this->new_posttype($post_type);
      }
    }
    /* end register post type */

    /* begin register custom groups and custom fields */
    foreach($groups as $group){
      $fields = $group['fields'];
      unset($group['fields']);
      $group['post_type'] = $name;

      if($overwrite){
        $tmp_group = $this->get_group_by_name($group['name'],$name);
        if($tmp_group){
          $group_id = $tmp_group['id'];
          $group['id'] = $group_id;
          $group['duplicate'] = $group['duplicated'];
          unset($tmp_group);
          $tmp_group['core'] = $group;
          $this->update_custom_group($tmp_group);
          
          foreach($fields as $field){
            $tmp_field = $this->get_custom_field_by_name($field['name'],$name);

            if($tmp_field){
              $field['id'] = $tmp_field['id'];
              $field['duplicate'] = $field['duplicated'];
              unset($tmp_field);
              $tmp_field['core'] = $field;
              $tmp_field['option'] = unserialize( $field['options'] );
              $this->update_custom_field($tmp_field);
            }else{
              $field['duplicate'] = $field['duplicated'];
              unset($tmp_field);
              $tmp_field['core'] = $field;
              $tmp_field['option'] = unserialize( $field['options'] );
              $this->new_custom_field($tmp_field);
            }
         
          }

        }else{
          $group['duplicate'] = $group['duplicated'];
          $tmp_group['core'] = $group;
          $group_id = $this->new_custom_group($tmp_group);
          foreach($fields as $field){
            $field['custom_group_id'] = $group_id;
            $field['duplicate'] = $field['duplicated'];
            $tmp_field['core'] = $field;
            $tmp_field['option'] = unserialize( $field['options'] );
            $this->new_custom_field($tmp_field);
          }
        }
      }else{
        $group['duplicate'] = $group['duplicated'];
        $group['post_type'] = $name;
        $tmp_group['core'] = $group;
        $group_id = $this->new_custom_group($tmp_group);
        foreach($fields as $field){
          $field['post_type'] = $name;
          $field['custom_group_id'] = $group_id;
          $field['duplicate'] = $field['duplicated'];
          $tmp_field['core'] = $field;
          $tmp_field['option'] = unserialize( $field['options'] );
          $this->new_custom_field($tmp_field);
        }
      }

    }
    /* end register custom groups and custom fields */

    /* begin register custom taxonomies */
    foreach($taxonomies as $taxonomy){
      if($overwrite){
        $t_type = $taxonomy['core']['type'];
        
        $tmp_taxonomy = $this->get_custom_taxonomy_by_type($t_type);

        if($tmp_taxonomy){
          $taxonomy['core']['id'] = $tmp_taxonomy['core']['id'];
          $taxonomy['post_types'] = array($name);
          $this->update_custom_taxonomy($taxonomy);
        }else{
          $taxonomy['core']['id'] = NULL;
          $taxonomy['post_types'] = array($name);
          $this->new_custom_taxonomy($taxonomy);
        }

      }else{
        $t_type = $taxonomy['core']['type'];
        
        $tmp_taxonomy = $this->get_custom_taxonomy_by_type($t_type);
        
        if($tmp_taxonomy){
          $i = 2;
          $temp_name = $t_type . "_1";
          while ($this->get_custom_taxonomy_by_type($temp_name)){
            $temp_name = $t_type. "_" . $i++;
          }
          $t_type = $temp_name;
          $taxonomy['core']['id'] = NULL;
          $taxonomy['core']['type'] = $t_type;
          $taxonomy['core']['name'] = $t_type;
          $taxonomy['post_types'] = array($name);
          $this->new_custom_taxonomy($taxonomy);

        }else{
          $taxonomy['core']['id'] = NULL;
          $taxonomy['post_types'] = array($name);
          $this->new_custom_taxonomy($taxonomy);
        }
      }
      
    }
    /* end register custom taxonomies */

  }

  /**
   * Escape data before serializing
   */
  function escape_data(&$value){
    // quick fix for ' character
    /** @todo have a proper function escaping all these */
    if(is_string($value)){
        $value = stripslashes($value);
        $value = preg_replace('/\'/','´', $value);
        $value = addslashes($value);
    }
  }

  /* function save and update for post type */

  /**
   * Save a new post
   */
  public function new_posttype($data){
    global $wpdb;

    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    $sql = $wpdb->prepare(
      "INSERT INTO " . MF_TABLE_POSTTYPES .
      " (type, name, description, arguments, active)" .
      " values" .
      " (%s, %s, %s, %s, %d)",
      $data['core']['type'],
      $data['core']['label'],
      $data['core']['description'],
      serialize($data),
      1
    );

    $wpdb->query($sql);
    $postTypeId = $wpdb->insert_id;
    return $postTypeId;
  }

  /**
   * Update Post type data
   */
  public function update_post_type($data){
    global $wpdb;

    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));
		
    $sql = $wpdb->prepare(
      "Update " . MF_TABLE_POSTTYPES .
      " SET type = %s, name = %s, description = %s, arguments = %s " .
      " WHERE id = %d",
      $data['core']['type'],
      $data['core']['label'],
      $data['core']['description'],
      serialize($data),
      $data['core']['id']
    );

    $wpdb->query($sql);
  }

  /* function for save and update custom groups */

  /**
   * Add a new custom group
   */
  public function new_custom_group($data){
    global $wpdb;
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));
   
    $sql = $wpdb->prepare(
      "INSERT INTO ". MF_TABLE_CUSTOM_GROUPS .
      " (name, label, post_type, duplicated, expanded) ".
      " VALUES (%s, %s, %s, %d, %d)",
      $data['core']['name'],
      $data['core']['label'],
      $data['core']['post_type'],
      $data['core']['duplicate'],
      1
    );
    $wpdb->query($sql);
    
    $postTypeId = $wpdb->insert_id;
    return $postTypeId;
  }

  /**
   * Update a custom group
   */
  public function update_custom_group($data){
    global $wpdb;

    //ToDo: falta sanitizar variables
    // podriamos crear un mettodo para hacerlo
    // la funcion podria pasarle como primer parametro los datos y como segundo un array con los campos que se va a sanitizar o si se quiere remplazar espacios por _ o quitar caracteres extraños
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    $sql = $wpdb->prepare(
      "UPDATE ". MF_TABLE_CUSTOM_GROUPS .
      " SET name = %s, label =%s, duplicated = %d, expanded = %d ".
      " WHERE id = %d",
      $data['core']['name'],
      $data['core']['label'],
      $data['core']['duplicate'],
      1,
      $data['core']['id']
    );
    
    $wpdb->query($sql);
  }

  /* funciton for save and update custom field */
  public function new_custom_field($data){
    global $wpdb;

    if( !isset($data['option']) ) $data['option'] = array();
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    //check group
    if(!$data['core']['custom_group_id']){
      $custom_group_id = $this->get_default_custom_group($data['core']['post_type']);
      $data['core']['custom_group_id'] = $custom_group_id;
    }

    $data['core']['name'] = str_replace(" ","_",$data['core']['name']);

    $sql = $wpdb->prepare(
      "INSERT INTO ". MF_TABLE_CUSTOM_FIELDS . 
      " (name, label, description, post_type, custom_group_id, type, required_field, duplicated, options) ".
      " VALUES (%s, %s, %s, %s, %d, %s, %d, %d, %s)",
      $data['core']['name'],
      $data['core']['label'],
      $data['core']['description'],
      $data['core']['post_type'],
      $data['core']['custom_group_id'],
      $data['core']['type'],
      $data['core']['required_field'],
      $data['core']['duplicate'],
      serialize($data['option'])
    );

    $wpdb->query($sql);
  }

  /**
   * Update a custom field
   */
  public function update_custom_field($data){
    global $wpdb;

    if( !isset($data['option']) ) $data['option'] = array();
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    //check group
    if(!$data['core']['custom_group_id']){
      $custom_group_id = $this->get_default_custom_group($data['core']['post_type']);
      $data['core']['custom_group_id'] = $custom_group_id;
    }

    $data['core']['name'] = str_replace(" ","_",$data['core']['name']);

    $sql = $wpdb->prepare(
     "UPDATE ". MF_TABLE_CUSTOM_FIELDS . 
     " SET name = %s, label = %s, description = %s, type = %s, required_field = %d, ".
     " duplicated = %d, options = %s ".
     " WHERE id = %d",
     $data['core']['name'],
     $data['core']['label'],
     $data['core']['description'],
     $data['core']['type'],
     $data['core']['required_field'],
     $data['core']['duplicate'],
     serialize($data['option']),
     $data['core']['id']
    );
    $wpdb->query($sql);
  }

  /* function for save and update custom taxonomies */
  
  /**
   * Save a new custom taxonomy
   */
  public function new_custom_taxonomy($data){
    global $wpdb;
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    $sql = $wpdb->prepare(
      "INSERT INTO " . MF_TABLE_CUSTOM_TAXONOMY .
      " (type, name, description, arguments, active)" .
      " values" .
      " (%s, %s, %s, %s, %d)",
      $data['core']['type'],
      $data['core']['name'],
      $data['core']['description'],
      serialize($data),
      1
    );

    $wpdb->query($sql); 
    $custom_taxonomy_id = $wpdb->insert_id;
    return $custom_taxonomy_id;
  }

  /**
   * Update a custom taxonomy
   */
  public function update_custom_taxonomy($data){
    global $wpdb;
    
    // escape all the strings
    array_walk_recursive($data, array($this, 'escape_data'));

    $sql = $wpdb->prepare(
      "Update " . MF_TABLE_CUSTOM_TAXONOMY .
      " SET type = %s, name = %s, description = %s, arguments = %s " .
      " WHERE id = %d",
      $data['core']['type'],
      $data['core']['name'],
      $data['core']['description'],
      serialize($data),
      $data['core']['id']
    );

    $wpdb->query($sql);
  }

  function mf_unregister_post_type( $post_type ) {
    /* Ideally we should just unset the post type from the array 
       but wordpress 3.2.1 this doesn't work */

    //global $wp_post_types;
    //if ( isset( $wp_post_types[ $post_type ] ) ) {
    // unset( $wp_post_types[ $post_type ] );
    // return true;
    //}

    /* So, we are only remove the item from the menu (this is not a 
       real unregister post_type but for at least we not will see 
       the post or page menu)
     */
    if( $post_type == "post" ) {
      remove_menu_page('edit.php');
      return true;
    }

    remove_menu_page('edit.php?post_type='.$post_type );
	  return true;
  }
}
