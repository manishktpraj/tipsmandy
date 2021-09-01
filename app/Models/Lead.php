<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    /**
     * @var string
     */
    protected $table = 'leads';

    /**
     * @var array
     */
    protected $fillable = ['tip_id', 'user_id', 'email', 'mobile_no', 'segment'];
}
