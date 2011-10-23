<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class related_type_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;

  public function _update_description(){
    global $mf_domain;
    $this->description = __("This field allows to do relations with other post type",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $posttypes = $this->mf_get_post_types();
    $select = array();
    foreach($posttypes as $k => $v){
      $select[$k] = $v->label;
    }

    $data = array(
      'option'  => array(
        'post_type'  => array(
          'type'        =>  'select',
          'id'          =>  'post_type',
          'label'       =>  __('Related Type Panel (Post type)',$mf_domain),
          'name'        =>  'mf_field[option][post_type]',
          'default'     =>  '',
          'options'     => $select,
          'add_empty'   => false,
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        ),
        'field_order'  => array(
          'type'        =>  'select',
          'id'          =>  'field_order',
          'label'       =>  __('Field for order of Related type',$mf_domain),
          'name'        =>  'mf_field[option][field_order]',
          'default'     =>  '',
          'options'     => array('id' => 'ID','title' =>'Title'),
          'add_empty'   => false,
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        ),
        'order'  => array(
          'type'        =>  'select',
          'id'          =>  'order',
          'label'       =>  __('Order of Related type',$mf_domain),
          'name'        =>  'mf_field[option][order]',
          'default'     =>  '',
          'options'     => array('asc' => 'ASC','desc' =>'DESC'),
          'add_empty'   => false,
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        ),

        'notype'  => array(
          'type'        =>  'text',
          'id'          =>  'notype',
          'label'       =>  __('Default option (when no related type has been set)',$mf_domain),
          'name'        =>  'mf_field[option][notype]',
          'default'     =>  '',
          'options'     => '',
          'add_empty'   => false,
          'description' =>  '',
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        )
      )
    );
    
    return $data;
  }

  public function display_field( $field, $group_index = 1, $field_index = 1 ) {
    global $mf_domain;

    //If is not required this field be added a None value
    $notype = "";
    if( !$field['required_field'] ) {
      $notype = ( !empty($field['options']['notype']) ) ? $field['options']['notype'] : __( "-- None --" , $mf_domain );
    }

    $output = '';

    $type        = $field['options']['post_type'];
    $order       = $field['options']['order'];
    $field_order = $field['options']['field_order'];

    $options = get_posts( sprintf("post_type=%s&numberposts=-1&order=%s&orderby=%s&suppress_filters=0",$type,$order,$field_order) );
    $output = '<div class="mf-dropdown-box">';

    $value = $field['input_value'];

    $output .= sprintf('<select class="dropdown_mf" id="%s" name="%s" >',$field['input_id'],$field['input_name']);

    if( $notype != "" ) {
      $output .= "<option value=''>$notype</option>";
    }
  
    foreach($options as $option) {
      $check = ($option->ID == $value) ? 'selected="selected"' : '';

      $output .= sprintf('<option value="%s" %s >%s</option>',
        esc_attr($option->ID),
        $check,
        esc_attr($option->post_title)
      );
    }
    $output .= '</select>';
    $output .= '</div>';

    return $output;
  }
}
