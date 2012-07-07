<?php
//common function for MF

function mf_form_select($data) {
  $id         = $data['id'];
  $label      = $data['label'];
  $name       = $data['name'];
  $options    = $data['options'];
  $value      = $data['value'];
  $add_empty  = $data['add_empty'];
  $description = $data['description'];
  $class       = (isset($data['class']))? sprintf('class="%s"',$data['class']) : '';
  ?>
    <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
      <select name="<?php echo $name; ?>" id="<?php echo $id;?>" <?php echo $class; ?>>
      <?php if($add_empty):?>
        <option value=""></option>
      <?php endif;?>
      <?php if(!empty($options)):?>
        <?php foreach($options as $key => $field_name):
          $selected = (!empty($value) && $value == $key) ? "selected=selected" : "";
        ?>
          <option value="<?php print $key;?>" <?php print $selected; ?>><?php echo $field_name;?></option>
        <?php endforeach;?> 
      <?php endif;?>
    </select>
    <p><?php echo $description; ?></p>
    <?php
  }


//checkbox
function mf_form_checkbox($data){
    $id = $data['id'];
    $label = $data['label'];
    $name = $data['name'];
    $check = ($data['value'])? 'checked="checked"' : '' ;
    $description = htmlentities($data['description']);
    $extra = isset($data['extra'])? sprintf('<p><small>%s/small></p>',$data['extra']) :'';
  ?>
    <label for="<?php echo $id; ?>" ><?php echo $label; ?></label>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>_" type="hidden" value="0">
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="checkbox" value="1" <?php echo $check; ?> >
    <div class="clear"></div>
     <p><?php echo $description; ?></p>
     <?php echo $extra; ?>
    <?php
  }

//textbox
function mf_form_text( $data , $max = NULL ){


    $id = $data['id'];
    $label = $data['label'];
    $name = $data['name'];
    $value = ($data['value'])? sprintf('value="%s"',$data['value']) : '' ;
    if(is_string($data['value'])) $value = sprintf('value="%s"',$data['value']);
    $description = $data['description'];
    $size = ($max)? sprintf('value-size="%s"',$max) : '' ;
    $class = (isset($data['class']))? sprintf('class="%s"',$data['class']) : '';
    $rel = (isset($data['rel'])) ? sprintf('rel="%s"',$data['rel']): '';
    $readonly = (isset($data['readonly']) && $data['readonly']) ? 'readonly="readonly"' : '';
    $is_r = ( isset($data['div_class'] ) && $data['div_class'] == 'form-required'  )? ' <span class="required">*</span>' : '';

    ?>
    <label for="<?php echo $id; ?>"><?php echo $label.$is_r; ?></label>
    <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="text" <?php echo $size; ?> <?php echo $value; ?> <?php echo $class; ?> <?php echo $rel; ?> <?php print $readonly;?> >
    <p><?php echo $description; ?></p>
    <?php
  }

//textarea
function mf_form_textarea( $data ){
    $id = $data['id'];
    $label = $data['label'];
    $name = $data['name'];
    $value = ($data['value'])? $data['value'] : '' ;
    $description = $data['description'];
    $class = (isset($data['class']))? sprintf('class="%s"',$data['class']) : '';
    $rel = (isset($data['rel'])) ? sprintf('rel="%s"',$data['rel']): '';
    ?>
    <label for="<?php echo $id; ?>"><?php echo $label; ?></label>
    <textarea name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="text" <?php echo $class; ?> <?php echo $rel; ?> ><?php echo $value; ?></textarea>
    <p><?php echo $description; ?></p>
    <?php
  }

//hidden
function mf_form_hidden($data){
  $id = $data['id'];
  $name = $data['name'];
  $value = ($data['value'])? sprintf('value = "%s"',$data['value']) : '';
  ?>
  <input name="<?php echo $name; ?>" id="<?php echo $id; ?>" type="hidden" <?php echo $value;?> >
  <?php
}