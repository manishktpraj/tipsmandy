<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$validator = Validator::make($request->all(), [ 
            'user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) { 
            $response = [
				'status' => false,
                'message' => $validator->errors()->first(),
				'data' => []
            ];
            return response()->json($response, 401);            
        }
		
		$userId = $request->user_id;
		
        $notifications = DB::table('notifications')->where('user_id', $userId)->orderBy('id', 'desc')->get();

        $dataArray = [];
        
		$apiStatus = false;

        $message = 'Notifications not found.';

        if(count($notifications)) {$message = 'Notifications lists.'; $apiStatus = true;}

        foreach ($notifications as $key => $row) {
            $dataArray[$key]['content'] = $row->content;
        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,
                'data' => $dataArray
            ];

        return response()->json($response, 200);
    }

}
