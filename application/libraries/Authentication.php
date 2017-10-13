<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Authentication {

 	protected $CI;

	public function __construct() {

		// Assign the CodeIgniter super-object
		$this->CI =& get_instance();

		$this->CI->load->model('data_model');

		$this->aUserType 		= $this->CI->data_model->getDataItem('user_types');
		$this->aUserTypeFlipped = array_flip($this->aUserType);
		$this->aUserStatus 		= $this->CI->data_model->getDataItem('user_statuses');
		$this->aUserStatusFlipped = array_flip($this->aUserStatus);
		$this->aOnlineStatus 	= $this->CI->data_model->getDataItem('online_statuses');
		$this->aOnlineStatusFlipped = array_flip($this->aOnlineStatus);
		$this->aOnlineVia 		= $this->CI->data_model->getDataItem('online_via');;
		$this->aOnlineViaFlipped = array_flip($this->aOnlineVia);

		$this->CI->load->model('user_model');
		$this->CI->load->model('account_model');
		//p( $this->CI->session->userdata );

		// query which selects basic information of a user
		$this->sUserSelect 	= "
		username AS USERNAME,
		email_id AS EMAIL,
		CONCAT_WS(' ', first_name, middle_name, last_name ) FULL_NAME,
		status,
		account_no ACCOUNT_NO,
		online_via ONLINE_VIA,
		type TYPE,
		salt
		";

	}


	function process_login($login = NULL){

		if (!is_array ($login) || 0 >= count ($login)){
				return FALSE;
		}

		$login_status	= array('error' => '');

		$username       = $login['username'];
		$password       = $login['password'];

		$this->CI->db->select ($this->sUserSelect, FALSE);
		$this->CI->db->where('username', $username);
		$oRow = $this->CI->db->get ('users')->row();

		// p($this->CI->db->last_query());
		// exit;

		if ( $oRow ) {

			$this->CI->load->model('account_model');
			$sHash = $this->CI->account_model->getPasswordHash($oRow->salt, $password);

			$this->CI->db->select("
									U.*,
									CONCAT_WS(' ', first_name, middle_name, last_name ) full_name
									");
			$this->CI->db->where('username', $username);
			$this->CI->db->where('hash', $sHash);
			$oUser = $this->CI->db->get ('users U')->row();

// p('PASSWORD : ' . $password);
// p('SALT : ' . $oRow->salt);
// p('HASH : ' . $sHash);
// p($this->CI->db->last_query());
// p($oUser);
// exit;
			if( $oUser ) {

				$login_status['oUser'] = $oUser;




				if($this->aUserStatusFlipped['active'] == $oUser->status){

					$this->makeLogin('', 'account_no', $oUser);

				}else if($this->aUserStatusFlipped['pending'] == $oUser->status){
					$login_status['error'] = 'Your Account is not activated yet. Please check your email.';
				}else if($this->aUserStatusFlipped['blocked'] == $oUser->status){
					$login_status['error'] = 'Your Account is Blocked';
				}else if($this->aUserStatusFlipped['closed'] == $oUser->status){


					$this->CI->account_model->activateAccount($oUser->ACCOUNT_NO);
					$this->makeLogin('', 'id', $oUser);

					$login_status['message'] = 'Good To have you back!!'; //to be replaced by notification system later on
				}

				//p($login_status);exit;
				return $login_status;

			} else {

				$login_status['error'] = 'Invalid Username or Password';
				return $login_status;
			}
		} else {

			$login_status['error'] = 'Invalid Username or Password';
			return $login_status;
		}


	}


	/**
	 * Make a user login
	 *
	 * the actual process of logging in a user after the authentication
	 * Used for logging in a user from any part of code. using the following unique identifiers
	 * 	1. username
	 * 	2. facebook_id
	 * 	3. account_no
	 * 	4. email_id
	 *
	 * @param integer $iUserId
	 * @param string type of user-id as specified in config - online_via
	 */
	function makeLogin($iId=0, $sFieldName = 'id', $oUser=false) {

		$sTaskStatus = true; // to indicate the status of the login process. was it success or failed?

		if(!$oUser) {

			$this->CI->db->select($this->sUserSelect, FALSE);
			$this->CI->db->where($sFieldName, $iId);
			if(!$oUser = $this->CI->db->get('users')->row()){

				return false;

			}
		}

		$iAccountNo = $oUser->account_no;


		if($sFieldName == 'facebook_id') {

			$aLoginData['online_via'] 	= $this->aOnlineViaFlipped['facebook'];
		} elseif($sFieldName == 'account_no') {
			$aLoginData['online_via'] = $this->aOnlineViaFlipped['system'];
		}

		//set login status for the user
		$aLoginData['online_status'] = $this->aOnlineStatusFlipped['online'];
		$this->CI->db->where('account_no', $iAccountNo);
		$this->CI->db->update('users', $aLoginData);


		//store data to session
		$this->setUserSesssionData($oUser);


		return $sTaskStatus;
	}


	/**
	 * checks if a user is logged in
	 *
	 * When called, This function will make sure that the value stored
	 * in session variable "USERID" is correct if the user is logged in, or the variable "USERID" is not set at all.
	 *
	 * @param boolean should redirect if user not logged in
	 * @param string URI to redirect if the user is not logged in
	 * @param boolean $bBreachAttempt
	 * @return integer 0 on failure or the current users system-id
	 */
	function is_user_logged_in ($bRedirect=false, $sRedirectTo='home', $bReturnObject=false, $bTest=false){

		$return = 0;


		$oUserData = $this->CI->user_model->getUserBy('account_no', s('ACCOUNT_NO'), 'full');


		if($oUserData) {

			if($oUserData->status == $this->aUserStatusFlipped['active']) {
                // p($this->aOnlineStatusFlipped);
                // p($oUserData);
                // exit;
				if ( $oUserData->online_status == $this->aOnlineStatusFlipped['online'] ) {


					if($oUserData->online_via == $this->aOnlineViaFlipped['system']) {

							if($bReturnObject){
								$return = $oUserData;
							} else {

								$return = s('ACCOUNT_NO');
							}

					} elseif( $this->CI->config->item('enable_facebook_login') && ( $oUserData->online_via == $this->aOnlineVia['facebook'] ) ) {

						//$user = $this->CI->facebook->getCurrentUser();
						$user = $this->CI->facebook->request('get', '/me');

						// We may or may not have this data based on whether the user is logged in.
						//
						// If we have a $user id here, it means we know the user is logged into
						// Facebook, but we don't know if the access token is valid. An access
						// token is invalid if the user logged out of Facebook.

						// Login or logout url will be needed depending on current user state.
						if ($user) {

							if($bReturnObject){
								$return = $oUserData;
							} else {
								$return = s('USERID');
							}

						} else {
							$return = false;
						}


						/*
						contact facebook for confirmation
							IF FACEBOOK CONFIRMS
								RETURN USERID
							ELSE
								RETURN FALSE
						*/
					} else {
						return 0;
					}

				} else {

					$this->logout_from_db(); // logout a user whos was forced to go offline by admin

					if( $bRedirect ) {

						setPostLoginRedirect();
						sf('error_message', 'You have to login before proceeding');
						redirect($sRedirectTo);
					} else {
						//p( 'here 1' );exit;
						$return = 0;
					}
				}

			}  else {


					$this->logout_from_db(); // logout a user whos status was changed to inactive by admin
					if( $bRedirect ){

						setPostLoginRedirect();
						sf('error_message', 'You have to login before proceeding');
						redirect($sRedirectTo);
					} else {
						$return = 0;
					}
			}
		} else {

			if( $bRedirect ){

				setPostLoginRedirect();
				sf('error_message', 'You have to login before proceeding');
				redirect($sRedirectTo);
			} else {
				$return = false;
			}
		}


		return $return;
	}


	/**
	 *
	 * check if the current user is administrator.
	 * @param boolean $bRedirect
	 */
	function is_admin_logged_in ($bRedirect=false, $sRedirectTo=''){


		$sRedirectTo = $sRedirectTo ? $sRedirectTo : c('login_uri_segment');
		if( ($this->CI->session->userdata ('USER_TYPE') == $this->aUserTypeFlipped['admin']) ){
			return true;
		} else {
			setPostLoginRedirect();


			//echo 'test', $sRedirectTo;exit;
            //var_dump($bRedirect);exit;
			if( $bRedirect ){
				//o('test');exit;
				redirect($sRedirectTo);
			} else {
				//o('test 2'); exit;
				return false;
			}
		}
	}



	/**
	 *
	 * logout a user
	 *
	 * also see the hook , which will use this function to logout those users who does not have a valid session_id
	 */
	function logout ($iAccountNo=0){


	    if(!$iAccountNo){
	    	$iAccountNo = s('ACCOUNT_NO');
	    }


	    $oUser = $this->CI->user_model->getUserBy('account_no', $iAccountNo);



		$this->CI->user_model->logout_routines($iAccountNo);

		$session_data 	= array (
                                   	'USERID',
                                   	'USERNAME',
                                   	'FULL_NAME',
                                   	'EMAIL',
                                   	'USER_TYPE',
                                   	'USER_ROLES',
                                   	'ONLINE_VIA',
                                   	'ADDRESS_ID',
									'ACCOUNT_NO',

									'post_login_redirect',
								);
		//$session_data = array();
	    $this->CI->session->unset_userdata($session_data);

		//delete the session data from the DB (ci_session table) for this user
		$this->logout_from_db($iAccountNo);

		return TRUE;
	}

	/**
	 * clear the login details of a user from the session table (ci_session).
	 * this will result in the user being logged out.
	 *
	 * this function is being called in two places.
	 * 1. in a normal logout procedure by a user.
	 * 2. when admin wants to force a user to logout.
	 *
	 * 		in this second case, we need to logout a user ONLY from the DB. because, a normal logout will clear the session.
	 * 		resulting in the administrator himself being logged out.
	 */
	function logout_from_db($iAccountNo=0){


		if( ! $iAccountNo ) {
			$iAccountNo = s('ACCOUNT_NO');
		}

	    //clear data from 'users' table in DB

		$aDBData['online_status'] = $this->aOnlineStatusFlipped['offline'];
		$this->CI->db->where('account_no', $iAccountNo);
		$this->CI->db->update('users', $aDBData);

	}

	/**
	 * load some user data to session upon signing in
	 *
	 * @param unknown_type $iId
	 */
	function setUserSesssionData($oUser=false) {


		if ( $oUser ) {

			$session_data 	= array (

                               	'ACCOUNT_NO'	=> $oUser->account_no,
                               	'USERNAME'		=> $oUser->username,
                               	'FULL_NAME' 	=> $oUser->full_name,
                               	'EMAIL'     	=> $oUser->email_id,
                               	'USER_TYPE'     => $oUser->type,
                               	'USER_ROLES'    => getUserRoles( $oUser->account_no ),
                               	'ONLINE_VIA'	=> $oUser->online_via,
                            );

			$this->CI->session->set_userdata($session_data);


			$data = array('last_login' => date('Y-m-d H:i:s'));
			$this->CI->db->where('account_no', $oUser->account_no);
			$this->CI->db->update('users', $data);
		}
	}


	/**
	 * Generate an account number for a new user
	 *
	 */
	function generateAccountNumber(){

		$aAccountNumbers = array();
		$iRandom = 0;
		$this->CI->db->select('account_no');
		foreach($aData = $this->CI->db->get('users')->result() AS $oData){
			$aAccountNumbers[] = $oData->account_no;
		}

		do{
			$iRandom = mt_rand(10000000, 90000000);

		} while(in_array($iRandom, $aAccountNumbers));

		return $iRandom;
	}

	/**
	 *encryption of a password is handled in this function
	 */
	function encryptPassword($sRawPassword){

		$encrptedPassword = md5($sRawPassword);
		return $encrptedPassword;
	}

	/**
	 *
	 * generates salt for a user
	 * @return [type] [description]
	 */
	function getSalt() {

		$this->CI->load->helper('string');
		return random_string('alnum', 32);
	}


}
// End of library class
// Location: system/application/libraries/authentication.php
