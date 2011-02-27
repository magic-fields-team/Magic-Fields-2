<?php
/**
 * @todo make a good description of this class
 */
class mf_admin {

  public $name = 'mf_admin';
  /**
   * is a wrapper of wp_safe_redirect 
   */
  function mf_redirect( $section = 'mf_dashboard', $action = 'main', $vars = array( ) ) {
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
    wp_safe_redirect($url);
    exit;
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
    ?>
    <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="text" <?php echo $size; ?> <?php echo $value; ?> <?php echo $class; ?> >
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
   */
  public function get_post_types(){
    global $wpdb;
    
    $query = sprintf('SELECT * FROM %s ORDER BY id',MF_TABLE_POSTTYPES);
    $posttypes = $wpdb->get_results( $query, ARRAY_A );
    return $posttypes;
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

}
