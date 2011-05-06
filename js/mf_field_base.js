(function( $ ){
  var mf_stack = [];
  var methods = {
    init : function( options ) {
      $.each(mf_stack, function(indice, valor) {
        try{
          if (typeof valor == "function") valor();
          else
            eval(valor);
        }catch(err){
          $.error(err);
        }
      });
    },
    duplicate : function( ) {
      $.each(mf_stack, function(indice, valor) {
        try{
          if (typeof valor == "function") valor();
          else
            eval(valor);
        }catch(err){
          $.error(err);
        }
      });
    },
    add: function ( content) {
      mf_stack.push(content);
    }
  };

  $.fn.mf_bind = function( method ) {
    if ( methods[method] ) {
      return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.mf_bind' );
    }
  };
})( jQuery );
jQuery.mf_bind = function(method, content){
  jQuery().mf_bind(method,content);
}

jQuery(document).ready(function($) {
  $.mf_bind();

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
   var item =  pattern.exec(id);
   div = '#mf_field_' + item[1] + '_ui';

   group_id = item[2];
   group_index = item[3];
   field_id = item[4];

   div_group_id = '#mf_group_field_'+group_id+'_'+group_index+'_'+field_id;
   if($(div_group_id).children('div.mf-field-ui').length > 1){
     deleteGroupDuplicate(div,div_group_id);
   }else{
     /*debemos usar las notificaciones que antes teniamos para mostrar este mensaje*/
     $(div_group_id).find('a.delete_duplicate_field').fadeOut({duration: "slow"});
   }
  });

  $('a.delete_duplicate_button').live('click', function(){
    id = jQuery(this).attr("id");
    pattern =  /delete\_group\_repeat\-(([0-9]+)\_([0-9]+))/i;
    var item =  pattern.exec(id);

    div = '#mf_group_'+item[1];
    var parent = $(div);
    parent.fadeOut({
      duration: "normal",
      complete: function() {
        parent.remove();
        fixCounterGroup('#mf_group-'+item[2]);
      }
    });
  });

  $('a.duplicate-field').live("click",function(){
    id = jQuery(this).attr("id");
    pattern =  /mf\_field\_repeat\-(([0-9]+)\_([0-9]+)\_([0-9]+)\_([0-9]+))/i;
    var item =  pattern.exec(id);

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
        fixcounter('#mf_group_field_'+group_id+'_'+group_index+'_'+field_id);
        $.mf_bind('duplicate');
      }
    });
  });

  $('a.duplicate_button').live('click', function(){
    id = jQuery(this).attr('id');
    pattern =  /mf\_group\_repeat\-(([0-9]+)\_([0-9]+))/i;
    var item = pattern.exec(id);

    group_id = item[2];
    counter_group_id = '#mf_group_counter_'+group_id;
    group_index = parseInt($(counter_group_id).val()) + 1;

    jQuery.ajax({
      url: ajaxurl,
      type: 'POST',
      data: "action=mf_call&type=group_duplicate&group_id="+group_id+"&group_index="+group_index,
      success: function(response){
        $(counter_group_id).before(response);
        $(counter_group_id).val(group_index);
        fixCounterGroup('#mf_group-'+group_id);
        $.mf_bind('duplicate');
      }
    });
  });

});

deleteGroupDuplicate = function(div,div_group_id){
  var parent = jQuery(div);
  parent.fadeOut({
    duration: "normal",
    complete: function() {
      parent.remove();
      fixcounter(div_group_id);
    }
  });
}

fixcounter = function(field_class){
  init = 1;
  div_content_field = jQuery(field_class).children('div.mf-field-ui');
  jQuery.each(div_content_field,function(key,value){
    jQuery(this).find('span.mf-field-count').text(key+1);
      if(key == 0){
        jQuery(this).find('span.name em').hide();
      }
  });
  if(div_content_field.length == 1){
    jQuery(field_class).find('div.mf-duplicate-controls a.delete_duplicate_field').hide();
  }else{
    jQuery(field_class).find('div.mf-duplicate-controls a.delete_duplicate_field').show();
  }
}

fixCounterGroup = function(div_group){
  div_content = jQuery(div_group).children('div.mf_group');
  jQuery.each(div_content, function(key,value){
    jQuery(this).find('span.mf-counter').text(key+1);
  });

  if(div_content.length == 1){
    jQuery(div_group).find('a.delete_duplicate_button').hide();
  }else{
    jQuery(div_group).find('a.delete_duplicate_button').show();
  }
}
