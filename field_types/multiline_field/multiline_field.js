jQuery.mf_bind('add',function(){
  if('undefined' != typeof tinyMCEPreInit){

    if ( typeof tinymce !== 'undefined' ) {
        for ( id in tinyMCEPreInit.mceInit ) {
          if (tinyMCEPreInit.mceInit.hasOwnProperty(id)) {
            init = tinyMCEPreInit.mceInit[id];
            $wrap = tinymce.$( '#wp-' + id + '-wrap' );

            if ( $wrap.hasClass( 'html-active' ) ){
              for ( id in tinyMCEPreInit.mceInit ) {
                if (tinyMCEPreInit.mceInit.hasOwnProperty(id)) {
                  init = tinyMCEPreInit.mceInit[id];
                  tinymce.init( init );
                }
              }

              // yeah, I know, this is ugly but works :s
              setTimeout(function(){
                jQuery("button#content-html").click();
              }, 1500);
              
            }
          }
        }
      }


    jQuery(".multiline_custom_field .add_editor_mf").each( function(index,value){
      var editor_text = jQuery(this).attr('id');
      tinyMCE.execCommand(mf_js.mf_mceAddString, false, editor_text);
      jQuery(this).removeClass('add_editor_mf');
    });
  }
});

jQuery.mf_bind('before_sort', function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery("#"+sort_group_id+" .multiline_custom_field .pre_editor").each( function(){
      var editor_text = jQuery(this).attr('id');
      if(tinyMCE.get(editor_text)){
        tinyMCE.execCommand(mf_js.mf_mceRemoveString, false, editor_text);
        jQuery('#'+editor_text).addClass('temp_remove_editor');
      }
    });
  }
});

jQuery.mf_bind('after_sort', function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery("#"+sort_group_id+" .multiline_custom_field .temp_remove_editor").each( function(){
      var editor_text = jQuery(this).attr('id');
      tinyMCE.execCommand(mf_js.mf_mceAddString, false, editor_text);
      jQuery('#'+editor_text).removeClass('temp_remove_editor');
    });
  }
});

jQuery.mf_bind('before_save',function(){
  if('undefined' != typeof tinyMCEPreInit){
    jQuery(".multiline_custom_field .pre_editor").each(function(){
      var editor_text = jQuery(this).attr('id');
      if(tinyMCE.get(editor_text)) {
        jQuery(jQuery('#'+editor_text)).attr('value', tinyMCE.get(editor_text).getContent());
      }
    });
  }
});

// Add the editor (button)
function add_editor(id){
  if('undefined' != typeof tinyMCEPreInit){
    new_valor = jQuery('#'+id).val();
    new_valor = switchEditors.wpautop(new_valor);
    jQuery('#'+id).val(new_valor);
    tinyMCE.execCommand(mf_js.mf_mceAddString, false, id);
  }
}

// Remove the editor (button)
function del_editor(id){
  if('undefined' != typeof tinyMCEPreInit){
    tinyMCE.execCommand(mf_js.mf_mceRemoveString, false, id);
  }
}

jQuery().ready(function($){
  $('.tab_multi_mf a.edButtonHTML_mf').click( function() {
    $(this).closest(".tab_multi_mf").find(".edButtonHTML_mf").removeClass("current");
    $(this).addClass("current");
  });
});
