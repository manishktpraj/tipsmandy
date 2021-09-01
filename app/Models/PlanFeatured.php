<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanFeatured extends Model
{
    /**
     * @var string
     */
    protected $table = 'plan_featureds';

    /**
     * @var array
     */
    protected $fillable = ['plan_id', 'name', 'image'];
}
