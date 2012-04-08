function mf_file_callback_upload(data){
  
  if(data.error == false){
    //como aun tiene jquery 1.4 aun no tiene prop
    var image_thumb = data.phpthumb;
    image_thumb += '?&w=150&h=120&zc=1&src=';
    image_thumb += data.file_url;
    
    //jQuery('#img_thumb_'+data.field_id).attr('src',image_thumb);
    jQuery('#edit-'+data.field_id).attr('href',data.file_url);
    jQuery('#'+data.field_id).val(data.name);

    // display filename
    jQuery('#filename_'+data.field_id).empty();
    var p = document.createElement("p");
    var txt = document.createTextNode(data.name);
    p.appendChild(txt);
    jQuery('#filename_'+data.field_id).append(p);
    
    var success_resp = '<span class="mf-upload-success" >'+data.msg+'</span>';
    jQuery('#response-'+data.field_id).html(success_resp).show();
    jQuery('#photo_edit_link_'+data.field_id).show();

    setTimeout("remove_resp('#response-"+data.field_id+"')",5000);
    
  }else{
    //show errors
    var error_resp = '<span class="mf-upload-error" >'+data.msg+'</span>';
    jQuery('#response-'+data.field_id).html(error_resp).show();
    setTimeout("remove_resp('#response-"+data.field_id+"')",5000);
  }
  
}

function remove_resp(field_id){
  jQuery(field_id).fadeOut('slow', function(){
    jQuery(this).empty();
  });
}

jQuery('.remove_file').live('click', function(){
  if(confirm("Are you sure?")){
    var pattern =  /remove\-(.+)/i;
    var id = jQuery(this).attr('id');
    id = pattern.exec(id);
    id = id[1];

    //todo añadir al arreglo de estan los files a borrar
    jQuery('#'+id).val('');
    jQuery('#photo_edit_link_'+id).hide();
    
    // remove filename
    jQuery('#filename_'+id).empty();
    //jQuery("#img_thumb_"+id).attr("src",mf_js.mf_url+"images/noimage.jpg");
  }
});
