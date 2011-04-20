jQuery(document).ready(function($) {
  //Tooltip
 $('.mf_description').hover(
    function() {
      $('span', this).fadeIn('slow');
    },
    function() {
      $('span', this).fadeOut('slow');
    }
  );
});
