jQuery(document).ready(function($) {
  //Tooltip
 $('.mf_description, .mf_tip').hover(
    function() {
      $('span', this).fadeIn('slow');
    },
    function() {
      $('span',this).fadeOut('slow');
    }
  );
});
