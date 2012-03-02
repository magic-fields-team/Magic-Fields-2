<?php

class mf_custom_fields extends mf_admin {

  //Properties
  public  $css_script = FALSE;
  public  $js_script = FALSE;
  public  $js_dependencies = array();
  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  public $description = '';
  public $options = array();


  public function __construct() {
    $this->_update_description();
    $this->_update_options();
  }

  public function _update_description(){
    global $mf_domain;
    $this->description = __("Base field",$mf_domain);
  }

  public function _update_options(){
    $this->options = $this->_options();
  }


  public function get_properties() {
    $properties['css']              = $this->css_script;
    $properties['js_dependencies']  = $this->js_dependencies;
    $properties['js']               = $this->js_script;

    return $properties;
  }


  public function get_options($options = NULL,$name){
    global $mf_domain;

        print '<div class="desc_field">';
    printf('<p>%s</p>',$this->description);
    printf('<p>%s:</p>',__('Preview',$mf_domain));
    printf('<p><img src="%sfield_types/%s/preview.jpg" /></p>',MF_URL,$name);
    print '</div>';

    if($this->has_properties){
      //aqui deberiamos saber si el campos ya esta en el sistema y pedir los datos actuales
      // por el momento solo renderamos el formulario sin datos
      if($options){
        foreach($options as $k => $v){
          @$this->options['option'][$k]['value'] = $v;
        }
      }
      $this->form_options();  
    }


    return false;
  }

  public function form_options(){

    if(isset($this->options['option'])){
      foreach($this->options['option'] as $option){
        printf('<div class="form-field mf_form %s">',$option['div_class']);
        if($option['type'] == 'text'){
          mf_form_text($option);
        }elseif($option['type'] == 'select'){
          mf_form_select($option);
        }elseif( $option['type'] == 'checkbox' ){
          mf_form_checkbox($option);
        }elseif( $option['type'] == 'textarea' ){
          mf_form_textarea($option);
        }
        printf('</div>');
      }
    }
  }

