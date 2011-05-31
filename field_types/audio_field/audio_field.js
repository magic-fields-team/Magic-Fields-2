function mf_audio_callback_upload(data){
  
  if(data.error == false){
    //como aun tiene jquery 1.4 aun no tiene prop
    var image_thumb = data.phpthumb;
    image_thumb += '?&w=150&h=120&zc=1&src=';
    image_thumb += data.file_url;
    player_audio = set_player(data.encode_file_url);

    jQuery('#obj-'+data.field_id).html(player_audio);
    jQuery('#edit-'+data.field_id).attr('href',data.file_url);
    jQuery('#'+data.field_id).val(data.name);
    
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

function set_player(url){
  var player = '';
  player += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="95%" height="20" wmode="transparent" \><param name="movie" value="'+mf_js.mf_player_url+'?file='+url+'" wmode="transparent" \><param name="quality" value="high" wmode="transparent" \><embed src="'+mf_js.mf_player_url+'?file='+url+'" width="100%" height="20" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" wmode="transparent" ></embed></object>';
  return player;
}

function remove_resp(field_id){
  jQuery(field_id).fadeOut('slow', function(){
    jQuery(this).empty();
  });
}

jQuery('.remove_audio').live('click', function(){
  if(confirm("Are you sure?")){
    var pattern =  /remove\-(.+)/i;
    var id = jQuery(this).attr('id');
    id = pattern.exec(id);
    id = id[1];

    //todo añadir al arreglo de estan los files a borrar
    jQuery('#'+id).val('');
    jQuery('#photo_edit_link_'+id).hide();
    jQuery("#obj-"+id).empty();
  }
});