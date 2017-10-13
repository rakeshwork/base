<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Lib_upload {


	function Lib_Upload (){

		$this->CI = & get_instance ();

	  $this->CI->load->config('upload_queue');



	}


	function tieUploadWithEntity($iUploadQueueId, $iEntityId) {

		// make the association & move the file to appropriate folder


		// remove the upload from queue

		// remove the file from the temporary folder
		$this->deleteFromUploadQueue();

	}


	/**
	 *
	 * check if a given scenario is valid or not
	 */
	function isValidFileUploadScenario($sScenario, $bReturnDetails = FALSE) {

			$result = FALSE;

			if( in_array($sScenario, array_keys( $this->CI->config->item('upload_scenarios') )) ) {
				$result = TRUE;
			}

			if($result) {
				$result = $bReturnDetails ? $this->CI->config->item('upload_scenarios')[$sScenario] : $result;
			}

			return $result;
	}


	function requireFileUpload ($sScenario, $iNumUploads) {

		// get the JS and CSS files
		$this->CI->mcontents['load_js'][] = '';


		// load the flash upload helper
		$this->CI->load->helper('flash');


		// get the current user
		$this->CI->mcontents['iAccountNo'] = isset( $CI->mcontents['iAccountNo'] ) ?
												$this->CI->mcontents['iAccountNo'] : $this->CI->session->userdata('ACCOUNT_NO');

		// session handling while uploading via flash
		// following data will be sent back along with the file. and we will verify the user login at file receiving end
		$this->CI->mcontents['uploadify_session_token'] 	= setFlashSessionToken($this->CI->mcontents['iAccountNo'], 'upload_queue');
		$this->CI->mcontents['uploadify_user_acc_no'] 		= $this->CI->mcontents['iAccountNo'];

//p($this->CI->mcontents['uploadify_session_token']);
//p($this->CI->mcontents['uploadify_user_acc_no']);



	}

	/**
	 *
	 * Check if there are entires in the upload queue
	 */
	function checkUploadQueue($sType, $iAccountNo) {

		$CI = & get_instance ();

		 $this->CI->db->where('type', $sType);
		 $this->CI->db->where('account_no', $iAccountNo);
		$query =  $this->CI->db->get('upload_queue');



		if( $query->num_rows() ) {
			return $query->row();
		} else {
			return array();
		}
	}



	/**
	 *
	 * Temporarily store files which are uploaded, but not finalized by the user
	 *
	 * @param unknown_type $sType
	 * @param unknown_type $iMageName
	 * @param unknown_type $iAccountNo
	 * @param boolean $bMulti | indicates multiple file uploads
	 *
	 */
	function pushToUploadQueue($sScenario, $sImageName, $iAccountNo, $bMulti=false, $sMultiUploadToken='', $aUploadData=array(), $sUUid='' ) {

		$CI = &get_instance();

		$this->CI->load->helper('custom_file');

		 $this->CI->db->select('
											image_name,
											multi_upload_token'
										);
		 $this->CI->db->where('account_no', $iAccountNo);
		 $this->CI->db->where('scenario', $sScenario);

		// Remove any previously uploaded files by this user

		if( $oData =  $this->CI->db->get('upload_queue')->row() ) {

			if( $bMulti ) {

				if( $sMultiUploadToken != $oData->multi_upload_token ) {


					// multiple file upload is permitted, and the user is using uploadify to upload yet another picture.
					// problem is that the multi_upload_token doesn match. meaning the user has once again brought up the pop up window for
					// an upload. in this case, check if there are any pending images from the previous upload attempt and
					// delete them

					 $this->CI->db->select('image_name');
					 $this->CI->db->where('account_no', $iAccountNo);
					 $this->CI->db->where('scenario', $sScenario);
					 $this->CI->db->where('multi_upload_token <> ', $sMultiUploadToken);
					foreach( $this->CI->db->get('upload_queue')->result() AS $oRow){

						//deletePendingImage($sType, $oRow->image_name, $iAccountNo, true);
						$this->deleteFromUploadQueue('account_no', $iAccountNo, $sScenario);
					}

				}

			} else {

				//delete any previously uploaded file by this user.
				$this->deleteFromUploadQueue('account_no', $iAccountNo, $sScenario);
			}

		}


		// Now that any previously uploaded files are removed by the
		// above code, lets add this file to the pending images queue

		 $this->CI->db->set('uuid', $sUUid);
		 $this->CI->db->set('account_no', $iAccountNo);
		 $this->CI->db->set('scenario', $sScenario);
		 $this->CI->db->set('image_name', $sImageName);
		 $this->CI->db->set('set_on', time());


		if( $aUploadData ) {
			 $this->CI->db->set( 'serialized_upload_data', serialize( $aUploadData ) );
		}


		if( $bMulti ) {

			// multi_upload_token is set only in case of multiple uploads, in order to identify the group
			// the flash-session-token can be used here
			 $this->CI->db->set('multi_upload_token', $sMultiUploadToken);
		}

		 $this->CI->db->insert('upload_queue');


		return  $this->CI->db->insert_id();
	}


	/**
	 * [clearUploadQueue clear the upload que by deleting the files and the database entries]
	 * @param  integer $iOlderThan [files older than "$iOlderThan" number of hours will be deleted ]
	 * @return [type]              [description]
	 */
		function clearUploadQueue($iOlderThan=0) {

			if($iOlderThan) {
				 $this->CI->db->where('set_on < ', (time() - ($iOlderThan * 60 * 60))); // older than 24 hours
			}

			if( $aData =  $this->CI->db->get('upload_queue')->result() ) {

				//p($aData);
				foreach ($aData AS $oItem) {
					deleteFromUploadQueue('uuid', $oItem->uuid, $oItem->scenario);
				}
			}

		}

		/**
		 * [clearUploadQueue clear the upload que by deleting the files and the database entries]
		 * @param  integer $iOlderThan [files older than "$iOlderThan" number of hours will be deleted ]
		 * @return [type]              [description]
		 */
			function clearUploadQueue_byAccNoScenario($iAccountNo, $sScenario) {

				if($iOlderThan) {
					 $this->CI->db->where('set_on < ', (time() - ($iOlderThan * 60 * 60))); // older than 24 hours
				}

				$this->CI->db->where('account_no',$iAccountNo);
				$this->CI->db->where('scenario',$sScenario);
				if( $aData =  $this->CI->db->get('upload_queue')->result() ) {

					foreach ($aData AS $oItem) {
						deleteFromUploadQueue('id', $oItem->id);
					}
				}

			}

		/**
		 *
		 * Delete a file from the upload queue
		 */
		function deleteFromUploadQueue($sBy='uuid', $sValue='', $sScenario='') {

			$sError = '';

			if( $sBy && $sValue) {

				 $this->CI->db->where($sBy, $sValue);
				if($sScenario) {
					 $this->CI->db->where('scenario', $sScenario);
				}

				$aItem =  $this->CI->db->get('upload_queue')->result();

				if( $aItem ) {

					foreach($aItem AS $oItem) {

						//delete the file from folder
						$this->CI->load->helper('file');



						$sRawPath = $this->CI->config->item('upload_queue_path');
						$sFileName = $oItem->image_name;

						$sFilePath = $sRawPath . $sFileName;

						if( file_exists($sFilePath) ) {

							unlink($sFilePath);
						}

						 $this->CI->db->where('id', $oItem->id);
						 $this->CI->db->delete('upload_queue');
					}

				}

			} else {
				$sError = 'There was some error';
			}

			return $sError;
		}

}
// End of library class
// Location: system/application/libraries/authentication.php
