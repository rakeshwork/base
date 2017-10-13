<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Contact us Settings
|--------------------------------------------------------------------------
|
|
*/
$CI = & get_instance();


$config['contact_us_purpose_status'] = array(
                                    1 => 'active',
                                    2 => 'blocked',
                                ); 


$config['contact_us_upload_path'] = $CI->config->config['base_path'] . 'uploads/contact_us/';




/*
|--------------------------------------------------------------------------
| Upload Settings
|--------------------------------------------------------------------------
|
*/
$config['contact_us_upload_settings'] = array(

	'upload_path'		    => $config['contact_us_upload_path'],
    // WHEN UPDATING allowed_types, UPDATE THE _getHighLevelFileType() ALSO . ELSE THERE WILL BE ERRORS
	'allowed_types'		    =>  'png|jpg|jpeg|pdf|xls|ppt|doc|docx|xlsx|word|tar|tgz|zip|wav|rar|mp3|swf|mpeg|mpg|mpe|qt|mov|avi|movie|mp4|flv',
	'types_description'	    => '', //will appear in the drop-down box for "file types" field in the "select files" window
	'file_name'			    => '',
	'overwrite'			    => false,
	'max_size'			    => 20480,
	'max_width'			    => 4000, //in pixels
	'max_height'		    => 4000, //in pixels
	'max_filename'		    => 0,
	'encrypt_name'		    => false,
	'remove_spaces'		    => true,
	'extension'			    => '',
	'create_thumbnails'     => false,
    'append_real_name'	    => true,
    'uploadify_settings'    => array(
                                     'uploadify_uploadLimit' => 1
                                ),
);


