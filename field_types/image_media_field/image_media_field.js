jQuery(document).ready(function($){
  $(document).on('click',".remove_image_media",function(){
    var message = jQuery(this).attr('alt');
    if(confirm(message)){
         //get the  name to the image
         pattern =  /remove\-(.+)/i;
         id = jQuery(this).attr('id');
         id = pattern.exec(id);
         id = id[1];

         $('#'+id).val('');
         jQuery("#img_thumb_"+id).attr("src",mf_js.mf_url+"images/noimage.jpg");
         jQuery("#photo_edit_link_"+id).hide();
    }
  });
});