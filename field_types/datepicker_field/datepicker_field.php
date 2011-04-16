<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class datepicker_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Simple datepicker input",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $select = array(
      'm/d/Y'     => '4/20/2008',
      'l, F d, Y' => 'Sunday, April 20, 2008',
      'F d, Y'    => 'April 20, 2008',
      'm/d/y'     => '4/20/08',
      'Y-m-d'     => '2008-04-20',
      'd-M-y'     => '20-Apr-08',
      'm.d.Y'     => '4.20.2008',
      'm.d.y'     => '4.20.08'
    );

    $data = array(
      'option'  => array(
        'format'  => array(
          'type'        =>  'select',
          'id'          =>  'date_format',
          'label'       =>  __('Format',$mf_domain),
          'name'        =>  'mf_field[option][format]',
          'default'     =>  '',
          'options'     => $select,
          'add_empty'   => false,
          'description' =>  __( 'Format for date', $mf_domain ),
          'value'       =>  '',
          'div_class'   => '',
          'class'       => ''
        )
      )
    );
    
    return $data;
  }
  
}
