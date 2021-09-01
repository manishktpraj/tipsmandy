<?php

namespace App\Traits\Api;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\PlanPrice;
use App\Models\UserPlan;

/**
 * Trait UserPlan
 * @package App\Traits\Api
 */
trait UserPlanTrait
{
    
    public function userPlanDetail($user)
    {
    	$planArray['plan_name'] = $user->plan->name ?? "";
		$planArray['plan_duration'] = $user->userplanDetail->plan_duration ?? 0;
		$planArray['plan_price'] = $user->userplanDetail->price ?? 0;
		$planArray['plan_expiry_date'] = $user->userplanDetail->plan_expiry_date ?? "";
		if(isset($user->is_plan_status)) {
			$planArray['isPlanActivated'] = $user->is_plan_status ? true : false;
		}else{
			$planArray['isPlanActivated'] = false;
		}
		return $planArray;
    }
}
