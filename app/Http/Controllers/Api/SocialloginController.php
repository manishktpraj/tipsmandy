<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Traits\LoginTrait;
use App\Traits\Api\UserPlanTrait;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;
use App\Models\UserDevice;
use App\User;
use App\Traits\FcmTrait;

class SocialloginController extends Controller
{
	use LoginTrait, UserPlanTrait, FcmTrait;

    const FCMMESSAGE = 'You have registered successfully.';

    /**
     *
     * Socail login
     */
    public function socialLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'device_token' => 'required|string',
            'social_type' => 'required|string|in:Facebook,Google',
        ]);

        if ($validator->fails()) { 
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);            
        }

        //Request data
        $device_type = request('device_type') ? : NULL;
        $device_token = request('device_token') ? : NULL;
        $device_id = request('device_id') ? : NULL;
        $social_type = request('social_type') ? : NULL;
        $facebook_user_id = request('facebook_user_id') ? : NULL;
        $google_user_id = request('google_user_id') ? : NULL;
        $app_id = request('app_id') ? : NULL;
        $email = request('email') ? : NULL;
        

        $response_arr = array();

        $fbcount = 0;

        $password = $this->randomPassword();

        if ($social_type=='Facebook') {

            $getuserdetailfromemail = User::where('email', $email)->first();

            if ($getuserdetailfromemail) {

                $user_detail = $getuserdetailfromemail;

                $status = 1;

                //User ID
                $user_id = $user_detail->id;

                if($status  == 1){

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $user_id, $device_id);
                    
                    // update the facebook_user_id that might have changed
                    $user_detail->update([
                        'facebook_user_id' => $facebook_user_id,
                    ]);

                    $planDetail = $this->userPlanDetail($user_detail);

                    $response = [
                        'status' => true,
                        'message' => 'Login successfully.',
                        'data' => [
                            'id' => $user_detail->id ?? '',
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            'social_type' => $request->social_type,
                            'device_token' => $user_detail->userdevice->device_token ?? '',
                            'plan_detail' => $planDetail
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'message' => "Your account is not active. Please contact the administrator to activate it!",
                        'active' => false,
                    ];

                    return response()->json($response, 401);
                }
                
            }else{

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users',
                ]);

                if ($validator->fails()) {
                    $response = [
                        'message' => $validator->errors()->first(),
                    ];
                    return response()->json($response, 401);
                }

                $array['user_id'] = $this->generateUserId();
                $array['name'] = $request->name;
                $array['email'] = $request->email;
                $array['password'] = Hash::make('secret@123'); //set detault password
                $create = User::create($array);

                //Check user registration success or not.
                if($create){

                    //Request data
                    $device_type = request('device_type') ? : NULL;
                    $device_token = request('device_token') ? : NULL;
                    $device_id = request('device_id') ? : NULL;

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $create->id, $device_id);

                    //Send push notification
                    $deviceDetail = UserDevice::where('user_id', $create->id)->first();

                    if($deviceDetail) {

                        $deviceToken = $deviceDetail->device_token ?? '';

                        $this->sendPushNotification($deviceToken, 'Stock Edge', self::FCMMESSAGE, 'Notification');
                    }

                    $user_detail = User::where('id', $create->id)->first();

                    $planDetail = $this->userPlanDetail($user_detail);

                    $response = [
                        'message' => 'Login successfully.',
                        'status' => true,
                        'data' => [
                            'id' => $user_detail->id ?? '',
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            'social_type' => $request->social_type,
                            'device_token' => $device_token,
                            'plan_detail' => $planDetail
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'is_facebook' => false,
                        'message' => "Please complete the registration process.",
                    ];

                    return response()->json($response, 401);
                    exit;

                }

            }

        }elseif ($social_type=='Google') {
            
            //Get user detail by using email
            $getuserdetailfromemail = User::where('email', $email)->first();

            if ($getuserdetailfromemail) {

                $user_detail = $getuserdetailfromemail;

                $status = 1;

                //User ID
                $user_id = $user_detail->id;

                if($status  == 1){

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $user_id, $device_id);
                    
                    // update the google_user_id that might have changed
                    $user_detail->update([
                        'google_user_id' => $google_user_id,
                    ]);

                    $planDetail = $this->userPlanDetail($user_detail);

                    $response = [
                        'message' => 'Login successfully.',
                        'status' => true,
                        'data' => [
                            'id' => $user_detail->id ?? '',
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            'social_type' => $request->social_type,
                            'device_token' => $user_detail->userdevice->device_token ?? '',
                            'plan_detail' => $planDetail
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'message' => "Your account is not active. Please contact the administrator to activate it!",
                        'active' => false,
                    ];

                    return response()->json($response, 401);
                }
                
            }else{

                $validator = Validator::make($request->all(), [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|max:255|unique:users',
                ]);

                if ($validator->fails()) {
                    $response = [
                        'message' => $validator->errors()->first(),
                    ];
                    return response()->json($response, 401);
                }

                $array['user_id'] = $this->generateUserId();
                $array['name'] = $request->name;
                $array['email'] = $request->email;
                $array['password'] = Hash::make('secret@123'); //set detault password
                $create = User::create($array);

                //Check user registration success or not.
                if($create){

                    //Request data
                    $device_type = request('device_type') ? : NULL;
                    $device_token = request('device_token') ? : NULL;
                    $device_id = request('device_id') ? : NULL;

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $create->id, $device_id);

                    //Send push notification
                    $deviceDetail = UserDevice::where('user_id', $create->id)->first();

                    if($deviceDetail) {

                        $deviceToken = $deviceDetail->device_token ?? '';

                        $this->sendPushNotification($deviceToken, 'Stock Edge', self::FCMMESSAGE, 'Notification');
                    }

                    $user_detail = User::where('id', $create->id)->first();

                    $planDetail = $this->userPlanDetail($user_detail);

                    $response = [
                        'message' => 'Login successfully.',
                        'status' => true,
                        'data' => [
                            'id' => $user_detail->id ?? '',
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            'social_type' => $request->social_type,
                            'device_token' => $device_token,
                            'plan_detail' => $planDetail
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'is_facebook' => false,
                        'message' => "Please complete the registration process.",
                    ];

                    return response()->json($response, 401);
                    exit;

                }
            }
        
        }else{   
            
            $response = [
                'message' => "Please complete the registration process.",
            ];

            return response()->json($response, 401);
            exit;
        }
    }

    /**
     * Generate user id
     *
     * @return \string
     */
    private function generateUserId()
    {
        do{

            $user = User::orderBy('user_id', 'desc')->first(['user_id']);

            if(empty($user->user_id)) {

                $unique_code = 1001;

            }else{

                $unique_code = $user->user_id+1;
            }

        }while (!empty(User::where('user_id', $unique_code)->first()));

        return $unique_code;

    }

    /**
     *
     * Socail login
     */
    public function socialLoginOld(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            //'device_id' => 'required|string',
            'device_token' => 'required|string',
            //'device_type' => 'required|string',
            'social_type' => 'required|string|in:Facebook,Google',
        ]);

        if ($validator->fails()) { 
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);            
        }

        //Request data
        $device_type = request('device_type');
        $device_token = request('device_token');
        $device_id = request('device_id');
        $social_type = request('social_type');
        $facebook_user_id = request('facebook_user_id');
        $google_user_id = request('google_user_id');
        $app_id = request('app_id');
        $email = request('email');
        

        $response_arr = array();

        $fbcount = 0;

        $password = $this->randomPassword();

        if (!empty($facebook_user_id) && $social_type=='Facebook') {

            //Get user detail by using facebook_user_id
            $user_data = User::where('facebook_user_id', $facebook_user_id)->first();

            //Get user detail by using email
            $getuserdetailfromemail = User::where('email', $email)->first();

            if ($user_data || $getuserdetailfromemail) {

                $user_detail = $user_data ?? $getuserdetailfromemail;

                $status = 1;

                //User ID
                $user_id = $user_detail->id;

                if($status  == 1){

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $user_id, $device_id);
                    
                    // update the facebook_user_id that might have changed
                    $user_detail->update([
                        'facebook_user_id' => $facebook_user_id,
                    ]);

                    $planDetail = $this->userPlanDetail($user_detail);

                    $response = [
                        'message' => 'Login successfully.',            
                        'data' => [
                            'id' => $user_detail->id ?? '',
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            //'device_id' => $user_detail->userdevice->device_id ?? '',
                            'device_token' => $user_detail->userdevice->device_token ?? '',
                            'device_type' => $user_detail->userdevice->device_type ?? '',
                            'plan_detail' => $planDetail
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'message' => "Your account is not active. Please contact the administrator to activate it!",
                        'active' => false,
                    ];

                    return response()->json($response, 401);
                }
                
            }else{

                $response = [
                    'is_facebook' => false,
                    'message' => "Please complete the registration process.",
                ];

                return response()->json($response, 401);
                exit;
            }

        }elseif (!empty($google_user_id) && $social_type=='Google') {
            
            //Get user detail by using google_user_id
            $user_data = User::where('google_user_id', $google_user_id)->first();

            //Get user detail by using email
            $getuserdetailfromemail = User::where('email', $email)->first();

            if ($user_data || $getuserdetailfromemail) {

                $user_detail = $user_data ?? $getuserdetailfromemail;

                $status = 1;

                //User ID
                $user_id = $user_detail->id;

                if($status  == 1){

                    //Update user device detail
                    $this->adddeviceDetail($device_type, $device_token, $user_id, $device_id);
                    
                    // update the google_user_id that might have changed
                    $user_detail->update([
                        'google_user_id' => $google_user_id,
                    ]);

                    $response = [
                        'message' => 'Login successfully.',            
                        'data' => [
                            'name' => $user_detail->name,
                            'email' => $user_detail->email ?? '',
                            'device_id' => $user_detail->userdevice->device_id ?? '',
                            'device_token' => $user_detail->userdevice->device_token ?? '',
                            'device_type' => $user_detail->userdevice->device_type ?? '',
                        ]
                    ];

                    return response()->json($response, 200);

                }else{

                    $response = [
                        'message' => "Your account is not active. Please contact the administrator to activate it!",
                        'active' => false,
                    ];

                    return response()->json($response, 401);
                }
                
            }else{

                $response = [
                    'is_facebook' => false,
                    'message' => "Please complete the registration process.",
                ];

                return response()->json($response, 401);
                exit;
            }
        
        }else{   
            
            $response = [
                'message' => "Please complete the registration process.",
            ];

            return response()->json($response, 401);
            exit;
        }
    }

    /**
     * Generate random password
     *
     */
    public function randomPassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        
        return implode($pass); //turn the array into a string
        //return 12345678; //turn the array into a string
    }

}
