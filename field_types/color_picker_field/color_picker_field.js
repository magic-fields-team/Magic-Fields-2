jQuery(document).ready(function($) {
  $('.mf_colorpicker').each(function(index,value) {
    exp =  /colorpicker\_(.*)/g;
    field_id = exp.exec($(value).attr('id'))[1];
    field_id = 'colorpicker_value_'+field_id;
    $('.mf_colorpicker').farbtastic('#'+field_id);
  });
});
