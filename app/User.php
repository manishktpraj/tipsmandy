<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'plan_id', 'user_id', 'name', 'email', 'password', 'avatar', 'gender', 'phone_no', 'address', 'facebook_user_id', 'google_user_id', 'is_status','referral_code', 'last_login_at','last_login_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function getExportMembers()
    {

        $userss = DB::table('users')
            ->leftJoin('user_plans', 'users.id', '=', 'user_plans.user_id')
            ->leftJoin('plans', 'users.plan_id', '=', 'plans.id')
            ->select('users.user_id', 'users.name', 'users.email','users.phone_no', 'plans.name as plan', 'user_plans.plan_expiry_date')
            ->get()->toArray();

        $users = User::get();


        $array = array();

        foreach ($users as $key => $user)
        {
            $array[$key]['user_id'] = $user->user_id;
            $array[$key]['name'] = $user->name;
            $array[$key]['email'] = $user->email;
            $array[$key]['phone_no'] = $user->phone_no;
            $array[$key]['plan'] = $user->plan->name ?? '';
            $array[$key]['plan_expiry_date'] = $user->userplanDetail->plan_expiry_date ?? '';
        }

        //return $userss;
        return json_decode(json_encode($array));
    }

    /**
     * Get the plan detail.
     */
    public function plan()
    {
        //return $this->belongsTo('App\Models\Plan', 'plan_id')->withDefault([
        return $this->belongsTo('App\Models\Plan', 'plan_id')->withDefault();
    }

    /**
     * Get the user plan detail.
     */
    public function userplanDetail()
    {
        return $this->hasOne('App\Models\UserPlan', 'user_id');
    }

    /**
     * Get the user device detail
     */
    public function userdevice()
    {
        return $this->hasOne('App\Models\UserDevice', 'user_id');
    }
}
