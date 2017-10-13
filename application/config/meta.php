<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 all meta information of pages, like title, meta tag title, meta tag description etc are stored in this file.
 this means each page, will have a unique identification . using which we can get the meta information.
 controller name + function name is used as the identification.
 in some cases, controller name + function name + argument name(s) will be used
 
 The function getMetaData() will display this info on each page.
 getMetaData() is called in the common_hook
*/

$config['meta_data'] = array(
  'home/index' => array(
    'meta_desc'     => '',
    'meta_keywords' => '',
    'page_title'    => ''
  ),
  
);