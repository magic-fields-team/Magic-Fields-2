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
    global $mf_pt_register,$mf_pt_unique;

    $post_types = $this->_get_post_types();
    
    foreach($post_types as $p){
      $p = unserialize($p['arguments']);

      $name = $p['core']['type'];
      $option = $p['option'];

      $option['show_in_menu'] = ($option['show_in_menu']) ? true : false;
      $option['query_var'] = ($option['query_var']) ? true : false;
      $option['exclude_from_search'] = ($option['exclude_from_search']) ? true : false;
      $option['labels'] = $p['label'];
      $option['with_front'] = (isset($option['with_front'])) ? $option['with_front'] : true;


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
			if(isset($option['has_archive']) && $option['has_archive'] && isset($option['has_archive_slug']) && $option['has_archive_slug'])	
				$option['has_archive'] = $option['has_archive_slug'];
			
			
      if($option['rewrite'] && $option['rewrite_slug'])
        $option['rewrite'] = array( 'slug' => $option['rewrite_slug'],'with_front' => $option['with_front']);

      unset($option['rewrite_slug']);
      unset($option['with_front']);
      array_push($mf_pt_register,$name);

      if($option['menu_position']){
        $option['menu_position'] = (int)$option['menu_position'];
      }

      //check Capability type
      trim($option['capability_type']);
      if(empty($option['capability_type'])){
        $option['capability_type'] = 'post';
      }elseif( !in_array($option['capability_type'],array('post','page')) ){
        $option['capabilities'] = $this->_get_cap($option['capability_type']);
      }
      
      //description
      $option['description'] = $p['core']['description'];
      register_post_type($name,$option);

      //add unique post type
      if ($p['core']['quantity']) {
        array_push($mf_pt_unique, "edit.php?post_type=".$name);
      }
     
     
    }
  
  }

  public function _get_cap($name){

    $caps = array(
      'edit_post'          => sprintf('edit_%s',$name),
      'read_post'          => sprintf('read_%s',$name),
      'delete_post'        => sprintf('delete_%s',$name),
      'edit_posts'         => sprintf('edit_%ss',$name),
      'edit_others_posts'  => sprintf('edit_others_%ss',$name),
      'publish_posts'      => sprintf('publish_%ss',$name),
      'read_private_posts' => sprintf('read_private_%ss',$name)
    );

      return $caps;
  }

  public function mf_register_custom_taxonomies(){

    $taxonomies = $this->_get_custom_taxonomies();
    foreach($taxonomies as $tax){
      $tax = unserialize($tax['arguments']);
      $name = $tax['core']['type'];
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
