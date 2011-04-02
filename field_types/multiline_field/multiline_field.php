<?php

global $mf_domain;

// initialisation

class multiline_field extends mf_custom_fields {

  public static $allow_multiple = TRUE;
  public static $has_properties = TRUE;
  public static $description = __("A TinyMCE editor with the same properites as the Wordpress visual editor, for editing rich HTML-based content");
  
  public static function post_ui($field, $field_value, $form_field) {

  }
  
  public static function create_ui($field) {
    
  }
  
  public static function display() {
    
  }
  
  public static function get($field, $value) {
    
  }
  
  public static function set() {
    
  }
}
?>