  /**
   * this is the page where is displayed the list of fields of a certain custom field
   * @return none
   */
  function fields_list() {
    global $mf_domain,$mf_pt_register;

    $post_type =  get_post_type_object($_GET['post_type']);
    $select = array();
    $posttypes = $this->mf_get_post_types();

    print '<div class="wrap">';
    print '<h2>';
    print $post_type->label;
    print '  ';
    
    print '<span style="font-size:small">';
    printf('<a href="admin.php?page=mf_dispatcher&noheader=true&mf_section=mf_posttype&mf_action=export_post_type&post_type=%s ">%s</a>',$post_type->name,__('Export',$mf_domain) );
    print '</span>';
    
    if(in_array($post_type->name,$mf_pt_register)):
      print '<span style="font-size:small">';
    print ' | ';
      printf('<a href="admin.php?page=mf_dispatcher&mf_section=mf_posttype&mf_action=edit_post_type&post_type=%s">Edit</a> | ',$post_type->name);
      $link = "admin.php?page=mf_dispatcher&init=true&mf_section=mf_posttype&mf_action=delete_post_type&post_type={$post_type->name}";
      $link = wp_nonce_url($link,"delete_post_type_mf_posttype");
      printf('<a class="mf-delete mf_confirm" alt="%s" href="%s">Delete</a>',__("This action can't be undone, are you sure?", $mf_domain ),$link);
      print '</span>';
    endif;

    print '<span class="mf-change-pt" >'.__( 'Choose a Post Type', $mf_domain ).' </span>';
    print '<select id="change-post-type" >';
    foreach($posttypes as $p){
      $selected = '';
      if($p->name == $post_type->name ) $selected = 'selected="selected"';
      printf('<option value="%s" %s >%s</option>',$p->name,$selected,$p->label);
    }
    print '</select>';
    print '</h2>';

    print '<p id="post-search" style="margin-top:16px">';
    print '<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_group&mf_action=add_group&post_type='.$post_type->name.'" class="add-new-h2 button">+ '.__( 'Create a Group', $mf_domain ).'</a>';
    print '<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field&post_type='.$post_type->name.'" class="add-new-h2 button">+ '.__( 'Create a Field', $mf_domain ).'</a>';
    print '</p>';


    //list cusmtom field of post type
    $groups = $this->get_groups_by_post_type($post_type->name);

    if( empty( $groups ) ) :
    ?>
      <div class="message-box info">
        <p>
          This post type haven't any custom field yet,  create one <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field&post_type=<?php print $post_type->name;?>">here</a> or
          you can create a group <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_group&mf_action=add_group&post_type=<?php print $post_type->name;?>">here</a>
        </p>
      </div>
    <?php
    endif;
    foreach( $groups as $group):
    $name = $group['label'];
    if($name != 'Magic Fields'){
      $name = sprintf('<a class="edit-group-h2" href="admin.php?page=mf_dispatcher&mf_section=mf_custom_group&mf_action=edit_group&custom_group_id=%s">%s</a>',$group['id'],$name);
    
      $add = sprintf('admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field&post_type=%s&custom_group_id=%s',$post_type->name,$group['id']);

      $name .= sprintf('  <span class="mf_add_group_field">(<a href="%s">create field</a>)</span>',$add);

      $delete_link = 'admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_group&mf_action=delete_custom_group&custom_group_id='.$group['id'];
      $delete_link = wp_nonce_url($delete_link,'delete_custom_group');
      $delete_msg  = __( "This action can't be undone, are you sure?", $mf_domain );

      $name .= sprintf( '  <span class="mf_delete_group_field mf-delete">(<a  alt="%s" class="mf_confirm" href="%s">delete group</a>)</span>', $delete_msg, $delete_link );
    }
    else {
      $name .= sprintf( '  <span class="mf_add_group_field">(<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field&post_type=%s">create field</a>)</span>',$post_type->name );	
    }
    //return all fields for group
    $fields = $this->get_custom_fields_by_group($group['id']);
    ?>
      <h2 class="mf-no-default-group"><?php echo $name; ?></h2><div class="mf-ajax-loading" id="mf-ajax-loading-<?php echo $group['id'];?>"></div>
      <?php if($fields): ?>
     <div>
      <input type="hidden" name="mf_order_fields" id="mf_order_fields" />
     <table class="widefat fixed" id="mf_sortable" cellspacing="0">
      <thead>
        <tr>
          <th scope="col" id="order" class="manage-column column-title" width="5%"></th>
          <th scope="col" id="label" class="manage-column column-title" width="35%"><?php _e('Label',$mf_domain); ?></th>
          <th scope="col" id="name" class="manage-column column-title" width="35%"><?php _e('Name',$mf_domain); ?> (<?php _e('order',$mf_domain); ?>)</th>
          <th scope="col" id="type" class="manage-column column-title" width="25%"><?php _e('Type',$mf_domain); ?></th>
        </tr>
      </thead>
      <tfoot>
         <tr>
          <th scope="col" id="order" class="manage-column column-title" width="5%"></th>
          <th scope="col" id="label" class="manage-column column-title" width="35%"><?php _e('Label',$mf_domain); ?></th>
          <th scope="col" id="name" class="manage-column column-title" width="35%"><?php _e('Name',$mf_domain); ?> (<?php _e('order',$mf_domain); ?>)</th>
          <th scope="col" id="type" class="manage-column column-title" width="25%"><?php _e('Type',$mf_domain); ?></th>
        </tr>
      </tfood>
      <tbody rel="group-<?php print $group['id']; ?>" >
      <?php foreach($fields as $field): ?>
        <tr id="order_<?php echo $field['id']; ?>">
          <td>
            <img class="mf-order-icon" src="<?php echo MF_BASENAME ?>images/arrows_up_down.gif" />
          </td>
          <td>
            <?php echo $field['label'];?>
            <?php if($field['required_field']): ?><span class="required">*</span> <?php endif; ?>
            <div class="row-actions">
              <span class="edit">
               <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=edit_field&custom_field_id=<?php echo $field['id'];?>"><?php _e('Edit',$mf_domain); ?></a>
              </span> |
              <span class="delete">
                <?php
                $delete_link = "admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=delete_custom_field&custom_field_id={$field['id']}&init=true";
                $delete_link = wp_nonce_url($delete_link,'delete_custom_field');
                $delete_msg = __('This action can\'t be undone, are you sure?', $mf_domain);
                ?>
                <a class="mf_confirm" alt="<?php print $delete_msg; ?>" href="<?php print $delete_link;?>"><?php _e('Delete',$mf_domain)?></a>
              </span>
            </div>
          </td>
          <td><tt><?php echo $field['name'];?></tt> <span style="color: #999;">(<?php echo $field['display_order']; ?>)</span></td>
          <td style="text-transform:capitalize;" ><?php echo preg_replace('/_/',' ',$field['type']);?></td>
        </tr>
       <?php endforeach; ?>
      </tbody>
     </table>
     </div>
     <?php else:?>
      <div class="message-box info">
        <p>
          This group haven't any custom field yet,  create one <a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field&post_type=<?php print $post_type->name;?><?php if($group['label'] != "Magic Fields"){?>&custom_group_id=<?php print $group['id'];}?>">here</a>
        </p>
      </div>
     <?php endif; ?>
      <br />
   <?php
      endforeach;
    print '</div>';
  }

