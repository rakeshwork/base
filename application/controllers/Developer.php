<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developer extends CI_Controller {


	function __construct() {

		parent::__construct();


		$this->load->model('common_model');

		if(FALSE) {
			exit(0);
		}
	}

	function load() {

		$this->load->helper('asset_load');

		$this->mcontents['load_js'][] = 'js/validation/login.js';
		$this->mcontents['load_js'][] = 'js/datepicker/profile_edit.js';
		$this->mcontents['load_css'][] = 'common/css/captcha.css';
		$this->mcontents['load_css'][] = 'common/css/malayalam.css';

		$this->load->view('developer/index');
	}

//
// 	function purifier() {
//
// 		$sSafeHTML = '';
// 		$sUnsafeHTML = '
// 		<html>
// 		Latest comment:
// 		<script>...</script>
// 		</html>
// 		';
//
// 		$sSafeHTML = safeHTML($sUnsafeHTML, false, '', true);
//
// p($sUnsafeHTML);
// p($sSafeHTML);
// exit;
// 		p(htmlentities($sUnsafeHTML));
// 		p(htmlentities($sSafeHTML));
// 	}

	//
	// function token() {
	//
	// 	$this->load->model('token_model');
	// 	$sPurpose = 'password_recovery';
	// 	$sUniqueIdentification = 57874435;
	//
	// 	$oToken = $this->token_model->generateToken($sPurpose, 57874435, 20, '', false, true);
	//
	// 	p($oToken);
	// 	$bIsValid = $this->token_model->isValidToken($oToken->token, $sPurpose, $sUniqueIdentification);
	// 	p('IS VALID ? ' . ($bIsValid ? 'true' : 'false') );
	//
	// }

	// function profile_picture_test() {
	//
	// 	$iAccountNo = 57874435;
	//
	// 	$this->load->model('profile_picture_model');
	// 	$this->profile_picture_model->createDefaultProfilePicture($iAccountNo);
	// }

// function hash_testing() {
// 	$sPassword = 'admin123';
// 	$sSalt 		= $this->authentication->getSalt();
//
//
// 	$sSalt = 'LVoeyHrnIqsUXP9lRTpACmvdKzux5DBJ';
//
// 	p('SALT : ' . $sSalt);
//
// 	$sHash = $this->account_model->getPasswordHash($sSalt, $sPassword);
// 	p('HASH : ' . $sHash);
// 	$sHash = $this->account_model->getPasswordHash($sSalt, $sPassword);
// 	p('HASH : ' . $sHash);
//
//
// }



	// function create_profile() {
	//
	// 	$iAccountNo = 14448987;
	//
	// 	$oUser = $this->user_model->getUserBy('account_no', $iAccountNo);
	// 	$aProfileData = array('gender' => 1);
	//
	// 	// p($oUser);
	// 	// exit;
	//
	// 	$this->load->model('account_model');
	// 	$this->account_model->createUserProfile($oUser, $aProfileData);
	// }


	function index() {

		$this->db->order_by('created_on', 'desc');
		$this->mcontents['aTemporarySurveys'] = $this->db->get('temporary_survey')->result();

		$this->mcontents['aLinks'] = array(
			array(
				'uri' => 'preview_data/',
				'title' => 'Temporary Survey -> view raw data',
			),
		);
		loadTemplate('developer/index');
	}

	function generate_family_template() {

		$this->load->model('display_model');

		$this->display_model->generateQuestionTemplate_FamilyDetails();
	}



	function generate_result_template() {

		$this->load->model('display_model');

		$this->display_model->generateSurveyResultTemplate();
	}



	/**
	 *
	 * given the temporary id of a survey, we can do the last step.
	 * ie, creation of the survey.
	 * @return [type] [description]
	 */
	function complete_survey($iTemporarySurveyNumber=0) {

		if($iTemporarySurveyNumber) {


			$this->load->model('survey_model');
			list($iSurveyId, $aErrorMessages) = $this->survey_model->createSurvey($iTemporarySurveyNumber);

			if($aErrorMessages) {

				p($aErrorMessages);

			} else {
				echo "success";
			}

		}

	}


	function generate_config_array() {

		$this->load->model('QuestionTransfer_model');
		$this->QuestionTransfer_model->generateConfigArray();

		$this->load->model('question_model');
		$this->question_model->generateConfig_QuestionsInOrder();

		// regenerate the question groups config array as well.
		$this->QuestionTransfer_model->generateQuestionGroupConfigArray();

	}



	function preview_data($iTemporarySurveyId) {

		$this->db->where('id', $iTemporarySurveyId);
		$this->mcontents['oRow'] = $this->db->get('temporary_survey')->row();

		loadTemplate('survey/preview_data');
		//p( unserialize($oRow->general_data) );
	}



	function alter_raw_data() {
		$iTemporarySurveyId = 7;
		exit('SAFE GUARD ........ SAFE GUARD!!'); // safe guard . to prevent accidental updates

		$this->db->where('id', $iTemporarySurveyId);
		$this->mcontents['oRow'] = $this->db->get('temporary_survey')->row();

		$unserialized_data = unserialize($this->mcontents['oRow']->raw_data);


		$unserialized_data['surveyee_users']['is_office_bearer_ayalkoottam'] = 1;



		$this->db->where('id', $iTemporarySurveyId);
		$this->db->set('raw_data', serialize($unserialized_data));
		$this->db->update('temporary_survey');

	}







	function show_log() {

		$sFilePath = APPPATH . '/logs/';

		$sFileName = 'log-' . date('Y-m-d') . '.php';
		$sText = file_get_contents($sFilePath . $sFileName);
		$sText = str_replace("\n","<br/>",$sText);
		echo $sText;
	}


	function purge_log(  ) {

		$sFileName = 'log-' . date('Y-m-d') . '.php';
		purge_log($sFileName);
		redirect('developer/show_log');
	}



		function sync_demo_data() {

			$aTemporarySurveyIds = array(1, 4, 5, 6, 7);

			$this->load->model('survey_model');

			$bFailTransaction = FALSE; // whether to fail transaction or not. Used for testing.

			foreach($aTemporarySurveyIds AS $iTemporarySurveyId) {
				$this->survey_model->createSurvey($iTemporarySurveyId, $bFailTransaction);
			}

		}

	function truncate_all_data_________use_only_when_neeeded() {

		//exit('Cannot execute now');

		$aTruncatableTables = array(

			'family_agriculture_location_map',
			'family_appliance_map',
			'family_domestic_fuel_type_map',
			'family_house_map',
			'family_livestock_map',
			'family_loan_purpose_map',
			'family_loan_sources_map',
			'family_pet_map',
			'family_residence_history_map',
			'family_vehicle_type_map',

			//'temporary_survey',

			'house_biodegradable_waste_management_solution_map',
			'house_floor_type_map',
			'house_house_type_map',
			'house_nonbiodegradable_waste_management_solution_map',
			'house_public_utility_proximity',
			'house_road_map',
			'house_tax',
			'house_water_source_map',


			'land_cash_crop_map',
			'land_fruit_tree_map',
			'land_house_map',
			'leased_lands',


			'surveyee_user_bank_account_type_map',
			'surveyee_user_family_map',
			'surveyee_user_insurance_type_map',
			'surveyee_user_investment_type_map',
			'surveyee_user_pension_type_map',
			'surveyee_user_reservation_map',
			'surveys',

			'ward_sabha_participation'
		);

		$aNonTruncatableTables = array(
			'families',
			'houses',
			'lands',
			'surveyee_users',
		);


		foreach($aTruncatableTables AS $sTableName) {
			$this->db->truncate($sTableName);
		}

		foreach($aNonTruncatableTables AS $sTableName) {

			$this->db->query('DELETE FROM ' . $sTableName);
			$this->db->query('ALTER TABLE ' . $sTableName . ' AUTO_INCREMENT=1');
		}

	}

}

/* End of file developer.php */
/* Location: ./application/controllers/developer.php */
