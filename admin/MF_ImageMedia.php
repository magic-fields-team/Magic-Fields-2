<?php
// use wp-load. Normally right here, but if it's not...
if( file_exists('../../../../wp-load.php') )
{
	require_once('../../../../wp-load.php');
	$loaded = true;
} // ...then look over here
elseif( file_exists('./mf-config.php') )
{
	include_once('./mf-config.php');
	require_once(MF_WP_LOAD);
	$loaded = true;
}

if( $loaded !== true ){
	die('Could not load wp-load.php, edit/add mf-config.php and define MF_WP_LOAD to point to a valid wp-load file.');
}

require_once(ABSPATH."/wp-admin/includes/image.php");
require_once(ABSPATH."/wp-includes/media.php");

// remove text aditional in attachment
$image_id = preg_replace('/del_attachment_/','',$_POST['image_id']);
$info = wp_get_attachment_image_src($image_id,'original');

$field_id = preg_replace('/thumb_/','',$_POST['field_id']);

if( count($info)){
  $image_thumb = PHPTHUMB.'?&w=150&h=120&src='.$info[0];
  $data = array('image' => $image_thumb,'field_id' => $field_id,'image_value' => $image_id,'image_path' => $info[0]);
  echo json_encode($data);
}