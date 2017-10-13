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
	 * A logged-in user wants to change password
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
				// p($oCurrentUser);
				// p(safeText('current_password'));
				// p($sHash);
				// exit;
				$this->db->where('hash', $sHash);
				$this->db->where('account_no', s('ACCOUNT_NO') );

				if ( $oUser = $this->db->get('users')->row() ) {

					$bMismatch = false;

					if( $oUser->account_no == s('ACCOUNT_NO') ) {

						//proceed with changing password
						$nNewSalt = $this->authentication->getSalt();
						$sHash = $this->account_model->getPasswordHash($nNewSalt, safeText('new_password'));


						$this->db->set('salt', $nNewSalt);
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

		$this->mcontents['load_js'][] 	= 'jquery/jquery.validate.min.js';
		$this->mcontents['load_js'][] 	= 'validation/change_password.js';


		loadTemplate('account/change_password');
	}



	/**
	 * User has clicked the confirmation link, and has come to recover username/password
	 *
	 */
	function recovery_process($sTokenPurpose='', $sToken='', $sTokenUniqueIdentification = 0) {

		$this->load->model('common_model');

		$aResult 		= $this->common_model->isValidToken_new($sToken, $sTokenPurpose, $sTokenUniqueIdentification);
		$aTokenStatus 	= c('token_status');

		if( $aResult['status'] == $aTokenStatus['valid'] ) {

			if($sTokenPurpose == 'password_recovery') {

				if( isset($_POST) && !empty($_POST) ) {

					$this->form_validation->set_rules ('password','New password', c('password_validation_rules'));
					$this->form_validation->set_rules ('password_again','Confirm new password', c('password_again_validation_rules'));
					$this->form_validation->set_rules('captcha', 'Captcha', 'required');

					$this->load->helper('captcha');

					if( isValidCaptcha() ) {

						if($this->form_validation->run() !== false) {

							$this->db->where('id', $aResult['oToken']->unique_identification);
							$this->db->set( 'password', $this->authentication->encryptPassword(safeText('password')) );
							$this->db->update('users');

							$this->common_model->deleteToken( $aResult['oToken']->id );

							//login the user and redirect to account settings page
							$this->authentication->makeLogin($aResult['oToken']->unique_identification);

							sf('success_message', 'Your password has been reset successfully');
							redirect('account/overview');
						}
					} else {
						$this->merror[] = 'Enter once again, the characters as seen in the image';
					}

				}

				$this->load->helper('captcha');
				$this->mcontents['sCaptcha'] 		            = getCaptchaView();
				$this->mcontents['sToken']			            = $sToken;
                $this->mcontents['sTokenUniqueIdentification']	= $sTokenUniqueIdentification;
				$this->mcontents['page_heading'] 	            = 'Reset Password';
				$this->mcontents['page_title'] 		            = 'Reset Password';
				$this->mcontents['load_js'][] 		            = 'jquery/jquery.validate.min.js';
				$this->mcontents['load_js'][] 		            = 'validation/new_password.js';


				loadTemplate('account/password_recovery_process', $this->mcontents);

			} elseif ($sTokenPurpose == 'username_recovery') {

				$this->db->select('username');
				$this->db->where('id', $aResult['oToken']->unique_identification);
				$sUsername = $this->db->get('users')->row()->username;

				//this link will expire.. need not delete it.
				//$this->common_model->deleteToken($aResult['oToken']->id);

				$this->mcontents['page_heading'] 	= 'Username Recovery';
				$this->mcontents['sText'] 			= 'Your Username is : '.$sUsername;

				loadTemplate('plain_output', $this->mcontents);
			}

		} else {

			$this->mcontents['page_heading'] 	= 'Password Recovery';

			if ( $aResult['status'] == $aTokenStatus['expired'] ) {

				$this->mcontents['sText'] = formatMessage('This Link has expired. Click <a href='.c('base_url').'>here</a> to get a new link.');
			} else {

				$this->mcontents['sText'] = formatMessage('Invalid Link');
			}

			loadTemplate('plain_output');
		}
	}



	public function _validate_forgot_password(){

		$this->form_validation->set_rules('username', 'username', 'required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'required');
	}


	/**
	 *
	 * User forgot password
	 *
	 */
	public function forgot($sType){

		$aData = array('output' => array('success'=>'', 'error'=>'', 'page'=>''));
		$sError = '';

		if( $sType == 'username' || $sType == 'password' ) {

			if( isset($_POST) && !empty($_POST)) {

				$sFnName = '_validate_forgot_'.$sType;
				$this->$sFnName();

				if($this->form_validation->run() !== false) {

					$this->load->helper('captcha');

					if( isValidCaptcha() ) {

						$sField = $value= '';
						if( $sType == 'username' ) {

							$sField = 'email_id';
							$sValue= safeText('email');

						} elseif ( $sType == 'password' ) {

							$sField = 'username';
							$sValue= safeText('username');
						}

						$this->load->model('user_model');
						if( $oUser = $this->user_model->getUserBy($sField, $sValue) ) {

							$this->load->model('common_model');

							//send email recovery link to the user
							$sTokenPurpose = $sType.'_recovery';
							$sToken = $this->common_model->generateToken_new($sTokenPurpose, $oUser->id);

							$aEmail = array(
								$sType.'_recovery_link' => c('base_url').'account/recovery_process/' . $sTokenPurpose . '/' . $sToken . "/" . $oUser->id,
								'receiver_name' => $oUser->full_name,
							);

							$this->load->helper('custom_mail');

							$aSettings = array(
								'to' 				=> array($oUser->email_id => $oUser->full_name), // email_id => name pairs
								'from_email' 		=> c('accounts_email_id'),
								'email_contents' 	=> $aEmail, // placeholder keywords to be replaced with this data
								'template_name' 	=> $sType.'_regeneration_link', //name of template to be used
							);
							sendMail_PHPMailer($aSettings);

							$aData['output']['success'] = "You are required to confirm your identity by clicking a link we sent to your email id.<br/>
							Please note that this link will expire in the next " . (c($sTokenPurpose.'_token_life')/ 3600) . " hours";

						} else {

							if($sType == 'username'){

								$sError = 'Email you entered is not associated with any account.';
							}
							if($sType == 'password'){

								$sError = 'Username you entered does not exist.';
							} else {
								$sError = 'Invalid Type';
							}
						}

					} else {

						$sError = 'You entered the wrong catpcha';
					}

				} else {

					$sError = validation_errors();
				}
			}

			$this->load->helper('captcha');
			$this->mcontents['sType'] 			= $sType;
			$this->mcontents['sCaptcha'] 		= getCaptchaView();
			$this->mcontents['page_heading'] 	= ucfirst($sType).' Recovery';
			$aData['output']['success'] 		= $aData['output']['success'] ? formatMessage($aData['output']['success'], 'success') : '';
			$aData['output']['error'] 			= $sError ? formatMessage($sError, 'error') : '';
			$aData['output']['page'] 			= $this->load->view('account/forgot_'.$sType, $this->mcontents, true);
			$this->mcontents['load_js'][] 		= 'jquery/jquery.blockui.js';

			if ( $sType == 'username' ) {

				$this->mcontents['load_js'][] = 'validation/forgot_username.js';
			} elseif ( $sType == 'password' ) {

				$this->mcontents['load_js'][] = 'validation/forgot_password.js';
			}

		}

		$aData['output'] = json_encode($aData['output']);
		$this->load->view('output', $aData);

	}


	/**
	 *
	 * user clicks on forgot username/password link and comes here
	 *
	 * form is displayed for the recovery of password or username
	 *
	 */
	function recovery() {

		if( $this->authentication->is_user_logged_in() ) {

			redirect('account/overview');
		}

		$this->mcontents['page_heading'] 	= 'Recovery';
		$this->mcontents['page_title'] 		= 'Recovery';


		$this->mcontents['load_js'][] 	= 'jquery/jquery.blockui.js';
		$this->mcontents['load_js'][] 	= 'misc/forgot.js';

		$this->mcontents['load_js'][] 	= 'jquery/jquery.validate.min.js';

		//$this->mcontents['load_css'][] 	= 'captcha.css';
		//$this->mcontents['load_css'][] 	= 'account/recovery.css';
		//$this->mcontents['load_css'][] 	= 'forms/forgot.css';

		loadTemplate('account/recovery');

	}


	/**
	 *
	 * Display form for a user to select a username/password
	 * this is for users who login via facebook/twitter
	 *
	 */
	function set_username_password() {

		if( !$this->mcontents['oCurrUser'] || $this->account_model->hasUserNamePassword($this->mcontents['oCurrUser']->id)) {
			redirect('account/overview');
		}

		$this->mcontents['page_title'] 		= 'Set Username and Password';
		$this->mcontents['page_heading'] 	= 'Set Username and Password';

		if ( isset($_POST) && !empty($_POST)) {

			$this->form_validation->set_rules ('username','Username', c('username_validation_rules'));
			$this->form_validation->set_rules ('password','Password', c('password_validation_rules'));
			$this->form_validation->set_rules ('password_again','Repeat Password', c('password_again_validation_rules'));

			if ($this->form_validation->run() == TRUE) {

				$post_data = array(
								   'username' => safeText('username'),
								   'password' => $this->authentication->encryptPassword(safeText('password')),
								   );

				$this->db->where('id', $this->mcontents['oCurrUser']->id);
				$this->db->set($post_data);
				$this->db->update('users');


				sf('success_message', 'Your username and password has been set');
				redirect('account/overview');
			}
		}

		$this->mcontents['load_js'][] 	= 'jquery/jquery.validate.min.js';
		$this->mcontents['load_js'][] 	= 'validation/set_username_password.js';
		$this->mcontents['load_css'][] 	= 'form.css';
		$this->mcontents['load_css'][] 	= 'forms/signup.css';

		loadTemplate('account/set_username_password');
	}

}

/* End of file account.php */
/* Location: ./application/controllers/account.php */
