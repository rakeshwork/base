<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );


    /**
     * Validation rules for creating an address
     *
     * specified here, because it will be used both the sections for users address and office address
     *
     */
	function address_create_form_validation() {

        $CI = & get_instance();

        $CI->load->library('form_validation', NULL, 'address_form_validation');

		$CI->address_form_validation->set_rules('address_line1_', 'Address Line 1', 'trim|required');
		$CI->address_form_validation->set_rules('address_line2_', 'Address Line 2', 'trim');
		$CI->address_form_validation->set_rules('city', 'City', 'trim');
		//$CI->address_form_validation->set_rules('place', 'Place', 'trim');
		$CI->address_form_validation->set_rules('pincode', 'Pincode', 'trim');
		$CI->address_form_validation->set_rules('mobile1', 'Mobile 1', 'trim');
		$CI->address_form_validation->set_rules('mobile2', 'Mobile 2', 'trim');
		$CI->address_form_validation->set_rules('landline1', 'Land line 1', 'trim');
		$CI->address_form_validation->set_rules('landline2', 'Land line 2', 'trim');
	}



    function getAddressSettings($aAddressCustomSettings = array()) {

        $CI = & get_instance();

        $aAddressDefaultSettings = array_merge($CI->config->item('address_form_default_settings'), $aAddressCustomSettings);

        foreach( $aAddressDefaultSettings AS $sKey => $value ) {

            $CI->mcontents[$sKey] = $value;
        }

    }



    /**
     *
     * This function wil load the part of a form, which will collect address of an entity, while creating the address
     */
    function getAddressCreateForm() {


        $CI = & get_instance();


        getAddressSettings();


		// Get districts
        $aConfig = array(
                        'table' => 'districts',
						'id_field' => 'id',
						'title_field' => 'name',
                        'aWhere' => array('state_iso_code' => $CI->mcontents['address_form_settings__sDefaultState']),
                        );
        $CI->mcontents['aDistricts'] = $CI->common_model->getDropDownArray( $aConfig );


		// Get states
        $aConfig = array(
                        'table' => 'states',
						'id_field' => 'iso_code',
						'title_field' => 'name',
                        'aWhere' => array('country_code' => $CI->mcontents['address_form_settings__sDefaultCountry']),
                        );
        $CI->mcontents['aStates'] = $CI->common_model->getDropDownArray( $aConfig );


		// Get Countries
        $aConfig = array(
                        'table' => 'countries',
						'id_field' => 'alpha_2',
						'title_field' => 'name',
                        );
		$CI->mcontents['aCountries'] = $CI->common_model->getDropDownArray( $aConfig );

		return $CI->load->view('admin/address/create_address_form', $CI->mcontents, TRUE);
	}



    /**
     *
     * This function wil load the part of a form, which will show prepopulated address of the entity.
     * used while updating the address
     */
    function getAddressUpdateForm($iAddressUid) {


        $CI = & get_instance();

        $CI->mcontents['oAddressItemForEdit'] = $CI->address_model->get_address_and_contact_numbers($iAddressUid);

        //p( $CI->mcontents['oAddressItemForEdit'] );exit;

        getAddressSettings();

		// Get districts
        $aConfig = array(
                        'table' => 'districts',
						'id_field' => 'id',
						'title_field' => 'name',
                        'aWhere' => array('state_iso_code' => $CI->mcontents['address_form_settings__sDefaultState']),
                        );
        $CI->mcontents['aDistricts'] = $CI->common_model->getDropDownArray( $aConfig );


		// Get states
        $aConfig = array(
                        'table' => 'states',
						'id_field' => 'iso_code',
						'title_field' => 'name',
                        'aWhere' => array('country_code' => $CI->mcontents['address_form_settings__sDefaultCountry']),
                        );
        $CI->mcontents['aStates'] = $CI->common_model->getDropDownArray( $aConfig );


		// Get Countries
        $aConfig = array(
                        'table' => 'countries',
						'id_field' => 'alpha_2',
						'title_field' => 'name',
                        );
		$CI->mcontents['aCountries'] = $CI->common_model->getDropDownArray( $aConfig );


		//$CI->mcontents['aCities'] = $CI->common_model->getDropDownArray( $aConfig );

        return $CI->load->view('admin/address/update_address_form', $CI->mcontents, TRUE);
    }



    /**
     *
     * See if a given address form is valid or not
     *
     * returns boolean
     */
    function isValidAddress() {

        $CI = & get_instance();

        $bIsValid = FALSE;

        address_create_form_validation();

        if( $CI->address_form_validation->run() !== false ) {

            if( validate_contact_numbers() == TRUE ) {

                $bIsValid = TRUE;
            }
        } else {


            /**
             *
             * since we use a different object name for validationa of address,
             * calling form_validation() will not load errors inside the showMessage() funciton.
             * so we are manually getting the errors to merror[] gobal array.
             */

            $CI->merror['error'][] = $CI->address_form_validation->error_string();
        }

        return $bIsValid;
    }



    function validate_contact_numbers(){

        $CI = & get_instance();

        $bReturn = true;

        $aContactNumbers = $CI->address_model->get_contact_numbers();


        if(
            ! $aContactNumbers['mobile1']   &&
            ! $aContactNumbers['mobile2']   &&
            ! $aContactNumbers['landline1'] &&
            ! $aContactNumbers['landline2']
        ) {

            $bReturn = false;

            $CI->merror['error'][] = 'Give atleast one contact number';
        }

        return $bReturn;
    }





    /**
     *
     * Given an address Item, generate the appropriate HTML to display it
     */
    function getAddressHTML($oSingleAddressItem, $aAddressView_Settings = array()) {

        $CI = & get_instance();
        $sView = '';


        if( $oSingleAddressItem ) {


            if( ! isset($CI->mcontents['aStates']) ) {
                $aConfig = array(
                            'table' => 'states',
                            'aWhere' => array('country_id' => 81),
                        );

                $CI->mcontents['aStates'] = $CI->common_model->getDropDownArray( $aConfig );
            }

            if( ! isset($CI->mcontents['aCities']) ) {
                $aConfig = array(
                            'table' => 'cities',
                            'aWhere' => array('state_id' => 1),
                        );

                $CI->mcontents['aCities'] = $CI->common_model->getDropDownArray( $aConfig );
            }


            $aAddressView_DefaultSettings = array(
                                            'template_view_file'    => 'address/template_single_view_default',
                                            'show_contact_details'  => TRUE,
                                        );
            $aAddressView_Settings = array_merge($aAddressView_DefaultSettings, $aAddressView_Settings);

            $CI->mcontents['oSingleAddressItem'] = $oSingleAddressItem;
            $sView = $CI->load->view($aAddressView_Settings['template_view_file'], $CI->mcontents, true);
        }

        return $sView;
    }



    /**
     *
     * Given an address Item, generate the appropriate HTML to display it
     */
    function getContactNumbersHTML($oSingleAddressItem, $aAddressView_Settings = array()) {

        $CI = & get_instance();
        $sView = '';


        if( $oSingleAddressItem ) {

            $CI->mcontents['oSingleAddressItem'] = $oSingleAddressItem;
            $sView = $CI->load->view('address/template_single_contact_numbers', $CI->mcontents, true);
        }

        return $sView;
    }
