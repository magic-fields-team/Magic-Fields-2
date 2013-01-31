<?php
if( file_exists('../../../../../wp-load.php')){
  require_once('../../../../../wp-load.php');
  $loaded = true;
} elseif( file_exists( dirname(__FILE__).'/../../mf-config.php')){
  include_once(dirname(__FILE__).'/../../mf-config.php');

  include_once('./mf-config.php');
  require_once(MF_WP_LOAD);
  $loaded = true;
}

if($loaded  !== true){
  die('Could not load wp-load.php, edit/add mf-config.php and define MF_WP_LOAD to point to a valid wp-load file');
}

if (!(is_user_logged_in() &&
      (current_user_can('edit_posts') || current_user_can('edit_published_pages'))))
	die(__("Authentication failed!",$mf_domain));


function html2txt($document){ 
	$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
    	           /* '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags  */
        	       '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
            	   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
	); 
 	$text = preg_replace($search, '', $document); 
	return nl2br($text);
}



$data = html2txt($_POST['data']);

if(get_magic_quotes_gpc())
{
	$data = stripslashes($data);
}
?>
<html>
	<body>
		<?php print $data; ?>
	</body>
</html>
