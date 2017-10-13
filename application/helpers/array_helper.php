<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

/**
 * removes empty elemts from array. associative arrays wont work.
 *
 * @param string $a
 * @return array
 */
function array_trim($a) { 
	$j = 0; 
	$b = array();
	if(is_array($a) && !empty($a)){
		for ($i = 0; $i < count($a); $i++) { 
			if ($a[$i] != "") { 
			$b[$j++] = $a[$i];
			}
		}		
	}
	return $b; 
}