<?php

/**
 * class mf_custom_taxonomy
 *
 **/
class mf_custom_taxonomy extends mf_admin{

  public $name = 'mf_custom_taxonomy';

  public function __construct(){
  
  }
  
  /** add a new custom taxonomy **/
  public function add_custom_taxonomy(){
    $data = $this->fields_form();
    $this->form_custom_taxonomy($data);
  }

  /**
   * Edit custom taxonomy
   */
  public function edit_custom_taxonomy() {

    if(!isset($_GET['custom_taxonomy_id']) ){
      $this->mf_redirect(null,null,array('message' => 'success'));
    }

    $custom_taxonomy = $this->get_custom_taxonomy($_GET['custom_taxonomy_id']);

    if( !$custom_taxonomy ){
      $this->mf_redirect(null,null,array('message' => 'error'));
    }
    $data = $this->fields_form();

    $post_types = array();
    if( isset($custom_taxonomy['post_types']) ){
      foreach($custom_taxonomy['post_types'] as $k => $v){
        array_push($post_types,$v);
      }
      $data['taxonomy']['post_type'] = $post_types;
    }
    // update fields
    $perm = array('core','option','label');
    foreach($custom_taxonomy as $key => $value){
      if( in_array($key,$perm) ){
        foreach($value as $id => $val){
          $data[$key][$id]['value'] = $val;
        }
      }
    }
    $this->form_custom_taxonomy($data);
  }

  /**
   * get a specific post type
   */
  public function get_custom_taxonomy($custom_taxonomy_id){
    global $wpdb;

    $query = sprintf('SELECT * FROM %s WHERE id = %s',MF_TABLE_CUSTOM_TAXONOMY,$custom_taxonomy_id);
    $custom_taxonomy = $wpdb->get_row( $query, ARRAY_A );
    if($custom_taxonomy){
      $custom_taxonomy = unserialize($custom_taxonomy['arguments']);
      $custom_taxonomy['core']['id'] = $custom_taxonomy_id;
      return $custom_taxonomy;
    }
    return false;
  }

  /**
   * save a custom taxonomy
   */
  public function save_custom_taxonomy(){
    global $mf_domain;

    //checking the nonce
    check_admin_referer('form_custom_taxonomy_mf_custom_taxonomy');

    if(isset($_POST['mf_custom_taxonomy'])){
      //check custom_taxonomy_id
      $mf = $_POST['mf_custom_taxonomy'];
      if($mf['core']['id']){
        $this->update_custom_taxonomy($mf);
      }else{
        if($this->new_custom_taxonomy($mf)){
          //redirect to dashboard
        }else{
          //reload form and show warning
        }
      }
    }
    $this->mf_redirect(null,null,array('message' => 'success'));
  }

  /**
   * delete a custom taxonomy
   */
  public function delete_custom_taxonomy(){
    global $wpdb;

    //checking the nonce
    check_admin_referer('delete_custom_taxonomy_mf_custom_taxonomy');

    if( isset($_GET['custom_taxonomy_id']) ){
      $id = (int)$_GET['custom_taxonomy_id'];

      if( is_int($id) ){
        $sql = sprintf(
          "DELETE FROM " . MF_TABLE_CUSTOM_TAXONOMY .
          " WHERE id = %d",
          $id
        );
        $wpdb->query($sql);
        $this->mf_redirect(null,null,array('message' => 'success'));
      }
    }
  }


