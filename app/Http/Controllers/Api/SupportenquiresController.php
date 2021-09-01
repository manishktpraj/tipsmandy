<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\Support;

class SupportenquiresController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|numeric|min:10',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);
        }

        $data['ticket_id'] = $this->generateTicketId();
        $data['name'] = $request->name ? : NULL;
        $data['email'] = $request->email ? : NULL;
        $data['phone'] = $request->phone ? : NULL;
        $data['message'] = $request->message ? : NULL;

        $create = DB::table('supports')->insert($data);

        if($create){

            $response = [
                'status' => true,
                'message' => 'Your message has been successfully sent. We will contact you very soon!',
                //'data' => $data
            ];

            return response()->json($response, 200);

        }else{

            $response = [
                'status' => false,
                'message' => 'please try again',
                //'data' => $data
            ];

            return response()->json($response, 200);
        }
    }


    /**
     * Generate ticket id
     *
     * @return \string
     */
    private function generateTicketId()
    {

        do{

            $supportTicket = Support::orderBy('ticket_id', 'desc')->first(['ticket_id']);

            //echo bin2hex(random_bytes(5));
            //$ticket_id = 5;
            //$unique_code = rand(pow(10, $ticket_id-1), pow(10, $ticket_id)-1);

            if(empty($supportTicket->ticket_id)) {

                $unique_code = 10001;

            }else{

                $unique_code = $supportTicket->ticket_id+1;
            }

        }while (!empty(Support::whereTicketId($unique_code)->first()));

        return $unique_code;

    }
	
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storePartnerWithUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|numeric|min:10',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);
        }

        $data['name'] = $request->name ? : NULL;
        $data['email'] = $request->email ? : NULL;
        $data['phone'] = $request->phone ? : NULL;
        $data['message'] = $request->message ? : NULL;

        $create = DB::table('partner_with_us')->insert($data);

        if($create){

            $response = [
                'status' => true,
                'message' => 'Your message has been successfully sent. We will contact you very soon!',
                //'data' => $data
            ];

            return response()->json($response, 200);

        }else{

            $response = [
                'status' => false,
                'message' => 'please try again',
                //'data' => $data
            ];

            return response()->json($response, 200);
        }
    }
}
