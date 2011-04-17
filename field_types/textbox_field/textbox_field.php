<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class textbox_field extends mf_custom_fields {

  //This Properties MUST be static
  public static $css_script = TRUE;
  public static $js_script = TRUE;
  public static $js_dependencies = array();
  // --

  //Properties
  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public static function get_static_properties() {
    $properties['css']              = self::$css_script;
    $properties['js_dependencies']  = self::$js_dependencies;
    $properties['js']               = self::$js_script;

    return $properties;
  }

  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple Textbox input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'size'  => array(
          'type'        =>  'text',
          'id'          =>  'textbox_size',
          'label'       =>  __('Size',$mf_domain),
          'name'        =>  'mf_field[option][size]',
          'description' =>  '',
          'value'       =>  '25',
          'div_class'    => '',
          'class'       => ''
        ),
        'evalueate'  => array(
          'type'        =>  'checkbox',
          'id'          =>  'textbox_evalueate',
          'label'       =>  __('Evaluate Max Length',$mf_domain),
          'name'        =>  'mf_field[option][evalueate]',
          'description' =>  '',
          'default'     => true,
          'value'       =>  1,
          'div_class'    => '',
          'class'       => ''
        )
      )
    );
    return $data;
  }

}