  /**
   *  Page for add a new custom field
   */
  function add_field() {
    global $mf_domain;

    $data = $this->fields_form();
    $this->form_custom_field($data);
    ?>
    <?php
  }

   /**
   *  Page for edit a custom field
   */
  function edit_field() {
    global $mf_domain;

    //check param custom_field_id


    $data = $this->fields_form();
    $field = $this->get_custom_field($_GET['custom_field_id']);

    //check if exist field
    if(!$field){
      $this->mf_flash('error');
    }else{
      $no_set = array('options','active','display_order');
      foreach($field as $k => $v){
        if( !in_array($k,$no_set) ){
          $data['core'][$k]['value'] = $v;
        }
      }
      $data['option'] = unserialize($field['options'] );
    }
    $this->form_custom_field($data);
    ?>
    <?php
  }

  function save_custom_field(){

    //save custom field
    $mf = $_POST['mf_field'];
    if($mf['core']['id']){
      //update
      $this->update_name_field($mf);
      $this->update_custom_field($mf);
    }else{
      //insert
      $this->new_custom_field($mf);
    }
    $this->mf_redirect('mf_custom_fields','fields_list',array('message' => 'success','post_type' => $mf['core']['post_type']));
  }

  public function update_name_field($mf){
    global $wpdb;
    $field = $this->get_custom_field($mf['core']['id']);

    // change the name of field?
    if( $mf['core']['name'] != $field['name'] ){
      $query = sprintf(
      "UPDATE %s pm, %s p ".
      "SET pm.field_name = '%s' ".
      "WHERE pm.field_name = '%s' AND p.post_type = '%s' AND pm.post_id = p.id",
      MF_TABLE_POST_META,
      $wpdb->posts,
      $mf['core']['name'],
      $field['name'],
      $mf['core']['post_type']
      );
      $wpdb->query($query);
    }
  }

  public function get_custom_fields_post_type($post_type){
    GLOBAL $wpdb;
    $query = sprintf("SELECT * FROM %s WHERE post_type = '%s'", MF_TABLE_CUSTOM_FIELDS,$post_type);
    $fields = $wpdb->get_results($query, ARRAY_A);
    return $fields;

  }

  /**
   * Get the list of custom fields
   *
   * @return array
   */
  function get_custom_fields_name () {
    $path = MF_PATH.'/field_types/*';
    $folders = glob($path,GLOB_ONLYDIR);

    $fields = array();

    foreach($folders as $folder) {
      $name = preg_match('/\/([\w\_]+)\_field$/i',$folder,$name_match);
      $fields[$name_match[1]] = preg_replace('/_/',' ',$name_match[1]);
    }

    return $fields;
  }


