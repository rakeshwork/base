<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 * Atoload this when there is a user involved
 *
 */

 /**
  * This function will do all necessary things to show admin section.
  *
  **/
function isAdminSection(){

	$CI = &get_instance();


	//$CI->mcontents['load_js'][] 	= 'jquery/jquery.cookie.min.js';
	$CI->mcontents['load_js'][] 	= 'json2_min.js';
	$CI->mcontents['load_js'][] 	= 'admin_menu.js';
}


/**
 * get the default picture of any section
 *
 * no need to worry about strict_dimensions, because default pics are anyways of correct dimensions.
 * @param string $sSection
 */
function getDefaultPic($sSection, $sSize, $bImageTag=true, $aSettings=array()) {

	$sUrl = c($sSection . '_default_pic_url') . c($sSection . '_default_pic') . '_' . $sSize . '.' . c($sSection . '_default_pic_ext');

	if($bImageTag){

	  return getImageTag($sUrl, $aSettings);

	} else {
		return $sUrl;
	}

}


/**
 *
 * @param unknown_type $bRedirect
 * @param unknown_type $sRedirectTo
 * @return unknown
 */
function isAdminLoggedIn($bRedirect=false, $sRedirectTo=''){

	$CI = &get_instance();
	return $CI->authentication->is_admin_logged_in($bRedirect, $sRedirectTo);
}

/**
 *
 * @param unknown_type $bRedirect
 * @param unknown_type $sRedirectTo
 * @return unknown
 */
function isUserLoggedIn($bRedirect=false, $sRedirectTo='welcome', $bReturnObject=false){

	$CI = &get_instance();
	return $CI->authentication->is_user_logged_in($bRedirect, $sRedirectTo, $bReturnObject);
}



/**
 *
 * Get all the roles . from the user_roles table
 */
function getAllRoles() {

   $CI = &get_instance();

   $aUserRoles = array();

   foreach( $CI->db->get('user_roles')->result() AS $oItem ) {

	  $aUserRoles[$oItem->name] = array(
									'id' 			=> $oItem->id,
									'title' 		=> $oItem->title,
									'description' 	=> $oItem->description,
									'weight' 		=> $oItem->weight,
								 );
   }

   return $aUserRoles;
}



/**
 * get the roles of a user in an array
 */
function getUserRoles ( $iAccountNo ) {

   $CI = &get_instance();
   $aList = array();

   $CI->db->where('account_no', $iAccountNo);
   if( $aData = $CI->db->get('map_user_role')->result() ){
	  foreach( $aData AS $oRow ) {
		 $aList[] = $oRow->role;
	  }
   }

   return $aList;
}

/**
 *
 * check if a given users mobile number is verified or not
 */
function isMobileNumberVerified($iAccountNo=0) {

	$CI = & get_instance();
	$bSMSVerificationStatus = FALSE;

	$oUser = $CI->user_model->getUserBy('account_no', $iAccountNo);
	//p($CI->db->last_query());
	$aSMSVerificationStatus = $CI->config->item('mobile_verification_status');

	//p($oUser);
	if( $oUser->mobile_verification_status == $aSMSVerificationStatus['sms_verified'] ) {

		$bSMSVerificationStatus = TRUE;
	}

	return $bSMSVerificationStatus;
}



/**
 *
 * See if the users role matches the given roles
 * $sMatchType 	- return value depends upon if all values match or any match
 * 				- values : 'any', 'all'
 * returns boolean
 *
 */
function doesUserRoleMatch($iAccountNo=0, $sMatchType = 'all', $aGivenRoles = array()) {

	$bMatch = FALSE;

	$aUserRoles = getUserRoles($iAccountNo);

	switch( $sMatchType ) {

		case 'all':

			$aDifference = array_diff($aGivenRoles, $aUserRoles);

			if( empty($aDifference) ) {

				$bMatch = TRUE;
			}
		break;

		case 'any':

			$aIntersection = array_intersect($aGivenRoles, $aUserRoles);

			if( ! empty($aIntersection) ) {

				$bMatch = TRUE;
			}
		break;
	}

	return $bMatch;
}



/**
 *
 * Get the level of a users Role
 */
function getUserRoleLevel( $iAccountNo=0, $sDirection = 'lowest' ) {


    $CI = & get_instance();

    $return = FALSE;

    $CI->db->where('URM.account_no', $iAccountNo);
    $CI->db->join('user_role_map URM', 'UR.id = URM.role');

    if( $sDirection == 'lowest' ) {

        $CI->db->order_by('UR.level', 'DESC');
    }

    if( $sDirection == 'highest' ) {

        $CI->db->order_by('UR.level', 'ASC');
    }
    $CI->db->limit(1);

    if( $oRow = $CI->db->get('user_roles UR')->row() ) {

        $return = $oRow->level;
    }

    return $return;
}




/**
 *
 * Makes sure the user accessing a page has the required level of access or more
 */
function requiredAccessRoleLevel($iRequiredLevel, $bRedirect = TRUE, $sRedirectTo = 'home') {

    $bHasAccess = FALSE;

    $iHighestUserRoleLevel = getUserRoleLevel(S('ACCOUNT_NO'), 'highest');

    //p($iHighestUserRoleLevel);

    if($iHighestUserRoleLevel) {

        //p($iHighestUserRoleLevel);
        //p($iRequiredLevel);

        if( $iHighestUserRoleLevel <= $iRequiredLevel ) {

            $bHasAccess = TRUE;
        }

    } else {

        $bHasAccess = FALSE;

    }

    //var_dump($bHasAccess);
    //exit;

    if( ! $bHasAccess ) {

        if($bRedirect) {

            redirect($sRedirectTo);

        }

    }

    return $bHasAccess;

}
