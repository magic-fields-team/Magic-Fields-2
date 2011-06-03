jQuery( document ).ready( function( $ ) {
  //Thanks to:  http://devblog.foliotek.com/2009/07/23/make-table-rows-sortable-using-jquery-ui-sortable/
  // Return a helper with preserved width of cells
  var fixHelper = function(e, ui) {
  	ui.children().each(function() {
  		$(this).width($(this).width());
	  });
    return ui;
  };

  $("#mf_sortable tbody").sortable({
  	helper: fixHelper,
    axis: 'y',
    handle: '.mf-order-icon',
    placeholder: 'mf-sortable-placeholder',
    stop: function( event, ui ) {
      $('#mf_order_fields').val($(this).sortable( 'toArray' ));

      //getting the group id
      exp = /group\-([0-9]+)/g;
      group_id = exp.exec($(this).attr('rel'))[1];
      
      save_fields_order(group_id);
    }
  }).disableSelection();


  save_fields_order = function(group_id) {
    var data = {
      order     : $('#mf_order_fields').val(),
      action    : 'mf_call',
      type      : 'mf_sort_field',
      group_id  : group_id
    }

    $.ajax({
      type: 'POST',
      url: ajaxurl,
      data: data,
      success: function (msg) {
        $('#mf-ajax-loading-'+group_id).hide();
        
        if ( msg == "1" ) {
        }else{
          alert('somethings wrong!, try again please');
        }
      },
      beforeSend: function () {
        $('#mf-ajax-loading-'+group_id).show();
      },
      error: function () {
        $('#mf-ajax-loading-'+group_id).hide();
        alert('somethings wrong!, try again please');
      }
    });
  }
});
