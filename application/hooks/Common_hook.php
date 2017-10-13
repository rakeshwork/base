<?php
class Common_Hook{



	function __construct(){

		$CI->CI = & get_instance();
	}



	/**
 	*
 	* Populate the aAds array for a page
 	*
 	*/
	function Common(){


		$CI = & get_instance();

		//p('testing');exit;
		// If commonly used arrays are not already defined in the contructure of controller, then initialize it here
		if( !isset( $CI->mcontents ) ) {
			$CI->mcontents = array();
		}
		if( !isset( $CI->mcontents['load_js'] ) ) {
			$CI->mcontents['load_js'] = array();
		}
		if( !isset( $CI->mcontents['load_css'] ) ) {
			$CI->mcontents['load_css'] = array();
		}
		if( !isset( $CI->merror ) ) {
			$CI->merror['error'] = array();
		}

//p('here 0');exit;

		// load commonly used config items here,
		// so that we can avoid many function calls throughout the rest of the code.
		$CI->mcontents['c_base_url'] 			= $CI->config->item('base_url');
		$CI->mcontents['c_asset_url'] 			= $CI->config->item('asset_url');
		$CI->mcontents['c_static_image_url'] 	= $CI->config->item('static_image_url');
		$CI->mcontents['c_website_title'] 		= $CI->config->item('website_title');
		$CI->mcontents['c_charset'] 			= $CI->config->item('charset');
		$CI->mcontents['aErrorTypes'] 			= $CI->config->item('error_types');



		$CI->mcontents['success_message'] 		= '';


        // sCurrentMainMenu is used to highlight main menu of website
		$CI->mcontents['sCurrentMainMenu'] 		= ( ! isset( $CI->mcontents['sCurrentMainMenu'] ) ) ?
                                                    'home' :
                                                    $CI->mcontents['sCurrentMainMenu'];

		$CI->mcontents['sCurrentMainMenuChild'] = '';


		// get the regments once here,
		// so that we can avoid many function calls throughout the rest of the code.
		$CI->mcontents['uri_1'] = $CI->uri->rsegment(1);
		$CI->mcontents['uri_2'] = $CI->uri->rsegment(2);




        //GET DATA FOR LOGIN LOGOUT SECTION
		$CI->mcontents['sLoginUrl'] = '';
        $CI->mcontents['bLoggedIn'] = false;



		/**
		 *
		 *
		 * Store the previous uri.
		 *
		 * could be used as an alternative for BACKBUTTON_URI, cancel_button_uri, etc?
		 *
		 * Note : experimental
		 */
		if( $CI->session->userdata('current_uri') ) {
			$CI->session->set_userdata( 'previous_uri', $CI->session->userdata('current_uri') );
		}
		$CI->session->userdata('current_uri', uri_string());



		//load details of the current user who is logged in.
		$CI->mcontents['oCurrUser'] 	= $CI->authentication->is_user_logged_in(false, '', true);

		if($CI->mcontents['oCurrUser']) {
			$CI->mcontents['iAccountNo'] = $CI->mcontents['oCurrUser']->account_no;
		}

		// load user related config
		$CI->mcontents['aGenders'] 					= $CI->data_model->getDataItem('genders');
		$CI->mcontents['aGendersFlipped'] 			= array_flip($CI->mcontents['aGenders']);
		$CI->mcontents['aGenderTitles'] 			= $CI->data_model->getDataItem('genders', 'id-title');


		$CI->mcontents['aUserTypes'] 				= $CI->data_model->getDataItem('user_types');
		$CI->mcontents['aUserTypesFlipped'] 		= array_flip($CI->mcontents['aUserTypes']);
		$CI->mcontents['aUserTypesTitle'] 			= $CI->data_model->getDataItem('user_types', 'id-title');

		$CI->mcontents['aUserStatuses'] 			= $CI->data_model->getDataItem('user_statuses');
		$CI->mcontents['aUserStatusesFlipped'] 		= array_flip($CI->mcontents['aUserStatuses']);

		$CI->mcontents['aOnlineStatuses'] 			= $CI->data_model->getDataItem('online_statuses');
		$CI->mcontents['aOnlineStatusesFlipped'] 	= array_flip($CI->mcontents['aOnlineStatuses']);

		$CI->mcontents['aOnlineVia'] 				= $CI->data_model->getDataItem('online_via');
		$CI->mcontents['aOnlineViaFlipped'] 		= array_flip($CI->mcontents['aOnlineVia']);



		//User roles
		$CI->mcontents['aAllRoles'] 	= getAllRoles();
		$CI->mcontents['aRoleTitles'] = array();
		foreach($CI->mcontents['aAllRoles'] AS $aItem ) {
			$CI->mcontents['aRoleTitles'][$aItem['id']] = $aItem['title'];
		}



		$CI->mcontents['db_facebook_app_id'] = $CI->config->item('db_facebook_app_id');
		$CI->mcontents['load_js']['data']['db_facebook_app_id'] = $CI->config->item('db_facebook_app_id');

		$CI->mcontents['bShowOpenGraphMetaDataInPage'] = TRUE;

		// do we need social sharing functionality in this page?
		// set it from the individual functions
		$CI->mcontents['enable_social_buttons'] = false;



        /**
         *
         *
         *
         * The language routines
         */
		/*
		$aLanguages = array(
						'en' => 'English',
						'ml' => 'Malayalam',
					);


		$cookie_set_language = '';
		if( $CI->input->cookie('language') ) {

			$cookie_set_language = $CI->input->cookie('language');
		}


		$CI->mcontents['sLanguage'] = 'en'; // default
		if ( array_key_exists($cookie_set_language, $aLanguages ) ) {

			$CI->mcontents['sLanguage'] = $cookie_set_language;
		}


		//see if the URL has got anything to say about the language of the page
		if ( array_key_exists( safeText('language', false, 'get'), $aLanguages ) ) {

			$CI->mcontents['sLanguage'] = safeText('language', false, 'get');
		}


		// Set language of the page
        switch( $CI->mcontents['sLanguage'] ) {

            case 'ml' :
                //p'here');
                $CI->mcontents['sLanguage_FieldNameSuffix'] = 'ml';

                $CI->input->set_cookie('language', 'ml',0);
                //p($CI->input->cookie('language'));
				//exit;
				break;

			default :
				$CI->input->set_cookie('language', 'en',0);
                break;
        }

		//we will need it anyways - as long as its light weight
		$CI->lang->load('common');
        */


		/**
		 *
		 * Bread crumbs
		 *
		 */

		$CI->mcontents['aBreadCrumbs'] = array(array(
												'uri' =>'home',
												'title' =>'Home',
											));



		/**
		 *
		 * initializing globally used arrays, if not already initialized in controller
		 */
		if( ! isset($CI->mcontents['load_common_css']) ) {
			$CI->mcontents['load_common_css'] = array();
		}
		if( ! isset($CI->mcontents['load_common_js']) ) {
			$CI->mcontents['load_common_js'] = array();
		}

	}
}
