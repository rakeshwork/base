<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {


	public function __construct() {

		parent::__construct();

		$this->load->model('user_model');
		$this->load->model('account_model');
		$this->load->model('profile_model');
		$this->load->helper('profile');

		$this->aErrorTypes 		= $this->config->item('error_types');

		$this->mcontents['sCurrentMainMenu'] = 'user';
	}

	public function index() {

		if( $this->authentication->is_user_logged_in (true, 'user/login') ){
			$this->home();
		}
	}


	function login () {

		$this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Login';

		if( ! $this->authentication->is_user_logged_in() ) {

		    if (!empty($_POST)) {

				$this->form_validation->set_rules('username', 'Username', 'trim|required');
				$this->form_validation->set_rules('password','Password', 'trim|required');
				if ($this->form_validation->run() == TRUE) {
					$aUserData['username'] 	= safeText('username');
					$aUserData['password']	= safeText ('password');
					$login_details			= $this->authentication->process_login ($aUserData);


					if('' == $login_details['error']) {

						if( @$login_details['message'] ) {

							sf('success_message', $login_details['message']);
						}

						// See if the user needs to be redirected to a previous page he was seeying
						// redirect the users to the link which they were trying to access

						if( $post_login_redirect = s('post_login_redirect') ) {

							us('post_login_redirect');
							redirect($post_login_redirect);

						} else {

							if($this->authentication->is_admin_logged_in()){

								redirect('admin');

							} else {

								redirect('home');
							}

						}

					}else{
					 	$this->merror['error']	=	$login_details['error'];
					}
				}
		    }

		} else {

			redirect('home');
		}


		// we need front end validation for this page.
		requireFrontEndValidation();
        $this->mcontents['load_js'][] = 'validation/login.js';

		loadTemplate('user/login');
	}




	/**
	 *
	 * Used by admin user to create a new user.
	 */
	function create() {

		$this->authentication->is_admin_logged_in (true);

		isAdminSection();


		$this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Create New User';

		$error	= FALSE;


		if (!empty($_POST) && isset($_POST)) {

			$this->_validate_create_user();

			$bValidationPassed = false;

			if (true === $this->form_validation->run()) {
				$bValidationPassed = true;
			}

			// verify user types
			$bUserTypeVerified = false;
			if( array_key_exists( safeText('user_type'), $this->mcontents['aUserTypes'] ) ) {

				// user type cannot be administrator
				if( $this->mcontents['aUserTypesFlipped']['admin'] != safeText('user_type') ) {
					$bUserTypeVerified = true;
				}
			}

			// verify gender
			$bGenderVerified = false;
			if( array_key_exists( safeText('gender'), $this->mcontents['aGenders'] ) ) {

				$bGenderVerified = true;
			}


			// verify uniqueness of email id
			$bIsUniqueEmailId = false;
			if( ! $this->account_model->isEmailExists( safeText('email_id') ) ) {
				$bIsUniqueEmailId = true;
			}




			if( $bValidationPassed && $bUserTypeVerified && $bGenderVerified && $bIsUniqueEmailId ) {

				$aUserData = $aProfileData = array();

				// Generate account number
				$aConfig = array(
								'table' => 'users',
								'field' => 'account_no',
							);
				$iAccountNo = $this->common_model->generateUniqueNumber($aConfig);

				//salt the password
				$sPassword 	= safeText('password');
				$sSalt 		= $this->authentication->getSalt();
				$sHash 		= $this->account_model->getPasswordHash($sSalt, $sPassword);


				$aUserData['account_no']	= $iAccountNo;
				$aUserData['username'] 		= NULL;

				$aUserData['salt']			= $sSalt;
				$aUserData['hash']			= $sHash;

				$aUserData['type']			= safeText('user_type');

				$aUserData['online_status']	= $this->mcontents['aOnlineStatusesFlipped']['offline'];
				$aUserData['salutation']	= safeText('salutation');

				$aUserData['first_name']	= safeText('first_name');
				$aUserData['middle_name']	= safeText('middle_name');
				$aUserData['last_name']		= safeText('last_name');

				$aUserData['email_id']		= safeText('email_id');
    			$aUserData['status']		= $this->mcontents['aUserStatusesFlipped']['active'];

				$aUserData['created_on']	= date('Y-m-d H:i:s');

				// profile data
				$aProfileData['user_account_no'] 	= $iAccountNo;
				$aProfileData['gender'] 			= safeText('gender');


				if(!$error) {

					//start transaction
					$this->db->trans_start();

					$this->db->set ($aUserData);
			       	$this->db->insert('users');

					// fetch the inserted user
					$oUser = $this->user_model->getUserBy('account_no', $iAccountNo);
//p($oUser);exit;
					if( $oUser ) {

						// create profile for user.
						$this->account_model->createUserProfile($oUser, $aProfileData);


						// create address and contact number
						$bGetAddressOfUser = FALSE;
						if($bGetAddressOfUser) {

							$bByPassAddressValidation = TRUE;
							$this->load->model('address_model');
							$iAddressUid = $this->address_model->create_address_and_contact_numbers($bByPassAddressValidation);

							// create mapping between user and address
							$aData = array(
												'one' => $oUser->account_no,
												'many' => array($iAddressUid),
											);
							one_to_many_mapping('create', 'map_user_address', $aData);
						}

						//End transaction
						$this->db->trans_complete();

						sf('success_message', 'Successfully created new User.');

						redirect('user/listing');

					} else {

						$this->merror['error'][] = 'There was some issue in creating the user. Please try again.';
					}

				}
			}
		}


		// we need front end validation for this page.
		requireFrontEndValidation();
		$this->mcontents['load_js'][] 	= "validation/register.js";

		$this->mcontents['aGendersFlipped'] 	= array_flip($this->mcontents['aGenders']);


		// Do not give option to choose admininstrator as user type
		unset($this->mcontents['aUserTypesTitle'][$this->mcontents['aUserTypesFlipped']['admin']]);


		// get the salutations for user.
		$aAvoid = array('miss', 'master'); //we are avoiding certain salutations. (this action is specific to this website)
		$this->mcontents['aUserSalutationTitles'] = $this->user_model->getUserSalutations($aAvoid, 'id-title');

		loadAdminTemplate('user/create', $this->mcontents);
	}



	/**
	 *
	 * Signup feature used by a user to create an account with the website
	 * @return [type] [description]
	 */
	function register() {

		if($this->authentication->is_user_logged_in ()){
			redirect('home');
		}
		$this->mcontents['page_heading'] 	= 'Register';
		$this->mcontents['page_title'] 		= 'Register';

		$error	= FALSE;

		if (!empty($_POST) && isset($_POST)) {

			$this->_validate_registration();

			if (TRUE == $this->form_validation->run()) {

				$aUserData = $aProfileData = array();

				// generate uniquie account number
				$aUserData['username'] 		= $aConfig = array(
												'table' => 'users',
												'field' => 'account_no',
											);
				$iAccountNo = $this->common_model->generateUniqueNumber($aConfig);

				$aUserData['first_name']	= safeText('first_name');
				$aUserData['last_name']		= safeText('last_name');
				$aUserData['account_no'] 	= $iAccountNo;
				$aUserData['username'] 		= $aUserData['account_no'];
    			$aUserData['password']		= $this->authentication->encryptPassword( $aUserData['username'] );
    			$aUserData['status']		= $this->aUserStatus['active'];
				$aUserData['email_id']		= safeText('email_id');
				$aUserData['type']			= safeText('user_type');
				$aUserData['joined_on']		= date('Y-m-d H:i:s');




				if(!$error) {


					//start transaction
					$this->db->trans_start();

					$this->db->set ($aUserData);
			       	$this->db->insert ('users');

					if( $iUserId = $this->db->insert_id() ) {

						$oUser = $this->user_model->getUserBy('id', $iUserId);


						$this->load->model('account_model');

						// create profile for user.
						$this->account_model->createUserProfile($iAccountNo, $aProfileData);

						// create address and contact number
						$bGetAddressOfUser = FALSE;
						if($bGetAddressOfUser) {
							$bByPassAddressValidation = TRUE;
							$this->load->model('address_model');
							$iAddressUid = $this->address_model->create_address_and_contact_numbers($bByPassAddressValidation);

							// create mapping between user and address
							$aData = array(
												'one' => $oUser->account_no,
												'many' => array($iAddressUid),
												'extra_field_value_pairs' => array('is_main' => 1)
											);
							one_to_many_mapping('create', 'user_address', $aData);
						}


						//End transaction
						$this->db->trans_complete();

						sf('success_message', 'Your application has been received. We will get in touch with you shortly.');

						redirect('user/register');

					} else {
						$this->merror['error']         = "There was some issue with registration. Please try back later.";
					}
				}
			}
		}


		// we need front end validation for this page.
		requireFrontEndValidation();
		$this->mcontents['load_js'][] 	= "validation/register.js";



		$this->mcontents['aGenders'] 	= array_flip($this->aGenders);

		loadTemplate('user/register', $this->mcontents);
	}



	/**
	 *
	 * This URL is called when the Activate the a user account when
	 * the user clicks on the confirmation link in the email
	 *
	 */
	function account_activation($account_act_code='') {

		if( $this->authentication->is_user_logged_in() ) {

			redirect('home');
		}

		$this->mcontents['title']			= 'Account Activation';
		$this->mcontents['page_heading']	= 'Account Activation';

		if( !$account_act_code ) {

			redirect('home');
		}

		$this->load->model('token_model');
		$aResult = $this->token_model->isValidToken($account_act_code, 'account_activation');

		$aTokenStatuses = $this->data_model->getDataItem('token_purposes');
		$aTokenStatusesFlipped = array_flip($aTokenStatuses);


		if( $aResult['status'] != $aTokenStatus['valid'] ) {

			//find the reason why this token is not valid
			if( $aResult['status'] == $aTokenStatusesFlipped['invalid'] ) {

				sf('error_message', 'Invalid Link. Please contact out support team');
			} elseif($aResult['status'] == $aTokenStatusesFlipped['expired']) {

				sf('error_message', 'This link has expired. Click <a class="highlight1" href="'.c('base_url').'user/resend_account_activation/'.$account_act_code.'">here</a> to get another confirmation email');
			}

			redirect('home');

		} else {

			//activate the account
			if(true === $this->account_model->activateAccount($aResult['oToken']->user_id)){

				//delete the token
				$this->token_model->deleteToken($aResult['oToken']->id);


				if(!$this->authentication->makeLogin($aResult['oToken']->user_id)){

					sf('error_message', 'You could not be logged in. Please contact out admin');
				} else {

					// Send welcome message
					$this->load->model('maintenance_model');
					$this->maintenance_model->getSingleSetting('db_welcome_msg');
					$aWelcomeEmail['receiver_name'] = s('FULL_NAME');
					$aWelcomeEmail['welcome_text'] 	= $this->maintenance_model->getSingleSetting('db_signup_welcome_msg');

					$aSettings = array(
						'to' 				=> array(s('EMAIL') => s('FULL_NAME')), // email_id => name pairs
						'from_email' 		=> c('accounts_email_id'),
						'from_name'			=> c('accounts_email_from'),
						'reply_to' 			=> array(c('accounts_email_id') => c('accounts_email_from')), // email_id => name pairs
						'email_contents' 	=> $aWelcomeEmail, // placeholder keywords to be replaced with this data
						'template_name' 	=> 'welcome', //name of template to be used
						//'preview'			=> true
					);

					$this->load->helper('custom_mail');
					sendMail_PHPMailer($aSettings);

					sf('success_message', 'Account has been activated. Welcome to '.$this->mcontents['c_website_title']);
				}

			} else {
				sf('error_message', 'Could not activate!!');
			}


			redirect('home');
		}
	}



	/**
	 *
	 * resend account activation url to email and redirect to home page
	 *
	 * WHy not write a common function to resend validation urls for different purposes??
	 *
	 */
	function resend_account_activation($sToken) {

		$this->load->model('token_model');

		//see if the token is an expired one
		$aResult = $this->token_model->isValidToken($sToken, 'account_activation');
		$aTokenStatus = c('token_status');

		if($aResult['status'] == $aTokenStatus['expired']){

			$oToken = $aResult['oToken'];
			$oUser = $this->user_model->getUserBy('id', $oToken->user_id);

			//confirmation email to user
			$account_activation_code = $this->token_model->generateToken('account_activation', $oUser->id);
			$arr_email['name']				= $oUser->first_name . ' ' . $oUser->last_name;
			$arr_email['activation_url']	= site_url('user/account_activation/'.$account_activation_code);
			$arr_email['help_url']			= site_url('help');


			$this->load->helper('custom_mail');
			if(sendMail($oUser->email_id, $arr_email, 'registration_activation_link')){
				$this->session->set_flashdata ('success_message', 'Please Check your email now.');
			}else{
				$this->session->set_flashdata ('info_message', 'Confirmation mail is not sent');
			}
		}

		redirect('home');
	}


	function _validate_create_user() {

		$this->form_validation->set_rules ('first_name','First Name', 'trim|required');
		$this->form_validation->set_rules ('gender','Gender', 'trim|required');
		$this->form_validation->set_rules ('user_type','User Type', 'trim|required');

	}



	function _validate_registration(){

		$this->form_validation->set_rules ('first_name','First Name', 'trim|required');
		$this->form_validation->set_rules ('gender','Gender', 'trim|required');
	}


	function _validate_signup(){

		$this->form_validation->set_rules ('first_name','First Name', 'trim|required');
		$this->form_validation->set_rules ('last_name','Last Name', 'trim|required');
	    $this->form_validation->set_rules ('email_id','Email', 'trim|required|valid_email');
	    $this->form_validation->set_rules ('username','Username', c('username_validation_rules'));
		$this->form_validation->set_rules ('password','Password', c('password_validation_rules'));
		$this->form_validation->set_rules ('password_again','Repeat Password', c('password_again_validation_rules'));

	}


	/**
	 * Home page of user
	 *
	 */
	function home(){

		redirect('home');
	}


	/**
	 * Check availability of username and password
	 *
	 * accessed via ajax
	 *
	 * @param unknown_type $sType
	 */
	function checkavailability($sType='', $sValue='') {


		$aJasonData = array(
						'status'=>0,
						'output'=>'',
						'type' => $sType
						);
		$sOutput = true;


		if( ($sType == 'username' || $sType == 'email_id') ) {

			//if (!empty($_POST) && isset($_POST)) {
				$sLabel = '';
				//$sValue = '';
				if($sType == 'username'){

					$sLabel = 'Username';
					$sValue = safeText('username', false, 'get');
				} else {
					$sLabel = 'Email Id';
					$sValue = urldecode( safeText('email_id', false, 'get') );
				}

				// CHECK FOR VALID USERNAME HERE!!!??


				$this->db->where($sType, $sValue);
				$query = $this->db->get('users');
				if($query->row()){

					$sOutput = 'This '.$sLabel.' has been taken';

				} else {

					$sOutput = true;
				}
			//}
			//$sOutput = $this->db->last_query();
		}

		$this->load->view('output', array('output'=>json_encode($sOutput)));

	}



	/**
	 * manage user from admin section
	 *
	 */
	function listing($iStatus=0, $iGender=0, $iUserRole=0, $iOffset=0) {

		$this->authentication->is_admin_logged_in (true);

		isAdminSection();

		$this->mcontents['aUserStatusesTitles'] = $this->data_model->getDataItem('user_statuses', 'id-title');

		$this->mcontents['uri_string'] = $this->uri->uri_string();
		$this->mcontents['load_js']['data']['uri_string'] = $this->mcontents['uri_string'];

		ss('BACKBUTTON_URI', $this->mcontents['uri_string']);
		ss('redirect_to', $this->mcontents['uri_string']); // used only related to the profile section

		$this->mcontents['page_title'] 		= 'Users';
		$this->mcontents['page_heading']	= 'Users';

		$this->load->helper('date');

		$aWhere = array();

		if($iStatus) {
			$aWhere['U.status'] = $iStatus;
		}
		if($iGender) {
			$aWhere['U.gender'] = $iGender;
		}

		if($iUserRole ) {
			$aWhere['URM.role'] = $iUserRole;
		}

		//exclude the admin
		$aWhere['U.type <>'] = $this->mcontents['aUserTypesFlipped']['admin'];


		$this->mcontents['iTotal'] = count($this->profile_model->getUsersAndProfiles(0, 0, $aWhere));

		$this->mcontents['iPerPage'] = c('users_per_page');
		$this->mcontents['aData'] = $this->profile_model->getUsersAndProfiles($this->mcontents['iPerPage'], $iOffset, $aWhere);

		/* Pagination */
		$this->load->library('pagination');
		$this->aPaginationConfiguration = array();
		$this->aPaginationConfiguration['base_url'] 	= c('base_url').'user/listing/'.$iStatus.'/'.$iGender.'/'.$iUserRole;
		$this->aPaginationConfiguration['total_rows'] 	= $this->mcontents['iTotal'];
		$this->aPaginationConfiguration['per_page'] 	= $this->mcontents['iPerPage'];
		$this->aPaginationConfiguration['uri_segment'] 	= 6;
		$this->pagination->customizePagination();
		$this->mcontents['iOffset'] = $iOffset;
		$this->pagination->initialize($this->aPaginationConfiguration);
		$this->mcontents['sPagination'] = $this->pagination->create_links();
		/* Pagination - End*/


		$this->mcontents['load_js'][] 	= 'admin/user_listing.js';

		$this->mcontents['aMonths'] 			= numbersTill(0, 1, 12);
		$this->mcontents['aYears'] 				= numbersTill(0, 2011, 2015);
		$this->mcontents['iStatus'] 			= $iStatus;
		$this->mcontents['iUserRole'] 			= $iUserRole;
		$this->mcontents['iGender'] 			= $iGender;


//p($this->mcontents['aData']);

		// get the profile pictures
		$this->load->model('profile_picture_model');
		foreach ($this->mcontents['aData'] AS & $oUser) {

			$oUser->oProfilePicture
			= $this->profile_picture_model->getProfilePictureDetails($oUser->account_no);
			//p($oUser);exit;
		}



		$this->_requireUserRolesDropdown();

		loadAdminTemplate('user/listing', $this->mcontents);

	}



	/**
	 *
	 * make user roles array,  to use in a drop down
	 */
	function _requireUserRolesDropdown() {

		//p($this->mcontents['aAllRoles']);

		$this->mcontents['aAllUserRoles'][0] = 'All';
		foreach( $this->mcontents['aAllRoles'] AS $sName => $aItem ) {

			$this->mcontents['aAllUserRoles'][ $aItem['id'] ] = $aItem['title'];
		}
	}




	/**
	 * Close the account of a user.
	 *
	 * accessed via AJAX. ONLY by the admin user
	 */
	function take_action($sPurpose='', $iUserId=0, $sClass='') {

		$sCurrentUrl = urldecode( safeText('current_url', false, 'get') );
		$sCurrentUrl = $sCurrentUrl ? $sCurrentUrl : 'user/listing';

		if( !in_array( $sPurpose, array('close_account', 'logout') ) ) {
			redirect($sCurrentUrl);
		}

		initializeJsonArray();


		if( isAdminLoggedIn() ){

			if((s('USERID') != $iUserId)) {

				$oUser = $this->user_model->getUserBy('id', $iUserId);

				switch($sPurpose) {
					case 'close_account':

						$this->account_model->closeAccount($oUser->id);

						$this->aJsonOutput['output']['success'] = 'The account has been closed';
						break;
					case 'logout':

						$this->authentication->logout_from_db($oUser->id);

						$this->aJsonOutput['output']['success'] = 'The user has been logged out';
						break;
				}

			} else {
				sf('Cannot close admin account!');
				redirect($sCurrentUrl);
			}

		} else {

			setPostLoginRedirect($sCurrentUrl);
			$this->aJsonOutput['output']['error_type'] = $this->aErrorTypes['not_logged_in'];
			$this->aJsonOutput['output']['error'] = 'Not logged In';

		}
		$this->aJsonOutput['output']['c'] = $sClass;
		outputJson();
	}



	/**
	 *
	 * edit user details like email, status roles etc. by the admin user
	 */
	function edit($iAccountNo=0) {


		$this->authentication->is_admin_logged_in(true);

		if( !$this->mcontents['oUser'] = $this->user_model->getUserBy('account_no', $iAccountNo) ) {

			sf('error_message', 'Invalid user');
			redirect('user/listing');

		}

		isAdminSection();

		$this->mcontents['iEditedAccountNo'] = $iAccountNo;
		$this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Edit User';
		$this->mcontents['aExistingRoles'] = getUserRoles( $this->mcontents['oUser']->account_no );

		if ( isset($_POST) && !empty($_POST)) {


			$this->form_validation->set_rules('status', 'Status', 'trim|required');

			if ($this->form_validation->run() == TRUE) {

				$aData = array(
							'email_id' 	=> safeText('email_id'),
							'status' 	=> safeText('status'),
						);

				$this->db->where('account_no', $this->mcontents['iEditedAccountNo']);
				$this->db->update('users', $aData);

				//update roles.
				$aRoles 			= array_trim( safeText('user_roles') );

				$aDeletedRoles 	= array_diff($this->mcontents['aExistingRoles'], $aRoles);
				$aNewRoles 		= array_diff($aRoles, $this->mcontents['aExistingRoles']);


				$this->_createRoles($aNewRoles, $this->mcontents['oUser']->account_no);
				$this->_deleteRoles($aDeletedRoles, $this->mcontents['oUser']->account_no);

				sf('success_message', 'The user data has been updated');
				redirect('user/edit/'.$this->mcontents['iEditedAccountNo']);

			}
		}

		$this->mcontents['aUserRolesTitles'] 	= $this->config->item('user_roles_title');
		$this->mcontents['iTotalNumRoles'] 		= count( $this->mcontents['aUserRolesTitles'] );
		$this->mcontents['aUserStatusesTitles'] = $this->data_model->getDataItem('user_statuses', 'id-title');

		loadAdminTemplate('user/edit');
	}




	function _createRoles( $aRoles=array(), $iAccountNo ) {

		if( $aRoles ) {

		}
		foreach( $aRoles AS $iRole ) {

			$this->db->set('role', $iRole);
			$this->db->set('account_no', $iAccountNo);
			$this->db->insert('user_role_map');

		}
	}


	function _deleteRoles( $aRoles=array(), $iAccountNo ) {

		if( $aRoles ) {

		}
		foreach( $aRoles AS $iRole ) {

			$this->db->where('role', $iRole);
			$this->db->where('account_no', $iAccountNo);
			$this->db->delete('user_role_map');
		}
	}

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */
