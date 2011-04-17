<?php

global $mf_domain;

// initialisation

class multiline_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;

  public function _update_description(){
    global $mf_domain;
    $this->description = _("Multiline field",$mf_domain);
  }
  
  public function _options() {
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'height'  => array(
          'type'        =>  'text',
          'id'          =>  'multiline_height_',
          'label'       =>  __('Height',$mf_domain),
          'name'        =>  'mf_field[option][height]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '3',
          'div_class'   => '',
          'class'       => ''
        ),
        'width'  => array(
          'type'        =>  'text',
          'id'          =>  'multiline_width',
          'label'       =>  __('Width',$mf_domain),
          'name'        =>  'mf_field[option][Width]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  '23',
          'div_class'   => '',
          'class'       => ''
        ),
        'hide_visual'  => array(
          'type'        =>  'checkbox',
          'id'          =>  'multiline_hide_visual',
          'label'       =>  __('Hide Visual Editor for this field',$mf_domain),
          'name'        =>  'mf_field[option][hide_visual]',
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        ),
        'max_length'  => array(
          'type'        =>  'checkbox',
          'id'          =>  'multiline_max_length',
          'label'       =>  __('Max Length',$mf_domain),
          'name'        =>  'mf_field[option][max_length]',
          'description' =>  __('If set, Hide Visual Editor for this field',$mf_domain),
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        )
      )
    );

    return $data;
  }
}
?>
