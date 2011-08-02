<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class radiobutton_list_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Radio button list that allows the user to choose only one of a predefined set of options.",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
     $data = array(
      'option'  => array(
        'options'  => array(
          'type'        =>  'textarea',
          'id'          =>  'checkbox_list_options',
          'label'       =>  __('Options',$mf_domain),
          'name'        =>  'mf_field[option][options]',
          'default'     =>  '',
          'description' =>  __( 'Separate each option with a newline.', $mf_domain ),
          'value'       =>  '',
          'div_class'    => '',
          'class'       => ''
        ),
        'default_value'  => array(
          'type'        =>  'text',
          'id'          =>  'checkbox_list_default_value',
          'label'       =>  __('Deafult value',$mf_domain),
          'name'        =>  'mf_field[option][default_value]',
          'default'     =>  '',
          'description' =>  __( 'Separate each value with a newline.', $mf_domain ),
          'value'       =>  '',
          'div_class'    => '',
          'class'       => ''
        )
      )
    );
    
    return $data;
  }

  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    $output = '';

    $check_post_id = null; 
    if( !empty($_REQUEST['post'] ) ) {
      $check_post_id = $_REQUEST['post'];
    }

    $value = array();
    if( $check_post_id ) {
        $value = trim($field['input_value']);
    } else {
        $value = trim($field['options']['default_value']);
    }

    $options = preg_split( "/\\n/", $field['options']['options']);
    
    foreach( $options as $option ) {
      $option = trim($option);
      $checked = ''; 
      if( $option == $value ) {
        $checked =  'checked="checked"';
      }
   
      $output .= '<label class="mf-radio-field">';
      $output .=  sprintf('<input type="radio" value="%s" name="%s" %s %s >%s', $option, $field['input_name'], $checked,$field['input_validate'], $option);
      $output .= '</label>';
    }

    return $output; 
  }
}
