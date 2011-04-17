<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class related_type_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple Relared type input",$mf_domain);
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
        )
      )
    );
    
    return $data;
  }
  
}
