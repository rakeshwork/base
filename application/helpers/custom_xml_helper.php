<?php
/**
 * Generate an XML from an array
 *
 * @param unknown_type $aData
 * @param unknown_type $bEcho
 * @return unknown
 */
function array2XML($aData=array(), $bEcho=false, $bXmlHead = false) {

	$CI = &get_instance();
	$CI->load->library('arraytoxml');

	$CI->arraytoxml->setArray($aData);
	
	return $CI->arraytoxml->outputXML($bEcho, $bXmlHead);

}


