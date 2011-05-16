jQuery(document).ready(function($){
  $(".mf-group-wrapper").sortable({
    handle: '.sortable_mf',
      start: function(){ 
          sort_group_id = $(this).attr('id');
        $.mf_bind('callback_before_sort');
    },
    stop: function(){ 
      id =  jQuery(this).attr("id").split("-")[1];
      sort_group_id = $(this).attr('id');
      fixCounterGroup('#mf_group-'+id);
      $.mf_bind('callback_after_sort');
    }
  });
});