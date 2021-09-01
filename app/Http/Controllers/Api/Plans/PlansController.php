<?php

namespace App\Http\Controllers\Api\Plans;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Models\Plan;
use App\Models\PlanFeatured;
use App\Models\PlanPrice;
use App\Models\PlanSegment;
use Exception;
use Helper;

class PlansController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $planFeaturedImagePath = public_path('uploads/plan_featured');

        $plans = Plan::latest()->get();

        $plansArray = [];
        

        $responseStatus = 202;
        $apiStatus = false;
        
        $message = 'Plans not found.';

        if(count($plans)) {$responseStatus = 200; $message = 'Plans lists.'; $apiStatus = true;}


        foreach ($plans as $key => $plan) {

            $plansArray[$key]['plan_id'] = $plan->id;
            $plansArray[$key]['plan_name'] = $plan->name;
            $plansArray[$key]['daily_tips_limit'] = $plan->daily_tips_limit;

            $planpricesArray = [];

            foreach ($plan->apiPlanPrices as $planPricesKey => $planprice) {
                //$planpricesArray[$planPricesKey]['plan_month'] = $planprice->plan_month;
                $planpricesArray[$planPricesKey]['plan_month'] = config('constants.planMonths')[$planprice->plan_month] ?? '';
                $planpricesArray[$planPricesKey]['price'] = $planprice->price;
                $planpricesArray[$planPricesKey]['regular_price'] = $planprice->regular_price ? : 0;
                //$planpricesArray[$planPricesKey]['months'] = config('constants.planMonths')[$planprice->plan_month] ?? '';
            }

            $plansArray[$key]['planprices'] = $planpricesArray;

            $planFeaturedArray = [];
            
            foreach ($plan->planfeatureds as $planfeaturedsKey => $planfeatured) {
                
                $planFeaturedArray[$planfeaturedsKey]['name'] = $planfeatured->name;

                $planfeaturedImage = url('public/images/check.png');

                if(!empty($planfeatured->image) && File::exists($planFeaturedImagePath.'/'.$planfeatured->image)){

                    $planfeaturedImage = url('public/uploads/plan_featured/'.$planfeatured->image);
                }

                $planFeaturedArray[$planfeaturedsKey]['image'] = $planfeaturedImage;
            }

            $plansArray[$key]['planfeatureds'] = $planFeaturedArray;
        }

        $response = [
                'status' => $apiStatus,
                'message' => $message,            
                'data' => $plansArray
            ];

        return response()->json($response, $responseStatus);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
