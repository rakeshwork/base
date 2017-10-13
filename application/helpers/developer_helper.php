<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function p($aData, $bReturn=false){
	
	$CI = &get_instance();
	
	$bShow = true;
	/*
	if ( ENVIRONMENT_ == 'production' ) {
		if( ! $CI->authentication->is_admin_logged_in() ) {
			$bShow = false;
		}
	}
	*/
	
	if ( $bShow ) {
		
		$sOutput = 	'</br>'.$CI->uri->rsegment(1).
					'</br>'.$CI->uri->rsegment(2).
					'<pre>'.print_r($aData, true).'</pre></br>';
		
		if( $bReturn ) {
			return $sOutput;
		} else {
			echo $sOutput;	
		}
	}


}

function o($stuff){
	
	
	echo $stuff.'</br>';
}
	
function write_log($sMessage = '', $sLogFile = 'common.html') {

	
	//o( 'testing' );
	$CI = &get_instance();
	
	//if the file exists, append to it. else create a new one
	
		
	$sLogFilePath = $CI->config->item('base_path').'logs/';
	$handle = fopen($sLogFilePath.$sLogFile, "a");
	
	
	// append to the end
	$sMessage .= "<br/> ============= Written On : ".date('Y-m-d H:i:s', time())." ============= <br/>";
	
	$aFile = explode('.', $sLogFile);
	if($aFile[1] == 'html'){
		$sMessage .= '<div style="width:100%;text-align:right;"><a href = "'.c('base_url').'developer/purge_log/'.$sLogFile.'">PURGE LOG</a></div>';
	}
		
	$return = fwrite($handle, $sMessage);
	fclose($handle);
}

/**
 * clear the log file of its contents and redirect to the logs page itself
 */
function purge_log($sLogFile){
	
	$sLogFilePath = APPPATH.'logs/';
	$handle = fopen($sLogFilePath.$sLogFile, "w");
	fwrite($handle, '');
	fclose($handle);
	
}