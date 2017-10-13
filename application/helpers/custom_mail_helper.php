<?php if (! defined ( 'BASEPATH' ))	exit ( 'No direct script access allowed' );
/**
 *
 *
 *	TO DO :
 *	1. ALL EMAILING NEEDS TO BE MADE A BACKGROUND PROCESS,
 *	with a DB entry to test for success n all
 *
 *	2. all place-holders used in the body of the email must be predefined to avoid confusion and to bring clarity
 *		Also, this will help us direct the user how to format messages to be used in mails.
 *
 *
 */




	/**
	 * To process and send emails using phpmailer
	 *
	 * @param string $email - to address of the receiver
	 * @param array  $email_array
	 * @param sting $title
	 * @param int $language_id
	 * @return Boolean
	 */

	function sendMail_PHPMailer($aSettings= array()) {

		$CI = &get_instance();

		$bReturn = false;
		$aDefaults = array(
			'to' 						=> array(), // email_id => name pairs
			'from_email' 				=> '',
			'from_name'					=> '',
			'cc' 						=> array(),
			'reply_to' 					=> array(), // email_id => name pairs
			'bcc' 						=>array(),
			'email_contents' 			=> array(), // placeholder keywords to be replaced with this data
			'template_name' 			=> '', //name of template to be used
			'attachment' 				=> array(),
			'wrapping_template_name' 	=> 'default_mail_template',
			'template_from_config' 		=> false, //is the template to be taken from the config file
			'preview' 					=> false, // should the function return a preview of the email? or send it?
			'subject' 					=> '',	// if present, this will be used. ignoring all others
			'mail_body' 				=> '',	// if present, this will be used. ignoring all others
			'mailer' 					=> $CI->config->item('mailer'),
		);

		$aSettings = array_merge($aDefaults, $aSettings);


		$values_array		= array ();
		$result_array       = array();
		if( $aSettings['template_from_config'] ){
			$result_array       = c('email_template_' . $aSettings['template_name']);
		} else {
			//p('testing contact us 1');exit;
			$result_array       = getMailContentAndTitle( $aSettings['template_name'] );
		}


		if ( empty( $result_array ) ) {

			// log an error message here
			$CI->meror['error'][] = 'Templates could not be fetched';
			log_message('error', 'Templates could not be fetched');
			return false;
		}

		foreach ( $result_array as $key=>$value ) {

			$mail_subject   = $key;
			$email_body     = $value;
		}
		//p('mail helper  1 ');
		$body_content = generateEmailBody($aSettings['email_contents'], $email_body, $aSettings['wrapping_template_name'], $aSettings['template_from_config']);


		// the following step can be avoided if we can do the mergin at an earlier stage
		// ( Now the merging is done inside the  generateEmailBody() function, and the changes are not available here,
		// when we are trying to replace into the subject line)

		$aSettings['email_contents'] = array_merge( $CI->config->item('email_template_default_variables'), $aSettings['email_contents'] );

		$mail_subject = replaceInto($aSettings['email_contents'], $mail_subject);

		if( $aSettings['preview'] ) {

			return $body_content;
		}
		//p($body_content);exit;

		// ok.. we are going to send email now

		$CI->load->library('phpmailer');
		if($aDefaults['mailer'] == 'smtp') {


			$CI->phpmailer->Host 			= $CI->config->item('smtp_host');
			$CI->phpmailer->Port			= $CI->config->item('smtp_port');
			$CI->phpmailer->Helo 			= '';
			$CI->phpmailer->SMTPSecure 		= $CI->config->item('smtp_secure');
			$CI->phpmailer->SMTPAuth 		= $CI->config->item('smtp_auth');
			$CI->phpmailer->Username 		= $CI->config->item('smtp_username');
			$CI->phpmailer->Password 		= $CI->config->item('smtp_password');
			$CI->phpmailer->Timeout 		= 10;
			$CI->phpmailer->SMTPDebug 		= false;
			$CI->phpmailer->SMTPKeepAlive 	= false;

			log_message('error', 'SMTP HOST : ' . $CI->phpmailer->Host);
			log_message('error', 'SMTP PORT : ' . $CI->phpmailer->Port);
			log_message('error', 'SMTP SECURE : ' . $CI->phpmailer->SMTPSecure);
			log_message('error', 'SMTP AUTH : ' . $CI->phpmailer->SMTPAuth);
			log_message('error', 'SMTP USERNAME : ' . $CI->phpmailer->Username);
			log_message('error', 'SMTP PASSWORD : ' . $CI->phpmailer->Password);
			
			$CI->phpmailer->IsSMTP();

		} else{


			$CI->phpmailer->IsMail();
		}

		//p('mail helper  31 ');exit;
		$CI->phpmailer->IsHTML(true);

		// log message for debugging purpose
		if( ! is_array($aSettings['to']) ) {
			$CI->merror['error'][] = 'to format is wrong';
			log_message('error', 'to format is wrong');
		}


		foreach($aSettings['to'] AS $sEmail => $sName){
			$CI->phpmailer->AddAddress( $sEmail, $sName );
		}

		if( isset($aSettings['reply_to']) && !empty($aSettings['reply_to']) ) {

			foreach($aSettings['reply_to'] AS $sEmail => $sName){
				$CI->phpmailer->AddReplyTo( $sEmail, $sName );
			}
		}

		$CI->phpmailer->SetFrom( $aSettings['from_email'], $aSettings['from_name'] );
		$CI->phpmailer->Subject = $aSettings['subject'] ? $aSettings['subject'] : $mail_subject;
		$CI->phpmailer->Body = $aSettings['mail_body'] ? $aSettings['mail_body'] : $body_content;



		// Add attachements if present
		if( ! is_array( $aSettings['attachment'] ) ) {
			$aSettings['attachment'] = (array) $aSettings['attachment'];
		}
		if ( $aSettings['attachment'] ) {

			foreach( $aSettings['attachment'] AS $sAttachment ) {

				if ( !empty( $sAttachment ) ) {
					$CI->phpmailer->AddAttachment($sAttachment);
				}

			}
		}



		if($CI->phpmailer->Send()) {
			//p('sent');
			//p( $CI->phpmailer->ErrorInfo );
			//exit;
			$bReturn = TRUE;

		} else {

			$CI->error['error'] = $CI->phpmailer->ErrorInfo;
			log_message('error', $CI->phpmailer->ErrorInfo);
		}

		//clear everything before returning
		$CI->phpmailer->ClearAddresses();
		$CI->phpmailer->ClearCCs();
		$CI->phpmailer->ClearBCCs();
		$CI->phpmailer->ClearCustomHeaders();
		$CI->phpmailer->ClearAllRecipients();
		$CI->phpmailer->ClearAttachments();
		$CI->phpmailer->ClearReplyTos();


		return $bReturn;
	}

	/**
	 * To process and send emails
	 *
	 * TO BE DELETED
	 */
	function sendMail  ($email, $email_array, $content_name, $attachment = array(), $bPreview = false, $sWrapperTemplateName = "default_mail_template", $bFromConfig=false) {



		$CI = &get_instance();
		$values_array		= array ();
		$result_array       = array();
		if($bFromConfig){
			$result_array       = c('email_template_' . $content_name);
		} else {
			$result_array       = getMailContentAndTitle ($content_name);
		}

		if (empty($result_array)){
//p('true 1');
			exit;return false;
		}

		foreach ($result_array as $key=>$value){
			$mail_subject   = $key;
			$email_body     = $value;
		}

		$body_content = generateEmailBody($email_array, $email_body, $sWrapperTemplateName, $bFromConfig);

		$mail_subject = replaceInto($email_array, $mail_subject);

		if( $bPreview ) {
			//p('true 2');
			return $body_content;
		}

		$CI->load->library ('email');
      	$CI->email->protocol       = $CI->config->item ('protocol');
        $CI->email->smtp_host      = $CI->config->item ('smtp_host');
        $CI->email->smtp_user      = $CI->config->item ('smtp_user');
        $CI->email->smtp_pass      = $CI->config->item ('smtp_password');
        $CI->email->mailtype       = $CI->config->item ('mailtype');
        $CI->email->smtp_port       = $CI->config->item ('smtp_port');

        $CI->email->_smtp_auth     = TRUE;
        $from = '';
		$from_name					 = ($from=='')? $CI->config->item ('smtp_from_name') : $from;
        $CI->email->from ($CI->config->item ('smtp_from'), $from_name);
        $CI->email->to ($email);
        $CI->email->subject ($mail_subject);
        $CI->email->message ($body_content);

        foreach($attachment as $attach ){
        	$CI->email->attach($attach);
        }

		/*
        if( ENVIRONMENT_ == 'development'){
        	return true;
        }
        */

        if ($CI->email->send ()){

            return TRUE;

        } else {

            log_message('error', $CI->email->print_debugger());

            return FALSE;

        }
	}


	/**
	 * generates the email body with the specified template
	 *
	 * THE CODE NEEDS TO BE REWRITTEN FOR MORE CLARITY AND POSSIBLE IMPROVED PERFORMANCE
	 *
	 * @param array $aEmailVariables
	 * @param string $sEmailBody
	 * @param string $sTemplateName
	 * @return string
	 */
	function generateEmailBody($aEmailVariables, $sEmailBody, $sTemplateName, $bFromConfig) {

		$CI = &get_instance();

		$sEmailTemplate = '';
		if($bFromConfig){
			$aEmailTemplate = c( 'email_template_' . $sTemplateName );
			$sEmailTemplate = $aEmailTemplate['body'];
		} else {
			$sEmailTemplate = getEmailTemplate( $sTemplateName );
		}

		$aEmailTemplateVariables = $aDefaultEmailTemplateVariables = $CI->config->item('email_template_default_variables');



		// get the template variables which are specific to this template
		if( c('email_template_variables__'.$sTemplateName) ){
			$aEmailTemplateVariables = array_merge($aDefaultEmailTemplateVariables , c('email_template_variables__'.$sTemplateName));
		}



		//if any of the $aEmailTemplateVariables are being used inside the custom content of the email,
		// then they do no get a change to get replaced into the custom content.
		// so we are merging the $aEmailVariables and $aEmailTemplateVariables.
		$aEmailVariables = array_merge($aEmailVariables, $aEmailTemplateVariables);



		//UNDERSTANDING : We want to retain any place holders that were not replaced, to
		// stay in tact, so that we can replace them with the common variables used for all templates. (in the next step)
		// hence the 3rd parameter is false
		$aEmailTemplateVariables['template_email_body'] = replaceInto($aEmailVariables, $sEmailBody, false);


		//UNDERSTANDING : try to replace any left over place holders with the variables which are common for all templates
		$sReturn = '';
		if ( $sEmailTemplate ) {
			$sReturn = replaceInto($aEmailTemplateVariables, $sEmailTemplate);
		} else {
			$sReturn = $aEmailTemplateVariables['template_email_body'];
		}

		//UNDERSTANDING : ok, now we are left with those place holders which are not replaced
		// in step 1 or two.(because they were empty). replace them now
		$sReturn = replaceInto($aEmailVariables, $sReturn);

		return $sReturn;
		//return $sEmailTemplate ? replaceInto($aEmailTemplateVariables, $sEmailTemplate) : $aEmailTemplateVariables['template_email_body'];

	}


	/**
	 * Fetch the email template
	 *
	 * @param string $title
	 * @return string
	 */
	function getEmailTemplate($title) {

		$CI = &get_instance();

        $CI->db->select('body');
        $CI->db->where('name', $title);

		if ( $email_template = $CI->db->get('email_templates')->row() ) {

            return $email_template->body;

		} else {

		    return '';
		}
	}


	function getMailContentAndTitle ($message_title){

		$CI = &get_instance();

        $CI->db->select('subject AS subject, body AS message');
        $CI->db->from('email_templates');
        $CI->db->where('name', $message_title);
        $select_query   = 	$CI->db->get();
        $email_template	=	$select_query->result();

		//p($CI->db->last_query());
		if (!empty($email_template)){
            foreach ($email_template as $row){
                $arr_result[$row->subject] = $row->message;
            }
            return $arr_result;
		}else{
		    return array ()	;
		}
	}


		function sendMailAdvanced ($to_email,$from='', $subject, $body_content,$attachment = array()){
	    $CI->load->library ('email');
      	$CI->email->protocol       = $CI->config->item ('protocol');
        $CI->email->smtp_host      = $CI->config->item ('smtp_host');
        $CI->email->smtp_user      = $CI->config->item ('smtp_user');
        $CI->email->smtp_pass      = $CI->config->item ('smtp_password');
        $CI->email->mailtype       = $CI->config->item ('mailtype');
        $CI->email->_smtp_auth     = TRUE;
		$from_name					 = ($from=='')? $CI->config->item ('smtp_from_name') : $from;
        $CI->email->from ($CI->config->item ('smtp_from'), $from_name);
        $CI->email->to ($to_email);
        $CI->email->subject ($subject);
        $CI->email->message ($body_content);
        foreach($attachment as $attach ){
        	$CI->email->attach($attach);
        }
        if ($CI->email->send ()){
			//echo $CI->email->print_debugger();
			return TRUE;
        }else{
			echo $CI->email->print_debugger();
          	return FALSE;
        }
	}
