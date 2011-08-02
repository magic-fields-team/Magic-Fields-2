<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class checkbox_list_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Checkbox list let a user select ONE or MORE options of a limited number of choices.",$mf_domain);
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
          'type'        =>  'textarea',
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

  public function display_field($field, $group_index = 1, $field_index = 1){
    $output = '';
    $check_post_id = null;
    if( !empty($_REQUEST['post'])) {
      $check_post_id = $_REQUEST['post'];
    }

    $values = array();
    if($check_post_id){
      $values = ($field['input_value']) ? (is_serialized($field['input_value']))? unserialize($field['input_value']): (array)$field['input_value'] : array() ;
    }else{
      $values = (array)preg_split("/\\n/",$field['options']['default_value']);
    }
    $options = preg_split("/\\n/",$field['options']['options']);
    $output = '<div class="mf-checkbox-list-box" >';
      
      foreach($values as &$val){
        $val = trim($val);
      }
      
    foreach($options as $option){
      $option = trim($option);
      $check = in_array($option, $values) ? 'checked="checked"' : '';

      $output .= sprintf('<label for="%s_%s" class="selectit mf-checkbox-list">',$field['input_id'],$option);
      $output .= sprintf('<input tabindex="3" class="checkbox_list_mf" id="%s_%s" name="%s[]" value="%s" type="checkbox" %s %s />',$field['input_id'],$option,$field['input_name'],$option,$check,$field['input_validate']);
      $output .= esc_attr($option);
      $output .= '</label>';
    }

    $output .= '</div>';
    return $output;
  }
  
}
