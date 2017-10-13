<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 * Upload a File
 *
 * @param string $sFieldName
 * @param array $aConfig
 * @return array
 */


/**
 * Upload any file
 *
 * will return an empty array on error and populate the common error variable.
 * on success, will return upload details
 *
 * @param string $sFileType | image, video, pdf, file etc
 * 	'file' => performs a simple file upload
 * @param string $sSection | profile_pic, album, resume etc
 * @param string $sFieldName | name of the input field of type "file"
 * @param array $aConfig
 * @return unknown
 */
function uploadFile($sFileType, $sSection, $sFieldName, $aCustomConfig=array(), $sFileName='') {

	$CI = & get_instance();
	$sError = '';


	$aDefaultConfig = array(
		'upload_path'		    => '', // path to which upload should happen
		'allowed_types'		    => '',//ex: png|jpg|gif|jpeg
		'types_description'	    => 'Image Files', //will appear in the drop-down box for "file types" field in the "select files" window
		'file_name'			    => '',
		'overwrite'			    => false,
		'max_size'			    => 4096,
		'max_width'			    => 3000, //in pixels
		'max_height'		    => 2500, //in pixels
		'max_filename'		    => 0,
		'encrypt_name'		    => false,
		'remove_spaces'		    => true,
		'extension'			    => '.jpg',
		'create_thumbnails'     => true,
		'append_real_name'	    => true,
		'random_name_length'    => 4,// length of the random string we are going to give as new filename
		'total_filename_length' => 50,// limit the total length of the file name. see DB restrictions as well
	);




	// get the upload settings for this section
	if( $CI->config->item($sSection . '_upload_settings') ) {
		$aDefaultConfig = array_merge( $aDefaultConfig, $CI->config->item( $sSection . '_upload_settings' ) );

	} else {

		$CI->merror['error'] = 'Invalid Section';
		return array();
	}


	$aConfig = array_merge($aDefaultConfig, $aCustomConfig);

	// p($aConfig);

	$aFileName = explode('.', $_FILES[$sFieldName]['name']);
	$dp = strrpos($_FILES[$sFieldName]['name'], '.');


	$sExtention = @$aConfig['extension'] ? $aConfig['extension'] : substr($_FILES[$sFieldName]['name'], $dp, strlen($_FILES[$sFieldName]['name']));


	$sRealFileName = substr($_FILES[$sFieldName]['name'], 0, $dp);
	$sRealFileName = customizeFileName($sRealFileName);

// p('here 1');
// p($aConfig);

	//get the file name after uploading | doing it here because this involves database access.
	if( ! $aConfig['file_name'] ) {
		$aConfig['file_name'] = getUploadFileName($sSection, $aConfig['upload_path'], $sRealFileName, $sExtention, $aConfig['append_real_name'], $aConfig['random_name_length'], 'numeric', $aConfig['total_filename_length']);
	}

	//( ($aConfig['append_real_name']) ? '_' . $sRealFileName . $sExtention : $sExtention );
// p('here 2');

	$CI->load->library('upload', $aConfig, $aConfig['file_name']);


	if ($CI->$aConfig['file_name']->do_upload($sFieldName)) {


		$aUploadData =  $CI->$aConfig['file_name']->data();

		if($sFileType != 'file'){
			postUploadProcessing($sFileType, $sSection, $aConfig, $aUploadData);
		}

		if(isset($aConfig['keep_original']) && !$aConfig['keep_original']){
			unlink($aUploadData['full_path']);
		}

		return $aUploadData;

	} else {
			$CI->merror['error'] = $CI->$aConfig['file_name']->display_errors();
			return array();
	}

}


/**
 *
 * get a file name for the new file thats being uploaded.
 *
 * will check the corresponding folder(DB) for conflicting file names and create a file name accordingly
 *
 */
