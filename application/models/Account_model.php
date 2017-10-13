<?php
class Account_model extends CI_Model{

	function __construct(){
		parent::__construct();
		$this->aUserStatus = c('user_status');
		$this->aProfilePicUploadType = c('profile_pic_upload_type');
		$this->aAddressTypes = c('address_types');
	}


	/**
	 * Enter description here...
	 *
	 * @param string $username
	 * @return boolean
	 */
	function isUsernameExists($username) {

		$this->db->select('id');
		$this->db->where('username', $username);
		$result	=	$this->db->get ('users');
		$result	=	$result->row();
		if($result){
			return $result->id;
		}
		return FALSE;
	}



	/**
	 * Used to check if a given email id exists
	 *
	 * @param string $sEmailId
	 * @param integer $iAccountNo
	 * @return boolean
	 */
	function isEmailExists($sEmailId='') {

		$bEmailIdExists = FALSE;


		$this->db->select('account_no');
		$this->db->where('email_id', $sEmailId);
		if( $this->db->get ('users')->row() ) {

			$bEmailIdExists = TRUE;
		}

		return $bEmailIdExists;
	}




	/**
	 * Activate a given users account
	 *
	 * perform all the routines/ checks required for making an account active
	 *
	 */
	function activateAccount($iUserId){

		//set the status of user as active
		$this->db->set('status', $this->aUserStatus['active']);
		$this->db->where('id', $iUserId);
		$this->db->update('users');

		return true;
	}



	/**
	 * creates profile of a user. Makes entry in the "user_profile" table
	 *
	 * This function is used by both user create and signup code
	 *
	 * its assumed that $aProfileData's content has been verified by the calling code.
	 * @param  [type] $iAccountNo   [description]
	 * @param  [type] $aProfileData [description]
	 * @return [type]               [description]
	 */
	function createUserProfile($oUser, $aProfileData) {

		if( $oUser ) {


			// create default profile picture for a user
			$this->load->model('profile_picture_model');
			$iProfilePictureId = $this->profile_picture_model->createDefaultProfilePicture($oUser->account_no);

			$aProfileData['profile_picture'] = $iProfilePictureId;

			// make sure there is account details entered
			if( ! isset($aProfileData['user_account_no']) || empty($aProfileData['user_account_no']) ) {
				$aProfileData['user_account_no'] = $oUser->account_no;
			}

			$this->db->insert('user_profile', $aProfileData);
		}
	}


	/**
	 * Close a given users account
	 * To be moved to the account model when we create one.
	 *
	 * @param unknown_type $iUserId
	 */
	function closeAccount($iUserId) {

		$oUser = $this->user_model->getUserBy('id', $iUserId);
		//p('here 2');exit;
		//logout the user
			$this->authentication->logout_from_db($iUserId); //this way, only the DB entry is cleared and session Data is kept intact
															 //this will help prevent the admin from being logged out, when he
															 // is closing another account.


		//set user status as closed
			$this->db->where('id', $iUserId);
			$this->db->set('status', $this->aUserStatus['closed']);
			$this->db->update('users');


		//Deal with the profile picture

			$this->db->where('user_id', $iUserId);
			$oData = $this->db->get('profile_pictures')->row();

			//delete pending images
			$this->load->helper('custom_upload');
			deletePendingImage('profile_pic', '', $oUser->account_no, true);

			//delete any old images which are left over
			deleteImage('profile_pic', $oData->upload);
			deleteImage('profile_pic', $oData->url);

			//set default picture
			$aProfilePic = array(
				'current_pic' => $this->aProfilePicUploadType['none'],
				'url' => '',
				'upload' => '',
				'last_updated_on' => date('Y-m-d H:i:s'),
			);
			$this->db->where('user_id', $iUserId);
			$this->db->update('profile_pictures', $aProfilePic);


		//empty record in user_address_map
			//$this->db->where('account_no', $oUser->account_no);
			//$this->db->delete('user_address_map');


		//delete any tokens
			$this->db->where('user_id', $iUserId);
			$this->db->delete('tokens');

		//delete any user roles
			$this->db->where('account_no', $oUser->account_no);
			$this->db->delete('user_role_map');


		// IMPORTANT : Any other site specific stuff should be handled separately in a separate function.
		// deleteSiteSpecificUserData is used now

		$this->deleteSiteSpecificUserData($oUser);



		ss('account_deleted', 'YES');

	}

	/**
	 * Site specific user data that needs to be deleted on the event of either
	 * 1. closing of account by user
	 * 2. permanently deleting a user by admin.
	 *
	 */
	function deleteSiteSpecificUserData($oUser) {

		//delete the user's association with programs
		$this->db->set('program_director', 0);
		$this->db->where('program_director', $oUser->account_no);
		$this->db->update('programs');

		//delete the user's association with campaigns
		$this->db->set('campaigner', 0);
		$this->db->where('campaigner', $oUser->account_no);
		$this->db->update('campaigns');

		//delete the user's association with campaigns
		$this->db->set('project_manager', 0);
		$this->db->where('project_manager', $oUser->account_no);
		$this->db->update('projects');

	}


	/**
	 * Stuff to do when permanently deleting a user from the system
	 *
	 *
	 */
	/*
	function permanent_delete_routines($oUser) {

		//Delete the profile picture table entry
		//(the profile picture will be taken care of by the closeAccount function)

		$this->db->where('account_no', $oUser->account_no);
		$this->db->delete('profile_pictures');


		// IMPORTANT : Any other site specific stuff should be handled separately in a separate function.
		// deleteSiteSpecificUserData is used now

		$this->deleteSiteSpecificUserData($oUser);
	}
*/

	/**
	 * Check if the user has got a username and passord set.
	 * (Users logging in from facebook/ twitter wont have a username/password with the system)
	 */
	function hasUserNamePassword($iId, $sField = 'id'){

		$this->db->select('username, password');
		$this->db->where($sField, $iId);
		$oUser = $this->db->get('users')->row();

		if($oUser->username && $oUser->password){
			return true;
		} else {
			return false;
		}
	}

	/**
	 * given password and salt, return the hash.
	 *
	 * @param  [type] $sSalt     [description]
	 * @param  [type] $sPassword [description]
	 * @return [type]            [description]
	 */
	function getPasswordHash($sSalt, $sPassword) {

		return hash('whirlpool', $sSalt . $sPassword);
	}



}
