jQuery(document).ready(function($) {
  mf_suggest_labels = function () {
    label               = $('#posttype-label').val(); 
    label_name          = label;
    label_singular_name = label; //@todo inflection
    label_add_new       = 'Add ' + label;
    label_new_item      = 'Add New ' + label;
    label_edit_item     = 'Edit ' + label;
    label_new_item      = 'New ' + label;
    label_view_item     = 'View '  + label;
    label_search_item   = 'No ' + label + ' found';
    label_found_trash  = 'No ' + label + ' found in Trash';

    $('#posttype-label-name').val(label_name);
    $('#posttype-label-singular-name').val(label_singular_name);
    $('#posttype-label-add-new').val(label_add_new);
    $('#posttype-label-add-new-item').val(label_new_item);
    $('#posttype-label-edit-item').val(label_edit_item);
    $('#posttype-label-new-item').val(label_new_item);
    $('#posttype-label-view-item').val(label_view_item);
    $('#posttype-label-not-found').val(label_search_item);
    $('#posttype-label-not-found-in-trash').val(label_found_trash);
  }

  if( $('#suggest-labels:checked').val() != undefined ) {
      $('#posttype-label').change(mf_suggest_labels);
  }

  $('#suggest-labels').click(function() {
    if($('#suggest-labels:checked').val() != undefined) {
      $('#posttype-label').change(mf_suggest_labels);
    } else {
      $('#posttype-label').unbind('change');
    }
  });
});
