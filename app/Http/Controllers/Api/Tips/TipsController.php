<?php

namespace App\Http\Controllers\Api\Tips;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Plan;
use App\Models\PlanFeatured;
use App\Models\PlanPrice;
use App\Models\PlanSegment;
use App\Models\Tip;
use App\Models\TipPlan;
use App\Models\TipSegment;
use App\Models\TipsTarget;
use App\Models\Source;
use App\Models\TipMutualFund;
use App\User;
use App\Traits\Api\UserPlanTrait;
use Exception;
use Helper;
use Carbon\Carbon;

class TipsController extends Controller
{
	use UserPlanTrait;
	
    const SEGMENTNCD = 'NCD';
    const SEGMENTIPO = 'IPOs';
    const SEGMENTFD = 'FDs';
    const SEGMENTMF = 'MF'; //Mutual Fund (segment)

    protected $planTbl,
              $planSegmentTbl, 
              $tblTip, 
              $tblTipPlan, 
              $tblTipSegment, 
              $tblTipsTarget, 
              $sourceTbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->planTbl = new Plan;
        $this->planSegmentTbl = new PlanSegment;
        $this->tblTip = new Tip;
        $this->tblTipPlan = new TipPlan;
        $this->tblTipSegment = new TipSegment;
        $this->tblTipsTarget = new TipsTarget;
        $this->sourceTbl = new Source;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		
	
        $validator = Validator::make($request->only(['user_id']), [
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);
        }

