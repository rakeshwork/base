<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {


	public function __construct(){

		parent::__construct();
	}

	public function index() {

		$this->authentication->logout();

		$this->mcontents['aOnlineVia'];
		$aOnlineVia = c('online_via');

		redirect(c('default_controller'));
	}

}

/* End of file logout.php */
/* Location: ./application/controllers/logout.php */
