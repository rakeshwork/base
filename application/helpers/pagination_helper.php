<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );


	
	function requirePagination( $aSettings=array() ) {
			
            $CI = & get_instance();
            
		$aDefaults = array(
			
			'base_url' 			=> '',
			'base_segment' 		=> $CI->mcontents['uri_1'] . '/' . $CI->mcontents['uri_2'],
			'total' 			=> isset( $CI->mcontents['iTotal'] ) ? $CI->mcontents['iTotal'] : 0,
			'per_page' 			=> isset( $CI->mcontents['iPerPage'] ) ? $CI->mcontents['iPerPage'] : 10,
			'uri_segment' 		=> 3,
			'page_query_string' => true,
			
		);
		
		$aSettings = array_merge($aDefaults, $aSettings);
	
	
		if ( ! $aSettings['base_url'] ) {
			
			$sQueryString = str_replace( $aSettings['base_segment'], '', current_complete_uri());
			$sQueryString = str_replace('?', '', $sQueryString);
			
			//p($sQueryString);
			parse_str($sQueryString, $aQueryParams);
			
			if( isset($aQueryParams['per_page']) ) {
				unset($aQueryParams['per_page']);
			}
			$sUri = http_build_query($aQueryParams);
			
			$aSettings['base_url'] = c('base_url').$sUri;
		}
		
	
		
		$CI->load->library('pagination');
		$CI->aPaginationConfiguration = array();
		$CI->aPaginationConfiguration['base_url'] 	= c('base_url'). $aSettings['base_segment'] . '?' . $sUri;
		$CI->aPaginationConfiguration['total_rows'] 	= $CI->mcontents['iTotal'];
		$CI->aPaginationConfiguration['per_page'] 	= $CI->mcontents['iPerPage'];
		$CI->aPaginationConfiguration['uri_segment'] 	= $aSettings['uri_segment'];
		$CI->aPaginationConfiguration['page_query_string'] = $aSettings['page_query_string'];
		$CI->pagination->customizePagination();
		
		$CI->pagination->initialize($CI->aPaginationConfiguration);
		$CI->mcontents['sPagination'] = $CI->pagination->create_links();
		
		
	}