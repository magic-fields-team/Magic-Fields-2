<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class textbox_field extends mf_custom_fields {

  //Properties
  public  $css_script = FALSE;
  public  $js_script = FALSE;
  public  $js_dependencies = array();
  public  $allow_multiple = TRUE;
  public  $has_properties = TRUE;
  
  public function get_properties() {
    $properties['css']              = $this->css_script;
    $properties['js_dependencies']  = $this->js_dependencies;
    $properties['js']               = $this->js_script;

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

  /** 
   * This method display the markup of the field into the admin area
   */
  function display_field( $field, $value = '', $field_index = 1, $group_index = 1 ) {
    global $mf_domain;

    $output = "";
    $output .= "<label><span>{$field['label']}</span><small class=\"mf_description\">".__( 'What\'s this?', $mf_domain )." <span>{$field['description']}</span></small></label>";
    $output .= "<input name=\"data[{$group_index}][{$field_index}]\" placeholder=\"{$field['label']}\" />";
    return $output;
  }
 }
