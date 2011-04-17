<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class dropdown_list_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple dropdown input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'options'  => array(
          'type'        =>  'textarea',
          'id'          =>  'checkbox_dropdown_options',
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
          'id'          =>  'checkbox_dropdown_default_value',
          'label'       =>  __('Defualt value',$mf_domain),
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
  
}
