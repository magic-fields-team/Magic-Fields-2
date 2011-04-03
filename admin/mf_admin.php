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

  public function mf_form_select($data) {
    $id         = $data['id'];
    $label      = $data['label'];
    $name       = $data['name'];
    $options    = $data['options'];
    $value      = $data['value'];
    $add_empty  = $data['add_empty'];
    ?>
    <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
    <select name="<?php echo $name; ?>" id="<?php echo $id;?>">
      <?php if($add_empty):?>
        <option value=""></option>
      <?php endif;?>
      <?php if(!empty($options)):?>
        <?php foreach($options as $key => $field_name):
          $selected = (!empty($value) && $value == $key) ? "selected=selected" : "";
        ?>
          <option value="<?php print $key;?>" <?php print $selected; ?>><?php echo $field_name;?></option>
        <?php endforeach;?> 
      <?php endif;?>
    </select>
    <?php
  }

  public function mf_form_checkbox($data){
    $id = $data['id'];
    $label = $data['label'];
    $name = $data['name'];
    $check = ($data['value'])? 'checked="checked"' : '' ;
    $description = $data['description'];
  ?>
    <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>_" type="hidden" value="0">
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="checkbox" value="1" <?php echo $check; ?> >
    <p><?php echo $description; ?></p>
    <?php
  }

  public function mf_form_text( $data , $max = NULL ){
    $id = $data['id'];
    $label = $data['label'];
    $name = $data['name'];
    $value = ($data['value'])? sprintf('value="%s"',$data['value']) : '' ;
    $description = $data['description'];
    $size = ($max)? sprintf('value-size="%s"',$max) : '' ;
    $class = (isset($data['class']))? sprintf('class="%s"',$data['class']) : '';
    $rel = (isset($data['rel'])) ? sprintf('rel="%s"',$data['rel']): '';
    ?>
    <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="text" <?php echo $size; ?> <?php echo $value; ?> <?php echo $class; ?> <?php echo $rel; ?> >
    <p><?php echo $description; ?></p>
    <?php
  }

  public function mf_form_hidden($data){
    $id = $data['id'];
    $name = $data['name'];
    $value = ($data['value'])? sprintf('value = "%s"',$data['value']) : '';
    ?>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="hidden" <?php echo $value;?> >
    <?php
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
   * return all fields of group
   */
  public function get_custom_fields_by_group($id){
    global $wpdb;

    $query = sprintf("SELECT * FROM %s WHERE custom_group_id = '%s' ORDER BY display_order",MF_TABLE_CUSTOM_FIELDS,$id);
    $fields = $wpdb->get_results( $query, ARRAY_A);
    return $fields;
  }

  /**
   * retun a group
   */
  public function get_custom_field($custom_field_id){
    global $wpdb;

    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_CUSTOM_FIELDS." WHERE id = %d", array( $custom_field_id ) );
    $field = $wpdb->get_row( $query, ARRAY_A);
    return $field;
  }

}
