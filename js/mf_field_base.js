jQuery(document).ready(function($) {

  var tt_template =
   '<div class="tt"> \
    <div class="tthl"><div class="tthr"><div class="tth"></div></div></div> \
    <div class="ttbl"><div class="ttbr"><div class="ttb"><div class="ttbc">#{content}</div></div></div></div> \
    <div class="ttfl"><div class="ttfr"><div class="ttf"></div></div></div> \
    </div>';

  //tooltip
  $('small.mf_tip').live('mouseenter mouseleave', function(event) {
    if (event.type == 'mouseenter') {
       var ht = $.trim($(this).children('span.mf_helptext').html());
       if (ht && ht != "") {
          var tt = $($.tmpl(tt_template, { content: ht }));
          var tip = $(this).offset();
          var tip_left = tip.left - 13;
          var tip_top = tip.top + 5;
          $(this).children('span.mf_helptext').after(tt).hide();

          var tip_height = $(this).children('div.tt').height();;
          tip_top -= tip_height;

          $(this).children('div.tt').offset({ top: tip_top, left: tip_left}).show();
       }
    } else {
      $(this).children('div.tt').remove();
    }
  });

  $('.delete_duplicate_field').live("click", function(event){
   id = jQuery(this).attr("id");
   pattern =  /delete\_field\_repeat\-(([0-9]+)\_([0-9]+)\_([0-9]+)\_([0-9]+))/i;
   item =  pattern.exec(id);
   div = 'mf_field_' + item[1] + '_ui';

   group_id = item[2];
   group_index = item[3];
   field_id = item[4];

   div_group_id = '#mf_group_field_'+group_id+'_'+group_index+'_'+field_id;
   if($(div_group_id).children('div.mf-field-ui').length > 1){
     deleteGroupDuplicate(div);
   }else{
     /*debemos usar las notificaciones que antes teniamos para mostrar este mensaje*/
     $(div_group_id).find('a.delete_duplicate_field').fadeOut({duration: "slow"});
//     alert('mensaje que no puedes dejar solo el grupo');
   }
  });

  $('a.duplicate-field').live("click",function(){
    id = jQuery(this).attr("id");
    pattern =  /mf\_field\_repeat\-(([0-9]+)\_([0-9]+)\_([0-9]+)\_([0-9]+))/i;
    item =  pattern.exec(id);

    group_id = item[2];
    group_index = item[3];
    field_id = item[4];
    counter_id = '#mf_counter_'+group_id+'_'+group_index+'_'+field_id;
    field_index = parseInt($(counter_id).val()) + 1;

    jQuery.ajax({
      url: ajaxurl,
      type: 'POST',
      data: "action=mf_call&type=field_duplicate&group_id="+group_id+"&group_index="+group_index+"&field_id="+field_id+"&field_index="+field_index,
      success: function(response){
        $(counter_id).before(response);
        $(counter_id).val(field_index);
        fixcounter('mf_group_field_'+group_id+'_'+group_index+'_'+field_id);
      }
    });
  });

});

deleteGroupDuplicate = function(div){
    var parent = jQuery("#"+div);
    /*var db = parent.find(".duplicate_button").clone();*/
    /*var context = parent.closest(".write_panel_wrapper");*/
    parent.fadeOut({
      duration: "normal",
      complete: function() {
        parent.remove();
        /*context.mf_group_update_count();
        context.mf_group_show_save_warning();
        moveAddToLast(context, db);*/
      }
    });
}

fixcounter = function(field_class){
  init = 1;
  jQuery.each(jQuery('#'+field_class).children('div.mf-field-ui'),function(key,value){
    if(key > 0){
      jQuery(this).find('span.mf-field-count').text(key+1);;
    }
  });
  jQuery('#'+field_class).find('div.mf-duplicate-controls a.delete_duplicate_field').show();
}

