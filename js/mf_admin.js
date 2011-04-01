jQuery(document).ready(function($) {
  //Custom Validataion methods 
  jQuery.validator.addMethod( "lowercase", function(value, element) {
    return this.optional(element) || /^[0-9a-z\_]+$/.test(value);
  },'Only  are accepted lowercase characters,numbers or underscores');

  $('#addPostType').validate({meta:"validate"});
  $('#addCustomTaxonomy').validate({meta:"validate"});
  $('#addCustomGroup').validate({meta:"validate"});
  $('#addCustomField').validate({meta:"validate"});


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
