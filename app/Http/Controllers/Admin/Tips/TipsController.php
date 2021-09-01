<?php

namespace App\Http\Controllers\Admin\Tips;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Traits\Admin\FormButtons;
use App\Traits\UploadTrait;
use App\Traits\Admin\AuthorizationsTrait;
use Exception;
use Carbon\Carbon;
use Helper;
use App\Http\Requests\Admin\Tips\StoreTip;
use App\Models\Plan;
use App\Models\PlanFeatured;
use App\Models\PlanPrice;
use App\Models\PlanSegment;
use App\Models\Tip;
use App\Models\TipPlan;
use App\Models\TipSegment;
use App\Models\TipsTarget;
use App\Models\Source;
use App\Models\TipNcdInvesment;
use App\Models\TipMutualFund;
use App\Models\Notification;
use App\Models\UserPlan;
use App\Models\UserDevice;
use App\Traits\FcmTrait;

class TipsController extends Controller
{
    use FormButtons, UploadTrait, AuthorizationsTrait, FcmTrait;

    protected $planTbl, $planSegmentTbl, $tblTip, $tblTipPlan, $tblTipSegment, $tblTipsTarget, $sourceTbl, $ncdInvesmentTbl, $tblTipMutualFund;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->planTbl = new Plan;
        $this->planSegmentTbl = new PlanSegment;
        $this->tblTip = new Tip;
        $this->tblTipPlan = new TipPlan;
        $this->tblTipSegment = new TipSegment;
        $this->tblTipsTarget = new TipsTarget;
        $this->sourceTbl = new Source;
        $this->ncdInvesmentTbl = new TipNcdInvesment;
        $this->tblTipMutualFund = new TipMutualFund;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Tips', 'Manage Tips');

        if(request()->ajax()) {

            ## Read value
            $draw = request('draw');

            $start = request('start');

            $rowperpage = request('length'); // Rows display per page

            $columnIndex = request('order.0.column'); // Column index

            //$columnName = request('columns')[$columnIndex]['data']; // Column name

            $columnSortOrder = request('order.0.dir'); // asc or desc

            $searchValue = request('search.value'); // Search value

            $segment = request('segment');
            $from = request('from');
            $to = request('to');
            //$tipsDate = [$from, $to];
            $tbl = $this->tblTip;

            ## Total number of records without filtering
            $totalRecords = $tbl->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = $tbl->tipsegment($segment)->tipdatestartto($from, $to)->count();

            ## Fetch records
            $tips = $tbl->tipsegment($segment)->tipdatestartto($from, $to)->offset($start)
                            ->limit($rowperpage)
                            ->latest()
                            ->get(['id', 'name', 'segment', 'price', 'buy_range', 'stop_loss', 'created_by']);

            $data = array();

            $i = $start;

            foreach($tips as $key => $row) {

                $i++;

                /*$actions = $this->actionsButtons([
                                ['key' => 'Edit', 'link' => route('admin.tips.edit', $row->id)],
                                ['key' => 'Delete', 'link' => route('admin.tips.delete', [$row->id])]
                            ]);*/
                //Check authorization
                $actions = '';
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_edit'))
                {
                    $actions .= Helper::getButtons([['key' => 'Edit', 'link' => route('admin.tips.edit', $row->id)]]);
                }
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_delete'))
                {
                    $actions .= Helper::getButtons([['key' => 'Delete', 'link' => route('admin.tips.delete', [$row->id])]]);
                }

                $data[$key]['id'] = $i;
                $data[$key]['name'] = $row->name;
                $data[$key]['segment'] = $row->segment;
                //$data[$key]['buy_range'] = $row->buy_range;
                //$data[$key]['stop_loss'] = $row->stop_loss;
                $data[$key]['created_by'] = $row->adminDetail->name ?? '';
                $data[$key]['action'] = $actions;
            }

            ## Response
            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordwithFilter,
                "aaData" => $data
            );

            echo json_encode($response);

