<?php
class Profile_picture_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}

	/**
	 *
	 * Get profile picture entry of a given user
	 *
	 * @param  [type] $iAccountNo [description]
	 * @return [type]             [description]
	 */
	function getProfilePictureDetails($iAccountNo) {

		$this->db->where('PP.account_no', $iAccountNo);
		$oRow = $oUser = $this->db->get('profile_pictures PP')->row();

		
		return $oRow;
	}


	/**
	 * create default profile picture for a user
	 * @param  [type] $iAccountNo [description]
	 * @return [type]             [description]
	 */
	function createDefaultProfilePicture($iAccountNo) {

		$aProfilePictureSources = $this->data_model->getDataItem('profile_picture_sources');
		$aProfilePictureSourcesFlipped = array_flip($aProfilePictureSources);

		$aData = array(
			'account_no' => $iAccountNo,
			'image_name' => NULL,
			'current_source' => $aProfilePictureSourcesFlipped['none']
		);

		$this->db->insert('profile_pictures', $aData);

		return $this->db->insert_id();
	}

}