function getUploadFileName($sType='', $sPath='', $sRealFileName='', $sExtention, $append_real_name=false, $filename_length=12, $sStringType='alnum', $iTotalFilenameLength=100){

	$sFileName= '';
	$aData = array();
	if($sType){

		switch($sType){

			case 'profile_pic':
				$aData = getColumnData('users', 'profile_image');
				break;
			case 'fruit_image':
				$aData = getColumnData('fruits', 'image');
				break;
		}

		$aData = array_trim($aData);

		$CI = &get_instance();
		$CI->load->helper('string');

		if ( $sPath ) {

			do {

				do {

					$sFileName = random_string($sStringType, $filename_length, $sType);
				} while( in_array($sFileName, $aData) );


                if( $append_real_name ) {

                    $sTemp = $sRealFileName . '-' . $sFileName . $sExtention;


                    // check if the new file name is greater in length than the permissible limit
                    if( strlen( $sTemp ) > $iTotalFilenameLength ) {

                        //this problem will occur only if we are appending the real filename. Over which, we have no control.
                        $iMinLenght = strlen('-' . $sFileName . $sExtention); // "-" is also included, so that we can try to accomodate atleast one character from the real name

                        $iExtraAllowableLegth = abs($iTotalFilenameLength - $iMinLenght);

                        $sRealFileName = substr($sRealFileName, 0 , $iExtraAllowableLegth);

                        $sFileName = $sRealFileName . '-' . $sFileName . $sExtention;
                    }
                } else {
                    $sFileName = $sFileName . $sExtention;
                }


			} while (file_exists($sPath . $sFileName ) );

		} else {

			do {

				$sFileName = random_string('alnum', 12, $sType);
			} while( in_array($sFileName, $aData) );
		}



	}


	return $sFileName;
}


/**
 *
 * Its required that the file have only lower case alphabets.
 * all non alphabets to be replaced with an "-" symbol
 *
 */
function customizeFileName($sString){


	return preg_replace('|[^(a-z0-9\.)]|', '-', strtolower($sString));

}



/**
 *
 * process the file after uploading it
 *
 * @param unknown_type $sFileType
 * @param unknown_type $aUploadData
 * @return unknown
 *
 */
function postUploadProcessing($sFileType, $sSection, $aConfig, $aUploadData) {


	//$aUploadData['is_image'] - is returning false .. needs to be checked why, corrected and included in the following condition
	switch ( $sFileType ) {

		case $sFileType == 'image':

			if($aConfig['create_thumbnails'] === true) {
				//write_log('postUploadProcessing 1');
				if(!createThumbnails($aUploadData['full_path'], $aUploadData['file_path'], $aUploadData['file_name'], c($sSection.'_thumbnail_dimensions'), 'width')) {

					$CI->merror['error'] = 'Some problem in creating thumbnails';
				return FALSE;
				} else {/*all ok*/}
			}
			break;
	}

}


/**
 * Access a file from a server and upload it to our server
 *
 * currently only images are handled
 *
 * used for uploading profile image via url
 *
 */
function urlUpload($sFileType, $sSection, $sUrl, $aConfig = array()) {


	$CI = & get_instance();
	$sError = '';

	// get the upload settings for this section
	if(c($sSection . '_upload_settings')){

		$aConfig = array_merge(c($sSection . '_upload_settings'), $aConfig);
	} else {

		$CI->merror['error'] = 'Invalid Section';
		return array();
	}


	//get the file name to be assigned after uploading
	$sExtention = $aConfig['extension'];
	$aConfig['file_name'] = getUploadFileName($sSection, $aConfig['upload_path'],'', $sExtention) ;


	//determine the upload path
	$sFilePath = @$aConfig['upload_path'] ? $aConfig['upload_path'] : $aConfig['url_upload_path'];
	$sFullPath = $sFilePath . $aConfig['file_name'];


	//access the file from the url and write to our server
	$fp = fopen( $sFullPath, 'w');
	fwrite($fp, file_get_contents($sUrl));
	fclose($fp);


	//validate this image for type etc and delete if not found to be good.
	if($aImageInfo = isValidFile('image', $aConfig['allowed_types'], $sFullPath)){

		$sFullPath = str_replace("\\", "/", realpath($sFullPath)); 	// it will not work with forward slashes
		$sFilePath = str_replace("\\", "/", $sFilePath);			// it will not work with forward slashes

		if($return  = createThumbnails($sFullPath, $sFilePath, $aConfig['file_name'], c($sSection.'_thumbnail_dimensions'), 'width')){

			//   [file_name] => EU5_6Jbjx7kX.jpg
			//    [file_type] => application/octet-stream
			//    [file_path] => E:/www/rentinu/uploads/profile_pic/upload/
			//    [full_path] => E:/www/rentinu/uploads/profile_pic/upload/EU5_6Jbjx7kX.jpg
			//    [raw_name] => EU5_6Jbjx7kX
			//    [orig_name] => EU5_6Jbjx7kX.jpg
			//    [client_name] => me1.jpg
			//    [file_ext] => .jpg
			//    [file_size] => 20.7
			//    [is_image] =>
			//    [image_width] =>
			//    [image_height] =>
			//    [image_type] =>
			//    [image_size_str] =>

			//make details of the uploaded file just like what we get when using codeigniters upload code
			return array(
				'file_name' => $aConfig['file_name'],
				'file_type' => $aImageInfo['image_type'],
				'full_path' => $sFullPath,
				'file_ext' => $sExtention,
			);
		} else {
			//$CI->merror['error'] is already populated inside createThumbnails function
			return array();
		}


	} else {

		unlink($sFullPath);
		$CI->merror['error'] = 'invalid File format';
		return array();
	}

}


