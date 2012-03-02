jQuery(document).ready(function($) {
  mf_suggest_labels = function () {

    $('.options_label input[name*=mf_posttype]:text').each(function(index,value) {
      var rel = $(this).attr('rel');
      // swap singular value
      var label = $('#posttype-label').val();
      rel = rel.replace('$s',label);
      // swap plural value (if exists)
      var labels = $('#posttype-labels').val();
      if(labels.length > 0){
        rel = rel.replace('$p',labels);
      }else{
        rel = rel.replace('$p',label);
      }
      $(this).val(rel);
    });
  }

  if( $('#suggest-labels:checked').val() != undefined ) {
      $('#posttype-label').change(mf_suggest_labels);
      $('#posttype-labels').change(mf_suggest_labels);
  }

  $('#suggest-labels').click(function() {
    if($('#suggest-labels:checked').val() != undefined) {
      $('#posttype-label').change(mf_suggest_labels);
      $('#posttype-labels').change(mf_suggest_labels);
    } else {
      $('#posttype-label').unbind('change');
      $('#posttype-labels').unbind('change');
    }
  });
  
  $('#options_label').click(function(){
    $('.options_label').show();
    $('.options').hide();
    $('.options-tabs li').removeClass('tabs');
    $(this).parent('li').addClass('tabs');
    return false;
  });
  $('#options').click(function(){
    $('.options').show();
    $('.options_label').hide();
    $('.options-tabs li').removeClass('tabs');
    $(this).parent('li').addClass('tabs');
    return false;
  });

});
