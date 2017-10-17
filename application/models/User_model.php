<?php
class User_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->aUserStatus = c('user_status');
		$this->aProfilePicUploadType = c('profile_pic_upload_type');

	}



	/**
	 * Used to check if a given mobile number exists
	 *
	 * @param string $sMobileNum
	 * @param integer $iAccountNo
	 * @return boolean
	 */
	function isMobileNumExists($sMobileNum='') {

		$bMobileNumExists = FALSE;


		$this->db->select('id, account_no');
		$this->db->where('mobile_number', $sMobileNum);
		if( $this->db->get ('users')->row() ) {

       		$bMobileNumExists = TRUE;
       	}

       	return $bMobileNumExists;
	}



	/**
	 * get details of a single user by
	 * 	1. id
	 * 	2. account_no
	 * 	3. facebook_id
	 * 	4. email_id
	 *
	 */
	function getUserBy($sFieldName, $sValue, $sInformationType = 'basic', $aWhere=array()){

		//p($aWhere);
		$this->db->select('
						  U.*,
						  CONCAT_WS(" ", U.first_name, U.middle_name, U.last_name ) full_name',
						  false);

		$this->db->where('U.'.$sFieldName, $sValue);

		if($aWhere){
			$this->db->where($aWhere);
		}

		$oUser = $this->db->get('users U')->row();

		return $oUser;

	}

	/**
	 * Stuff the needs to be done before logout
	 *
	 */
	function logout_routines($iAccountNo=0){

	}

	/**
	 * Stuff the needs to be done after login
	 *
	 */
	function login_routines($iUserId=0){

	}

	/**
	 * get a list of users
	 *
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 * @param unknown_type $aWhere
	 * @return unknown
	 */
	function getUsers( $iLimit=0, $iOffset=0, $aWhere=array(), $aWhereIn=array(), $aWhereNotIn=array(), $aOrderBy = array()) {

		$sSelect = '
					U.*,
					CONCAT_WS(" ", U.first_name, , U.middle_name, U.last_name ) full_name,
					';

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

		if( isset( $aWhere['URM.role'] ) ) {
			$this->db->join('user_role_map URM', 'URM.account_no = U.account_no');
		}

		if( $aOrderBy ) {

			foreach( $aOrderBy AS $key=>$value ) {

				$this->db->order_by($key, $value);

			}
		}

		$aResult = $this->db->get('users U')->result();




		return $aResult;
	}



	/**
	 *
	 * A user can have multiple addresses linked. only one will be main address.
	 * @param  [type] $iAccountNo [description]
	 * @return [type]             [description]
	 */
	function getMainAddressValue($iAccountNo) {
		$value = FALSE;

		$this->db->where('account_no', $iAccountNo);
		$this->db->where('is_main', 1);
		if($oRow = $this->db->get('user_address_map')->row()) {

			$value = $oRow->address_uid;
		}

		return $value;
	}

	/**
     *
	 * get a list of users, to be used in the support section.
	 *
	 * The data will have an extra field, indicating if the user is a supporter or not
	 */
	function getUsers_Support( $iLimit=0, $iOffset=0, $aWhere=array() ) {


        $this->load->config('support_config');

		$this->db->select('
			U.*,
			CONCAT_WS(" ", U.first_name, , U.middle_name, U.last_name ) full_name,
			PP.current_pic,
            IF(SUC.amount > '.c('supporter_commitent_min_limit').', 1, 0) is_supporter',
            false);
		$this->db->join('profile_pictures PP', 'PP.user_id = U.id');
		$this->db->join('support_user_commitments SUC', 'SUC.account_no = U.account_no', 'LEFT');

		if($iLimit || $iOffset) {
			$this->db->limit($iLimit, $iOffset);
		}

		if($aWhere) {
			$this->db->where($aWhere, false);
		}
		return $this->db->get('users U')->result();
	}



	/**
	 *
	 * get the roles of a user
	 *
	 */
	function getUserRoles( $oUser ) {

		$this->db->select('URM.*, UR.title');

		$this->db->where('account_no', $oUser->account_no);
		$this->db->join('user_roles UR', 'URM.role = UR.id');
		$this->db->order_by('UR.weight', 'ASC');

		return $this->db->get('user_role_map URM')->result();
	}



    /**
     *
     * Create a dummy record in the "support_user_commitments" table
     */
    function createSupportProfile($iAccountNo){


        $this->load->model('common_model');
        $aData['uid'] = $this->common_model->generateUniqueNumber(
                                                array('table' => 'support_user_commitments',
                                                      'field' => 'uid'));
        $aData['account_no']        = $iAccountNo;
        $aData['amount']            = 0;
        $aData['payment_interval']  = 0;
        $aData['status']            = 1;
        $aData['created_on'] = $aData['updated_on'] = date('Y-m-d H:i:s');

        $this->db->insert('support_user_commitments', $aData);

        return $aData;
    }


	function getUsersByRole($sRole, $iLimit=0, $iOffset=0, $aWhere=array(), $aWhereIn=array(), $aWhereNotIn=array() ) {


		//$aUserRoles = $this->config->item('user_roles');


		$this->db->select('
						  U.id,
						  U.username,
						  U.account_no,
						  U.facebook_url,
						  U.twitter_url,
						  U.blog_url,
						  CONCAT_WS(" ", U.first_name, U.middle_name, U.last_name ) full_name,
						  U.profile_image,
						  PP.current_pic,
						  UR.name role_name,
						  UR.title role_title
						  ', false);


		if($aWhereNotIn) {

			foreach( $aWhereNotIn AS $sKey => $aValues ) {

				$this->db->where_not_in($sKey, $aValues);
			}

		}


		//$this->db->order_by('UR.weight', 'ASC');
		$this->db->join('profile_pictures PP', 'PP.user_id = U.id', 'left');
		$this->db->join('user_role_map URM', 'URM.account_no = U.account_no');
		$this->db->join('user_roles UR', 'UR.id = URM.role');
		$this->db->where('URM.role', $this->mcontents['aAllRoles'][$sRole]['id']);
		$aData = $this->db->get('users U')->result();

		return $aData;
	}




	/**
	 * Get user salutations in required format.
	 * also, we can specify which salutations to avoid
	 *
	 * @param  [type] $aAvoid [description]
	 * @return [type]         [description]
	 */
	function getUserSalutations($aAvoid, $sFormat='id-title') {

		// get user salutations
		$aSalutationsInFormat = $aSalutations = $this->data_model->getDataItem('user_salutations');

		if($sFormat != 'id-name') {
			$aSalutationsInFormat = $this->data_model->getDataItem('user_salutations', $sFormat);
		}

		// avoid the specified salutations
		$aSalutationsFlipped = array_flip($aSalutations);
		foreach($aAvoid AS $sSalutationName) {
			unset($aSalutationsInFormat[$aSalutationsFlipped[$sSalutationName]]);
		}

		return $aSalutationsInFormat;
	}
}
