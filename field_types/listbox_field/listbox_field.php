<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class listbox_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple listbox input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'size'  => array(
          'type'        =>  'text',
          'id'          =>  'listbox_size',
          'label'       =>  __('Size',$mf_domain),
          'name'        =>  'mf_field[option][size]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '3',
          'div_class'    => '',
          'class'       => ''
        ),
        'options'  => array(
          'type'        =>  'textarea',
          'id'          =>  'listbox_options',
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
          'id'          =>  'listbox_default_value',
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
  
}
