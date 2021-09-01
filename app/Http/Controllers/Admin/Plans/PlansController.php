<?php

namespace App\Http\Controllers\Admin\Plans;

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
use App\Http\Requests\Admin\Plans\StorePlan;
use App\Http\Requests\Admin\Plans\UpdatePlan;
use App\Traits\Admin\FormButtons;
use App\Traits\UploadTrait;
use App\Traits\Admin\AuthorizationsTrait;
use Exception;
use Helper;

class PlansController extends Controller
{
    use FormButtons, UploadTrait, AuthorizationsTrait;

    protected $tbl;

    protected $planPriceTbl;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->tbl = new Plan;
        $this->planPriceTbl = new PlanPrice;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_read'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Plans', 'Manage Plans');

        $renderButtons = $this->addButtons(route('admin.plans.create'), 'plans', 'Add Plan');

        return view('admin.plans.index', compact('renderButtons'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function plans(Request $request)
    {
        if(request()->ajax()) {

            ## Read value
            $draw = request('draw');

            $start = request('start');

            $rowperpage = request('length'); // Rows display per page

            $columnIndex = request('order.0.column'); // Column index

            //$columnName = request('columns')[$columnIndex]['data']; // Column name

            $columnSortOrder = request('order.0.dir'); // asc or desc

            $searchValue = request('search.value'); // Search value

            $planTbl = $this->tbl;

            ## Total number of records without filtering
            $totalRecords = $planTbl->count();

            ## Total number of record with filtering
            $totalRecordwithFilter = $planTbl->count();

            ## Fetch records
            $plans = $planTbl->offset($start)
                            ->limit($rowperpage)
                            //->orderBy($columnName, $columnSortOrder)
                            ->latest()
                            ->get(['id', 'name', 'price', 'content', 'is_status']);

            $data = array();

            $i = $start;

            foreach($plans as $key => $row) {

                $i++;

                //Check authorization
                $actions = '';
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_edit'))
                {
                    $actions .= Helper::getButtons([['key' => 'Edit', 'link' => route('admin.plans.edit', $row->id)]]);
                }
                if($this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_delete'))
                {
                    $actions .= Helper::getButtons([['key' => 'Delete', 'link' => route('admin.plans.delete', [$row->id])]]);
                }

                /*$actions = Helper::getButtons([
                                ['key' => 'Edit', 'link' => route('admin.plans.edit', $row->id)],
                                ['key' => 'Delete', 'link' => route('admin.plans.delete', [$row->id])]
                            ]);*/

                $data[$key]['id'] = $i;
                $data[$key]['name'] = $row->name;
                //$data[$key]['price'] = $row->price;
                //$data[$key]['content'] = $row->content;
                $data[$key]['status'] = Helper::getStatus($row->is_status, $row->id, '_planStatus');
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
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_add'))
        {
            return abort(403);
        }

        $this->setPageTitle('Manage Plans', 'Create Plan');

        $renderButtons = $this->addFormButtons(route('admin.plans.index'));

        $backButtons = $this->backButtons(route('admin.plans.index'));

        $segments = $this->tbl->segments();

        return view('admin.plans.create', compact('renderButtons', 'backButtons', 'segments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePlan $request)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_add'))
        {
            return abort(403);
        }

        try {

            $data['name'] = $request->name ? : NULL;
            $data['price'] = $request->price ? : NULL;
            $data['content'] = $request->content ? : NULL;
            $data['daily_tips_limit'] = $request->daily_tips_limit ? : NULL;

            $create = $this->tbl::create($data);

            if($create) {

                //Add plan price detail start
                $plan_price = $request->plan_price;
                $regular_price = $request->regular_price;
                $plan_months = $request->months;

                if(is_array($plan_price) && is_array($plan_months)) {

                    foreach ($plan_price as $plan_price_key => $plan_price_value) {

                        if(!empty($plan_months[$plan_price_key]) && !empty($plan_price_value) && !empty($regular_price[$plan_price_key])) {

                            PlanPrice::create([
                                'plan_id' => $create->id,
                                'plan_month' => $plan_months[$plan_price_key],
                                'price' => $plan_price_value,
                                'regular_price' => $regular_price[$plan_price_key]
                            ]);
                        }
                    }
                }
                //Add plan price detail end

                //Add plan featured detail start
                $featured_name = $request->featured_name;
                $featured_images = $request->file('featured_images');

                if($request->hasFile('featured_images')){

                    $uplode_image_path = public_path('uploads/plan_featured');

                    foreach ($request->file('featured_images') as $fileKey => $fileObject ) {

                        // make sure each file is valid
                        if ($fileObject->isValid()) {

                            $photo_img  = $featured_images[$fileKey] ?? '';

                            if(!empty($photo_img) && !empty($featured_name[$fileKey])) {

                                $image_name =  $this->uploadOne($photo_img, $uplode_image_path);

                                PlanFeatured::create([
                                    'plan_id' => $create->id,
                                    'name' => $featured_name[$fileKey],
                                    'image' => $image_name
                                ]);
                            }
                        }
                    }
                }
                //Add plan featured detail end


                //Add plan segments detail start
                $segments = $request->segments;
                $segmentsArray = $request->segmentsArray;

                if(is_array($segments)) {

                    foreach ($segments as $segments_key => $segments_value) {

                        if(!empty($segments_value)) {

                            PlanSegment::create([
                                'plan_id' => $create->id,
                                'name' => $segments_value,
                                'type' => false
                            ]);
                        }
                    }
                }

                //Add more segments list
                if(is_array($segmentsArray)) {

                    foreach ($segmentsArray as $segmentsArray_key => $segmentsArray_value) {

                        if(!empty($segmentsArray_value)) {

                            PlanSegment::create([
                                'plan_id' => $create->id,
                                'name' => $segmentsArray_value,
                                'type' => true
                            ]);
                        }
                    }
                }
                //Add plan segments detail end

                return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully.');

            }else{
                return redirect()->back()->with('error', 'Oops. Something went wrong. Please try again.');
            }

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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_edit'))
        {
            return abort(403);
        }

        $plan = $this->planFindById($id);

        $this->setPageTitle('Manage Plans', 'Edit Plan');

        $renderButtons = $this->editFormButtons(route('admin.plans.index'));

        $backButtons = $this->backButtons(route('admin.plans.index'));

        $segments = $this->tbl->segments();

        return view('admin.plans.edit', compact('plan', 'renderButtons', 'backButtons', 'segments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePlan $request, $id)
    {
        //Check authorization
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_edit'))
        {
            return abort(403);
        }

        try {

            $data['name'] = $request->name ? : NULL;
            //$data['price'] = $request->price ? : NULL;
            //$data['content'] = $request->content ? : NULL;
            $data['daily_tips_limit'] = $request->daily_tips_limit ? : NULL;

            $this->tbl::where('id', $id)->update($data);
            //Get plan id
            $plan_id = $id;


            //Update & delete plan price detail.
            $plan_price_id_a = $request->plan_price_id_a;
            $plan_price_id_b = $request->plan_price_id_b;
            $plan_price_id_c = $request->plan_price_id_c;
            $plan_price_id_d = $request->plan_price_id_d;
            $update_plan_price_a = $request->update_plan_price_a;
            $update_plan_price_b = $request->update_plan_price_b;
            $update_plan_price_c = $request->update_plan_price_c;
            $update_plan_price_d = $request->update_plan_price_d;

            //Update plan price a
            if(!empty($update_plan_price_a) && !empty($plan_price_id_a)) {
                $this->planPriceTbl::where('id', $plan_price_id_a)->update(['price' => $update_plan_price_a]);
            }else{
                $this->planPriceTbl::where('id', $plan_price_id_a)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price b
            if(!empty($update_plan_price_b) && !empty($plan_price_id_b)) {
                $this->planPriceTbl::where('id', $plan_price_id_b)->update(['price' => $update_plan_price_b]);
            }else{
                $this->planPriceTbl::where('id', $plan_price_id_b)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price c
            if(!empty($update_plan_price_c) && !empty($plan_price_id_c)) {
                $this->planPriceTbl::where('id', $plan_price_id_c)->update(['price' => $update_plan_price_c]);
            }else{
                $this->planPriceTbl::where('id', $plan_price_id_c)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price d
            if(!empty($update_plan_price_d) && !empty($plan_price_id_d)) {
                $this->planPriceTbl::where('id', $plan_price_id_d)->update(['price' => $update_plan_price_d]);
            }else{
                $this->planPriceTbl::where('id', $plan_price_id_d)->where('plan_id', $plan_id)->delete();
            }

            $plan_price_a = $request->plan_price_a;
            $plan_price_b = $request->plan_price_b;
            $plan_price_c = $request->plan_price_c;
            $plan_price_d = $request->plan_price_d;
			$regular_price_a = $request->regular_price_a ? : NULL;
            $regular_price_b = $request->regular_price_b ? : NULL;
            $regular_price_c = $request->regular_price_c ? : NULL;
            $regular_price_d = $request->regular_price_d ? : NULL;
            if($plan_price_a) {
                $this->planPriceTbl::create([
                    'plan_id' => $plan_id,
                    'plan_month' => $request->month_a,
                    'price' => $plan_price_a,
                    'regular_price' => $regular_price_a
                ]);
            }
            if($plan_price_b) {
                $this->planPriceTbl::create([
                    'plan_id' => $plan_id,
                    'plan_month' => $request->month_b,
                    'price' => $plan_price_b,
                    'regular_price' => $regular_price_b
                ]);
            }
            if($plan_price_c) {
                $this->planPriceTbl::create([
                    'plan_id' => $plan_id,
                    'plan_month' => $request->month_c,
                    'price' => $plan_price_c,
                    'regular_price' => $regular_price_c
                ]);
            }
            if($plan_price_d) {
                $this->planPriceTbl::create([
                    'plan_id' => $plan_id,
                    'plan_month' => $request->month_d,
                    'price' => $plan_price_d,
                    'regular_price' => $regular_price_d
                ]);
            }
            //End plan price add & edit & delete.
			
			//Update & delete regular plan price detail.
            $regular_price_id_a = $request->regular_price_id_a;
            $regular_price_id_b = $request->regular_price_id_b;
            $regular_price_id_c = $request->regular_price_id_c;
            $regular_price_id_d = $request->regular_price_id_d;
            $update_regular_price_a = $request->update_regular_price_a;
            $update_regular_price_b = $request->update_regular_price_b;
            $update_regular_price_c = $request->update_regular_price_c;
            $update_regular_price_d = $request->update_regular_price_d;

            //Update plan price a
            if(!empty($update_regular_price_a) && !empty($regular_price_id_a)) {
                $this->planPriceTbl::where('id', $regular_price_id_a)->update(['regular_price' => $update_regular_price_a]);
            }else{
                $this->planPriceTbl::where('id', $regular_price_id_a)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price b
            if(!empty($update_regular_price_b) && !empty($regular_price_id_b)) {
                $this->planPriceTbl::where('id', $regular_price_id_b)->update(['regular_price' => $update_regular_price_b]);
            }else{
                $this->planPriceTbl::where('id', $regular_price_id_b)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price c
            if(!empty($update_regular_price_c) && !empty($regular_price_id_c)) {
                $this->planPriceTbl::where('id', $regular_price_id_c)->update(['regular_price' => $update_regular_price_c]);
            }else{
                $this->planPriceTbl::where('id', $regular_price_id_c)->where('plan_id', $plan_id)->delete();
            }

            //Update plan price d
            if(!empty($update_regular_price_d) && !empty($regular_price_id_d)) {
                $this->planPriceTbl::where('id', $regular_price_id_d)->update(['regular_price' => $update_regular_price_d]);
            }else{
                $this->planPriceTbl::where('id', $regular_price_id_d)->where('plan_id', $plan_id)->delete();
            }            
            //End plan regular price add & edit & delete.

            //Add plan segments detail start
            $segments = $request->segments;

            PlanSegment::where(['plan_id' => $plan_id, 'type' => false])->delete();

            if(is_array($segments)) {

                foreach ($segments as $segments_key => $segments_value) {

                    if(!empty($segments_value)) {

                        PlanSegment::create([
                            'plan_id' => $plan_id,
                            'name' => $segments_value,
                            'type' => false
                        ]);
                    }
                }
            }

            $segmentsArray = $request->segmentsArray;
            $segmentsArrays = $request->segmentsArrays;
            $segments_value_id = $request->segments_value_id;
            $segments_id = $request->segments_id;

            //Update & delete more segment detail
            if(is_array($segments_value_id)) {

                foreach ($segments_value_id as $segments_value_id_key => $segments_value_id_value) {

                    if(!empty($segments_id[$segments_value_id_key]) &&  !empty($segmentsArrays[$segments_value_id_key])) {

                        PlanSegment::where('id', $segments_value_id_value)->update([
                            'name' => $segmentsArrays[$segments_value_id_key]
                        ]);

                    }else{
                        PlanSegment::where('id', $segments_value_id_value)->delete();
                    }
                }
            }

            //Add more segments list
            if(is_array($segmentsArray)) {

                foreach ($segmentsArray as $segmentsArray_key => $segmentsArray_value) {

                    if(!empty($segmentsArray_value)) {

                        PlanSegment::create([
                            'plan_id' => $plan_id,
                            'name' => $segmentsArray_value,
                            'type' => true
                        ]);
                    }
                }
            }
            //Add plan segments detail end

            $update_featured_name = $request->update_featured_name;
            $plan_featureds_value_id = $request->plan_featureds_value_id;
            $plan_featureds_id = $request->plan_featureds_id;
            $update_featured_images = $request->file('update_featured_images');

            $uplode_image_path = public_path('uploads/plan_featured');

            //Update & delete more segment detail
            if(is_array($plan_featureds_value_id)) {

                foreach ($plan_featureds_value_id as $plan_featureds_value_id_key => $segments_value_id_value) {

                    if(!empty($plan_featureds_id[$plan_featureds_value_id_key]) &&  !empty($update_featured_name[$plan_featureds_value_id_key])) {

                            $update_photo_img  = $update_featured_images[$segments_value_id_value] ?? '';
                            //Get plan featured detail.
                            $planFeaturedDetail = PlanFeatured::where('id', $segments_value_id_value)->first();

                            $update_image_name = $planFeaturedDetail->image ? : NULL;

                            if(!empty($update_photo_img)) {
                                //Unlik file
                                if(!empty($planFeaturedDetail->image) && File::exists($uplode_image_path.'/'.$planFeaturedDetail->image)){

                                    unlink($uplode_image_path.'/'.$planFeaturedDetail->image);
                                }
                                $update_image_name =  $this->uploadOne($update_photo_img, $uplode_image_path);
                            }

                        PlanFeatured::where('id', $segments_value_id_value)->update([
                            'name' => $update_featured_name[$plan_featureds_value_id_key],
                            'image' => $update_image_name
                        ]);

                    }else{
                        PlanFeatured::where('id', $segments_value_id_value)->delete();
                    }
                }
            }

            //Add plan featured detail start
            $featured_name = $request->featured_name;
            $featured_images = $request->file('featured_images');

            if($request->hasFile('featured_images')){

                $uplode_image_path = public_path('uploads/plan_featured');

                foreach ($request->file('featured_images') as $fileKey => $fileObject ) {

                    // make sure each file is valid
                    if ($fileObject->isValid()) {

                        $photo_img  = $featured_images[$fileKey] ?? '';

                        if(!empty($photo_img) && !empty($featured_name[$fileKey])) {

                            $image_name =  $this->uploadOne($photo_img, $uplode_image_path);

                            PlanFeatured::create([
                                'plan_id' => $plan_id,
                                'name' => $featured_name[$fileKey],
                                'image' => $image_name
                            ]);
                        }
                    }
                }
            }
            //Add plan featured detail end


            return redirect()->route('admin.plans.index')->with('success', 'Plan detail updated successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }
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
        if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_delete'))
        {
            return redirect()->route('admin.dashboard')->with('warning', 'You are not authorised for this action.');
        }
        $plan = $this->planFindById($id);
        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan detail deleted successfully.');

    }

    /**
     *
     * Plan detail find by id.
     */
    public function planFindById($id)
    {
        return  $this->tbl::findorFail($id);
    }


    /**
     * Vehicles make featured or unfeatured
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $output = array('success' => '', 'error' => '', 'class' => '');

        if($request->ajax()) {

            //Check authorization
            if(!$this->checkPermission(Auth::guard('admin')->user()->is_role, 'plans', 'is_status'))
            {
                $output['error'] = 'You are not authorised for this action.';

                $plan = $this->tbl::where('id', $request->plan_id)->first(['id', 'is_status']);

                $output['class'] = 'm-badge--danger';
                $output['text'] = 'Inactive';

                if($plan->is_status) {
                    $output['class'] = 'm-badge--success';
                    $output['text'] = 'Active';
                }

                return $this->outputJSON($output);
            }

            $plan = $this->tbl::where('id', $request->plan_id)->first(['id', 'is_status']);

            if($plan) {

                $status = true;
                $output['class'] = 'm-badge--success';
                $output['text'] = 'Active';

                if($plan->is_status) {
                    $status = false;
                    $output['class'] = 'm-badge--danger';
                    $output['text'] = 'Inactive';
                }

                $plan->is_status = $status;
                $plan->update();

                $output['success'] = 'Status updated successfully.';

            }else {
                $output['error'] = 'Error occurred while updating status. Please try again later.';
            }

            return $this->outputJSON($output);
        }
        //Ajax request type is invalid
        return $this->invalidajaxRequest();
    }



    public function deletePlanPrice($plan_price_id, $plan_id)
    {
        return PlanPrice::where('id', $plan_price_id)->where('plan_id', $plan_id)->delete();
    }
}
