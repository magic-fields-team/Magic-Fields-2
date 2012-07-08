<?php

/**
 * In this page be added the metaboxes into the posts
 * and be added the hooks for save the data of each post
 */
class mf_post extends mf_admin {

  function __construct() {
    //creating metaboxes
    add_action( 'add_meta_boxes', array( &$this, 'mf_post_add_metaboxes' ));

    //save data
    add_action( 'save_post', array( &$this, 'mf_save_post_data' ) );
  }

  /**
   * Adding the metaboxes
   */
  function mf_post_add_metaboxes() {
    global $post,$mf_post_values;
  
    //if the user are going to add a new link 
    //the var $post is not defined and we do nothing
    if(!isset($post)) {
      return false;
    }
 
    $mf_post_values = $this->mf_get_post_values($post->ID);

    //Getting the post types
    $post_types = $this->mf_get_post_types( array('public' => true ), 'names'  );

    foreach ( $post_types as $post_type ){
      if ( post_type_supports($post_type, 'page-attributes') && $post_type != 'page' ) {
        // If the post type has page-attributes we are going to add
        // the meta box for choice a template by hand
        // this is because wordpress don't let choice a template
        // for any non-page post type
        add_meta_box(
          'mf_template_attribute',
          __('Template'),
          array( &$this, 'mf_metabox_template' ),
          $post_type,
          'side',
          'default'
        );
      }

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
    global $mf_domain, $mf_post_values;

    //Getting the custom fields for this metabox
    $custom_fields = $this->get_custom_fields_by_group($metabox['args']['group_info']['id']);
    $group_id = $metabox['args']['group_info']['id'];
    //default markup
    ?>
    <div class="mf-group-wrapper group-<?php print $group_id;?>" id="mf_group-<?php print $group_id; ?>" >
      <!-- grupos se puede repetir -->
      <?php
        $extraclass = "";
        if( $metabox['args']['group_info']['duplicated'] ) {
          $extraclass = "mf_duplicate_group";
          $repeated_groups = $this->mf_get_duplicated_groups( $post->ID, $group_id );
        } else {
          $repeated_groups = 1;
        }

        for( $group_index = 1; $group_index <= $repeated_groups; $group_index++ ){
          $only = ($repeated_groups == 1)? TRUE : FALSE;
          $this->mf_draw_group($metabox,$extraclass,$group_index,$custom_fields,$mf_post_values,$only);
        }
        printf('<input value="%d" id="mf_group_counter_%d" style="display:none" >',$repeated_groups,$group_id);
      ?>
      <!-- fin del grupo -->
    </div>
  <?php
  }

  public function mf_draw_group($metabox,$extraclass = '',$group_index = 1 ,$custom_fields = array() ,$mf_post_values = array(),$only_group = FALSE){
    global $post;
    $id = sprintf('mf_group_%s_%s',$metabox['args']['group_info']['id'], $group_index);
    $group_id = $metabox['args']['group_info']['id'];
    $delete_id = sprintf('delete_group_repeat-%d_%d',$group_id,$group_index);
    $add_id = sprintf('mf_group_repeat-%s_%s',$group_id,$group_index);
    $group_style = 'style="display: none;"';
   ?>
    <div class="mf_group <?php print $extraclass; ?>" id="<?php print $id; ?>">
       <!-- campos del grupo (por cada campo) -->
       <?php foreach( $custom_fields as $field ):?>
         <!-- si el campo se puede duplicar deberia estar esto N veces -->
         <?php
           $field_class = '';
    if($field['duplicated'] && isset($post->ID) ){
             $repeated_field = $this->mf_get_duplicated_fields_by_group($post->ID, $field['name'],$group_id,$group_index);
           }else{
             $repeated_field = 1;
           }

           $group_field = sprintf('mf_group_field_%d_%d_%d',$group_id,$group_index,$field['id']);
           print '<div class="mf-field" id="'.$group_field.'" >';
           for( $field_index = 1; $field_index <= $repeated_field; $field_index++ ){
             $only = ($repeated_field == 1)? TRUE : FALSE;
             $this->mf_draw_field($field,$group_id,$group_index,$field_index,$mf_post_values, $only);
           }
           printf('<input value="%d" id="mf_counter_%d_%d_%d" style="display:none" >',$repeated_field,$group_id,$group_index,$field['id']);
           print '</div>';
         ?>
         <!-- fin de campo duplicado -->
       <?php endforeach;?>
       <!-- fin del campo -->
       <?php if($metabox['args']['group_info']['duplicated']): ?>
          <div class="mf_toolbox">
             <span class="mf-counter sortable-mf"><?php print $group_index; ?></span>
             <span class="hndle sortable_mf row_mf">&nbsp;</span>
             <span class="mf_toolbox_controls">
               <a class="duplicate_button" id="<?php print $add_id; ?>" href="javascript:void(0);"><span>Add Another</span> <?php echo $metabox['args']['group_info']['label']; ?></a>
                                                                                                                                                                                      <a class="delete_duplicate_button"  id="<?php print $delete_id; ?>" href="javascript:void(0);" <?php if($only_group) print $group_style; ?> ><span>Remove</span> <?php echo $metabox['args']['group_info']['label']; ?></a>
             </span>
          </div>
       <?php endif; ?>
    </div>
   <?php
  }

  public function mf_draw_field($field,$group_id,$group_index =1,$field_index =1 , $mf_post_values = array(),$only = FALSE ){
    global $mf_domain;

    $id = sprintf('mf_field_%d_%d_%d_%d_ui',$group_id,$group_index,$field['id'],$field_index);
    $delete_id = sprintf('delete_field_repeat-%d_%d_%d_%d',$group_id,$group_index,$field['id'],$field_index);
    $add_id = sprintf('mf_field_repeat-%d_%d_%d_%d',$group_id,$group_index,$field['id'],$field_index);
    $field_style = ($field_index == 1)? 'style="display: none; "' : ''; 

    $name = sprintf('field-%s',$field['name']);
    $tool = sprintf('<small class="mf_tip"><em>%s</em><span class="mf_helptext">%s</span></small>',__( 'What\'s this?', $mf_domain ),'%s');
    $help = ($field['description'])? sprintf($tool,$field['description']) : '';
    $required = ($field['required_field'])? ' <span class="required">*</span>' : '';
    $value =  (!empty($mf_post_values[$field['name']][$group_index][$field_index])) ? $mf_post_values[$field['name']][$group_index][$field_index] : '';
    ?>
      <div class="mf-field-ui <?php print $name;?>" id="<?php print $id;?>">
         <div>
           <?php
         $field_num = sprintf(' <em %s>(<span class="mf-field-count">%d</span>)</em> ',$field_style,$field_index);
             print sprintf('<div class="mf-field-title"><label><span class="name" >%s%s%s</span>%s</label></div>',$field['label'],$field_num,$required,$help);
             $f = $field['type'].'_field';
             $f = new $f();
             print '<div class="clear"></div><div>';

             $field['input_name'] = sprintf("magicfields[%s][%d][%d]",$field['name'],$group_index,$field_index);
             $field['input_id'] = sprintf("%s_%s_%s",$field['name'],$group_index,$field_index);
             $field['input_value'] = $value;
             $field['options'] = unserialize($field['options']);
             $field['input_validate'] = ($field['required_field']) ? 'validate="required:true"' : '';

             print $f->display_field( $field,$group_index, $field_index);
             print '</div><div class="clear"></div>';
             if( $field['required_field'] ){
               $id_validate = array('image_media','image','audio','file','color_picker','datepicker','markdown_editor','multiline');
               $validate_name = ( in_array($field['type'],$id_validate) )? $field['input_id'] : $field['input_name'];
               if($field['type'] == 'color_picker') $validate_name = 'colorpicker_value_'.$validate_name;
               if($field['type'] == 'datepicker' ){
                 $validate_name = 'date_field_' . $validate_name;
               }
               if( isset($field['options']['options']) && $field['type'] != 'radiobutton_list' ) $validate_name .= '[]';
               print '<div class="mf_message_error"><label for="' . $validate_name. '" class="error_magicfields error">';
               _e("This field is required",$mf_domain);
               print '</label></div>';
             }
             ?>
         </div>
         <?php if( $field['duplicated'] ) :?>
           <div class="mf-duplicate-controls">
             <a href="javascript:void(0);" id="<?php print $add_id; ?>" class="duplicate-field"> <span>Add Another</span> <?php echo $field['label']; ?></a>
             <a href="javascript:void(0);" id="<?php print $delete_id; ?>" <?php if($only) print $field_style; ?> class="delete_duplicate_field"><span>Remove</span> <?php echo $field['label']; ?></a>
           </div>
         <?php endif;?>
      </div>
    <?php
  }

  public function mf_ajax_duplicate_field($group_id,$group_index,$field_id,$field_index){
    $field = $this->get_custom_field($field_id);
    $this->mf_draw_field($field,$group_id,$group_index,$field_index);
  }

  public function mf_ajax_duplicate_group($group_id,$group_index){
    $group = $this->get_group($group_id);
    $metabox = array(
      'args' => array(
        'group_info' => $group
      )
    );
    $custom_fields = $this->get_custom_fields_by_group($group_id);
    $this->mf_draw_group($metabox,'mf_duplicate_group',$group_index,$custom_fields);
  }


  /** When the post is saved, saves our custom data **/
  function mf_save_post_data( $post_id ) {
    global $wpdb;

    //@todo hay que ponerle nonce a una de las metaboxes
    /*if ( !wp_verify_nonce( $_POST['myplugin_noncename'], plugin_basename(__FILE__) ) ) {*/
      //return $post_id;
    /*}*/

    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;

     //just in case if the post_id is a post revision and not the post inself
    if ( $the_post = wp_is_post_revision( $post_id ) ) {
      $post_id = $the_post;
    }

    // Check if the post_type has page attributes
    // if is the case is necessary need save the page_template
    if ($_POST['post_type'] != 'page' && isset($_POST['page_template'])) {
      add_post_meta($post_id, '_wp_mf_page_template', $_POST['page_template'], true) or update_post_meta($post_id, '_wp_mf_page_template', $_POST['page_template']);
    }

    if (!empty($_POST['magicfields'])) {

      $customfields = $_POST['magicfields'];

      /** Deleting the old values **/
            $wpdb->query( "DELETE FROM ". MF_TABLE_POST_META ." WHERE post_id= {$post_id}" );
      foreach ( $customfields as $field_name => $field ) {
        delete_post_meta($post_id, $field_name);
      }
      /** / Deleting the old values **/

      //creating the new values
      foreach( $customfields as $field_name => $groups ) {

        $group_count = 1;
        foreach( $groups as $fields ) {
          $field_count = 1;
          foreach( $fields as $value ) {
            //here if the value of the field needs a process before to be saved
            //should be trigger that method here
            //$value =  mf_process_value_by_type($field_name,$value);

            // Adding field value meta data
            add_post_meta($post_id, "{$field_name}", $value);

            $meta_id = $wpdb->insert_id;

            $wpdb->query("INSERT INTO ". MF_TABLE_POST_META." ( meta_id, field_name, field_count, group_count, post_id ) ".
              " VALUES ( {$meta_id}, '{$field_name}' , {$field_count},{$group_count} ,{$post_id} )"
            );
            $field_count++;
          }
          $group_count++;
        }
      }
    }
  }

  /**
   *
   * @param int $post_id  the post id
   * @param int $group_id the group_id
   * @return int
   */
  function mf_get_duplicated_groups( $post_id, $group_id ) {
    global $wpdb;

    $group_count =  $wpdb->get_var(
      "SELECT
        mfpm.group_count
      FROM
        ".MF_TABLE_POST_META." AS mfpm
      LEFT JOIN
        ".MF_TABLE_CUSTOM_FIELDS." AS mfcf ON ( mfpm.field_name = mfcf.name)
      WHERE
        mfpm.post_id  = {$post_id}
      AND
        custom_group_id = {$group_id}
      ORDER BY
        group_count DESC
      LIMIT 1"
    );

    return ($group_count > 1) ? $group_count : 1;
  }

  /**
   *
   * @param int $post_id  the post id
   * @param int $group_id the group_id
   * @param int $group_index (group_count)
   * @return int
   */
  function mf_get_duplicated_fields_by_group( $post_id,$field_name, $group_id , $group_index ) {
    global $wpdb;

    $field_count =  $wpdb->get_var(
    "SELECT
      mfpm.field_count
    FROM
      ".MF_TABLE_POST_META." AS mfpm
    LEFT JOIN
      ".MF_TABLE_CUSTOM_FIELDS." AS mfcf ON ( mfpm.field_name = mfcf.name)
    WHERE
      mfpm.post_id  = {$post_id}
    AND
      custom_group_id = {$group_id}
    AND
      mfpm.group_count = {$group_index}
    AND
      mfpm.field_name = '{$field_name}'
    ORDER BY
      field_count DESC
    LIMIT 1"
    );
    return ($field_count > 1) ? $field_count : 1;
  }


  /**
   * retrieve the custom fields values of a certain post
   */
  function mf_get_post_values( $post_id ) {
    global $wpdb;

    $raw = $wpdb->get_results(
      "SELECT
        mfpm.meta_id,
        mfpm.field_name,
        mfpm.field_count,
        mfpm.group_count,
        pm.meta_value
      FROM
        ".MF_TABLE_POST_META." as mfpm
      LEFT JOIN
       ".$wpdb->postmeta." as pm
      ON
        ( mfpm.meta_id = pm.meta_id )
      WHERE
        mfpm.post_id = ".$post_id
    );

    $data = array();

    foreach( $raw as $key => $field ){
      $data[$field->field_name][$field->group_count][$field->field_count] = $field->meta_value;
    }

    return $data;
  }

  /* enqueue css and js base for post area*/
  public function load_js_css_base(){
    global $mf_domain;

    wp_enqueue_style( 'mf_field_base', MF_BASENAME.'css/mf_field_base.css' );
    wp_enqueue_script( 'tmpl', MF_BASENAME.'js/third_party/jquery.tmpl.js');       
    wp_enqueue_script( 'mf_field_base', MF_BASENAME.'js/mf_field_base.js'); 
    wp_enqueue_script( 'mf_sortable_groups', MF_BASENAME.'js/mf_sortable_groups.js', array( 'jquery-ui-sortable' ) );

    //global mf js
    $js_vars = array(
      'mf_url' => MF_BASENAME,
      'mf_player_url' => MF_BASENAME . 'js/singlemp3player.swf',
      'mf_validation_error_msg' => __('Sorry, some required fields are missing. Please provide values for any highlighted fields and try again.',$mf_domain),
      'mf_image_media_set' => __('Insert into field',$mf_domain)
    );
    wp_localize_script( 'mf_field_base', 'mf_js', $js_vars );    
    
  }

  /* enqueue css and js of fields */
  public function load_js_css_fields(){
    
    //Loading any custom field  if is required 
    if( !empty( $_GET['post']) && is_numeric( $_GET['post'] ) ) {//when the post already exists
      $post_type = get_post_type($_GET['post']);   
    }else{ //Creating a new post
      $post_type = (!empty($_GET['post_type'])) ? $_GET['post_type'] : 'post';
    }

    $fields = $this->get_unique_custom_fields_by_post_type($post_type);

    /* add tiny_mce script */
    /* only add of editor support no exits for the post type*/
    if( (in_array('multiline',$fields) || in_array('image_media',$fields) )  && !post_type_supports($post_type,'editor' ) ){
      add_thickbox();
      wp_enqueue_script('media-upload');
      wp_enqueue_script('editor'); // load admin/mf_editor.js (switchEditor)
      mf_autoload('mf_tiny_mce'); // load admin/mf_tiny_mce.php (tinyMCE)
      add_action( 'admin_print_footer_scripts', 'mf_tiny_mce', 25 ); // embed tinyMCE
      add_action( 'admin_print_footer_scripts', array($this, 'media_buttons_add_mf'), 51 );
    }

    foreach($fields as $field) {
      //todo: Este método debería también de buscar en los paths donde los usuarios ponen sus custom fields
      $type = $field."_field";
      $type = new $type();
      $properties = $type->get_properties();
         
      if ( $properties['js'] ) {
        wp_enqueue_script(
          'mf_field_'.$field,
          MF_BASENAME.'field_types/'.$field.'_field/'.$field.'_field.js',
          $properties['js_dependencies'],
          null,
          true
        );
            
        /* idear forma por si se necesita mas de dos js*/
        if( isset($properties['js_internal']) ){
          wp_enqueue_script(
            'mf_field_'. preg_replace('/\./','_',$properties['js_internal']),
            MF_BASENAME.'field_types/'.$field.'_field/'.$properties['js_internal'],
            $properties['js_internal_dependencies'],
            null,
            true
          );
        }
      }

      if ( $properties['css'] ) {
        wp_enqueue_style( 
          'mf_field_'.$field,
          MF_BASENAME.'field_types/'.$field.'_field/'.$field.'_field.css'
        );
      }
      
      if ( !empty($properties['css_dependencies'] )) {
        foreach($properties['css_dependencies'] as $css_script) {
          wp_enqueue_style($css_script);
        }
      }
          
      /* load css internal */
      if(isset($properties['css_internal'])){
        wp_enqueue_style( 
          'mf_field_'.preg_replace('/\./','_',$properties['css_internal']),
          MF_BASENAME.'field_types/'.$field.'_field/'.$properties['css_internal']
        );
      }
    }
  }
  
  public function media_buttons_add_mf(){
    
    print '<div style="display:none;">';
    do_action( 'media_buttons' );
    print '</div>'; 
  }
  
  public function register_media_button($buttons) {
    array_push($buttons, "separator","add_image","add_video","add_audio","add_media");
    return $buttons;
  }
  
  public function tmce_not_remove_p_and_br(){
    ?>
    <script type="text/javascript">
      //<![CDATA[ 
      jQuery('body').bind('afterPreWpautop', function(e, o){
          o.data = o.unfiltered
            .replace(/caption\]\[caption/g, 'caption] [caption')
            .replace(/<object[\s\S]+?<\/object>/g, function(a) {
                        return a.replace(/[\r\n]+/g, ' ');
        });
        }).bind('afterWpautop', function(e, o){
          o.data = o.unfiltered;
        });
    //]]>
    </script>
    <?php
  }
  
  public function general_option_multiline(){
    
    /* load aditional options for multiline */
    add_filter('mce_buttons', array($this,'register_media_button'));
    
    if( mf_settings::get('dont_remove_tags') == '1'){
       add_action( 'admin_print_footer_scripts', array($this,'tmce_not_remove_p_and_br'), 50 );
    }
    
  }

	public function categories_of_post_type(){
		
		global $wpdb;
		$assignedCategoryIds =  array();
		
		if( count($_GET) == 0){ $_GET['post_type'] = 'post'; }
		
		if (isset($_GET['post_type'])) {
			$post_type_key = sprintf('_cat_%s',$_GET['post_type']);
				
			$sql ="SELECT meta_value FROM ".$wpdb->postmeta." WHERE meta_key='".$post_type_key."' ";
			$check = $wpdb->get_row($sql);
			if ($check) {
				$cata = $check->meta_value;
				$assignedCategoryIds = maybe_unserialize($cata);
			}
		}
		
	
		?>
		<script type="text/javascript">
			var mf_categories = new Array(<?php echo '"'.implode('","',$assignedCategoryIds).'"' ?>); 
			jQuery(document).ready(function($) {

			  if(mf_categories.length == 1 && mf_categories[0] == "" ){

			  }else{
			    $.each(mf_categories, function(key,value) {
			      $("#in-"+value).attr('checked','checked');
			    });
			  }

			});
		</script>
		<?php
	}
	public function set_categories(){
		
		add_action( 'admin_print_footer_scripts', array($this,'categories_of_post_type'), 50 );
	}
 

  //MF Meta box for select template
  function mf_metabox_template () {
    global $post;
    
    if ( 0 != count( get_page_templates() ) ) {

      $template = get_post_meta($post->ID, '_wp_mf_page_template', TRUE);
      $template =  ($template != '') ? $template : false;
    ?>
      <label class="screen-reader-text" for="page_template"><?php _e('Page Template') ?></label><select name="page_template" id="page_template">
      <option value='default'><?php _e('Default Template'); ?></option>
      <?php page_template_dropdown($template); ?>
      </select>
    <?php  
    }
  }
}