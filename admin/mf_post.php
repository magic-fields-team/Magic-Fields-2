<?php

/** 
 * In this page be added the metaboxes into the posts
 * and be added the hooks for save the data of each post
 */
class mf_post extends mf_admin {
  
  function __construct() {
    //creating metaboxes
    add_action('add_meta_boxes', array( &$this, 'mf_post_add_metaboxes' ));

    //save data
    //add_action('save_post', 'mf_save_post_data');
    
  }
  
  /** 
   * Adding the metaboxes
   */
  function mf_post_add_metaboxes() {
    //Getting the post types
    $post_types = $this->mf_get_post_types( array('public' => true ), 'names'  );
  
    foreach ( $post_types as $post_type ){
      if( !mf_custom_fields::has_fields($post_type) ) {
          continue;
      }

      //getting  the groups (each group is a metabox) 
      $groups = $this->get_groups_by_post_type($post_type);

      //creating the metaboxes
      foreach( $groups as $group ) {
        add_meta_box( 
          'mf_'.$group['id'],
          $group['label'],
          array( &$this, 'mf_metabox_content' ),
          $post_type,
          'normal',
          'default',
          array( 'group_info' => $group)
        );
      }
    }
  }

  /**
   * Fill a metabox with custom fields
   */
  function mf_metabox_content( $post, $metabox) {
    pr($metabox);
  }
}
