<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\User;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;
use App\Models\UserDevice;
use Carbon\Carbon;
use Exception;
use Helper;
use App\Traits\FcmTrait;

class FcmpushnotificationsController extends Controller
{
    use FcmTrait;

    const PLANEXPIREDMESSAGE = 'Your plan expired.';

    /**
     * When Plan about to expire before 7 days , 2 Days , 1 Day.
     *
     * @return \Illuminate\Http\Response
     */
    public function planexpiredNotification()
    {
        //$todayDate  = Carbon::today()->format('Y-m-d');
        $todayDate  = Carbon::now();

        // add 30 days to the current time
        //$trialExpires = $todayDate->addDays(30);
        //echo $trialExpires;
        //exit();

        //$sender = urlencode('TXTLCL');
        //echo $message = rawurlencode('This is your message');
        //SELECT * FROM `user_plans` WHERE `plan_expiry_date` = DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        //SELECT * FROM `user_plans` WHERE `plan_expiry_date` = DATE_ADD(CURDATE(), INTERVAL 1 WEEK)
        //isset($arrdata->product_sku) ? $arrdata->product_sku : ''
        $users = DB::table('users')
                ->join('user_plans', 'users.id', '=', 'user_plans.user_id')
                ->select('user_plans.*', 'users.id as id_user')
                ->whereDate('user_plans.plan_expiry_date', '<=', Carbon::now()->subDays(7))
                ->toSql();

        return $users;
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
