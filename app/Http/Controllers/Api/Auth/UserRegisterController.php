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
use App\Models\UserDevice;
use App\User;
use Exception;
use Helper;
use App\Traits\FcmTrait;
use App\Traits\Textlocal;

class UserRegisterController extends Controller
{

    use FcmTrait, Textlocal;

    const FCMMESSAGE = 'You have registered successfully.';

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function sendOtp(Request $request)
    {

        $validator = Validator::make($request->all(), [
            //'phone_no' => 'required|numeric|min:10|unique:users,phone_no',
            'phone_no' => 'required|numeric|min:10',
        ]);

        if($validator->fails()) {

            return $this->outputJSON([
                        'message' => $validator->errors()->first()
                        ], $this->notauthorized);

            exit();
        }

        $phoneNo = $request->phone_no;

        //Check account exit or not.
        $user = User::wherePhoneNo($phoneNo)->first();
        if($user) {

            $planArray['plan_name'] = $user->plan->name ?? '';
            $planArray['plan_duration'] = $user->userplanDetail->plan_duration ?? 0;
            $planArray['plan_price'] = $user->userplanDetail->price ?? 0;
            $planArray['plan_expiry_date'] = $user->userplanDetail->plan_expiry_date ?? '';
            $planArray['isPlanActivated'] = $user->is_plan_status ? true : false;

            $response = [
				'status' => false,
				'message' => 'The phone no has already been taken.',
                'data' => [
                    'otp' => '',
                    'phone_no' => $phoneNo,
                    //'id' => $user->id ?? '',
                    //'name' => $user->name ?? '',
                    //'email' => $user->email ?? '',
                    //'phone_no' => $user->phone_no ?? '',
                    //'plan_detail' => $planArray
                ]
            ];

            return response()->json($response, 200);
        }else{

        //Delete otp if already exits
        DB::table('user_otps')->where('phone_no', $phoneNo)->delete();

        $otpExpiryTime= date('Y/m/d H:i:s', strtotime('+15 minutes'));

        $otpCode = $this->generateNumericOTP(4);
        $array['phone_no'] = $phoneNo;
        $array['otp_code'] = $otpCode;
        $array['otp_expiry'] = $otpExpiryTime;

        $create = DB::table('user_otps')->insert($array);

        //Check user registration success or not.
        if($create){

                //$otp=rand(1000,9999);
$message = "Welcome to TIPS MANDI.

Your One Time Password (OTP) for registration/transaction is $otpCode

DO NOT SHARE WITH ANYBODY .

Thanks.";

            if($this->sendSms($request->phone_no, $message)=='success') {

                $data['status'] = true;
                $data['phone_no'] = $phoneNo;
				$data['otp'] = $otpCode;
				
				$response = [
				'status' => true,
				'message' => 'Otp sent successfully on registered mobile number.',
                'data' => [                    
                    'otp' => $otpCode,
					'phone_no' => $phoneNo,
                ]
            ];

            //return response()->json($response, 200);

                //$response = ['message' => 'Otp sent successfully on registered mobile number.','data' => $data];

            }else{

                $response = ['message' => $this->sendSms($request->phone_no, $message)];
            }

        }else{
            $response = ['message' => 'something went wrong please try again'];
        }

        return $this->outputJSON($response, 200);

        exit();
        }
    }
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function useRegister(Request $request) 
    {	

        $validator = Validator::make($request->all(), [ 
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8',
            'phone_no' => 'required|numeric|min:10',
            'otp' => 'required|numeric',
            //'plan' => 'required|exists:plans,id',
            //'plan_duration' => 'required|exists:plan_prices,plan_month',
        ]);

       	if($validator->fails()) { 
            
            return $this->outputJSON([
            			'message' => $validator->errors()->first()
                    	], $this->notauthorized);

            exit();
        }
		
		//check email exit or not 
		$user = User::whereEmail($request->email)->first();
        if($user) {

			
			
            $response = [
				'status' => false,
				'message' => 'The email has already been taken.',
                'data' => [
					'otp' => $request->otp ?? '',
                    'id' => $user->id ?? '',
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'phone_no' => $user->phone_no ?? '',
                    
                ]
            ];

            return response()->json($response, 200);
        }
		//check phone no exit or not.
		
		$user = User::wherePhoneNo($request->phone_no)->first();
        if($user) {

            $response = [
				'status' => false,
				'message' => 'The phone no has already been taken.',
                'data' => [
					'otp' => $request->otp ?? '',
                    'id' => $user->id ?? '',
                    'name' => $user->name ?? '',
                    'email' => $user->email ?? '',
                    'phone_no' => $user->phone_no ?? '',
                    
                ]
            ];

            return response()->json($response, 200);
        }
		
        //Check otp detail valid or not.
        $otpDetail = DB::table('user_otps')->where('phone_no', $request->phone_no)->where('otp_code', $request->otp)->first();
        if(!$otpDetail) {
            $response = ['message' => 'Otp does not matching, Please insert correct otp.'];
            return $this->outputJSON($response, 200);
            exit();
        }
		//$planId = $request->plan;
		//$planDuration = $request->plan_duration;
		
		//$planDurationDetail = PlanPrice::where('plan_id', $planId)->where('plan_month', $planDuration)->first();
		
		//if($planDurationDetail) {
			

		 $referralname = substr("$request->name", 0, 3);
		 $referral_rand=rand(100000,999999);
		$referral_code=$referralname.$referral_rand;
		
		//$referral_code="Rah162589";
		
		
		$referral_codeexits = DB::table('users')->where('referral_code', $referral_code)->first();
		if($referral_codeexits){
			 $referralname = substr("$request->name", 0, 3);
		 $referral_rand=rand(100000,999999);
		 $referral_code=$referralname.$referral_rand;
		}

		
			//Data request
        	//$array['plan_id'] = $request->plan;
        	$array['user_id'] = $this->generateUserId();
        	$array['name'] = $request->name;
        	$array['email'] = $request->email;
        	$array['password'] = Hash::make($request->password);
        	$array['phone_no'] = $request->phone_no;
		    $array['referral_code'] = $referral_code;
		
		
        	//$array['is_plan_status'] = true;

            

            $create = User::create($array);
			//$create = 1;
            //Check user registration success or not.
            if($create){
				
				//Send push notification
                $deviceDetail = UserDevice::where('user_id', $create->id)->first();
                
                if($deviceDetail) {
                    
                    $deviceToken = $deviceDetail->device_token ?? '';
                    
                    $this->sendPushNotification($deviceToken, 'Stock Edge', self::FCMMESSAGE, 'Notification');
                }

				/*$userPlanArray['user_id'] = $create->id;
				$userPlanArray['plan_id'] = $planId;
				$userPlanArray['plan_duration'] = $plan_duration = $planDurationDetail->plan_month;
				$userPlanArray['price'] = $planDurationDetail->price;

				$currentDateTime = Carbon::now();

				$newDateTime = Carbon::now()->addMonths($plan_duration);

				$userPlanArray['plan_expiry_date'] = Carbon::parse($newDateTime)->format('Y-m-d');

				UserPlan::create($userPlanArray);*/
				
                $otp=rand(1000,9999);
$message = "Welcome to TIPS MANDI.

Your One Time Password (OTP) for registration/transaction is $otp

DO NOT SHARE WITH ANYBODY .

Thanks.";

        //$this->sendSms($request->phone_no, $message);
				
				$data['id'] = $create->id ?? 0;
                $data['name'] = $create->name ?? '';
                $data['email'] = $create->email ?? '';
                $data['phone_no'] = $create->phone_no ?? '';

                $response = [
					'status' => true,
                    'message' => 'You have registered successfully.',
                    'data' => $data
                ];

                $api_status = $this->successStatus;

            }else{

            	$response = [
                    
                    'message' => 'Unauthenticated',
                ];
                
                $api_status = $this->notauthorized;

    		}
			
		/*}else{
			
			$response = [
                    
				'message' => 'Plan duration invalid',
			];
			
			$api_status = $this->notauthorized;
		}*/
            

		return $this->outputJSON($response, $api_status);

		exit();
        
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

    // Function to generate OTP
    private function generateNumericOTP($n) {

        // Take a generator string which consist of
        // all numeric digits
        $generator = "1357902468";

        // Iterate for n-times and pick a single character
        // from generator and append it to $result

        // Login for generating a random character from generator
        //     ---generate a random number
        //     ---take modulus of same with length of generator (say i)
        //     ---append the character at place (i) from generator to result

        $result = "";

        for ($i = 1; $i <= $n; $i++) {
            $result .= substr($generator, (rand()%(strlen($generator))), 1);
        }

        // Return result
        return $result;
    }
}
