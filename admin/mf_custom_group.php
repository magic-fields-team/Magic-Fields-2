<?php 

class mf_custom_group extends mf_admin {

  /** 
   *  Page for add a new group
   */
  function add_group() {
    global $mf_domain;

    $data = $this->group_data();
    $this->form_custom_group($data);
  }

  /**
   * Edit group
   */
  function edit_group(){
    global $mf_domian;

    //check param custom_group_id

    $data = $this->group_data();
    $group = $this->get_group($_GET['custom_group_id']);
    
    //check exist group
    if(!$group){
       $this->mf_flash('error');
    }else{
      //set the values
      foreach($group as $k => $v){
        $data['core'][$k]['value'] = $v;
      }
      $this->form_custom_group($data);
    }
  }
  
  /**
   * Delete a custom group
   */
  public function delete_custom_group(){
    global $wpdb;
    
    //checking the nonce
    check_admin_referer('delete_custom_group');
    
    if( isset($_GET['custom_group_id']) ){
      $id = (int)$_GET['custom_group_id'];
      if( is_int($id) ){
        $group = $this->get_group($id);
        $sql = sprintf("DELETE FROM %s WHERE id = %s",MF_TABLE_CUSTOM_GROUPS,$id);
        $wpdb->query($sql);
        
        $sql_fields = sprintf("DELETE FROM %s WHERE custom_group_id = %s",MF_TABLE_CUSTOM_FIELDS,$id);
        $wpdb->query($sql_fields);

        //ToDo: poner mensaje de que se borro correctamente
        $this->mf_redirect('mf_custom_fields','fields_list',array('message' => 'success','post_type' => $group['post_type']));
        
      }
    }
  }

  function save_custom_group(){

    //save custom group
    $mf = $_POST['mf_group'];
    if($mf['core']['id']){
      //update
      $this->update_custom_group($mf);
    }else{
      //insert
      $this->new_custom_group($mf);
    }
    
    //redirect to dashboard fields
    $this->mf_redirect('mf_custom_fields','fields_list',array('message' => 'success','post_type' => $mf['core']['post_type']));
  }  

  public function get_custom_fields_post_type($post_type){
    GLOBAL $wpdb;
    $query = sprintf("SELECT * FROM %s WHERE post_type = '%s'", MF_TABLE_CUSTOM_FIELDS,$post_type);
    $fields = $wpdb->get_results($query, ARRAY_A);
    return $fields;
    
  }

   public function group_data() {
    global $mf_domain;

    $post_type = isset($_GET['post_type'])? $_GET['post_type'] : '';
    $id = isset($_GET['custom_group_id'])? $_GET['custom_group_id']: '';
    $data = array(
      'core'  => array(
        'id' => array(
          'type' => 'hidden',
          'id'   => 'custom_group_id',
          'name'  => 'mf_group[core][id]',
          'value' => $id
        ),
        'post_type' => array(
          'type' => 'hidden',
          'id'   => 'custom_group_post_type',
          'name' => 'mf_group[core][post_type]',
          'value' => $post_type
        ),
        'label'  => array(
          'type'        =>  'text',
          'id'          =>  'custom_group_label',
          'label'       =>  __('Label',$mf_domain),
          'name'        =>  'mf_group[core][label]',
          'description' =>  __( 'The label of the group', $mf_domain),
          'class'       => "{validate:{required:true,messages:{required:'". __('This Field is required',$mf_domain)."'}}}",
          'div_class'   =>  'form-required',
          'value'       =>  ''
        ),
        'name'  => array(
          'type'        =>  'text',
          'id'          =>  'custom_group_name',
          'label'       =>  __('Name',$mf_domain),
          'name'        =>  'mf_group[core][name]',
          'description' =>  __( 'Used by the system, only lowercase alphanumeric characters and underscore is accepted.', $mf_domain),
          'class'       => "{ validate:{ required:true, maxlength:150, lowercase:true, messages:{ lowercase:'".__( 'Only  are accepted lowercase characters,numbers or underscores' )."', required:'".__( 'This Field is required', $mf_domain )."', maxlength:'".__( 'This Field must have less than 150 characters' )."' }}}",
          'div_class'   =>  'form-required',
          'value'       =>  ''
        ),
         'duplicated'  =>  array(
          'type'        =>  'checkbox',
          'id'          => 'custom_group_duplicated',
          'label'       =>  __('Can be duplicated',$mf_domain),
          'name'        =>  'mf_group[core][duplicate]',
          'description' =>  __('this group is duplicable?',$mf_domain),
          'value'       =>  0,
          'div_class'   => ''
         ),
        'expanded'    =>  array(
          'type'        =>  'hidden',
          'id'          =>  'custom_group_expanded',
          'label'       =>  __('Show as Expanded:',$mf_domain),
          'name'        =>  'mf_group[core][expanded]',
          'description' =>  __( 'Display the full expanded group editing interface instead of the group summary',$mf_domain),
          'extra'       => __('Note: the group can still be collapsed by the user, this just determines the default state on load', $mf_domain ),
          'value'       =>  1,
          'div_class'   => ''
        )   
      )
    );

    return $data;
  }

  function form_custom_group( $data ) {
    global $mf_domain;
    ?>
    <div class="wrap">
      <div id="message_mf_error" class="error below-h2" style="display:none;"><p></p></div>
      <div id="icon-themes" class="icon32"><br></div>
      <?php if( !$data['core']['id']['value'] ): ?>
      <h2><?php _e('Create Custom Group', $mf_domain);?></h2>
      <?php else: ?>
    <h2><?php _e('Edit Custom Group', $mf_domain);?> - <?php echo $data['core']['label']['value']; ?></h2>
      <?php endif; ?>


     <form id="addCustomGroup" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_group&mf_action=save_custom_group" class="validate mf_form_admin">
      <div class="alignleft fixed" style="width: 40%;" id="mf_add_custom_group">
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
      	  <input type="submit" class="button button-primary" name="submit" id="submit" value="Save Custom Group">
      	</p>
      </div>
      <div class="widefat mf_form_right stuffbox metabox-holder">
        <h3><?php _e('About group',$mf_domain); ?></h3>
        <div class="inside">
          <div id="options_field" class="group_side">
            <p><?php _e('A group allows us to group a series of custom fields and to have a better managing of the custom fields',$mf_domain); ?></p>
            <p><?php _e('The groups have the great usefulness of which it is possible to duplicate, this is, one creates new instance of the group (with all the custom fields that the group contains)',$mf_domain); ?></p>
            <p><?php _e('Another characteristic of the duplicated groups is that we them can arrange and have a control of which group we want that it show to be first',$mf_domain); ?></p>
            <p><img src="<?php echo MF_URL; ?>images/admin/group.jpg"/></p>
          </div>
        </div>
      </div>
    </div>
</form>
  <?php
  }
  
  public function check_group($name,$post_type,$id = NULL){
    global $wpdb;
  
    $query = sprintf(
      "SELECT COUNT(*) FROM %s WHERE name = '%s' AND post_type = '%s' ",
      MF_TABLE_CUSTOM_GROUPS,
      $name,
      $post_type
    );
    if($id)
      $query = sprintf("%s AND id != %s",$query,$id);
      
    $check = $wpdb->get_var($query);
    return $check;
  }
}
