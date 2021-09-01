<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use App\Models\UserDevice;
use App\Traits\FcmTrait;

trait LoginTrait
{

    use FcmTrait;

    //const FCMMESSAGE = 'New device login.';

    /**
     * Update user device detail.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected function adddeviceDetail($device_type, $device_token, $user_id, $device_id)
    {
        //$userdevice = UserDevice::where('device_type', $device_type)->where('device_token', $device_token)->orderBy('id', 'desc')->first();
        $userdevice = UserDevice::where('user_id', $user_id)->orderBy('id', 'desc')->first();

        if($userdevice){

            //$deviceToken = $userdevice->device_token ?? '';

            //$this->sendPushNotification($deviceToken, 'Tips Mandi', 'New device login.', 'Logout');

            return UserDevice::where('id', $userdevice->id)->update([
                    //'device_id' => $device_id,
                    'device_token' => $device_token,
                    //'device_type' => $device_type,
                ]);

        }else{

            //Add user device detail
            return UserDevice::create([
                'user_id' => $user_id,
                //'device_id' => $device_id,
                'device_token' => $device_token,
                //'device_type' => $device_type,
            ]);
        }
    }

    /**
     * Generate access token.
     *
     * @return \Illuminate\Http\Response
     */

    protected function accessToken($email, $password)
    {
        //Access token
        try {                       
        
            //Generate new access token
            $http = new \GuzzleHttp\Client(); 
            
            $oauthurl = url('/oauth/token');
            
            $response = $http->post($oauthurl, [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => '2',
                    'client_secret' => 'F0Z66dgLVRI2NueF4U3bzClI3nv6ZDxycxFEO75q',
                    'username' => $email,
                    'password' => $password,
                    'scope' => '',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);

        } catch (ClientException $e) {            

            // If there are network errors, we need to ensure the application doesn't crash.
            // if $e->hasResponse is not null we can attempt to get the message
            // Otherwise, we'll just pass a network unavailable message.

            if ($e->hasResponse()) {

                $exception = (string) $e->getResponse()->getBody();

                $exception = json_decode($exception);
                // Coverting object to an array
                $jsonArray = (array)$exception;

                $response = [
                    'message' => $jsonArray['message'],
                ];

                return response()->json($response, 401);

            } else {

                $response = [
                    'message' => $e->getMessage(),
                ];

                return response()->json($response, 401);

            }
            
        } catch (RequestException $e) {

            // If there are network errors, we need to ensure the application doesn't crash.
            // if $e->hasResponse is not null we can attempt to get the message
            // Otherwise, we'll just pass a network unavailable message.

            if ($e->hasResponse()) {

                $exception = (string) $e->getResponse()->getBody();

                $exception = json_decode($exception);
                // Coverting object to an array
                $jsonArray = (array)$exception;

                $response = [
                    'message' => $jsonArray['message'],
                ];

                return response()->json($response, 401);

            } else {

                $response = [
                    'message' => $e->getMessage(),
                ];

                return response()->json($response, 401);

            }

        } catch (Exception $e) {
            
            $response = [
                'message' => $e->getMessage(),
            ];

            return response()->json($response, 401);

        }
    }  
}