/**
 * find out if a file is of valid type and format.
 *
 * used to validate files when they are uploaded by other means than a regular post. in which
 * case we cannot use CI's upload and verification system with ease.
 *
 * will return true or false, and update the common error variable if required
 * only image is handled now
 *
 * @param string $sStype
 * @param string $sFormats
 * @param string $sFullPath
 */
function isValidFile($sType, $sAllowedFormats, $sFullPath){

	$bResult = false;
	if($sType == 'image'){

		if($info = getimagesize($sFullPath)){

			$aAllowedTypes = explode('|', $sAllowedFormats);

			$image_type = image_type_to_mime_type($info[2]);
			$image_type_parts = explode('/', $image_type);


			if(in_array($image_type_parts[1], $aAllowedTypes)){

				//$bResult = true;
				return array('image_type' => $image_type);

			} else {
				$bResult = false;
				$CI->merror['error'] = 'invalid type : '.$image_type;
			}
		} else {
			$bResult = false;
			$CI->merror['error'] = 'Not an image file';
		}
	}
	return $bResult;
}


/**
 *
 * save Images which was uploaded by not finalized by the user
 *
 * @param unknown_type $sType
 * @param unknown_type $iMageName
 * @param unknown_type $iAccountNo
 * @param boolean $bMulti | indicates multiple file upload is allowed in uploadify
 *
 */
function addPendingImage($sType, $sImageName, $iAccountNo, $bMulti=false, $sToken='', $aUploadData=array() ) {

	$CI = &get_instance();

	$CI->db->select('image_name, token');
	$CI->db->where('account_no', $iAccountNo);
	$CI->db->where('type', $sType);



	// Remove any previously uploaded files by this user

	if( $oData = $CI->db->get('pending_images')->row() ) {

		if( $bMulti ) {

			if( $sToken != $oData->token ) {

				// multiple file upload is permitted, and the user is using uploadify to upload yet another picture.
				// problem is that the token doesn match. meaning the user has once again brought up the pop up window for
				// an upload. in this case, check if there are any pending images from the previous upload attempt and
				// delete them

				$CI->db->select('image_name');
				$CI->db->where('account_no', $iAccountNo);
				$CI->db->where('type', $sType);
				foreach($CI->db->get('pending_images')->result() AS $oRow){

					// I think this will cause problems in the case of test case #11
					// great!! ... we do not know what test case $11 is!!
					deletePendingImage($sType, $oRow->image_name, $iAccountNo, true);
				}
			} else {
				// multiple file upload is permitted, and the user
				// is using uploadify to upload yet another picture.
			}

		} else {

			//delete any previously uploaded file by this user.
			deletePendingImage($sType, $oData->image_name, $iAccountNo, true);
		}

	}


	// Now that any previously uploaded files are removed by the above code, lets add this file to the pending images queue

	$CI->db->set('account_no', $iAccountNo);
	$CI->db->set('type', $sType);
	$CI->db->set('image_name', $sImageName);
	$CI->db->set('set_on', time());


	if( $aUploadData ) {
		$CI->db->set( 'serialized_upload_data', serialize( $aUploadData ) );
	}


	if( $bMulti ) {

		// token is set only in case of multiple uploads, in order to identify the group
		// the flash-session-token can be used here
		$CI->db->set('token', $sToken);
	}

	$CI->db->insert('pending_images');
}


/**
 * Delete Images from the pending list
 *
 * @param unknown_type $sType
 * @param unknown_type $iMageName
 * @param unknown_type $iAccountNo
 */
function deletePendingImage($sType, $sImageName='', $iAccountNo, $bDeleteImage=false) {

	$CI = &get_instance();


	//delete from database
	$CI->db->where('account_no', $iAccountNo);
	$CI->db->where('type', $sType);
	if( $sImageName ) {
		$CI->db->where('image_name', $sImageName);
	}
	$aResult = $CI->db->get('pending_images')->result();


	foreach( $aResult AS $oRow ) {

		$CI->db->where('id', $oRow->id);
		$CI->db->delete('pending_images');

		//delete the file
		if($bDeleteImage) {
			$CI->load->helper('image');
			deleteFile('image', $sType, $oRow->image_name);
		}
	}
}



