<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Exception;

trait Textlocal
{
	
	/**
     * Send sms notification
     * @param
     * @return \Illuminate\Http\Response
     */
    public function sendSms($phone, $message)
    {
        try {
                $numbers = array($phone);

                $sender = urlencode("TPSMDI");

                $message = rawurlencode($message);

                // Account details
                //$apiKey = urlencode('NjU3YTRmNjg2Njc3NGM2YzUzNDc1MjUxN2E0NDc5NDQ=');
                $apiKey = urlencode('NjU3YTRmNjg2Njc3NGM2YzUzNDc1MjUxN2E0NDc5NDQ=');

                $numbers = implode(',', $numbers);

                // Prepare data for POST request
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);

                // Send the POST request with cURL
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);

                $json = json_decode($result, true);
                //echo '<pre>';
                //print_r($json);

                if ($json['status']=="success") {
                    return 'success';
                }else{
                    if(isset($json['errors'][0]['message'])) {
                        return $json['errors'][0]['message'];
                    }else{

                        return 'Server error.';
                    }
                }

        } catch (Exception $e) {
            
        }

    }
	

}
