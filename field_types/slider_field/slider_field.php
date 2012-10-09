<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class slider_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function get_properties() {
    return array(
      'js'  => TRUE,
      'js_dependencies' => array(
        'jquery',
        'jquery-ui-widget',
        'jquery-ui-core',
        'jquery-ui-mouse'
      ),
      'js_internal_dependencies'  => array(),
      'js_internal' => 'jquery.ui.slider.js',
      'css' => FALSE,
      'css_internal'  => 'ui.slider.css'
    );
  }

  public function _update_description(){
    global $mf_domain;
    $this->description = __("The slider allows you to render a slider whose position represents a value in a range you specify.",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'value_min'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_value_min',
          'label'       =>  __('Value min',$mf_domain),
          'name'        =>  'mf_field[option][value_min]',
          'description' =>  '',
          'value'       =>  '0',
          'div_class'    => '',
          'class'       => ''
        ),
        'value_max'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_value_max',
          'label'       =>  __('Value max',$mf_domain),
          'name'        =>  'mf_field[option][value_max]',
          'description' =>  '',
          'value'       =>  '10',
          'div_class'    => '',
          'class'       => ''
        ),
        'stepping'  => array(
          'type'        =>  'text',
          'id'          =>  'slider_stepping',
          'label'       =>  __('Stepping',$mf_domain),
          'name'        =>  'mf_field[option][stepping]',
          'description' =>  '',
          'value'       =>  '1',
          'div_class'    => '',
          'return'       => ''
        )
      )
   );
    return $data;
  }

  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    $check_post_id = null; 
    if( !empty($_REQUEST['post'] ) ) {
      $check_post_id = $_REQUEST['post'];
    } 
    
    $value = $field['options']['value_min'];
    if( $check_post_id ) {
      $value = (!empty($field['input_value'])) ? $field['input_value'] : 0;
    }

    $output = '';
    $output .= sprintf(
      '<div id="slider_%s" class="mf_slider_field" data="{min:\'%s\', max:\'%s\', value:\'%s\', stepping:\'%s\'}"></div>',
      $field['input_id'], 
      $field['options']['value_min'], 
      $field['options']['value_max'],
      $value,
      $field['options']['stepping']
    );
    $output .= sprintf('<input type="hidden" name="%s" id="%s" value="%s" />', $field['input_name'], $field['input_id'], $value );

    return $output;
  }
}
