<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class color_picker_field extends mf_custom_fields {

  public $has_properties = FALSE;

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
    $this->description = __("Select a color based on a visual rainbow.",$mf_domain);
  }

  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    global $mf_domain;
    if(!trim($field['input_value'])) $field['input_value'] = '#ffffff';
    $output = '';
    $output .= sprintf(
      '<input name="%s" placeholder="%s" class="clrpckr" value="%s" id="colorpicker_value_%s" %s /><div class="mf_colorpicker" id="colorpicker_%s" ></div>', 
      $field['input_name'],
      $field['label'],
      $field['input_value'],
      $field['input_id'],
      $field['input_validate'],
      $field['input_id']
    );

    return $output;
  }

}
