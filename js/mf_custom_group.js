/**
 * JavaScript functions used for admin/mf_custom_group.php
 */
jQuery(document).ready(function($) {
  // suggest a type name if label is changed
  $('#custom_group_label').change(function(){
    if( $('#custom_group_name').val().length == 0 ){
      // only suggest if type is empty
      jQuery('#custom_group_label').stringToSlug({
        space:'_',
        getPut:'#custom_group_name', 
        replace:/\s?\([^\)]*\)/gi
      });
    }
  });
});
