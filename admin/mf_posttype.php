<?php

/**
 *Dashboard
 *
 * Display, add, edit,delete  post types
 */
class mf_posttype extends mf_admin {

  public $name = 'mf_post_type';

  public function __construct() {

  }

  /** add a new post type **/
  public function add_post_type(){

    $data = $this->fields_form();
    $this->form_post_type($data);

  }

  public function fields_form() {
    global $mf_domain;

    $data = array(
      'suggest_labels'	=> array(
        'id'          =>  'suggest-labels',
        'type'        =>  'checkbox',
        'label'       =>  'Suggest labels',
        'name'        =>  'suggest-labels',
        'value'       =>  1,
        'description' => __( 'Make suggestions for the labels using the label of the post type', $mf_domain )
      ),
      'core' => array(
        'id' => array(
          'id' => 'posttype-id',
          'type' => 'hidden',
          'label' => '',
          'name' => 'mf_posttype[core][id]',
          'value' => NULL,
          'description' => ''
        ),
        'type' => array(
          'id' => 'posttype-type',
          'type' => 'text',
          'label' => __( 'Type', $mf_domain ),
          'name' => 'mf_posttype[core][type]',
          'value' => '',
          'description' => __( 'The type must have less than 20 characters and only are accepted lowercases letters and undescores.', $mf_domain ),
          'class' => "{ validate:{ required:true, maxlength:20, lowercase:true, messages:{ lowercase:'".__( 'Only  are accepted lowercase characters,numbers or underscores' )."', required:'".__( 'This Field is required', $mf_domain )."', maxlength:'".__( 'This Field must have less than 20 characters' )."' }}}",
          'div_class' => 'form-required'
        ),
        'label' => array(
          'id' => 'posttype-label',
          'type' => 'text',
          'label' => __( 'Label', $mf_domain ),
          'name' => 'mf_posttype[core][label]',
          'value' => '',
          'description' => __( 'Singular label of the post type.', $mf_domain ),
          'class' => "{validate:{required:true,messages:{required:'". __('This Field is required',$mf_domain)."'}}}",
          'div_class' => 'form-required'
        ),
        'labels' => array(
          'id' => 'posttype-labels',
          'type' => 'text',
          'label' => __( 'Labels', $mf_domain ),
          'name' => 'mf_posttype[core][labels]',
          'value' => '',
          'description' =>  __( 'Plural label of the post type.', $mf_domain ),
          'class' => '',
          'div_class' => ''
        ),
        'description' => array(
          'id' => 'posttype-description',
          'type' => 'text',
          'label' =>  __( 'Description', $mf_domain ),
          'name' => 'mf_posttype[core][description]',
          'value' => '',
          'description' => __( 'A short descriptive summary of what the post type is.', $mf_domain ),
          'class' => '',
          'div_class' => ''
        )
      ),
      'posttype_support' => array(),
      'posttype_taxonomy' => array(),
      'option' => array(
        'public' => array(
          'id' => 'posttype-public',
          'type' => 'checkbox',
          'label' => __( 'Public', $mf_domain ),
          'name' => 'mf_posttype[option][public]',
          'value' => 1,
          'description' => __( 'Meta argument used to define default values for publicly_queriable, show_ui, show_in_nav_menus and exclude_from_search.', $mf_domain )
        ),
        'publicly_queryable' => array(
          'id' => 'posttype-publicly-queryable',
          'type' => 'checkbox',
          'label' => __( 'Publicly queryable', $mf_domain ),
          'name' => 'mf_posttype[option][publicly_queryable]',
          'value' => 1,
          'description' => __( 'Whether post_type queries can be performed from the front end.', $mf_domain )
        ),
        'exclude_from_search' => array(
          'id' => 'posttype-exclude-from-search',
          'type' => 'checkbox',
          'label' => __( 'Exclude from search', $mf_domain ),
          'name' => 'mf_posttype[option][exclude_from_search]',
          'value' => 0,
          'description' => __( 'Whether to exclude posts with this post type from search results.', $mf_domain )
        ),
        'show_ui' => array(
          'id' => 'posttype-show-ui',
          'type' => 'checkbox',
          'label' => __( 'Show UI', $mf_domain ),
          'name' => 'mf_posttype[option][show_ui]',
          'value' => 1,
          'description' => __( 'Whether to generate a default UI for managing this post type. Note that _built-in post types, such as post and page, are intentionally set to false.', $mf_domain )
        ),
        'show_in_menu' =>array(
          'id' => 'posttype-show-in-menu',
          'type' => 'checkbox',
          'label' => __( 'Show in menu', $mf_domain ),
          'name' => 'mf_posttype[option][show_in_menu]',
          'value' => 1,
          'description' => __( 'Whether to show the post type in the admin menu and where to show that menu. Note that show_ui must be true.', $mf_domain )
        ),
        'menu_position' => array(
          'id' => 'posttype-menu-position',
          'type' => 'text',
          'label' => __( 'Menu position', $mf_domain ),
          'name' => 'mf_posttype[option][menu_position]',
          'value' => '',
          'description' => __( 'The position in the menu order the post type should appear.', $mf_domain )
        ),
        'capability_type' => array(
          'id' => 'posttype-capability-type',
          'type' => 'text',
          'label' => __( 'Capability type', $mf_domain ),
          'name' => 'mf_posttype[option][capability_type]',
          'value' => 'post',
          'description' => __( 'Capability type (post,page)', $mf_domain )
        ),
        'hierarchical' => array(
          'id' => 'posttype-hierarchical',
          'type' => 'checkbox',
          'label' => __( 'Hierarchical', $mf_domain ),
          'name' => 'mf_posttype[option][hierarchical]',
          'value' => 1,
          'description' => __( 'Rewrite permalinks with this format. False to prevent rewrite.', $mf_domain )
        ),
        'rewrite' => array(
          'id' => 'posttype-rewrite',
          'type' => 'checkbox',
          'label' => __( 'Rewrite', $mf_domain ),
          'name' => 'mf_posttype[option][rewrite]',
          'value' => 0,
          'description' => __( 'Rewrite permalinks with this format. False to prevent rewrite.', $mf_domain )
        ),
        'rewrite_slug' => array(
          'id' => 'posttype-rewrite-slug',
          'type' => 'text',
          'label' => __( 'Rewrite slug', $mf_domain ),
          'name' => 'mf_posttype[option][rewrite_slug]',
          'value' => '',
          'description' => __( 'Prepend posts with this slug - defaults to post type\'s name', $mf_domain )
        ),
        'query_var' => array(
          'id' => 'posttype-query-var',
          'type' => 'checkbox',
          'label' =>  __( 'Query var', $mf_domain ),
          'name' => 'mf_posttype[option][query_var]',
          'value' => 1,
          'description' => __( 'False to prevent queries, or string value of the query var to use for this post type.', $mf_domain )
        ),
        'can_export' => array(
          'id' => 'posttype-can-export',
          'type' => 'checkbox',
          'label' => __( 'Can export', $mf_domain ),
          'name' => 'mf_posttype[option][can_export]',
          'value' => 1,
          'description' => __( 'Can this post_type be exported.', $mf_domain )
        ),
        'show_in_nav_menus' => array(
          'id' => 'posttype-show-in-nav-menus',
          'type' => 'checkbox',
          'label' => __( 'Show in nav menus', $mf_domain ),
          'name' => 'mf_posttype[option][show_in_nav_menus]',
          'value' => 1,
          'description' => __( 'Whether post_type is available for selection in navigation menus.', $mf_domain )
        )
      ),
      'label' => array(
        'name' => array(
          'id' => 'posttype-label-name',
          'type' => 'text',
          'label' => __( 'Name', $mf_domain ),
          'name' => 'mf_posttype[label][name]',
          'value' => __('Posts',$mf_domain),
          'description' => __( 'General name for the post type, usually plural.', $mf_domain ),
          'rel' => '%s'
        ),
        'singular_name' => array(
          'id' => 'posttype-label-singular-name',
          'type' => 'text',
          'label' => __( 'Singular name', $mf_domain ),
          'name' => 'mf_posttype[label][singular_name]',
          'value' => __('Post',$mf_domain),
          'description' => __( 'Name for one object of this post type. Defaults to value of name.', $mf_domain ),
          'rel' => '%s' //@todo inflection
        ),
        'add_new' => array(
          'id' => 'posttype-label-add-new',
          'type' => 'text',
          'label' => __( 'Add new', $mf_domain ),
          'name' => 'mf_posttype[label][add_new]',
          'value' => __('Add New',$mf_domain),
          'description' => __( 'General name for the post type, usually plural.', $mf_domain ),
          'rel' => 'Add %s'
        ),
        'add_new_item' => array(
          'id' => 'posttype-label-add-new-item',
          'type' => 'text',
          'label' =>  __( 'Add new item', $mf_domain ),
          'name' => 'mf_posttype[label][add_new_item]',
          'value' => __('Add New Post',$mf_domain),
          'description' => __( 'The add new item text.', $mf_domain ),
          'rel' => 'Add New %s'
        ),
        'edit_item' => array(
          'id' => 'posttype-label-edit-item',
          'type' => 'text',
          'label' => __( 'Edit item', $mf_domain ),
          'name' => 'mf_posttype[label][edit_item]',
          'value' => __('Edit Post',$mf_domain),
          'description' => __( 'General name for the post type, usually plural.', $mf_domain ),
          'rel' => 'Edit %s'
        ),
        'new_item' => array(
          'id' => 'posttype-label-new-item',
          'type' => 'text',
          'label' => __( 'New item', $mf_domain ),
          'name' => 'mf_posttype[label][new_item]',
          'value' => __('New Post',$mf_domain),
          'description' => __( 'The new item text.', $mf_domain ),
          'rel' => 'New %s'
        ),
        'view_item' => array(
          'id' => 'posttype-label-view-item',
          'type' => 'text',
          'label' => __( 'View item', $mf_domain ),
          'name' => 'mf_posttype[label][view_item]',
          'value' => __('View Post',$mf_domain),
          'description' => __( 'The view item text.', $mf_domain ),
          'rel'   => 'View %s'
        ),
        'search_items' => array(
          'id' => 'posttype-label-search-items',
          'type' => 'text',
          'label' => __( 'Search items', $mf_domain ),
          'name' => 'mf_posttype[label][search_items]',
          'value' => __('Search Posts',$mf_domain),
          'description' => __( 'The search items text.', $mf_domain ),
          'rel' =>  'No %s found'
        ),
        'not_found_in_trash' => array(
          'id' => 'posttype-label-not-found-in-trash',
          'type' => 'text',
          'label' => __( 'Not found in trash', $mf_domain ),
          'name' => 'mf_posttype[label][not_found_in_trash]',
          'value' => __('No posts found in Trash',$mf_domain),
          'description' => __( 'the not found in trash text.', $mf_domain ),
          'rel' =>  'No %s found in Trash'
        )
      )
    );

    return $data;
  }

