<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use App\User;

class ChangePasswordController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->only(['user_id', 'old_password', 'password', 'password_confirmation']), [ 
            'user_id' => 'required|exists:users,id',
            'old_password' => 'required',
            'password' => 'required|required_with:password_confirmation|string|min:6|confirmed|different:old_password',
        ]);

        if($validator->fails()) { 
            
            return $this->outputJSON([
                        'status' => false,
                        'message' => $validator->errors()->first()
                        ], $this->notauthorized);

            exit();
        }

        $userDetail = User::find($request->user_id);

        if (!Hash::check($request->old_password, $userDetail->password)) 
        {
            return $this->outputJSON([
                        'status' => false,
                        'message' => 'The old password does not match.'
                        ], $this->notauthorized);

            exit();
        }

        $userDetail->password = Hash::make($request->password);
        $userDetail->update();

        $response = [
            'status' => true,
            'message' => 'Password updated successfully.',
        ];

        return response()->json($response, 200);
    }

}
