<?php
// use wp-load. Normally right here, but if it's not...
if( file_exists('../../../../wp-load.php') ){
  require_once('../../../../wp-load.php');
  $loaded = true;
}elseif( file_exists('./mf-config.php') ){
  include_once('./mf-config.php');
  require_once(MF_WP_LOAD);
  $loaded = true;
}

global $mf_domain;
if( $loaded !== true ){
  die('Could not load wp-load.php, edit/add mf-config.php and define MF_WP_LOAD to point to a valid wp-load file.');
}

if ( !current_user_can('upload_files') ){
  die(__("You do not have sufficient permissions to access this page.",$mf_domain));
}

/**
*  Check the mime type of the file for 
*  avoid upload any dangerous file.
*  
*  @param string $mime is the type of file can be "image","audio" or "file"
*  @param string $file_type  is the mimetype of the field
*/
function valid_mime($mime,$file_type){
  $imagesExts = array(
    'image/gif',
    'image/jpeg',
    'image/pjpeg',
    'image/png',
    'image/x-png'
  );
  $audioExts = array(
    'audio/mpeg',
    'audio/mpg',
    'audio/x-wav',
    'audio/mp3'
  );
						
  if($file_type == "image"){
    if(in_array($mime,$imagesExts)){
      return true;
    }
  }elseif($file_type == "audio"){
    if(in_array($mime,$audioExts)){
      return true;
    }
  }else{
    //TODO: here users should be set what mime types
    //are safety for the "files" type of field
    return true;
  }
  return false;
}

?>
<html>
<head>
<?php
if( isset($_POST['fileframe']) ){ 
  $resp = array('error' => true, 'field_id' => $_POST['input_name'] ,'msg' => __("Upload Unsuccessful",$mf_domain) );
  if (isset($_FILES['file']) && (!empty($_FILES['file']['tmp_name']))){

    if ($_FILES['file']['error'] == UPLOAD_ERR_OK){
      if(valid_mime($_FILES['file']['type'],$_POST['type'])){

        if ( !wp_verify_nonce($_POST['checking'],'nonce_upload_file') ){
          $resp['msg'] = __('Sorry, your nonce did not verify.',$mf_domain);
        }else{
          $special_chars = array(' ','`','"','\'','\\','/'," ","#","$","%","^","&","*","!","~","‘","\"","’","'","=","?","/","[","]","(",")","|","<",">",";","\\",",","+","-");
          $filename = str_replace($special_chars,'',$_FILES['file']['name']);
          $filename = time() . $filename;
          
          @move_uploaded_file( $_FILES['file']['tmp_name'], MF_FILES_DIR . $filename );
          @chmod(MF_FILES_DIR . $filename, 0644);
          $info = pathinfo(MF_FILES_DIR . $filename);
          
          $resp = array(
            'error' => false, 
            'name' => $filename,
            'ext' => $info['extension'],
            'field_id'  => $_POST['input_name'],
            'file_path' => MF_FILES_DIR . $filename,
            'file_url' => MF_FILES_URL . $filename,
            'encode_file_url' => urlencode(MF_FILES_URL . $filename),
            'phpthumb' => PHPTHUMB,
            'msg' => __("Successful upload",$mf_domain)
          );
        }
      }else{
        $resp['msg'] = __("Failed to upload the file!",$mf_domain);
      }
    }elseif( $_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE ){
      $resp['msg'] = __('The uploaded file exceeds the maximum upload limit!',$mf_domain);
    }else{
      $resp['msg'] = __("Upload Unsuccessful",$mf_domain);
    }
  }
?>

  <script type="text/javascript" charset="utf-8">
    var mf_par = window.parent;
    var mf_js = <?php echo json_encode($resp); ?>;
    mf_par.<?php echo $_POST['callback']; ?>(mf_js);
 
    var par = window.parent.document;
    var iframe = par.getElementById('iframe_upload_<?php echo $_POST["input_name"]?>');
    iframe.style.display="";
  </script>
<?php } ?>

<?php
// insert global admin stylesheet
$admin_css = array('global.css', 'wp-admin.css'); // different stylesheets for different WP versions
foreach($admin_css as $c){
  if( file_exists(ABSPATH . '/wp-admin/css/' . $c) ){
    echo '<link rel="stylesheet" href="'. get_bloginfo('wpurl') .'/wp-admin/css/' . $c . '" type="text/css" />';
    break; // insert only one stylesheet
  }
}
?>
<style>
  body { padding: 0px; margin: 0px; vertical-align:top; background: transparent;}

input.mf-file { background: #f8f8f8;}
label.label-file { font-size: 12px; padding-left: 2px; }
</style>

<!--[if IE]>
    <style> body{ background:#F8F8F8;}  </style>
<![endif]-->

<script language="javascript">
function upload(){
  // hide old iframe
  var par = window.parent.document;
  var iframe = par.getElementById('iframe_upload_<?php echo $_GET["input_name"]?>');
  iframe.style.display="none";
  
  par.getElementById("response-<?php echo $_GET['input_name'];?>").style.display = "block";
  par.getElementById("response-<?php echo $_GET['input_name'];?>").innerHTML = "Transferring ";
  setTimeout("transferring(0)",1000);
  // send
  document.iform.submit();
}
function transferring(dots){
	
  newString = "Transferring ";
  for (var x=1; x<=dots; x++) {
    newString = newString + ".";
  } 
  
  var par = window.parent.document;
  // update progress
  if (par.getElementById("response-<?php echo $_GET['input_name'];?>").innerHTML.substring(0,5) != "Trans") return;
  par.getElementById("response-<?php echo $_GET['input_name'];?>").innerHTML = newString;
  if (dots == 4) dots = 0; else dots = dots + 1;
  setTimeout("transferring("+dots+")",1000) ;
	
}
</script>
</head>
<body>
<form name="iform" action="" method="post" enctype="multipart/form-data">
  <label for="file" class="label-file"><?php _e('File', $mf_domain); ?>:</label><br />
  <input id="file" type="file" name="file" onchange="upload()" class="mf-file" />
  <?php wp_nonce_field('nonce_upload_file','checking'); ?> 
  <input type="hidden" name="input_name" value="<?php echo $_GET["input_name"]?>" />
  <input type="hidden" name="callback" value="<?php echo $_GET["callback"]?>" />
  <input type="hidden" name="fileframe" value="true" />
  <input type="hidden" name="type" value="<?php echo $_GET["type"]?>" />
</form>
</body>
</html>
