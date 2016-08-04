# Magic Fields

* Authors: [Edgar Garcia](http://hunk.com.mx "Hunk")
* Tested up to: Wordpress 4.2.2
* Requires at least: 3.1
* Stable tag: 2.3.3
* Description: Magic Fields 2 is a feature rich Wordpress CMS plugin
* License: GPLv2

## Donations

Please consider a donation, I spend part of my free time programming in Magic Fields. [Help this proyect](https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=edgar%40programador%2ecom&lc=GB&item_name=Donation%20Magic%20Fields&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest)

##Description
Magic Fields 2 is a WordPress CMS plugin which is focused on improving the way how custom fields, post types and custom taxonomies are created in Wordpress.

There are 15 pre-built custom fields in Magic Fields:

* Audio field
* Checkbox field
* Checkbox list field
* Color picker field
* Datepicker field
* Dropdown field
* File field
* Image field
* Image media field
* Markdown editor field
* Multiline editor field
* Radiobutton list field
* Related type field
* Slider field
* Textbox field

**NOTE: Magic Fields 2 IS NOT backward compatible with MF1**

##Installation
Follow these steps to install MF2:

1. Download the plugin into the **/wp-content/plugins/** folder
2. Activate MF2

## Changelog ##


###2.3.3###
* nonce was added in forms, thanks Burak Kelebek for the report
* In import post type change wp_verify_nonce for check_admin_referer
* check_ajax_referer was added in upload image alternative
* Export Post Type now is json file :-)
* JSON_PRETTY_PRINT was added in export post type
* nonce was added all wp ajax calls

###2.3.2.4###
* fix problem with upload file field
* fix problem with get_image and gen_image, sorry

###2.3.2.3###
* Patch for mf_upload.php (sanitize input), thanks @robre

###2.3.2.2###
* better way of handle error in image thumbnails
* fix problem of maxlength in textbox
* fixes for localization

###2.3.2.1###
* Change text domain for localization ( translate.wordpress.org )
* minor fixes in css

###2.3.2###
* fix in get data for image media (now use admin-ajax.php for request) , some server has aditional security for wp-content
* fix warnigs in activate plugin
* remove console.log, sorry

###2.3.1###
* add verification in dispacher, add wpdb->prepare
* add improvements and testing for WP 4.2.2

###2.3###
* Fix #232 mf_register.php Warning on line 40
* Add support for menu-icon for post type (Dashicons)
* Update validate plugin (the previous version caused conflict in parts of WP)
* Fixed for wp_load_image (deprecated funtion) in mf_front-end
* Little fixes in menu post types

###2.2.2.2###
* fix problem with editor, featured image and more
* fix problem in delete item of custom post type

###2.2.2.2###
* fix problem with editor, featured image and more
* fix problem in delete item of custom post type

###2.2.1###
* add static to public functions

###2.2###
* Replace all .live for .on (jquery)
* Fix issue #184 invalid html
* Fix export import error see this page 
* Now in the term field appear also the new terms
* Fix issue #176 Selectable variable name for a better workflow
* Fix issue #165 If we set the post type as not public it disappears and can no longer edit it.
* Fix issue #170 Error is not found posts ($post->ID)
* Preparing for translations
* Update markdownPReview, clean javascript data
* Fix issue #170 JavaScript object 'wp' is not present on a lot of pages
* Fix for 'Uncaught ReferenceError: wp is not defined'
* The text in confirm-dialogs when deleting an image, image-media, file
* Labels 'Add Another' and 'Remove' in duplicatable groups and fields
* Add new wp 3.5 media gallery (thanks alexandergryth)
* Fixed for wp_load_image (deprecated funtion)
* Fix for Notice: Undefined index: post_type
* Fix for Undefined index div_class in checkbox quantity
* Fix max-height in options panel in post type and taxonomies
* Updating ui.datepicker.js to 1.8.24
* Adding backward compatibility the media upload
* Fix problem con new media upload of WP 3.5
* Add index in mf_post_meta for upgardes and fix in name field of index
* Fix in color picker for duplicate action
* Compacting color picker
* Fix issue #137 Quotes is breaking the text on textbox field
* Fix issue #153 Extremely slow queries to wp_X_mf_post_meta
* Fix issue #142 Custom post type image does not display thumbnail image in admin

###2.1###
* Now is possible choice a page template per post (when the "page attribute" is checked in the post type configuration)
* Post type unique, now is possible create post types with only one post, (useful for create static pages)
* adding "set_front" option at the Post type configuration page
* New field term type.
* Issue #158. https://github.com/magic-fields-team/Magic-Fields-2/issues/158
* Issue #139. https://github.com/magic-fields-team/Magic-Fields-2/issues/139
* Update the Datepicker Plugin
* Issue #118 https://github.com/magic-fields-team/Magic-Fields-2/issues/118
* And much more bugfixes


###2.0###
 * First release

##Thanks To:
* Yusuke Kamiyamane  for the [Fugue Icons](http://p.yusukekamiyamane.com/ "Fugue Icons"), the Wand-Hat icon is his work
* http://bassistance.de  for his awesome [Jquery Validate](http://bassistance.de/jquery-plugins/jquery-plugin-validation/ "jQuery Validate")
* John Resig  for his [jQuery metadata Plugin](https://github.com/jquery/jquery-metadata "jQuery metadata")
* [David Valdez](http://gnuget.org "Gnuget") old contributor
