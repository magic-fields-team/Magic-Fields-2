<?php

/**
 * Dashboard
 *
 * Display, add, edit, delete post types
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

    //if be editing the post type we can't modify the post type name
    $type_readonly = FALSE;
    if( $_GET['mf_action']  == 'edit_post_type' ) {
      $type_readonly = TRUE;
    }
    
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
        'label' => array(
          'id' => 'posttype-label',
          'type' => 'text',
          'label' => __( 'Label', $mf_domain ),
          'name' => 'mf_posttype[core][label]',
          'value' => '',
          'description' => __( 'Singular label of the post type.', $mf_domain ),
          'class' => "{validate:{required:true,messages:{required:'". __('This field is required',$mf_domain)."'}}}",
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
        'type' => array(
          'id' => 'posttype-type',
          'type' => 'text',
          'label' => __( 'Type name', $mf_domain ),
          'name' => 'mf_posttype[core][type]',
          'value' => '',
          'description' => __( 'Used by the system, the type must have less than 20 characters, only lowercase alphanumeric characters and undescore is accepted. Once the post type is created, the type name cannot be changed.', $mf_domain ),
          'class' => "{ validate:{ required:true, maxlength:20, lowercase:true, messages:{ lowercase:'".__( 'Only lowercase alphanumeric characters and underscore is accepted' )."', required:'".__( 'This field is required', $mf_domain )."', maxlength:'".__( 'This field must have less than 20 characters' )."' }}}",
          'div_class' => 'form-required',
          'readonly'   => $type_readonly
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
        ),
        'quantity' => array(
          'id' => 'posttype-quantity',
          'type' => 'checkbox',
          'label' => __( 'Quantity', $mf_domain ),
          'name' => 'mf_posttype[core][quantity]',
          'value' => 0,
          'description' => __( 'mark true if you want your post type only has one element.', $mf_domain )
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
          'description' => __( 'Capability type (post,page) in Singular', $mf_domain ),
          'class' => "{ validate:{ maxlength:40, lowercase:true, messages:{ lowercase:'".__( 'Only  are accepted lowercase characters,numbers or underscores' )."',  maxlength:'".__( 'This Field must have less than 40 characters' )."' }}}",
          'div_class' => 'form-required'
        ),
        'hierarchical' => array(
          'id' => 'posttype-hierarchical',
          'type' => 'checkbox',
          'label' => __( 'Hierarchical', $mf_domain ),
          'name' => 'mf_posttype[option][hierarchical]',
          'value' => 0,
          'description' => __( 'Whether the post type is hierarchical. Allows Parent to be specified', $mf_domain )
        ),
        'has_archive' => array(
            'id' => 'posttype-has-archive',
            'type' => 'checkbox',
            'label' => __( 'Has archive', $mf_domain ),
            'name' => 'mf_posttype[option][has_archive]',
            'value' => 0,
            'description' => __( 'Enables post type archives. Will use string as archive slug. Will generate the proper rewrite rules if rewrite is enabled.', $mf_domain )
        ),
        'has_archive_slug' => array(
            'id' => 'posttype-has-archive-slug',
            'type' => 'text',
            'label' => __( 'Archive slug', $mf_domain ),
            'name' => 'mf_posttype[option][has_archive_slug]',
            'value' => '',
            'description' => __( 'Archive slug. The archive for the post type can be viewed at this slug. Has archives must be checked for this to work.', $mf_domain )
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
        'with_front' => array(
          'id'  => 'posttype-with-front',
          'type' => 'checkbox',
          'label' => __( 'With front' ),
          'name' => 'mf_posttype[option][with_front]',
          'value' => 1,
          'description' => __( 'Should the permastruct be prepended with the front base. (example: if your permalink structure is /blog/, then your links will be: false->/news/, true->/blog/news/)', $mf_domain )
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
          'rel' => '$p' // plural, the sign is rather $ than %, because it's more like a variable rather than a sprintf type
        ),
        'singular_name' => array(
          'id' => 'posttype-label-singular-name',
          'type' => 'text',
          'label' => __( 'Singular name', $mf_domain ),
          'name' => 'mf_posttype[label][singular_name]',
          'value' => __('Post',$mf_domain),
          'description' => __( 'Name for one object of this post type. Defaults to value of name.', $mf_domain ),
          'rel' => '$s' // singular
        ),
        'add_new' => array(
          'id' => 'posttype-label-add-new',
          'type' => 'text',
          'label' => __( 'Add new', $mf_domain ),
          'name' => 'mf_posttype[label][add_new]',
          'value' => __('Add New',$mf_domain),
          'description' => __( 'General name for the post type, usually plural.', $mf_domain ),
          'rel' => 'Add $s'
        ),
        'all_items' => array(
          'id' => 'posttype-label-all-items',
          'type' => 'text',
          'label' => __( 'All', $mf_domain ),
          'name' => 'mf_posttype[label][all_items]',
          'value' => __('All',$mf_domain),
          'description' => __( 'The all items text used in the menu. Default is the Name label.', $mf_domain ),
          'rel' => 'All $p'
        ),
        'add_new_item' => array(
          'id' => 'posttype-label-add-new-item',
          'type' => 'text',
          'label' =>  __( 'Add new item', $mf_domain ),
          'name' => 'mf_posttype[label][add_new_item]',
          'value' => __('Add New Post',$mf_domain),
          'description' => __( 'The add new item text.', $mf_domain ),
          'rel' => 'Add New $s'
        ),
        'edit_item' => array(
          'id' => 'posttype-label-edit-item',
          'type' => 'text',
          'label' => __( 'Edit item', $mf_domain ),
          'name' => 'mf_posttype[label][edit_item]',
          'value' => __('Edit Post',$mf_domain),
          'description' => __( 'General name for the post type, usually plural.', $mf_domain ),
          'rel' => 'Edit $s'
        ),
        'new_item' => array(
          'id' => 'posttype-label-new-item',
          'type' => 'text',
          'label' => __( 'New item', $mf_domain ),
          'name' => 'mf_posttype[label][new_item]',
          'value' => __('New Post',$mf_domain),
          'description' => __( 'The new item text.', $mf_domain ),
          'rel' => 'New $s'
        ),
        'view_item' => array(
          'id' => 'posttype-label-view-item',
          'type' => 'text',
          'label' => __( 'View item', $mf_domain ),
          'name' => 'mf_posttype[label][view_item]',
          'value' => __('View Post',$mf_domain),
          'description' => __( 'The view item text.', $mf_domain ),
          'rel'   => 'View $s'
        ),
        'search_items' => array(
          'id' => 'posttype-label-search-items',
          'type' => 'text',
          'label' => __( 'Search items', $mf_domain ),
          'name' => 'mf_posttype[label][search_items]',
          'value' => __('Search Posts',$mf_domain),
          'description' => __( 'The search items text.', $mf_domain ),
          'rel' =>  'Search $p'
        ),
        'not_found' => array(
            'id' => 'posttype-label-not-found',
            'type' => 'text',
            'label' => __( 'Not found', $mf_domain ),
            'name' => 'mf_posttype[label][not_found]',
            'value' => __('No %s found',$mf_domain),
            'description' => __( 'The not found text. Default is No posts found/No pages found', $mf_domain ),
            'rel' =>  'No $p found'
        ),
        'not_found_in_trash' => array(
          'id' => 'posttype-label-not-found-in-trash',
          'type' => 'text',
          'label' => __( 'Not found in trash', $mf_domain ),
          'name' => 'mf_posttype[label][not_found_in_trash]',
          'value' => __('No posts found in Trash',$mf_domain),
          'description' => __( 'the not found in trash text.', $mf_domain ),
          'rel' =>  'No $p found in Trash'
        ),
        'parent_item_colon' => array(
            'id'          => 'posttype-label-parent-item-colon',
            'type'        => 'text',
            'label'       => __( 'Parent item colon', $mf_domain ),
            'name'        => 'mf_posttype[label][parent_item_colon]',
            'value'       => __('Parent Item:',$mf_domain),
            'description' => __( 'The same as parent_item, but with colon:', $mf_domain ),
            'rel'         => __('Parent',$mf_domain) . ' $s:'
        ),
        'menu_name' => array(
          'id' => 'posttype-label-menu_name',
          'type' => 'text',
          'label' => __( 'Menu name', $mf_domain ),
          'name' => 'mf_posttype[label][menu_name]',
          'value' => __('Post',$mf_domain),
          'description' => __( 'The name of menu, usually plural.', $mf_domain ),
          'rel' =>  '$p'
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

    global $mf_domain;
    
    $supports = array(
      'title','editor','author',
      'thumbnail','excerpt','trackbacks',
      'custom-fields','comments','revisions',
      'page-attributes'
    );

    print '<div class="wrap">';
    print '<div id="message_post_type" class="error below-h2" style="display:none;"><p></p></div>';
    print '<div id="icon-edit" class="icon32"><br /></div>';
    if( !$data['core']['id']['value'] ):
      print '<h2>'.__( 'Add Post Type', $mf_domain ).'</h2>';
    else:
      printf( "<h2>%s - %s</h2>",__('Edit Post Type'),$data['core']['label']['value'] );
    endif;
    print '</div>';
    ?>
     <form id="addPostType" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=save_post_type" class="validate mf_form_admin">

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
  <?php elseif($core['type'] == 'checkbox'): ?>
    <div class="form-field mf_form <?php echo $core['div_class']; ?>">
    <?php   mf_form_checkbox($core); ?>
    </div>
	<?php endif; ?>
	<?php endforeach; ?>
	<!-- / core -->

	<!-- supports -->
	<div class="form-field mf_label_checkbox mf_form">
	  <?php _e( 'Supports', $mf_domain ) ?>
	  <?php foreach($supports as $support){ ?>
	    <?php
	      $check = '';
	      if(in_array($support,$data['posttype_support'])){
		$check = 'checked="checked"';
	      }
	    ?>
	    <p>
	    <input name="mf_posttype[support][<?php echo $support; ?>]" id="posttype-support-<?php echo $support; ?>" value="1" type="checkbox" <?php echo $check; ?> >
	    <label for="posttype-support-<?php echo $support; ?>"><?php echo preg_replace('/-/',' ',$support); ?></label>
	    </p>
	  <?php } ?>
	</div>
	<!-- / supports -->

	<!-- taxonomies -->
	<div class="form-field mf_label_checkbox mf_form">
	  <?php _e( 'Taxonomies', $mf_domain ) ?>
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
	<p>
		<?php $cat_post_type = (isset($_GET['post_type'])) ? $_GET['post_type']: ''; ?>
		<a href="admin.php?page=mf_dispatcher&init=false&mf_section=mf_posttype&mf_action=set_categories&post_type=<?php echo $cat_post_type;?>&TB_iframe=1&width=640&height=541" title="default categories" class="thickbox" onclick="return false;" >set default categories</a>
		</p>
	</div>
	<!-- / taxonomies -->

	<!-- Submit -->
	<p class="submit">
	  <a style="color:black" href="admin.php?page=mf_dispatcher" class="button">Cancel</a>
	  <input type="submit" class="button button-primary" name="submit" id="submit" value="Save Post type">
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
    </div>
     </form>
    <?php
  }

	public function set_categories(){
		global $wpdb;
		$post_type = $_GET['post_type'];

		if(!$post_type){
			echo "<h3>is necessary that the post type is created</h3>";
		}else{
			
		$all_taxonomies = get_object_taxonomies($post_type,'object');
		$is_type_categorie = array();
		foreach($all_taxonomies as  $cat){
			if($cat->hierarchical == '1'){
				array_push($is_type_categorie,$cat->name);
			}
		}
	//	pr($is_type_categorie);
		$customCategoryIds = array();
		
		$post_type_key = sprintf('_cat_%s',$post_type);
		$sql ="SELECT meta_value FROM ".$wpdb->postmeta." WHERE meta_key='".$post_type_key."' ";
		$check = $wpdb->get_row($sql);
		
		if ($check) {
			$cata = $check->meta_value;
			$customCategoryIds = maybe_unserialize($cata);
		}
		
		echo '<input type="hidden" id="post_type_name" value="'.$post_type.'"> ';
		echo '<div id="default-cats">';
		echo '<div id="resp" style="color: #39A944; display:none;">changes have been saved successfully</div>';
		foreach($is_type_categorie as $name){
			echo "<h3>".$name.'</h3>';
			echo "<div>";
			$taxonomy = 'category';
			$term_args=array(
			  'hide_empty' => false,
			  'orderby' => 'term_group',
			  'order' => 'ASC'
			);
			$termsOfCategory = get_terms($name,$term_args);
			$this->PrintNestedCats( $termsOfCategory, 0, 0, $customCategoryIds );
			echo "</div>";
		}
		
		
		echo '<p class="submit">';
		  
		echo  '<input type="submit" class="button button-primary" name="submit" id="send_set_categories" value="Save categories">';
		echo '</p>';
		
		echo '</div>';
		
	}
	
			

	}
	
	
	private function PrintNestedCats( $cats, $parent = 0, $depth = 0, $customCategoryIds ) {
		foreach ($cats as $cat) : 
			if( $cat->parent == $parent ) {
				$checked = "";
				
				if (@in_array($cat->taxonomy . "-" .$cat->term_id, $customCategoryIds))
				{
					$checked = "checked=\"checked\"";
				}
				echo str_repeat('&nbsp;', $depth * 4);
?>					<input type="checkbox" name="custom-write-panel-categories[]" class="dos" value="<?php echo $cat->taxonomy . "-" .$cat->term_id?>" <?php echo $checked?> /> <?php echo $cat->name ?> <br/>
<?php				
			$this->PrintNestedCats( $cats, $cat->term_id, $depth+1, $customCategoryIds );
			}
		endforeach;
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
      $name = trim($mf['option']['capability_type']);
      if( !in_array($name,array('post','page')) && !empty($name) ){
        //register capabilities for admin
        $this->_add_cap($name);
      }

    }
		$this->mf_redirect("mf_posttype","update_rewrite",array("noheader" => "true"));
  }

	public function update_rewrite() {
		flush_rewrite_rules(false);
		$this->mf_redirect(null,null,array('message' => 'success'));
		die;
	}
	

  /**
   * Add a news Capabilities for Administrator
   *
   */
  public function _add_cap($name){

    $caps = array(
      'publish_posts'      => sprintf('publish_%ss',$name),
      'edit_posts'         => sprintf('edit_%ss',$name),
      'edit_others_posts'  => sprintf('edit_others_%ss',$name),
      'read_private_posts' => sprintf('read_private_%ss',$name),
      'edit_post'          => sprintf('edit_%s',$name),
      'delete_post'        => sprintf('delete_%s',$name),
      'read_post'          => sprintf('read_%s',$name)
    );
    $role = get_role('administrator');

    if( !in_array($caps['edit_post'],array_keys($role->capabilities)) ){
      foreach($caps as $cap){
        $role->add_cap($cap);
      }
    }
    
  }  

  /**
   * get a specific post type using the post_type_id or the post_type_name
   *
   * @param mixed  post_type, can be a integer or a string
   * @return array
   */
  public function get_post_type($post_type){
    global $wpdb;
    
    $query = $wpdb->prepare( "SELECT * FROM ".MF_TABLE_POSTTYPES." WHERE type = %s", array( $post_type ) );

    $post_type = $wpdb->get_row( $query, ARRAY_A );
    if($post_type){
      $post_type_id = $post_type['id'];
      $post_type = unserialize($post_type['arguments']);
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

    if( in_array($post_type,array('post','page') ) )
      $check = 1;

    return $check;
  }

  public function export_post_type(){
    global $mf_pt_register;

    if(!isset($_GET['post_type']) ){
      $this->mf_flash( 'Oops! something was wrong, you will be redirected a safe place in a few seconds' );
    }

    //post_type_exists
    
    $post_type = $_GET['post_type'];
    $data = array(
      'name'      => $post_type,
      'post_type' => array(),
      'groups'    => array(),
      'taxonomy'  => array()
    );

    

    if( in_array($post_type,$mf_pt_register) ){
      $p = $this->get_post_type($post_type);
    }else{
      global $_wp_post_type_features;
      $tmp = get_post_types( array('public' => true,'name' => $post_type) , 'onbject', 'and' );

      $tmp = $tmp[$post_type];

      $rewrite = 0; $rewrite_slug = '';
      if( is_array($tmp->rewrite) ){
        $rewrite = 1;
        $rewrite_slug = $tmp->rewrite['slug'];
      }

      $p = array(
        'core' => array(
          'type'        => $post_type,
          'label'       => $tmp->label,
          'labels'      => $tmp->labels->name,
          'description' => $tmp->description
        ),
        'support' => $_wp_post_type_features[$post_type],
        'option' => array(
          'public'              => ($tmp->public)? 1 : 0,
          'publicly_queryable'  => ($tmp->publicly_queryable)? 1 : 0,
          'exclude_from_search' => ($tmp->exclude_from_search)? 1 : 0,
          'show_ui'             => ($tmp->show_ui)? 1 : 0,
          'show_in_menu'        => ($tmp->show_in_menu)? 1 : 0,
          'menu_position'       => $tmp->menu_position,
          'capability_type'     => $tmp->capability_type,
          'hierarchical'        => ($tmp->hierarchical)? 1 : 0,
          'rewrite'             => $rewrite,
          'rewrite_slug'        => $rewrite_slug,
          'with_front'          => ($tmp->with_front)? 1 : 0,
          'query_var'           => ($tmp->query_var)? 1 : 0,
          'can_export'          => ($tmp->can_export)? 1 : 0,
          'show_in_nav_menus'   => ($tmp->show_in_nav_menus)? 1 : 0
        ),
        'label' => array(
          'name'               => $tmp->labels->name,
          'singular_name'      => $tmp->labels->singular_name,
          'add_new'            => $tmp->labels->add_new,
          'add_new_item'       => $tmp->labels->add_new_item,
          'edit_item'          => $tmp->labels->edit_item,
          'new_item'           => $tmp->labels->new_item,
          'view_item'          => $tmp->labels->view_item,
          'search_items'       => $tmp->labels->search_items,
          'not_found_in_trash' => $tmp->labels->not_found_in_trash,
          'menu_name'          => $tmp->labels->menu_name
        )
      );
    }

    //taxonomy
    $taxs = get_object_taxonomies($post_type);
    if( isset($p['taxonomy']) ){
      foreach($taxs as $tax){
        if( !in_array($tax,array('nav_menu','post_format')) && !in_array($tax,array_keys($p['taxonomy'])) ){
          $p['taxonomy'][$tax] = 1;
        }
      }
    }else{
      foreach($taxs as $tax){
        if( !in_array($tax,array('nav_menu','post_format')) ){
            $p['taxonomy'][$tax] = 1;
        }
      } 
    }
    
    
    if( isset($p['taxonomy']) ){
      foreach($p['taxonomy'] as $tax_name => $t){
        if($custom_taxonomy = $this->get_custom_taxonomy_by_type($tax_name)){
          unset($p['taxonomy'][$tax_name]);
          $data['taxonomy'][] = $custom_taxonomy;
        }
        
      }
    }

    //groups
    $groups = $this->get_groups_by_post_type($post_type);
    foreach($groups as $group_id => $group){
      $fields = $this->get_custom_fields_by_group($group['id']);
      $groups[$group_id]['fields'] = $fields;
    }
    $data['groups'] = $groups;
    
    //post type
    $data['post_type'] = $p;
    
    header('Content-type: binary');
    header('Content-Disposition: attachment; filename="'.$post_type.'.pnl"');
    print serialize($data);
    die;
  }

  public function import_form_post_type(){
    global $mf_domain;
    ?>
    <div class="wrap">
      <div id="message_mf_error" class="error below-h2" style="display:none;"><p></p></div>
      <div id="icon-tools" class="icon32"><br></div>
      <h2><?php _e('Import a Post Type', $mf_domain);?></h2>

      <form id="import_post_type" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=upload_import_post_type" enctype="multipart/form-data">
      <?php wp_nonce_field('nonce_upload_file_import','checking'); ?>
        <div class="alignleft fixed" style="width: 40%;" id="mf_add_custom_group">
          <div class="form-field mf_form">
    <label for="import-file" ><?php _e('File'); ?>:</label>
    <input type="file" id="import-file" name="file" >
    <p><?php _e('File with information about post type',$mf_domain);?></p>
    <div class="clear"></div>
          </div>
          <div class="form-field mf_form ">
            <label for="import_overwrite"><?php _e('Overwrite',$mf_domain); ?></label>
            <input name="mf_post_type[import][overwrite]" id="import_overwrite_" type="hidden" value="0">
            <input name="mf_post_type[import][overwrite" id="import_overwrite" type="checkbox" value="1">
            <div class="clear"></div>
            <p><?php _e('Overwrite existing post type?',$mf_domain); ?> </p>
          </div>
        
      	<p class="submit">
    <a style="color:black" href="admin.php?page=mf_dispatcher" class="button"><?php _e('Cancel',$mf_domain); ?></a>
    <input type="submit" class="button button-primary" name="submit" id="submit" value="<?php _e('Import',$mf_domain); ?>">
      	</p>
      </div>
      <div class="widefat mf_form_right stuffbox metabox-holder">
        <h3><?php _e('Import a Post Type',$mf_domain); ?></h3>
        <div class="inside">
          <div id="options_field" class="group_side">
            <p><?php _e('This functionality allows us to import all the information of a post type',$mf_domain); ?></p>
            <p><?php _e('Also they are imported the groups, custom fields and custom taxonomies that contains the post type',$mf_domain); ?></p>
            <p><?php _e('For defualt to create a new post type, if it exists a post type with the same name was added with a prefix to be able to differentiate it, if the option  overwrite is checked the system overwrite the information of post type and  It will add the custom groups and custom fields to the already existing ones, If some custom group or custom field already this registered It will be overwrite',$mf_domain); ?></p>
            <p><img src="<?php echo MF_URL; ?>images/admin/import.jpg"/></p>
          </div>
        </div>
      </div>
    </div>
</form>
  <?php
  }

  public function upload_import_post_type(){
    global $mf_domain;

    if ( empty($_POST) || !wp_verify_nonce($_POST['checking'],'nonce_upload_file_import') ){
      print 'Sorry, your nonce did not verify.';
      exit;
    }

    if ($_FILES['file']['error'] == UPLOAD_ERR_OK){
      $file_path = $_FILES['file']['tmp_name'];
      $overwrite = $_POST['mf_post_type']['import']['overwrite'];
      $this->import($file_path,$overwrite);
      unlink($filePath);
      $this->mf_redirect(null,null,array('message' => 'success'));
    }else{
      //mensaje de error
      die(__('Error uploading file!', $mf_domain));
    }

    die;

  }

}
