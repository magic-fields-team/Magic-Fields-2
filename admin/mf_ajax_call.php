<?php
$MFthumb = MF_PATH.'/mf_front_end.php';
require_once($MFthumb);

/* all ajax call fo MF */
class mf_ajax_call{

  public function __construct(){

  }

  public function resolve($data){
    $type = $data['type'];
    if(method_exists($this, $type)){
      $this->$type($data);
    }
    die;
  }

  public function mf_sort_field($data){
    if ( !empty( $data['order'] ) && !empty( $data['group_id'] ) ) {
      $order = $data['order'];
      $order = split(',',$order);
      array_walk( $order, create_function( '&$v,$k', '$v =  str_replace("order_","",$v);' ));
      
      if( $thing =  mf_custom_fields::save_order_field( $data['group_id'], $order ) ) {
        print "1";
        die;
      }
      print "0"; //error!
    }
  }

  public function check_name_post_type($data){
    global $mf_domain;
    
    $type = $data['post_type'];
    $id = $data['post_type_id'];
    $check = mf_posttype::check_post_type($type,$id);
    if($check){
      // exist type(name) in the system
      $resp = array('success' => 0, 'msg' => __('The Type(name) of Post type exist,Please choose a different type(name).',$mf_domain) );
    }else{
      $resp = array('success' => 1);
    }
    echo json_encode($resp);
  }

  public function check_name_custom_group($data){
    global $mf_domain;
    
    $name = $data['group_name'];
    $post_type = $data['post_type'];
    $id = $data['group_id'];
    $resp = array('success' => 1);
    
    $check = mf_custom_group::check_group($name,$post_type,$id);
    if($check){
      $resp = array('success' => 0, 'msg' => __('The name of Group exist in this post type, Please choose a different name.',$mf_domain) );
    }
    
    echo json_encode($resp);
  }

  public function check_name_custom_field($data){
    global $mf_domain;
    
    $name = $data['field_name'];
    $post_type = $data['post_type'];
    $id = $data['field_id'];
    $resp = array('success' => 1);
    
    $check = mf_custom_fields::check_group($name,$post_type,$id);
    if($check){
      $resp = array('success' => 0, 'msg' => __('The name of Field exist in this post type, Please choose a different name.',$mf_domain) );
    }
    echo json_encode($resp);
  }

  public function check_type_custom_taxonomy($data){
    global $mf_domain;
    
    $type = $data['taxonomy_type'];
    $id = $data['taxonomy_id'];
    $check = mf_custom_taxonomy::check_custom_taxonomy($type,$id);
    if($check){
      // exist type(name) in the system
      $resp = array('success' => 0, 'msg' => __('The type(name) of custom taxonomy exist, Please choose a different type (name) .',$mf_domain) );
    }else{
      $resp = array('success' => 1);
    }
    echo json_encode($resp);
  }

  public function group_duplicate($data){
    $group_id = (int)$data['group_id'];
    $group_index = (int)$data['group_index'];
    $mf_post = new mf_post();
    $mf_post->mf_ajax_duplicate_group($group_id,$group_index);
  }

  public function field_duplicate($data){
    $group_id = (int)$data['group_id'];
    $group_index = (int)$data['group_index'];
    $field_id = (int)$data['field_id'];
    $field_index = (int)$data['field_index'];
    $mf_post = new mf_post();
    $mf_post->mf_ajax_duplicate_field($group_id,$group_index,$field_id,$field_index);
  }

  public function change_custom_field($data){
    if( isset($data['field_type']) && ($data['field_type'] != NULL) ){
      $name = sprintf('%s_field',$data['field_type']);
      $mf_field = new $name();
      $mf_field->get_options(array(),$name);
    }
  }

