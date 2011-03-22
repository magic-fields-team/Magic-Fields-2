<?php 

class mf_custom_fields {
  
  /**
   *
   */
  function init() {
    $path = $_SERVER['PHP_SELF'];

    if(preg_match('/post\-new\.php/',$path)) {
      //getting the post type
      $post_type =  !(empty($_GET['post_type'])) ? $_GET['post_type'] :  'post';

      //adding the custom fields related with the post_type
      $this->load_fields($post_type);
    }
  }

  function load_fields($post_type) {

    print_r($post_type);
  }

  /**
   * Magic Fields UI for add a new custom field
   */
  function add_new_field() {
    global $mf_domain;

    $pt = new mf_posttype();
    $post_type = $pt->get_post_type($_GET['post_type_id']);

    print '<div class="wrap">';
    print '<h2>'.$post_type['core']['label'].'</h2>';
    print '<h3>'.__( 'Custom Fields', $mf_domain ).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=add_post_type" class="add-new-h2 button">'.__( 'Add new Custom Field', $mf_domain ).'</a></h3>';

    print '</div>';
  }
}
