<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class audio_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = FALSE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Audio input file",$mf_domain);
  }
  
}
