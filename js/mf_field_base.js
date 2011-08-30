(function( $ ){
  var mf_stack = [];
  var mf_before_sort = [];
  var mf_after_sort = [];
  var mf_before_save = [];
  var methods = {
    init : function( options ) { methods.callback_execute_js(mf_stack); },
    duplicate : function( ) { methods.callback_execute_js(mf_stack); },
    add: function( content){ mf_stack.push(content); },
    before_sort: function(content){ mf_before_sort.push(content); },
    after_sort: function(content){ mf_after_sort.push(content); },
    before_save: function(content){ mf_before_save.push(content); },
    callback_before_sort: function(){ methods.callback_execute_js(mf_before_sort); },
    callback_after_sort: function(){ methods.callback_execute_js(mf_after_sort); },
    callback_before_save: function(){ methods.callback_execute_js(mf_before_save); },
    callback_execute_js: function( mf_var){
      $.each(mf_var, function(indice, valor) {
        try{
          if (typeof valor == "function") valor();
          else
            eval(valor);
        }catch(err){
          $.error(err);
        }
      });
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

  // duplicate field
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
        var newel = jQuery(response);
        $(counter_id).before(newel);
        newel.find('.mf_message_error .error_magicfields').hide();
        $(counter_id).val(field_index);
        fixcounter('#mf_group_field_'+group_id+'_'+group_index+'_'+field_id);
        $.mf_bind('duplicate');
      }
    });
  });

  //duplicate group
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
        var newel = jQuery(response);
        $(counter_group_id).before(newel);
        newel.find('.mf_message_error .error_magicfields').hide();
        $(counter_group_id).val(group_index);
        fixCounterGroup('#mf_group-'+group_id);
        $.mf_bind('duplicate');
      }
    });
  });

  //add validation for fields
  $('.mf_message_error .error_magicfields').hide();
  $.metadata.setType("attr", "validate");
  
  //Validating the post
  $("#post").validate({
    errorClass: "error_magicfields",
    invalidHandler: function(form, validator) { 
      var errors = validator.numberOfInvalids();
      if (errors) {
        $('#mf-publish-errors').remove();
        $('#publishing-action #ajax-loading').hide();
        $('#publishing-action #publish').removeClass("button-primary-disabled");
        $('#major-publishing-actions').append( $('<div id="mf-publish-errors">'+mf_js.mf_validation_error_msg+'</div>') ); 
      }
    },
	  submitHandler: function(form) {
      $('#mf-publish-errors').remove();
        form.submit();
      }
  });
  
  var mf_groups = $('.mf_group');
  mf_groups.find("input[type=text],textarea").live("keydown", fieldchange);
  mf_groups.find("input[type=checkbox],input[type=radio]").live("click", fieldchange);
  mf_groups.find("select").live("change", fieldchange);
  
  //callback before save
  $("#publish").live('click',function(){ $.mf_bind('callback_before_save'); });

  //Post saved as Draft don't require validations
  $('#save-post').click(function(){
    //bypass the validation calling directly the submit action from the dom
    $('#post')[0].submit();

    //hiding the error messages
    //this messages  will be printed no matter if the validation was bypassed
    $('.mf_message_error').hide();
    mf_js.mf_validation_error_msg = "Saving a draft..";
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

fieldchange = function() {
  jQuery('#mf-publish-errors').hide();
}
