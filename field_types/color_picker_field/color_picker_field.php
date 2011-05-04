<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class color_picker_field extends mf_custom_fields {

  function get_properties() {
    return  array(
      'js'  => TRUE,
      'js_dependencies' => array(
        'jquery',
        'farbtastic',
      ),
      'css' => TRUE,
      'css_dependencies'  => array(
        'farbtastic'
      )
    );
  }
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple Color picker input",$mf_domain);
  }

  public function display_field( $field, $value = '', $group_index = 1, $field_index = 1 ) {
    global $mf_domain;

    $output = '';
    $output .= sprintf(
      '<input name="magicfields[%s][%d][%d]" placeholder="%s" value="%s" id="colorpicker_value_%s_%d_%d" /><div class="mf_colorpicker" id="colorpicker_%s_%d_%d"></div>', 
      $field['name'], 
      $group_index, 
      $field_index, 
      $field['label'], 
      $value,
      $field['name'],
      $group_index,
      $field_index,
      $field['name'],
      $group_index,
      $field_index
    );

    return $output;
  }

}
