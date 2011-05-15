jQuery(document).ready(function($){
  $(".mf-group-wrapper").sortable({
    handle: '.sortable_mf',
    start: function(){ $.mf_bind('callback_before_sort'); },
    stop: function(){ 
      id =  jQuery(this).attr("id").split("-")[1];
      fixCounterGroup('#mf_group-'+id);
      $.mf_bind('callback_after_sort');
    }
  });
});