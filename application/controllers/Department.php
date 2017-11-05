<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Department extends CI_Controller {

	function __construct() {

		parent::__construct();

		$this->load->model('department_model');

		list($this->mcontents['aDepartmentStatuses'], $this->mcontents['aDepartmentStatusTitles']) =
							$this->data_model->getDataItem('department_statuses', array('id-name','id-title'));
		$this->mcontents['aDepartmentStatusesFlipped'] = array_flip($this->mcontents['aDepartmentStatuses']);
	}



	function listing() {


		$this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Departments';

		$this->authentication->is_admin_logged_in(true, 'user/login');

		$this->mcontents['aOrderBy'] = array('DEPT.title' => 'ASC');
		$this->mcontents['iLimit'] = 0;
		$this->mcontents['iOffset'] = 0;
		$this->mcontents['aWhere'] = array();

		$this->mcontents['aDepartments'] = $this->department_model->getDepartments(
																		$this->mcontents['iLimit'],
																		$this->mcontents['iOffset'],
																		$this->mcontents['aWhere'],
																		$this->mcontents['aOrderBy']
																	);

		loadAdminTemplate('department/listing');
	}


	/**
	 *
	 * Edit sitepage details at admin side
	 *
	 */
	function edit($iDepartmentId=0) {

// log_message('error', 'DEPT ID : ' . $iDepartmentId);

		$this->authentication->is_admin_logged_in(true, 'user/login');
		isAdminSection();

		$this->post_contents				= array();
		$this->mcontents['title']			= 'Edit Department';
		$this->mcontents['page_heading']	= 'Edit Department';


		if( ! $this->mcontents ['oDepartment'] = $this->department_model->getDepartmentBy('id', $iDepartmentId)) {

// p($this->db->last_query());
// exit;

			sf('error_message', 'Department not found');
			redirect('department/listing');
		}


			if( isset($_POST) && !empty($_POST) ) {

				$this->form_validation->set_rules('title', 'Title','required');
				$this->form_validation->set_rules('status', 'Status','required');

				// verify status
				$bIsStatusVerified = false;
				if( array_key_exists(safeText('status'), $this->mcontents['aDepartmentStatuses']) ) {
					$bIsStatusVerified = true;
				}

				// do form validation
				$bFormValidationPassed = false;
				if($this->form_validation->run() === TRUE) {
					$bFormValidationPassed = true;
				}


				if( $bFormValidationPassed && $bIsStatusVerified ) {

					$aDetails = array(
						'title' 		=> safeText('title'),
						'status' 		=> safeText('status'),
						'website_url' 	=> safeText('website_url'),
						'updated_on' 	=> date('Y-m-d H:i:s')
					);

					// p($aDetails);
					// exit;

					$this->db->where('id', $this->mcontents ['oDepartment']->id);
					$this->db->update('departments', $aDetails);

p($this->db->last_query());
exit;

					$this->session->set_flashdata ('success_message', 'Successfully updated deaprtment');

					redirect('department/edit/'.$this->mcontents ['oDepartment']->id);
				}
			}



		requireTextEditor();

		loadAdminTemplate('department/edit');
	}

}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
