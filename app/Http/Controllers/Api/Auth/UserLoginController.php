<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;
use App\User;
use Exception;
use Helper;

class UserLoginController extends Controller
{
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    protected function userLogin(Request $request)
    {
       try {

            $validator = Validator::make($request->all(), [
                'email' => 'required|string',
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {

                return $this->outputJSON(['message' => $validator->errors()->first()], $this->notauthorized);
                exit();
            }

            //'email', 'mobile', 'password', 'api_password', 'dudid'
            $get_detail = User::whereEmail($request->email)->first();

            // Check Condition email. Found or Not
            if(!$get_detail) {

                //$message = 'These credentials do not match our records.';

                //return $this->outputJSON(['message' => $message], 401);

                $planArray['plan_name'] = $get_detail->plan->name ?? '';
                $planArray['plan_duration'] = $get_detail->userplanDetail->plan_duration ?? 0;
                $planArray['plan_price'] = $get_detail->userplanDetail->price ?? 0;
                $planArray['plan_expiry_date'] = $get_detail->userplanDetail->plan_expiry_date ?? '';
                $planArray['isPlanActivated'] = false;

                $response = [
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                    'data' => [
                        'id' => $get_detail->id ?? 0,
                        'name' => $get_detail->name ?? '',
                        'email' => $get_detail->email ?? '',
                        'phone_no' => $get_detail->phone_no ?? '',
                        'plan_detail' => $planArray

                    ]
                ];

                return $this->outputJSON($response, 200);

                exit();

            }

            // Set auth details for login user
            $credentials = ['email' => $request->email, 'password' => $request->password];

            //Login user
            if (!Auth::attempt($credentials))
            {

                //$message = 'These credentials do not match our records.';

                //return $this->outputJSON(['message' => $message], 401);

                $planArray['plan_name'] = '';
                $planArray['plan_duration'] = 0;
                $planArray['plan_price'] = 0;
                $planArray['plan_expiry_date'] = '';
                $planArray['isPlanActivated'] = false;

                $response = [
                    'status' => false,
                    'message' => 'These credentials do not match our records.',
                    'data' => [
                        'id' => 0,
                        'name' => '',
                        'email' => '',
                        'phone_no' => '',
                        'plan_detail' => $planArray

                    ]
                ];

                return $this->outputJSON($response, 200);

                exit();
            }

            $user = $request->user();

            //if($user->userplanDetail) {
                $planArray['plan_name'] = $user->plan->name ?? '';
                $planArray['plan_duration'] = $user->userplanDetail->plan_duration ?? 0;
                $planArray['plan_price'] = $user->userplanDetail->price ?? 0;
                $planArray['plan_expiry_date'] = $user->userplanDetail->plan_expiry_date ?? '';
                $planArray['isPlanActivated'] = $user->is_plan_status ? true : false;
            //}else{

                //$planArray = [];
            //}
            $response = [
				'status' => true,
				'message' => 'Login successfully.',
                'data' => [                                        
                    'id' => $user->id ?? '',
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'phone_no' => $user->phone_no ?? '',
                    'plan_detail' => $planArray
                ]
            ];

            return response()->json($response, 200);


        } catch (Exception $e) {
            
            return $this->outputJSON(['message' => $e->getMessage()], 500);

        }
    }
}
