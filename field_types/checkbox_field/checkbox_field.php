<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class checkbox_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = FALSE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple checkbox input",$mf_domain);
  }

  public function display_field($field,$group_index = 1, $field_index = 1){
    $output = '';
    $check = ($field['input_value'])? 'checked="checked"' : '';
    $output .= sprintf(
      '<input type="hidden" name="%s" value="0" />',
      $field['input_name']
    );
    $output .= sprintf(
      '<input class="checkbox checkbox_mf" name="%s" value="1" id="checkbox_%s" type="checkbox" %s />',
      $field['input_name'],
      $field['input_id'],
      $check
    );

    return $output;
  }
  
}