        try {
            
			$segment = $request->segment;
			$tipName = $request->name;
			$createdAt = $request->created_date;
			$from = $request->from;
            $to = $request->to;

            $userID = $request->user_id;

            $userDetail = User::where('id', $userID)->first();
            $plan_id = $userDetail->plan_id ?? '';
            //Old query
            /*$tipsOld = $this->tblTip::when($segment, function ($query, $segment) {
                            return $query->where('segment', $segment);
							})->when($tipName, function ($query, $tipName) {
                            return $query->where('name','LIKE',"%{$tipName}%");
							})->tipdatestartto($from, $to)->latest()->get();*/

            $tips = $this->tblTip::join('tip_plans', 'tips.id', '=', 'tip_plans.tip_id')
                    ->leftJoin('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
                    ->select('tips.*', 'tip_plans.plan_id', 'tip_plans.tip_id as tip_id_plan', 'tips_targets.name as target_name', 'tips_targets.price as target_price', 'tips_targets.is_achieved as is_achieved_status')
                    ->when($plan_id, function ($query, $plan_id) {
                        return $query->where('tip_plans.plan_id', $plan_id)->where('tip_plans.is_status', true);
                    })
                    ->when($segment, function ($query, $segment) {
                        return $query->where('tips.segment', $segment);
                    })->when($tipName, function ($query, $tipName) {
                        return $query->where('tips.name','LIKE',"%{$tipName}%");
                    });
                    if(!empty($from) && !empty($to)) {
                        $from = Carbon::parse($from)->format('Y-m-d');
                        $to = Carbon::parse($to)->format('Y-m-d');
                        $tips->where(function($query) use ($from, $to) {
							//if($from!=$to) {
                        		return $query->whereBetween('tips.created_at', [$from, $to]);
								
							//}else{
								//return $query->whereDate('tips.created_at', $from);
							//}
                        });
                    }
                    //$tips = $tips->groupBy('tips_targets.tip_id')->get();
                    $tips = $tips->groupBy('tips.id')->get();
			
			
				//Get tips targets list end

				$planDetail = $this->userPlanDetail($userDetail);

				//Get tips guest for user
				if(count($tips)==0) {
					
					$tips = $this->tblTip::join('tip_plans', 'tips.id', '=', 'tip_plans.tip_id')
                    ->leftJoin('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
                    ->select('tips.*', 'tip_plans.plan_id', 'tip_plans.tip_id as tip_id_plan', 'tips_targets.name as target_name', 'tips_targets.price as target_price', 'tips_targets.is_achieved as is_achieved_status')
                    ->when($segment, function ($query, $segment) {
                        return $query->where('tips.segment', $segment);
                    })->when($tipName, function ($query, $tipName) {
                        return $query->where('tips.name','LIKE',"%{$tipName}%");
                    });
                    if(!empty($from) && !empty($to)) {
                        $from = Carbon::parse($from)->format('Y-m-d');
                        $to = Carbon::parse($to)->format('Y-m-d');
                        $tips->where(function($query) use ($from, $to) {
                        return $query->whereBetween('tips.created_at', [$from, $to]);
                        });
                    }
                    $tips = $tips->groupBy('tips.id')->get();
					
					$planArray['plan_name'] = $userDetail->plan->name ?? "";
					$planArray['plan_duration'] = $userDetail->userplanDetail->plan_duration ?? 0;
					$planArray['plan_price'] = $userDetail->userplanDetail->price ?? 0;
					$planArray['plan_expiry_date'] = $userDetail->userplanDetail->plan_expiry_date ?? "";				
					$planArray['isPlanActivated'] = false;

					$planDetail = $planArray;
				}

            $tipsArray = [];
            
            $responseStatus = 202;
            
            $apiStatus = false;
            
            $message = 'For the selected criteria Tips not found.';

            if(count($tips)) {$responseStatus = 200; $message = 'Tips lists.'; $apiStatus = true;}


            foreach ($tips as $key => $tip) {

                $tipsArray[$key]['tip_id'] = $tip->id;
                $tipsArray[$key]['segment'] = $tip->segment;
                $tipsArray[$key]['stock_name'] = $tip->name ?? '';
                $tipsArray[$key]['stock_price'] = number_format($tip->price, 2);
                $tipsArray[$key]['buy_range'] = $tip->buy_range;
                $tipsArray[$key]['stop_loss'] = $tip->stop_loss;
                $tipsArray[$key]['tip_date'] = Carbon::parse($tip->created_at)->format('d:m:Y');
                $tipsArray[$key]['remark'] = $tip->note ?? '';
                //Get tips targets list
                $tipsTipstargetsArray = [];

                $tipsTipstargets = DB::table('tips_targets')
                        ->where('tip_id', $tip->id)
                        ->get();

                foreach ($tipsTipstargets as $tipsTargetKey => $tipsTarget) {

                    $tipsTipstargetsArray[$tipsTargetKey]['target_name'] = $tipsTarget->name;
                    $tipsTipstargetsArray[$tipsTargetKey]['target_price'] = number_format($tipsTarget->price, 2);
                    $tipsTipstargetsArray[$tipsTargetKey]['is_achieved'] = $tipsTarget->is_achieved ? true : false;
                }

                $tipsArray[$key]['tips_targets'] = $tipsTipstargetsArray;
                //Get tips targets list end

            }
			
			
            //Get tips targets list end
            //$planDetail = $this->userPlanDetail($userDetail);
			if(count($tips)) {
				$sort = array();
				foreach($tipsArray as $k=>$v) {
					$sort['tip_id'][$k] = $v['tip_id'];
				}
            	array_multisort($sort['tip_id'], SORT_DESC,$tipsArray);
			}

            $response = [
                    'status' => $apiStatus,
                    'message' => $message,
                    'data' => [
                        'user_id' => $userDetail->id ?? 0,
                        'plan_detail' => $planDetail,
                        'tips_targets' => $tipsArray
                    ]
                ];

            return response()->json($response, $responseStatus);
        
        } catch (Exception $e) {

           return response()->json($e->getMessage(), 500); 
        }
    }
	
	public function userTipDetail(Request $request)
	{
		$validator = Validator::make($request->only(['user_id']), [ 
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) { 
            $response = [
                'message' => $validator->errors()->first(),
            ];
            return response()->json($response, 401);            
        }
		
		try {
			
			$userID = $request->user_id;
			
			$userDetail = User::where('id', $userID)->first();
            
            $keyword = $request->keyword;
			
			$segment = $request->segment;
			
            $plan_id = $userDetail->plan_id ?? '';

			$tips = DB::table('tips')
					->join('tip_plans', 'tips.id', '=', 'tip_plans.tip_id')
					->join('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
					->select('tips.*', 'tip_plans.plan_id', 'tip_plans.tip_id as tip_id_plan', 'tips_targets.name as target_name', 'tips_targets.price as target_price', 'tips_targets.is_achieved as is_achieved_status')
					->when($plan_id, function ($query, $plan_id) {
                        return $query->where('tip_plans.plan_id', $plan_id)->where('tip_plans.is_status', true);
                    })
                    ->when($keyword, function ($query, $keyword) {
                        return $query->where('tips.segment', $keyword);
                    })
					->groupBy('tips_targets.tip_id')
					->get();
			
			//Get tips targets list end

			$planDetail = $this->userPlanDetail($userDetail);
			
			//Get tips guest for user
			if(count($tips)==0) {
				
				$tips = DB::table('tips')
						->join('tip_plans', 'tips.id', '=', 'tip_plans.tip_id')
						->join('tips_targets', 'tips.id', '=', 'tips_targets.tip_id')
						->select('tips.*', 'tip_plans.plan_id', 'tip_plans.tip_id as tip_id_plan', 'tips_targets.name as target_name', 'tips_targets.price as target_price', 'tips_targets.is_achieved as is_achieved_status')
						->when($keyword, function ($query, $keyword) {
							return $query->where('tips.segment', $keyword);
						})
						->groupBy('tips_targets.tip_id')
						->get();
				
				$planArray['plan_name'] = $userDetail->plan->name ?? "";
				$planArray['plan_duration'] = $userDetail->userplanDetail->plan_duration ?? 0;
				$planArray['plan_price'] = $userDetail->userplanDetail->price ?? 0;
				$planArray['plan_expiry_date'] = $userDetail->userplanDetail->plan_expiry_date ?? "";				
				$planArray['isPlanActivated'] = false;
				
				$planDetail = $planArray;
			}

			$tipsArray = [];
				
			foreach ($tips as $key => $tip) {

				$tipsArray[$key]['tip_id'] = $tip->id;
				$tipsArray[$key]['segment'] = $tip->segment;
				if(!empty($tip->name)) {
					$tipsArray[$key]['stock_name'] = $tip->name ?? '';
				}else{
					$tipsArray[$key]['stock_name'] =  $tip->symbol ?? '';
				}
				
				$tipsArray[$key]['stock_price'] = number_format($tip->price, 2);
				//$tipsArray[$key]['source'] = $tip->source->name ?? '';
				$tipsArray[$key]['buy_range'] = $tip->buy_range;
				$tipsArray[$key]['stop_loss'] = $tip->stop_loss;
				$tipsArray[$key]['tip_date'] = Carbon::parse($tip->created_at)->format('d:m:Y');
				$tipsArray[$key]['remark'] = $tip->note ?? '';
				
				//Get tips targets list
				$tipsTipstargetsArray = [];

				$tipsTipstargets = DB::table('tips_targets')
						->where('tip_id', $tip->id)
						->get();

				foreach ($tipsTipstargets as $tipsTargetKey => $tipsTarget) {

					$tipsTipstargetsArray[$tipsTargetKey]['target_name'] = $tipsTarget->name;
					$tipsTipstargetsArray[$tipsTargetKey]['target_price'] = number_format($tipsTarget->price, 2);
					$tipsTipstargetsArray[$tipsTargetKey]['is_achieved'] = $tipsTarget->is_achieved ? true : false;
				}

				$tipsArray[$key]['tips_targets'] = $tipsTipstargetsArray;
				//Get tips targets list end
			}
				
    		/*$tipPlans = $this->tblTipPlan::where('plan_id', $userDetail->plan_id)->where('is_status', true)->get();

    		$tipId = 7; //$tipPlanDetail->tip_id ?? '';

    		//Get tips targets list
    		$tipsTipstargetsArray = [];

    		$tipsTipstargets = $this->tblTipsTarget::where('tip_id', $tipId)->get();

    		foreach ($tips as $tipsTargetKey => $tipsTarget) {

    			$tipsTipstargetsArray[$tipsTargetKey]['tip_date'] = Carbon::parse($tipsTarget->created_at)->format('d:m:Y');
    			$tipsTipstargetsArray[$tipsTargetKey]['stock_name'] = $tipsTarget->name ?? '';
    			$tipsTipstargetsArray[$tipsTargetKey]['stock_symbol'] = $tipsTarget->symbol ?? '';
    			$tipsTipstargetsArray[$tipsTargetKey]['buy_range'] = $tipsTarget->buy_range ?? '';
    			$tipsTipstargetsArray[$tipsTargetKey]['stop_loss'] = $tipsTarget->stop_loss ?? '';
    			$tipsTipstargetsArray[$tipsTargetKey]['target_name'] = $tipsTarget->target_name;
    			$tipsTipstargetsArray[$tipsTargetKey]['target_price'] = number_format($tipsTarget->target_price, 2);
    			$tipsTipstargetsArray[$tipsTargetKey]['is_achieved'] = $tipsTarget->is_achieved_status ? true : false;
    		}*/

    		
			
			
			if(count($tips)) {
			
				$sort = array();
				foreach($tipsArray as $k=>$v) {
					$sort['tip_id'][$k] = $v['tip_id'];
				}
				array_multisort($sort['tip_id'], SORT_DESC,$tipsArray);
			}
    		$response = [
    			'message' => 'Tip detail.',
    			'data' => [
    				'user_id' => $userDetail->id ?? 0,
    				//'plan_id' => $userDetail->plan_id ?? '',
    				'plan_detail' => $planDetail,
    				'tips_targets' => $tipsArray
    			]
    		];

    		return response()->json($response, 200);


        
        } catch (Exception $e) {

           return response()->json($e->getMessage(), 500); 
        }
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tipNcd(Request $request)
    {
        try {

            $tipName = $request->name;
			$createdAt = $request->created_date;
			$from = $request->start;
            $to = $request->to;
            $tips = $this->tblTip::with(['ncdInvestments'])->where('segment', self::SEGMENTNCD)->when($tipName, function ($query, $tipName) {
                                        return $query->where('name','LIKE',"%{$tipName}%");
                                    })->tipdatestartto($from, $to)->latest()->get();

            $tipsArray = [];

            $responseStatus = 202;

            $apiStatus = false;

            $message = 'Tips not found.';

            if(count($tips)) {$responseStatus = 200; $message = 'Tips lists.'; $apiStatus = true;}


            foreach ($tips as $key => $tip) {

                $tipsArray[$key]['tip_id'] = $tip->id;
                $tipsArray[$key]['segment'] = $tip->segment;

                //Get tips plan list
                $tipplansArray = [];

                foreach ($tip->tipsPlans as $tipsPlanKey => $tipsPlan) {
                    $tipplansArray[$tipsPlanKey]['plan_name'] = $tipsPlan->planDetail->name ?? '';
                    $tipplansArray[$tipsPlanKey]['plan_tip_status'] = $tipsPlan->is_status ? true : false;
                }

                $tipsArray[$key]['tipPlans'] = $tipplansArray;
                //Get tips plan list end

                $tipsArray[$key]['bond_title'] = $tip->name;
                $tipsArray[$key]['type_of_bond'] = $tip->type_of_bond ?? '';
                $tipsArray[$key]['unit_price'] = number_format($tip->unit_price, 2);
                $tipsArray[$key]['rating'] = $tip->rating ?? '';
				$tipsArray[$key]['remark'] = $tip->note ?? '';
                //Get tips ncdInvestments list
                $tipsncdInvestmentsArray = [];

                foreach ($tip->ncdInvestments as $tipsNcdKey => $tipsNcd) {

                    $tipsncdInvestmentsArray[$tipsNcdKey]['investment'] = $tipsNcd->investment ?? '';
                    $tipsncdInvestmentsArray[$tipsNcdKey]['duration'] = $tipsNcd->duration ?? '';
                    $tipsncdInvestmentsArray[$tipsNcdKey]['maturity_amount'] = number_format($tipsNcd->maturity_amount, 2);
                }

                $tipsArray[$key]['ncdInvestments'] = $tipsncdInvestmentsArray;
                //Get tips ncdInvestments list end


            }

			if(count($tips)) {	
			  $sort = array();
			foreach($tipsArray as $k=>$v) {
				$sort['tip_id'][$k] = $v['tip_id'];
			}
            array_multisort($sort['tip_id'], SORT_DESC,$tipsArray);
			}
            $response = [
                    'status' => $apiStatus,
                    'message' => $message,
                    'data' => $tipsArray
                ];

            return response()->json($response, $responseStatus);

        } catch (Exception $e) {

           return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tipIpo(Request $request)
    {
        try {

            $tipName = $request->name;
            $createdAt = $request->created_date;
			$from = $request->start;
            $to = $request->to;
            $tips = $this->tblTip::with(['tipsPlans'])->where('segment', self::SEGMENTIPO)->when($tipName, function ($query, $tipName) {
                                        return $query->where('name','LIKE',"%{$tipName}%");
                                    })->tipdatestartto($from, $to)->latest()->get();

            $tipsArray = [];

            $responseStatus = 202;

            $apiStatus = false;

            $message = 'Tips not found.';

            if(count($tips)) {$responseStatus = 200; $message = 'Tips lists.'; $apiStatus = true;}


            foreach ($tips as $key => $tip) {

                $tipsArray[$key]['tip_id'] = $tip->id;
                $tipsArray[$key]['segment'] = $tip->segment;

                //Get tips plan list
                $tipplansArray = [];

                foreach ($tip->tipsPlans as $tipsPlanKey => $tipsPlan) {
                    $tipplansArray[$tipsPlanKey]['plan_name'] = $tipsPlan->planDetail->name ?? '';
                    $tipplansArray[$tipsPlanKey]['plan_tip_status'] = $tipsPlan->is_status ? true : false;
                }

                $tipsArray[$key]['tipPlans'] = $tipplansArray;
                //Get tips plan list end

                $tipsArray[$key]['stock_name'] = $tip->name;
                $tipsArray[$key]['price_range'] = $tip->ipo_price_range ?? '';
                $tipsArray[$key]['ipo_open_date'] = $tip->ipo_open_date ?? '';
                $tipsArray[$key]['ipo_close_date'] = $tip->ipo_open_date ?? '';
                $tipsArray[$key]['buy'] = $tip->ipo_buy_range ?? '';
                $tipsArray[$key]['avoid'] = $tip->ipo_avoid_range ?? '';
                $tipsArray[$key]['rating'] = $tip->rating ?? '';
				$tipsArray[$key]['remark'] = $tip->note ?? '';
            }

			if(count($tips)) {
			  $sort = array();
			foreach($tipsArray as $k=>$v) {
				$sort['tip_id'][$k] = $v['tip_id'];
			}
				
            array_multisort($sort['tip_id'], SORT_DESC,$tipsArray);
			
			}
            $response = [
                    'status' => $apiStatus,
                    'message' => $message,
                    'data' => $tipsArray
                ];

            return response()->json($response, $responseStatus);

        } catch (Exception $e) {

           return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tipFds(Request $request)
    {
        try {

            $tipName = $request->name;
			$createdAt = $request->created_date;
			$from = $request->start;
            $to = $request->to;
            $tips = $this->tblTip::with(['tipsPlans'])->where('segment', self::SEGMENTFD)->when($tipName, function ($query, $tipName) {
                                        return $query->where('name','LIKE',"%{$tipName}%");
                                    })->tipdatestartto($from, $to)->latest()->get();

            $tipsArray = [];

            $responseStatus = 202;

            $apiStatus = false;

            $message = 'Tips not found.';

            if(count($tips)) {$responseStatus = 200; $message = 'Tips lists.'; $apiStatus = true;}

            $p = $request->p; //principal amount
            $t = $request->t; //days

            foreach ($tips as $key => $tip) {

                $tipsArray[$key]['tip_id'] = $tip->id;
                $tipsArray[$key]['segment'] = $tip->segment;

                //Get tips plan list
                $tipplansArray = [];

                foreach ($tip->tipsPlans as $tipsPlanKey => $tipsPlan) {
                    $tipplansArray[$tipsPlanKey]['plan_name'] = $tipsPlan->planDetail->name ?? '';
                    $tipplansArray[$tipsPlanKey]['plan_tip_status'] = $tipsPlan->is_status ? true : false;
                }

                $tipsArray[$key]['tipPlans'] = $tipplansArray;
                //Get tips plan list end

                $tipsArray[$key]['stock_name'] = $tip->name;
                $tipsArray[$key]['interest'] = $interest = $tip->interest ?? '';
                $tipsArray[$key]['minimum_days'] = $tip->start_year ?? '';
                $tipsArray[$key]['maximum_days'] = $tip->end_year ?? '';
                $tipsArray[$key]['rating'] = $tip->rating ?? '';
				$tipsArray[$key]['remark'] = $tip->note ?? '';
                //Calculation (M = P + (P x r x t/100)
                //days convent to year
                if(is_numeric($t) && is_numeric($p)) {
                    $year = intval($t / 365);
                    $m = $p+($p*$interest*$year/100);
                    $tipsArray[$key]['m'] = number_format($m, 2);
                }else{
                    $tipsArray[$key]['m'] = '';
                }
            }

            $response = [
                    'status' => $apiStatus,
                    'message' => $message,
                    'data' => $tipsArray
                ];

            return response()->json($response, $responseStatus);

        } catch (Exception $e) {

           return response()->json($e->getMessage(), 500);
        }
    }

    private function mfApiCal($tipId, $date)
    {
        return DB::table('mf_apis')->where('tip_id', $tipId)->whereDate('mutual_nav_date', '<=', $date)->first();
        //return TipMutualFund::whereJsonContains('mf_api', ['data' => ['date' => '07-06-2020']])->first();
        //return TipMutualFund::whereJsonContains('mf_api', ['data' => ['date' => $date]])->first();
        //echo Carbon::now()->subMonth()->format('Y-m-d');
        //exit();
        //return TipMutualFund::whereJsonContains('mf_api', ['data' => ['date' => '24-05-2021']])->first();
    }

    private function mfApiPer($tipId, $date)
    {
        $mfApiDetail = TipMutualFund::select('mf_api')->where('tip_id', $tipId)->first();

        $obj = json_decode($mfApiDetail->mf_api);

        $price = 0;

        $currentDate = strtotime(Carbon::now()->subMonth()->format('d-m-Y'));

        foreach ($obj->data as $key => $value) {
            //if(Carbon::now()->subMonth()->format('d-m-Y')==$value->date) {
            if(strtotime($date)<=strtotime($value->date)) {
                $price = $value->nav;
                //$price = $value;
            }
        }

        return $price;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function tipmutualFund(Request $request)
    {
        //try {

            $mfApi = $request->mf_api;

            $tipName = $request->name;
			
			$createdAt = $request->created_date;
			$from = $request->start;
            $to = $request->to;
            $tips = $this->tblTip::with(['tipsPlans', 'tipmutualfunds'])
                        ->select('id', 'name', 'price', 'segment', 'rating', 'is_achieved', 'note')
                        ->searchbymfapi($mfApi)
                        ->where('segment', self::SEGMENTMF)
                        ->when($tipName, function ($query, $tipName) {
                            return $query->where('name','LIKE',"%{$tipName}%");
                        })->tipdatestartto($from, $to)->latest()->get();

            $tipsArray = [];

            $responseStatus = 200;

            $apiStatus = false;

            $message = 'Tips not found.';

            if(count($tips)) {$responseStatus = 200; $message = 'Tips lists.'; $apiStatus = true;}

            foreach ($tips as $key => $tip) {

                $net_asset_value = $tip->tipmutualfunds->net_asset_value;

                $mf_api_one_month = $this->mfApiPer($tip->id, Carbon::now()->subMonth()->format('d-m-Y'));
                $mf_api_three_month = $this->mfApiPer($tip->id, Carbon::now()->subMonths(3)->format('Y-m-d'));
                $mf_api_six_month = $this->mfApiPer($tip->id, Carbon::now()->subMonths(6)->format('Y-m-d'));
                $mf_api_one_year = $this->mfApiPer($tip->id, Carbon::now()->subYear()->format('Y-m-d'));
                $mf_api_three_year = $this->mfApiPer($tip->id, Carbon::now()->subYears(3)->format('Y-m-d'));
                $mf_api_five_year = $this->mfApiPer($tip->id, Carbon::now()->subYears(5)->format('Y-m-d'));

                $m = 0;
                $mThree = 0;
                $mSix = 0;
                $mOneYear = 0;
                $mThreeYear = 0;
                $mFiveYear = 0;

                if(!empty($mf_api_one_month)) {
                    $m = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_one_month)/intval($mf_api_one_month);
                }
                if(!empty($mf_api_three_month)) {
                    $mThree = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_three_month)/intval($mf_api_three_month);
                }
                if(!empty($mf_api_six_month)) {
                    $mSix = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_six_month)/intval($mf_api_six_month);
                }
                if(!empty($mf_api_one_year)) {
                    $mOneYear = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_one_year)/intval($mf_api_one_year);
                }
                if(!empty($mf_api_three_year)) {
                    $mThreeYear = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_three_year)/intval($mf_api_three_year);
                }
                if(!empty($mf_api_five_year)) {
                    $mFiveYear = 100*($tip->tipmutualfunds->net_asset_value-$mf_api_five_year)/intval($mf_api_five_year);
                }
                $tipsArray[$key]['tip_id'] = $tip->id;
                $tipsArray[$key]['segment'] = $tip->segment;
                $tipsArray[$key]['one_month_percentage'] = '%'.number_format($m, 3);
                $tipsArray[$key]['three_month_percentage'] = '%'.number_format($mThree, 3);
                $tipsArray[$key]['six_month_percentage'] = '%'.number_format($mSix, 3);
                $tipsArray[$key]['one_year_percentage'] = '%'.number_format($mOneYear, 3);
                $tipsArray[$key]['three_year_percentage'] = '%'.number_format($mThreeYear, 3);
                $tipsArray[$key]['five_year_percentage'] = '%'.number_format($mFiveYear, 3);

                //Get tips plan list
                $tipplansArray = [];

                foreach ($tip->tipsPlans as $tipsPlanKey => $tipsPlan) {
                    $tipplansArray[$tipsPlanKey]['plan_name'] = $tipsPlan->planDetail->name ?? '';
                    $tipplansArray[$tipsPlanKey]['plan_tip_status'] = $tipsPlan->is_status ? true : false;
                }

                $tipsArray[$key]['tipPlans'] = $tipplansArray;
                //Get tips plan list end

                $tipsArray[$key]['stock_name'] = $tip->name;
                $tipsArray[$key]['fund_type'] = $tip->tipmutualfunds->caps_type ?? '';
                $tipsArray[$key]['purpose'] = $tip->tipmutualfunds->purpose ?? '';
                $tipsArray[$key]['scheme_code'] = $tip->tipmutualfunds->scheme_code ?? '';
                $tipsArray[$key]['isin_div_payout_isin_growth'] = $tip->tipmutualfunds->isin_div_payout_isin_growth ?? '';
                $tipsArray[$key]['isin_div_reinvestment'] = $tip->tipmutualfunds->isin_div_reinvestment ?? '';
                $tipsArray[$key]['scheme_name'] = $tip->tipmutualfunds->scheme_name ?? '';
                $tipsArray[$key]['net_asset_value'] = $tip->tipmutualfunds->net_asset_value ?? '';
                $tipsArray[$key]['mutual_date'] = $tip->tipmutualfunds->mutual_date ?? '';
                $tipsArray[$key]['scheme_type'] = $tip->tipmutualfunds->scheme_type ?? '';
                $tipsArray[$key]['scheme_category'] = $tip->tipmutualfunds->scheme_category ?? '';
                $tipsArray[$key]['mutual_fund_family'] = $tip->tipmutualfunds->mutual_fund_family ?? '';
                $tipsArray[$key]['rating'] = $tip->rating ?? '';
				$tipsArray[$key]['remark'] = $tip->note ?? '';
            }

		if(count($tips)) {
		  $sort = array();
			foreach($tipsArray as $k=>$v) {
				$sort['tip_id'][$k] = $v['tip_id'];
			}
            array_multisort($sort['tip_id'], SORT_DESC,$tipsArray);
		}
            $response = [
                    'status' => $apiStatus,
                    'message' => $message,
                    'data' => $tipsArray
                ];

            return response()->json($response, $responseStatus);

        //} catch (Exception $e) {

           //return response()->json($e->getMessage(), 500);
        //}
    }

}
