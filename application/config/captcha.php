<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Captcha Settings
|--------------------------------------------------------------------------
|
|
*/
$CI = & get_instance();
$config['captcha_case_sensitive'] 	= FALSE;
$config['captcha_url'] 				= $CI->config->config['base_url'].'captcha/';
$config['captcha_font_name'] 		= 'monofont.ttf';
$config['captcha_font_path'] 		= $CI->config->config['font_path'].$config['captcha_font_name'];
$config['captcha_img_width'] 		= 190;
$config['captcha_img_height'] 		= 50;
$config['captcha_expiration'] 		= 600; // time before the image is deleted from the folder - 10 mins
$config['captcha_word_length'] 		= 5;
$config['captcha_img_class'] 		= 'l';