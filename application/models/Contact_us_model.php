<?php
class Contact_us_model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->load->config('contact_us');
		$this->aPurposeStatus = $this->config->item('contact_us_purpose_status');
	}


	/**
	 *
	 * Get a single purpose
	 *
	 */
	function getPurposeBy($sField='uid', $sValue, $aWhere=array() ) {

		$sField = 'EP.'.$sField;

        $aWhere[$sField] = $sValue;

        if( $aWhere ) {
            $this->db->where($aWhere);
        }

		$query = $this->db->get('enquiry_purposes EP');

		//p($this->db->last_query());

		return $query->row();
	}


	/**
	 * get a list of purposes
	 *
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 * @param unknown_type $aWhere
	 * @return unknown
	 */
	function getPurposes( $iLimit=0, $iOffset=0, $aWhere=array(), $aOrderBy=array() ) {


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
		return $this->db->get('enquiry_purposes EP')->result();
	}


}
