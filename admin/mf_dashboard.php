<?php

/** 
 *Dashboard
 *
 * Display, add, edit,delete  post types 
 */ 
class mf_dashboard extends mf_admin {

  public $name = 'mf_dashboard';
 
  function __construct() {

  }

  function main() {
    global $mf_domain;
    
    $posttypes = $this->get_post_types();
    $custom_taxonomies = $this->get_custom_taxonomies();
    
    print '<div class="wrap">';
    print '<h2>'.__( 'Magic Fields',$mf_domain).'</h2>';
    print '<h3>'.__( 'Post Types', $mf_domain ).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=add_post_type" class="add-new-h2 button">'.__( 'Add new Post Type', $mf_domain ).'</a></h3>';

    ?>
    <table class="widefat fixed" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" id="id" class="manage-column column-title" width="5%"><?php _e( 'id',$mf_domain); ?></th>
          <th scope="col" id="title" class="manage-column column-title" width="15%"><?php _e( 'Title/Singular',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'description',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'Actions',$mf_domain); ?></th>
        </tr> 
      </thead>
      <tfoot>
        <tr>
          <th scope="col" id="id" class="manage-column column-title" width="5%"><?php _e( 'id',$mf_domain); ?></th>
          <th scope="col" id="title" class="manage-column column-title" width="15%"><?php _e( 'Title/Singular',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'description',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'Actions',$mf_domain); ?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php if($posttypes): ?>
          <?php foreach($posttypes as $pt): ?>
        <tr class="alternate iedit">
          <td><?php echo $pt['id']; ?></td>
          <td><?php echo $pt['name']; ?></td>
          <td><?php echo $pt['description']; ?></td>
          <td>
            <span class="edit">
              <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_new_field&post_type_id=<?php print $pt['id'];?>">Edit Fields/Groups</a>
            </span> | 
            <span class="edit">
              <a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=edit_post_type&post_type_id=<?php echo $pt['id']; ?>">Edit Post Type</a>
            </span> | 
            <span class="delete">
              <?php //nonce
                $link = "admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=delete_post_type&post_type_id={$pt['id']}";
                $link = wp_nonce_url($link,"delete_post_type_mf_posttype");
              ?>
              <a href="<?php print $link;?>">Delete</a>
            </span>
          </td>
        </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <?php

    print '<h3>'.__(' Custom Taxonomy',$mf_domain).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_taxonomy&mf_action=add_custom_taxonomy" class="add-new-h2 button">'.__( 'Add new Custom Taxonomy', $mf_domain ).'</a></h3>';
    print '</div>';
    ?>
    <table class="widefat fixed" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" id="id" class="manage-column column-title" width="5%"><?php _e( 'id',$mf_domain); ?></th>
          <th scope="col" id="title" class="manage-column column-title" width="15%"><?php _e( 'Title/Singular',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'description',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'Actions',$mf_domain); ?></th>
        </tr> 
      </thead>
      <tfoot>
        <tr>
          <th scope="col" id="id" class="manage-column column-title" width="5%"><?php _e( 'id',$mf_domain); ?></th>
          <th scope="col" id="title" class="manage-column column-title" width="15%"><?php _e( 'Title/Singular',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'description',$mf_domain); ?></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'Actions',$mf_domain); ?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php if($custom_taxonomies): ?>
          <?php foreach($custom_taxonomies as $tax): ?>
        <tr class="alternate iedit">
          <td><?php echo $tax['id']; ?></td>
          <td><?php echo $tax['name']; ?></td>
          <td><?php echo $tax['description']; ?></td>
          <td>
            <span class="edit">
              <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_taxonomy&mf_action=edit_custom_taxonomy&custom_taxonomy_id=<?php echo $tax['id']; ?>">Edit Custom Taxonomy</a>
            </span> | 
            <span class="delete">
              <?php 
                $link = "admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_taxonomy&mf_action=delete_custom_taxonomy&custom_taxonomy_id={$tax['id']}";
                $link = wp_nonce_url($link,"delete_custom_taxonomy_mf_custom_taxonomy");
              ?>
              <a href="<?php print($link);?>">Delete</a>
            </span>
          </td>
        </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <?php

  }

}