            exit();
        }

        $renderButtons = $this->addButtons(route('admin.tips.create'), 'manage-tips');

        $segments = $this->planTbl->segments();

        return view('admin.tips.index', compact('renderButtons', 'segments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Tips', 'Create Tips');

        $data['renderButtons'] = $this->addFormButtons(route('admin.tips.index'));

        $data['backButtons'] = $this->backButtons(route('admin.tips.index'));

        $data['segments'] = $this->planTbl->segments();

        $data['sources'] = $this->sourceTbl::latest()->get(['id', 'name']);

        return view('admin.tips.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_add'))
        {
            return abort(403);
        }

        try {

            if(empty($request->tipsplans)) {
                return redirect()->back()->with('error', 'Select plan.');
            }

            $data['source_id'] = $request->source ? : NULL;
            if($request->segment=='MF') {
            $data['name'] = $request->mf_stock_name ? : NULL;
            }elseif($request->segment=='Currency') {
            $data['name'] = $request->currency ? : NULL;
			}else{
            $data['name'] = $request->stock_name ? : NULL;
            }
			if($request->segment=='Currency') {
            $data['currency_name'] = $request->currency ? : NULL;
            }
            $data['price'] = $request->price ? : NULL;
            $data['buy_range'] = $request->buy_range ? : NULL;
            $data['stop_loss'] = $request->stop_loss ? : NULL;
            $data['segment'] = $segment = $request->segment ? : NULL;
            $data['tipsplans'] = $tipsplans = serialize($request->tipsplans);
            $symbol = $request->symbol;
            $symbolVal = Str::upper($request->symbolVal);
            if($request->symbolcommodity) {
				$data['symbol'] = $request->symbolcommodity;
			}else{
				$data['symbol'] = $symbol;
			}
            $data['symbols'] = $symbol.'.'.$symbolVal;
            if($segment=='NCD') {
            $data['type_of_bond'] = $request->type_of_bond ? : NULL;
            $data['rating'] = $request->ncd_rating ? : NULL;
            $data['unit_price'] = $request->unit_price ? : NULL;
            }
            if($segment=='FDs') {
            $data['rating'] = $request->fds_rating ? : NULL;
            $data['end_year'] = $request->end_year ? : NULL;
            $data['start_year'] = $request->start_year ? : NULL;
            $data['interest'] = $request->interest ? : NULL;
            }
            if($segment=='IPOs') {
            $data['rating'] = $request->ipo_rating ? : NULL;
            $data['ipo_price_range'] = $request->price_range ? : NULL;
            $data['ipo_open_date'] = $request->ipo_open_date ? : NULL;
            $data['ipo_close_date'] = $request->ipo_close_date ? : NULL;
            $data['ipo_buy_range'] = $request->ipo_buy_range ? : NULL;
            $data['ipo_avoid_range'] = $request->ipo_avoid_range ? : NULL;
            }
            if($segment=='MF') {
            $data['rating'] = $request->mf_rating ? : NULL;
            }
            $data['note'] = $request->note ? : NULL;
            $data['created_by'] = $this->adminId();
            $data['updated_by'] = $this->adminId();

            $create = $this->tblTip::create($data);

            if($create) {

                //Get last insert tip id
                $tipId = $create->id;

                //add m f detail
                if($segment=='MF') {
                    $mfData['tip_id'] = $tipId;
                    $mfData['caps_type'] = $request->caps_type ? : NULL;
                    $mfData['purpose'] = $request->purpose ? : NULL;
                    $mfData['scheme_code'] = $request->scheme_code ? : NULL;
                    $mfData['isin_div_payout_isin_growth'] = $request->isin_div_payout_isin_growth ? : NULL;
                    $mfData['isin_div_reinvestment'] = $request->isin_div_reinvestment ? : NULL;
                    $mfData['scheme_name'] = $request->mutual_scheme_name ? : NULL;
                    $mfData['net_asset_value'] = $request->net_asset_value ? : NULL;
                    $mfData['mutual_date'] = $request->date ? : NULL;
                    if(!empty($request->date)) {
                        $mutual_nav_date = Carbon::parse($request->date)->format('Y-m-d');
                        $mfData['mutual_nav_date'] = $mutual_nav_date ? : NULL;
                    }
                    $mfData['scheme_type'] = $request->scheme_type ? : NULL;
                    $mfData['scheme_category'] = $request->scheme_category ? : NULL;
                    $mfData['mutual_fund_family'] = $request->mutual_fund_family ? : NULL;
                    $mfData['mf_api'] = $request->mf_api ? : NULL;
                    $this->tblTipMutualFund::create($mfData);
                }
                //Add tips segments detail
                $segments = $request->segments;

                if(is_array($segments)) {
                    //$this->tblTipSegment->createSegment($segments, $tipId);
                }

                //Add tips targets detail
                $target_names = $request->target_names;
                $target_prices = $request->target_prices;
                if(is_array($target_names) && is_array($target_prices)) {
                    $this->tblTip->createTarget($target_names, $target_prices, $tipId);
                }

                //Add tips plans detail.
                $this->addTipPlan($tipsplans, $tipId);
                /*$plans = $request->plans;
                if(is_array($plans)) {
                    $this->tblTip->createPlan($plans, $tipId);
                }*/

                //Add ncd invesment detail
                $ncdInvestment = $request->ncd_investment;
                $ncdDuration = $request->ncd_duration;
                $ncdMaturityAmount = $request->ncd_maturity_amount;

                if(is_array($ncdInvestment) && is_array($ncdDuration) && is_array($ncdMaturityAmount)) {

                    foreach ($ncdInvestment as $ncdInvestmentKey => $ncdInvestmentValue) {

                        if(!empty($ncdInvestmentValue) && !empty($ncdDuration[$ncdInvestmentKey]) && !empty($ncdMaturityAmount[$ncdInvestmentKey])) {

                            $this->ncdInvesmentTbl::create([
                                'tip_id' => $tipId,
                                'investment' => $ncdInvestmentValue,
                                'duration' => $ncdDuration[$ncdInvestmentKey],
                                'maturity_amount' => $ncdMaturityAmount[$ncdInvestmentKey]
                            ]);
                        }
                    }
                }
                return redirect()->route('admin.tips.index')->with('success', 'Tips created successfully.');
            }

            return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_edit'))
        {
            return abort(403);
        }

        $data['tip'] = $this->tipFindById($id);

        $this->setPageTitle('Manage Tips', 'Edit Tips Detail');

        $data['renderButtons'] = $this->editFormButtons(route('admin.tips.index'));

        $data['backButtons'] = $this->backButtons(route('admin.tips.index'));

        $data['segments'] = $this->planTbl->segments();

        $data['sources'] = $this->sourceTbl::latest()->get(['id', 'name']);

        return view('admin.tips.edit', $data);
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
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_edit'))
        {
            return abort(403);
        }

        if(empty($request->tipsplans)) {
            return redirect()->back()->with('error', 'Select plan.');
        }

        $tipDetail = $this->tipFindById($id);

        //Validation request data
        /*$request->validate([
            'segment' => 'required|string',
            'stock_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'buy_range' => 'required|numeric',
            'buy_range' => 'required|numeric'
        ]);*/

        //try {
            //dd($request->segment);
            $data['source_id'] = $request->source ? : NULL;
            if($request->segment=='MF') {
            $data['name'] = $tipName = $request->mf_stock_name ? : NULL;
            }elseif($request->segment=='Currency') {
            $data['name'] = $tipName = $request->currency ? : NULL;
			}else{
            $data['name'] = $tipName = $request->stock_name ? : NULL;
            }
			if($request->segment=='Currency') {
            $data['currency_name'] = $request->currency ? : NULL;
            }else{
            $data['currency_name'] = NULL;
            }
            $data['price'] = $request->price ? : NULL;
            $data['buy_range'] = $request->buy_range ? : NULL;
            $data['stop_loss'] = $request->stop_loss ? : NULL;
            $data['segment'] = $segment = $request->segment ? : NULL;
            $data['tipsplans'] = $tipsplans = serialize($request->tipsplans);
            $symbol = $request->symbol;
            $symbolVal = Str::upper($request->symbolVal);
			if($request->symbolcommodity) {
				$data['symbol'] = $request->symbolcommodity;
			}else{
				$data['symbol'] = $symbol;
			}
            
            $data['symbols'] = $symbol.'.'.$symbolVal;
            if($segment=='NCD') {
            $data['type_of_bond'] = $request->type_of_bond ? : NULL;
            $data['rating'] = $request->ncd_rating ? : NULL;
            $data['unit_price'] = $request->unit_price ? : NULL;
            }
            if($segment=='FDs') {
            $data['rating'] = $request->fds_rating ? : NULL;
            $data['end_year'] = $request->end_year ? : NULL;
            $data['start_year'] = $request->start_year ? : NULL;
            $data['interest'] = $request->interest ? : NULL;
            }
            if($segment=='IPOs') {
            $data['rating'] = $request->ipo_rating ? : NULL;
            $data['ipo_price_range'] = $request->price_range ? : NULL;
            $data['ipo_open_date'] = $request->ipo_open_date ? : NULL;
            $data['ipo_close_date'] = $request->ipo_close_date ? : NULL;
            $data['ipo_buy_range'] = $request->ipo_buy_range ? : NULL;
            $data['ipo_avoid_range'] = $request->ipo_avoid_range ? : NULL;
            }
            if($segment=='MF') {
            $data['rating'] = $request->mf_rating ? : NULL;
            }
			$data['note'] = $request->note ? : NULL;
            $data['updated_by'] = $this->adminId();

            //Update tip detail
            $tipDetail->update($data);


            //Get last insert tip id
            $tipId = $id;

            $this->tblTipMutualFund::where('tip_id', $tipId)->delete();
            //add m f detail
            if($segment=='MF') {
                $mfData['tip_id'] = $tipId;
                $mfData['caps_type'] = $request->caps_type ? : NULL;
                $mfData['purpose'] = $request->purpose ? : NULL;
                $mfData['scheme_code'] = $request->scheme_code ? : NULL;
                $mfData['isin_div_payout_isin_growth'] = $request->isin_div_payout_isin_growth ? : NULL;
                $mfData['isin_div_reinvestment'] = $request->isin_div_reinvestment ? : NULL;
                $mfData['scheme_name'] = $request->mutual_scheme_name ? : NULL;
                $mfData['net_asset_value'] = $request->net_asset_value ? : NULL;
                $mfData['mutual_date'] = $request->date ? : NULL;
                if(!empty($request->date)) {
                    $mutual_nav_date = Carbon::parse($request->date)->format('Y-m-d');
                    $mfData['mutual_nav_date'] = $mutual_nav_date ? : NULL;
                }
                $mfData['scheme_type'] = $request->scheme_type ? : NULL;
                $mfData['scheme_category'] = $request->scheme_category ? : NULL;
                $mfData['mutual_fund_family'] = $request->mutual_fund_family ? : NULL;
                $mfData['mf_api'] = $request->mf_api ? : NULL;
                $this->tblTipMutualFund::create($mfData);
            }
            //Add tips segments detail
            $segments = $request->segments;

            //$this->tblTipSegment::where(['tip_id' => $tipId])->delete();

            if(is_array($segments)) {
                //$this->tblTipSegment->createSegment($segments, $tipId);
            }

            //Add tips plans detail.
            //$this->addTipPlan($tipsplans, $id);
		
			TipPlan::where('tip_id', $id)->delete();
		
		
			//Get Segment detail.
			$segmentDetail = Tip::select('segment')->where('id', $id)->first();

			$tipsplans = unserialize($tipsplans);

			if(!empty($tipsplans['plan_id'])) {

				foreach ($tipsplans['plan_id'] as $key=> $value) {

					$tipPlanStatus = false;

					if(isset($tipsplans['plan_status'][$key])){
						$tipPlanStatus = true;
					}

					// Send notification when post new tip
					$users = DB::table('users')
							->join('user_devices', 'users.id', '=', 'user_devices.user_id')
							->select('users.*', 'user_devices.device_token')
							->where('users.plan_id', $key)
							//->groupBy('user_devices.user_id')
							->orderBy('user_devices.id', 'desc')
							->get()
							->unique('user_devices.user_id');

					if(count($users)) {
						foreach($users as $user)
						{
							$this->sendPushNotification($user->device_token, 'Tips Mandi', $tipName.' is updated in '.$segmentDetail->segment, 'Notification');

							DB::table('notifications')->insert([
								'user_id' => $user->id,
								'content' => $tipName.' is updated in '.$segmentDetail->segment,
							]);
						}
					}

					$addTipPlan= new TipPlan;
					$addTipPlan->tip_id =  $id;
					$addTipPlan->plan_id = $key;
					$addTipPlan->is_status = $tipPlanStatus;
					$addTipPlan->save();

				}
			}
           /* $plans = $request->plans;


            $this->tblTipPlan::where(['tip_id' => $tipId])->delete();

            if(is_array($plans)) {
                $this->tblTip->createPlan($plans, $tipId);
            }*/

            //Update & delete tip target detail.
            $targets_id_array = $request->targets_id;
            $update_tip_targets_id = $request->update_tip_targets_id;
            $update_target_names = $request->update_target_names;
            $update_target_prices = $request->update_target_prices;
            $update_achieved = $request->update_achieved;

            if(is_array($targets_id_array)) {

                foreach ($targets_id_array as $targets_id_key => $targets_id_value) {

                    if(!empty($update_tip_targets_id[$targets_id_key]) && !empty($update_target_names[$targets_id_key]) && !empty($update_target_prices[$targets_id_key])) {

                        if(!empty($update_achieved[$targets_id_key])) {
                            $update_is_achieved = true;
                        }else{
                            $update_is_achieved = false;
                        }

                        $this->tblTipsTarget::where('id', $targets_id_value)->update([
                            'name' => $update_target_names[$targets_id_key],
                            'price' => $update_target_prices[$targets_id_key],
                            'is_achieved' => $update_is_achieved,
                            'updated_by' => self::adminId(),
                        ]);

                    }else{

                        $this->tblTipsTarget::where('id', $targets_id_value)->delete();

                    }
                }
            }

            //Add tips targets detail
            $target_names = $request->target_names;
            $target_prices = $request->target_prices;
            $target_achieved = $request->target_achieved;

            if(is_array($target_names) && is_array($target_prices)) {

                foreach ($target_names as $target_name_key => $target_name_value) {


                    if(!empty($target_prices[$target_name_key]) && !empty($target_name_value)) {


                        //dd($target_name_key, $target_name_value);
                        if(!empty($target_achieved[$target_name_key])) {
                            $is_achieved = true;
                        }else{
                          $is_achieved = false;
                        }

                        TipsTarget::create([
                            'tip_id' => $tipId,
                            'name' => $target_name_value,
                            'price' => $target_prices[$target_name_key],
                            'is_achieved' => $is_achieved,
                            'created_by' => self::adminId(),
                            'updated_by' => self::adminId(),
                        ]);
                    }
                }
            }

            $this->ncdInvesmentTbl::where('tip_id', $tipId)->delete();
            //Add ncd invesment detail
            $ncdInvestment = $request->ncd_investment;
            $ncdDuration = $request->ncd_duration;
            $ncdMaturityAmount = $request->ncd_maturity_amount;

            if(is_array($ncdInvestment) && is_array($ncdDuration) && is_array($ncdMaturityAmount)) {

                foreach ($ncdInvestment as $ncdInvestmentKey => $ncdInvestmentValue) {

                    if(!empty($ncdInvestmentValue) && !empty($ncdDuration[$ncdInvestmentKey]) && !empty($ncdMaturityAmount[$ncdInvestmentKey])) {

                        $this->ncdInvesmentTbl::create([
                            'tip_id' => $tipId,
                            'investment' => $ncdInvestmentValue,
                            'duration' => $ncdDuration[$ncdInvestmentKey],
                            'maturity_amount' => $ncdMaturityAmount[$ncdInvestmentKey]
                        ]);
                    }
                }
            }
            return redirect()->route('admin.tips.index')->with('success', 'Tips detail updated successfully.');


        //} catch (Exception $e) {
            //return redirect()->back()->with('error', $e->getMessage());
        //}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'manage-tips', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }

        $plan = $this->tipFindById($id);
        $plan->delete();

        return redirect()->route('admin.tips.index')->with('success', 'Tips detail deleted successfully.');
    }

    public function getPlansSegment(Request $request)
    {
        $output = array('success' => '', 'error' => '', 'data' => '');

        if($request->ajax()) {

            $segments = $request->segments;

            $html = '';

            $plans = $this->planSegmentTbl::where('name', $segments)->get();

            if(count($plans)) {

                foreach ($plans as $plan) {

                    $planDetail = $this->planTbl::whereId($plan->plan_id)->select('id', 'name')->first();

                    $html .= '<tr>
                                <td>
                                    <input type="text" name="tipsplans[plan_name]['.$planDetail->id.']" class="form-control" value="'.$planDetail->name.'" readonly />
                                    <input type="hidden" class="form-control" name="tipsplans[plan_id]['.$planDetail->id.']" value="'.$planDetail->id.'" readonly />
                                </td>
                                <td>
                                    <label class="m-checkbox col-lg-2">
                                        <input type="checkbox" name="tipsplans[plan_status]['.$planDetail->id.']" value="1">
                                        <span></span>
                                    </label>
                                </td>
                            </tr>';
                }
            }

            $output['data'] = $html;

            return $this->outputJSON($output);
        }

        //Ajax request type is invalid
        return $this->invalidajaxRequest();
    }

    public function getTipsSegments(Request $request)
    {
        $output = array('success' => '', 'error' => '', 'data' => '');

        if($request->ajax()) {

            $segments = $request->segments;

            $html = '';

            if(is_array($segments)) {

                //select * from `plan_segments` where (`name` = 'Delivery' or `name` = 'Intraday' or `name` = 'Commodity' or `name` = 'Boolean' or `name` = 'IPOs' or `name` = 'FDs') group by `plan_id`
                $plans = $this->planSegmentTbl::where(function ($query) use ($segments) {
                                    foreach($segments as $row_segment) {
                                        //$query->orWhere('name', 'like', "%$row_segment%");
                                        //$query->orWhere('name', "'$row_segment'");
                                        $query->orWhere('name', $row_segment);
                                    };
                               })->groupBy('plan_id')->get();
                //$Delivery = 'Delivery';
                //$plans = $this->planSegmentTbl::where('name', $Delivery)->groupBy('plan_id')->get();

                if(count($plans)) {
                    foreach ($plans as $plan) {
                        $planDetail = $this->planTbl::whereId($plan->plan_id)->select('id', 'name')->first();
                        $html .= '<tr>
                                    <td>
                                        <input type="text" class="form-control" value="'.$planDetail->name.'" readonly />
                                        <input type="hidden" class="form-control" name="plans[]" value="'.$planDetail->id.'" readonly />
                                    </td>
                                </tr>';
                    }
                }

            }

            $output['data'] = $html;

            return $this->outputJSON($output);
        }

        //Ajax request type is invalid
        return $this->invalidajaxRequest();
    }



    private function adminId()
    {
        return Auth::guard('admin')->user()->id;
    }

    /**
     *
     * Tip detail find by id.
     */
    public function tipFindById($id)
    {
        return  $this->tblTip::findorFail($id);
    }


    public function addTipPlan($tipsplans, $tipId)
    {

        TipPlan::where('tip_id', $tipId)->delete();
		
		
		//Get Segment detail.
		$segmentDetail = Tip::select('segment')->where('id', $tipId)->first();
		
        $tipsplans = unserialize($tipsplans);

        if(!empty($tipsplans['plan_id'])) {

            foreach ($tipsplans['plan_id'] as $key=> $value) {

                $tipPlanStatus = false;

                if(isset($tipsplans['plan_status'][$key])){
                    $tipPlanStatus = true;
                }
				
				// Send notification when post new tip
				$users = DB::table('users')
						->join('user_devices', 'users.id', '=', 'user_devices.user_id')
						->select('users.*', 'user_devices.device_token')
						->where('users.plan_id', $key)
						//->groupBy('user_devices.user_id')
						->orderBy('user_devices.id', 'desc')
						->get()
						->unique('user_devices.user_id');
				
				if(count($users)) {
					foreach($users as $user)
					{
						$this->sendPushNotification($user->device_token, 'Tips Mandi', 'New tips posted in '.$segmentDetail->segment, 'Notification');

						DB::table('notifications')->insert([
							'user_id' => $user->id,
							'content' => 'New tips posted in '.$segmentDetail->segment,
						]);
					}
				}
				
                $addTipPlan= new TipPlan;
                $addTipPlan->tip_id =  $tipId;
                $addTipPlan->plan_id = $key;
                $addTipPlan->is_status = $tipPlanStatus;
                $addTipPlan->save();

            }
        }
    }
}
