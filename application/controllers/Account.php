<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Account extends CI_Controller {

	public function __construct() {

		parent::__construct();

		$this->load->model('profile_model');
		$this->lang->load('account');
		$this->load->helper('account');

		$this->mcontents['sCurrentMainMenu']    = 'account';
	}


	/**
	 * display the settings page
	 *
	 */
	public function overview() {

		$this->authentication->is_user_logged_in(true, 'user/login');


		$this->mcontents['page_heading'] 	= 'Account Settings';
		$this->mcontents['page_title'] 		= 'Account Settings';

		$this->mcontents['oUser'] = $this->profile_model->getUserProfile('account_no', s('ACCOUNT_NO'));

		if( ! $this->mcontents['oUser'] ) {
			redirect('home');
		}


		loadTemplate('account/overview', $this->mcontents);
	}


	/**
	 *
	 * A logged-in user can change password using this page
	 *
	 */
	public function change_password() {

		$oCurrentUser = $this->authentication->is_user_logged_in(true, 'user/login', true, true);

		$this->mcontents['page_heading'] 	= 'Change Password';
		$this->mcontents['page_title'] 		= 'Change Password';

		if( isset($_POST) && ! empty($_POST) ) {

			$this->form_validation->set_rules('current_password', 'Current Password', 'required');
			$this->form_validation->set_rules('new_password', 'New Password', $this->config->item('password_validation_rules'));
			$this->form_validation->set_rules('password_again', 'Confirm New Password', $this->config->item('password_again_validation_rules'));

			if( $this->form_validation->run() !== false ) {

				$bMismatch = true;

				//salt the password
				$sHash = $this->account_model->getPasswordHash($oCurrentUser->salt, safeText('current_password'));

				$this->db->where('hash', $sHash);
				$this->db->where('account_no', s('ACCOUNT_NO') );

				if ( $oUser = $this->db->get('users')->row() ) {

					$bMismatch = false;

					if( $oUser->account_no == s('ACCOUNT_NO') ) {

						//proceed with changing password
						$sNewSalt = $this->authentication->getSalt();
						$sHash = $this->account_model->getPasswordHash($sNewSalt, safeText('new_password'));


						$this->db->set('salt', $sNewSalt);
						$this->db->set('hash', $sHash);
						$this->db->where('account_no', $oUser->account_no);
						$this->db->update('users');

						sf('success_message', 'Password has been changed');
						redirect('account/overview');
					}
				}

				if( $bMismatch ) {

					sf('error_message', 'Current password you entered was wrong');
					redirect('account/change_password');
				}

			}

		}

		// we need front end validation for this page.
		requireFrontEndValidation();
		$this->mcontents['load_js'][] 	= 'validation/change_password.js';


		loadTemplate('account/change_password');
	}



	/**
	 *
	 *
	 * User has clicked the confirmation link, and has come to recover password
	 *
	 */
	function reset_password($sToken='', $sTokenUniqueIdentification = 0) {

		$this->load->model('common_model');
		$this->load->model('token_model');

		$aResult 		= $this->token_model->isValidToken($sToken, 'password_recovery', $sTokenUniqueIdentification);


		$aTokenStatuses = $this->data_model->getDataItem('token_statuses');
		$aTokenStatusesFlipped = array_flip($aTokenStatuses);

		if( $aResult['status'] == $aTokenStatusesFlipped['valid'] ) {

			if( isset($_POST) && !empty($_POST) ) {

				$this->form_validation->set_rules ('password','New password', c('password_validation_rules'));
				$this->form_validation->set_rules ('password_again','Confirm new password', c('password_again_validation_rules'));


				if($this->form_validation->run() !== false) {

					// get new salt and hash
					$sNewSalt = $this->authentication->getSalt();
					$sHash = $this->account_model->getPasswordHash($sNewSalt, safeText('password'));

					$this->db->where('account_no', $aResult['oToken']->unique_identification);
					$this->db->set( 'salt', $sNewSalt );
					$this->db->set( 'hash', $sHash );
					$this->db->update('users');


					$this->token_model->deleteToken( $aResult['oToken']->id );

					sf('success_message', 'Your password has been reset successfully');
					redirect('user/login');
				}

			}


			$this->mcontents['sToken']			            = $sToken;
            $this->mcontents['sTokenUniqueIdentification']	= $sTokenUniqueIdentification;
			$this->mcontents['page_heading'] 	            = 'Reset Password';
			$this->mcontents['page_title'] 		            = 'Reset Password';

			// we need front end validation for this page.
			requireFrontEndValidation();
			$this->mcontents['load_js'][] 		            = 'validation/new_password.js';

			loadTemplate('account/reset_password', $this->mcontents);

		} else {


			if ( $aResult['status'] == $aTokenStatusesFlipped['expired'] ) {

				sf('error_message', 'This Link has expired. Click <a href='.c('base_url').'>here</a> to get a new link.');

			} else {

				sf('error_message', 'Invalid Link');

			}

			redirect($this->config->item('default_controller'));
		}
	}



	public function _validate_forgot_password() {

		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'required');
	}


	/**
	 *
	 * Password can be recovered using this page.
	 *
	 * @return [type] [description]
	 */
	function password_recovery() {

		$this->mcontents['page_heading'] = $this->mcontents['page_title'] 	= 'Password Recovery';



		if( isset($_POST) && ! empty($_POST) ) {


			$this->form_validation->set_rules('email_id', 'Email ID', 'required|valid_email');

			$bFormValidationPassed = false;
			if($this->form_validation->run() !== false) {
				$bFormValidationPassed = true;
				// p('here 0');exit;
			}

			$bExistingEmail = false;
			if( $this->account_model->isEmailExists(safeText('email_id')) ) {
				$bExistingEmail = true;
				// p('here 1');exit;
			}

			if( $bFormValidationPassed && $bExistingEmail ) {

				//get the user
				$oUser = $this->user_model->getUserBy('email_id', safeText('email_id'));

				// generate token for building recovery URL
				$this->load->model('token_model');
				$sTokenPurpose = 'password_recovery';
				$sToken = $this->token_model->generateToken($sTokenPurpose, $oUser->account_no);

				// prepare email contents
				$aEmailContents = array(
					'password_recovery_link' => c('base_url').'account/reset_password/' . $sToken . "/" . $oUser->account_no,
					'receiver_name' => $oUser->full_name,
				);

				p($aEmailContents);
				p('Email functionality pending');
				exit;


				// send email to the user
				$this->load->helper('custom_mail');
				$aSettings = array(
					'to' 				=> array($oUser->email_id => $oUser->full_name), // email_id => name pairs
					'from_email' 		=> $this->config->item('accounts_email_id'),
					'email_contents' 	=> $aEmailContents, // placeholder keywords to be replaced with this data
					'template_name' 	=> 'password_regeneration_link', //name of template to be used
				);

				sendMail_PHPMailer($aSettings);
			}

			sf('success_message',
				"If an account is found with the information you provided, will send the password recovery process to the registered email.<br/>" .
				"Please note that this link will expire in the next " . ($this->config->item('password_recovery_token_life')/ 3600) . " hours"
			);
			redirect('account/password_recovery');
		}

		loadTemplate('account/password_recovery');
	}



}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
