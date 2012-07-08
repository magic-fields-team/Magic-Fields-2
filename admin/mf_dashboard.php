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
    global $mf_domain,$mf_pt_register;
    
    $posttypes = $this->mf_get_post_types();
    $custom_taxonomies = $this->get_custom_taxonomies();
    
    print '<div class="wrap">';
    // print screen icon 	
    print get_screen_icon('magic-fields');   
    print '<h2>'.__( 'Magic Fields',$mf_domain).'</h2>';
    print '<h3>'.__( 'Post Types', $mf_domain ).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=add_post_type" class="add-new-h2 button">'.__( 'Add new Post Type', $mf_domain ).'</a></h3>';

    ?>
    <table class="widefat fixed" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" id="title" class="manage-column column-title" width="40%"><?php _e( 'Label ',$mf_domain); ?><small>(<?php _e('Menu name',$mf_domain); ?>)</small></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="30%"><?php _e( 'Type',$mf_domain); ?></th>
          <th scope="col" id="type_desc" class="manage-column column-title" width="30%"><?php _e( 'Description',$mf_domain); ?></th>
        </tr> 
      </thead>
      <tfoot>
        <tr>
          <th scope="col" id="title" class="manage-column column-title" width="40%"><?php _e( 'Label ',$mf_domain); ?><small>(<?php _e('Menu name',$mf_domain); ?>)</small></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="30%"><?php _e( 'Type',$mf_domain); ?></th>
          <th scope="col" id="type_desc" class="manage-column column-title" width="30%"><?php _e( 'Description',$mf_domain); ?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php 
          $counter = 0;
  foreach($posttypes as  $pt):
          $alternate = ($counter % 2 ) ? "alternate" : "";
          $counter++;
        ?>

        <tr class="<?php print $alternate;?> iedit">
          <td>
            <strong><?php echo $pt->label; ?></strong><small> ( <?php echo $pt->labels->menu_name; ?> )</small>
            <div class="row-actions">
              <span class="edit">
                <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=fields_list&post_type=<?php print $pt->name;?>">Edit Fields/Groups</a> 
              </span>
              <?php if(in_array($pt->name,$mf_pt_register)): ?>
              |<span class="edit">
                <a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=edit_post_type&post_type=<?php echo $pt->name; ?>">Edit Post Type</a> |
              </span>
              <span class="delete">
                <?php //nonce
                  $link = "admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=delete_post_type&post_type={$pt->name}";
                  $link = wp_nonce_url($link,"delete_post_type_mf_posttype");
                ?> 
                <a class="mf_confirm" alt="<?php _e("This action can't be undone, are you sure?", $mf_domain )?>"  href="<?php print $link;?>">Delete</a> 
								<?php else: ?>
									| <a href="admin.php?page=mf_dispatcher&init=false&mf_section=mf_posttype&mf_action=set_categories&post_type=<?php echo $pt->name;?>&TB_iframe=1&width=640&height=541" title="default categories" class="thickbox" onclick="return false;" >Set default categories</a>
                 <?php endif; ?>
              </span>
            </div>
          </td>
          <td><?php echo $pt->name; ?></td>
          <td><?php echo $pt->description; ?></td>
        </tr>
          <?php endforeach; ?>
      </tbody>
    </table>
    <?php

    print '<h3>'.__(' Custom Taxonomy',$mf_domain).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_taxonomy&mf_action=add_custom_taxonomy" class="add-new-h2 button">'.__( 'Add new Custom Taxonomy', $mf_domain ).'</a></h3>';

    if( empty( $custom_taxonomies ) ) :
    ?>
      <div class="message-box info">
      <p>
        ooh, you do  haven't created any Custom Taxonomy,  try creating one <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_taxonomy&mf_action=add_custom_taxonomy">here</a>
      </p>
      </div>

    <?php else: ?>
    <table class="widefat fixed" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" id="title" class="manage-column column-title" width="40%"><?php _e( 'Label ',$mf_domain); ?><small>(<?php _e('Menu name',$mf_domain); ?>)</small></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="30%"><?php _e( 'Type',$mf_domain); ?></th>
          <th scope="col" id="type_desc" class="manage-column column-title" width="30%"><?php _e( 'Description',$mf_domain); ?></th>
        </tr> 
      </thead>
      <tfoot>
        <tr>
          <th scope="col" id="title" class="manage-column column-title" width="15%"><?php _e( 'Label ',$mf_domain); ?><small>(<?php _e('Menu name',$mf_domain); ?>)</small></th>
          <th scope="col" id="type_name" class="manage-column column-title" width="40%"><?php _e( 'Type',$mf_domain); ?></th>
          <th scope="col" id="type_desc" class="manage-column column-title" width="40%"><?php _e( 'Description',$mf_domain); ?></th>
        </tr>
      </tfoot>
      <tbody>
        <?php if($custom_taxonomies):?>
          <?php 
            $counter = 0;
             foreach($custom_taxonomies as $tax):
             $alternate = ($counter % 2 ) ? "alternate" : "";
             $counter++;
             $tmp = unserialize($tax['arguments']);
          ?>
        <tr class="<?php print $alternate;?> iedit">
          <td>
            <strong><?php echo $tax['name']; ?></strong> <small>( <?php echo $tmp['label']['menu_name']; ?> )</small>
            <div class="row-actions">
              <span class="edit"> 
                <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_taxonomy&mf_action=edit_custom_taxonomy&custom_taxonomy_id=<?php echo $tax['id']; ?>">Edit Custom Taxonomy</a> |
              </span>
              <span class="delete">
                <?php 
                  $link = "admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_taxonomy&mf_action=delete_custom_taxonomy&custom_taxonomy_id={$tax['id']}";
                  $link = wp_nonce_url($link,"delete_custom_taxonomy_mf_custom_taxonomy");
                ?>
                <a href="<?php print($link);?>" class="mf_confirm" alt="<?php _e("This action can't be undone, are you sure?", $mf_domain );?>">Delete</a>
              </span>
            </div>
          </td>
          <td> <?php echo $tax['type']; ?></td>
          <td><?php echo $tax['description']; ?></td>
        </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    <?php
    endif;
    print '</div>';
  }

}
