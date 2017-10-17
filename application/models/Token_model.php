<?php
class Token_model extends CI_Model{

	function __construct() {

		parent::__construct();

		$this->aTokenPurposes = $this->data_model->getDataItem('token_purposes');
		$this->aTokenPurposesFlipped = array_flip($this->aTokenPurposes);

		$this->aTokenStatuses = $this->data_model->getDataItem('token_statuses');
		$this->aTokenStatusesFlipped = array_flip($this->aTokenStatuses);
	}


	/**
	 *
	 * Check if a given token is valid or not
	 *
	 * @param string $sToken
	 * @param integer $iUserId
	 * @param string $sPurpose
	 * @return miscellaneous
	 */
	function isValidToken($sToken, $sPurpose, $sUniqueIdentification) {

		$aReturn 		= array(
			'status' => $this->aTokenStatusesFlipped['invalid']
		);

		if( in_array($sPurpose, $this->aTokenPurposes) ) {


			$this->db->where('purpose_id', $this->aTokenPurposesFlipped[$sPurpose]);
			$this->db->where('unique_identification', $sUniqueIdentification);
			$this->db->where('token', $sToken);

			if($oToken = $this->db->get('tokens T')->row()) {

				$aReturn['oToken'] = $oToken;

				$this->load->helper('date');

				if( ! hasExpired( date( 'Y-m-d H:i:s' ), $oToken->expires_on ) ) {

					$aReturn['status'] = $this->aTokenStatusesFlipped['valid'];

				} else {

					$aReturn['status'] = $this->aTokenStatusesFlipped['expired'];
				}
			}

		}

		return $aReturn;
	}



	/**
	 *
	 * Generate tokens for various purposes
	 *
	 * @param unknown_type $sPurpose
	 * @param unknown_type $iLength
	 * @param unknown_type $sPool
	 *
	 */
	function generateToken(	$sPurpose,
							   $sUniqueidentification,
							   $iLength=20,
							   $sPool='',
							   $bStrictPool = false,
							   $bReturnObject=false,
							   $sRandomStringType = 'alnum') {


		$sToken = '';


		// get a unique token string
		$this->load->helper('string');
		do{
			$sToken = random_string($sRandomStringType, $iLength);

			$this->db->where('token', $sToken);
			$oSameTokenValue = $this->db->get('tokens')->row();
		}
		while( $oSameTokenValue );


		// calculate the expiry datetime of this token
		$oTokenPurpose = $this->getPurpose($sPurpose);
		$sExpiresOn = date('Y-m-d H:i:s', time() + $oTokenPurpose->time_to_live);


		$aTokenData = array(
			'purpose_id' 			=> $this->aTokenPurposesFlipped[$sPurpose],
			'token' 				=> $sToken,
			'status' 				=> $this->aTokenStatusesFlipped['valid'],
			'generated_on' 			=> date('Y-m-d H:i:s'),
			'expires_on' 			=> $sExpiresOn,
			'unique_identification' => $sUniqueidentification,
		);

		$oToken = (object)$aTokenData;


		// see if we have an existing token for same purpose, for the same entity(represented by unique_identification).
		$this->db->select('id');
		$this->db->where('purpose_id', $this->aTokenPurposesFlipped[$sPurpose]);
		$this->db->where('unique_identification', $sUniqueidentification);
		$oExistingToken = $this->db->get('tokens')->row();


		if( $oExistingToken ) {

			// if we have, then we are just updating the token with new data
			$this->db->where('id', $oExistingToken->id);
			$this->db->update('tokens', $aTokenData);

			$oToken->id = $oExistingToken->id;

		} else {

			// create the token
			$this->db->insert('tokens', $aTokenData);
			$oToken->id = $this->db->insert_id();
		}


		// what do we want to return ? object or just the token.
		if( $bReturnObject ) {

			return $oToken;
		} else {

			return $sToken;
		}

	}



	/**
	 * Get a token purpose row
	 *
	 * @return [type] [description]
	 */
	function getPurpose($sPurposeName) {

		$this->db->where('name', $sPurposeName);
		return $this->db->get('token_purposes')->row();
	}



	/**
	 *
	 * Delete the given token
	 *
	 * @param integer $iTokenId
	 */
	function deleteToken($iTokenId) {

		$this->db->where('id', $iTokenId);
		$this->db->delete('tokens');
	}

}
