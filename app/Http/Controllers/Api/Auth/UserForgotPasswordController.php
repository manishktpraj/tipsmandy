<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Carbon\Carbon;
use App\User;
use Exception;

class UserForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->only(['email']), [
            'email' => 'required|email',
        ]);

        if($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first(),
            ];

            return response()->json($response, 401);
            exit();
        }

        $email = request('email');

        $user = User::where('email', $email)->first();

        if(!$user){

            $response = [
				'status' => false,
                'message' => 'These credentials do not match our records.',
            ];

            return response()->json($response, 200);
        }

        //Checked exceptions
        try {

            $credentials = $request->email;

            // We will send the password reset link to this user. Once we have attempted
            // to send the link, we will examine the response then see the message we
            // need to show to the user. Finally, we'll send out a proper response.
            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            $response == Password::RESET_LINK_SENT
                        ? $this->sendResetLinkResponse($request, $response)
                        : $this->sendResetLinkFailedResponse($request, $response);

           /*$check_token = DB::table('password_resets')->where('email', $email)->first();

            if($check_token){
                //Delete password token
                DB::table('password_resets')->where('email', $email)->delete();
            }

            //Create New Password Reset Token
            DB::table('password_resets')->insert([
                'email' => $email,
                'token' => $new_token,
                'created_at' => Carbon::now()
            ]);

            //Get the token just created above
            $tokenData = DB::table('password_resets')->where('email', $email)->first();*/

            $response = [
				'status' => true,
                'message' => 'A reset link has been sent to your email address.',
            ];

            return response()->json($response);

        } catch (Exception $e) {

            $response = [
				'status' => true,
                'message' => $e->getMessage(),
            ];

            return response()->json($response, 401);

        }
    }
}
