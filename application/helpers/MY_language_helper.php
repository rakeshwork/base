<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 * to access language variables easily
 * TO: give an example useage with this function desc
 */
function t($line, $aReplaceData = array()) {
    $CI = &get_instance();
    $language		=	s('current_language') ? s('current_language') : 'english';
    
    $sString = ($t = $CI->lang->line($line, $language)) ? $t : $line;
    
    
    //check if there is any data to insert into the string
    if($aReplaceData) {
//    	p('HERE 1 ');
//    	p($sString);
//    	p($aReplaceData);
//		p(vsprintf($sString, $aReplaceData));
		
		return vsprintf($sString, $aReplaceData);
    	
    } else {
    	
    	return $sString;
    }
    
}