  public function fields_form() {
    global $mf_domain;

    $custom_fields = $this->get_custom_fields_name();
    $post_type = isset($_GET['post_type'])? $_GET['post_type'] : '';
    $custom_field_id = isset($_GET['custom_field_id'])? $_GET['custom_field_id']: '';
    $custom_group_id = isset($_GET['custom_group_id'])? $_GET['custom_group_id']: '';
    $data = array(
      'core'  => array(
        'id' => array(
          'type' => 'hidden',
          'id'   => 'customfield_id',
          'name'  => 'mf_field[core][id]',
          'value' => $custom_field_id
        ),
        'post_type' => array(
          'type' => 'hidden',
          'id'   => 'customfield-post_type',
          'name' => 'mf_field[core][post_type]',
          'value' => $post_type
        ),
        'custom_group_id' => array(
          'type' => 'hidden',
          'id'   => 'customfield_custom_group_id',
          'name' => 'mf_field[core][custom_group_id]',
          'value' => $custom_group_id
        ),
        'label'  => array(
          'type'        =>  'text',
          'id'          =>  'customfield-label',
          'label'       =>  __('Label',$mf_domain),
          'name'        =>  'mf_field[core][label]',
          'description' =>  __( 'The label of the field', $mf_domain),
          'class'       => "{validate:{required:true,messages:{required:'". __('This Field is required',$mf_domain)."'}}}",
          'div_class'   =>  'form-required',
          'value'       =>  ''

        ),
        'name'  => array(
          'type'        =>  'text',
          'id'          =>  'customfield-name',
          'label'       =>  __('Name',$mf_domain),
          'name'        =>  'mf_field[core][name]',
          'description' =>  __( 'Used by the system, only lowercase alphanumeric characters and underscore is accepted.', $mf_domain),
          'div_class'   =>  'form-required',
          'class'       => "{ validate:{ required:true, maxlength:150, lowercase:true, messages:{ lowercase:'".__( 'Only  are accepted lowercase characters,numbers or underscores' )."', required:'".__( 'This Field is required', $mf_domain )."', maxlength:'".__( 'This Field must have less than 150 characters' )."' }}}",
          'value'       =>  ''
        ),
        'description' =>  array(
          'type'        =>  'textarea',
          'label'       =>  __('Help text',$mf_domain),
          'name'        =>  'mf_field[core][description]',
          'description' =>  __( 'Tell to the user about what is the field', $mf_domain ),
          'value'       =>  '',
          'id'          => 'customfield-description',
          'class'       => '',
          'div_class'   => ''
        ),
        'type'  => array(
          'type'        =>  'select',
          'id'          =>  'customfield-type',
          'label'       =>  __('Type of Custom Field',$mf_domain),
          'name'        =>  'mf_field[core][type]',
          'default'     =>  '',
          'options'      =>  $custom_fields,
          'description' =>  __( 'Select the type of custom field', $mf_domain ),
          'value'       =>  '',
          'add_empty'   =>  true,
          'class'       => "{validate:{required:true,messages:{required:'". __('This Field is required',$mf_domain)."'}}}",
          'div_class'   => 'form-requierd'
        ),
        'required_field'    =>  array(
          'type'        =>  'checkbox',
          'label'       =>  __('required',$mf_domain),
          'name'        =>  'mf_field[core][required_field]',
          'description' =>  __( 'this field is required', $mf_domain ),
          'id'          =>  'customfield-required',
          'value'       =>  0,
          'class'       => '',
          'div_class'   => ''
        ),
        'duplicated'  =>  array(
          'type'        =>  'checkbox',
          'label'       =>  __('Can be duplicated',$mf_domain),
          'name'        =>  'mf_field[core][duplicate]',
          'description' =>  __('this field is duplicable?',$mf_domain),
          'value'       =>  0,
          'id'          =>  'customfield-duplicated',
          'class'       => '',
          'div_class'   => ''
        )
      )
    );

    return $data;
  }

