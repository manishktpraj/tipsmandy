<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\User;

class UserHomeController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
	

	
	
    public function getProfile(Request $request)
    {
	

        $validator = Validator::make($request->only(['user_id']), [ 
            'user_id' => 'required'
        ]);

        if($validator->fails()) { 
            
            return $this->outputJSON([
                        'message' => $validator->errors()->first()
                        ], $this->notauthorized);

            exit();
        }

        $getUser = User::whereId($request->user_id)->first();

        //Check user login or not
        if ($getUser) {

            //Get Login user detail
            //$user = Auth::user();
            
            //if($getUser->userplanDetail) {
                $planArray['plan_name'] = $getUser->plan->name ?? '';
                $planArray['plan_duration'] = $getUser->userplanDetail->plan_duration ?? 0;
                $planArray['plan_price'] = $getUser->userplanDetail->price ?? 0;
                $planArray['plan_expiry_date'] = $getUser->userplanDetail->plan_expiry_date ?? '';
                $planArray['isPlanActivated'] = $getUser->is_plan_status ? true : false;
            //}else{
                //$planArray = [];
            //}
            
            $userarray = [
                'user_id' => $getUser->id,
                'name' => $getUser->name ?? '',
                'email' => $getUser->email ?? '',
                'phone_no' => $getUser->phone_no ?? '',
                'gender' => $getUser->gender ? 'Male' : 'Female',
                'market_experience' => config('constants.marketExperience')[$getUser->market_experience] ?? '',
                'plan_detail' => $planArray
            ];

            $response = [
                'status' => true,
                'message' => 'Profile detail',
                'data' => $userarray,
            ];

            return response()->json($response, 200);

        }else{

            $response = [
                'status' => false,
                'message' => 'User detail not found.',
            ];
            
            return response()->json($response, 401);
			
			
			
                
        }
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
	
	
	public function updateProfile(Request $request)
	{

		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required|exists:users,id',
			'email' => 'required|string|email|max:255|unique:users,email,'.$request->user_id,

		]);

		if ($validator->fails()) { 
			$response = [
				'message' => $validator->errors()->first(),
			];
			return response()->json($response, 401);   
		}

		$userId=$request->user_id;
		$userdetails['name'] = $request->name;
		$userdetails['email'] = $request->email;
		$userdetails['phone_no'] = $request->phone_no;
		$userdetails['gender'] = $request->gender;
		$userdetails['market_experience'] = $request->market_experience;
		$result= User::whereId($userId)->update($userdetails);

		if($result){
			$response = [
				'status' => true,
				'message' => 'Profile detail Updated',
			];
			return response()->json($response, 200);	
		}
		else{
			$response = [
				'status' => false,
				'message' => 'User detail not found.',
			];
			return response()->json($response, 401);
		}


	}
	
	
	
	
	public function referraluser(Request $request)
	{


		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required|exists:users,id',
			'referral_code' => 'required',

		]);

		if ($validator->fails()) { 
			$response = [
				'message' => $validator->errors()->first(),
			];
			return response()->json($response, 401);   
		}

		$getreferral_code_used = DB::table('referral_user')->where('user_id',$request->user_id)->first();

		if($getreferral_code_used){
			$response = [
				'status' => false,
				'message' => 'you already used some referral code before .',
			];

			return response()->json($response, 200);
		}
		$getreferral_code = User::where('referral_code',$request->referral_code)->first();
		
		if($getreferral_code){
			$userid=$getreferral_code->id;

			$userdetails['user_id'] = $request->user_id;
			$userdetails['referral_code'] = $request->referral_code;
			$userdetails['referral_userid'] = $userid;

			$result= DB::table('referral_user')->insert($userdetails);

			$response = [
				'status' => true,
				'message' => 'Sucess.',
			];

			return response()->json($response, 200);	
		}

		else{
			$response = [
				'status' => false,
				'message' => 'wrong referral code ',
			];

			return response()->json($response, 401);
		}

	}
	
	
	
	
		
	public function userbankdetails(Request $request)
	{


		$validator = Validator::make($request->all(), [ 
			'user_id' => 'required|exists:users,id',
			'account_number' => 'required',
			'account_name' => 'required',
			'ifsc_code' => 'required',
			'account_type' => 'required',

		]);

		if ($validator->fails()) { 
			$response = [
				'message' => $validator->errors()->first(),
			];
			return response()->json($response, 401);   
		}

		$bank_details = DB::table('bankdetails')->where('user_id',$request->user_id)->first();
		if($bank_details){
			
		
		
			$userdetails['user_id'] = $request->user_id;
			$userdetails['account_number'] = $request->account_number;
			$userdetails['account_name'] = $request->account_name;
			$userdetails['ifsc_code'] = $request->ifsc_code;
			$userdetails['account_type'] = $request->account_type;

			
			$result= DB::table('bankdetails')->where('user_id', $request->user_id)->update($userdetails);

			

			$response = [
				'status' => true,
				'message' => 'Sucess.',
			];

			return response()->json($response, 200);	
		}

		else{
			
	
						$userdetails['user_id'] = $request->user_id;
			$userdetails['account_number'] = $request->account_number;
			$userdetails['account_name'] = $request->account_name;
			$userdetails['ifsc_code'] = $request->ifsc_code;
			$userdetails['account_type'] = $request->account_type;

			$result= DB::table('bankdetails')->insert($userdetails);
			
			
			

			$response = [
				'status' => true,
				'message' => 'Sucess.',
			];

			return response()->json($response, 200);
		}

	}
	
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
