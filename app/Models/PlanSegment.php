<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanSegment extends Model
{
    /**
     * @var string
     */
    protected $table = 'plan_segments';

    /**
     * @var array
     */
    protected $fillable = ['plan_id', 'name', 'type'];
}
