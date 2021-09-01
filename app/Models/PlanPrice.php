<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPrice extends Model
{
    /**
     * @var string
     */
    protected $table = 'plan_prices';

    /**
     * @var array
     */
    protected $fillable = ['plan_id', 'plan_month', 'price', 'regular_price'];
}
