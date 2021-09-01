<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipNcdInvesment extends Model
{
    /**
     * @var string
     */
    protected $table = 'tip_ncd_invesments';

    /**
     * @var array
     */
    protected $fillable = ['tip_id', 'investment', 'duration', 'maturity_amount'];
}
