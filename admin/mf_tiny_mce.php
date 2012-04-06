<?php

/**
 * Direct copy from latest Wordpress 3.2 wp-admin/includes/post.php
 * This was deprecated in 3.3, but we need it 
 */

/**
 * Adds the TinyMCE editor used on the Write and Edit screens.
 *
 * @package WordPress
 * @since 2.7.0
 *
 * TinyMCE is loaded separately from other Javascript by using wp-tinymce.php. It outputs concatenated
 * and optionaly pre-compressed version of the core and all default plugins. Additional plugins are loaded
 * directly by TinyMCE using non-blocking method. Custom plugins can be refreshed by adding a query string
 * to the URL when queueing them with the mce_external_plugins filter.
 *
 * @param bool $teeny optional Output a trimmed down version used in Press This.
 * @param mixed $settings optional An array that can add to or overwrite the default TinyMCE settings.
 */
//function wp_tiny_mce( $teeny = false, $settings = false ) {
function mf_tiny_mce( $teeny = false, $settings = false ) {
        global $concatenate_scripts, $compress_scripts, $tinymce_version, $editor_styles;

        if ( ! user_can_richedit() )
                return;

        $baseurl = includes_url('js/tinymce');

        $mce_locale = ( '' == get_locale() ) ? 'en' : strtolower( substr(get_locale(), 0, 2) ); // only ISO 639-1

        /*
        The following filter allows localization scripts to change the languages displayed in the spellchecker's drop-down menu.
        By default it uses Google's spellchecker API, but can be configured to use PSpell/ASpell if installed on the server.
        The + sign marks the default language. More information:
        http://wiki.moxiecode.com/index.php/TinyMCE:Plugins/spellchecker
        */
        $mce_spellchecker_languages = apply_filters('mce_spellchecker_languages', '+English=en,Danish=da,Dutch=nl,Finnish=fi,French=fr,German=de,Italian=it,Polish=pl,Portuguese=pt,Spanish=es,Swedish=sv');

        if ( $teeny ) {
                $plugins = apply_filters( 'teeny_mce_plugins', array('inlinepopups', 'fullscreen', 'wordpress', 'wplink', 'wpdialogs') );
                $ext_plugins = '';
        } else {
                $plugins = array( 'inlinepopups', 'spellchecker', 'tabfocus', 'paste', 'media', 'wordpress', 'wpfullscreen', 'wpeditimage', 'wpgallery', 'wplink', 'wpdialogs' );

                /*
                The following filter takes an associative array of external plugins for TinyMCE in the form 'plugin_name' => 'url'.
                It adds the plugin's name to TinyMCE's plugins init and the call to PluginManager to load the plugin.
                The url should be absolute and should include the js file name to be loaded. Example:
                array( 'myplugin' => 'http://my-site.com/wp-content/plugins/myfolder/mce_plugin.js' )
                If the plugin uses a button, it should be added with one of the "$mce_buttons" filters.
                */
                $mce_external_plugins = apply_filters('mce_external_plugins', array());

                $ext_plugins = '';
                if ( ! empty($mce_external_plugins) ) {

                        /*
                        The following filter loads external language files for TinyMCE plugins.
                        It takes an associative array 'plugin_name' => 'path', where path is the
                        include path to the file. The language file should follow the same format as
                        /tinymce/langs/wp-langs.php and should define a variable $strings that
                        holds all translated strings.
                        When this filter is not used, the function will try to load {mce_locale}.js.
                        If that is not found, en.js will be tried next.
                        */
                        $mce_external_languages = apply_filters('mce_external_languages', array());

                        $loaded_langs = array();
                        $strings = '';

                        if ( ! empty($mce_external_languages) ) {
                                foreach ( $mce_external_languages as $name => $path ) {
                                        if ( @is_file($path) && @is_readable($path) ) {
                                                include_once($path);
                                                $ext_plugins .= $strings . "\n";
                                                $loaded_langs[] = $name;
                                        }
                                }
                        }

                        foreach ( $mce_external_plugins as $name => $url ) {

                                if ( is_ssl() ) $url = str_replace('http://', 'https://', $url);

                                $plugins[] = '-' . $name;

                                $plugurl = dirname($url);
                                $strings = $str1 = $str2 = '';
                                if ( ! in_array($name, $loaded_langs) ) {
                                        $path = str_replace( WP_PLUGIN_URL, '', $plugurl );
                                        $path = WP_PLUGIN_DIR . $path . '/langs/';

                                        if ( function_exists('realpath') )
                                                $path = trailingslashit( realpath($path) );

                                        if ( @is_file($path . $mce_locale . '.js') )
                                                $strings .= @file_get_contents($path . $mce_locale . '.js') . "\n";

                                        if ( @is_file($path . $mce_locale . '_dlg.js') )
                                                $strings .= @file_get_contents($path . $mce_locale . '_dlg.js') . "\n";

                                        if ( 'en' != $mce_locale && empty($strings) ) {
                                                if ( @is_file($path . 'en.js') ) {
                                                        $str1 = @file_get_contents($path . 'en.js');
                                                        $strings .= preg_replace( '/([\'"])en\./', '$1' . $mce_locale . '.', $str1, 1 ) . "\n";
                                                }

                                                if ( @is_file($path . 'en_dlg.js') ) {
                                                        $str2 = @file_get_contents($path . 'en_dlg.js');
                                                        $strings .= preg_replace( '/([\'"])en\./', '$1' . $mce_locale . '.', $str2, 1 ) . "\n";
                                                }
                                        }

                                        if ( ! empty($strings) )
                                                $ext_plugins .= "\n" . $strings . "\n";
                                }

                                $ext_plugins .= 'tinyMCEPreInit.load_ext("' . $plugurl . '", "' . $mce_locale . '");' . "\n";
                                $ext_plugins .= 'tinymce.PluginManager.load("' . $name . '", "' . $url . '");' . "\n";
                        }
                }
        }

        if ( $teeny ) {
                $mce_buttons = apply_filters( 'teeny_mce_buttons', array('bold, italic, underline, blockquote, separator, strikethrough, bullist, numlist,justifyleft, justifycenter, justifyright, undo, redo, link, unlink, fullscreen') );
                $mce_buttons = implode($mce_buttons, ',');
                $mce_buttons_2 = $mce_buttons_3 = $mce_buttons_4 = '';
        } else {
                $mce_buttons = apply_filters('mce_buttons', array('bold', 'italic', 'strikethrough', '|', 'bullist', 'numlist', 'blockquote', '|', 'justifyleft', 'justifycenter', 'justifyright', '|', 'link', 'unlink', 'wp_more', '|', 'spellchecker', 'fullscreen', 'wp_adv' ));
                $mce_buttons = implode($mce_buttons, ',');

                $mce_buttons_2 = array( 'formatselect', 'underline', 'justifyfull', 'forecolor', '|', 'pastetext', 'pasteword', 'removeformat', '|', 'charmap', '|', 'outdent', 'indent', '|', 'undo', 'redo', 'wp_help' );
                $mce_buttons_2 = apply_filters('mce_buttons_2', $mce_buttons_2);
                $mce_buttons_2 = implode($mce_buttons_2, ',');

                $mce_buttons_3 = apply_filters('mce_buttons_3', array());
                $mce_buttons_3 = implode($mce_buttons_3, ',');

                $mce_buttons_4 = apply_filters('mce_buttons_4', array());
                $mce_buttons_4 = implode($mce_buttons_4, ',');
        }
        $no_captions = (bool) apply_filters( 'disable_captions', '' );

        // TinyMCE init settings
        $initArray = array (
                'mode' => 'specific_textareas',
                'editor_selector' => 'theEditor',
                'width' => '100%',
                'theme' => 'advanced',
                'skin' => 'wp_theme',
                'theme_advanced_buttons1' => $mce_buttons,
                'theme_advanced_buttons2' => $mce_buttons_2,
                'theme_advanced_buttons3' => $mce_buttons_3,
                'theme_advanced_buttons4' => $mce_buttons_4,
                'language' => $mce_locale,
                'spellchecker_languages' => $mce_spellchecker_languages,
                'theme_advanced_toolbar_location' => 'top',
                'theme_advanced_toolbar_align' => 'left',
                'theme_advanced_statusbar_location' => 'bottom',
                'theme_advanced_resizing' => true,
                'theme_advanced_resize_horizontal' => false,
                'dialog_type' => 'modal',
                'formats' => "{
                        alignleft : [
                                {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'left'}},
                                {selector : 'img,table', classes : 'alignleft'}
                        ],
                        aligncenter : [
                                {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'center'}},
                                {selector : 'img,table', classes : 'aligncenter'}
                        ],
                        alignright : [
                                {selector : 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li', styles : {textAlign : 'right'}},
                                {selector : 'img,table', classes : 'alignright'}
                        ],
                        strikethrough : {inline : 'del'}
                }",
                'relative_urls' => false,
                'remove_script_host' => false,
                'convert_urls' => false,
                'apply_source_formatting' => false,
                'remove_linebreaks' => true,
                'gecko_spellcheck' => true,
                'keep_styles' => false,
                'entities' => '38,amp,60,lt,62,gt',
                'accessibility_focus' => true,
                'tabfocus_elements' => 'major-publishing-actions',
                'media_strict' => false,
                'paste_remove_styles' => true,
                'paste_remove_spans' => true,
                'paste_strip_class_attributes' => 'all',
                'paste_text_use_dialog' => true,
                'extended_valid_elements' => 'article[*],aside[*],audio[*],canvas[*],command[*],datalist[*],details[*],embed[*],figcaption[*],figure[*],footer[*],header[*],hgroup[*],keygen[*],mark[*],meter[*],nav[*],output[*],progress[*],section[*],source[*],summary,time[*],video[*],wbr',
                'wpeditimage_disable_captions' => $no_captions,
                'wp_fullscreen_content_css' => "$baseurl/plugins/wpfullscreen/css/wp-fullscreen.css",
                'plugins' => implode( ',', $plugins ),
        );

        if ( ! empty( $editor_styles ) && is_array( $editor_styles ) ) {
                $mce_css = array();
                $style_uri = get_stylesheet_directory_uri();
                if ( ! is_child_theme() ) {
                        foreach ( $editor_styles as $file )
                                $mce_css[] = "$style_uri/$file";
                } else {
                        $style_dir    = get_stylesheet_directory();
                        $template_uri = get_template_directory_uri();
                        $template_dir = get_template_directory();
                        foreach ( $editor_styles as $file ) {
                                if ( file_exists( "$template_dir/$file" ) )
                                        $mce_css[] = "$template_uri/$file";
                                if ( file_exists( "$style_dir/$file" ) )
                                        $mce_css[] = "$style_uri/$file";
                        }
                }
                $mce_css = implode( ',', $mce_css );
        } else {
                $mce_css = '';
        }

        $mce_css = trim( apply_filters( 'mce_css', $mce_css ), ' ,' );

        if ( ! empty($mce_css) )
                $initArray['content_css'] = $mce_css;

        if ( is_array($settings) )
                $initArray = array_merge($initArray, $settings);

        // For people who really REALLY know what they're doing with TinyMCE
        // You can modify initArray to add, remove, change elements of the config before tinyMCE.init
        // Setting "valid_elements", "invalid_elements" and "extended_valid_elements" can be done through "tiny_mce_before_init".
        // Best is to use the default cleanup by not specifying valid_elements, as TinyMCE contains full set of XHTML 1.0.
        if ( $teeny ) {
                $initArray = apply_filters('teeny_mce_before_init', $initArray);
        } else {
                $initArray = apply_filters('tiny_mce_before_init', $initArray);
        }

        if ( empty($initArray['theme_advanced_buttons3']) && !empty($initArray['theme_advanced_buttons4']) ) {
                $initArray['theme_advanced_buttons3'] = $initArray['theme_advanced_buttons4'];
                $initArray['theme_advanced_buttons4'] = '';
        }

        if ( ! isset($concatenate_scripts) )
                script_concat_settings();

        $language = $initArray['language'];

        $compressed = $compress_scripts && $concatenate_scripts && isset($_SERVER['HTTP_ACCEPT_ENCODING'])
                && false !== stripos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip');

        /**
         * Deprecated
         *
         * The tiny_mce_version filter is not needed since external plugins are loaded directly by TinyMCE.
         * These plugins can be refreshed by appending query string to the URL passed to mce_external_plugins filter.
         * If the plugin has a popup dialog, a query string can be added to the button action that opens it (in the plugin's code).
         */
        $version = apply_filters('tiny_mce_version', '');
        $version = 'ver=' . $tinymce_version . $version;

        if ( 'en' != $language )
                //include_once(ABSPATH . WPINC . '/js/tinymce/langs/wp-langs.php');
                include_once(MF_PATH . '/admin/mf_tiny_mce_langs.php');

        $mce_options = '';
        foreach ( $initArray as $k => $v ) {
                if ( is_bool($v) ) {
                        $val = $v ? 'true' : 'false';
                        $mce_options .= $k . ':' . $val . ', ';
                        continue;
                } elseif ( !empty($v) && is_string($v) && ( ('{' == $v{0} && '}' == $v{strlen($v) - 1}) || ('[' == $v{0} && ']' == $v{strlen($v) - 1}) || preg_match('/^\(?function ?\(/', $v) ) ) {
                        $mce_options .= $k . ':' . $v . ', ';
                        continue;
                }

                $mce_options .= $k . ':"' . $v . '", ';
        }

        $mce_options = rtrim( trim($mce_options), '\n\r,' );

        do_action('before_wp_tiny_mce', $initArray); ?>

