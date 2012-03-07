<?php
// initialisation
global $mf_domain;

// class with static properties encapsulating functions for the field type

class term_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;

  public function _update_description(){
    global $mf_domain;
    $this->description = __("This field allows to do relations with taxonomie terms",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
	
    // Get the taxonomie as dropdownoption
    $select = array();
    $tax = get_taxonomies();
    foreach($tax as $k => $v){
	  $select[] = $v;
    }
	
    $data = array(
     'option'  => array(
        'term'  => array(
          'type'        =>  'select',
          'id'          =>  'term',
          'label'       =>  __('related taxonomy: ',$mf_domain),
          'name'        =>  'mf_field[option][term]',
          'default'     =>  '',
          'options'     => $select,
          'add_empty'   => false,
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        ),
      )
    );
    return $data;
  }
  
  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    global $mf_domain;

    // If is not required this field be added a None value
    $notype = "";
    if( !$field['required_field'] ) {
      $notype = ( !empty($field['options']['notype']) ) ? $field['options']['notype'] : __( "-- None --" , $mf_domain );
    }

    $output = '';
	
	// Get the taxonomie as dropdownoption
    $select = array();
    $tax = get_taxonomies();
    foreach($tax as $k => $v){
	  $select[] = $v;
    }
	
    $option_from_term_array = $field['options']['term'];
    $options = get_terms($select[$option_from_term_array]);
    $output = '<div class="mf-dropdown-box">';
    $value = $field['input_value'];
	
    $output .= sprintf('<select class="dropdown_mf" id="%s" name="%s" >',$field['input_id'],$field['input_name']);

    if( $notype != "" ) {
      $output .= "<option value=''>$notype</option>";
    }
  
    foreach($options as $option) {

      $check = ($option->slug == $value) ? 'selected="selected"' : '';
      $output .= sprintf('<option value="%s" %s >%s</option>',
        esc_attr($option->slug),
        $check,
        esc_attr($option->name)
      );
    }
    $output .= '</select>';
    $output .= '</div>';

    return $output;
  }
}
