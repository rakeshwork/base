<?php
class Display_model extends CI_Model{

	function __construct(){




		parent::__construct();
	}

	/**
	 * This function is called to construct the <option> part for the HTML element "select".
	 *
	 * @param  [type] $aAnswerOption [description]
	 * @return [type]                [description]
	 */
	function constructSelectElement_forMultiOptionQuestion($aQuestion, $sAppendToName='') {
		$sHtml = '';

		$sHtml .= '<select name="'. $aQuestion['field_name'] .$sAppendToName.'" class="form-control">';

		// if sepcified, append the non selection option
		if($aQuestion['answer_non_selection_option']) {

			$sHtml .= '<option value="'. $aQuestion['answer_non_selection_option']['value'] .'">'.
									$aQuestion['answer_non_selection_option']['title']
									.'</option>';
		}

		// append the options
		foreach($aQuestion['answer_options'] AS $aAnswerOption) {
			$sHtml .= '<option value="'. $aAnswerOption['value'] .'">'. $aAnswerOption['title'] .'</option>';
		}


		$sHtml .= '</select>';

		return $sHtml;
	}


	/**
	 * This function is called to construct the <option> part for the HTML element "select".
	 *
	 * @param  [type] $aAnswerOption [description]
	 * @return [type]                [description]
	 */
	function constructSelectOptions_forMultiOptionQuestion($aQuestion) {

	  $sHtml = '';

	  // append the non selection option
	  foreach($aQuestion['answer_non_selection_option'] AS $aAnswerOption) {
	    $sHtml .= '<option value="'. $aAnswerOption['value'] .'">'. $aAnswerOption['title'] .'</option>';
	  }

	  // append the options
	  foreach($aQuestion['answer_options'] AS $aAnswerOption) {
	    $sHtml .= '<option value="'. $aAnswerOption['value'] .'">'. $aAnswerOption['title'] .'</option>';
	  }

	  return $sHtml;
	}



	function generateQuestionTemplate_FamilyDetails() {

		$aTemplateData = array();

		$aTemplateData['aTrueFalseVariants'] = array();
		foreach($this->db->get('ans_option_true_false_variances')->result() AS $oRow) {

			$aTemplateData['aTrueFalseVariants'][$oRow->id] = array(
				0 => $oRow->false_title,
				1 => $oRow->true_title
			);
		}

		$this->load->config('question_master_data_config_raw');
		$aTemplateData['aQuestionsMasterData_raw'] = $this->config->item('questions_master_data_raw_new');

		$this->load->config('field_name_quid_map_config');
		$aTemplateData['aFieldName_Quid_map'] = $this->config->item('field_name_quid_map');

		$sTemplate = $this->load->view('question/tpl_family_member_details', $aTemplateData, true);

		$sTemplateFile = $this->config->item('base_path') . 'application/views/question/tpl_family_member_details_new.php';
		$fh = fopen($sTemplateFile, 'w');
		if(!fwrite($fh, $sTemplate)) {
			log_message('error_message', 'could not write to file display_model->generateQuestionTemplate_FamilyDetails()'); ;
		}

		fclose($fh);
	}


	function generateSurveyResultTemplate() {

		$this->load->config('question_config');
		$aQuestionTypes = $this->config->item('question_types');

		//get the questions
		$this->load->model('question_model');
		$aQuestionsMasterData_raw = $this->question_model->getQuestionMasterData_raw();
		$aQuestionsMasterData_GroupedIntoCollections = $this->question_model->getQuestionMasterData();


		$aTables = array();

		$aConfig = array(
				'table' => 'question_groups',
				'id_field' => 'id',
				'title_field' => 'title',
			);
		$aGroupDetails = $this->common_model->getDropDownArray($aConfig);

		//group the questions.
		$aGroupedQuestions = array();
		foreach($aQuestionsMasterData_GroupedIntoCollections AS $iQuestionUid => $aQuestion)  {

			$iGroupId = $aQuestion['group_id'];


			if( ! isset($aGroupedQuestions[ $iGroupId ]) ) {
				$aGroupedQuestions[ $iGroupId ] = array();
			}


			//if the question is a collection, all its children are added to the same grouping
			if( $aQuestion['question_type'] == $aQuestionTypes['collection']) {

				foreach($aQuestion['questions'] AS $aChildQuestion) {
					$aGroupedQuestions[ $iGroupId ][] = $aChildQuestion;
				}

			} else {
					// questions which form a collection do not take answer directly.
					//add the question to the grouping
					$aGroupedQuestions[ $iGroupId ][] = $aQuestionsMasterData_raw[$iQuestionUid];
			}


		}


		// make the groups as tables
		foreach($aGroupedQuestions AS $iGroupId => $aQuestions) {

			$sTable = '
			<h4>'. $aGroupDetails[$iGroupId] .'</h4>
			<table class="table">';
			foreach($aQuestions AS $aQuestion) {

				$sTable .=
				'<tr>
					<td>'.$aQuestion['title'].'</td>
					<td>{'.$aQuestion['field_name'].'}</td>
				</tr>';
			}
			$sTable .= '</table>';

			$aTables['{group-no-' . $iGroupId.'}'] = $sTable;
		}


		// place the grups into the template
		$sTemplate = '';
		$sTemplate = '<div class="row">
			<div class="col-md-4">
				{group-no-1}
				{group-no-4}
				{group-no-7}
				{group-no-10}
				{group-no-13}
			</div>
			<div class="col-md-4">
				{group-no-2}
				{group-no-5}
				{group-no-8}
				{group-no-11}
			</div>
			<div class="col-md-4">
				{group-no-3}
				{group-no-6}
				{group-no-9}
				{group-no-12}
			</div>
		</div>';

		// create template file
		$sTemplate = str_replace(array_keys($aTables), array_values($aTables), $sTemplate);

		$sTemplateFile = $this->config->item('base_path') . 'application/views/survey_result/tpl_view.php';
		$fh = fopen($sTemplateFile, 'w');
		if(fwrite($fh, $sTemplate)) {
			echo 'success';
		} else {
			echo 'could not write to file';
		}

		fclose($fh);
	}


	function populateTemplate($aDataAsKeyValuePairs) {

		//$aDataAsKeyValuePairs = array();

		$sTemplate = $this->load->view('survey_result/tpl_view', array(), true);

		$aMultivalueAnswers = array();
		foreach($aDataAsKeyValuePairs AS $sFieldName => $data) {
			if( is_array($data) ) {
				$aMultivalueAnswers[$sFieldName] = $data;
				unset($aDataAsKeyValuePairs[$sFieldName]);
			}
		}

		$sPopulatedTemplate = str_replace(array_keys($aDataAsKeyValuePairs), array_values($aDataAsKeyValuePairs), $sTemplate);

		//loadTemplate('survey/view_result');
		return $sPopulatedTemplate;
	}
}
