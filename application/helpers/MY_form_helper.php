<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 * Enter description here...
 *
 * @param unknown_type $width
 * @todo option to show information messages
 */
function showMessage ($width=''){
	$CI 				= 	&get_instance();
	$data['output']	=	'';
	$success_message	=	$CI->session->flashdata ('success_message');
	$validation_errors  = 	validation_errors();
	$sMessageClass = '';
	$CI->merror['error'] = ( isset( $CI->merror['error'] ) ) ? $CI->merror['error'] : '';
	
	$validation_errors	=	('' == trim($validation_errors))? $CI->merror['error']:$validation_errors;
	
	$sStyle				=	('' != $width) ? 'width:'.$width.'; ':'width:100%;';
	if ($sText = $CI->session->flashdata ('error_message')){
		
		$data['output']	=	formatMessage($sText, 'error');
		
	}else if ($sText = @$CI->mcontents['success_message']){
		
		$data['output']	=	formatMessage($sText, 'success');
		
	}else if ($sText = $CI->session->flashdata ('success_message')){
		
		$data['output']	=	formatMessage($sText, 'success');
		
	}else if (!empty($validation_errors)){
		
		$data['output']	=	formatMessage($validation_errors, 'error');
	}else{ 
		// hmmm..
	}
	
	//$data['output'] = '<div class = "'.$sMessageClass.'" style="'.$sStyle.'">'.$data['output'].'</div>';
	$CI->load->view ('output', $data);
}

/**
 * display the "mandatory" title of a form
 *
 */
function required_title() {
	
	echo '<div class="req_title">Fields marked with <span class="req">*</span> are mandatory</div>';
}


/**
 * display back button
 *
 * @param unknown_type $sDestinationURI
 * @param unknown_type $sButtonValue
 * @param unknown_type $attr
 * @return unknown
 * @todo make the attr variable functioning
 */
function backButton( $sDestinationURI='', $sButtonValue='Back', $attr=array() ) {
	
	
	//initialize attributes array
	$aInitialize = array(
							   'onclick' => '',
							   'class' => '',
							   );
	$attr = array_merge($aInitialize, $attr);
	
	
	// decide on onclick
	if( !isset( $attr['onclick'] ) || empty( $attr['onclick'] )) {
		
		if( !$sDestinationURI ) {
			
			$sDestinationURI = s('BACKBUTTON_URI') ? s('BACKBUTTON_URI') : s('previous_uri');
			
			if($sDestinationURI){
				 
				// go to the cancel URI if specified
				$attr['onclick'] = "javascript:gotoPage('".$sDestinationURI."')";
			} else {
				//act as a browser back button
				$attr['onclick'] = "javascript:window.back();";
			}
			
		} else {
			// go to the specified URI
			$attr['onclick'] = "javascript:gotoPage('".$sDestinationURI."')";
		}
	}
	
	
	// assign predetermined values.
	$attr['class'] .= ' form-button btn btn-primary';
	
	
	//Convert the attributes in array to a single string
	$sAttributes = array2Attr($attr, "double");
	
	return '<input type="button" name="back" value="' . $sButtonValue . '" ' . $sAttributes . '/>';
}

	
/**
 * Uses the data in BACKBUTTON_URI to redirect to a previous page.
 *
 * usefull if you want to redirect to the page from you came from.
 *
 * STALLED : what if there is not data in BACKBUTTON_URI???
 *
 */
function redirect_back(){
	
}