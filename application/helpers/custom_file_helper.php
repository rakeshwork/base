<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );




/**
 *
 * Delete a file
 */
function deleteFile($type, $sSection, $file_name) {

	if( '' != $file_name ) {

		$CI 				= &get_instance();


		$aUploadSettings 	= $CI->config->item ($sSection . '_upload_settings');
		$file_path 			= $aUploadSettings['upload_path'];

		$aInfo = explode('.', $file_name);

		//delete the original file
		@unlink($file_path.$file_name);

		//delete the thumbnails if the file is an image
		if ( $type == 'image' ) {

			$thumb_image_size	= $CI->config->item($sSection . '_thumbnail_dimensions');
			if( $thumb_image_size ) {

				foreach ( $thumb_image_size as $size => $thumb_image_size ) {

					$image = getThumbFileName($aInfo[0], $size , $aInfo[1]);
					@unlink( $file_path.$image );
				}
			}
		}
	}
}
