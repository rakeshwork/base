<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

	/**
	 * find parent name and id of a type
	 */
	function _getParentDetails($sType){
		
        $CI = & get_instance();
        
        $CI->load->config('location_config');
		$hierarchy          = $CI->config->item('hierarchy');
		$hierarchy_flipped  = array_flip($hierarchy);
		$hierarchy_title    = $CI->config->item('hierarchy_title');
		$hierarchy_tables   = $CI->config->item('hierarchy_tables');
        
		
		$iType = $hierarchy[$sType];
		$hierarchy_flipped = array_flip($hierarchy);
		
		if($iType == 1){
			$CI->aParentData['id'] = 0;
		} else {
			$CI->aParentData['id'] = $iType - 1;
		}
		
		if($CI->aParentData['id']){
			$CI->aParentData['name'] = $hierarchy_flipped[$CI->aParentData['id']];
		}
		
		return $CI->aParentData;
	}
	
	/**
	 * Find parents and return details in an array
	 *  ex : if given district_id as 4, we wil get return as
		array(
			country => 81,
			state => 2,
			district => 4
		);
	 */
	function _findParentHistory($sType, $iId){
		
		$CI = & get_instance();
        
        $CI->load->config('location_config');
		$hierarchy          = $CI->config->item('hierarchy');
		$hierarchy_flipped  = array_flip($hierarchy);
		$hierarchy_title    = $CI->config->item('hierarchy_title');
		$hierarchy_tables   = $CI->config->item('hierarchy_tables');
        
		$aParentHistory = array();
		$iType = $hierarchy[$sType];
		
		for( $i= $iType; $i > 0; --$i ){
			
			$aParentDetails = _getParentDetails($hierarchy_flipped[$i]);
			
			$CI->db->where('id', $iId);
			$oQuery = $CI->db->get($hierarchy_tables[$i]);
			$oRow = $oQuery->row();
			$aParentHistory[ $hierarchy_flipped[$i] ] = @$oRow->id;
			
			$sVariableName = $aParentDetails['name'] . '_id';
			$iId = @$oRow->$sVariableName;
            
            //p($CI->db->last_query());
		}
		//exit;
		return $aParentHistory;
	}
    
    
    
    /**
     *
     * Populate the dropdown menu's with data on country, state,
     * district etc, depending on a location type and its ID in DB
     * 
     */    
    function getParentDropDowns($sType, $iParentId){
        
        $CI = & get_instance();
        
        $CI->load->config('location_config');
		$hierarchy          = $CI->config->item('hierarchy');
		$hierarchy_flipped  = array_flip($hierarchy);
		$hierarchy_title    = $CI->config->item('hierarchy_title');
		$hierarchy_tables   = $CI->config->item('hierarchy_tables');
        
        //$aParentDetails = _getParentDetails($sType);
        //p($iParentId);
        $aParentHistory = _findParentHistory($sType, $iParentId);
        
		//p($CI->db->last_query());
        //p($sType);
        //p($iParentId);
        //p($aParentHistory);
		$aParents = array();
		$i= 1;
		foreach( array_reverse($aParentHistory) AS $sKey => $iValue ){
			
			$aWhere = array();

			if($hierarchy[$sKey] >= 2) {
				
                
				$aParentDetails = _getParentDetails($sKey);
                //p($aParentDetails);
				if($sKey != $sType){
					$aWhere[ $aParentDetails['name'] . '_id' ] = $aParentHistory[$aParentDetails['name']];
				}
			}
			
			$sParentTable = $hierarchy_tables[ $hierarchy[$sKey] ];
			$aParents['aParent' . $i] = $CI->common_model->getDropDownArray(array(
																	'table' => $sParentTable,
																	'id_field' => 'id',
																	'aWhere' => $aWhere,
															));
			++$i;
            
            p($CI->db->last_query());
		}
        
        return $aParents;
        
    }
    
    