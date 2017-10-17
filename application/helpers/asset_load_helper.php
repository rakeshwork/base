<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );


/**
 * Function to load css / js files
 *
 * will make all files into one.
 * when in dev mode, will regenerate the files each time, or else will load existing files.
 *
 * @param string $sType (css/js)
 * @param boolean $bSpecifics | use only the specified files?
 * @return string
 * @todo filter the files for newlines and tabs while making them
 */
function load_files_new ($sType, $bLoadOnlySpecificFiles=false, $aSpecificFiles=array(), $bConcatenate=true) {


	$CI = &get_instance();

	// The asset Path. All user specified files are expected to be relative to the asset Path.
	$sAssetPath 		= $CI->config->item ('asset_path');

	// The asset url. used for generating the URLs
	$sAssetURL 			= $CI->config->item ('asset_url');

	// folder in which the concatenated file will reside
	$sParsedFilePath   	= $CI->config->item ($sType . '_parsed_path');

	// URL to the folder in which the concatenated file will reside
	$sParsedFolderUrl   	= $CI->config->item ($sType . '_parsed_url');

	// commonly used files that will be needed in a project anyways.
	$aDeafultFiles = array();

	// user specified files given from controller
	$aControllerSpecificFiles = array();

	// The $aFiles array will hold the final list of URIs / URLs that will be loaded.
	$aFiles = array();

	// to hold the URLs specified in the load_ array
	$aUrls = array();

	// whether we have to regenerate the files or not
	$bRegenerateFrontEndFiles = FALSE;
	if ( isset($CI->mcontents['bRegenerateFrontEndFiles']) && ($CI->mcontents['bRegenerateFrontEndFiles'] === TRUE) ) {
		$bRegenerateFrontEndFiles = TRUE;
	}

	//Use only the specified files
	if( $bLoadOnlySpecificFiles === TRUE && ! empty( $aSpecificFiles ) ) {

		$aFiles = $aSpecificFiles;

	} else {

		//get the default files
		$aDeafultFiles = $CI->config->item('default_' . $sType . '_files');

		// user specified files given from controller
		$aControllerSpecificFiles = $CI->mcontents['load_'.$sType];

		$aFiles = array_merge($aDeafultFiles, $aControllerSpecificFiles);
	}


	/**
	 *
	 * temporary fix. until we eliminate the usage of 'data' keys from the codes.
	 */
	unset($aFiles['data']);


	// check for duplicate entries
	$aFiles = array_unique($aFiles);


	// separate out the URLs
	foreach ($aFiles AS $sString) {

		if( _is_Url($sString) ) {
			$aUrls[] = $sString;
		}
	}


	// Do we want to concatenate the files?
	if( $bConcatenate ) {
		$aFiles = array($sParsedFolderUrl . concatenateFiles($sType, $aFiles, $sParsedFilePath, $bRegenerateFrontEndFiles));
	}

	//for each file, prepend the corresponding URL. Unless if they are already URLs
	foreach ($aFiles AS &$sFile) {

		if( ! _is_Url($sFile) ) {
			$sFile = $sAssetURL . $sFile;
		}
	}

	// Bring back all into a single place
	$aFiles = array_merge($aUrls, $aFiles);

	return getTags($sType, $aFiles, $bRegenerateFrontEndFiles);
}



/**
 * Performs a very basic check to determine whether a given string is a  URL or not
 * @return boolean [description]
 */
function _is_Url($sString) {

	$bIsUrl = false;

	if(strrpos($sString, 'http') === 0) {
		$bIsUrl = true;
	} elseif(strrpos($sString, 'https') === 0) {
		$bIsUrl = true;
	}

	return $bIsUrl;
}



/**
 * Concatenate contents of the given files, place them in the specified
 * folder and return the name of the concatenated file.
 *
 * @param  [type] $sType  [description]
 * @param  [type] $aFiles [description]
 * @return [type]         [description]
 */
function concatenateFiles($sType, $aFiles, $sParsedFilePath, $bRegenerateFrontEndFiles) {


	$CI = &get_instance();

	$sAssetPath 		= $CI->config->item('asset_path');
	$sParsedFilePath   	= $CI->config->item($sType . '_parsed_path'); // path of the destination for concatinated files


	// File exstension
	$sExstension = 'css';
	if($sType == 'js') {
		$sExstension = 'js';
	}

	//make the filename for the combination of these files
	$sParsedFilename = $CI->mcontents['uri_1'] . $CI->mcontents['uri_2'] . md5( str_replace('.','', implode('', $aFiles)) ). '.' . $sExstension;

	$sParsedFileFullPath = $sParsedFilePath . $sParsedFilename;


	if( ! file_exists($sParsedFileFullPath) ) {

		doFileConcatenation($aFiles, $sParsedFileFullPath, $sAssetPath);

	} else {

		if( $bRegenerateFrontEndFiles ) {

			doFileConcatenation($aFiles, $sParsedFileFullPath, $sAssetPath);
		}
	}

	return $sParsedFilename;

}


function doFileConcatenation($aFiles, $sDestinationPath, $sAssetPath) {

	// create the empty file
	@file_put_contents($sDestinationPath,'');


	// append content of each file into the single file | Done this way, so that very large files will not cause memory to run out
	foreach ($aFiles as $file ) {


		$file_to_open = $sAssetPath . $file;
		//p($file_to_open);
		$file_contents = file_get_contents($file_to_open,true);

		@file_put_contents($sDestinationPath,$file_contents,FILE_APPEND);
	}
}


/**
 *
 * used to place CSS/ JS links on HTML Documents
 * @param  [type] $aUrls [description]
 * @return [type]        [description]
 */
function getTags($sType, $aUrls, $bRegenerateFrontEndFiles) {

	$sHtml = '';

	foreach( $aUrls AS $sUrl ) {

		// if the files are regenerated, then add a variying parameter to prevent client side caching.
		if( $bRegenerateFrontEndFiles ) {
			$sUrl .= '?t='.time();
		}

		switch($sType) {

			case 'css':
				$sHtml .= '<link href="' . $sUrl . '" rel="stylesheet" type="text/css" media="all"/>'. "\n";
				break;

			case 'js':
				$sHtml .= '<script type="text/javascript" src="' . $sUrl .'"></script>'. "\n";
				break;
		}
	}

	return $sHtml;
}
