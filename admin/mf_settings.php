<?php

/**
 * Settings of Magic Fields
 */
class mf_settings extends mf_admin {

  function main() {

    if($_POST){
      self::save_settings($_POST);
    }

    global $mf_domain;
    print '<div class="wrap">';

    // the title needs a hat icon
    echo get_screen_icon('magic-fields');

    print '<h2>'.__('Magic Fields Settings', $mf_domain ).'</h2>';
    $options = self::fields_form();
    self::form_options($options);
    print '</div>';

  }

  public function save_settings($data){

    if($data['uninstall_magic_field'] == 'uninstall'){
      mf_install::uninstall();
    }else{
      unset($data['uninstall_magic_field']);

      if($data['mf_settings']['extra']['clear_cache'] == 1){
        mf_install::clear_cache();
      }
      unset($data['mf_settings']['extra']);
    
      self::update($data['mf_settings']['general']);
      wp_redirect('options-general.php?page=mf_settings');
    }
  }

  public function form_options($options){
    global $mf_domain;
    ?>
    <div class="widefat mf_form mf_form_settings">
    <form method="post" action="<?php echo get_option('siteurl'); ?>/wp-admin/options-general.php?page=mf_settings&amp;noheader=true">
       <h3><?php _e('General settings of fields',$mf_domain); ?></h3>
      <?php
      foreach($options['general'] as $option){
        if( $option['type'] == 'text' ){
          mf_form_text($option);
        }elseif( $option['type'] == 'checkbox'){
          mf_form_checkbox($option);
        }
      }
      ?>
    <h3><?php _e('Extra',$mf_domain); ?></h3>
      <?php
      foreach($options['extra'] as $option){
        if( $option['type'] == 'text' ){
          mf_form_text($option);
        }elseif( $option['type'] == 'checkbox'){
          mf_form_checkbox($option);
        }
      }
      ?>
      <h3><?php _e('Uninstall Magic Fields',$mf_domain); ?></h3>
      <input type="text" name="uninstall_magic_field" size="25" /><br />
      <label for="uninstall_magic_field">
        <?php _e('Type <strong>uninstall</strong> into the textbox, click <strong>Update Options</strong>, and all the tables created by this plugin will be deleted', $mf_domain); ?></label>

    <div class="clear"></div>
      <input type="submit" value="Submit" class="button" >
      </form>
      </div>
    <?php
  }

  public function fields_form() {
    global $mf_domain;
  
    $options = get_option(MF_SETTINGS_KEY);
    if( !is_array( $options ) )  {
      $options = unserialize( $options );
    }

    //en extra nunca se va a guardar el valor, solo es para procesar algo en el instante
    $data = array(
      'general' => array(
        'hide_visual_editor'	=> array(
          'id'          =>  'hide_visual_editor',
          'type'        =>  'checkbox',
          'label'       =>  __('Hide Visual Editor (multiline)',$mf_domain),
          'name'        =>  "mf_settings[general][hide_visual_editor]",
          'value'       =>  ( !empty( $options['hide_visual_editor'] ) )? $options['hide_visual_editor'] : 0,
          'description' => __( 'Hide All Visual Editor (multiline)', $mf_domain )
        ),
        'dont_remove_tags'	=> array(
          'id'          =>  'dont_remove_tags',
          'type'        =>  'checkbox',
          'label'       =>  __('Do not remove tags TinyMCE. (multiline)',$mf_domain),
          'name'        =>  "mf_settings[general][dont_remove_tags]",
          'value'       =>  ( !empty( $options['dont_remove_tags'] ) ) ? $options['dont_remove_tags'] : 0,
          'description' => __( 'Stop removing the <p> and <br /> tags when saving and show them in the HTML editor', $mf_domain )
        ),
        'hide_post_panel'  =>  array(
          'id'          =>  'hide_post_panel',
          'type'        =>  'checkbox',
          'label'       =>  __( 'Hide Wordpress Post panel' ),
          'name'        =>  "mf_settings[general][hide_post_panel]",
          'value'       =>  ( !empty( $options['hide_post_panel'] ) ) ? $options['hide_post_panel'] : 0,
          'description' =>  __( 'Hide the post panel', $mf_domain )
        ),
        'hide_page_panel'  =>  array(
          'id'          =>  'hide_page_panel',
          'type'        =>  'checkbox',
          'label'       =>  __( 'Hide Wordpress Page panel' ),
          'name'        =>  "mf_settings[general][hide_page_panel]",
          'value'       =>  ( !empty( $options['hide_page_panel'] ) ) ? $options['hide_page_panel'] : 0,
          'description' =>  __( 'Hide the page panel', $mf_domain )
        )
      ),
      'extra' => array(
        'clear_cache'	=> array(
          'id'          =>  'clear_cache',
          'type'        =>  'checkbox',
          'label'       =>  __('Clear cache',$mf_domain),
          'name'        =>  "mf_settings[extra][clear_cache]",
          'value'       =>  0,
          'description' => __( 'delete all image thumbs', $mf_domain )
        )
      )
    );

    //update values
    $settings = self::get();
    if(is_array($settings)){
      foreach($settings as $k => $v){
        if( isset($data['general'][$k]) ){
          $data['general'][$k]['value'] = $v;
        }
      }
    }

    return $data;
  }

  public function update($options) {
    $options = serialize($options);
    update_option(MF_SETTINGS_KEY, $options);
  }

  function get($key = null) {
    if (get_option(MF_SETTINGS_KEY) == "") return "";
    if (is_array(get_option(MF_SETTINGS_KEY)))
      $options = get_option(MF_SETTINGS_KEY);
    else
      $options = unserialize(get_option(MF_SETTINGS_KEY));
    
    if (!empty($key)){
      if( isset($options[$key]) ) return $options[$key];
      return false;
    }else{
      return $options;
    }
  }

  function set($key, $val) {
    $options = self::get();
    $options[$key] = $val;
    self::update($options);
  }

}
