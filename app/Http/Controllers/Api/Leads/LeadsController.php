<?php

namespace App\Http\Controllers\Api\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use Carbon\Carbon;
use Exception;
use Helper;

class LeadsController extends Controller
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
            'tip_id' => 'required|exists:tips,id',
            'user_id' => 'nullable|exists:users,id',
            'email' => 'required|string|email|max:255',
            'mobile_no' => 'required|numeric',
            'segment' => 'required|string|in:Delivery,Intraday,Future,Option,Currency,Commodity,Boolean,NCD,IPOs,FDs'
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);
        }

        try {

            $data['tip_id'] = $request->tip_id ? : NULL;
            $data['user_id'] = $request->user_id ? : NULL;
            $data['email'] = $request->email ? : NULL;
            $data['mobile_no'] = $request->mobile_no ? : NULL;
            $data['segment'] = $request->segment ? : NULL;

            $create = Lead::create($data);
            if($create) {

                $response = [
                    'message' => 'Lead generate successfully.',
                    'data' => $data
                ];

            }else{
                $response = ['message' => 'Please try again.'];
            }

            return $this->outputJSON($response, 200);

        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }


}
