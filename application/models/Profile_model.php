<?php
class Profile_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}


	/**
	 *
	 * Get all information required to show a user profile
	 *
	 * @param integer $iAccountNo
	 *
	 */
	function getUserProfile($by = 'account_no', $sValue) {

		$sField = 'U.account_no';
		if($by == 'username'){
			$sField = 'U.username';
		}

		$this->db->select("
			U.*,
			UP.gender,
			UP.birthday,
			UP.profile_picture,
			UP.about_me,
			UP.about_me_excerpt,
			CONCAT_WS(' ', U.first_name, U.middle_name, U.last_name ) full_name,
			", false);
		$this->db->join('user_profile UP', 'UP.user_account_no = U.account_no');
		$this->db->where($sField, $sValue);

		$oUser = $this->db->get('users U')->row();

// p($this->db->last_query());
// p($oUser);
// exit;

		return $oUser;
	}


	/**
	 *
	 *  get a number of user profiles
	 * @param  string $by     [description]
	 * @param  [type] $sValue [description]
	 * @return [type]         [description]
	 */
	 function getUsersAndProfiles( $iLimit=0, $iOffset=0, $aWhere=array(), $aWhereIn=array(), $aWhereNotIn=array(), $aOrderBy = array()) {

 		$sSelect = "
			U.*,
			UP.gender,
			UP.birthday,
			UP.profile_picture,
			UP.about_me,
			UP.about_me_excerpt,
			pp.image_name,
			CONCAT_WS(' ', U.first_name, U.middle_name, U.last_name ) full_name,
			";

 		$this->db->select($sSelect, false);

 		if($iLimit || $iOffset) {
 			$this->db->limit($iLimit, $iOffset);
 		}

 		if($aWhere) {
 			$this->db->where($aWhere, false);
 		}

 		if($aWhereIn) {

 			foreach( $aWhereIn AS $sKey => $aValues ) {

 				$this->db->where_in($sKey, $aValues);
 			}

 		}

 		if($aWhereNotIn) {

 			foreach( $aWhereNotIn AS $sKey => $aValues ) {

 				$this->db->where_not_in($sKey, $aValues);
 			}

 		}


 		if( $aOrderBy ) {

 			foreach( $aOrderBy AS $key=>$value ) {

 				$this->db->order_by($key, $value);

 			}
 		}




		$this->db->join('user_profile UP', 'UP.user_account_no = U.account_no');
		$this->db->join('profile_pictures PP', 'PP.account_no = UP.user_account_no');

		$oUser = $this->db->get('users U')->result();

	// p($this->db->last_query());
	// p($oUser);
	// exit;

		return $oUser;
	}




}
