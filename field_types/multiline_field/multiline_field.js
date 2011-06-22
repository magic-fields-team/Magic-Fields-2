jQuery.mf_bind('add',function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery(".multiline_custom_field .add_editor_mf").each( function(index,value){
      var editor_text = jQuery(this).attr('id');
      tinyMCE.execCommand('mceAddControl', true, editor_text); 
      jQuery(this).removeClass('add_editor_mf');
    });
  }
});
jQuery.mf_bind('before_sort', function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery("#"+sort_group_id+" .multiline_custom_field .pre_editor").each( function(){
      var editor_text = jQuery(this).attr('id');
      if(tinyMCE.get(editor_text)){
        tinyMCE.execCommand('mceRemoveControl', false, editor_text);
        jQuery('#'+editor_text).addClass('temp_remove_editor');
      }
    });
  }
});
jQuery.mf_bind('after_sort', function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery("#"+sort_group_id+" .multiline_custom_field .temp_remove_editor").each( function(){
      var editor_text = jQuery(this).attr('id');
      tinyMCE.execCommand('mceAddControl', false, editor_text);
      jQuery('#'+editor_text).removeClass('temp_remove_editor');
    });
  }
});

jQuery.mf_bind('before_save',function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery(".multiline_custom_field .pre_editor").each(function(){
      var editor_text = jQuery(this).attr('id');
      jQuery(jQuery('#'+editor_text)).attr('value', tinyMCE.get(editor_text).getContent());
    });
  }
});

// Add the editor (button)
function add_editor(id){
  if('undefined' != typeof tinyMCEPreInit){
    new_valor = jQuery('#'+id).val();
    new_valor = switchEditors.wpautop(new_valor);
    jQuery('#'+id).val(new_valor);
    tinyMCE.execCommand('mceAddControl', false, id);
  }
}
// Remove the editor (button)
function del_editor(id){
  if('undefined' != typeof tinyMCEPreInit){
    tinyMCE.execCommand('mceRemoveControl', false, id);
  }
}

jQuery().ready(function($){
  $('.tab_multi_mf a.edButtonHTML_mf').click( function() {
    $(this).closest(".tab_multi_mf").find(".edButtonHTML_mf").removeClass("current");
    $(this).addClass("current");
  });
});