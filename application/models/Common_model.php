<?php
class Common_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	
	/**
	 * 
	 * get array to show in a drop down
	 * 
		'table' 		=> '',
		'id_field' 		=> 'id',
		'title_field' 	=> 'name',
		'default_text' 	=> 'Select',//show in the unselected state
		'default_value' => 0,//value in the unselected state
		'aWhere' => array()
		'aJoin' => array(),
		'aOrderBy'=>array(),
		default_value => false // whether we need to show the default option or not
	
	 */
	function getDropDownArray( $aConfig ) {
	
		$aConfig = array_merge( c('dropdown_defaults'), $aConfig ); 
			
		$this->db->select( $aConfig['id_field'].' id,'.$aConfig['title_field'].' title' );
		
		if( isset( $aConfig['aJoin'] ) ){
			if(!is_array($aConfig['aJoin'])){
				$aConfig['aJoin'] = (array)$aConfig['aJoin'];
			}
			
			foreach($aConfig['aJoin'] AS $key=>$value){
				$this->db->join( $key, $value );
			}
		}
		
		if( isset( $aConfig['aWhere'] ) ) {
			
			$this->db->where( $aConfig['aWhere'] );
		}
		
		if( isset( $aConfig['aOrderBy'] ) ) {
			foreach($aConfig['aOrderBy'] AS $key=>$value){
				$this->db->order_by($key, $value);
			}
		}
		
		$oQuery = $this->db->get( $aConfig['table'] );
		//p($this->db->last_query());
		$aData = $oQuery->result();
		
		$aDropDown = array();
		
		
		// add default option only if required
		if( $aConfig['show_default_value'] !== false ) {
			
			$aDropDown[$aConfig['default_value']] = $aConfig['default_text'];
		}
		
		
		
		if( $aData ) {
			
			foreach($aData  AS $oItem ){
				$aDropDown[$oItem->id] = $oItem->title;
			}
	
			//$aDropDown = $aDropDown + $aDropDown;
		}

		
		return $aDropDown;
	}
	

	
	/**
	 * Check if a given token is valid or not
	 *
	 * @param string $sToken
	 * @param integer $iUserId
	 * @param string $sPurpose
	 * @return miscellaneous
	 */
	function isValidToken_new($sToken, $sPurpose, $sUniqueIdentification) {
		
		$aReturn 		= array();
		$aTokenStatus 	= $this->config->item('token_status');
		
		
		
			
			$sExpiresOn = date('Y-m-d H:i:s', time() + c($sPurpose.'_token_life'));
			
			$this->db->where('purpose', $sPurpose);
			$this->db->where('unique_identification', $sUniqueIdentification);
			$this->db->where('token', $sToken);
			
			if($oToken = $this->db->get('tokens_new T')->row()){
				
				$aReturn['oToken'] = $oToken;
				
				$this->load->helper('date');
				if( ! hasExpired( date( 'Y-m-d H:i:s' ), $oToken->expires_on ) ) {
					
					$aReturn['status'] = $aTokenStatus['valid'];
				
				} else {
					
					$aReturn['status'] = $aTokenStatus['expired'];
					
				}
			} else {
				$aReturn['status'] = $aTokenStatus['invalid'];
			}
		
			//$aReturn['status'] = $aTokenStatus['invalid_purpose'];
		
		
		return $aReturn;
	}
	

	
	
	
	/**
	 * Generate tokens for various purposes
	 * 
	 * Its an improvement to the existing function generateToken()
	 *
	 * @param unknown_type $sPurpose
	 * @param unknown_type $iLength
	 * @param unknown_type $sPool
	 * 
	 */
	function generateToken_new($sPurpose,
							   $sUniqueidentification,
							   $iLength=20,
							   $iLifeTime=3600,
							   $sPool='',
							   $bStrictPool = false,
							   $bReturnObject=false,
							   $sRandomStringType = 'alnum') {
		
		
		$sToken = '';
	
		$this->load->helper('string');
		
		$aTokens = getColumnData('tokens_new', 'token', array('purpose' => $sPurpose));
		
		do{
			$sToken = random_string($sRandomStringType, $iLength);
		}
		while( in_array($sToken, $aTokens) );
		
		
		
		$sExpiresOn = date('Y-m-d H:i:s', time() + $iLifeTime);
		
		$aTokenData = array(
			'purpose' 				=> $sPurpose,
			'token' 				=> $sToken,
			'generated_on' 			=> date('Y-m-d H:i:s'),
			'expires_on' 			=> $sExpiresOn,
			'unique_identification' => $sUniqueidentification,
		);
		
		$oToken = (object)$aTokenData;
		
		
		
		$this->db->select('id');
		$this->db->where('purpose', $sPurpose);
		$this->db->where('unique_identification', $sUniqueidentification);
		
		if($oExistingToken = $this->db->get('tokens_new')->row()) {
			
			//update this record
			$this->db->where('id', $oExistingToken->id);
			$this->db->update('tokens_new', $aTokenData);
			
			$oToken->id = $oExistingToken->id;
			
		} else {
			
			//create it
			$this->db->insert('tokens_new', $aTokenData);
			
			$oToken->id = $this->db->insert_id();
		}
			
		
		if( $bReturnObject ) {
			
			return $oToken;
		} else {
			
			return $sToken;
		}
		
	}
	
	
	
	
	/**
	 * Delete the given token
	 *
	 * @param integer $iTokenId
	 */
	function deleteToken_new($iTokenId) {
		
		$this->db->where('id', $iTokenId);
		$this->db->delete('tokens_new');
	}

	
	
	/**
	 *
	 * Given a text, make the seo equivalent of it
	 * 
	 * if a unique id is provided, it will be appended to the end of the seo string
	 *
	 * $iLimit : Only $iLimit no: of characters will be ultimately used from $sText
	 */
	function getSeoName($sText, $iUniqueId=0, $iLimit=100) {
		
        
        //convert to small letters
        $sText = strtolower($sText);
		
		//remove any special characters from the filename
		
		$sText = preg_replace('$[^a-zA-Z0-9\s]$', '', $sText);
		
		
		//Limit the length of the seo name
		$sText = substr($sText, 0, $iLimit);
		
		
		//replace spaces with " - " symbol
		$sText = str_replace(" ", "-", $sText);
        
        // remove two consecutive " - " as a result of above step
        $sText = str_replace("--", "-", $sText);
        
        
        /*
		$aData = explode(' ', $sText);
		foreach( $aData as &$sValue ) {
			
			$sValue = trim($sValue);
		}
		$sText = implode('-', $aData);
		*/
		
		//If given, append the unique id to the end of the string
		
		if($iUniqueId){
			$sText .= '-' . $iUniqueId;
		}
		
		return $sText;
	}
	
	
	
	/**
	 *
	 * Generate a unique number for a new item
	 */
	function generateUniqueNumber( $aConfig=array() ) {
		
		$aConfig = array_merge(
						array(
							'table' => '',
							'field'
							  ), $aConfig
							   );
		$aAccountNumbers = array();
		$iRandom = 0;
		$this->db->select($aConfig['field']);
		$query = $this->db->get($aConfig['table']);
		
		
		if( $query !== false ) {
			
			foreach($aData = $query->result() AS $oData) {
				
				$aAccountNumbers[] = $oData->$aConfig['field'];
			}
		}
		
		
		do{
			$iRandom = mt_rand(10000000, 90000000);	
			
		} while(in_array($iRandom, $aAccountNumbers));
		
		return $iRandom;
	}
	
	
	
	/**
	 *
	 * get a list of resources
	 *
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 * @param unknown_type $aWhere
	 * @return unknown
	 *
	 * This function is experimental
	 */
	function getData( $sFrom, $sFields='', $aWhere=array(), $aOrderBy=array(), $iLimit=0, $iOffset=0 ) {
		
		$this->db->select($sFields, false);
		
		if($iLimit || $iOffset){
			$this->db->limit($iLimit, $iOffset);
		}
		
		if($aWhere){
			$this->db->where($aWhere, false);
		}
		
		if($aOrderBy){
			foreach($aOrderBy AS $key=>$value){
				$this->db->order_by($key, $value);
			}
		}
		
		//p($this->db->last_query());
		
		return $this->db->get( $sFrom )->result();
	}
	
	
	
	
	/*
	 *
	 *
	 * ------------------------------ WEBSITE SPECIFIC FUNCTIONS ---------------------------------------
	 *
	 *
	 */
	
}