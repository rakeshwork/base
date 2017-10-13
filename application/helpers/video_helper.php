<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );



    /**
     * Extract id from a youtube link
     *
     * return string
     */
	function getYoutubeVideoId($sUrl) {
		
		
		$id = '';
		if($sUrl){
			
			$aData = parse_url($sUrl);
            
			
			/*EMPLOY A BETTER METHOD LATER ON*/
			if($aData['host'] == 'www.youtube.com') {
	
				if( isset($aData['query']) && !empty($aData['query']) ) {
						
					$a = explode('&', $aData['query']);
					if(is_array($a) && !empty($a)){
			
            
            
						$a = explode('=', $a[0]);
						if($a[0] == 'v' && !empty($a[1])){
						
							$id = $a[1]; //youtube video id
							
						}
					}
				}	
			} elseif($aData['host'] == 'youtu.be') {
				$a = explode('/', $aData['path']);
				$id = $a[1];
			}
			
		}
		//p($id);exit;
		return $id;
	}
    
	
	/**
	 * Get the youtube URL required to show video inside an iframe
	 * 
	 * Youtube Player Parameters with default values
	 * https://developers.google.com/youtube/player_parameters
	 */
    function getYouTubeVideoURL_iFrame($iVideoId, $aConfig=array()) {
        
        $aDefaultConfig = array(
            
            'autohide'          => 2, //0, 1, 2
            'autoplay'          => 0, //0, 1
            'cc_load_policy'    => 1, //1
            'color'             => 'red', // red, white
            'controls'          => 1, // 0, 1, 2
            'disablekb'         => '', // 0, 1
            'enablejsapi'       => '', //0, 1
            'end'               => '',
            'fs'                => '',
            'iv_load_policy'    => '',
            'list'              => '',
            'listType'          => '',
            'loop'              => '',
            'modestbranding'    => '',
            'origin'            => '',
            'playerapiid'       => '',
            'playlist'          => '',
            'rel'               => '',
            'showinfo'          => '',
            'start'             => '',
            'theme'             => '',
        );
        
        $aConfig = array_merge($aDefaultConfig, $aConfig);
        
        $sQueryString = '';
        foreach($aConfig AS $key=>$value) {
            
            if($value){
                $sQueryString .= $key . '=' . $value . '&';
            }
        }
        
        $sQueryString = rtrim($sQueryString, '&');
        
        $sUrl = 'http://www.youtube.com/embed/' . $iVideoId . '?' . $sQueryString;
        
        return $sUrl;
    }
	
	
	/**
	 * Get the youtube URL required to show in a browser
	 *
	 * Y this function?. because any change to the format of video url
	 * in youtube can be easily reflected in our application too, using this function
	 * 
	 */
	function getYouTubeVideoURL_browser( $iVideoId, $sType='type1' ) {
		
		switch( $sType ) {
			
			case 'type1':
				return 'http://www.youtube.com/watch?v=' . $iVideoId;
				break;
			case 'type2':
				return 'http://www.youtube.com/embed/' . $iVideoId;
				break;
			
		}
		
	}
	
	/**
	 * Get the youtube URL required to show in a browser
	 *
	 * Y this function?. because any change to the format of video url
	 * in youtube can be easily reflected in our application too, using this function
	 * 
	 */
	function getYouTubeVideo_PreviewImage( $iVideoId, $iImageNo=1 ) {
		
		return 'http://img.youtube.com/vi/'.$iVideoId.'/'.$iImageNo.'.jpg';
	}