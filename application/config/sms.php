<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

		


$config['sms_purpose'] = array(
						'mobile_number_verification' => 1,
					);

$config['sms_sent_status'] = array(
						'sent' => 1,
						'not_sent' => 2,
					);

$config['sms_sent_status_title'] = array(
						1 => 'sent',
						2 => 'not_sent',
					);