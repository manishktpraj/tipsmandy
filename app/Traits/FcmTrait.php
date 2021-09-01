<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Exception;

trait FcmTrait
{
	
	/**
     * Push notification
     * @param
     * @return \Illuminate\Http\Response
     */
    public function sendPushNotification($registration_ids, $title, $body, $type)
    {
        try {
            
        
            $json_data = array(
                'registration_ids' => array($registration_ids),
                //'notification' => array( 'title' => $title, 'body' => 'Hello world.'),
                'data' => array( 'title' => $title, 'body' => $body, 'type' => $type),
                'notification' => array( 'body' => $body, 'title' => $title),
            );


            $data = json_encode($json_data);
            //FCM API end-point
            $url = 'https://fcm.googleapis.com/fcm/send';
            //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = 'AAAAp20Vvi4:APA91bG3UgQ8sVrjL6ET284vWz03BRpCnVGWWEpKTA_9dtb9onMHLIpt9CuEo3CiB5oPu5k-I0qkgaox5OPm2pMBUgmpEQnZkfsswPhrdanB5vOTJZ0lFhfG98AKst3t3KhDyTUFpu74';

            
            //header with content_type api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            //if ($result === FALSE) {
                //die('Oops! FCM Send Error: ' . curl_error($ch));
            //}
            curl_close($ch);

            //echo '<pre>';
            //print_r($result);
        } catch (Exception $e) {
            
        }

    }
	
    /**
     * Push notification
     * @param
     * @return \Illuminate\Http\Response
     */
    public function fcmPushNotification($registration_ids, $title, $body, $type)
    {
        try {
            
        
            $json_data = array(
                'registration_ids' => array($registration_ids),
                //'notification' => array( 'title' => $title, 'body' => 'Hello world.'),
                'data' => array( 'title' => $title, 'body' => $body, 'type' => $type),
                'notification' => array( 'body' => $body, 'title' => $title),
            );


            $data = json_encode($json_data);
            //FCM API end-point
            $url = 'https://fcm.googleapis.com/fcm/send';
            //api_key in Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key
            $server_key = 'AAAAp20Vvi4:APA91bG3UgQ8sVrjL6ET284vWz03BRpCnVGWWEpKTA_9dtb9onMHLIpt9CuEo3CiB5oPu5k-I0qkgaox5OPm2pMBUgmpEQnZkfsswPhrdanB5vOTJZ0lFhfG98AKst3t3KhDyTUFpu74';

            
            //header with content_type api key
            $headers = array(
                'Content-Type:application/json',
                'Authorization:key='.$server_key
            );
            //CURL request to route notification to FCM connection server (provided by Google)
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            $result = curl_exec($ch);
            //if ($result === FALSE) {
                //die('Oops! FCM Send Error: ' . curl_error($ch));
            //}
            curl_close($ch);

            echo '<pre>';
            print_r($result);
        } catch (Exception $e) {
            
        }

    }
}
