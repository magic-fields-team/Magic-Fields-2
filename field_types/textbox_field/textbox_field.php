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
        'evalueate'  => array(
          'type'        =>  'checkbox',
          'id'          =>  'textbox_evaluate',
          'label'       =>  __('Evaluate Max Length',$mf_domain),
          'name'        =>  'mf_field[option][evalueate]',
          'description' =>  '',
          'default'     => true,
          'value'       =>  1,
          'div_class'    => '',
          'class'       => ''
        ),
        'size'  => array(
          'type'        =>  'text',
          'id'          =>  'textbox_size',
          'label'       =>  __('Max Length',$mf_domain),
          'name'        =>  'mf_field[option][size]',
          'description' =>  'Only if evaluate max length is checked',
          'value'       =>  '25',
          'div_class'    => '',
          'class'       => ''
        )
      )
    );
    return $data;
  }
  
  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    global $mf_domain;

    $output = '';
    $max = '';
    if( $field['options']['evalueate'] && ($field['options']['size'] > 0) ){
      $max = sprintf('maxlength="%s"',$field['options']['size']);
    }

    $output .= '<div class="text_field_mf" >';
    $output .= sprintf('<input %s type="text" name="%s" placeholder="%s" value="%s" %s />',$field['input_validate'], $field['input_name'], $field['label'], str_replace('"', '&quot;', $field['input_value']), $max );
    $output .= '</div>';
    return $output;
  }

 }
