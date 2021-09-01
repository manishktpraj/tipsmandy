<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\Rules\Admin\MatchOldPassword;
use Carbon\Carbon;
use Exception;
use App\Admin;
use App\User;
use App\Models\Plan;
use App\Models\UserPlan;
use App\Models\PlanFeatured;
use App\Models\PlanPrice;
use App\Models\PlanSegment;
use App\Models\Tip;
use App\Models\TipPlan;
use App\Models\TipSegment;
use App\Models\TipsTarget;
use App\Models\Source;
use App\Traits\UploadTrait;

class HomeController extends Controller
{
    use UploadTrait;

    protected $tblTip;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->tblTip = new Tip;
    }

    /**
     * Show Admin Dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->setPageTitle('Dashboard', 'Dashboard');
        $currentDate = Carbon::now();

        //echo $currentMonth = $currentDate->format('m');
        //echo Carbon::now()->daysInMonth;
        //echo date("t", mktime(0,0,0, date("n")));

        //exit();
        //Tips Added Day Wise
        //dd(Carbon::now()->format('Y'));
        //$data['daywiseTips'] = $this->tblTip::whereMonth('created_at', date('m'))->get();
        //$data['daywiseTips'] = $this->tblTip::whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->get();
        $data['daywiseTips'] = $this->tblTip::whereMonth('created_at', 6)->whereYear('created_at', Carbon::now()->format('Y'))->get();
        //dd($this->tblTip::whereMonth('created_at', 6)->get());
        $data['totalTodayTips'] = $this->tblTip::whereDate('created_at', Carbon::today())->count();

        $data['totalTips'] = $this->tblTip::count();

        //$data['tipsAchived'] = TipsTarget::whereIsAchieved(true)->whereDate('updated_at', Carbon::today())->count();

        $data['tipsAchived'] = TipsTarget::whereIsAchieved(true)->count();

        $data['totalPlans'] = Plan::count();

        $data['totalStaffMembers'] = Admin::where('is_role', '!=', false)->count();

        $data['totalMembers'] = User::count();

        $data['sources'] = Source::latest()->get(['id', 'name']);

        //$plans = Plan::select('name as plan_name', 'id')->get();
        $plans = Plan::withCount(['userplans'])->get();

        $plansArray = '';
        $plansSoldArray = '';

        if(count($plans)) {
            foreach ($plans as $plan) {
                $plansArray .= "'$plan->name'".',';
                $plansSoldArray .= $plan->userplans_count.',';
            }
        }

        $data['plansArray'] = rtrim($plansArray, ',');

        $data['plansSoldArray'] = rtrim($plansSoldArray, ',');

        $totalRe = UserPlan::select('plan_id', DB::raw('SUM(price) as total_revenue'))->groupBy('plan_id')->get();

        //Total Revenue End
        $r_start_date = $request->query('r_start_date'); //
        $r_end_date = $request->query('r_end_date'); //
        $datearr = [];
        if(!empty($r_end_date) && !empty($r_start_date)) {
            $datearr['start'] = $r_end_date;
            $datearr['end'] = date('Y-m-d', strtotime($r_end_date. ' + 1 day'));
        }
        /*if(!empty($r_end_date) && !empty($r_start_date)) {
            $revenueGraphs = Plan::leftJoin('user_plans', 'plans.id', '=', 'user_plans.plan_id')->select('user_plans.plan_id', 'user_plans.price', DB::raw('SUM(user_plans.price) as total_revenue'))->whereBetween('user_plans.created_at', [$r_start_date, $r_end_date])->groupBy('user_plans.plan_id')->toSql();
            //dd($revenueGraphs);
        }else{*/
            $revenueGraphs = Plan::leftJoin('user_plans', 'plans.id', '=', 'user_plans.plan_id')->select('user_plans.plan_id', DB::raw('SUM(user_plans.price) as total_revenue'))->when($datearr, function ($query, $datearr) {
                            //return $query->whereBetween('user_plans.created_at', [$datearr['start'], $datearr['end']);
                            return $query->whereDate('user_plans.created_at','>=',$datearr['start'])->whereDate('user_plans.created_at','<=',$datearr['end']);
                            })->groupBy('user_plans.plan_id')->get();
        //}
        //dd($revenueGraphs);
        $revenuesArray = '';
        //$total_revenue = 0;
        if(count($revenueGraphs)) {
            foreach ($revenueGraphs as $revenueGraph) {
                $revenuesArray .= $revenueGraph->total_revenue.',';
                //$revenuesArray .= $revenueGraph->price.',';
            }
        }
        /*if(count($plans)) {
            foreach($plans as $plan) {
                $userPlans = UserPlan::where('plan_id', $)
            }
        }*/
        //dd(rtrim($revenuesArray, ','));
        $data['revenuesArray'] = rtrim($revenuesArray, ',');
        //Total Revenue End

        $monthDays = '';

        $data['daysInMonth'] = Carbon::now()->daysInMonth;

        $planss =  Plan::leftJoin('user_plans', 'user_plans.plan_id', '=', 'plans.id')
                ->groupBy('plans.id')
                ->get(['plans.id', 'plans.name', DB::raw('count(user_plans.id) as planSold')]);

        $data['tips_targets'] = DB::table('tips_targets')->where('is_achieved', 1)->count();

        $data['plans'] = DB::table('plans')->get();

        return view('admin.dashboard', $data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {

        $profile = $this->adminProfile()->toArray();

        return view('admin.profile', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $profile = $this->adminProfile()->toArray();

        //Validation request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email,'.$profile['id'],
            'password' => 'nullable|string|min:8|confirmed',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png'
        ]);

        try {

            $avatar = $request->file('profile_image');

            $data['name'] = $request->name;
            $data['email'] = $request->email;
            if ($request->password != ''){
                $data['password'] = Hash::make($request->password);
            }

            if($request->has('profile_image')) {

                $image_path = public_path('uploads/avatar');

                $admin_avatar = $profile['avatar'];

                //Unlik old file
                if(!empty($admin_avatar) && File::exists($image_path.'/'.$admin_avatar)) {

                    unlink($image_path.'/'.$admin_avatar);
                }

                $data['avatar'] =  $this->uploadOne($avatar, public_path('uploads/avatar'));

            }

            Admin::where('id', $profile['id'])->update($data);

            return redirect()->back()->with('success', 'Profile updated successfully.');

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());

        }
    }
}
