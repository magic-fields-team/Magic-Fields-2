/*!
  slug plug-in for jQuery, License - MIT, Copyright: 2010 Traversal
*/

/*
  Title: slug plug-in
    
  Description:
    Creates "slug" strings out of another string value, which are safe to 
    use as URL slugs, or variable names etc.

  Author Info:
    Created By - Traversal <http://traversal.com.au>
    Licence - MIT Style <http://en.wikipedia.org/wiki/MIT_License>
  
  Requires: 
    jQuery 1.3 - http://jquery.com

  Companion plug-ins:
    Metadata - http://plugins.jquery.com/project/metadata (optional)

*/

(function($) { 

  var defaults = {
    sep : '-',
    strip: /[!@#$%^\*=\(\)\{\}\|\~`;:"'<>\,\.\/\?]/gi,
    replace: /[\-\_\s\&\+\[\]]/gi,
    noDuplicates: true,
    toCase: 'lower'
  };
  
  var pn = 'slug';

  $[pn] = function() {
    
    var p, o, cmd, val, options = {}, params = {}, a = arguments; 
    val = a[0]; 
    
    
    if (a.length >= 2) { 
      if (typeof(a[1]) == "string") { 
        cmd = a[1]; 
      } else { 
        options = a[1]; 
      } 
    }
    
    if (a.length >= 3) { 
      params = a[2]; 
    } 
    
    p = params;
    o = $.extend(true, {}, defaults, options);
 
    var ret = val;

    // remove unwanted characters
    ret = ret.replace(o.strip, '');
    
    // replace others with the separator
    ret = ret.replace(o.replace, o.sep);

    // remove duplicate separators
    if (o.noDuplicates) {
      ret = ret.replace(new RegExp("[" + o.sep + "]{2,}"), o.sep);
    }
    
    if (o.toCase) {
      if (o.toCase == 'upper') {
        ret = ret.toUpperCase()
      } else {
        ret = ret.toLowerCase()
      }
    }
    
    return ret;

  };
  
  $[pn].defaults = defaults;
  
})(jQuery);