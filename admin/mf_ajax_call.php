<?php
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

}