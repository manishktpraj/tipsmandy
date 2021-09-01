<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_plans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $guarded  = [];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'plan_id', 'plan_duration', 'price', 'plan_expiry_date', 'plan_start_date', 'plan_information'
    ];
}
