<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Function to create thumbnail image
 *
 * @param string $source_full_path full path of the source file
 * @param array $arr_thumb_image_size thumbnail image dimension
 * @param string $destination
 * @param string $file_name
 * @param string $master_dim
 * @return unknown
 */
function createThumbnails($source_full_path, $destination, $file_name, $arr_thumb_image_size, $master_dim='width', $aConfig=array()){
	
	$img_size	=	getimagesize($source_full_path);
	$img_original_width	=	$img_size[0];
	$img_original_height	=	$img_size[1];
	
	
	$CI 						= &get_instance();
	$config['image_library'] 	= 'gd2';
	$config['source_image'] 	= $source_full_path;
	$config['create_thumb'] 	= TRUE;
	$config['maintain_ratio'] 	= TRUE;
	$config['master_dim'] 		= $master_dim;
	$config['thumb_marker'] 	= '';
	
	$CI->load->library('image_lib');
	
	$aFilenameInfo = explode('.', $file_name);
	$sFileName 		= $aFilenameInfo[0];
	$sFileNameExt 	= $aFilenameInfo[1];
	
	foreach ($arr_thumb_image_size as $key => $thumb_image_size) {
		
		$config['width'] 		= $thumb_image_size['width'];
		$config['height'] 		= $thumb_image_size['height'];
		
		//p($config);
		//exit;
		$config['new_image'] 	= $destination . getThumbFileName($sFileName, $key , $sFileNameExt);

		//p($config);
		
		if($thumb_image_size['width'] >= $img_original_width && $thumb_image_size['height'] > $img_original_height){
			
			// prevents a small image from being made into a big one.
			
			fwrite(fopen($config['new_image'], 'w'), file_get_contents($source_full_path) );
			continue;
		}
		
		/*
		if($key == 'small' || $key == 'tiny'){
			$config['master_dim'] = 'auto';
		}
		*/
		
		$CI->image_lib->initialize($config);

		if(!$CI->image_lib->resize()) {
			
		    $CI->merror['error'] = $CI->image_lib->display_errors();
		    return FALSE;
		}
	}
	return TRUE;
}


function cropImages($sType, $sImageName){
	
	$CI = &get_instance();
	$CI->load->library('image_lib');
	
	foreach(c($sType.'_thumbnail_dimensions') AS $sKey=>$aDimensions){
		
		$img_size	=	getimagesize(c($sType.'_path').DS.$sImageName);
		
		$img_original_width		=	$img_size[0];
		$img_original_height	=	$img_size[1];
		
		$aFilenameInfo = explode('.', $sImageName);
		$sFileName 		= $aFilenameInfo[0];
		$sFileNameExt 	= $aFilenameInfo[1];
		
		$config['new_image'] = $config['source_image'] = c($sType . '_path') . getThumbFileName($sFileName.'_c_', $sKey , $sFileNameExt);
		
		if($img_original_width > $aDimensions['width']){
			$config['x_axis'] = 0;
		}
		if($img_original_height > $aDimensions['height']){
			$config['y_axis'] = 0;
		}
		
		$CI->image_lib->initialize($config);
	
		if ( ! $CI->image_lib->crop())
		{
		    echo $CI->image_lib->display_errors();
		}		
	}
}

/**
 * creating filenames for thumbnail names in a single function, will help up maintain a uniform pattern in the way
 * we name out thumbnails.
 * easy for maintenance or change
 */
function getThumbFileName($sFileName, $key , $sFileNameExt) {
	
	$sFileNameExt = str_replace('.', '', $sFileNameExt);
	return $sFileName. '_' . $key . '.' . $sFileNameExt;
}

/**
 *
 * get an uploaded image depending on the type and size required
 *
 *
 */
function getImage($type, $image_name, $size = 'small', $aConfig	= array(), $bBlank = false){

	if(!$image_name){ return '';}
	
	$CI = &get_instance();

	$aDefaultConfig = c('get_image_default_settings');
	
	$aConfig = array_merge($aDefaultConfig, $aConfig);
	
	//var_dump($aConfig);

	$aConfig['align_dim']			= ('width' == $aConfig['align_dim']) ? 'width':'height';
	$thumb_image_size	= $CI->config->item($type . '_thumbnail_dimensions');
	
	
	//get the PATH and URL for the image
	
	$image_url 			= $CI->config->item($type.'_url');
	$image_path 		= $CI->config->item($type.'_path');
	
	if($size == 'original'){
		
		$image_name		= 	$image_name;
		
	} elseif (array_key_exists($size, $thumb_image_size)){
		
		$aImageParts = explode('.', $image_name);
		$image_name		= 	getThumbFileName($aImageParts[0], $size, $aImageParts[1]);
	}



	//construct the full URL and PATH
	$image_url 	= $image_url.$image_name;
	$image_path = $image_path.$image_name;

//p($image_url);
//p($aConfig);

	if( @$aConfig['only_url'] ) {
		 return $image_url;
	}
	
	$string_attributes	= '';
	
	/*  handled by strict_dimensions option now. This code to be removed later on
	if(0 == $aConfig['container_size']){
		if('width' == $aConfig['align_dim']){
			$aConfig['container_size'] = $thumb_image_size[$size]['width'];
		}else{
			if('original' != $size) {
				if( isset( $thumb_image_size[$size]['height'] ) ){
					$aConfig['container_size'] = $thumb_image_size[$size]['height'];
				}
				
			}
		}
	}
	*/
	
	$style			= '';
	//$aConfig['attributes']['oncontextmenu'] = 'return false';
	
	//p($aConfig['attributes']);
	
	if(file_exists($image_path)){
	
		return getImageTag($image_url, array('attributes' => $aConfig['attributes']));
		
	} else {
	
		if($bBlank) {
			return '';
		} else {
			getImageTag(c('static_image_url') . c('profile_pic_default_pic'), array('attributes' => $aConfig['attributes']));
		}
	}
}

