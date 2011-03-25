<?php 

class mf_custom_fields extends mf_admin {

  /**
   * this is the page where is displayed the list of fields of a certain custom field
   * @return none
   */
  function fields_list() {
    global $mf_domain;

    $pt = new mf_posttype();
    $post_type = $pt->get_post_type($_GET['post_type']);

    print '<div class="wrap">';
    print '<h2>'.$post_type['core']['label'].'</h2>';
    print '<h3>'.__( 'Custom Fields', $mf_domain ).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field" class="add-new-h2 button">'.__( 'Add new Custom Field', $mf_domain ).'</a></h3>';
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
      $fields[$name_match[1]] = $name_match[1];
    }

    return $fields;
  }


  public function fields_form() {
    global $mf_domain;

    $custom_fields = $this->get_custom_fields_name();

    $data = array(
      'core'  => array(
        'type'  => array(
          'type'        =>  'select',
          'id'          =>  'customfield-type',
          'label'       =>  'Type of Custom Field',
          'name'        =>  'type',
          'default'     =>  '',
          'options'      =>  $custom_fields,
          'description' =>  __( 'Select the type of custom field', $mf_domain ),
          'value'       =>  '',
          'add_empty'   =>  true
        ),
        'label'  => array(
          'type'        =>  'text',
          'id'          =>  'customfield-label',
          'label'       =>  'Label',
          'name'        =>  'label',
          'description' =>  __( 'The label of the field', $mf_domain),
          'div_class'   =>  'form-required',
          'value'       =>  ''

        ),
        'name'  => array(
          'type'        =>  'text',
          'id'          =>  'customfield-name',
          'label'       =>  'Name',
          'name'        =>  'name',
          'description' =>  __( 'The name only accept letters and numbers (lowercar)', $mf_domain),
          'div_class'   =>  'form-required',
          'value'       =>  ''
        ),
        'description' =>  array(
          'type'        =>  'text',
          'label'       =>  'Description', 
          'name'        =>  'description',
          'description' =>  __( 'Tell to the user about what is the field', $mf_domain ),
          'value'       =>  '',
          'id'          => 'customfield-description'
        ),
        'required'    =>  array(
          'type'        =>  'checkbox',
          'label'       =>  'required',
          'name'        =>  'required',
          'description' =>  __( 'this field is required', $mf_domain ),
          'id'          =>  'customfield-required',
          'value'       =>  0
        ),
        'duplicated'  =>  array(
          'type'        =>  'checkbox',
          'label'       =>  'Can be duplicated',
          'name'        =>  'duplicate',
          'description' =>  '',
          'value'       =>  0,
          'id'          =>  'customfield-duplicated',

        )
      )
    );

    return $data;
  }

  function form_custom_field( $data ) {
    global $mf_domain;
    ?>
    <div class="wrap">
      <h2><?php _e('Create Custom Field', $mf_domain);?></h2>


     <form id="addCustomField" method="post" action="admin.php?page=mf_dispatcher&init=true&mf_section=mf_custom_field&mf_action=save_custom_field" class="validate">
      <div class="alignleft fixed" id="mf_add_custom_field">
        <?php $this->mf_form_select($data['core']['type']);?>
        <?php $this->mf_form_text($data['core']['label']);?>
        <?php $this->mf_form_text($data['core']['name']);?>
        <?php $this->mf_form_text($data['core']['description']);?>
        <?php $this->mf_form_checkbox($data['core']['required']);?>
        <?php $this->mf_form_checkbox($data['core']['duplicated']);?>
      </div>
      <div class="widefat mf_form_right">
        <p>By default on this box will be displayed a information about custom fields, after the  custom field be selected, this box will be displayed some extra options of the field (if required) or a information about the selected field</p>
      </div>
    </div>
  <?php
  }
}