  /**
   * form
   */
  function form_custom_taxonomy($data){
    global $mf_domain;

    print '<div class="wrap">';
    print '<div id="message_mf_error" class="error below-h2" style="display:none;"><p></p></div>';
    print '<div id="icon-edit" class="icon32"><br /></div>';
    if( !$data['core']['id']['value'] ):
      print '<h2>'.__( 'Add Custom Taxonomy', $mf_domain ).'</h2>';
    else:
      printf( "<h2>%s - %s</h2>",__('Edit Custom Taxonomy'),$data['core']['label']['value'] );
    endif;
    print '</div>';
    ?>
     <form id="addCustomTaxonomy" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_taxonomy&mf_action=save_custom_taxonomy" class="validate mf_form_admin">
       
      <?php
        //nonce 
        wp_nonce_field('form_custom_taxonomy_mf_custom_taxonomy');
      ?>

    <div class="alignleft fixed" id="add_mf_custom_taxonomy">
	<!-- core -->
	<?php foreach($data['core'] as $core): ?>
	<?php if($core['type'] == 'hidden'): ?>
	  <?php mf_form_hidden($core); ?>
	<?php elseif($core['type'] == 'text'): ?>
	  <div class="form-field mf_form <?php echo $core['div_class']; ?>">
	    <?php mf_form_text($core); ?>
	  </div>
	<?php endif; ?>
	<?php endforeach; ?>
	<!-- / core -->
        
        <!-- post types -->
        <div class="form-field mf_label_checkbox mf_form form_required">
          <?php _e( 'Post types', $mf_domain ) ?> <span class="required">*</span>
          <?php
          $post_types= $this->mf_get_post_types(); 
          ?>
          <?php $i = 1; ?>
          <?php foreach($post_types as $pt){ ?>
            <?php if($pt->name != 'attachment'){ ?>
              <?php 
                $check = '';
                if(in_array($pt->name,$data['taxonomy']['post_type'])){
                  $check = 'checked="checked"';
                }
                $val = 'a';
                if( $i == 1){
                $val = sprintf("{validate:{required:true,messages:{required:'%s'}}}",__('This Field is required, least one post type',$mf_domain));
                }
                $i++;
              ?>
              <p>
                <input name="mf_custom_taxonomy[post_types][]" id="custom-taxonomy-post-type-<?php echo  $pt->name; ?>" type="checkbox" value="<?php echo $pt->name; ?>" <?php echo $check; ?> class="<?php echo $val; ?>" >
                <label for="custom-taxonomy-post-type-<?php echo  $pt->name; ?>"><?php echo $pt->label; ?></label>
              </p>
            <?php } ?>
          <?php } ?>
        </div>
        <!-- / post types -->

        <!-- Submit -->
        <p class="submit">
          <a style="color:black" href="admin.php?page=mf_dispatcher" class="button">Cancel</a>
          <input type="submit" class="button button-primary" name="submit" id="submit" value="Save Custom Taxonomy">
        </p>
        <!-- / Submit -->
    </div>
    
    <div class="widefat mf_form_right stuffbox metabox-holder">
      <h3><?php _e('Options',$mf_domain); ?></h3>
      <div class="inside  categorydiv">
        <ul id="category-tabs" class="category-tabs options-tabs">
          <li class="tabs">
            <a id="options" href="#" ><?php _e( 'Advanced Options', $mf_domain ) ?></a>
          </li>
          <li class="">
            <a id="options_label" href="#"><?php _e( 'Advanced Label', $mf_domain ) ?></a>
          </li>
        </ul>
        <div class="tabs-panel">
          <div class="options">
            <fieldset>
              <legend><?php _e( 'Advanced Options', $mf_domain ) ?></legend>
              <!-- options -->
              <?php foreach($data['option'] as $option){ ?>
              <div class="form-field mf_label_checkbox mf_form">
                <?php
                  if($option['type'] == 'text'){
                    mf_form_text($option);
                  }elseif($option['type'] == 'checkbox'){
                    mf_form_checkbox($option);
                  }
                ?>
              </div>
              <?php } ?>
              <!-- / options -->
            </fieldset>
          </div>
      
          <div class="options_label" style="display: none;">
            <fieldset>
              <?php mf_form_checkbox($data['suggest_labels']);?>
            </fieldset>

            <fieldset>
              <legend><?php _e( 'Label Options', $mf_domain ) ?></legend>
              <!-- labels -->
              <?php foreach($data['label'] as $label){ ?>
              <div class="form-field mf_label_checkbox mf_form">
                <?php mf_form_text($label); ?>
              </div>
              <?php } ?>
              <!-- / labels -->
            </fieldset>
          </div>
        </div>
      </div>
  </form>
  <?php
  }


