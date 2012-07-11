jQuery(document).ready(function($) {
	$('#adminmenuback,#adminmenuwrap,#wpadminbar,#footer').css( 'display', 'none' );
	
	 $('#send_set_categories').click(function() {
		
		$('#resp').css('display','none');
		default_cat = '';
		$('.dos').each(function(){ 

				if($(this).is(':checked')){
					if (default_cat.length == 0) {
						default_cat = $(this).val();
					}else{
						default_cat += '|||' +  $(this).val();
					}
				
				}
				
			 });

			name = $('#post_type_name').val();
			jQuery.ajax({
		         url: ajaxurl,
		         type: 'POST',
		         async: true,
		         dataType: 'json',
		         data: "action=mf_call&type=set_default_categories&cats="+default_cat+"&post_type="+name,
		beforeSend: function($co){
			
		},
		        success: function(response){
		           $("#message_mf_error").hide();
		           if(response.success){
		             status = 1;
					$('#resp').css('display','block');
		           }else{
		             
		           }
		         }
		       });
		});
});