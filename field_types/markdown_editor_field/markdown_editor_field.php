<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class markdown_editor_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = FALSE;

  function get_properties() {
    return  array(
      'js'  => TRUE,
      'js_dependencies' => array(),
      'js_internal_dependencies' => array(
        'jquery'
      ),
      'js_internal' =>'jquery.markitup.pack.js',
      'css' => FALSE,
      'css_internal' => 'markitup.css'
    );
  }
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("A markdown editor",$mf_domain);
  }

  public function display_field($field, $group_index = 1, $field_index =1){
    $out = '';
    
    $out .= sprintf('<textarea class="markdowntextboxinterface markdowntextboxinterface_editor" id="%s" name="%s" %s >%s</textarea>',$field['input_id'],$field['input_name'],$field['input_validate'],$field['input_value']);
    return $out;
  }
    
  
}
