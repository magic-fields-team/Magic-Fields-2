<?php
//useful for get quickly the path for  images/javascript files and css files
//return something like: http://wordpress.local/wp-content/plugins/Magic-Fields/
define('MF_BASENAME',plugins_url().'/'.str_replace(basename(__FILE__),"",plugin_basename(__FILE__)));

//return something like: /Users/user/sites/wordpres/wp-content/plugins/Magic-Fields
define("MF_PATH", dirname(__FILE__));

global $supports,$wpdb;

$supports = array(
    'title','editor','author',
    'thumbnail','excerpt','trackbacks',
    'custom_fields','comments','revisions',
    'page_attributes'
);

define('MF_TABLE_POSTTYPES', $wpdb->prefix . 'mf_posttypes');
define('MF_TABLE_CUSTOM_TAXONOMY', $wpdb->prefix . 'mf_custom_taxonomy');
define('MF_TABLE_CUSTOM_FIELDS',$wpdb->prefix . 'mf_custom_fields');
define('MF_TABLE_CUSTOM_GROUPS',$wpdb->prefix . 'mf_custom_groups');

