<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI = & get_instance();

/*
|--------------------------------------------------------------------------
| Tiny MCE Settings
|--------------------------------------------------------------------------
|
|
*/


$config['default_wysiwyg_editor'] = 'tinymce4';

$config['tinymce_default_settings'] = array(
	'tinymce_script_url' => $CI->config->config['js_url'].'tinymce/jscripts/tiny_mce/tiny_mce.js',
	'tinymce_theme' => 'basic',
	'tinymce_theme_advanced_resizing' => 'true',
	'tinymce_theme_advanced_resizing_use_cookie' => 'false',
	'tinymce_theme_advanced_statusbar_location' => 'bottom',
	'tinymce_theme_advanced_toolbar_location' => 'top',
	'tinymce_theme_advanced_toolbar_align' => 'left',
);

$config['tinymce_button_collection_1'] = '
		theme_advanced_buttons1 : "bold,italic,underline,bullist,numlistlink,|,link,unlink,|justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,|,outdent,indent,|,image",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
';

