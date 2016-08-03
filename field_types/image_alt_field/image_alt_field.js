function remove_resp_alt(field_id){
  jQuery(field_id).fadeOut('slow', function(){
    jQuery(this).empty();
  });
}

jQuery(document).on('click','.remove_photo_alt', function(){
  var message = jQuery(this).attr('alt');
  if(confirm(message)){
    var pattern =  /remove\-(.+)/i;
    var id = jQuery(this).attr('id');
    id = pattern.exec(id);
    id = id[1];

    jQuery('#'+id).val('');
    jQuery('#photo_edit_link_'+id).hide();
    jQuery("#img_thumb_"+id).attr("src",mf_js.mf_url+"images/noimage.jpg");
  }
});

jQuery.mf_bind('add',function() {

  var mf_upload_handler = function(){
    var parent = jQuery(this).parent();
    var inputFile = jQuery(this);

    var childHide = parent.children('input[type=hidden]');
    var field_id = childHide.attr('id');

    var formData = new FormData();
    formData.append("action", "upload-attachment");
    var myFile = jQuery(this).prop('files');
    formData.append("file", myFile[0]);
    formData.append("fileName", myFile[0].name);
    formData.append('action','mf_call');
    formData.append('type','upload_ajax');
    formData.append('security',mf_js.mf_nonce_ajax);

    // add token

    jQuery.ajax({
      url: ajaxurl,
      type: 'POST',
      async: true,
      dataType: 'json',
      processData: false,
      contentType: false,
      enctype: 'multipart/form-data',
      data: formData,
      success: function(response){
        if(response.success){
          var image_thumb = response.thumb;
          jQuery('#img_thumb_'+field_id).attr('src',image_thumb);
          jQuery('#edit-'+field_id).attr('href',response.file_url);
          jQuery('#'+field_id).val(response.name);

          var success_resp = '<span class="mf-upload-success" >'+response.msg+'</span>';
          jQuery('#response-'+field_id).html(success_resp).show();
          jQuery('#photo_edit_link_'+field_id).show();

          setTimeout("remove_resp_alt('#response-"+field_id+"')",5000);

        }else{
          //show error
          var error_resp = '<span class="mf-upload-error" >'+response.msg+'</span>';
          jQuery('#response-'+field_id).html(error_resp).show();
          setTimeout("remove_resp_alt('#response-"+field_id+"')",5000);
        }
        inputFile.replaceWith(inputFile.clone(true));
      },
      error: function(xhr,status,error){
        alert(error);
        inputFile.replaceWith(inputFile.clone(true));
      }
    });
  }

  // jQuery(".up_ajax").unbind( "change", mf_upload_handler );
  jQuery(".up_ajax").off();
  jQuery(".up_ajax").bind("change",mf_upload_handler);

});
