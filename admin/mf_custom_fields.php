<?php 

class mf_custom_fields {

  /**
   * this is the page where is displayed the list of fields of a certain custom field
   * @return none
   */
  function fields_list() {
    global $mf_domain;

    $pt = new mf_posttype();
    $post_type = $pt->get_post_type($_GET['post_type']);

    print '<div class="wrap">';
    print '<h2>'.$post_type['core']['label'].'</h2>';
    print '<h3>'.__( 'Custom Fields', $mf_domain ).'<a href="admin.php?page=mf_dispatcher&mf_section=mf_custom_fields&mf_action=add_field" class="add-new-h2 button">'.__( 'Add new Custom Field', $mf_domain ).'</a></h3>';
    print '</div>';
  }

  /** 
   *  Page for add a new custom field
   */
  function add_field() {
    global $mf_domain;

    $custom_fields = $this->get_custom_fields();
    ?>  
    <div class="wrap">
      <h2>Create Custom Field</h2>
      <div class="alignleft fixed" id="mf_add_custom_field">
        <label><?php _e('Type of Custom Field', $mf_domain);?></label>
        <select>
          <?php foreach($custom_fields as $custom_field):?>
          <option><?php print $custom_field['name']; ?></option>
          <?php endforeach;?>
        </select>
        <p>[textbox]  - label</p>
        <p>[textbox]  - name </p>
        <p>[Checkbox] - Is required</p>
        <p>[checkbox] - Is duplicated</p>
      </div>
      <div class="widefat mf_form_right">
        <p>By default on this box will be displayed a information about custom fields, after the  custom field be selected, this box will be displayed some extra options of the field (if required) or a information about the selected field</p>
      </div>
    </div>
    <?php
  }

  /**
   * Get the list of custom fields
   *
   * @return array
   */
  function get_custom_fields () {
    $path = MF_PATH.'/field_types/*';
    $folders = glob($path,GLOB_ONLYDIR); 
    
    $fields = array();

    foreach($folders as $folder) {
      $name = preg_match('/\/(\w+)$/i',$folder,$name_match);
      $fields[] = array(
        'path'  => $folder,
        'name'  => $name_match[1],
      ); 
    }
    return $fields;
  }
}
