<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );


/**
 * Will return the URL to the current profile picture of the user
 *
 * @param unknown_type $iUserId
 * @return unknown
 */
function getCurrentProfilePic($oProfilePicture, $sSize='small', $bImageTag=true, $aSettings=array()) {

    $CI = &get_instance ();

    // get profile picture sources
    $aProfilePictureSource = $CI->data_model->getDataItem('profile_picture_sources');
    $aProfilePictureSourceFlipped = array_flip($aProfilePictureSource);


    // default settings are defined here
    $aDefaultSettings = array(
        'only_url' => !$bImageTag,
        'strict_dimensions' => false, // if true, the image will be placed inside a
                                      // container with exact dimensions as specified by $sSize
    );
    $aSettings = array_merge($aDefaultSettings, $aSettings);


    // see if the image has to be of strict dimensions.
    // set the settings accordingly.
    if( isset($aSettings['strict_dimensions']) && $aSettings['strict_dimensions'] == true ) {

      $aDimensions = c('profile_pic_thumbnail_dimensions');
      $aSettings['width'] 	= $aDimensions[$sSize]['width'];
      $aSettings['height'] 	= $aDimensions[$sSize]['height'];
    }


    //return image according to the current image set in table.
    switch($oProfilePicture->current_source) {

        case $aProfilePictureSourceFlipped['none']:

            return getDefaultPic('profile_pic', $sSize, $bImageTag, $aSettings);
            break;

        case $aProfilePictureSourceFlipped['system']:
            return getImage('profile_pic', $oProfilePicture->image_name, $sSize, $aSettings);
            break;

        default:
            return getImage('profile_pic', $oProfilePicture->image_name, $sSize, $aSettings);
            break;
    }
}
