<?php
//funciones de uso general admin o fron-end

/** 
 * aux function 
 **/
if (!function_exists('pr')) {
  function pr($data){
    echo "<pre>";
    print_r($data);
    echo "</pre>";
  }
}

/* return true is MF ins plugin multinetwork */
if( !function_exists('mf_mu') ){
  function mf_mu(){
    if ( function_exists( 'is_multisite' ) )
      return is_multisite();

    return false;
  }
}

if( !function_exists('mf_mu2') ){

  function mf_mu2(){
    global $current_blog;
    if(isset($current_blog)){
      return true;
    }else{
      return false;
    }
  }

}

if ( !defined('DS') ){
  if (strpos(php_uname('s'), 'Win') !== false )
    define('DS', '\\');
  else 
    define('DS', '/');
}

if( !function_exists('mf_mu_alone') ){

  function mf_mu_alone(){
    $current = get_option('active_plugins');
    $plugin = plugin_basename(MF_PATH.'/main.php');

    if( in_array($plugin,$current) ) return true;

    return false;
  }

}