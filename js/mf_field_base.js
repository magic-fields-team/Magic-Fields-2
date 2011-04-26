jQuery(document).ready(function($) {

  var tt_template = 
   '<div class="tt"> \
    <div class="tthl"><div class="tthr"><div class="tth"></div></div></div> \
    <div class="ttbl"><div class="ttbr"><div class="ttb"><div class="ttbc">#{content}</div></div></div></div> \
    <div class="ttfl"><div class="ttfr"><div class="ttf"></div></div></div> \
    </div>';

  //tooltip
  $('small.mf_tip').live('mouseenter mouseleave', function(event) {
    if (event.type == 'mouseenter') {
       var ht = $.trim($(this).children('span.mf_helptext').html());
       if (ht && ht != "") {
          var tt = $($.tmpl(tt_template, { content: ht }));
          var tip = $(this).offset();
          var tip_left = tip.left - 13;
          var tip_top = tip.top - 40; 
          $(this).children('span.mf_helptext').after(tt).hide();
          $(this).children('div.tt').offset({ top: tip_top, left: tip_left}).show();
       }
    } else {
      $(this).children('div.tt').remove();
    }
  });

});
