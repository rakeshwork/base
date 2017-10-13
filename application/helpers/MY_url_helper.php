<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

/**
 *
 * get the complete URI including query strings
 */
function current_complete_uri()
{
    $CI =& get_instance();

    $uri = $CI->uri->uri_string();
    return $_SERVER['QUERY_STRING'] ? $uri.'?'.$_SERVER['QUERY_STRING'] : $uri;
}


/**
*
* Any stuff to do with the query string, before being used in pagination.
*/
function preprocess_query_string_for_pagination( $sUriSegment ) {
   
    $sQueryString = str_replace($sUriSegment, '', current_complete_uri());
    $sQueryString = str_replace('?', '', $sQueryString);
    
    
    parse_str($sQueryString, $aQueryParams);
    
    if( isset($aQueryParams['per_page']) ) {
        
        unset($aQueryParams['per_page']);
    }
    
    $sUri = http_build_query($aQueryParams);
    
    return $sUri;
}