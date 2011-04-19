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
    global $post;

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
        if( $this->group_has_fields($group['id'] ) ) {
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
  }

  /**
   * Fill a metabox with custom fields
   */
  function mf_metabox_content( $post, $metabox ) {
    //Getting the custom fields for this metabox
    $custom_fields = $this->get_custom_fields_by_group($metabox['args']['group_info']['id']);
    //default markup
    ?>
    <div class="mf_group group-<?php print $metabox['args']['group_info']['id'];?>" > 
      <div class="mf-group-count">1 items</div> 
      <!-- grupos se puede repetir --> 
      <div class="mf_group mf_duplicate_group" id="mf_group_<?php print $metabox['args']['group_info']['id']; ?>_1"> 
        <div class="inside" > 
          <!-- campos del grupo (por cada campo) --> 
          <?php foreach( $custom_fields as $field ):?>
            <!-- si el campo se puede duplicar deberia estar esto N veces --> 
            <div class="mf-field  mf-field-ui <?php print $field['name'];?>" id="row_<?php print $field['id']; ?>_1_1_ui"> 
                <div> 
                  <?php 
                    $f = $field['type'].'_field';
                    $f = new $f();
                    print $f->display_field($field);
                   ?>
                </div> 
                <?php if( $field['duplicated'] ) :?>
                  <div class="buttons"> 
                    <a href="javascript:void(0);" class="mf_add_another">Add Another</a> <a href="javascript:void(0);">Remove</a> 
                  </div> 
                <?php endif;?> 
            </div> 
            <!-- fin del campo duplicado --> 
          <?php endforeach;?> 
          <!-- fin del campo --> 
        </div> 
      </div> 
      <!-- fin del grupo --> 
    </div>
  <?php
    //pr($custom_fields);
    //pr($metabox);
  }
}