/**
 * Construct an image tag, given the url and attributes
 *
 * @param unknown_type $sUrl
 * @param unknown_type $aAttributes
 * @return unknown
 */
function getImageTag($sUrl, $aSettings=array()) {
	
	$sAttributes = '';
	//p($aSettings);
	$aDefaultSettings = array(
		'strict_dimensions' => false,
		'width' => 0,
		'height' => 0,
	);
	$aSettings = array_merge($aDefaultSettings, $aSettings);
	
	

	if( isset($aSettings['attributes']) && !empty($aSettings['attributes']) ) {
		
		foreach($aSettings['attributes'] AS $sKey=>$sValue){
			$sAttributes .= $sKey . '=\'' . $sValue . '\' ';
		}
	}

	$sReturn = '<img src=\''.$sUrl.'\' '.$sAttributes.'/>';
	
	if( $aSettings['strict_dimensions'] ) {
		$sStyle= 'width:'.$aSettings['width'].'px;' .
				'height:'.$aSettings['height']. 'px;' .
				'display:block;';
		$sReturn = '<div style="'.$sStyle.'">' . $sReturn . '</div>';
	}
	//var_dump($sReturn);
	return $sReturn;
}




/**
 * Function to delete a file
 * @param String $type - image|file
 * @param String $image_name
 * @return Null
 *
 * @todo : MOVE THIS FUNCTION FROM IMAGE HELPER TO A FILE HELPER
 *
 *
 *  DEPRECATED FUNCTION - USE FUNCTION IN CUSTOM FILE HELPER INSTEAD
 */
/*
function deleteFile($type, $sSection, $file_name) {
	
	if( '' != $file_name ) {
		
		$CI 				= &get_instance();
		
		
		$aUploadSettings 	= $CI->config->item ($sSection . '_upload_settings');
		$file_path 			= $aUploadSettings['upload_path'];
		
		$aInfo = explode('.', $file_name);
		
		//delete the original file
		@unlink($file_path.$file_name);
		
		//delete the thumbnails if the file is an image
		if ( $type == 'image' ) {
			
			$thumb_image_size	= $CI->config->item($sSection . '_thumbnail_dimensions');
			if( $thumb_image_size ) {
				
				foreach ( $thumb_image_size as $size => $thumb_image_size ) {
					
					$image = getThumbFileName($aInfo[0], $size , $aInfo[1]);
					@unlink( $file_path.$image );
				}
			}
		}
	}
}
*/


/**
 * save the new profile pic information to the session
 *
 * @param unknown_type $aUploadData
 * @param unknown_type $sUrl
 */
function updateProfilePicInSession($sPath, $sUrl){

	ss('profile_pic_path', $sPath);
	ss('profile_pic_url', $sUrl);
}



/**
 *
 * @param string $sOriginalFile - full path to the original image, whose thumbnails are to be generated
 *
 * here are old sizes is requires, because we cannot "find" the old thumbnails without it. its just a whole lot easier
 * if we know the old filenames
 * 
 */

function regenerateThumbnails ($sOriginalFilePath, $sOriginalFileName, $sThumbnailPath, $aNewSizes, $aOldSizes, $master_dim='width', $aConfig=array() ) {
	
	// get the original file name	
	$sFileName = $sOriginalFileName;
	$aFileNameParts = explode('.', $sFileName);
	
	// Look in the folder for old thumbnails of same name. delete any old thumnails present
	foreach( $aOldSizes AS $sKey => $sValue ) {
		//p( $sThumbnailPath . getThumbFileName($aFileNameParts[0], $sKey, $aFileNameParts[1]) );
		@unlink( $sThumbnailPath . getThumbFileName($aFileNameParts[0], $sKey, $aFileNameParts[1]) );
	}
	
	//p($sOriginalFilePath . $sOriginalFileName);
	//exit;
	// generate new thumnails
	createThumbnails($sOriginalFilePath . $sOriginalFileName, $sThumbnailPath, $sFileName, $aNewSizes, $master_dim, $aConfig=array());
}


/**
 *
 * Thumbnails created using the function getThumbFileName() can be renamed easily using this function
 *
 */
function renameThumbnails($sSourcePath, $sDestinationPath, $sSourceFilename, $sDestinationFilename, $aThumbnailDimensions ) {
	
	$aOldFilenameInfo = explode('.', $sSourceFilename);
	$aNewFilenameInfo = explode('.', $sDestinationFilename);
	
	//p($aThumbnailDimensions);exit;
	foreach ( $aThumbnailDimensions as $size => $thumb_image_size ) {
		
		$sNewFileName = getThumbFileName($aNewFilenameInfo[0], $size , $aNewFilenameInfo[1]);
		$sOldFileName = getThumbFileName($aOldFilenameInfo[0], $size , $aOldFilenameInfo[1]);
        
        //p($sNewFileName);
        //p($sOldFileName);
        
		rename ( $sSourcePath.$sOldFileName , $sDestinationPath.$sNewFileName );
        //exit;
	}
}
