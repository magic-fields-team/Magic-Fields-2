jQuery(document).ready(function($){
  $(".remove_image_media").live('click',function(){
    if(confirm("Are you sure?")){
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