<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Traits\LoginTrait;
use App\Traits\FcmTrait;
use Carbon\Carbon;
use App\Models\UserDevice;
use App\User;

class RegisterDeviceTokenController extends Controller
{
    use LoginTrait, FcmTrait;

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'nullable|exists:users,id',
            'device_token' => 'required|string',
        ]);

        if ($validator->fails()) { 
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);            
        }

        //Data Request
        $deviceToken = $request->device_token ? : NULL;
        $userId = $request->user_id ? : NULL;
        $deviceType = NULL;
        $deviceId = NULL;
		
		//Store user device token
		if(!empty($userId) && !empty($deviceToken)) {
				
				//Get all previous device token for send logout notification.
				$allDevicesToekns = UserDevice::where('user_id', $userId)->where('device_token', '!=', $request->device_token)->get();
				
				foreach($allDevicesToekns as $allDevicesToekn)
				{
					$deviceToken = $allDevicesToekn->device_token ?? ''; 
				
					$this->sendPushNotification($deviceToken, 'Logout', 'New device login.', 'Logout');
				}
				
				$getDeviceDetail = UserDevice::where('user_id', $userId)->where('device_token', $request->device_token)->first();
				
				if($getDeviceDetail) {
					UserDevice::where('user_id', $userId)->where('device_token', $request->device_token)->update([
						'device_token' => $request->device_token
					]);
				}else{
					UserDevice::create(['user_id' => $userId, 'device_token' => $request->device_token]);
				}
			
				//$deviceToken = $allDevicesToekn->device_token ?? ''; 
				
				$this->sendPushNotification($request->device_token, 'Login', 'New device login.', 'Logout');
				
		}else{
				
				$getDeviceDetail = UserDevice::whereNull('user_id')->where('device_token', $deviceToken)->first();
				if($getDeviceDetail) {
					UserDevice::whereNull('user_id')->where('device_token', $deviceToken)->update([
						'device_token' => $request->device_token
					]);
				}else{
					UserDevice::create(['user_id' => $userId, 'device_token' => $request->device_token]);
				}
		}
		
		$response = [
                        'status' => true,
                        'message' => 'Device toekn updated successfully.', 
                        'device_token' => $request->device_token,
                        'user_id' => $userId ? : 0
                    ];

        return response()->json($response, 200);
		
        $getDeviceDetail = UserDevice::whereNotNull('user_id')->whereUserId($userId)->first();
        
		$deviceTokenExit = UserDevice::whereNull('user_id')->where('device_token', $deviceToken)->first();
		
		//return $deviceTokenExit;
        if($getDeviceDetail) {
			
			if(!empty($userId)) {
				
				$deviceToken = $getDeviceDetail->device_token ?? ''; 
				
				$this->sendPushNotification($deviceToken, 'Login', 'New device login.', 'Logout');
				
			}
			UserDevice::where('user_id', $userId)->update(['device_token' => $request->device_token]);

        }elseif(empty($userId) && $deviceTokenExit) {
			
			if(!empty($userId)) {
				$deviceToken = $deviceTokenExit->device_token ?? ''; 
				
				$this->sendPushNotification($deviceToken, 'Login', 'New device login.', 'Logout');
			}
			
            $deviceTokenExit->update(['device_token' => $request->device_token]);

        }else{

            UserDevice::create(['user_id' => $userId, 'device_token' => $request->device_token]);
        }

        $response = [
                        'status' => true,
                        'message' => 'Device toekn updated successfully.', 
                        'device_token' => $request->device_token,
                        'user_id' => $userId ? : 0
                    ];

        return response()->json($response, 200);
    }

}
