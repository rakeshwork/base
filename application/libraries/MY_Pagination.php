<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Pagination extends CI_Pagination {
	
	public function __construct(){
		
		$this->CI = &get_instance();
	}
	
	/**
	 * 
	 * Customize the pagination
	 */
	function customizePagination( $aConfig=array() ) {
		
		$aDefault = array(
						'centralize' => false,
					);
		
		$aConfig = array_merge($aDefault, $aConfig);
	
		$this->CI->aPaginationConfiguration['num_links'] 	= 10;
		
		// DESIGN CUSTOMIZATION
		$this->CI->aPaginationConfiguration['full_tag_open'] 	= '<div class="pagination c">';
		$this->CI->aPaginationConfiguration['full_tag_close'] 	= '</div>';
		$this->CI->aPaginationConfiguration['first_link'] 		= '&laquo;';
		$this->CI->aPaginationConfiguration['last_link'] 		= '&raquo;';
		$this->CI->aPaginationConfiguration['first_tag_open'] 	= '<div class="pg_first_tag">';
		$this->CI->aPaginationConfiguration['first_tag_close']	= '</div>';
		$this->CI->aPaginationConfiguration['last_tag_open'] 	= '<div class="pg_last_tag">';
		$this->CI->aPaginationConfiguration['last_tag_close']	= '</div>';
		$this->CI->aPaginationConfiguration['next_link']		= 'Next';
		$this->CI->aPaginationConfiguration['next_tag_open']	= '<div class="nxt_tag_open">';
		$this->CI->aPaginationConfiguration['next_tag_close']	= '</div>';
		$this->CI->aPaginationConfiguration['prev_link']		= 'Prev';
		$this->CI->aPaginationConfiguration['prev_tag_open']	= '<div class="prev_tag_open">';
		$this->CI->aPaginationConfiguration['prev_tag_close']	= '</div>';
		$this->CI->aPaginationConfiguration['num_tag_open']		= '<div class="num_tag">';
		$this->CI->aPaginationConfiguration['num_tag_close']	= '</div>';
		$this->CI->aPaginationConfiguration['cur_tag_open']		= '<div class="cur_tag">';
		$this->CI->aPaginationConfiguration['cur_tag_close']	= '</div>';
		
		
		
		
		// DESIGN CUSTOMIZATION
		$this->CI->aPaginationConfiguration['full_tag_open'] 	=
															'<div class="' .
															($aConfig['centralize'] ? 'pull-center' : '') . '"><ul class="pagination">';
		$this->CI->aPaginationConfiguration['full_tag_close'] 	= '</ul></div>';
		
		
		$this->CI->aPaginationConfiguration['first_link'] 		= 'First';
		$this->CI->aPaginationConfiguration['last_link'] 		= 'Last';
		$this->CI->aPaginationConfiguration['first_tag_open'] 	= '<li>';
		$this->CI->aPaginationConfiguration['first_tag_close']	= '</li>';
		$this->CI->aPaginationConfiguration['last_tag_open'] 	= '<li>';
		$this->CI->aPaginationConfiguration['last_tag_close']	= '</li>';
		
		
		$this->CI->aPaginationConfiguration['next_link']		= '&raquo;';
		$this->CI->aPaginationConfiguration['next_tag_open']	= '<li>';
		$this->CI->aPaginationConfiguration['next_tag_close']	= '</li>';
		$this->CI->aPaginationConfiguration['prev_link']		= '&laquo;';
		$this->CI->aPaginationConfiguration['prev_tag_open']	= '<li>';
		$this->CI->aPaginationConfiguration['prev_tag_close']	= '</li>';
		
		$this->CI->aPaginationConfiguration['num_tag_open']		= '<li>';
		$this->CI->aPaginationConfiguration['num_tag_close']	= '</li>';
		$this->CI->aPaginationConfiguration['cur_tag_open']		= '<li class="active"><a href="#">';
		$this->CI->aPaginationConfiguration['cur_tag_close']	= '</a></li>';
		
	}
}
// End of library class
// Location: system/application/libraries/MY_pagination.php