  public function fields_form(){
    global $mf_domain;

    //if be editing the custom taxonomy, can't modify the type name
    $type_readonly = FALSE;
    if( $_GET['mf_action']  == 'edit_custom_taxonomy' ) {
      $type_readonly = TRUE;
    }

    $fields = array(
      'core' => array(
        'id' => array(
          'id'          => 'custom-taxonomy-id',
          'type'        => 'hidden',
          'label'       => '',
          'name'        => 'mf_custom_taxonomy[core][id]',
          'value'       => NULL,
          'description' => '',
          'class'       => '',
          'div_class'   => ''
        ),
        'name' => array(
          'id'          => 'custom-taxonomy-name',
          'type'        => 'text',
          'label'       => __( 'Label', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[core][name]',
          'value'       => '',
          'description' => __( 'Singular label of the taxonomy.', $mf_domain ),
          'class'       => "{validate:{required:true,messages:{required:'".__('This Field is required',$mf_domain)."'}}}",
          'div_class'   => 'form-required'
        ),
        'label' => array(
          'id'          => 'custom-taxonomy-label',
          'type'        => 'text',
          'label'       => __( 'Labels', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[core][label]',
          'value'       => '',
          'description' =>  __( 'A plural descriptive name for the taxonomy marked for translation.', $mf_domain ),
          'class'       => '',
          'div_class'   => ''
        ),
        'type' => array(
          'id'          => 'custom-taxonomy-type',
          'type'        => 'text',
          'label'       => __( 'Type', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[core][type]',
          'value'       => '',
          'description' => __( 'Name of the object type for the taxonomy object. Used by the system, name must not contain capital letters or spaces. Once the taxonomy is created, the type cannot be changed.', $mf_domain ),
          'class'       => "{validate:{required:true, lowercase:true ,messages:{ lowercase:'".__( 'Only  are accepted lowercase characters,numbers or underscores' )."', required:'". __('This Field is required',$mf_domain)."'}}}",
          'div_class'   => 'form-required',
          'readonly'   => $type_readonly
        ),
        'description' => array(
          'id'          => 'custom-taxonomy-description',
          'type'        => 'text',
          'label'       =>  __( 'Description', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[core][description]',
          'value'       => '',
          'description' => __( 'A short descriptive summary of what the custom taxonomy is.', $mf_domain ),
          'class'       => '',
          'div_class'   => ''
        )
      ),
      'taxonomy' => array(
        'post_type' => array()
      ),
      'option' => array(
        'public' => array(
          'id'          => 'custom-taxonomy-public',
          'type'        => 'checkbox',
          'label'       => __( 'Public', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][public]',
          'value'       => 1,
          'description' => __( 'Should this taxonomy be exposed in the admin UI.', $mf_domain )
        ),
        'show_in_nav_menus' => array(
          'id'          => 'custom-taxonomy-show-in-nav-menus',
          'type'        => 'checkbox',
          'label'       => __( 'Show in nav menus', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][show_in_nav_menus]',
          'value'       => 1,
          'description' => __( 'true makes this taxonomy available for selection in navigation menus.', $mf_domain )
        ),
        'show_ui' => array(
          'id'          => 'custom-taxonomy-show-ui',
          'type'        => 'checkbox',
          'label'       => __( 'Show UI', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][show_ui]',
          'value'       => 1,
          'description' => __( 'Whether to generate a default UI for managing this taxonomy.', $mf_domain )
        ),

        'show_tagcloud' =>array(
          'id'          => 'custom-taxonomy-show-tagcloud',
          'type'        => 'checkbox',
          'label'       => __( 'Show tagcloud', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][show_tagcloud]',
          'value'       => 0,
          'description' => __( 'Wether to allow the Tag Cloud widget to use this taxonomy.', $mf_domain )
        ),
        'hierarchical' => array(
          'id'          => 'custom-taxonomy-hierarchical',
          'type'        => 'checkbox',
          'label'       => __( 'Hierarchical', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][hierarchical]',
          'value'       => 1,
          'description' => __( 'Is this taxonomy hierarchical (have descendants) like categories or not hierarchical like tags.', $mf_domain )
        ),
        'update_count_callback' => array(
          'id'          => 'custom-taxonomy-update-count-callback',
          'type'        => 'text',
          'label'       => __( 'Update count callback', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][update_count_callback]',
          'value'       => '',
          'description' => __( 'A function name that will be called to update the count of an associated $object_type, such as post, is updated.', $mf_domain )
        ),
        'rewrite' => array(
          'id'          => 'custom-taxonomy-rewrite',
          'type'        => 'checkbox',
          'label'       => __( 'Rewrite', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][rewrite]',
          'value'       => 0,
          'description' => __( 'Set to false to prevent rewrite, Default will use $taxonomy as query var', $mf_domain )
        ),
        'rewrite_slug' => array(
          'id'          => 'custom-taxonomy-rewrite-slug',
          'type'        => 'text',
          'label'       => __( 'Rewrite slug', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][rewrite_slug]',
          'value'       => '',
          'description' => __( 'Prepend posts with this slug - defaults to taxonomy\'s name', $mf_domain )
        ),
        'query_var' => array(
          'id'          => 'custom-taxonomy-query-var',
          'type'        => 'checkbox',
          'label'       =>  __( 'Query var', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[option][query_var]',
          'value'       => 1,
          'description' => __( 'False to prevent queries, or string to customize query var. Default will use $taxonomy as query var.', $mf_domain )
        )
      ),
      'label' => array(
        'name' => array(
          'id'          => 'custom-taxonomy-label-name',
          'type'        => 'text',
          'label'       => __( 'Name', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][name]',
          'value'       => __('Categories',$mf_domain),
          'description' => __( 'General name for the taxonomy, usually plural.', $mf_domain ),
          'rel'         => '%s'
        ),
        'singular_name' => array(
          'id'          => 'custom-taxonomy-label-singular-name',
          'type'        => 'text',
          'label'       => __( 'Singular name', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][singular_name]',
          'value'       => __('Category',$mf_domain),
          'description' => __( 'Name for one object of this taxonomy.', $mf_domain ),
          'rel'         => '%s'
        ),
        'search_items' => array(
          'id'          => 'custom-taxonomy-label-search-items',
          'type'        => 'text',
          'label'       => __( 'Search items', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][search_items]',
          'value'       => __('Search Categories',$mf_domain),
          'description' => __( 'The search items text.', $mf_domain ),
          'rel'         => __('Search',$mf_domain).' %s'
        ),
        'add_new_item' => array(
          'id'          => 'custom-taxonomy-label-add-new-item',
          'type'        => 'text',
          'label'       =>  __( 'Add new item', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][add_new_item]',
          'value'       => __('Add New Post',$mf_domain),
          'description' => __( 'The add new item text.', $mf_domain ),
          'rel'         => __('Add New',$mf_domain) . ' %s'
        ),
        'popular_items' => array(
          'id'          => 'custom-taxonomy-label-popular-items',
          'type'        => 'text',
          'label'       => __( 'Popular items', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][popular_items]',
          'value'       => __('Popular Tags',$mf_domain),
          'description' => __( 'The popular items text.', $mf_domain ),
          'rel'         => __('Popular',$mf_domain) . " %s"
        ),
        'all_items' => array(
          'id'          => 'custom-taxonomy-label-all-items',
          'type'        => 'text',
          'label'       => __( 'All items', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][all_items]',
          'value'       => __('All Categories',$mf_domain),
          'description' => __( 'The all items text.', $mf_domain ),
          'rel'         => __('All',$mf_domain) . ' %s'
        ),
        'parent_item' => array(
          'id'          => 'custom-taxonomy-label-parent-item',
          'type'        => 'text',
          'label'       => __( 'Parent item', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][parent_item]',
          'value'       => __('Parent Category',$mf_domain),
          'description' => __( 'The parent item text. This string is not used on non-hierarchical taxonomies such as post tags.', $mf_domain ),
          'rel'         => __('Parent',$mf_domain) . ' %s'
        ),
        'parent_item_colon' => array(
          'id'          => 'custom-taxonomy-label-parent-item-colon',
          'type'        => 'text',
          'label'       => __( 'Parent item colon', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][parent_item_colon]',
          'value'       => __('Parent Category:',$mf_domain),
          'description' => __( 'The same as parent_item, but with colon :', $mf_domain ),
          'rel'         => __('Parent',$mf_domain) . ' %s:'
        ),
        'edit_item' => array(
          'id'          => 'custom-taxonomy-label-edit-item',
          'type'        => 'text',
          'label'       => __( 'Edit item', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][edit_item]',
          'value'       => __('Edit Category',$mf_domain),
          'description' => __( 'The edit item text.', $mf_domain ),
          'rel'         => __('Edit',$mf_domain) . ' %s'
        ),
        'update_item' => array(
          'id'          => 'custom-taxonomy-label-update-item',
          'type'        => 'text',
          'label'       => __( 'Update item', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][update_item]',
          'value'       => __('Update Category',$mf_domain),
          'description' => __( 'The update item text.', $mf_domain ),
          'rel'         => __('Update',$mf_domain) . ' %s'
        ),
        'add_new_item' => array(
          'id'          => 'custom-taxonomy-label-add-new-item',
          'type'        => 'text',
          'label'       => __( 'Add new item', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][add_new_item]',
          'value'       => __('Add New Category',$mf_domain),
          'description' => __( 'The add new item text.', $mf_domain ),
          'rel'         => __('Add New',$mf_domain) . ' %s'
        ),
        'new_item_name' => array(
          'id'          => 'custom-taxonomy-label-new-item-name',
          'type'        => 'text',
          'label'       => __( 'New item name', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][new_item_name]',
          'value'       => __('New Category Name',$mf_domain),
          'description' => __( 'The new item name text.', $mf_domain ),
          'rel'         => __('New',$mf_domain) . ' %s ' . __('Name',$mf_domain)
        ),
        'separate_items_with_commas' => array(
          'id'          => 'custom-taxonomy-label-separate-items-with-commas',
          'type'        => 'text',
          'label'       => __( 'Separate items with commas', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][separate_items_with_commas]',
          'value'       => __('Separate tags with commas',$mf_domain),
          'description' => __( "The separate item with commas text used in the taxonomy meta box. This string isn't used on hierarchical taxonomies.", $mf_domain ),
          'rel'         => __('Separate',$mf_domain) . ' %s ' . __('with commas',$mf_domain)
        ),
        'add_or_remove_items' => array(
          'id'          => 'custom-taxonomy-label-add-or-remove-items',
          'type'        => 'text',
          'label'       => __( 'Add or remove items', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][add_or_remove_items]',
          'value'       => __('Update Category',$mf_domain),
          'description' => __( "The add or remove items text and used in the meta box when JavaScript is disabled. This string isn't used on hierarchical taxonomies.", $mf_domain ),
          'rel'         => __('Update',$mf_domain) . ' %s'
        ),
        'choose_from_most_used' => array(
          'id'          => 'custom-taxonomy-label-choose-from-most-used',
          'type'        => 'text',
          'label'       => __( 'Choose from most used', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][choose_from_most_used]',
          'value'       => __('Choose from the most used tags',$mf_domain),
          'description' => __( "The choose from most used text used in the taxonomy meta box. This string isn't used on hierarchical taxonomies.", $mf_domain ),
          'rel'         => __('Choose from the most used',$mf_domain) . ' %s'
        ),
        'menu_name' => array(
          'id'          => 'custom-taxonomy-label-menu-name',
          'type'        => 'text',
          'label'       => __( 'Menu name', $mf_domain ),
          'name'        => 'mf_custom_taxonomy[label][menu_name]',
          'value'       => __('Categories',$mf_domain),
          'description' => __( 'The menu name text. This string is the name to give menu items. Defaults to value of name.', $mf_domain ),
          'rel'         => '%s'
        )
      ),
      'suggest_labels'	=> array(
        'id'          =>  'suggest-labels',
        'type'        =>  'checkbox',
        'label'       =>  'Suggest labels',
        'name'        =>  'suggest-labels',
        'value'       =>  1,
        'description' => __( 'Make suggestions for the labels using the label of the custom taxonomy', $mf_domain )
      )
    );

    return $fields;
  }
  
  public function check_custom_taxonomy($type,$id = NULL){
    global $wpdb;
  
    $query = sprintf("SELECT COUNT(*) FROM %s WHERE type = '%s'",MF_TABLE_CUSTOM_TAXONOMY,$type);
    if($id)
      $query = sprintf("%s AND id != %s",$query,$id);
      
    $check = $wpdb->get_var($query);
    return $check;
  }

}
