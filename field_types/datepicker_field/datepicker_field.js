jQuery(document).ready(function($){
        jQuery('.datebotton_mf').live('click',function(){
        the_id = jQuery(this).attr('id');
        picker = the_id.replace(/pick_/,'');
        format = jQuery('#format_date_field_'+picker).text();
        format = switch_formats(format);
        picker = 'display_date_field_' + picker;

         jQuery('#'+picker).datepicker({
            showAnim: 'fadeIn',
            changeYear: true,
            dateFormat: format,
            showOn:'focus',
            onClose: function(){
                input = jQuery(this);
                date = input.val();
                //unbind the event
                jQuery(this).datepicker('destroy');
            },
           onSelect: function(){
             raw =  '#' + the_id.replace(/pick_/,'date_field_');
             $(raw).val( $.datepicker.formatDate('@', $(this).datepicker('getDate')) / 1000 );
           }
        }).focus();

        });
        
        //TODAY Botton
	jQuery('.todaybotton_mf').live('click',function($){
	    the_id = jQuery(this).attr('id');
	    picker = the_id.replace(/today_/,'');
	    today = jQuery(this).attr('alt');
            today_raw = jQuery(this).attr('rel') + " 00:00:00";
            today_raw = Date.parse(today_raw)/1000;	    
	    jQuery('#display_date_field_'+picker).val(today);
            jQuery('#date_field_'+picker).val(today_raw);
	});

        //BLANK Botton
	jQuery('.blankBotton_mf').live('click',function(){
	    the_id = jQuery(this).attr('id');
	    picker = the_id.replace(/blank_/,'');	    
            jQuery('#display_date_field_'+picker).val("");
	    jQuery('#date_field_'+picker).val("");
	});
});

//From php date format to jqueyr datepicker format
switch_formats = function(date){

    if(date == "m/d/Y"){
        return "mm/dd/yy";
    }

    if(date == "l, F d, Y"){
        return "DD, MM dd, yy"; 
    }
    
    if(date == "F d, Y"){
        return "MM dd, yy"
    }
    
    if(date == "m/d/y"){
        return "mm/dd/y";
    }
    
    if(date == "Y-d-m"){
        return "yy-dd-mm";
    }
    
    if(date == "Y-m-d"){
        return "yy-mm-dd";
    }
    
    if(date == "d-M-y"){
        return "dd-M-y";
    }
    
    if(date == "m.d.Y"){
        return "mm.dd.yy";
    }
    
    if(date == "m.d.y"){
        return "mm.dd.y";
    }
}