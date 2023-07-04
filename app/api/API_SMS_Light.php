<?php 

	class API_SMS_Light extends Controller{

		const EMAIL     = 'chromaticsoftwares@gmail.com';
		const PASSWORD  = 'sololearn99';
		const API_CODE  = 'PR-MARKA527559_JOHRI';
		const SENDER_ID = 'chroma_sms';


		public function send() {
			//post only
			$endpointAPI = 'https://api.itexmo.com/api/broadcast';
			$req = request()->inputs();
			$payload = unseal($req['payload']);

			if(empty($payload['number'])) {
				Flash::set("NO MOBILE NUMBER ATTACHED FOR THIS USER",'danger');
				return request()->return();
			} elseif(is_mobile_number(str_to_mobile($payload['number']))) {
				$mobileNumberCleaned = str_to_mobile($payload['number']);
				$response = api_call('POST', $endpointAPI, [
					"Email"      => self::EMAIL,
					"Password"   => self::PASSWORD,
					"Recipients" => [$mobileNumberCleaned],
					"Message"    => $payload['message'],
					"ApiCode"    => self::API_CODE
				]);
				Flash::set("Message sent");
				return request()->return();
			} else {
				Flash::set("INVALID MOBILE NUMBER",'danger');
				return request()->return();
			}
		}

		/*
		*sample entry
		*[ [message='sample sms', phone='09xxxxx'], [message='sample sms', phone='09xxxxx'] ]
		*/
		public function sendBulk($messageAndRecipients = []) {
			//post only
			$endpointAPI = ' https://api.itexmo.com/api/broadcast-2d';
		}

	}