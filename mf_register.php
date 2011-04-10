<?php
 /**
  *
  */
class mf_register{

  public $name = 'mf_register';
  
  function __construct(){
    add_action('init', array( &$this, 'mf_register_custom_taxonomies' ) );
    add_action('init', array( &$this, 'mf_register_post_types' ) );
  }

  // register post type
  public function mf_register_post_types(){
    global $mf_pt_register;

    $post_types = $this->_get_post_types();

    foreach($post_types as $p){
      $p = json_decode($p['arguments'],true);

      $name = $p['core']['type'];
      $option = $p['option'];

      $option['show_in_menu'] = ($option['show_in_menu']) ? true : false;
      $option['query_var'] = ($option['query_var']) ? true : false;
      $option['labels'] = $p['label'];


      if( isset($p['support']) ){
        foreach($p['support'] as $k => $v){
          $option['supports'][] = $k;
        }
      }

      if( isset($p['taxonomy']) ){
        foreach($p['taxonomy'] as $k => $v){
          //register_taxonomy_for_object_type($k, $name);
          $option['taxonomies'][] = $k;
        }
      }
 
      if($option['rewrite'] && $option['rewrite_slug'])
        $option['rewrite'] = array( 'slug' => $option['rewrite_slug'] );

      
      unset($option['rewrite_slug']);
      array_push($mf_pt_register,$name);
      register_post_type($name,$option);
    }
    
  }

  public function mf_register_custom_taxonomies(){

    $taxonomies = $this->_get_custom_taxonomies();
    foreach($taxonomies as $tax){
      $tax = json_decode($tax['arguments'],true);
      $name = $tax['core']['name'];
      $option = $tax['option'];

      $option['show_in_nav_menus'] = ($option['show_in_nav_menus']) ? true : false;
      $option['query_var'] = ($option['query_var']) ? true : false;


      if( !$option['update_count_callback'] ){
        unset($option['update_count_callback']);
      }
      $option['labels'] = $tax['label'];
      $in = $tax['post_types'];

      if($option['rewrite'] && $option['rewrite_slug'])
        $option['rewrite'] = array( 'slug' => $option['rewrite_slug'] );

      unset($option['rewrite_slug']);
      register_taxonomy($name,$in, $option);
    }

  }

  /**                                                                         
   * return all post types                                                    
   * @todo this function is duplicated? 
   */
  private function _get_post_types(){
    global $wpdb;

    $query = sprintf('SELECT * FROM %s ORDER BY id',MF_TABLE_POSTTYPES);
    $posttypes = $wpdb->get_results( $query, ARRAY_A );
    return $posttypes;
  }

  /**                                                                        
   * return all custom_taxonomy                                               
   */
  private function _get_custom_taxonomies(){
    global $wpdb;

    $query = sprintf('SELECT * FROM %s ORDER BY id',MF_TABLE_CUSTOM_TAXONOMY);
    $custom_taxonomies = $wpdb->get_results( $query, ARRAY_A );
    return $custom_taxonomies;
  }
}
