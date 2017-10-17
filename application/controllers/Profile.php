<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {


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
	 * View a users profile
	 *
	 */
	public function view($input=0) {

		$iAccountNo = 0;
		$sUserName = '';

		if( is_numeric($input) ) {

			$iAccountNo = safeText($input, false, '', true);
			$this->mcontents['oUser'] = $this->profile_model->getUserProfile('account_no', $iAccountNo);

		} else {
			$sUserName = safeText($input, false, '', true);
			$this->mcontents['oUser'] = $this->profile_model->getUserProfile('username', $sUserName);
		}


		if( ! $this->mcontents['oUser'] ) {

				sf('error_message', 'The requested profile could not be found!');
				redirect('home');
		}


		// get the profile picture details
		$this->mcontents['oUser']->oProfilePicture = $this->profile_picture_model->getProfilePictureDetails($this->mcontents['oUser']->account_no);

		//p($this->mcontents['oUser']->oProfilePicture);exit;


        /**
         *
         * see if we can show this profile to a guest user
         */
        $bCanShowProfileToPublic            = TRUE;
        $sCanShowProfileToPublic_Message    = ''; // message to show user in case a profile cannot be shown.


        if( ! $bCanShowProfileToPublic ) {

            sf('error_message', $sCanShowProfileToPublic_Message);
            redirect('home');
        }



        /**
         *
         * only some of the users, can access their profiles using a pretty URL.(ex : http://example.com/username)
         * the following list of users should be in sync with the list in the /config/routes.php file
         * we are preventing these users profile to be viewd by the "un-pretty"(user/profile/view/...) way, because
         * of SEO concerns and to avoid confusion on the part of the user(s)
         *
         */
        $aSpecialUsers = array(
                            //'username',
                        );

        if( in_array($this->mcontents['oUser']->username , $aSpecialUsers) &&
            ($this->uri->segment(1) != $this->mcontents['oUser']->username) ) {

            redirect ( $this->mcontents['base_url'] . $this->mcontents['oUser']->username );
        }



		$this->mcontents['page_title'] 		    = $this->mcontents['oUser']->full_name . '\'s profile';
		$this->mcontents['page_heading'] 	    = 'Profile';
		$this->mcontents['sCurrentMainMenu']    = 'profile_view';


		$this->mcontents['iAccountNo'] = $this->mcontents['oUser']->account_no;


        $this->mcontents['aBreadCrumbs'][] = array(
                                                'uri' => '',
                                                'title' => $this->mcontents['oUser']->full_name,
                                            );


		$this->load->helper('date');
		loadTemplate('profile/view');
	}



	/**
	 *
	 *
	 * Edit a users basic profile
	 */
	public function edit($iAccountNo=0) {

		$bIsAdmin = false;

		if( ! $this->authentication->is_user_logged_in(false, '', false) ) {

			if( $this->authentication->is_admin_logged_in(true, 'user/login') ){

				$bIsAdmin = true;
			}

		} else {

			if( $this->authentication->is_admin_logged_in(false) ) {

				$bIsAdmin = true;
			}

		}

		//see if account number is present. else take account number of current user.
		// used when an admin is editing someones profile.
		$iAccountNo = $iAccountNo ? $iAccountNo : s('ACCOUNT_NO');


		//see if the profile is being edited by its rightful owner, or the admin
		if( s('ACCOUNT_NO') != $iAccountNo ) {

			if(!$bIsAdmin){

				redirect('profile/edit/' . s('ACCOUNT_NO'));
			}
		}


		// get the profile picture details
		$this->mcontents['oProfilePicture'] = $this->profile_picture_model->getProfilePictureDetails($iAccountNo);


		$sSection = 'profile_pic';

		if(isset($_POST) && !empty($_POST)) {

			$this->_validate_profile_edit();

			if( $this->form_validation->run() !== false ){

				$aUserData = $aProfileData = array();

				$aUserData['first_name'] 		= safeText('first_name');
				$aUserData['middle_name'] 		= safeText('middle_name');
				$aUserData['last_name'] 		= safeText('last_name');

				$aProfileData['gender'] 		= safeText('gender');
				$aProfileData['about_me'] 		= safeHtml('about_me');
				$aProfileData['about_me_excerpt']	= safeText('about_me_excerpt');
				$aProfileData['birthday']		= safeText('dob') ? safeText('dob') : NULL;


				$this->load->helper('date');
				$bProceed = true;

				if( $aProfileData['birthday'] ) {
					if( !validateDate( $aProfileData['birthday'], 'YYYY-MM-DD') ) {
						$bProceed = false;
					}
				}


				if( $bProceed ) {

					//start transaction
					$this->db->trans_start();

					$this->db->where('account_no', $iAccountNo);
					$this->db->update('users', $aUserData);

					$this->db->where('user_account_no', $iAccountNo);
					$this->db->update('user_profile', $aProfileData);

					//End transaction
					$this->db->trans_complete();


					//update to session.(Check if the admin was editing the user. in that case, need not update to session.)
					$bUpdateSession = false;
					if( s('TYPE') == $this->mcontents['aUserTypesFlipped']['admin'] ) {

						if( s('ACCOUNT_NO') == $iAccountNo ) {
							$bUpdateSession = true;
						}

					} else {

						$bUpdateSession = true;
					}

					if( $bUpdateSession ) {

						ss('FULL_NAME', $aData['first_name'] . " " . $aData['middle_name'] . " " . $aData['last_name']);
					}

					sf('success_message', 'Profile has been updated');


					//see if admin was editing someones profile.if yes, go back to the listing.
					if( $sRedirect = s('redirect_to') ) {

						us('redirect_to');
						redirect($sRedirect);
					} else {
						redirect('profile/edit/' . $iAccountNo);
					}

				} else {
					$this->merror['error'] = 'Invalid Date';
				}
			}
		}

		//load details of the user whose profile is being edited
		$this->mcontents['oUser'] = $this->profile_model->getUserProfile('account_no', $iAccountNo);

		// load various informations
		$this->mcontents['page_title'] 					= 'Edit Profile';
		$this->mcontents['page_heading'] 				= 'Edit Profile';

		$this->mcontents['sSection'] 					= $sSection;
		$this->mcontents['aProfilePicTypes'] 			= $this->mcontents['aProfilePicSelect'] = c('profile_pic_upload_type');
		$this->mcontents['profile_default_pic_img_tag'] = getDefaultPic('profile_pic', 'normal');




		// the various JS and CSS files that are required

		//datepicker
		$this->mcontents['load_js'][] = 'datepicker/profile_edit.js';

		// we need front end validation for this page.
		requireFrontEndValidation();
		$this->mcontents['load_js'][] = 'validation/profile_edit.js';

		$this->mcontents['load_js']['data']['iAccountNo'] = $iAccountNo;

        // common functionality
		$this->mcontents['load_js'][] = 'edit_profile.js';

		// get the text editor
		requireTextEditor(array('profile' => 'content_editor'));

		loadTemplate('profile/edit', $this->mcontents);
	}


	function _validate_profile_edit(){

		$this->form_validation->set_rules('gender', 'Gender', 'required');
		$this->form_validation->set_rules('about_me', 'About Me', 'trim');
		$this->form_validation->set_rules('about_me_excerpt', 'About Me Excerpt', 'trim');
		$this->form_validation->set_rules('dob', 'Birthday', 'trim');
	}





}

/* End of file profile.php */
/* Location: ./application/controllers/profile.php */
