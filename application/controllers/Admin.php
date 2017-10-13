<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {


	public function __construct(){

		parent::__construct();

        //$this->load->config('admin_config');
	}


	public function index() {

		if( hasAccess(array('admin', 'staff') ) ) {

			$aAssignedUserRoles = s('USER_ROLES');

			if( $this->session->userdata('USER_TYPE') == $this->mcontents['aUserTypesFlipped']['admin'] ) {
				$this->home();
			}


		}
	}


	/**
	 *
	 * Home page of admin
	 *
	 */
	function home() {

		$this->mcontents['page_title'] 		= 'Control Panel';
		$this->mcontents['page_heading'] 	= 'Home';

		isAdminSection();

		loadAdminTemplate('home', $this->mcontents);
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin/admin.php */
