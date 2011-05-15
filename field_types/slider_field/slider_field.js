jQuery.mf_bind('add',function() {
  jQuery('.mf_slider_field').not('.mf_processed').each(function(index,value) {
    jQuery(value).addClass('mf_processed');
    

    data = jQuery(value).metadata({type:'attr',name:'data'});
    slider_id = jQuery(value).attr('id');

    jQuery(value).slider({
      value: parseFloat(data.value),
      step: parseFloat(data.stepping),
      min: parseFloat(data.min),
      max: parseFloat(data.max),
      range: false,
      slide: function(e, ui) {
        exp = /slider_(.*)/g

        slider_id = jQuery(value).attr('id');
        field_id  = exp.exec(slider_id)[1];


        jQuery('#'+slider_id+' a').empty();
        jQuery('#'+slider_id+' a').append("<span class=\"slider_value\">"+ui.value+"</span>");
        jQuery('#'+field_id).val(ui.value);
      }
    });
    
    jQuery('#'+slider_id+' a').append("<span class=\"slider_value\">"+data.value+"</span>");

  });
});
