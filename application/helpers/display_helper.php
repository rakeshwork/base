<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );

/**
 * easily load header, footer and the required view with one function
 */

	
	function loadTemplate($sTemplate, $mcontents = array()) {
		
		$CI = &get_instance ();
		
        
        // Get the default data for facebook opengraph meta tags
        initialize_og_data();
    	
		
		$mcontents = $mcontents ? $mcontents : $CI->mcontents;
		if ($sTemplate) {
			
			$CI->load->view ( 'header.php', $mcontents );
			$CI->load->view ( $sTemplate );
			$CI->load->view ( 'footer.php' );
			
		} else {
			echo "No Template Specified";
		}
	
	}


/**
 * easily load header, footer and the required view with one function
 */
	function loadAdminTemplate($sTemplate, $mcontents=array()) {
		
		$CI = &get_instance ();
		$mcontents = $mcontents ? $mcontents : $CI->mcontents;
		if ($sTemplate) {
			$CI->load->view ( 'admin/header.php', $mcontents );
			$CI->load->view ( 'admin/'.$sTemplate );
			$CI->load->view ( 'admin/footer.php' );
			//exit;
		} else {
			echo "No Template Specified";
		}
	
	}
	
	/**
	 * Used to show a simple vertical menu tree. 
	 * 
	 * Currently used to display left pane menu in the admin section
	 *
	 * @param unknown_type $aConfig
	 * @return unknown
	 * @todo make use of some jquery Accordion for this
	 */
	function getMenuTree($aConfig){
		
		$CI = &get_instance ();
		
		/**
		 * Find out if the menu sections were opened or closed.
		 * make changes to adhoc changes to the $aConfig accordingly.
		 */
		$CI->load->helper('cookie');
		$sAdminMenuStatus = get_cookie('admin_menu_status');
		//$sAdminMenuStatus = rtrim($sAdminMenuStatus);
		$aAdminMenuStatus = json_decode($sAdminMenuStatus, TRUE);
		
		if($aAdminMenuStatus){
			
			foreach($aConfig AS $iKey => &$aData){
				++$iKey;
				$aData['opened'] = $aAdminMenuStatus[$iKey];
				
			}
		}
		
		
		
		// Create the admin menu.
		$sOutput = '<div class="admin_menu">';
		foreach($aConfig AS $iKey => $aSectionData){
			
			$sItemsDisplayClass = 'dn';
			if( isset($aSectionData['opened']) && $aSectionData['opened'] ) {
				$sItemsDisplayClass = '';
			}
			
			$sOutput .= '<div class="menu_header">'.$aSectionData['section_title'].'</div>';
			$sOutput .= '<div class="items_cnt ' . $sItemsDisplayClass . '">';	
				foreach( $aSectionData['links'] AS $aLink ){
					
					$sOutput .=
					'<div class="item"><a href="'. ($aLink['uri'] ? base_url().$aLink['uri'] : 'javascript:') .'">'.
					$aLink['title'].'</a></div>';
				}
			$sOutput .= '</div>';
			
		}
		$sOutput .= '</div>';
		
		
		return $sOutput;
	}
	
	
    

	/**
	 *
	 * 29-8-2014 - modifications made refering this tutorial -
	 * http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-accordion.php
	 * 
	 * 
	 */
    function getAccordion($aData, $aSettings=array()) {
		
		$CI = &get_instance ();
		
		$aDefaultSettings = array(
							'list_type' => 'unordered'
						);
		
		$aSettings=array_merge($aDefaultSettings, $aSettings);
		
		$sListTypeStart = '<ul class="list-unstyled">';
		$sListTypeEnd 	= '</ul>';
		if( $aSettings['list_type'] == 'ordered' ) {
			
			$sListTypeStart = '<ol>';
			$sListTypeEnd 	= '</ol>';
		}
		
		
		
		$iUnique = rand(0,30); // to have unque identifiers when multiple accordions are used in a single page
		
		// Create the accordian
		$sOutput = '<div class="panel-group" id="accordion'. $iUnique .'">';
		
		
		
		
		foreach( $aData AS $iKey => $aSectionData ) {
			
			
			$sOutput .= '<div class="panel">
							<div class="panel-heading">
								<h4 class="panel-title">
                                <a
									class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? '' : 'collapsed').'"
									data-toggle="collapse"
									data-parent="#accordion"
									href="#collapse' . $iUnique . $iKey.'"
								>
                                    <i class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? 'icon-minus' : 'icon-plus').'"></i>
                                    '.$aSectionData['section_title'].'
                                </a>
								</h4>
                            </div>
                            <div id="collapse' . $iUnique . $iKey.'" class="panel-collapse collapse '.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? 'in' : '').'">
                                <div class="panel-body">
                                    
							';
							
				$sOutput .= $sListTypeStart;
				foreach( $aSectionData['links'] AS $aLink ) {
					
					$sOutput .= '
					<li><i class="icon-caret-right"></i><a href="'.($aLink['uri'] ? $CI->mcontents['c_base_url'].$aLink['uri'] : 'javascript:').'">'.$aLink['title'].'</a></li>';
				}
				$sOutput .= $sListTypeEnd;
				
			$sOutput .= '
                                    
                                </div>
                            </div>
						</div>';
			
		}
		$sOutput .= '</div>';
		
		return $sOutput;
	}
    
    
    
	function getAccordion_old_29_8_2014($aConfig) {
		
		$CI = &get_instance ();
		
		/**
		 * Find out if the menu sections were opened or closed.
		 * make changes to adhoc changes to the $aConfig accordingly.
		 */
		$CI->load->helper('cookie');
		$sAdminMenuStatus = get_cookie('admin_menu_status');
		//$sAdminMenuStatus = rtrim($sAdminMenuStatus);
		$aAdminMenuStatus = json_decode($sAdminMenuStatus, TRUE);
		//p($aAdminMenuStatus);
		if($aAdminMenuStatus){
			
			foreach($aConfig AS $iKey => &$aData){
				++$iKey;
				$aData['opened'] = $aAdminMenuStatus[$iKey];
				
			}
		}
		
		
		
		// Create the admin menu.
		$sOutput = '<div class="accordion" id="accordion2">';
		
		foreach( $aConfig AS $iKey => $aSectionData ) {
			
			$sItemsDisplayClass = 'dn';
			if( isset($aSectionData['opened']) && $aSectionData['opened'] ) {
				$sItemsDisplayClass = '';
			}
			//p($aSectionData);
			//$sOutput .= '<div class="menu_header">'.$aSectionData['section_title'].'</div>';
			//$sOutput .= '<div class="items_cnt ' . $sItemsDisplayClass . '">';
			//$sOutput .= 'testb4';
			$sOutput .= '<div class="accordion-group">
							<div class="accordion-heading">
                                <a class="accordion-toggle '.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? '' : 'collapsed').'" data-toggle="collapse" data-parent="#accordion2" href="#collapse'.$iKey.'">
                                    <i class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? 'icon-minus' : 'icon-plus').'"></i>
                                    '.$aSectionData['section_title'].'
                                </a>
                            </div>
                            <div id="collapse'.$iKey.'" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <ul class="icons">
							
							';
							//$sOutput .= 'test';
							//p($sOutput);exit;
				foreach( $aSectionData['links'] AS $aLink ){
					
					/*$sOutput .=
					'<div class="item"><a href="'. ($aLink['uri'] ? base_url().$aLink['uri'] : 'javascript:') .'">'.
					$aLink['title'].'</a></div>';*/
					$sOutput .= '
					<li><i class="icon-caret-right"></i><a href="'.($aLink['uri'] ? $CI->mcontents['c_base_url'].$aLink['uri'] : 'javascript:').'">'.$aLink['title'].'</a></li>';
				}
			$sOutput .= '
                                    </ul>
                                </div>
                            </div>
						</div>';
			
		}
		$sOutput .= '</div>';
		
		return $sOutput;
		/*
		return '
                    
                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse1">
                                    <i class="icon-minus"></i>
                                    December 2012
                                </a>
                            </div>
                            <div id="collapse1" class="accordion-body collapse in">
                                <div class="accordion-inner">
                                    <ul class="icons">
                                        <li><i class="icon-double-angle-right"></i><a href="#">Wed Design</a></li>
                                        <li><i class="icon-double-angle-right"></i><a href="#">Responsive</a></li>
                                        <li><i class="icon-double-angle-right"></i><a href="#">HTML5 / CSS3</a></li>
                                        <li><i class="icon-double-angle-right"></i><a href="#">Coding Essentials</a></li>
                                        <li><i class="icon-double-angle-right"></i><a href="#">SEO Optimization</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
						
                    ';*/
		
	}
	
	
	
	/**
	 * Will display contents in a tabbed fashion.
	 * 
	 * * any number of menus per page will not conflict
	 * * Makes use of the Jquery Tabs functionality
	 *
	 * @param unknown_type $aContents
	 * @param unknown_type $aSettings
	 */
	function getTabbedDisplayNew($aContents=array(), $aSettings=array(), $aOptions=array()){
		
		$CI = &get_instance ();
		$aSettings = array_merge(c('tabbed_display_settings'), $aSettings);
		
		
		
		// unique key is generated for every request,
		// will conflict with the idea of generating parsed JS files.
		// solved this problem by being able to specify unique key manually.
		// TO DO : Find a proper solution on next revision.
		//
		// PS: unique_key - is required if multiple tabs are present in a single page.
		$aSettings['unique_key'] = ( isset( $aSettings['unique_key'] ) ) ? $aSettings['unique_key'] : rand(7, 1000) ;
		
		
		
		//if there are tab "options", make use of it and unset the key from settings array
		$CI->mcontents['load_js']['data']['sTabOptions'] = '';
		if( isset( $aSettings['tab_options'] ) ) {
			
			$CI->mcontents['load_js']['data']['sTabOptions'] = json_encode($aSettings['tab_options']);
			unset($aSettings['tab_options']);
		}
		
		
		
		//Tab_id - used for initializing the tabs.
		$aSettings['tab_id'] = $aSettings['content_type'] . '_' .$aSettings['unique_key']; 
		
		$CI->mcontents['load_js']['data']['aSettings'][] = $aSettings;
		
		
		//p($CI->mcontents['load_js']['data']);
		if(isset($aSettings['js_file'])){
			$CI->mcontents['load_js'][] = $aSettings['js_file'];
		} else {
			$CI->mcontents['load_js'][] = 'tabs.js';
		}
		
		$aData = array(
			'aContents' => $aContents,
			'aSettings' => $aSettings,
			'aOptions' => $aOptions,
			);
			
		/*load the necessary js and css*/
		requireTabbedContents();
		
		//p($aData);
		
		
		return $CI->load->view('tabbed', $aData, true);
	}
	
	/**
	 * Will display contents in a tabbed fashion.
	 * 
	 * Makes use of the Bootstrap tabbed display functionality
	 *
	 * @param unknown_type $aContents
	 * @param unknown_type $aSettings
	 */
	function getTabbedDisplay_bootstrap($aContents=array(), $aSettings=array(), $aOptions=array()){
		
		$CI = &get_instance ();
		$aSettings = array_merge(c('tabbed_display_settings'), $aSettings);
		
		
		//$aSettings['fragment_id']
		// unique key is generated for every request,
		// will conflict with the idea of generating parsed JS files.
		// solved this problem by being able to specify unique key manually.
		// TO DO : Find a proper solution on next revision.
		//
		// PS: unique_key - is required if multiple tabs are present in a single page.
		$aSettings['unique_key'] = ( isset( $aSettings['unique_key'] ) ) ? $aSettings['unique_key'] : rand(7, 1000) ;
		
		
		
		//if there are tab "options", make use of it and unset the key from settings array
		/*
		$CI->mcontents['load_js']['data']['sTabOptions'] = '';
		if( isset( $aSettings['tab_options'] ) ) {
			
			$CI->mcontents['load_js']['data']['sTabOptions'] = json_encode($aSettings['tab_options']);
			unset($aSettings['tab_options']);
		}
		*/
		
		
		//Tab_id - used for initializing the tabs.
		$aSettings['tab_id'] = $aSettings['content_type'] . '_' .$aSettings['unique_key']; 
		
		$CI->mcontents['load_js']['data']['aSettings'][] = $aSettings;
		
		
		//p($CI->mcontents['load_js']['data']);
		/*
		if(isset($aSettings['js_file'])){
			$CI->mcontents['load_js'][] = $aSettings['js_file'];
		} else {
			$CI->mcontents['load_js'][] = 'tabs.js';
		}
		*/
		
		$aData = array(
			'aContents' => $aContents,
			'aSettings' => $aSettings,
			'aOptions' => $aOptions,
			);
			
		/*load the necessary js and css*/
		//requireTabbedContents();
		
		//p($aData);
		
		
		return $CI->load->view('tabbed', $aData, true);
	}
	
	
	/*
	function getAccordion($aContents=array(), $aSettings=array()){
		
		$CI = &get_instance ();
		
		$aSettings = array_merge(c('accordion_display_settings'), $aSettings);
		
		$aSettings['unique_key'] = rand(1000, 2000);
		
		$aSettings['accordion_id'] = $aSettings['content_type'] . '_' .$aSettings['unique_key'];
		
		$CI->mcontents['load_js'][] = 'jquery\jquery.ui.core.min.js';
		$CI->mcontents['load_js'][] = 'jquery\jquery.ui.widget.min.js';
		$CI->mcontents['load_js'][] = 'jquery\jquery.ui.accordion.js';
		$CI->mcontents['load_js'][] = $aSettings['js_file'];
		$aData = array(
			'aContents' => $aContents,
			'aSettings' => $aSettings,
			);
			
			//p($aSettings);
			
		$CI->mcontents['load_js']['data']['aAccordionSettings'][] = $aSettings;
		return $CI->load->view('accordion', $aData, true);
	}
	*/
    
    
    /**
     *
     * Get the default data for facebook opengraph meta tags
     * 
     */
    function initialize_og_data () {
        
        $CI = &get_instance ();
        
        $aDefaultData = array(
             'og_app_id'      	=> $CI->config->item('db_facebook_app_id'),
             'og_type'      	=> '',
             'og_url'      	    => $CI->mcontents['c_base_url'],
             'og_image'    	    => $CI->config->item('static_image_url') . $CI->config->item('logo_image_name'),
             'og_site_name'	    => $CI->config->item('website_title'),
             'og_title'    		=> $CI->config->item('website_title'),
             'og_description'   => '',
        );
        
        $CI->mcontents['og_meta_data'] = isset($CI->mcontents['og_meta_data']) ? $CI->mcontents['og_meta_data'] : array();
        
        $CI->mcontents['og_meta_data'] = array_merge($aDefaultData, $CI->mcontents['og_meta_data']);
		
    }
    
    
	
	/**
	 *
	 * 29-8-2014 - modifications made refering this tutorial -
	 * http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-accordion.php
	 * 
	 * 
	 */
	function getAccordion_vertical_menu($aData, $aSettings=array()) {
		
		
		//p('tetetetete');
		
		$aDefaults = array(
						'show_open_close_indicator' => false,
						'list_class' => '',
						'underlying_structure' => 'list',
						'main_sub_active' => false, // this is when accessing sub menu item.
													// if true, main menu item and sub menus item, both  will be highlighted as
													// active at the same time.
													// else, only sub menu item will be shown active
					);
		$aSettings = array_merge($aDefaults, $aSettings);
		
		
		$CI = &get_instance ();
		
		//$CI->mcontents['load_js'][] = 'bootstrap_accordion.js';
		
		$sOpenIcon = 'fa fa-angle-down';
		$sCloseIcon = 'fa fa-angle-right';
		
		$aDefaultSettings = array(
							'list_type' => 'unordered'
						);
		
		$aSettings=array_merge($aDefaultSettings, $aSettings);
		
		
		
		$sListTypeStart = '<ul class="'. $aSettings['list_class'] .'">';
		$sListTypeEnd 	= '</ul>';
		if( $aSettings['list_type'] == 'ordered' ) {
			
			$sListTypeStart = '<ol>';
			$sListTypeEnd 	= '</ol>';
		}
		
		
		
		$iUnique = rand(0,30); // to have unque identifiers when multiple accordions are used in a single page
		
		$sAccordionId = 'accordion'. $iUnique;
		// Create the accordian
		
			
			// First iteration
			// To determine which all links need to be highlighted.
			// 'highlight' key is set in this point
			foreach( $aData AS  $iKey => & $aSectionData ) {
				
				$bSublinkActive = false;
				if( isset($aSectionData['links']) && ! empty($aSectionData['links']) ) {
					
					foreach( $aSectionData['links'] AS &$aLinks ) {
						
						if( $aLinks['opened'] === true ) {
							$bSublinkActive = true;
							$aLinks['highlight'] = true;
						}
						else {
							$aLinks['highlight'] = false;
						}
					}
					
				}
				
				if( $aSectionData['opened'] === true ) {
					
					if( $bSublinkActive && ! $aSettings['main_sub_active']) {
						$aSectionData['highlight'] = false;
					} else {
						$aSectionData['highlight'] = true;
					}
				}
				
			}
			
			//$aData2 = $aData;
			
			//following code is needed, so that after accessing array by reference, the last element of array will not repeat
			// see comment by ivan http://php.net/manual/en/language.references.php
			unset( $aSectionData );
			
			
		
		switch( $aSettings['underlying_structure'] ) {
			
			case 'list':
				
				$sOutput = '<div class="panel-group" id="'. $sAccordionId .'">';
				
				
				foreach( $aData AS $iKey => $aSectionData ) {
					
					
					$sOutput .= '<div class="panel">
									<div class="panel-heading">
										<h4 class="panel-title">';
					
					if( isset($aSectionData['url']) && ! empty( $aSectionData['url'] ) ) {
						
						$sOutput .= 	'<a href="'. $aSectionData['url'] .'">';
											
											if( $aSettings['show_open_close_indicator'] ) {
												$sOutput .= '<i class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? $sOpenIcon : $sCloseIcon).'"></i>';
											}
											
											
						$sOutput .= 		$aSectionData['section_title'].'
										</a>';
						
					} else {
						
						$sOutput .= 	'<a
											class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? '' : 'collapsed').'"
											data-toggle="collapse"
											data-parent="'. $sAccordionId .'"
											href="#collapse' . $iUnique . $iKey.'"
										>';
										if( $aSettings['show_open_close_indicator'] ) {
											$sOutput .= '<i class="'.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? $sOpenIcon : $sCloseIcon).'"></i>';
										}
											
						$sOutput .=			$aSectionData['section_title'].'
										</a>';
					}
					
		
					$sOutput .='
										</h4>
									</div>
									<div id="collapse' . $iUnique . $iKey.'" class="panel-collapse collapse '.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? 'in' : '').'">
										<div class="panel-body">
											
									';
									
						$sOutput .= $sListTypeStart;
						foreach( $aSectionData['links'] AS $aLink ) {
							
							
							
							$sOutput .= '
							<li><a href="'.($aLink['uri'] ? $CI->mcontents['c_base_url'].$aLink['uri'] : 'javascript:').'">'.$aLink['title'].'</a></li>';
						}
						$sOutput .= $sListTypeEnd;
						
					$sOutput .= '
											
										</div>
									</div>
								</div>';
					
				}
				$sOutput .= '</div>';
				
				break;
			
			case 'div':
			
				$CI->mcontents['load_js']['data']['accordion_id'] = 'accordion'. $iUnique;
				
				//$sOutput = '<div class="panel-group" id="accordion'. $iUnique .'">';
				$sOutput = '<div class="accordion menu" id="accordion'. $iUnique .'">';
				
				
				//p('here');
				
				//exit;
				
				//p($aData);
				//echo"<pre>";
				
				//print_r($aData);
				//echo"</pre>";
				
				//var_dump($aData);
				/*
				if( $this->input->ip_address() == '111.92.0.201' ) {
					
				}
				*/
				//$aData2 = $aData;
				$sOutput .= '<div class="panel">';	// panel fix start -
																						//http://stackoverflow.com/questions/19425165/bootstrap-3-accordion-button-toggle-data-parent-not-working
				foreach( $aData AS $iKey => $aSectionData ) {
					
					//p($iKey);
					
					if($iKey == 5) {
						
					}
					/*
					*/
					//var_dump($aLink);
					
					//$sOutput .= '<div class="accordion-group">';
					if( isset($aSectionData['url']) && ! empty( $aSectionData['url'] ) ) {
						$sOutput .=		'<div class="accordion-heading">
											<a class="accordion-toggle '.(( isset($aSectionData['highlight']) && $aSectionData['highlight'] ) ? 'current' : '').'" 
												 href="'. $aSectionData['url'] .'">
												'. $aSectionData['section_title'] .
												( (isset($aSectionData['section_title_line2']) && !empty($aSectionData['section_title_line2'])) ? "<br>&nbsp;&nbsp;&nbsp;" . $aSectionData['section_title_line2'] : "" ) . '
											</a>
										</div>';
					} else {
						$sOutput .=		'<div class="accordion-heading">
											<a 	class="accordion-toggle" 
												data-parent="#'. $sAccordionId .'"
												data-toggle="collapse"
												href="#collapse' . $iUnique . $iKey.'"
												>
												'. $aSectionData['section_title'] .
												( (isset($aSectionData['section_title_line2']) && !empty($aSectionData['section_title_line2'])) ? "<br>&nbsp;&nbsp;&nbsp;" . $aSectionData['section_title_line2'] : "" ) . '
											</a>
										</div>';
					}
					
					
					if( isset($aSectionData['links']) && !empty($aSectionData['links']) ) {
					
						$sOutput .= '
							<div class="accordion-body collapse '.(( isset($aSectionData['opened']) && $aSectionData['opened'] ) ? 'in' : '').'"
								id="collapse' . $iUnique . $iKey.'">
								<div class="accordion-inner">';
								
								$sOutput .= $sListTypeStart;
									foreach( $aSectionData['links'] AS $aLink ) {
										
										$sOutput .= '
										<li><a href="'
												.($aLink['uri'] ? $CI->mcontents['c_base_url'].$aLink['uri'] : 'javascript:void(0);').'" class=" '.(( isset($aLink['highlight']) && $aLink['highlight'] ) ? 'current' : '').'">'
												.$aLink['title'] .
												( (isset($aLink['title_line2']) && !empty($aLink['title_line2'])) ? "<br>&nbsp;&nbsp;&nbsp;" . $aLink['title_line2'] : "" )
										.'</a></li>';
									}
								$sOutput .= $sListTypeEnd;
						
						$sOutput .= 	'</div>';
						$sOutput .= '</div>';
									
					}
					
					
					//$sOutput .= '</div>';
					
				}
				$sOutput .= '</div>'; // panel fix end
				
				
				
				$sOutput .= '</div>';
				
				
				break;
				
				
		}
		
		
		
		
		
		
		return $sOutput;
	}