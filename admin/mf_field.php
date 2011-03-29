<?php
// initialisation
global $mf_domain;

// class base of fields

class mf_field {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;
  public $description = '';
  public $options = array();
  
  public function __construct() {
    $this->_update_description();
    $this->_update_options();
  }
  
  public function _update_description(){
    global $mf_domain;
    $this->description = __("Base field",$mf_domain);
  }
  
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
  
  public function get_options(){
    
    if($this->has_properties){
      //aqui deberiamos saber si el campos ya esta en el sistema y pedir los datos actuales
      // por el momento solo renderamos el formulario sin datos
      //$options = $this->get_data_options();
      $this->form_options();
    }
    
    return false;
  }
  
  public function form_options(){

    if(isset($this->options['option'])){
      foreach($this->options['option'] as $option){
        printf('<div class="form-field mf_form %s">',$option['div_class']);
        if($option['type'] == 'text'){
          mf_form_text($option);
        }elseif($option['type'] == 'select'){
          mf_form_select($option);
        }elseif( $option['type'] == 'checkbox' ){
          mf_form_checkbox($option);
        }elseif( $option['type'] == 'textarea' ){
          mf_form_textarea($option);
        }
        printf('</div>');
      }
    }
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'text_option'  => array(
          'type'        => 'text',
          'id'          => 'text_id',
          'label'       => __('label for text(input)',$mf_domain),
          'name'        => 'mf_field[option][text_option]',
          'description' => __( 'aqui una descripcion', $mf_domain ),
          'value'       => 'default value',
          'div_class'   => 'class_text',
          'class'       => 'div_class_text'
        ),
        'checkbox_option' => array(
          'type'        => 'checkbox',
          'id'          => 'checkbox_id',
          'label'       => __('label for checkbox',$mf_domain),
          'name'        => 'mf_field[option][checkbox_option]',
          'value'       => 1,
          'description' => __('One description for checkbox',$mf_domain),
          'class'       => 'class_checkbox',
          'div_class'   => 'div_class_checkbox'
        ),
        'select_option' =>  array(
          'type'        => 'select',
          'id'          => 'select_id',
          'label'       =>  __('label for select', $mf_domain), 
          'name'        =>  'mf_field[option][select_option]',
          'value'       => '',
          'description' =>  __( 'description for select', $mf_domain ),
          'options'     => array('one','two','more'),
          'add_empty'   => true,
          'div_class'   => 'class_select',
          'class'       => 'div_class_select'
        ),
        'textarea_option' =>  array(
          'type'        => 'textarea',
          'id'          => 'textarea_id',
          'label'       =>  __('Label for textarea', $mf_domain), 
          'name'        =>  'mf_field[option][textarea_option]',
          'value'       => 'uno value',
          'description' =>  __( 'description for textarea', $mf_domain ),
          'div_class'   => 'class_textarea',
          'class'       => 'div_class_textarea'
        )
      )
    );
    
    return $data;
  }
  
  public function _update_options(){
    $this->options = $this->_options();
  }
  
}