  /**
   * Edit post type
   */
  public function edit_post_type() {

    if(!isset($_GET['post_type']) ){
      $this->mf_flash( 'Oops! something was wrong, you will be redirected a safe place in a few seconds' );
    }

    $post_type = $this->get_post_type($_GET['post_type']);

    if( !$post_type ){
      $this->mf_flash('error');
    }else{

      $data = $this->fields_form();
      $post_type_support = array();
      if( isset($post_type['support']) ){
        foreach($post_type['support'] as $k => $v){
          array_push($post_type_support,$k);
        }
        $data['posttype_support'] = $post_type_support;
      }

      $post_type_taxonomy = array();
      if( isset($post_type['taxonomy']) ){
        foreach($post_type['taxonomy'] as $k => $v){
          array_push($post_type_taxonomy,$k);
        }
        $data['posttype_taxonomy'] = $post_type_taxonomy;
      }
      // update fields
      $perm = array('core','option','label');
      foreach($post_type as $key => $value){
        if( in_array($key,$perm) ){
          foreach($value as $id => $val){
            $data[$key][$id]['value'] = $val;
          }
        }
      }
      $this->form_post_type($data);
    }
  }

  function form_post_type($data){
    global $mf_domain, $supports;

    print '<div class="wrap">';
    print '<div id="message_post_type" class="error below-h2" style="display:none;"><p></p></div>';
    if( !$data['core']['id']['value'] ):
      print '<h2>'.__( 'Add Post Type', $mf_domain ).'</h2>';
    else:
      printf( "<h2>%s - %s</h2>",__('Edit Post Type'),$data['core']['label']['value'] );
    endif;
    print '</div>';
    ?>
     <form id="addPostType" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=save_post_type" class="validate">

      <!-- Nonces -->
      <?php wp_nonce_field('form_post_type_posttype');?>
      <!-- /Nonces  -->

    <div class="alignleft fixed" id="add_mf_posttype">
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
	<! / core -->

	<!-- supports -->
	<div class="form-field mf_label_checkbox mf_form">
	  <?php _e( 'Supports:', $mf_domain ) ?>
	  <?php foreach($supports as $support){ ?>
	    <?php
	      $check = '';
	      if(in_array($support,$data['posttype_support'])){
		$check = 'checked="checked"';
	      }
	    ?>
	    <p>
	    <input name="mf_posttype[support][<?php echo $support; ?>]" id="posttype-support-<?php echo $support; ?>" value="1" type="checkbox" <?php echo $check; ?> >
	    <label for="posttype-support-<?php echo $support; ?>"><?php echo preg_replace('/_/',' ',$support); ?></label>
	    </p>
	  <?php } ?>
	</div>
	<!-- / supports -->

	<!-- taxonomies -->
	<div class="form-field mf_label_checkbox mf_form">
	  <?php _e( 'Taxonomies:', $mf_domain ) ?>
	  <?php
	  $taxonomies=get_taxonomies(array( 'public'   => true ),'objects');
	  ?>
            <?php foreach($taxonomies as $tax){?>
      <?php if( !in_array($tax->name,array('nav_menu','post_format') ) ){ ?>
	      <?php
		$check = '';
		if(in_array($tax->name,$data['posttype_taxonomy'])){
		  $check = 'checked="checked"';
		}
	      ?>
	      <p>
		<input name="mf_posttype[taxonomy][<?php echo $tax->name; ?>]" id="posttype-taxonomy-<?php echo  $tax->name; ?>" type="checkbox" value="1" <?php echo $check; ?> >
		<label for="posttype-taxonomy-<?php echo  $tax->name; ?>"><?php echo $tax->label; ?></label>
	      </p>
	    <?php } ?>
	  <?php } ?>
	</div>
	<!-- / taxonomies -->

	<!-- Submit -->
	<p class="submit">
	  <a style="color:black" href="admin.php?page=mf_dispatcher" class="button">Cancel</a>
	  <input type="submit" class="button" name="submit" id="submit" value="Save Post type">
	</p>
	<!-- / Submit -->
    </div>

    <div class="widefat mf_form_right">
      <a id="options" href="#"><?php _e( 'Advanced Options', $mf_domain ) ?></a> | <a id="options_label" href="#"><?php _e( 'Advance Label', $mf_domain ) ?></a>

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

      <div class="options_label" style="display: none">
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

      <script>
      jQuery(document).ready(function(){
	jQuery('#options_label').click(function(){
	  jQuery('.options_label').show();
	  jQuery('.options').hide();
	});
	jQuery('#options').click(function(){
	  jQuery('.options').show();
	  jQuery('.options_label').hide();
	});
       });
      </script>
    </div>
     </form>
    <?php
  }

