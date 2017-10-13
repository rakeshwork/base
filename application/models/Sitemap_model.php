<?php
class Sitemap_model extends CI_Model{

	function __construct(){
		parent::__construct();

		$this->aChangeFrequencies = c('sitemap_frequencies');
	}
	
	
	/**
	 * 
	 * get landmark list alone
	 * 
	 * NOT USED NOW
	 * 
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 */
	function getLandmarksList($aWhere=array()){
		
		if($aWhere){
			$this->db->where($aWhere);
		}
		return $this->db->get('landmarks')->result();
	}
	
	/**
	 * 
	 * get sitemaps list with all details
	 * 
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 */	
	function getSitemapSections($iLimit=0, $iOffset=0, $aWhere=array()){
	
		$this->db->select('*', false);
		return $this->db->get('sitemap_sections')->result();
	}


	function getSingleSection($iSectionId){

		if( is_numeric($iSectionId) ){
			$this->db->where('id', $iSectionId);
		} else {
			$this->db->where('title', $iSectionId);
		}

		return $this->db->get('sitemap_sections')->row();
	}
	
	/**
	 * 
	 * get landmark list with all details
	 * 
	 * @param unknown_type $iLimit
	 * @param unknown_type $iOffset
	 */	
	function getLinks($iLimit=0, $iOffset=0, $aWhere=array()){
	
		$this->db->select('SL.*, SL.title link_title, SS.title section_title, SL2.title parent_title, SL2.id parent_id' );
		if($iLimit){
			$this->db->limit($iLimit, $iOffset);
		}
		
		if($aWhere){
			$this->db->where($aWhere);
		}
		
		$this->db->join('sitemap_sections SS', 'SS.id = SL.section_id');
		$this->db->join('sitemap_links SL2', 'SL.parent = SL2.id', 'left');
		
		return $this->db->get('sitemap_links SL')->result();
	}
	
	function getSingleLink($iId){

		$this->db->select('SL.*, SL.title link_title, SS.title section_title');
		$this->db->join('sitemap_sections SS', 'SS.id = SL.section_id');
		$this->db->where('SL.id', $iId);
		return $this->db->get('sitemap_links SL')->row();
	}	
	
	/**
	 * get all the sitemap data in a single array.
	 * 
	 * Used in the sitemap page and also during constructing the sitemap.xml file
	 *
	 */
	function getSiteMapData(){
		
		$aSitemapData = array();
		$aParents = array();
		
		$aData = $this->getLinks();
		
		foreach($aData AS $oItem) {
			
			if(!isset($aSitemapData[$oItem->section_id])){
				$aSitemapData[$oItem->section_id] = array();
				$aSitemapData[$oItem->section_id]['section_title'] = $oItem->section_title;
				$aSitemapData[$oItem->section_id]['section_id'] = $oItem->section_id;
			}
			
			$aSitemapData[$oItem->section_id]['aLinks'][$oItem->id] = array(
											'link_title' => $oItem->link_title,
											'url' => $oItem->url,
											'parent_id' => $oItem->parent_id,
											'aChildren' => array()
										);
			//indicate we have a parent
			if($oItem->parent_id){
				$aParents[$oItem->parent_id][] = $oItem->id;
			}
			
		}
		
		//p($aParents);exit;
		
		//adopt childrens
		foreach($aData AS $oItem){
			if( key_exists($oItem->id, $aParents)){
				
				foreach($aParents[$oItem->id] AS $iChildId){
					$aSitemapData[$oItem->section_id]['aLinks'][$oItem->id]['aChildren'][] = $aSitemapData[$oItem->section_id]['aLinks'][ $iChildId ];
					unset($aSitemapData[$oItem->section_id]['aLinks'][ $iChildId ]);					
//					$aSitemapData[$oItem->section_id]['aLinks'][$oItem->id]['aChildren'][] = $aSitemapData[$oItem->section_id]['aLinks'][ $aParents[$oItem->id] ];
//					unset($aSitemapData[$oItem->section_id]['aLinks'][ $aParents[$oItem->id] ]);					
				}

			}			
		}
		
		//p($aSitemapData);exit;
		return $aSitemapData;
		
	}
	
	/**
	 * Generate and save sitemap.xml
	 *
	 */
	function generateSitemapXML(){
		
		$this->load->helper('custom_xml');
		
		$aData = $this->getLinks();
		
		$sXmlContent = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		
		
		$aChangeFrequencies = array_flip($this->aChangeFrequencies);
		
		$fp = fopen(c('base_path').'sitemap.xml', 'w');
		$aDummy = array('url' => array());
		
		foreach($aData AS $oItem) {

			
			$aDummy['url']['loc'] 			= $oItem->url;
			$aDummy['url']['lastmod'] 		= date('Y-m-d', strtotime($oItem->last_modified_on));
			$aDummy['url']['changefreq'] 	= $aChangeFrequencies[$oItem->change_frequency];
			$aDummy['url']['priority'] 		= $oItem->priority;
			
			$sXmlContent .= array2XML($aDummy);

		}
		
		$sXmlContent .= '</urlset>';
		
		fwrite($fp, $sXmlContent);
		fclose($fp);
	}

}