/**
 *
 * This function will add the necessary files that need to be loaded to the queue and any anything else
 * that needs to be done in assisting the file upload
 *
 * this is implemented with uploadify in mind right now. if another mechanism is being used for upload,
 * then it should be easy to implement, because any changes need to be made only to this one function by adding  a switch or ifelse.
 *
 */
function requireUploaderFiles( $sSection, $aUploadSettings=array(), $sUploader='uploadify' ) {


	$CI = &get_instance ();

	$CI->mcontents['aUploadSettings'] = array_merge( $CI->config->item($sSection. '_upload_settings'), $aUploadSettings );


	// load the flash upload helper
	$CI->load->helper('flash');

	// initialize data array.
	if(!isset($CI->mcontents['load_js']['data'])){
		$CI->mcontents['load_js']['data'] = array();
	}

	// get the current user
	$CI->mcontents['iAccountNo'] = isset( $CI->mcontents['iAccountNo'] ) ? $CI->mcontents['iAccountNo'] : $CI->session->userdata('ACCOUNT_NO');

	// session handling while uploading via flash
	// following data will be sent back along with the file. and we will verify the user login at file receiving end
	$CI->mcontents['load_js']['data']['uploadify_session_token'] 	= setFlashSessionToken($CI->mcontents['iAccountNo'], $sSection);
	$CI->mcontents['load_js']['data']['uploadify_user_acc_no'] 		= $CI->mcontents['iAccountNo'];



	switch($sUploader) {


		case "fineuploader":
			break;

		case "uploadify":
		case "":

			$CI->mcontents['load_js'][] 	= 'uploadify/jquery.uploadify.min.js';
			$CI->mcontents['load_js'][] 	= 'uploadify/jquery.uploadify.js';
			$CI->mcontents['load_css'][] 	= 'uploadify/uploadify.css';

			// get configuration settings related to uploadify
			$CI->load->config('uploadify');

			// load default data that is required for uploader in the JS files
			$aUploadifySettings = $CI->config->item('uploadify_default_settings');

			if ( isset( $CI->mcontents['aUploadSettings']['uploadify_settings'] ) ) {

				$aUploadifySettings = array_merge( $aUploadifySettings, $CI->mcontents['aUploadSettings']['uploadify_settings'] );
			}
			$CI->mcontents['load_js']['data'] = array_merge( $aUploadifySettings, $CI->mcontents['load_js']['data']);
			$CI->mcontents['load_js']['data']['uploadify_sFileExt'] = '*.' . implode(';*.', explode('|', $CI->mcontents['aUploadSettings']['allowed_types']));
			break;
	}


	if( isset( $CI->mcontents['aUploadSettings']['multiple_upload'] ) && $CI->mcontents['aUploadSettings']['multiple_upload'] == TRUE) {
		/*

		*/
	}


	//p($CI->mcontents['load_js']['data']['uploadify_session_token']);

	$CI->mcontents['load_js']['regenerate'] = $CI->mcontents['aUploadSettings']['regenerate_js_files'];


}


/**
 *
 * Clear any files that were uploaded but left unused in the pending status.
 *
 * ex: a file was uploaded, but browser was closed/ or page reloaded, power failure etc.
 * no way to track this file, but to have a routine check up.
 *
 * This function is called by the cron task that runs daily.
 *
 */
function clearPendingFiles($sType) {

	$CI = & get_instance ();


		$CI->db->where('set_on < ', (time() - $CI->config->item('sess_expiration')));

		if( $aData = $CI->db->get('pending_images')->result() ) {


			foreach( $aData AS $oItem ) {

				//deleting the records from DB is also handled by deletePendingImage()

				deletePendingImage($sType, $oItem->image_name, $oItem->account_no, true);

			}
		}
}


/**
 *
 * Check if an upload has happened.
 *
 * useful in cases where we are using a function to handle upload
 * from uploadify, and another function to handle the submitted form
 *
 */
function checkUploadifyUploaded($sType, $iAccountNo) {

	$CI = & get_instance ();

	$CI->db->where('type', $sType);
	$CI->db->where('account_no', $iAccountNo);
	$query = $CI->db->get('pending_images');



	if( $query->num_rows() ) {
		return $query->row();
	} else {
		return array();
	}
}


/**
 * Used for a single file
 */
function getUploadData ($sType, $iAccountNo) {

	$CI->db->where('type', $sType);
	$CI->db->where('account_no', $iAccountNo);
	return $CI->db->get('pending_images')->row();
}