  function form_custom_field( $data ) {
    global $mf_domain;
    
    $name_group = '';
    if($data['core']['custom_group_id']['value']){
      $group = $this->get_group( $data['core']['custom_group_id']['value'] );
      $name_group = $group['name'];
    }
    printf('<input type="hidden" id="name_group_slug" value="%s" >',$name_group);
    ?>
    <div class="wrap">
      <div id="message_mf_error" class="error below-h2" style="display:none;"><p></p></div>
      <div id="icon-edit-pages" class="icon32 icon32-posts-page"><br></div>
      <?php if( !$data['core']['id']['value'] ): ?>
       <h2><?php _e('Create Custom Field', $mf_domain);?></h2>
    <?php else: ?>
    <h2><?php _e('Edit Custom Field', $mf_domain); echo ' - '.$data['core']['label']['value'];?></h2>
      <?php endif; ?>

     <form id="addCustomField" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_fields&mf_action=save_custom_field" class="validate mf_form_admin">
      <div class="alignleft fixed" style="width: 40%;" id="mf_add_custom_field">
        <?php foreach( $data['core'] as $core ):?>
          <?php if( $core['type'] == 'hidden' ): ?>
                  <?php mf_form_hidden($core); ?>
          <?php elseif( $core['type'] == 'text' ):?>
                  <div class="form-field mf_form <?php echo $core['div_class']; ?>">
              <?php mf_form_text($core); ?>
            </div>
          <?php elseif( $core['type'] == "select" ):?>
            <div class="form-field mf_form <?php echo $core['div_class']; ?>">
              <?php mf_form_select($core); ?>
            </div>
            <?php elseif( $core['type'] == "textarea" ):?>
            <div class="form-field mf_form helptext-form <?php echo $core['div_class']; ?>">
              <?php mf_form_textarea($core); ?>
            </div>
          <?php elseif( $core['type'] == "checkbox" ):?>
            <fieldset>
              <div class="form-field mf_form <?php echo $core['div_class']; ?>">
              <?php mf_form_checkbox($core);?>
              </div>
            </fieldset>
          <?php endif;?>
        <?php endforeach;?>
        <p class="submit">
          <a style="color:black" href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=fields_list&post_type=<?php echo $data['core']['post_type']['value'];?>" class="button">Cancel</a>
          <input type="submit" class="button button-primary" name="submit" id="submit" value="Save Custom Field">
        </p>
      </div>
      <div class="widefat mf_form_right stuffbox metabox-holder">
        <h3>Options of field</h3>
        <div class="inside">
        <?php $legend_class = ($data['core']['id']['value'])? sprintf('style="display:none;"') : ''; ?>
        <div  id="options_field_legend" <?php echo $legend_class; ?> >
          <p>By default on this box will be displayed a information about custom fields, after the  custom field be selected, this box will be displayed some extra options of the field (if required) or a information about the selected field</p>
        </div>
        <div id="options_field">
           <?php
           if( $data['core']['id']['value'] ){
             $name = sprintf('%s_field',$data['core']['type']['value']);
             $mf_field = new $name();
             $mf_field->get_options($data['option'],$name);
           } ?>
        </div>
      </div>
      </div>
    </div>
</form>
  <?php
  }