	public function set_default_categories($data){
		global $wpdb;
		
		$post_type_key = sprintf('_cat_%s',$data['post_type']);
		$cats = preg_split('/\|\|\|/', $data['cats']);
		$cats = maybe_serialize($cats);

    $table = $wpdb->postmeta;
		
    $check_parent = $wpdb->prepare(
      "SELECT meta_id FROM $wpdb->postmeta ".
      "  WHERE meta_key='%s'",
      $post_type_key
    );
		$query_parent = $wpdb->query($check_parent);

    if($query_parent){
      $sql = $wpdb->prepare(
        "UPDATE $wpdb->postmeta".
        " SET meta_value = '%s' ".
        " WHERE meta_key = '%s' AND post_id = '0' ",
        $cats,
        $post_type_key
      );

		}else{
      $sql = $wpdb->prepare(
        "INSERT INTO $wpdb->postmeta".
        " (meta_key, meta_value) ".
        " VALUES ('%s', '%s')",
        $post_type_key,
        $cats
      );
		}
		$wpdb->query($sql);
		$resp = array('success' => 1);
		
		//update_post_meta(-2, $post_type, $cats);
		
		echo json_encode($resp);
	}

  public function upload_ajax($data){
    global $mf_domain;
    // pr($data);
    // pr($_FILES);
    // $resp = array('ok' => true,$_FILES,$data);
    // echo json_encode($resp);

    if ( !current_user_can('upload_files') ){
      $resp = array('success' => false, 'msg' => __('You do not have sufficient permissions to upload images.',$mf_domain) );
      echo json_encode($resp);
      die;
    }

    if( isset($_POST['fileName']) ){
      $resp = array('success' => false, 'msg' => __("Upload Unsuccessful",$mf_domain) );
      if (isset($_FILES['file']) && (!empty($_FILES['file']['tmp_name']))){

        if ($_FILES['file']['error'] == UPLOAD_ERR_OK){
          if(mf_ajax_call::valid_mime($_FILES['file']['type'],'image')){

            // if ( !wp_verify_nonce($_POST['checking'],'nonce_upload_file') ){
            //   $resp['msg'] = __('Sorry, your nonce did not verify.',$mf_domain);
            // }else{
              $special_chars = array(' ','`','"','\'','\\','/'," ","#","$","%","^","&","*","!","~","‘","\"","’","'","=","?","/","[","]","(",")","|","<",">",";","\\",",","+","-");
              $filename = str_replace($special_chars,'',$_FILES['file']['name']);
              $filename = time() . $filename;
            
              @move_uploaded_file( $_FILES['file']['tmp_name'], MF_FILES_DIR . $filename );
              @chmod(MF_FILES_DIR . $filename, 0644);
              $info = pathinfo(MF_FILES_DIR . $filename);

              $thumb =  aux_image($filename,"w=150&h=120&zc=1",'image_alt');
            
              $resp = array(
                'success' => true,
                'name' => $filename,
                'ext' => $info['extension'],
                'thumb' => $thumb,
                'file_path' => MF_FILES_DIR . $filename,
                'file_url' => MF_FILES_URL . $filename,
                'encode_file_url' => urlencode(MF_FILES_URL . $filename),
                'msg' => __("Successful upload",$mf_domain)
              );
            // }
          }else{
            $resp['msg'] = __("Failed to upload the file!",$mf_domain);
          }
        }elseif( $_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE ){
          $resp['msg'] = __('The uploaded file exceeds the maximum upload limit!',$mf_domain);
        }else{
          $resp['msg'] = __("Upload Unsuccessful",$mf_domain);
        }
      }
    }
    echo json_encode($resp);
  }

  public function valid_mime($mime,$file_type){
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

  public function get_thumb($data){

    require_once(ABSPATH."/wp-admin/includes/image.php");
    require_once(ABSPATH."/wp-includes/media.php");

    // remove text aditional in attachment
    $image_id = preg_replace('/del_attachment_/','',$data['image_id']);
    $info = wp_get_attachment_image_src($image_id,'original');
    $field_value = $info[0];
    $thumb =  aux_image($field_value,"w=150&h=120&zc=1",'image_media');

    $field_id = preg_replace('/thumb_/','',$data['field_id']);

    if ( is_wp_error($thumb) ){
      $data = array('field_id' => $field_id,"error" => true, "msg" => html_entity_decode($thumb->get_error_message()));
      echo json_encode($data);
      return;
    }

    if( count($info)){
      $image_thumb = PHPTHUMB.'?&w=150&h=120&src='.$info[0];
      $data = array('image' => $image_thumb,'field_id' => $field_id,'image_value' => $image_id,'image_path' => $info[0],'thumb' => $thumb,"error" => false);
      echo json_encode($data);
    }

  }

}