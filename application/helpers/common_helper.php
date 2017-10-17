<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

	/**
	 * to access language variables easily
	 * TO: give an example useage with this function desc
	 */
	function t($line, $aReplaceData = array()) {

		$CI = &get_instance();
		$language =	s('current_language') ? s('current_language') : 'english';

		$sString = ($t = $CI->lang->line($line, $language)) ? $t : $line;

		//check if there is any data to insert into the string
		if($aReplaceData) {

			return vsprintf($sString, $aReplaceData);

		} else {

			return $sString;
		}

	}


	/**
	 * to access config settings easily
	 *
	 */
	function c($setting_name){
		$CI = &get_instance();
		return $CI->config->item($setting_name);
	}


	/**
	 * to access session value easily
	 *
	 */
	function s($session_key){

		$CI = &get_instance();
		return $CI->session->userdata($session_key);
	}


	/**
	 * to set session value easily
	 *
	 */
	function ss($key, $value) {

		$CI = &get_instance();
		return $CI->session->set_userdata($key, $value);
	}


	/**
	 * to get flash data value easily
	 *
	 */
	function f($key) {

		$CI = &get_instance();
		return $CI->session->flashdata($key);
	}


	/**
	 * to unset session value easily
	 *
	 */
	function us($key) {

		$CI = &get_instance();
		return $CI->session->unset_userdata($key);
	}


	/**
	 * to set flash session value easily
	 *
	 */
	function sf($key, $value) {

		$CI = &get_instance();
		return $CI->session->set_flashdata($key, $value);
	}


	/**
	 * Format a line of message as success , failure or as information
	 *
	 * @param unknown_type $sText
	 * @param string $sType | error, success
	 * @param string $sContainerStyle
	 * @param string $sInnerClass
	 * @param string $sInnerStyle
	 * @return string
	 */
	function formatMessage($sText, $sType='error', $sContainerStyle='', $sInnerClass='', $sInnerStyle='') {

		$s = '';
		$sClass = '';

		if(!is_array($sText)){
			$sText = (array)$sText;
		}

		if(!empty($sText)) {

            switch( $sType ) {
                case 'error':
                    $sClass = 'alert alert-danger';
                    //$sClass = 'bg-danger';
                    break;
                case 'success':
                    $sClass = 'alert alert-success';
                    break;
                case 'error':
                    $sClass = 'alert alert-info';
                    break;
            }

			//create the container
			// "error_msg_cnt" - id is used in the following div. it is placed so that
			// the JS errors have got a position to display its errors

			$s .= '<div class = "'.$sClass.'" style="'.$sContainerStyle.'" id="error_msg_cnt" role="alert">
                    <button class="close" data-dismiss="alert" type="button">x</button>';
				// create the inner content
				$s .= combineMessages($sText);
			$s .= '</div>';
		}
		return $s;
	}


	function combineMessages($aMessages,$sInnerClass='', $sInnerStyle=''){

		$s = '';

		if(!is_array($aMessages)){
			$aMessages = (array)$aMessages;
		}

		foreach ($aMessages AS $text){
			$s .= '<div class="'.$sInnerClass.'"  style="'.$sInnerStyle.'">'.$text.'</div>';
		}
		return $s;
	}


	function getTitle($sTitle='') {

		//p($sTitle);exit;
		$CI = & get_instance();
		$sTitle = $sTitle ? $sTitle.' | '.$CI->mcontents['c_website_title'] : $CI->mcontents['c_website_title'];
		return $sTitle;
	}


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
		function load_files ($sType, $bSpecifics=false, $aSpecificFiles=array()) {


			$CI = &get_instance();
			$sFilePath 			= $CI->config->item ($sType . '_path');
			$sAssetPath 		= $CI->config->item ('asset_path');
			$sParsedFilePath   	= $CI->config->item ($sType . '_parsed_path');
			$sReturn = '';


			//get the default files
			$aDeafultFiles = $aFiles =  $CI->config->item('default_' . $sType . '_files');

			// get the common asset files
			$aCommonAssetFiles = $aFiles = $CI->config->item('common_asset_files_' . $sType) ?
																					$CI->config->item('common_asset_files_' . $sType) :
																					array();

			//get the user specific default files
			$aDeafultUserSpecificFiles = $aFiles = $CI->config->item('default_' . $sType . '_files_user');


			// user specified files - Eiter given from controller, or given when function is called directly.
			$aControllerSpecificFiles = array();
			$aControllerSpecificCommonFiles = array();
			$aStrictlySpecificFiles = array(); // given when function is called directly.



			/**
			 *
			 * User specified files Specifics
			 *
			 * If $bSpecifics flas is true, get only those files specified by controller
			 * else, get the other files like the commonly used files etc
			 *
			 */
			if(!$bSpecifics){

				//$aFiles = array_merge($aFiles, $CI->mcontents['load_'.$sType]);
				if($CI->mcontents['load_'.$sType]) {
					$aControllerSpecificFiles = $CI->mcontents['load_'.$sType];
				}

			} else {

				//Use only the specified files
				if(!empty($aSpecificFiles)){
					//$aFiles = array_merge($CI->mcontents['load_'.$sType], $aSpecificFiles);
					$aStrictlySpecificFiles = $aSpecificFiles;



					// There is still usable content inside the load_js / load_css array. child arrays like, "data", "links" etc.
					// We need to get them
					// The following is a temporary fix for the way load_js / load_css files were used.
					// This scenario needs to be addressed more efficiently during the next overhaul

					foreach( $CI->mcontents['load_'.$sType] AS $key => $value) {

						if( ! is_int( $key ) ) { // we are looking for sub arrays here. an integer indicates a js file being specified.
							$aControllerSpecificFiles[$key] = $value;
						}
					}


				} else {
					$aControllerSpecificFiles = $CI->mcontents['load_'.$sType];
				}
			}




			/**
			 *
			 * accomodating the new file structure for css/js files - 22-7-2014 - START
			 *
			 * files have to be specified wrt the asset base path.
			 * instead of changing all the code in the controllers, we use the following code for now.
			 *
			 * common asset files are specified through a separate array
			 *
			 */

			if( $sType == 'js' ) {
				//p($aControllerSpecificFiles);
			}


			//log_message('error', print_r($aControllerSpecificFiles, true));


			$aTempArray = array(
							& $aDeafultFiles,
							& $aDeafultUserSpecificFiles,
							& $aControllerSpecificFiles,
							& $aStrictlySpecificFiles);

	/*
			p('temp array');
			p($aTempArray);
			p('temp array - end');
			p($aCommonAssetFiles);
			p('Asset files');
			p($CI->mcontents['asset_files']);
	*/
			/*
			p('temp array');
			p($aTempArray);
			p('temp array - end');
			p($aCommonAssetFiles);
			*/

//p($aTempArray);

			foreach( $aTempArray AS $k => & $aArray) {

				if( $sType == 'css' ) {

					$current_theme_path_segment = $CI->config->item('current_theme_path_segment');
					foreach( $aArray AS $iKey => & $sFile ) {
						if( is_int($iKey) ) {
							// so that only the paths get affected, and not the many inner arrays of load_css array
							$sFile = $current_theme_path_segment . $sFile;
						}

					}
				}
				if( $sType == 'js' ) {

					if($aArray) {

						foreach( $aArray AS $iKey => & $sFile ) {

							if( is_int($iKey) ) {
								// so that only the paths get affected, and not the many inner arrays of load_css array
								//$sFile = 'js/' . $sFile;
								$sFile = $CI->config->item('js_folder_name') . '/' . $sFile;

							}
							//p($sFile);
						}
					}
				}
			}

			/* accomodating the new file structure for css/js files - 22-7-2014 - END */
	//p($CI->mcontents['asset_files']['js']);
	//p($aTempArray);
	/*
	foreach( $CI->mcontents['asset_files'] AS $k => & $sAssetFile) {
		$sFile = $current_theme_path_segment . $sAssetFile;
	}
	*/


			//p($aCommonAssetFiles);

			/**
			 *
			 * Check if some external links (like those from CDN) were specified to be loaded.
			 * we expect complete links starting with protocol. eg : http:// .....
			 *
			 */
			$aLinks = array();
			$sLinks = '';

			if( isset($aControllerSpecificFiles['links']) ) {

				$aLinks = $aControllerSpecificFiles['links'];
				unset( $aControllerSpecificFiles['links'] );
			}

			foreach( $aLinks AS $sUrl ) {

				if( $sType == 'js' ) {
					$sReturn .= '<script type="text/javascript" src="' . $sUrl . '"></script>'."\n";
				} else {
					$sReturn .= '<link type="text/css" rel="stylesheet" href="' . $sUrl . '"/>';
				}

			}
			//p($aLinks);



			//get any extra data into a separate array
			$aExtraData = array();
			if( isset($aControllerSpecificFiles['data']) ) {

				$aExtraData = $aControllerSpecificFiles['data'];
				unset($aControllerSpecificFiles['data']);
			}

			//log_message('error', print_r($aExtraData, true));

	        // should we regenerate the js file
	        // it is sometimes required to regenerate the JS file every time we
	        // load the page, because we are having dynamic content in the JS files . in such cases, set this
	        // flag to true, inorder to regenerate js/css files on every request
	        $regenerate = false;
	        if( isset( $aControllerSpecificFiles['regenerate'] ) && ( $aControllerSpecificFiles['regenerate'] === true ) ) {

	            $regenerate = true;
	            unset( $aControllerSpecificFiles['regenerate'] );
	        }


	//var_dump($aCommonAssetFiles);exit;
			$aControllerSpecificCommonFiles = isset($CI->mcontents['load_common_' . $sType]) ? $CI->mcontents['load_common_' . $sType] : array();
			$aControllerSpecificCommonFiles_New = isset($CI->mcontents['load_common']) ? $CI->mcontents['load_common'] : array();
	//p($aControllerSpecificCommonFiles);
			$aCommonAssetFiles = array_merge($aCommonAssetFiles, $aControllerSpecificCommonFiles, $aControllerSpecificCommonFiles_New);

			// Note : Only either $aControllerSpecificFiles array , or, $aStrictlySpecificFiles array will have content in it.
			// Hence, we can do the following merge without conflict of interest.
			$aFiles = array_merge(
						$aDeafultFiles,
						$aCommonAssetFiles,
						$aDeafultUserSpecificFiles,
						$aControllerSpecificFiles,
						$aStrictlySpecificFiles
						);



			//filter any repetitions that may have occured
			$aFiles = array_unique(array_trim($aFiles));


			// filter any files that are to be removed. (as specified from the controller)
			if( isset( $CI->mcontents['avoid_'.$sType] ) ){

				$aFiles = array_diff($aFiles, $CI->mcontents['avoid_'.$sType]);
			}

// p($aFiles);

			//make the filename for the combination of these files
			$sParsedFilename = $CI->mcontents['uri_1'] . $CI->mcontents['uri_2'] . md5( str_replace('.','', implode('', $aFiles)) ). '.php';

			$sParsedFileFullPath = $sParsedFilePath . $sParsedFilename;
			$sParsedFileUrl = c($sType . '_parsed_url') . $sParsedFilename;


			//Files are always regenerated in local environment
			if (file_exists( $sParsedFileFullPath ) ){

				if( ( ENVIRONMENT == 'development' ) || ( ENVIRONMENT_ == 'development-rakesh' ) || ( $regenerate == true ) ) {

					@unlink($sParsedFileFullPath);

				} else {

					if($sType == 'js') {

						$sReturn .= '<script type="text/javascript" src="' . $sParsedFileUrl .'"></script>';
						return $sReturn;

					} elseif($sType == 'css') {

						$sReturn .= '<link href="' . $sParsedFileUrl . '" rel="stylesheet" type="text/css" media="all"/>';
						return $sReturn;

					}

				}
			}


	        //if we are regenerating the file, make the URL unique so that browser will not cache the previous version
	        if( $regenerate ) {
	            $sParsedFileUrl .= '?t='.microtime();
	        }

			//load certain variables from config file. | to be used in the files
			$aConfigVariables = c('config_variables_for_'. $sType .'_file');


			//load variables related to css, which are stored in config.
			if($sType == 'css') {

				$CI->load->config('style');
				$aConfigVariables[] = 'css_variables';

			}

			$sVariables='';
			foreach($aConfigVariables AS $sVariableName){

				$sVariableValue = c($sVariableName);

				if($sVariableName == 'base_path'){
					$sVariableValue = addslashes($sVariableValue);
				}

				if(is_array($sVariableValue)) {

					$sVariables .= '$' . $sVariableName.' =	' . defineArrayAsString($sVariableValue) . ";\n";

				} else {

					$sVariables .= '$' . $sVariableName.' =	\'' . $sVariableValue . "';\n";
				}

			}

			//append extra data if any
			if(!empty($aExtraData)){

				//p($aExtraData);
				foreach($aExtraData AS $sKey => $sValue){


					if(is_array($sValue)) {

						$sVariables .= '$' . $sKey.' =	' . defineArrayAsString($sValue) . ";\n";

					} else {

						$sVariables .= '$' . $sKey .' =	\'' . $sValue . "';\n";
					}

				}
			}



			$sFileHeaderPart = '';

			// set the header of the file
			if($sType == 'js'){
				$sFileHeaderPart .= '<?php header("Content-type: text/javascript");';
			} elseif($sType == 'css') {
				$sFileHeaderPart .= '<?php header("Content-type: text/css");';
			}

			// add the config variables
			$sFileHeaderPart .= $sVariables ;
			$sFileHeaderPart .= ' ?>';


			// make the file
			@file_put_contents($sParsedFileFullPath,$sFileHeaderPart,FILE_APPEND);

			$bMinify = false; // MINIFY CODE IS NOT WORKING
			$aSearch = array("\r", "\r\n", "\n", "\t");

// p($sParsedFileFullPath);

			// append content of each file into the single file | Done this way, so that very large files will not cause memory to run out
			foreach ($aFiles as $file ) {

				//$file_to_open = $sFilePath . $file;
				$file_to_open = $sAssetPath . $file;
				// p($file_to_open);
				$file_contents = file_get_contents($file_to_open,true);

				if($bMinify){
					$file_contents .= str_replace($aSearch, '', $file_contents);
				}

				@file_put_contents($sParsedFileFullPath,$file_contents,FILE_APPEND);
			}

			//return the links
			if($sType == 'js') {

				$sReturn .= '<script type="text/javascript" src="' . $sParsedFileUrl .'"></script>';

			} elseif($sType == 'css') {

				$sReturn .= '<link href="' . $sParsedFileUrl . '" rel="stylesheet" type="text/css" media="all"/>';

			}

			return $sReturn;
		}


	function defineArrayAsString($aArray){
		$a = 'array(' ;

		foreach($aArray AS $key => $value){
			if(is_array($value)){

				$a .= defineKeyAsString($key) .' => '. defineArrayAsString($value) .',';

			} else {

				$a .= defineKeyAsString($key) .' => \''. $value .'\',';
			}

		}
		$a .= ')' ;
		return $a;
	}


	function defineKeyAsString($key){

		if(is_numeric($key)){
			return $key;
		} else {
			return '\'' . $key . '\'';
		}
	}


	/**
	 *
	 * Filter the input data for dangerous content
	 *
	 * assuming here that xss cleaning by CI is globally turned on.( global_xss_filtering )
	 *
	 *
	 * @param unknown_type $input_field
	 * @param $bDirectData - data is directly given
	 * @return unknown
	 *
	 */
	function safeText($field_name, $utf8=false, $from='post', $bDirectData=false) {

		$CI = &get_instance();
		$s = '';

			if($bDirectData){
				$s = $field_name;
			} else {
				$s = $CI->input->$from($field_name);
			}


			if( is_array($s) ) {

				foreach($s AS &$item) {

					// htmlentities will cause issue when using malayalam characters
					$item = strip_tags( $item );
				}
			} else {

				//$s = htmlentities ( strip_tags( $s ) );
				// htmlentities will cause issue when using malayalam characters
				$s = strip_tags( $s );
			}


			if($utf8) {

				if( is_array($s) ) {

					foreach($s AS &$item) {
						$item = utf8_encode( $item );
					}

				} else {

					$s = utf8_encode( $s );
				}
			}

		return $s;
	}


	/**
	 *
	 * Make the html input safe for storage into DB
	 *
	 * @param unknown_type $sText
	 * @return unknown
	 *
	 */
	function safeHtml($field_name, $utf8=false, $from='post', $bDirectData=false, $aHtmlPurifierConfig=array()) {

		$CI = &get_instance();

		$s = '';

			if($bDirectData){
				$s = $field_name;
			} else {
				$s = $CI->input->$from($field_name);
			}

			$CI->load->helper('htmlpurifier');

			if( is_array($s) ) {

				foreach($s AS &$item) {

					$item = html_purify($item, false, $aHtmlPurifierConfig);
				}

			} else {

				$s = html_purify($s, false, $aHtmlPurifierConfig);
			}


			if( $utf8 ) {

				if( is_array($s) ) {

					foreach($s AS &$item) {

						$item = utf8_encode($item);
					}

				} else {

					$s = utf8_encode($s);
				}
			}

		return $s;
	}


	/**
	 *
	 * used in drop down lists etc
	 * @param integer $iCount
	 */
	function numbersTill($iFrom=0, $iTo=0){

		$iCount = $iFrom;
		if($iTo){
			$iCount = $iTo;
		}

		$aArray = array();
		if($iCount && ($iFrom < $iTo)){
			for($i=$iFrom;$i<=$iCount;++$i) {
				$aArray[$i] = $i;
			}
		}

		return $aArray;
	}


	/**
	 * replace into a text, some values as specified by the input array
	 *
	 * @param array $aReplace
	 * @param string $sSubject
	 * @return string
	 */
	function replaceInto($aReplace, $sSubject, $bBlankReplace=true) {

	//var_dump($bBlankReplace);

		$values_array		= array ();
		$aMatches            = array();
		preg_match_all("/\{\%([a-z_A-Z0-9]*)\%\}/", $sSubject, $aMatches);
		$variables_array    = $aMatches[1];
		if (count($variables_array) > 0){

                //p($sSubject);
                //p($variables_array);
			foreach ($variables_array as $key){
				$sNoMatchReplace = $bBlankReplace ? '' : '{%'.$key.'%}';
				$values_array[] = @$aReplace[$key] ? $aReplace[$key] : $sNoMatchReplace;
			}
		}

		$arr_variables    = array();
		foreach($variables_array as $variable){
			$arr_variables[] = '/\{\%'.$variable.'\%\}/';
		}

		return preg_replace ($arr_variables, $values_array, $sSubject);
	}


	/**
	 * THIS FUNCTION IS NOT USED ANYMORE.
	 *
	 * BUT KEPT, if the design requires special kind of headers which requires complex formatting.
	 * for which this function was originally made for.
	 *
	 *
	 * */
	function displayHeading($sText='Heading 1', $sType='heading5', $aSettings=array(), $bReturn=false){

		$CI = &get_instance();

		$sType = $sType ? $sType: 'heading1';

		$CI->mcontents['sText'] = $sText;
		$CI->mcontents['aSettings'] = $aSettings;

		$sHeading = $CI->load->view('headings/'.$sType,$CI->mcontents, true);

		if($bReturn){
			return $sHeading;
		} else {
			echo $sHeading;
		}
	}


	/**
	 * Used to change the format of date from one to another
	 *
	 * Assumed the incoming date is in the YYYY-MM-DD format[to be changed to accomodate any date format]
	 */
	function changeDateFormat($sDate, $sNewFormat){

		$sReturn = '';

		if( $sDate ){

			$sReturn = date($sNewFormat, strtotime($sDate));
		}

		return $sReturn;
	}


	/**
	 * get all the values of a particular column in a table
	 *
	 * useful in situations like checking for existing entries in a table - email, username, image names etc
	 *
	 * @param string $sFieldName
	 * @param string $sTableName
	 */
	function getColumnData($sTableName, $sFieldName, $aWhere=array() ) {

		$CI = &get_instance();
		$CI->db->select($sFieldName);
		$CI->db->where($aWhere);
		$sQuery = $CI->db->get($sTableName);

		$aArray=array();
		foreach($aData = $sQuery->result() AS $oData){

			$aArray[] = $oData->$sFieldName;
		}

		return $aArray;
	}



	/**
	 * display the section of login or logout.
	 *
	 * will check for user login status, and display the login or logout buttons respectively
	 */
	function login_logout(){

		//p('LOGOUT 2');exit;
		$CI = &get_instance();

		echo $CI->load->view('login_logout', $CI->mcontents, true);
	}



	/**
	 * Check if the current logged in user is the owner of $sItem
	 *
	 */
	function isOwner($sItem='account', $iItemId) {

		$CI = &get_instance ();
		$bReturn=false;

		if($iCurrUserId = $CI->authentication->is_user_logged_in()){

			if($sItem == 'account'){

				$CI->db->where('account_no', $iItemId);
				$CI->db->where('id', $iCurrUserId);

				if($CI->db->get('users')->row()) {

					$bReturn = true;
				}
			}
		}

		return $bReturn;
	}


	/**
	 *
	 * convert an array of attributes to a string of attributes
	 *
	 * @param unknown_type $aAttributes
	 * @return unknown
	 *
	 */
	function array2Attr($aAttributes, $quotes='single') {

		$s = '';

		if($quotes == 'single'){
			$quotes = "'";
		} else {
			$quotes = '"';
		}

		if( $aAttributes ) {

			foreach( $aAttributes AS $sKey=>$sValue ) {

				$s .= $sKey . '=' . $quotes . $sValue .  $quotes . ' ';
			}
		}
		return $s;
	}



	/**
	 *
	 * Help load tiny mce for the page which is calling this function
	 *
	 */
	function requireTinyMce($aConfig=array()){

		$CI = &get_instance ();

	}


    function requireTextEditor ( $aConfig=array() ) {

        $CI = &get_instance();

        $CI->load->config('wysiwyg');

        $aDefaults = array(
			'profile' => 'content_editor',
                        'editor' => $CI->config->item('default_wysiwyg_editor'),
                        'per_page' => array('generic.js'),
                    );

        $aConfig = array_merge($aDefaults, $aConfig);
        /*
        if( isset( $aConfig['editor'] ) && $aConfig['editor'] ) {
            $sEditor = $aConfig['editor'];
        }
        */

        switch( $aConfig['editor'] ) {

            case 'tinymce4' :

                $CI->load->config('wysiwyg');

				//cdn link for tinymce 4
                //$CI->mcontents['load_js']['links'][] = 'http://tinymce.cachefly.net/4.1/tinymce.min.js';
                $CI->mcontents['load_js']['links'][] = '//cdn.tinymce.com/4/tinymce.min.js';


                $aConfig = array_merge($CI->config->item('tinymce_default_settings'), $aConfig);
                foreach($aConfig AS $key=>$value){
                    $CI->mcontents['load_js']['data'][$key] = $value;
                }
                $CI->mcontents['load_js']['data']['tinymce_button_collection_1'] = $CI->config->item('tinymce_button_collection_1');
                /*
                $CI->mcontents['load_js']['data']['css_url'] = $CI->config->item('css_url');
                */


				switch( $aConfig['profile'] ) {

					case 'minimal':
						$aConfig['per_page'] = array('minimalistic.js');
						break;

					case 'content_editor':
						$aConfig['per_page'] = array('content_editor.js');
						break;
				}


				// if we are using a file manager plugin
				if ( isset( $aConfig['file_manager'] ) && $aConfig['file_manager'] ) {

					$CI->mcontents['load_js'][] = 'roxy_file_manager/functions.js';

				}


                break;
        }

        //include the page specific JS file
        foreach( $aConfig['per_page'] AS $sPerPageJsFile ) {
            $CI->mcontents['load_js'][] = 'text-editor-perpage/' . $aConfig['editor'] . '/' . $sPerPageJsFile;
        }
        //p($aConfig);
        //p($CI->mcontents['load_js']);exit;

    }



	/**
	 *
	 * Load the files required for pop up/ lighbox to work
	 *
	 */
	function requirePopup( $sPopUp = 'fancybox', $aConfig=array() ) {

		$CI = &get_instance ();

        $aDefault = array(
                    'buttons' => false,
                );
        $aConfig = array_merge($aDefault, $aConfig);

        switch( $sPopUp ) {

            case 'fancybox2' :
                $CI->mcontents['load_js'][]     = 'fancybox2/jquery.fancybox.pack.js';
                $CI->mcontents['load_js'][]     = 'fancybox2/fancybox_common.js';
                $CI->mcontents['load_css'][]    = 'fancybox2/jquery.fancybox.css';
                $CI->mcontents['load_css']['data']['fancybox_image_url'] = c('css_url').'fancybox2/images/';

                if( $aConfig['buttons'] ) {
                    $CI->mcontents['load_js'][]     = 'fancybox2/helpers/jquery.fancybox-buttons.js';
                    $CI->mcontents['load_css'][]    = 'fancybox2/helpers/jquery.fancybox-buttons.css';
                }
                break;
        }

	}

	/**
	 *
	 * Load the files required for Datatable to work
	 *
	 */
	function requireDataTable_new() {

		$CI = & get_instance();

		/*
		$CI->mcontents['load_js'][] = 'datatable/jquery.dataTables.js';
		$CI->mcontents['load_js'][] = 'datatable/common_customizations_datatable.js';
		$CI->mcontents['load_css'][] = 'datatables/demo_table.css';
		$CI->mcontents['load_css'][] = 'tablesorter/tablesorter.css';
		*/
		$CI->mcontents['load_js']['links'][] = 'http://cdn.datatables.net/1.10.2/js/jquery.dataTables.min.js';
		$CI->mcontents['load_js']['links'][] = 'http://cdn.datatables.net/plug-ins/a5734b29083/integration/bootstrap/3/dataTables.bootstrap.js';
		$CI->mcontents['load_css'][] = 'datatables/demo_table.css';
		$CI->mcontents['load_css'][] = 'tablesorter/tablesorter.css';
	}


	/**
	 * map the sizes of ours with the sizes of 3rd parties
	 */
	function mapImageSizes($sFor, $sRequiredSize) {


		if($sFor == 'facebook'){
			//facebook image sizes = small, normal, large, square
			$aSizeMap = array(
			'tiny' => 'small',
			'small' => 'small',
			'normal' => 'normal',
			'large' => 'large',
			);
			return $aSizeMap[$sRequiredSize];
		}

	}


	/**
	 * error		any error message, or status etc
	 * success		any success message or status etc
	 * page			any data
	 * error_type	type of error we are returning
	 * data			any extra data we need to send to the browser
	 *
	 **/
	function initializeJsonArray(){
		$CI = &get_instance();
		$CI->aJsonOutput = array('output' => array('error'=>'', 'success'=>'', 'page'=>'', 'error_type' => '', 'data' => ''));
	}


	function outputJson(){

		$CI = &get_instance();

		$CI->aJsonOutput['output'] = json_encode($CI->aJsonOutput['output']);

		//write_log($CI->aJsonOutput['output']);
		$CI->output->set_header('Content-type: application/json');
        //header('Content-type: application/json');
		$CI->load->view('output', $CI->aJsonOutput);
	}


	/**
	 *
	 * given an array, this function will output the content in JSON format
	 */
	function outputGenericJson($aData=array(), $sContentType='') {

		$CI = &get_instance();

		//set header appropriately
		switch($sContentType) {

			case "text-plain":
				$CI->output->set_header('Content-type: text/plain');
				break;

			case "":
				$CI->output->set_header('Content-type: application/json');
				break;

		}

        $aOutput['output'] = json_encode($aData, JSON_FORCE_OBJECT);
		$CI->load->view('output', $aOutput);
	}


	function preLoadImages(){

		$CI = &get_instance();
		$aPreloadImages = array();
		if( isset($CI->mcontents['preload_images']) && !empty($CI->mcontents['preload_images'])){
			$aPreloadImages = array_merge(c('preload_images'), $CI->mcontents['preload_images']);
		} else {
			$aPreloadImages = c('preload_images');
		}

		//filter any repetitions
		$aPreloadImages = array_unique(array_trim( $aPreloadImages ));

		$sStaticImageUrl = c('static_image_url');
		$sUrl = '';
		$s = '<div style="display:none;">';

		foreach($aPreloadImages AS $sImage){

			if ( strpos($sImage, 'http') === 0 ) {
				$sUrl = $sImage;
			} else {
				$sUrl = $sStaticImageUrl . $sImage;
			}
			$s .= '<img src="'. $sUrl .'"/>';
		}
		$s .= '</div>';

		echo $s;
	}


	/**
	 *
	 * Currently used in the common hook to load meta information of a page automatically.
	 *
	 */
	function getMetaData($sPage=''){

	  $CI = &get_instance();

	  //see if we need to use 2 URI segments, or 3, or maybe more, to uniquely identify the page.
	  $sPage = $sPage ? $sPage : $CI->uri->rsegment(1) . '/' . $CI->uri->rsegment(2);
	  if($sPage == 'page/view'){
		$sPage .= '/' . $CI->uri->rsegment(3);
	  }

	  $CI->load->config('meta');
	  $aMetaData = c('meta_data');

	  $CI->mcontents['meta_description'] = $CI->mcontents['meta_keywords'] = $CI->mcontents['page_title'] = '';

	  if( isset( $aMetaData[$sPage] ) ) {

		$CI->mcontents['meta_description'] 	= $aMetaData[$sPage]['meta_desc'];
		$CI->mcontents['meta_keywords'] 	= $aMetaData[$sPage]['meta_keywords'];
		$CI->mcontents['page_title'] 		= $aMetaData[$sPage]['page_title'];

	  }
	}


	/**
	 * used to set the uri, to which a user should redirect to after login.
	 * Made into a function, because
	 * current URI is always taken as the default redirect URI, and we needed to check if it was the login URI.
	 * if its the login URI itself, then it will result in infinite loop i guess?
	 *
	 * login uri MAY change for a project . In that case, we need to change the code in only one place.
	 */
	function setPostLoginRedirect($sUriString=''){
		$CI = &get_instance();

		$uri_string = $sUriString ? $sUriString : $CI->uri->uri_string();

		if($uri_string != 'user/login'){
			ss('post_login_redirect', $uri_string);
		}
	}


	/**
	 * get a number that is attached to the end of a string,
	 * usually separated from the string by a hyphen character.
	 *
	 * used in extracting id from seo names
	 */
	function getStringAppendedNum($sArticleName) {

		/*
		p($sArticleName);
		p(( strrpos($sArticleName, '-', -1) + 2) );
		p( strlen($sArticleName) );
		p( (int) substr($sArticleName,
							( strrpos($sArticleName, '-', -1) + 1),
							strlen($sArticleName)
							));
		exit;
		*/

		return (int) substr($sArticleName,
							( strrpos($sArticleName, '-', -1) + 1),
							strlen($sArticleName)
							);
	}


	/**
	 *
	 * if we want some content to be present in the page, which is hidden, use this function to
	 * specify it in the controller.
	 *
	 */
	function requireGenericContactForm( $iAccountNo, $sName ) {

		$CI = & get_instance();
		$CI->mcontents['sGenericContactForm'] = '';
		$CI->mcontents['sHiddenStuff'] = '';


        //we need a captcha for this form
        $CI->load->helper('captcha');
        $CI->mcontents['aCaptcha'] 	= getCaptcha(array('captcha_container_id' => 'captcha_container_test'));
        $CI->mcontents['load_js'][] = 'jquery/jquery.blockui.js';
        $CI->mcontents['load_js'][] = 'fancybox2/per_page/generic_contact_me.js';

		// we need front end validation for this page.
		requireFrontEndValidation();
        $CI->mcontents['load_js'][] 	= 'validation/generic_contact_me.js';

        $CI->mcontents['iContactPersonAccNo'] 	= $iAccountNo;
        $CI->mcontents['sContactPersonName'] 	= $sName;

        $CI->mcontents['sGenericContactForm'] = $CI->load->view('write_to_me_form', $CI->mcontents, true);

	}





		function one_to_many_mapping($sAction='', $sScenario='', $aData = array()) {

				$CI = & get_instance();

		$CI->load->config('relation_config');

				$aDefaultData = array(
								'one'   => 0,
								'many'  => array(),
								'extra_field_value_pairs'  => array()
						);

				$aData = array_merge($aDefaultData, $aData);

		//p($aData);

				if( $aData['one']  && $aData['many'] && $sAction && $sScenario) {

						$aScenarios = $CI->config->item('one_to_many_scenarios');

			//p($aScenarios);

						$sOne_FieldName     = $aScenarios[$sScenario]['one']['field_name'];
						$sMany_FieldName    = $aScenarios[$sScenario]['many']['field_name'];
						$sMappingTableName  = $aScenarios[$sScenario]['mapping_table_name'];

			foreach( $aData['many'] AS $iMany ) {

				switch($sAction) {

					case 'create' :
						$CI->db->set($sOne_FieldName, $aData['one']);
						$CI->db->set($sMany_FieldName, $iMany);

						foreach( $aData['extra_field_value_pairs'] AS $sKey => $sValue) {
							$CI->db->set($sKey, $sValue);
						}

						$CI->db->insert( $sMappingTableName );
						break;

					case 'delete' :
						$CI->db->where($sOne_FieldName, $aData['one']);
						$CI->db->where($sMany_FieldName, $iMany);
						foreach( $aData['extra_field_value_pairs'] AS $sKey => $sValue) {
							$CI->db->where($sKey, $sValue);
						}
						$CI->db->delete( $sMappingTableName );
						break;
				}
			}
				}

		}





	/**
	 *
	 * When we need to update a one to many mapping, this function is used.
	 */
	function updateOnetoManyMapping($iEntityUid_One, $sSecnario,  $aInputData, $aExistingData, $aExtra_FieldValuePairs = array()) {

		$aDeletedData 	= array_diff($aExistingData, $aInputData);
		$aNewData 		= array_diff($aInputData, $aExistingData);

		$aData = array(
					'one'   => $iEntityUid_One,
					'many'  => $aNewData,
					'extra_field_value_pairs'  => $aExtra_FieldValuePairs
					);
		one_to_many_mapping('create', $sSecnario, $aData);

		$aData['many'] = $aDeletedData;
		one_to_many_mapping('delete', $sSecnario, $aData);

	}


	/**
	 *
	 * convert a json encoded array in javascript to a php array
	 *
	 */
	function array_json_to_php( $sString ) {

		$sString = str_replace( array('[',']', '&quot;', '"'), array(), $sString );
        return explode(',', $sString);
	}



	/**
	 *
     * Force refresh of a cache
     * This is done by deleting the cache file, which will force CI to remake the cache file
     *
     * used in the case we are updating contents of a cached page.
     *
     * The code is taken from the core output class of CI
     *
     */
    function refresh_cache( $sUri ) {

        $CI = & get_instance();

		$path = $CI->config->item('cache_path');

		$cache_path = ($path == '') ? APPPATH.'cache/' : $path;

        $cache_expiration = c('cache_expiration');

		$sUrl =	$CI->config->item('base_url'). $sUri;

		$cache_path .= md5($sUrl);

        unlink($cache_path);
    }






	/**
	 *
	 *
	 * Check if a particular user has access to a certain section.
	 *
	 * access is checked against user's "type"
	 *
	 * to check user access by users role, use hasAccess_byRole() instead
	 */
	function hasAccess( $aAllowedUserTypes, $sRedirectTo = '' ) {

		$CI = & get_instance();


		$iUserType = $CI->session->userdata('USER_TYPE');

		$sRedirectTo = $sRedirectTo ? $sRedirectTo : c('login_uri_segment');

		//check if the user is logged in
		if( ! $CI->session->userdata('USER_TYPE') ) {

			//user is not logged in

			//set the url to go, after the user logs in
			setPostLoginRedirect();

			//go to login page
			redirect( $CI->config->item('login_uri_segment') );

		}

		//the admin user is always allowed without further checking
		if( $CI->session->userdata('USER_TYPE') == $CI->mcontents['aUserTypesFlipped']['admin'] ) {

			return true;

		} elseif( $aAllowedUserTypes ) {


			$aValidUserTypes = array();
			foreach( $CI->mcontents['aUserTypesFlipped'] AS $sKey => $iValue ) {

				if( in_array($sKey, $aAllowedUserTypes) ) {
					$aValidUserTypes[] = $iValue;
				}
			}

			if ( in_array($iUserType, $aValidUserTypes ) ) {

				return true;

			} else {

				if( $sRedirectTo ) {

					redirect($sRedirectTo);

				} else {

					return false;
				}
			}

		} else {

			// since control will reach here only if a user is logged in,
			// and no user type is specified ( $aAllowedUserTypes is empty ),
			// Hence this function will only check if a user is logged in.
			return true;

		}
	}


	/**
	 *
	 *
	 * Check if a particular user has access to a certain section.
	 *
	 * access is checked against user's "role" in the user_roles table
	 *
	 */
	function hasAccess_byRole( $aAccessAllowedRoles, $sRedirectTo = '' ) {

		$CI = & get_instance();
		//$aUserTypes = $CI->config->item('user_types');

		//$iUserType = $CI->session->userdata('USER_TYPE');
		$aUserCurrentRoles = $CI->session->userdata('USER_ROLES');



		$sRedirectTo = $sRedirectTo ? $sRedirectTo : c('login_uri_segment');

		//check if the user is logged in
		if( ! $CI->session->userdata('USER_TYPE') ) {

			//user is not logged in

			//set the url to go, after the user logs in
			setPostLoginRedirect();

			//go to login page
			redirect( $CI->config->item('login_uri_segment') );

		}

		//the admin user is always allowed without further checking
		if( $CI->session->userdata('USER_TYPE') == $aUserTypes['admin'] ) {

			return true;

		} elseif( $aAccessAllowedRoles ) {

			$aAccessAllowedRoles_Ids = array();
			$aAllRoles = $CI->mcontents['aAllRoles'];

			foreach($aAccessAllowedRoles AS $sRole) {
				$aAccessAllowedRoles_Ids[] = $aAllRoles[$sRole]['id'];
			}

			if ( array_intersect( $aAccessAllowedRoles_Ids, $aUserCurrentRoles ) ) {

				return true;

			} else {

				if( $sRedirectTo ) {

					redirect($sRedirectTo);

				} else {

					return false;
				}
			}

		} else {

			// since control will reach here only if a user is logged in,
			// and no user type is specified ( $aAllowedUserTypes is empty ),
			// Hence this function will only check if a user is logged in.
			return true;

		}
	}



	/**
	 * when we need validation in a page, this fucntion is called.
	 *
	 * It will load the required files
	 * @param  string $sFile [description]
	 * @return [type]        [description]
	 */
	function requireFrontEndValidation() {

		$CI = & get_instance();

		$CI->mcontents['load_js'][] = 'jqueryvalidation/1.17.0/dist/jquery.validate.min.js';

	}


	function requireTypeAhead($sSection) {

		$CI = & get_instance();

		$CI->mcontents['load_js'][] = 'autocomplete/common.js';

		$CI->mcontents['load_css'][] = 'autocomplete/autocomplete.css';

		$aSections = func_get_args();

		foreach( $aSections AS $sSection ) {

			switch( $sSection ) {

				case "fruit":
					$CI->mcontents['load_js'][] = 'autocomplete/fruit.js';
					//p($CI->mcontents['load_js']);
					break;

				case "country_multiple":
					$CI->mcontents['load_js'][] = 'autocomplete/country_multiple.js';
					break;
				case "country":
					$CI->mcontents['load_js'][] = 'autocomplete/country.js';
					break;
				case "country_multi_use":
					$CI->mcontents['load_js'][] = 'autocomplete/country_multi_use.js';
					break;

			}

		}

	}
