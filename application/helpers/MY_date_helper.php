<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Finds the difference in days between two calendar dates.
 * 
 * 2011-7-19', '2011-7-21 will give 2
 * 2011-7-21', '2011-7-21 will give 0
 * 2011-7-22', '2011-7-21 will give -1
 *
 * @param Date $startDate
 * @param Date $endDate
 * @return Int
 */
function dateDifference($startDate, $endDate)
{
    // Parse dates for conversion
    $startArry = date_parse($startDate);
    $endArry = date_parse($endDate);

    // Convert dates to Julian Days
    $start_date = gregoriantojd($startArry["month"], $startArry["day"], $startArry["year"]);
    $end_date = gregoriantojd($endArry["month"], $endArry["day"], $endArry["year"]);

    // Return difference
    return round(($end_date - $start_date), 0);
}

/**
 * To get the difference in dates in hours, minutes or seconds
 *
 * @param string $sStartDate | in Y-m-d H:i:s
 * @param string $sEndDate | in Y-m-d H:i:s
 * @param string $sType | years, days, hours, minutes
 */
function timeDifference($sStartDate, $sEndDate, $sType='', $bAbs=true) {
	
	$iStartTime = strtotime($sStartDate);
	$iEndTime = strtotime($sEndDate);
	
	$iTimeDiff = $bAbs ? (abs($iStartTime - $iEndTime)) : ($iStartTime - $iEndTime);

	if($sType == 'years'){
		
		return round (dateDifference($sStartDate, $sEndDate) / 365);
		
	} elseif($sType == 'days'){
		
		return dateDifference($sStartDate, $sEndDate);
		
	} elseif ($sType == 'hours') {
		
		return (int)($iTimeDiff/3600);
		
	} elseif($sType == 'minutes') {
		
		return (int)($iTimeDiff/60);
		
	}else{
		return $iTimeDiff;
	}
}

/**
 * 
 *
 */
/**
 * Finds how much time lapsed from current time, in years, days , hours, mins 
 *
 * @param string $sStartDate |  | in Y-m-d H:i:s
 * @param unknown_type $sMeasure | years, days, hours, minutes
 * @param string $sText | ex: years ago
 * @return unknown
 */
function timeLapsed($sStartDate, $sMeasure = '', $sText = '') {

	$aIntervals = array('years', 'days', 'hours', 'minutes', 'seconds');
	
	$iInterval = 0;
	
	if(in_array($sMeasure, $aIntervals)){
		
		$iInterval = timeDifference($sStartDate, date('Y-m-d H:i:s'), $sMeasure);
		
	} else {
		$i=0;
		while( !$iInterval = timeDifference($sStartDate, date('Y-m-d H:i:s'), $aIntervals[$i]) ) {
			
			$i++;
		}
	}

	$sText  = $sText ? $sText : $aIntervals[$i]." ago";
	
	return $iInterval . " " . $sText;
}

/**
 * get list of months
 *
 * @return array
 */
function getMonths(){

	$aMonths = array (
	        1 => 'January',
	        2 => 'February',
	        3 => 'March',
	        4 => 'April',
	        5 => 'May',
	        6 => 'June',
	        7 => 'July',
	        8 => 'August',
	        9 => 'September',
	        10 => 'October',
	        11 => 'November',
	        12 => 'December'
	    );
	    
	return $aMonths;
}

    /** 
    * Validate a date 
    * 
    * @param    string    $date 
    * @param    string    format 
    * @return    bool 
    * 
    */ 
    function validateDate( $date, $format='YYYY-MM-DD') 
    { 

        switch( $format ) 
        { 
            case 'YYYY/MM/DD': 
            case 'YYYY-MM-DD': 
            list( $y, $m, $d ) = preg_split( '/[-\\.\\/ ]/', $date ); 
            break; 
 
            case 'YYYY/DD/MM': 
            case 'YYYY-DD-MM': 
            list( $y, $d, $m ) = preg_split( '/[-\\.\\/ ]/', $date ); 
            break; 
 
            case 'DD-MM-YYYY': 
            case 'DD/MM/YYYY': 
            list( $d, $m, $y ) = preg_split( '/[-\\.\\/ ]/', $date ); 
            break; 
 
            case 'MM-DD-YYYY': 
            case 'MM/DD/YYYY': 
            list( $m, $d, $y ) = preg_split( '/[-\\.\\/ ]/', $date ); 
            break; 
 
            case 'YYYYMMDD': 
            $y = substr( $date, 0, 4 ); 
            $m = substr( $date, 4, 2 ); 
            $d = substr( $date, 6, 2 ); 
            break; 
 
            case 'YYYYDDMM': 
            $y = substr( $date, 0, 4 ); 
            $d = substr( $date, 4, 2 ); 
            $m = substr( $date, 6, 2 ); 
            break; 
 
            default: 
            return false;
        } 

        return checkdate( $m, $d, $y ); 
    } 
    
    /**
     * Check a date against another for expiry
     *
     * @param string $givenDate in yyyy-mm-dd format
     * @param unknown_type $expiryDate in yyyy-mm-dd format
     * @return boolean
     */
    function hasExpired($givenDate, $expiryDate){
    	
    	$iDiff = strtotime($expiryDate) - strtotime($givenDate);
    	if ( $iDiff > 0 ) {
    		return false;
    	} else {
    		return true;
    	}
    }
	
	
	/**
	 * Add days, weeks, months to a particular date
	 *
	 * $sDate has to be in Y-m-d H:i:s format
	 *
	 * $sUnit - day, days, week, month
	 */
	function addTo($sDate, $iNum, $sUnit="day", $sReturnformat = 'Y-m-d H:i:s'){
		
		return date($sReturnformat, strtotime($sDate . " +".$iNum." ".$sUnit));
	}
	
	
	/**
	 * Add days, weeks, months to a particular date
	 *
	 * $sDate has to be in Y-m-d H:i:s format
	 *
	 * $sUnit - day, days, week, month
	 */
	function minusFrom($sDate, $iNum, $sUnit="day", $sReturnformat = 'Y-m-d H:i:s'){
		
		return date($sReturnformat, strtotime($sDate . " -".$iNum." ".$sUnit));
	}
    
    /**
     *
     * Check whether a given date is within a given date range or not
     * All dates have to be in yymmdd format
     */
    function isWithinRange($sGivenDate, $sDateRangeFrom, $sDateRangeTo) {
        
        $CI = & get_instance();
        
        //p('HELPER' . $sGivenDate . '   ' . $sDateRangeFrom . '   ' . $sDateRangeTo );
        
        $bIsInRange = FALSE;
        
        $oGivenDate         = new DateTime($sGivenDate);
        $oDateRangeFrom     = new DateTime($sDateRangeFrom);
        $oDateRangeTo       = new DateTime($sDateRangeTo);
        
        //p($oGivenDate->getTimestamp());
        //p($oDateRangeFrom->getTimestamp());
        //p($oDateRangeTo->getTimestamp());
        
        if (
                $oGivenDate->getTimestamp() >= $oDateRangeFrom->getTimestamp()
                && 
                $oGivenDate->getTimestamp() <= $oDateRangeTo->getTimestamp()
            )
        {
            $bIsInRange = TRUE;
        } else {
            $CI->merror['error'][] = 'Date not within range';
        }
        
        //p($CI->merror['error']);
        
        return $bIsInRange;
    }