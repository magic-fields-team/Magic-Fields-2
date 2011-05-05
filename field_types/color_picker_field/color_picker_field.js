jQuery(document).ready(function($) {
  add_color_picker();
  mf_callback.push('add_color_picker();');
});

add_color_picker = function(){
  jQuery('.mf_colorpicker').each(function(index,value) {
    exp =  /colorpicker\_(.*)/g;
    field_id = exp.exec(jQuery(value).attr('id'))[1];
    div_color = "#"+jQuery(value).attr('id');
    field_id = 'colorpicker_value_'+field_id;
    jQuery(div_color).farbtastic('#'+field_id);
  });
}