  public function _options(){
    global $mf_domain;

    $data = array(
      'option'  => array(
        'text_option'  => array(
          'type'        => 'text',
          'id'          => 'text_id',
          'label'       => __('label for text(input)',$mf_domain),
          'name'        => 'mf_field[option][text_option]',
          'description' => __( 'aqui una descripcion', $mf_domain ),
          'value'       => 'default value',
          'div_class'   => 'class_text',
        'class'       => 'div_class_text'
        ),
        'checkbox_option' => array(
          'type'        => 'checkbox',
          'id'          => 'checkbox_id',
          'label'       => __('label for checkbox',$mf_domain),
          'name'        => 'mf_field[option][checkbox_option]',
          'value'       => 1,
          'description' => __('One description for checkbox',$mf_domain),
          'class'       => 'class_checkbox',
        'div_class'   => 'div_class_checkbox'
        ),
        'select_option' =>  array(
          'type'        => 'select',
          'id'          => 'select_id',
          'label'       =>  __('label for select', $mf_domain),
          'name'        =>  'mf_field[option][select_option]',
          'value'       => '',
          'description' =>  __( 'description for select', $mf_domain ),
          'options'     => array('one','two','more'),
          'add_empty'   => true,
          'div_class'   => 'class_select',
        'class'       => 'div_class_select'
        ),
        'textarea_option' =>  array(
          'type'        => 'textarea',
          'id'          => 'textarea_id',
          'label'       =>  __('Label for textarea', $mf_domain),
          'name'        =>  'mf_field[option][textarea_option]',
          'value'       => 'uno value',
          'description' =>  __( 'description for textarea', $mf_domain ),
          'div_class'   => 'class_textarea',
        'class'       => 'div_class_textarea'
        )
      )
    );

    return $data;
  }

  /**
   * Save the order of the custom fields
   */
  public static function save_order_field( $group_id, $order ) {
    global $wpdb;

    if( !is_numeric($group_id) ) {
      return false;
    }

    foreach( $order as $key => $value ) {
      $update = $wpdb->update(
        MF_TABLE_CUSTOM_FIELDS,
        array( 'display_order' => $key ),
        array( 'custom_group_id' => $group_id, 'id' => $value ),
        array( '%d' ),
        array( '%d', '%d' )
      );

      if( $update  === false ) {
        return $update;
      }
    }
    return true;
  }

  /**
   * Return True if the post type has at least one custom field
   *
   * return @bool
   **/
  public static function has_fields($post_type_name) {
    global $wpdb;

    $sql = $wpdb->prepare("SELECT COUNT(1) FROM ".MF_TABLE_CUSTOM_FIELDS. " WHERE post_type = %s",$post_type_name);

    return $wpdb->get_var( $sql ) > 0;
  }


  /**
   * Delete Custom Field
   */
  function delete_custom_field() {
    global $wpdb;

    //checking the nonce
    check_admin_referer('delete_custom_field');

    if(  isset($_GET['custom_field_id']) ) {
      $id = (int)$_GET['custom_field_id'];

      if( is_int($id) ){
        $sql = "DELETE FROM ".MF_TABLE_CUSTOM_FIELDS." WHERE id = ".$id;
        $wpdb->query($sql);
      }
    }

    //ToDo: poner mensaje de que se borro correctamente
    wp_safe_redirect(
      add_query_arg(
        'field_deleted',
        'true',
        wp_get_referer()
      )
    );
  }

  public function check_group($name,$post_type,$id = NULL){
    global $wpdb;

    $query = sprintf(
      "SELECT COUNT(*) FROM %s WHERE name = '%s' AND post_type = '%s' ",
      MF_TABLE_CUSTOM_FIELDS,
      $name,
      $post_type
    );
    if($id)
      $query = sprintf("%s AND id != %s",$query,$id);

    $check = $wpdb->get_var($query);
    return $check;
  }

  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    global $mf_domain;

    $output = '';
    $output .= sprintf('<input %s type="text" name="%s" placeholder="%s" value="%s" />',$field['input_validate'], $field['input_name'], $field['label'], $field['input_value'] );
    return $output;
  }

  public function upload($custom_field_id, $type = 'image',$callback = 'mf_callback_upload'){
    $iframe_src = sprintf('%sadmin/mf_upload.php?input_name=%s&callback=%s&type=%s',MF_BASENAME,$custom_field_id,$callback,$type);
    $out = sprintf('<iframe id="iframe_upload_%s" src="%s" height="45" scrolling="no" ></iframe>',$custom_field_id,$iframe_src);
    
    return $out;
  }
}
