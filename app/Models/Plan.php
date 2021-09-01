<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Plan extends Model
{
    /**
     * @var string
     */
    protected $table = 'plans';

    /**
     * @var array
     */
    protected $fillable = ['name', 'daily_tips_limit', 'price', 'content'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planfeatureds()
    {
        return $this->hasMany(PlanFeatured::class);
    }

	/**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function apiPlanFeatureds()
    {
        return $this->hasMany(PlanFeatured::class);
    }
	
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planprices()
    {
        return $this->hasMany(PlanPrice::class);
    }
	
	public function apiPlanPrices()
    {
        return $this->hasMany(PlanPrice::class)->orderBy('plan_month', 'asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userplans()
    {
        return $this->hasMany(UserPlan::class, 'plan_id');
    }
    /**
     *
     * Get plan price detail.
     */
    public function getPlanPriceDetail($planId, $month)
    {
        return PlanPrice::where(['plan_id' => $planId, 'plan_month' => $month])->first(['id', 'price', 'regular_price']);
    }

    /**
     *
     * Get plan price detail.
     */
    public function getPlanSegmentDetail($planId, $name)
    {
        return PlanSegment::where(['plan_id' => $planId, 'name' => $name, 'type' => false])->count();
    }

    /**
     *
     * Get plan price detail.
     */
    public function getMoreplansegments($planId)
    {
        return PlanSegment::where(['plan_id' => $planId, 'type' => true])->get();
    }

    public function segments()
    {
        return collect(['Delivery', 'Intraday', 'Future', 'Option', 'Currency', 'Commodity', 'Boolean', 'NCD', 'IPOs', 'FDs', 'MF']);
    }

}
