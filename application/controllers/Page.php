<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * To show uncategorized pages.
 *
 */
class Page extends CI_Controller {


	public function __construct(){

		parent::__construct();

		$this->load->model('sitepage_model');

        $this->mcontents['sCurrentMainMenu']    = 'sitepages';
	}


	public function index() {

	}


	public function view($sPageName='') {

		if( !$this->mcontents['oPage'] = $this->sitepage_model->getSingleSitepage($sPageName) ) {

			redirect( c('default_controller') );
		}

        $this->mcontents['page_title']          = $this->mcontents['oPage']->title;
		$this->mcontents['sCurrentMainMenu']    = $this->mcontents['oPage']->name;

		loadTemplate('page/view');
	}


	/**
	 * List sitepages for admin
	 *
	 */
	function listing() {

		isAdminSection();
		$iOffset 	= $this->uri->segment(3);
		$iLimit 	= $this->uri->segment(4);

		$this->authentication->is_admin_logged_in(true);
		$this->mcontents['title']						= 'List Sitepages';
		$this->mcontents['selected_tab']				= 'sitepage';
		$this->mcontents['page_heading']				= 'List Sitepages';
		//$this->mcontents['load_css'][] = 'grid.css';

		$iTotal											= $this->sitepage_model->getAllSitepages ('count');
		$this->mcontents ['sitepage_details'] 			= $this->sitepage_model->getAllSitepages ('list', array(), $iOffset, c('sitepage_per_page'));


		/* Pagination */
		$this->load->library('pagination');
		$this->aPaginationConfiguration = array();
		$this->aPaginationConfiguration['base_url'] 	= c('base_url').'page/listing';
		$this->aPaginationConfiguration['total_rows'] 	= $iTotal;
		$this->aPaginationConfiguration['per_page'] 	= c('sitepage_per_page');
		$this->pagination->customizePagination();
		$this->mcontents['iOffset'] = $iOffset;
		//$this->mcontents['load_css'][] = 'pagination.css';
		$this->pagination->initialize($this->aPaginationConfiguration);
		$this->mcontents['sPagination'] = $this->pagination->create_links();
		/* Pagination - End*/

		loadAdminTemplate('page/list_sitepage', $this->mcontents);
	}


	/**
	 *
	 * View sitepage details by admin
	 *
	 */
	function preview() {

		$this->authentication->is_admin_logged_in(true);

		$this->mcontents['title']    		= 'View Sitepage';
		$this->mcontents['selected_tab']	= 'sitepage';
		$this->mcontents['page_heading']	= 'View Sitepage';
	    $page_id   							= $this->uri->segment(3);

	    if( !is_numeric($page_id) ) {

	       $this->session->set_flashdata ("error_message","No sitepage found");

	    }else{

		  $this->mcontents['site_page']		= $this->sitepage_model->getSingleSitepage($page_id);
		  if( empty($this->mcontents['site_page']) ) {

      	       $this->session->set_flashdata ("error_message","No sitepage found");
		  }

	    }

		loadAdminTemplate('page/view_sitepage');
	}

	/**
	 *
	 * Edit sitepage details at admin side
	 *
	 */
	function edit($page_id=0) {

		$this->authentication->is_admin_logged_in(true);
		isAdminSection();

		$this->post_contents				= array();
		$this->mcontents['title']			= 'Edit Sitepage';
		$this->mcontents['page_heading']	= 'Edit Sitepage';
		$this->mcontents['bTinyMce'] 		= false;

		//p($_POST);exit;
		if($this->mcontents ['sitepage_details'] = $this->sitepage_model->getSingleSitepage($page_id)){


			if( isset($_POST) && !empty($_POST) ) {

				$this->__set_edit_validation_rules();
				if($this->form_validation->run() == TRUE) {

					$aDetails = $this->__get_edit_values();

					//p($aDetails);exit;

					$this->sitepage_model->updateSitepage($page_id, $aDetails);
					$this->session->set_flashdata ('success_message', 'Successfully updated sitepage');
					redirect('page/edit/'.$page_id);
				}
			}

		} else {

			redirect('sitepage');
		}

		requireTextEditor();

		//$this->mcontents['load_css'][] = 'forms/sitepage_edit.css';
		//$this->mcontents['load_js'][] = 'tinymce_perpage/edit_sitepage.js';

		loadAdminTemplate('page/edit_sitepage');
	}


	/**
	 *
	 * Set validation rules for sitepage edit
	 *
	 * @param null
	 * @return null
	 *
	 */
	function __set_edit_validation_rules () {

		$this->load->library ("form_validation");
		$this->form_validation->set_rules('title', 'Title','trim'); // NOT REQUIRED. in the case of static contents . will see
		$this->form_validation->set_rules('page_content1','Content','trim');
		$this->form_validation->set_rules('page_content2','Content','trim');
		$this->form_validation->set_rules('page_content3','Content','trim');
		$this->form_validation->set_rules('show','Show','trim');
	}


	/**
	 *
	 * Set edit values from POST array
	 *
	 */
	function __get_edit_values() {

		$aHtmlPurifierAdditionalConfig = array('Attr.AllowedFrameTargets' =>  "array('_blank')");
		return array(
			'title'		=> safeText('title'),
			'content1'	=> safeHtml('page_content1', false, 'post', false, $aHtmlPurifierAdditionalConfig),
			'content2'	=> safeHtml('page_content2', false, 'post', false, $aHtmlPurifierAdditionalConfig),
			'content3'	=> safeHtml('page_content3', false, 'post', false, $aHtmlPurifierAdditionalConfig),
			'show'		=> safeText('show'),
			'updated_date'	=> date('Y-m-d H:i:s'),
		);
	}
}

/* End of file page.php */
/* Location: ./application/controllers/page.php */
