jQuery(document).ready(function($) {
  //Custom Validataion methods 
  jQuery.validator.addMethod( "lowercase", function(value, element) {
    return this.optional(element) || /^[0-9a-z\_]+$/.test(value);
  },'Only  are accepted lowercase characters,numbers or underscores');

  $('#addPostType').validate({meta:"validate"});
  $('#addCustomTaxonomy').validate({meta:"validate"});
  $('#addCustomGroup').validate({meta:"validate"});
  $('#addCustomField').validate({meta:"validate"});

  //validation type(name)
  $('#addPostType').submit(function(){
    name = $('#posttype-type').val();
    id = $('#posttype-id').val();
    var status = 0;
     
     jQuery.ajax({
       url: ajaxurl,
       type: 'POST',
       async: false,
       dataType: 'json',
       data: "action=check_field_type&post_type="+name+"&post_type_id="+id,
       success: function(response){
         $("#message_post_type").hide();
         if(response.success){
           status = 1;
         }else{
           $('#message_post_type p').empty().append(response.msg);
           $("#message_post_type").show();
           $("#posttype-type").focus();
         }
       }
     });

    if(status)
      return true;

    return false;
  });
  
  //validation custom group
  $('#addCustomGroup').submit(function(){
    name = $("#custom_group_name").val();
    group_id = $("#custom_group_id").val();
    post_type = $("#custom_group_post_type").val();
    var status = 0;
    if(name){
      jQuery.ajax({
         url: ajaxurl,
         type: 'POST',
         async: false,
         dataType: 'json',
         data: "action=check_custom_group&group_name="+name+"&post_type="+post_type+"&group_id="+group_id,
         success: function(response){
           $("#message_mf_error").hide();
           if(response.success){
             status = 1;
           }else{
             $('#message_mf_error p').empty().append(response.msg);
             $("#message_mf_error").show();
             $("#custom_group_name").focus();
           }
         }
       });
    }
    if(status)
      return true;

    return false;
        
  });
  
  //validation custom field
  $('#addCustomField').submit(function(){
    name = $("#customfield-name").val();
    field_id = $("#customfield_id").val();
    post_type = $("#customfield-post_type").val();
    var status = 0;
    if(name){
      jQuery.ajax({
         url: ajaxurl,
         type: 'POST',
         async: false,
         dataType: 'json',
         data: "action=mf_check_custom_field&field_name="+name+"&post_type="+post_type+"&field_id="+field_id,
         success: function(response){
           $("#message_mf_error").hide();
           if(response.success){
             status = 1;
           }else{
             $('#message_mf_error p').empty().append(response.msg);
             $("#message_mf_error").show();
             $("#customfield-name").focus();
           }
         }
       });
    }
    if(status)
      return true;
      
    return false;    
  });
  
  //validation custom taxonomy
  $('#addCustomTaxonomy').submit(function(){
    type = $("#custom-taxonomy-type").val();
    taxonomy_id = $("#custom-taxonomy-id").val();
    var status = 0;
    if(name){
      jQuery.ajax({
         url: ajaxurl,
         type: 'POST',
         async: false,
         dataType: 'json',
         data: "action=mf_check_custom_taxonomy&taxonomy_type="+type+"&taxonomy_id="+taxonomy_id,
         success: function(response){
           $("#message_mf_error").hide();
           if(response.success){
             status = 1;
           }else{
             $('#message_mf_error p').empty().append(response.msg);
             $("#message_mf_error").show();
             $("#custom-taxonomy-type").focus();
           }
         }
       });
    }
    if(status)
      return true;
      
    return false;    
  });
  
  //Confirm for display a confirm box 
  $('.mf_confirm').click(function() {
    message = $(this).attr('alt');     

    return confirm_message(message);
  });
  
});

confirm_message = function(message) {

  if( confirm( message ) ){
    return true;
  } else {
    return false;
  }
}
