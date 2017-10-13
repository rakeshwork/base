<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 * generate a captcha
 * do what is required to get captcha for a form
 * 
 * NOTE : Core Captcha helper was edited
 * Function Name: create_captcha()
 * Line numbers : 44, 114, 193, 199, 235, 239
 *
 * @param array $aConfig
 * @return array
 */
function getCaptcha($aConfig=array(), $bHtml=false) {
	
	$CI = & get_instance();
	
	$CI->load->config('captcha');
	
	//if there was some config option set by the user, then save it. will be needed when we refresh captcha
	// make sure to delete this from session when deleting captcha
	if( $aConfig ) {
		ss( 'custom_captcha_settings', serialize($aConfig));
	}
	
	$vals = array_merge(array(
					    'word' 					=> '',
					    'img_path' 				=> $CI->config->item('base_path') . 'captcha/',
					    'img_url' 				=> $CI->config->item('captcha_url'),
					    'font_path' 			=> $CI->config->item('captcha_font_path'),
					    'img_width' 			=> $CI->config->item('captcha_img_width'),
					    'img_height' 			=> $CI->config->item('captcha_img_height'),
					    'expiration' 			=> $CI->config->item('captcha_expiration'),
					    'word_length' 			=> $CI->config->item('captcha_word_length'),
					    'class' 				=> $CI->config->item('captcha_img_class'),
					    'custom_font_size' 		=> 26,
						'img_id'        		=> 'captcha_image_id',
						'captcha_container_id' 	=> 'captcha_container'
					    ), $aConfig);

					    //p($vals);exit;
	$aCaptcha = create_captcha($vals);
	
	//p($aCaptcha);
	//exit;
	
	
	
	//store the word to the session
	$CI->session->set_userdata($aCaptcha);
	
	//p( s('word') );
	
	//load necessary JS/ CSS files
	//$CI->mcontents['load_js'][] = 'jquery.livequery.js';
	$CI->mcontents['load_js'][] = 'captcha.js';
	$CI->mcontents['load_css'][] = 'captcha.css';
	$CI->mcontents['load_js']['data']['captcha_container_id'] = $vals['captcha_container_id'];
	
	if($bHtml){
		//fetch the whole captcha block of the form
		return $CI->load->view('captcha', $aCaptcha, true);		
	} else {
		return $aCaptcha;
	}

}
/**
 * will fetch the whole captcha block of the form
 *
 * @param unknown_type $aConfig
 * @return unknown
 */
function getCaptchaView($aConfig=array()){
	
	$aData = getCaptcha($aConfig);
	
	$CI = &get_instance();
	
	return $CI->load->view('captcha', $aData, true);
}



/**
 * Check if the captcha entered by the user matches the one in our session
 */
function isValidCaptcha(){
	
	$CI = &get_instance();
	$bReturn = false;
	
	$CI->load->config('captcha');
	//var_dump(c('captcha_case_sensitive'));
	//exit;
	
	$sFnName = c('captcha_case_sensitive') ? 'strcmp' : 'strcasecmp';
	
	//p('POST : '.$CI->input->post('captcha'));
	//p( 'session : '.s('word') );
	//p( $CI->session->userdata );
	//exit;
	
	if( 0 === $sFnName($CI->input->post('captcha'), s('word')) ) {
		
		$bReturn = true;
		
	}
	
	destroyCaptcha();
	
	return $bReturn;
	
}

/**
 * destroy the current captcha information in the session
 */
function destroyCaptcha(){
	
	$CI = &get_instance();
	
	$aCapthaData = array('captcha_word', 'captcha_time', 'captcha_image');//same keys as in the captcha_helper
	
	//unset from session, any custom settings used for this captcha
	us('custom_captcha_settings');
	
	$CI->session->unset_userdata($aCapthaData);
}

/**
 * Used after a captcha is validated over AJAX
 *
 * if the cpatcha was valid, this function is called.
 * the code generated is sent back via AJAX to the form, which is to be validated once the form is finally submitted.
 *
 * TO IMPROVE : associate a timer with this code, so that its valid only for a certain time.
 *
 */
function setValidatedCaptcha(){

	$CI = &get_instance();
	$code = mt_rand();
	$CI->session->set_userdata('validated_captcha_code', $code);
	return $code;
}

/**
 *
 * USed along with setValidatedCaptcha().
 *
 */
function isValidatedCaptcha(){

	$CI = &get_instance();
	$bReturn = false;
	if( $CI->session->userdata('validated_captcha_code') == $CI->input->post('validated_captcha_code') ){
		$bReturn = true;
	}
	
	$CI->session->unset_userdata('validated_captcha_code');
	return $bReturn;
}