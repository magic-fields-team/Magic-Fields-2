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
  public $has_properties = FALSE;
  
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
        'type'  => array(
          'type'        =>  'text',
          'id'          =>  'uno',
          'label'       =>  'opcion 1',
          'name'        =>  'mf_field[option][uno]',
          'default'     =>  '',
          'description' =>  __( 'aqui una descripcion', $mf_domain ),
          'value'       =>  '',
          'div_class'    => 'clase1',
          'class'       => 'vemos1'
        )
      )
    );
    
    return $data;
  }


  /**
   * Create Post Output
   */
  public function admin_post_output() {
     
  }
}
