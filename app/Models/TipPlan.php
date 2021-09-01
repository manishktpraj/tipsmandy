<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipPlan extends Model
{
    /**
     * @var string
     */
    protected $table = 'tip_plans';

    /**
     * @var array
     */
    protected $fillable = ['tip_id', 'plan_id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function planDetail()
    {
        return $this->belongsTo(Plan::class, 'plan_id')->withDefault();
    }
}
