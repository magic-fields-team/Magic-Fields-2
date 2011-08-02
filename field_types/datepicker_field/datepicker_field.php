<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class datepicker_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;

    function get_properties() {
    return  array(
      'js'  => TRUE,
      'js_dependencies' => array(),
      'js_internal_dependencies' => array(
        'jquery',
        'jquery-ui-core',
      ),
      'js_internal' =>'ui.datepicker.js',
      'css' => FALSE,
      'css_internal' => 'ui.datepicker.css'
    );
  }

  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Elegant and powerful datepicker",$mf_domain);
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
      'm.d.y'     => '4.20.08',
      'd.m.Y'          => '20.04.2008'
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

  public function display_field($field, $group_index = 1, $field_index =1){

    $format = $field['options']['format'];
    $value = $value_raw = '';
    if($field['input_value']){
      $value_raw = $field['input_value'];
      $value =   $value = date($format,strtotime($value_raw));
    }

    $output = '';
    $today = date($format);
    $today_field = date('Y-m-d');
  
    $output .= sprintf('<div id="format_date_field_%s" style="display:none;">%s</div>',$field['input_id'],$format);
    $output .= sprintf('<input id="display_date_field_%s" value="%s" type="text" class="datepicker_mf" readonly="readonly" />',$field['input_id'],$value);
    $output .= sprintf('<input id="date_field_%s" value="%s" name="%s" type="hidden" %s />',$field['input_id'],$value_raw,$field['input_name'],$field['input_validate']);
    $output .= sprintf('<input type="button" value="Pick..." id="pick_%s" class="datebotton_mf button" />',$field['input_id']);
    $output .= sprintf('<input type="button" id="today_%s" value="Today" alt="%s" rel="%s" class="todaybotton_mf button"/>',$field['input_id'],$today,$today_field);
    $output .= sprintf('<input 	type="button" id="blank_%s"value="Blank" class="blankBotton_mf button"/>',$field['input_id']);

    return $output;
  }
  
}
