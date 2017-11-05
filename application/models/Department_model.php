
<?php
class Department_model extends CI_Model{

	function __construct(){
		parent::__construct();


	}


	/**
	 *
	 * Get a single Department
	 *
	 */
	function getDepartmentBy($sField='id', $sValue, $aWhere=array() ) {

		$sField = 'DEPT.'.$sField;

        $aWhere[$sField] = $sValue;

        if( $aWhere ) {
            $this->db->where($aWhere);
        }

		$query = $this->db->get('departments DEPT');

		//p($this->db->last_query());

		return $query->row();
	}


	/**
	 * get a list of Departments
	 *
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 * @param unknown_type $aWhere
	 * @return unknown
	 */
	function getDepartments( $iLimit=0, $iOffset=0, $aWhere=array(), $aOrderBy=array() ) {


		if($iLimit || $iOffset){
			$this->db->limit($iLimit, $iOffset);
		}

		if($aWhere){
			$this->db->where($aWhere, false);
		}

		if($aOrderBy) {
			foreach($aOrderBy AS $key=>$value){
				$this->db->order_by($key, $value);
			}
		}


		//p($this->db->last_query());
		return $this->db->get('departments DEPT')->result();
	}


}
