<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile_picture extends CI_Controller {


	public function __construct() {

		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('profile_model');
		$this->load->model('profile_picture_model');
		$this->load->helper('profile');

	}


	public function index() {

	}


	/**
	 *
	 *
	 * This function will display the profile image change functionality in a different window.
	 *
	 */
	function change_profile_pic($iAccountNo=0) {



		if( $this->authentication->is_user_logged_in(true, 'user/login') ) {

			if(!$this->authentication->is_admin_logged_in()) {

				if( ! isOwner('account', $iAccountNo, s('USERID')) ) {

					//tried to change someone elses profile pic
					sf('error_message', 'You do not have access to that section.');
					redirect('user/account');

				}
			}
		}

		$sSection = 'profile_pic';



		if( ! $this->mcontents['oProfilePicture'] = $this->profile_picture_model->getProfilePictureDetails($iAccountNo) ) {

			sf('error_message', 'User does not exists');
			redirect('profile/edit/'.$iAccountNo);
		}


		$sOldFileName = $this->mcontents['oProfilePicture']->image_name;


		if( isset( $_FILES['profile_pic'] ) && $_FILES['profile_pic']['error'] != 4 ) {


			$this->load->helper('string');

			$sImageName = '';
			do {

				$sRandomString = random_string('alnum', 10);
				$sImageName = $sRandomString . '.jpg';

				$this->db->where('image_name', $sImageName);
			} while ( $this->db->get('profile_pictures')->row() );


			if( $sImageName ) {

				$this->load->helper('custom_upload');
				$aConfig = array(
								'file_name' => $sImageName,
								'append_real_name' => false
							);
				$aDisplayImageUploadData 	= uploadFile('image', 'profile_pic', 'profile_pic', $aConfig);


				$aProfilePictureSources = $this->data_model->getDataItem('profile_picture_sources');
				$aProfilePictureSourcesFlipped = array_flip($aProfilePictureSources);
				$aData = array(
					'image_name' 		=> $aDisplayImageUploadData['file_name'],
					'current_source' 	=> $aProfilePictureSourcesFlipped['system']
				);

				//p($aData);exit;

				$this->db->where('account_no', $iAccountNo);
				$this->db->update('profile_pictures', $aData);


				// delete the old profile picture
				$this->load->helper('custom_file');
				deleteFile('image', 'profile_pic', $sOldFileName);

				sf('success_message', 'The profile picture has been updated');
				redirect('profile/edit/' . $iAccountNo);
			}


		}



		loadTemplate('profile_picture/change_profile_pic');
	}


}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
