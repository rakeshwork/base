<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_purpose extends CI_Controller {

	public function __construct() {

		parent::__construct();


		$this->load->model('contact_us_model');

        list(
			$this->mcontents['aPurposeStatus'],
			$this->mcontents['aPurposeStatusTitles']) =  $this->data_model->getDataItem(
																						'enquiry_purpose_statuses',
																						array('id-name', 'id-title')
																					);
		$this->mcontents['aPurposeStatusFlipped'] = array_flip($this->mcontents['aPurposeStatus']);
	}


	function index( $iStatus=0, $iOffset=0 ) {
		redirect('contact_purpose/listing');
	}


    /**
     *
     * Create a new purpose for contact us form
     *
     */
    function listing( $iStatus=0, $iOffset=0 ) {

		$this->authentication->is_admin_logged_in(true);

		isAdminSection();

        $aWhere = $aOrderBy = array();


        $iLimit = 10;
		$this->mcontents['iTotal'] = count( $this->contact_us_model->getPurposes(0, 0, $aWhere, $aOrderBy) );
		$this->mcontents['aData'] = $this->contact_us_model->getPurposes($iLimit, $iOffset, $aWhere, $aOrderBy);


		/* Pagination */
		$this->load->library('pagination');
		$this->aPaginationConfiguration = array();
		$this->aPaginationConfiguration['base_url'] 	= c('base_url').'contact_us/purpose_listing/'.$iStatus;
		$this->aPaginationConfiguration['total_rows'] 	= $this->mcontents['iTotal'];
		$this->aPaginationConfiguration['per_page'] 	= $iLimit;
		$this->aPaginationConfiguration['uri_segment'] 	= 4;
		$this->pagination->customizePagination();
		$this->mcontents['iOffset'] 					= $iOffset;
		$this->pagination->initialize($this->aPaginationConfiguration);
		$this->mcontents['sPagination'] 				= $this->pagination->create_links();
		/* Pagination - End*/


        $this->mcontents['load_js'][]   = 'validation/contact_us/purpose_listing.js';

		$this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Contact Purposes';

        loadAdminTemplate('contact_us/purpose/listing', $this->mcontents);
    }


    /**
     *
     * Create a new purpose for contact us form
     *
     */
    function add_purpose() {


		$this->authentication->is_admin_logged_in(true);

		isAdminSection();

        $this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Add contact us purpose';

		if( isset($_POST) && !empty($_POST) ) {

            //$this->form_validation->set_message('greater_than', 'This field is required');
			$this->form_validation->set_rules('title', 'Title', 'required|trim');
			$this->form_validation->set_rules('description', 'Description', 'trim');
			$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
			$this->form_validation->set_rules('reciever_name', 'Reciever Name', 'required|trim');
			//$this->form_validation->set_rules('email_template_id', 'Email Template', 'required|greater_than[0]');
			// $this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('success_message', 'Success Message', 'required|trim');

			if( $this->form_validation->run() !== false ) {


                if( empty($this->merror['error']) ) {

                    $aData = array(
								'name'                 => $this->common_model->getSeoName(safeText('title')),
                                'title'                 => safeText('title'),
                                // 'email_template_id'     => safeText('email_template_id'),
                                'description'           => safeText('description'),
                                'target_email'          => safeText('email'),
                                'reciever_name'         => safeText('reciever_name'),
                                'status'                => $this->mcontents['aPurposeStatusFlipped']['active'],
                                'success_message'       => safeText('success_message'),
                            );
                    $this->db->insert('enquiry_purposes', $aData);

                    sf('success_message', 'New enquiry purpose has been created.');
                    redirect('contact_purpose/add_purpose');
                }

            }
        }

        // get the email templates drop down
        $this->mcontents['aEmailTemplates'] = $this->_get_email_templates_dropdown();


		// we need front end validation for this page.
		requireFrontEndValidation();
        $this->mcontents['load_js'][]   = 'validation/contact_us/add_purpose.js';

        loadAdminTemplate('contact_us/purpose/add_purpose');
    }


    // get the email templates drop down
    function _get_email_templates_dropdown() {

        $this->load->model('common_model');
        $aConfig = array(
                        'table'         => 'email_templates',
                        'id_field'      => 'id',
                        'title_field'   => 'title',
                        'aWhere'        => array(
                            'id <> ' => 1,
                        ),
                        'aOrderBy'        => array(
                            'title' => 'ASC',
                        )
                    );
        return $this->common_model->getDropDownArray($aConfig);
    }


    /**
     *
     * Create a new purpose for contact us form
     *
     */
    function edit_purpose($iPurporseId) {


		$this->authentication->is_admin_logged_in(true);



        $iPurporseId = safeText($iPurporseId, false, '', true);
		if( !$this->mcontents['oPurpose'] = $this->contact_us_model->getPurposeBy('id', $iPurporseId) ) {

			sf('error_message', 'The contact us purpose does not exist!');
			redirect('contact_purpose/listing');
		}


		isAdminSection();


        $this->mcontents['page_heading'] = $this->mcontents['page_title'] = 'Edit contact us purpose';

		if( isset($_POST) && !empty($_POST) ) {

            if( $iPurporseId != 1 ) {
                $this->form_validation->set_rules('title', 'Title', 'required|trim');
            }


			$this->form_validation->set_rules('description', 'Description', 'trim');
			$this->form_validation->set_rules('target_email', 'Target Email', 'required|trim|valid_email');
            $this->form_validation->set_rules('reciever_name', 'Reciever Name', 'required|trim');
			$this->form_validation->set_rules('status', 'Status', 'required');
			$this->form_validation->set_rules('success_message', 'Success Message', 'required|trim');

			if( $this->form_validation->run() !== false ) {

                $aData = array(
							'title'       		=> safeText('title'),
                            'description'       => safeText('description'),
                            'target_email'      => safeText('target_email'),
                            'reciever_name'     => safeText('reciever_name'),
                            'status'            => safeText('status'),
                            'success_message'   => safeText('success_message'),
                        );


                $this->db->where('id', $this->mcontents['oPurpose']->id);
                $this->db->update('enquiry_purposes', $aData);

                sf('success_message', 'The enquiry purpose has been updated.');
                redirect('contact_purpose/listing');
            }
        }

        // get the email templates drop down
        $this->mcontents['aEmailTemplates'] = $this->_get_email_templates_dropdown();

		// we need front end validation for this page.
		requireFrontEndValidation();
        $this->mcontents['load_js'][]   = 'validation/contact_us/add_purpose.js';

        loadAdminTemplate('contact_us/purpose/edit_purpose');
    }



    /**
     *
     * Delete a purpose
     * cannot the purpose with id 1.
     *
     **/
    function delete_purpose( $iId ) {

		// sanitize data
        $iId = safeText($iId, false, '', true);

		// only admin can acces this link
		$this->authentication->is_admin_logged_in(true);

		// delete from table
		$this->db->where('id', $iId);
		$this->db->delete('enquiry_purposes');

		sf('success_message', 'The enquiry purpose has been deleted');
		redirect('contact_purpose/listing');
    }


}

/* End of file contact_us.php */
/* Location: ./application/controllers/contact_us.php */
