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
        ?>
        <div class="form-field mf_form <?php echo $option['div_class']; ?>">
        <?php echo $option['name']; ?>
        <div>
        <?php
      }
    }
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'type'  => array(
          'type'        =>  'text',
          'id'          =>  'uno',
          'label'       =>  'opcion 1',
          'name'        =>  'uno',
          'default'     =>  '',
          'description' =>  __( 'aqui una descripcion', $mf_domain ),
          'value'       =>  '',
          'div_class'    => 'clase1',
          'class'       => 'vemos1'
        ),
        'description' =>  array(
          'type'        =>  'text',
          'label'       =>  'Description', 
          'name'        =>  'description',
          'description' =>  __( 'Tell to the user about what is the field', $mf_domain ),
          'value'       =>  '',
          'id'          => 'customfield-description',
          'div_class'    => 'clase2',
          'class'       => 'veamos2'
        )
      )
    );
    
    return $data;
  }
  
  public function _update_options(){
    $this->options = $this->_options();
  }
  
}