<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;
use App\Models\UserDevice;
use Carbon\Carbon;
use Exception;
use Helper;
use App\Traits\FcmTrait;

class PlansController extends Controller
{
    use FcmTrait;

    const FCMMESSAGE = 'Plan purchased successfully.';

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id',
            'plan_duration' => 'required',
        ]);
		
		
		 $plan_month=$request->plan_duration;
		if($plan_month=="Monthly"){
			$plan_month_duration="1";
		}
		if($plan_month=="Quarters"){
			$plan_month_duration="3";
		}
		if($plan_month=="Half yearly"){
			$plan_month_duration="6";
		}
		if($plan_month=="Annually"){
			$plan_month_duration="12";
		}


        if ($validator->fails()) { 
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);            
        }

        //Request Data.
        $planId = $request->plan_id;
        $userId = $request->user_id;
        $planDuration = $plan_month_duration;

        $user = User::whereId($userId)->where('is_plan_status', true)->first();

        if($user) {
            $response = [
                'status' => false,
                'message' => 'Plan already buy.',
            ];

            return $this->outputJSON($response, 200);

            exit;
        }

        //Get plan detail
        $planDetail = Plan::where('id', $planId)->first(['id', 'name']);

        $planDurationDetail = PlanPrice::where('plan_id', $planId)->where('plan_month', $planDuration)->first();

        if($planDurationDetail) {

            $userPlanArray['user_id'] = $userId;
            $userPlanArray['plan_id'] = $planId;
            $userPlanArray['plan_duration'] = $plan_duration = $planDurationDetail->plan_month;
            $userPlanArray['price'] = $planDurationDetail->price;
            

            $currentDateTime = Carbon::now();

            $newDateTime = Carbon::now()->addMonths($plan_duration);

            $userPlanArray['plan_expiry_date'] = Carbon::parse($newDateTime)->format('Y-m-d');
            $userPlanArray['plan_start_date'] = Carbon::now();

            $create = UserPlan::create($userPlanArray);

            if($create) {

                //Update user plan status
                User::whereId($userId)->update(['plan_id' => $planId, 'is_plan_status' => true]);

                $getUser = User::whereId($userId)->first();

                $planArray['user_id'] = $userId;
                $planArray['plan_name'] = $planDetail->name ?? '';
                $planArray['plan_duration'] = $getUser->userplanDetail->plan_duration ?? '';
                $planArray['plan_price'] = $getUser->userplanDetail->price ?? '';
                $planArray['plan_expiry_date'] = $getUser->userplanDetail->plan_expiry_date ?? '';
                $planArray['isPlanActivated'] = true;
				
				//Send push notification
				$deviceDetail = UserDevice::where('user_id', $userId)->first();
				
				if($deviceDetail) {
					
					$deviceToken = $deviceDetail->device_token ?? '';
					
					$this->sendPushNotification($deviceToken, 'Stock Edge', self::FCMMESSAGE, 'Notification');
				}
				
				
                $response = [
                    'message' => 'Plan purchased successfully.',
                    'data' => $planArray
                ];

                $api_status = $this->successStatus;

            }else {

                $response = [
                    
                    'message' => 'Unauthenticated',
                ];
                
                $api_status = $this->notauthorized;
            }

        }else{
            
            $response = [
                    
                'message' => 'Plan duration invalid',
            ];
            
            $api_status = $this->notauthorized;
        }
            

        return $this->outputJSON($response, $api_status);

        exit();
    }


    public function checkPlan($user_id)
    {
        $user = User::whereId($user_id)->where('is_plan_status', true)->first();

        if($user) {
            $response = [
                'status' => false,
                'message' => 'Plan already buy',
            ];

            return $this->outputJSON($response, 200);
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
