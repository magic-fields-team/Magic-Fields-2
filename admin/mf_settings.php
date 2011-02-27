<?php

/**
 * Settings of Magic Fields
 */
class mf_settings extends mf_admin {

  function main() {
    global $mf_domain;
    print '<div class="wrap">';
    //@todo: the title needs a hat icon
    print '<h2>'.__('Magic Fields Settings', $mf_domain ).'</h2>';
    print '</div>';
  }
}
