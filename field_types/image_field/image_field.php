<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class image_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = TRUE;


  function get_properties() {
    return  array(
      'js'  => TRUE,
      'js_dependencies' => array(), 
      'css' => FALSE
    );
  }

  public function _update_description(){
    global $mf_domain;
    $this->description = __("Elegant and powerful upload image with creation of thumbnails",$mf_domain);
  }
  
  public function _options(){
    global $mf_domain;
    
    $data = array(
      'option'  => array(
        'css_class'  => array(
          'type'        =>  'text',
          'id'          =>  'image_css_class',
          'label'       =>  __('Css Class',$mf_domain),
          'name'        =>  'mf_field[option][css_class]',
          'default'     =>  '',
          'description' =>  '',
          'value'       =>  'magic_fields',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'max_height'  => array(
          'type'        =>  'text',
          'id'          =>  'image_max_height',
          'label'       =>  __('Max height',$mf_domain),
          'name'        =>  'mf_field[option][max_height]',
          'default'     =>  '',
          'description' =>  'value for thumbnail image',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'max_width'  => array(
          'type'        =>  'text',
          'id'          =>  'image_max_width',
          'label'       =>  __('Max Width',$mf_domain),
          'name'        =>  'mf_field[option][max_width]',
          'default'     =>  '',
          'description' =>  'value for thumbnail image',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        ),
        'custom'  => array(
          'type'        =>  'text',
          'id'          =>  'image_custom',
          'label'       =>  __('Custom',$mf_domain),
          'name'        =>  'mf_field[option][custom]',
          'default'     =>  '',
          'description' =>  'value for thumbnail image (eg zc=1,q=100 or zc=0&q=50 )',
          'value'       =>  '',
          'div_class'   =>  '',
          'class'       =>  ''
        )
      )
    );
    
    return $data;
  }

  public function display_field( $field, $group_index = 1, $field_index = 1){
    global $mf_domain;
    
    $field_style = '';
    $imageThumbID = "img_thumb_".$field['input_id']; 
    if(!$field['input_value']){
      $value = sprintf('%simages/noimage.jpg',MF_URL);
      $field_style = 'style="display:none;"';
    }else{
      $value = sprintf("%s?src=%s%s&w=150&h=120&zc=1",PHPTHUMB,MF_FILES_URL,$field['input_value']);
    }
 
    $value  = sprintf('<img src="%s" id="%s" />',$value,$imageThumbID);

    $out  = '<div class="image_layout">';
    $out .= '<div class="image_photo"><div class="image_wrap">';
    $out .= $value;
    $out .= '</div>';
    $out .= sprintf('<div id="photo_edit_link_%s"  %s class="photo_edit_link">',$field['input_id'],$field_style);
    $out .= sprintf('<a href="%s" target="_blank" id="edit-%s" >%s</a> | ',MF_FILES_URL.$field['input_value'],$field['input_id'],__('View',$mf_domain));
    $out .= sprintf('<a href="#remove" class="remove remove_photo" id="remove-%s" >%s</a>',$field['input_id'],__('Delete',$mf_domain));
    $out .= '</div>';
    $out .='</div>';
    $out .= '<div class="image_input">';
    $out .= '<div class="mf_custom_field">';
    $out .= sprintf('<div id="response-%s" style="display:none;" ></div>',$field['input_id']);
    $out .= sprintf('<input type="hidden" value="%s" name="%s" id="%s" %s >',$field['input_value'],$field['input_name'],$field['input_id'],$field['input_validate']);
    $out .= $this->upload($field['input_id'],'image','mf_image_callback_upload');
    $out .= '</div></div>';
    $out .= '</div>';

    return $out;
  }
  
}
