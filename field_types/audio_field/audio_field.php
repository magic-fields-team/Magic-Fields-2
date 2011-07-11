<?php
// initialisation
global $mf_domain;


// class with static properties encapsulating functions for the field type

class audio_field extends mf_custom_fields {

  public $allow_multiple = TRUE;
  public $has_properties = FALSE;

  function get_properties() {
    return  array(
      'js'  => TRUE,
      'js_dependencies' => array(), 
      'css' => FALSE
    );
  }

  public function _update_description(){
    global $mf_domain;
    $this->description = __("Audio upload with player",$mf_domain);
  }

  public function display_field( $field, $group_index = 1, $field_index = 1){
    global $mf_domain;
    
    $field_style = '';
    $imageThumbID = "img_thumb_".$field['input_id']; 
    if(!$field['input_value']){
      $value = '';
      $field_style = 'style="display:none;"';
    }else{
      $audio = sprintf("%s%s",MF_FILES_URL,$field['input_value']);
      $value = $value = stripslashes(trim("\<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase='\http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0\' width=\'95%\' height=\'20\' wmode=\'transparent\' \>\<param name=\'movie\' value=\'".MF_URL."js/singlemp3player.swf?file=".urlencode($audio)."\' wmode=\'transparent\' /\>\<param name=\'quality\' value=\'high\' wmode=\'transparent\' /\>\<embed src=\'".MF_URL."js/singlemp3player.swf?file=".urlencode($audio)."' width=\'100\%\' height=\'20\' quality=\'high\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\' type=\'application/x-shockwave-flash\' wmode=\'transparent\' \>\</embed\>\</object\>"));
    }

    $out  = '<div class="image_layout">';
    $out .= sprintf('<div class="audio_player image_photo"><div id="obj-%s" class="audio_wrap">',$field['input_id']);
    $out .= $value;
    $out .= '</div>';
    $out .= sprintf('<div id="photo_edit_link_%s"  %s class="photo_edit_link">',$field['input_id'],$field_style);
    $out .= sprintf('<a href="%s" target="_blank" id="edit-%s" >%s</a> | ',MF_FILES_URL.$field['input_value'],$field['input_id'],__('Download',$mf_domain));
    $out .= sprintf('<a href="#remove" class="remove remove_audio" id="remove-%s" >%s</a>',$field['input_id'],__('Delete',$mf_domain));
    $out .= '</div>';
    $out .='</div>';
    $out .= '<div class="image_input audio_frame">';
    $out .= '<div class="mf_custom_field">';
    $out .= sprintf('<div id="response-%s" style="display:none;" ></div>',$field['input_id']);
    $out .= sprintf('<input type="hidden" id="%s" name="%s" value="%s" %s >',$field['input_id'],$field['input_name'],$field['input_value'],$field['input_validate']);
    $out .= $this->upload($field['input_id'],'audio','mf_audio_callback_upload');
    $out .= '</div></div>';
    $out .= '</div>';
    return $out;
  }
  
}
