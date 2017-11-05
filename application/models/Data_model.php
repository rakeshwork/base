<?php
class Data_model extends CI_Model{

	function __construct(){


		parent::__construct();
	}


	/**
	 *
	 * 	Used for fetching from the database, the small data sets which are
	 * 	stored as id, name, title, description.
	 *
	 * @return [type] [description]
	 */
	function getDataItem($sItemName, $format='id-name') {

		$aReturn = array();

		$aItemName_TableName_Map = array(
			'genders' 			=> 'genders',
			'user_types' 		=> 'user_types',
			'user_statuses' 	=> 'user_statuses',
			'online_statuses' 	=> 'online_statuses',
			'user_salutations' 	=> 'user_salutations',
			'online_via' 		=> 'authenticating_sources',
			'enquiry_purposes' 	=> 'enquiry_purposes',
			'page_statuses' 	=> 'page_statuses',
			'token_statuses' 	=> 'token_statuses',
			'token_purposes' 	=> 'token_purposes',
			'enquiry_purpose_statuses' 	=> 'enquiry_purpose_statuses',
			'profile_picture_sources' 	=> 'profile_picture_sources',

			//project Specific
			'department_statuses' => 'department_statuses'

		);

		if( isset($aItemName_TableName_Map[$sItemName]) ) {

			$sTableName = $aItemName_TableName_Map[$sItemName];

			$aTableData = $this->db->get($sTableName)->result();

			if( is_array($format) ) {

				foreach($format AS $sFormat) {
					$aReturn[] = $this->getDataInFormat($aTableData, $sFormat);
				}

			} else {
				$aReturn = $this->getDataInFormat($aTableData, $format);
			}

		}

	    return $aReturn;
	}

	/**
	 * helper function of getDataItem() .
	 *
	 * @param  [type] $aTableData [description]
	 * @param  [type] $sFormat    [description]
	 * @return [type]             [description]
	 */
	private function getDataInFormat($aTableData, $sFormat) {

		$aData = array();
		$aParts = explode('-', $sFormat);

		$sKey 	= $aParts[0];
		$sValue = $aParts[1];


		foreach($aTableData AS $oRow) {

			$aData[$oRow->$sKey] = $oRow->$sValue;
		}
		return $aData;
	}

}
