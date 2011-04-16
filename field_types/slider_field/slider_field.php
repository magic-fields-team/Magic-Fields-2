<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class slider_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple slider input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'value_min'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_value_min',
          'label'       =>  __('Value min',$mf_domain),
          'name'        =>  'mf_field[option][value_min]',
          'description' =>  '',
          'value'       =>  '0',
          'div_class'    => '',
          'class'       => ''
        ),
        'value_max'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_value_max',
          'label'       =>  __('Value max',$mf_domain),
          'name'        =>  'mf_field[option][value_max]',
          'description' =>  '',
          'value'       =>  '10',
          'div_class'    => '',
          'class'       => ''
        ),
        'stepping'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_stepping',
          'label'       =>  __('Stepping',$mf_domain),
          'name'        =>  'mf_field[option][stepping]',
          'description' =>  '',
          'value'       =>  '1',
          'div_class'    => '',
          'return'       => ''
        )
      )
   );

    
    return $data;
  }
  
}