  /**
   * Save a Post Type
   */
  public function save_post_type () {
    global $mf_domain;

    //checking the nonce
    check_admin_referer('form_post_type_posttype');

    //saving the posttype
    if(isset($_POST['mf_posttype'])){
      //check posttype_id
      $mf = $_POST['mf_posttype'];
      if($mf['core']['id']){
        $this->update_post_type($mf);
      }else{
        if($this->new_posttype($mf)){
          //redirect to dashboard
        }else{
          //reload form and show warning
        }
      }
    }
    $this->mf_redirect(null,null,array('message' => 'success'));
  }

  /**
   * Save a new post
   */
  public function new_posttype($data){
    global $wpdb;

    $sql = sprintf(
      "INSERT INTO " . MF_TABLE_POSTTYPES .
      " (type, name, description, arguments, active)" .
      " values" .
      " ('%s', '%s', '%s', '%s', '%s')",
      $data['core']['type'],
      $data['core']['label'],
      $data['core']['description'],
      json_encode($data),
      1
    );

    $wpdb->query($sql);
    $postTypeId = $wpdb->insert_id;
    return $postTypeId;
  }

  /**
   * Update Post type data
   */
  public function update_post_type($data){
    global $wpdb;

    $sql = sprintf(
      "Update " . MF_TABLE_POSTTYPES .
      " SET type = '%s', name = '%s', description = '%s', arguments = '%s' " .
      " WHERE id = %s",
      $data['core']['type'],
      $data['core']['label'],
      $data['core']['description'],
      json_encode($data),
      $data['core']['id']
    );

    $wpdb->query($sql);
  }

