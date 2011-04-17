<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class image_media_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple image media input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'css_clas'  => array(
          'type'        =>  'text',
          'id'          =>  'image_css_class',
          'label'       =>  __('Css Class',$mf_domain),
          'name'        =>  'mf_field[option][css_class]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  'magic_fields',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'max_height'  => array(
          'type'        =>  'text',
          'id'          =>  'image_max_height',
          'label'       =>  __('Max height',$mf_domain),
          'name'        =>  'mf_field[option][max_height]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'max_width'  => array(
          'type'        =>  'text',
          'id'          =>  'image_max_width',
          'label'       =>  __('Max Width',$mf_domain),
          'name'        =>  'mf_field[option][max_width]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'custom'  => array(
          'type'        =>  'text',
          'id'          =>  'image_custom',
          'label'       =>  __('Custom',$mf_domain),
          'name'        =>  'mf_field[option][custom]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        )
      )
    );    
    return $data;
  }
  
}