<script type="text/javascript">
/* <![CDATA[ */
tinyMCEPreInit = {
        base : "<?php echo $baseurl; ?>",
        suffix : "",
        query : "<?php echo $version; ?>",
        mceInit : {<?php echo $mce_options; ?>},
        load_ext : function(url,lang){var sl=tinymce.ScriptLoader;sl.markDone(url+'/langs/'+lang+'.js');sl.markDone(url+'/langs/'+lang+'_dlg.js');}
};
/* ]]> */
</script>

<?php
        if ( $compressed )
                echo "<script type='text/javascript' src='$baseurl/wp-tinymce.php?c=1&amp;$version'></script>\n";
        else
                echo "<script type='text/javascript' src='$baseurl/tiny_mce.js?$version'></script>\n";

        if ( 'en' != $language && isset($lang) )
                echo "<script type='text/javascript'>\n$lang\n</script>\n";
        else
                echo "<script type='text/javascript' src='$baseurl/langs/wp-langs-en.js?$version'></script>\n";
?>

<script type="text/javascript">
/* <![CDATA[ */
<?php
        if ( $ext_plugins )
                echo "$ext_plugins\n";

        //if ( ! $compressed ) { // the stock language files also need to be marked loaded when compressed scripts are used
?>
(function(){var t=tinyMCEPreInit,sl=tinymce.ScriptLoader,ln=t.mceInit.language,th=t.mceInit.theme,pl=t.mceInit.plugins;sl.markDone(t.base+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'.js');sl.markDone(t.base+'/themes/'+th+'/langs/'+ln+'_dlg.js');tinymce.each(pl.split(','),function(n){if(n&&n.charAt(0)!='-'){sl.markDone(t.base+'/plugins/'+n+'/langs/'+ln+'.js');sl.markDone(t.base+'/plugins/'+n+'/langs/'+ln+'_dlg.js');}});})();
<?php //} ?>
tinyMCE.init(tinyMCEPreInit.mceInit);
/* ]]> */
</script>
<?php

do_action('after_wp_tiny_mce', $initArray);
}
?>