  /**
   * get a specific post type using the post_type_id or the post_type_name
   *
   * @param mixed  post_type, can be a integer or a string
   * @todo get_post_type fails when the post type is "page" or "post'
   * @return array
   */
  public function get_post_type($post_type){
    global $wpdb;
    
    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_POSTTYPES." WHERE type = %s", array( $post_type ) );

    $post_type = $wpdb->get_row( $query, ARRAY_A );
    if($post_type){
      $post_type_id = $post_type['id'];
      $post_type = json_decode($post_type['arguments'],true);
      $post_type['core']['id'] = $post_type_id;
      return $post_type;
    }
    return false;
  }

  /**
   * delete a post type
   */
  public function delete_post_type(){
    global $wpdb;

    //checking the nonce
    check_admin_referer('delete_post_type_mf_posttype');

    if( isset($_GET['post_type']) ){
      $post_type = $_GET['post_type'];

      if( $post_type ){
        $sql = sprintf(
          "DELETE FROM " . MF_TABLE_POSTTYPES .
          " WHERE type = '%s'",
          $post_type
        );
        $wpdb->query($sql);
        
        //delete all groups of post_type
        $sql_fields = sprintf("DELETE FROM %s WHERE post_type = '%s'",MF_TABLE_CUSTOM_GROUPS,$post_type);
        $wpdb->query($sql_fields);
        
        //delete field of post_type
        $sql_fields = sprintf("DELETE FROM %s WHERE post_type = '%s'",MF_TABLE_CUSTOM_FIELDS,$post_type);
        $wpdb->query($sql_fields);
        
        $this->mf_redirect(null,null,array('message' => 'success'));
      }
    }
  }
  
  public function check_post_type($post_type,$id = NULL){
    global $wpdb;
  
    $query = sprintf("SELECT COUNT(*) FROM %s WHERE type = '%s'",MF_TABLE_POSTTYPES,$post_type);
    if($id)
      $query = sprintf("%s AND id != %s",$query,$id);
      
    $check = $wpdb->get_var($query);
    return $check;
  }

}
