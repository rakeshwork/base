<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model for sitepage management
 * @author Rahul
 *
 */
class Sitepage_Model extends CI_Model {


	function __construct(){
		parent::__construct();
	}


	/**
	 * Function to get all sitepage details
	 *
	 * @param string $type to get 'list' or 'count'
	 * @param array $where
	 * @param int $offset
	 * @param int $limit
	 * @param array $sort_array
	 * @return object or int or false
	 */
	function getAllSitepages($type = "list", $where = array(), $offset = 0 , $limit = 0, $sort_array = array()){

	    $this->db->where($where);
	    $this->db->select("*");
	    if(is_array($sort_array) && count($sort_array) > 0) {

			$sort_value	= (0 == $sort_array['value']) ? 'ASC': 'DESC';

			if($sort_array['name'] == 'subject') {

				$this->db->order_by($sort_array['name'], $sort_value);
			}

		} else {

			$this->db->order_by('id', 'ASC');
		}

	    if($type == "count") {

		     $query    =   $this->db->get("pages");
		     return $query->num_rows();
	    } else {

	        $this->db->limit($limit,$offset);
		    $query    =   $this->db->get("pages");
	        return $query->result();
	    }
	}


	/**
	 * Function to get sitepage details
	 *
	 * @param int $page_id site page id
	 * @return object or false
	 */
	function getSingleSitepage($id) {

		$this->db->select('*');

		if(is_numeric($id)){
			$this->db->where('id', $id);
		} else {
			$this->db->where('name', $id);
		}

		$oPage = $this->db->get('pages')->row();

		return $oPage;
	}


	/**
	 * Function to update site page details
	 *
	 * @param int $page_id site page id
	 * @param array $page_details
	 * @return TRUE or FALSE
	 */
	function updateSitepage($iId, $page_details) {

		//write_log($iId);
		//write_log($page_details['content1']);

		$this->db->where('id',$iId);
		$this->db->update('pages', $page_details);
	}

}
