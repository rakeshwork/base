<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct() {

		parent::__construct();

	}

	function index() {


		
		$this->mcontents['sCurrentMainMenu'] = 'home';

		//redirect('survey');
		loadTemplate('home/home');

		//p($this->session->userdata);
	}


}

/* End of file home.php */
/* Location: ./application/controllers/home.php */
