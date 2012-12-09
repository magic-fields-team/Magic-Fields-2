jQuery.mf_bind('add',function(){
  jQuery('.mf_colorpicker').each(function(index,value) {
    exp =  /colorpicker\_(.*)/g;
    field_id = exp.exec(jQuery(value).attr('id'))[1];
    div_color = "#"+jQuery(value).attr('id');
    field_id = 'colorpicker_value_'+field_id;
    jQuery(div_color).farbtastic('#'+field_id);
  });
});

jQuery('.clrpckr').live('focusin focusout dblclick', function(e){
  var picker = jQuery(this).siblings('.mf_colorpicker');
      if ( e.type == 'focusout' ) {
        picker.stop(true, true).slideUp();
      } else {
        picker.stop(true, true).slideToggle();
      }
      jQuery('.mf_colorpicker').not(picker).slideUp();
});

jQuery(document).keyup(function(e){
  if(e.keyCode === 27) jQuery(".mf_colorpicker").slideUp();
});
