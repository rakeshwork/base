<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
|--------------------------------------------------------------------------
| If the website involves user login, then all user related configuration options should come here. and auto load this file
| its done to logically separate the contents of config files, and to make the main config file smaller
|--------------------------------------------------------------------------
|
|
*/
$CI = & get_instance();


$config['date_picker_user_format'] 	= 'd F Y'; // calendar date format as seen by the user when choosing date



$config['online_status'] = array(
								'offline' => 0,
								'online' => 1
							);

$config['online_via'] = array(
								'system' => 1,
								'facebook' => 2,
								'twitter' => 3,
							);

$config['password_min_length'] = 6;
$config['user_min_age']     = 5;    // years
$config['user_max_age']     = 100;  // years

//CI validation rules for username and password
// set here, because its being used in signup process,
// when creating user by admin, and when setting username/password by a fb/twitter user

$config['username_validation_rules']        = 'trim|required|min_length[3]|alpha_dash';
$config['password_validation_rules']        = 'trim|required|min_length[6]|matches[password_again]';
$config['password_again_validation_rules']  = 'trim|required|min_length[6]';

$config['users_per_page'] = 10;

$config['system_user_account_no'] = 3; 	// When the system code does some action, we need to identify it as such.
										// So we have a user dedicated as system user
/*
|--------------------------------------------------------------------------
| Profile Settings
|--------------------------------------------------------------------------
|
|
*/
$config['birthday_format'] 	= 'F j, Y'; //January 26, 2011 | shown in the profile
$config['profile_pic_upload_type'] = array(
								'facebook' => 1,
								'url' => 2,
								'upload' => 3,
								'none' => 4,
								);
// used with select box
$config['profile_pic_upload_type_text'] = array(
								'facebook' => 'Facebook Profile Picture',
								'url' => 'From a URL',
								'upload' => 'Upload a Picture',
								'none' => 'No Picture'
								);
$config['profile_pic_url'] = $CI->config->config['base_url'].'uploads/profile_pic/'; // has to be removed and use profile_pic_base_url eventually
$config['profile_pic_path'] = $CI->config->config['base_path'].'uploads'.DS.'profile_pic'.DS; // has to be removed and use profile_pic_base_path eventually

$config['profile_pic_default_pic'] 		= 'profile_pic_default';
$config['profile_pic_default_pic_ext'] 	= 'jpg';
$config['profile_pic_default_pic_path'] = $CI->config->config['static_image_path'];
$config['profile_pic_default_pic_url'] 	= $CI->config->config['static_image_url'];

$config['profile_pic_thumbnail_dimensions'] = array (

	'tiny' 		=> array('width' => 50, 'height' => 50),
	'small' 	=> array('width' => 100, 'height' => 100),
	'normal' 	=> array('width' => 200, 'height' => 200),
	'large' 	=> array('width' => 600, 'height' => 800),
);


/*
|--------------------------------------------------------------------------
| Upload Settings
|--------------------------------------------------------------------------
|
*/
$config['profile_pic_upload_settings'] = array(

	'upload_path'		=>$config['profile_pic_path'],
	'allowed_types'		=>'png|jpg|gif|jpeg',
	'types_description'	=>'Image Files', //will appear in the drop-down box for "file types" field in the "select files" window
	'file_name'			=>'',
	'overwrite'			=>false,
	'max_size'			=>3072,
	'max_width'			=>3000, //in pixels
	'max_height'		=>2500, //in pixels
	'max_filename'		=>0,
	'encrypt_name'		=>false,
	'remove_spaces'		=>true,
	'extension'			=>'.jpg',
	'create_thumbnails' => true,
    'append_real_name'	=>true,
    'uploadify_settings'    => array(
                                     'uploadify_uploadLimit' => 1
                                ),
);

/*
|--------------------------------------------------------------------------
| Token Settings
|--------------------------------------------------------------------------
|
|
*/
